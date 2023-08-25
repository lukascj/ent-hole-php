<?php
require_once("universal_functions.php");

function fetchRecent($conn, $user_id) { // model
    $stmt = mysqli_stmt_init($conn);
    $sql = "SELECT `to_id` FROM `follow` WHERE `from_id` = ?;";

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $following = [];
    while($id = mysqli_fetch_array($result, MYSQLI_NUM)[0]) {
        if(isset($id)) {
            array_push($following, $id);  
        }
    }
    mysqli_free_result($result);

    if(!isset($following) || count($following) === 0) {
        return [];
    }

    $num = count($following);
    $marks = "(".str_repeat("?, ", $num-1)."?)";

    $sql = "SELECT 
    `entries`.`user_id` AS `user_id`, 
    `users`.`uid` AS `user_uid`, 
    `users`.`name` AS `username`, 
    `entries`.`id` AS `entry_id`, 
    `entries`.`like` AS `like`, 
    `entries`.`rating` AS `rating`, 
    `entries`.`item_id` AS `item_id`, 
    `entries`.`log_date` AS `log_date`, 
    `entries`.`review_date` AS `review_date`, 
    IF(
        `review_date` IS NULL, `log_date`, IF(
            `log_date` IS NULL, `review_date`, IF(
                `log_date` >= `review_date`, `log_date`, `review_date`
            )
        )
    ) AS `date`, 
    `entries`.`text` AS `text`, 
    `entries`.`rewatch` AS `rewatch`, 
    `entries`.`spoilers` AS `spoilers`,  
    `types`.`uid` AS `item_type`, 
    `items`.`uid` AS `item_uid`, 
    `items`.`name` AS `item_name`, 
    `items`.`year` AS `item_year`
    FROM `entries` 
    INNER JOIN `items` ON `items`.`id` = `entries`.`item_id` 
    INNER JOIN `types` ON `types`.`id` = `items`.`type_id` 
    INNER JOIN `follow` ON `follow`.`to_id` = `entries`.`user_id` 
    INNER JOIN `users` ON `users`.`id` = `follow`.`to_id` 
    WHERE `follow`.`to_id` IN $marks 
    ORDER BY `date` DESC 
    LIMIT 19 
    ;";

    $param_str = str_repeat("i", $num);

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit();
    }    
    mysqli_stmt_bind_param($stmt, $param_str, ...$following);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $recent = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    if(!(count($recent) > 0)) {
        return [];
    }

    return $recent;
}

function renderListRecent($recent) { // view

    $list = '';
    foreach($recent as $r) {
        $list .= prepareActivityContainer($r['username'], $r['user_uid'], $r['entry_id'], $r['rating'], $r['date'], $r['rewatch'], $r['text'], $r['spoilers'], $r['item_name'], $r['item_uid'], $r['item_year'], $r['item_type'], 'list');
    }
    if(!(count($recent) > 0)) {
        $list .= 
        '<li class="special_container activity find_more">
        <a class="link find_more" href="/users">
        <p class="text">Find people to follow</p>
        <div class="plus"></div>
        </a>
        </li>';
    } else {
        $list .= 
        '<li class="special_container activity show_more">
        <a class="link show_more" href="/recent-activity">
        <p class="text">Show more</p>
        <div class="plus"></div>
        </a>
        </li>';
    }

    $html =
    '<section class="list_section horizontal" list-name="recent">
    <h2>Recent</h2>
    <div class="list_container">
    <div class="arrow l"></div>
    <div class="list_limits">
    <ul class="list">'.$list.'</ul>
    </div>
    <div class="arrow r"></div>
    </div>
    </section>';

    echo $html;
    return;
}

function fetchPopular($conn, $factor) { // model

    // validering
    if($factor !== 'week' && $factor !== 'month' && $factor !== 'all') {
        header("location: /?error");
        exit;
    }

    if($factor === 'all') {
        $tmp1 = '';
        $tmp2 = '';
    } elseif($factor === 'month') {
        $date = date('Y-m-d', strtotime('-1 month'));
        $tmp1 = "AND `ratings`.`created_date` > '$date'"; 
        $tmp2 = "AND `entries`.`log_date` > '$date'"; 
    } elseif($factor === 'week') {
        $date = date('Y-m-d', strtotime('-1 week'));
        $tmp1 = "AND `ratings`.`created_date` > '$date'"; 
        $tmp2 = "AND `entries`.`log_date` > '$date'"; 
    } else {
        header("location: /?error");
        exit;
    }

    $popularity = // todo: ändra så att om entry och rating är från samma tillfälle så räknas endast en av dem med
        "(SELECT COUNT(*) FROM `ratings` WHERE `ratings`.`item_id` = `items`.`id` $tmp1)
        + 
        (SELECT COUNT(*) FROM `entries` WHERE `entries`.`item_id` = `items`.`id` $tmp2)
    ";

    $sql = "SELECT 
    `items`.`id`, 
    `items`.`name`, 
    `items`.`year`, 
    `items`.`uid`, 
    ($popularity) AS `popularity`, 
    `types`.`uid` AS `type`
    FROM `items` 
    INNER JOIN `types` ON `types`.`id` = `items`.`type_id` 
    ORDER BY `popularity` DESC 
    LIMIT 19
    ;";

    $result = mysqli_query($conn, $sql);
    $popular = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $popular;
}

function renderListPopular($popular, $factor) { // view

    if($factor === 'week') {
        $defaultw = 'selected="selected"';
        $defaultm = '';
        $defaulta = '';
    } elseif($factor === 'month') {
        $defaultw = '';
        $defaultm = 'selected="selected"';
        $defaulta = '';
    } elseif($factor === 'all') {
        $defaultw = '';
        $defaultm = '';
        $defaulta = 'selected="selected"';
    }

    if(!(count($popular) > 0)) {
        return;
    }

    $list = '';
    foreach($popular as $p) {
        $list .= prepareItemContainer($p['name'], $p['uid'], $p['year'], $p['type'], 'list');
    }
    $list .= 
    '<li class="special_container item show_more">
    <a class="link show_more" href="/popular?by='.$factor.'">
    <p class="text">Show more</p>
    <div class="plus"></div>
    </a>
    </li>';

    $html =
    '<section class="list_section horizontal" list-name="popular">
    <h2>Popular</h2>
    <form method="get">
    <select name="popular">
    <option value="week" '.$defaultw.'>This week</option>
    <option value="all" '.$defaulta.'>All time</option>
    <option value="month" '.$defaultm.'>This month</option>
    </select>
    </form>
    <div class="list_container">
    <div class="arrow l"></div>
    <div class="list_limits">
    <ul class="list">
    '.$list.'
    </ul>
    </div>
    <div class="arrow r"></div>
    </div>
    </section>';

    echo $html;
    return;
}