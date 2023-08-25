<?php
require_once('universal_functions.php');

function fetchEntry($conn, $entry_id, $user_id) {

    // kollar om du likat den
    if(isset($user_id)) {
        $str = "SELECT COUNT(*) 
        FROM `review_likes` 
        WHERE `entry_id` = ? AND `user_id` = ? 
        LIMIT 1";

        $subsql = 
        ", 
        IF(`user_id` = ?, 1, 0) AS `yours`,
        (".$str.") AS `liked`";
    }

    $sql = "SELECT 
    `entries`.*,
    `users`.`name` AS `username`,
    `users`.`uid` AS `user_uid`,
    `items`.`name` AS `item_name`,
    `items`.`uid` AS `item_uid`,
    `items`.`year` AS `item_year`,
    `types`.`uid` AS `item_type`
    $subsql
    FROM `entries` 
    INNER JOIN `users` ON `entries`.`user_id` = `users`.`id`
    INNER JOIN `items` ON `entries`.`item_id` = `items`.`id`
    INNER JOIN `types` ON `items`.`type_id` = `types`.`id`
    WHERE `entries`.`id` = ? 
    LIMIT 1;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit;
    }

    if(isset($user_id)) {
        mysqli_stmt_bind_param($stmt, "iiii", $user_id, $entry_id, $user_id, $entry_id);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $entry_id);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $entry = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    return $entry;
}

function renderEntry($entry) {

    $date_str = '';
    if(isset($entry['log_date']) && isset($entry['review_date'])) {
        if($entry['log_date'] === $entry['review_date']) {
            $date_str .= 'Whatched and reviewed '.date('Y-m-d', strtotime($entry['log_date']));
        } else {
            $date_str .= 'Whatched '.date('Y-m-d', strtotime($entry['log_date'])).' and reviewed '.date('Y-m-d', strtotime($entry['review_date']));
        }
    } else {
        if(isset($entry['log_date'])) {
            $date_str .= 'Watched '.date('Y-m-d', strtotime($entry['log_date']));
        }
        if(isset($entry['review_date'])) {
            $date_str .= 'Reviewed '.date('Y-m-d', strtotime($entry['review_date']));
    
            // if($entry['spoilers'] == 1) { 
            //     $text = 
            //     '<p>This review may include spoilers!</p>
            //     <button type="button name="reveal-spoilers">Reveal</button>
            //     <p class="review-text" hidden>'.$entry['review_text'].'</p>';
            // } else {
            //     $text = 
            //     '<p class="review-text">'.$entry['review_text'].'</p>';
            // }
        }
    }

    if(!isset($entry['text'])) {
        $entry['text'] = '';
    }

    if(isset($entry['rating'])) {
        $i = 0;
        $stars = '';
        for($j = $entry['rating']; $j > 0; $j -= 0.5) {
            if($i % 2 == 0) {
                $stars .= '<div class="review half-star l"></div>';
            } else {
                $stars .= '<div class="review half-star r"></div>';
            }
            $i++;
        }
    }
    if($entry['like'] == 1) { 
        $like = '<div class="icon like"></div>';
    } else { $like = ''; }

    if($entry['yours'] == 1) {
        $button = '<button class="button" name="delete-review" data-entry-id="'.$entry['id'].'">Delete</button>';
    } elseif($entry['yours'] == 0) {
        if($entry['liked'] == 1) { 
            $button = '<div class="like_button on" data-entry-id="'.$entry['id'].'"></div>';
        } else { 
            $button = '<div class="like_button off" data-entry-id="'.$entry['id'].'"></div>'; 
        }    
    }
   
    $html =
    '<div class="entry_container">
    <div class="top_container">
    <a class="item_name" href="/type-'.$entry['item_type'].'/'.$entry['item_uid'].'">'.$entry['item_name'].'</a>
    <p class="by">'.$date_str.' by <a href="/user-'.$entry['user_uid'].'">'.$entry['username'].'</a></p>
    </div>
    <div class="poster_parent">
    '.prepareItemContainer($entry['item_name'], $entry['item_uid'], $entry['item_year'], $entry['item_type'], 'non-list').'
    </div>
    <div class="text_container"><p class="review">'.$entry['text'].'</p></div>
    <div class="bottom_container_left">
    '.$button.'
    </div>
    <div class="bottom_container_right">
    '.prepareClosedStars($entry['rating']).'
    '.$like.'
    </div>
    </div>';

    echo $html;


    // <div class="entry_container">
    // <div class="top_container">
    // <a class="item_name">Northman</a>
    // <a class="username" href="user-Array">Oskar</a>
    // </div>
    // <p class="date">Whatched and reviewed 2023-02-28</p>
    // <div class="poster_parent">
    // <div class="item_container" data-item-name="Northman" data-item-year="2022">
    // <a class="item_link poster" href="/type-film/northman-2022" style="background-image: url('/img/film/northman-2022/northman-2022-poster-small.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;"></a>
    // </div>
    // </div>
    // <div class="text_container"><p class="review">meh</p></div>
    // </div>
    // <div class="bottom_container">
    // <div class="right">
    // <ul class="star_container closed" data-rating="2.5";">
    // <li class="half_star l"></li><li class="half_star r"></li><li class="half_star l"></li><li class="half_star r"></li><li class="half_star l"></li>
    // </ul>
    // </div>
    // <div class="left">
    // <div class="like_button off"></div>
    // </div>
    // </div>  
}

function fetchUserRatings($conn, $user_uid) {
    $sql = "SELECT `ratings`.*, 
    `items`.`name` AS `item_name`, 
    `items`.`year` AS `item_year`, 
    `items`.`uid` AS `item_uid`, 
    `types`.`uid` AS `item_type`
    FROM `ratings` 
    INNER JOIN `users` ON `ratings`.`user_id` = `users`.`id` 
    INNER JOIN `items` ON `ratings`.`item_id` = `items`.`id` 


    WHERE `ratings`.`created_date` IS NOT NULL AND `users`.`uid` = ? 
    ORDER BY `ratings`.`rating` DESC;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $user_uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $ratings = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return $ratings;
}

function renderUserRatings($ratings) {

    $list = '';
    if(count($ratings) > 0) {
        foreach($ratings as $rating) {
            $list .= prepareRatingContainer($rating['rating'], $rating['like'], $rating['item_name'], $rating['item_uid'], $rating['item_year'], $rating['item_type'], 'list');
        }
    }
    
    $html = 
    '<section class="list_section grid items" list-name="browse">
    <h2></h2>
    <div class="list_container">
    <div class="list_limits">
    <ul class="list">
    '.$list.'
    </ul>
    </div>
    </div>
    </section>';

    echo $html;
    return;
}
 
function fetchUserReviews($conn, $user_uid) {

    $sql = "SELECT `entries`.*, 
    `users`.`name` AS `username`, 
    `users`.`uid` AS `user_uid`, 
    `items`.`name` AS `item_name`, 
    `items`.`year` AS `item_year`, 
    `items`.`uid` AS `item_uid`, 
    `types`.`uid` AS `item_type`
    FROM `entries` 
    INNER JOIN `users` ON `entries`.`user_id` = `users`.`id` 
    INNER JOIN `items` ON `entries`.`item_id` = `items`.`id` 
    INNER JOIN `types` ON `items`.`type_id` = `types`.`id` 
    WHERE `review_date` IS NOT NULL AND `users`.`uid` = ? 
    ORDER BY `review_date` DESC;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $user_uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return $reviews;
}

function renderUserReviews($reviews) {

    $list = '';
    if(count($reviews) > 0) {
        foreach($reviews as $review) {
            $list .= prepareReviewContainer($review['username'], $review['user_uid'], $review['id'], $review['rating'], $review['like'], $review['text'], $review['spoilers'], $review['item_name'], $review['item_uid'], $review['item_year'], $review['item_type'], 'list');
        }
    }
    
    $html = 
    '<section class="list_section grid items" list-name="browse">
    <h2></h2>
    <div class="list_container">
    <div class="list_limits">
    <ul class="list">
    '.$list.'
    </ul>
    </div>
    </div>
    </section>';

    echo $html;
    return;
}

function fetchDiary($conn, $user_uid) {

    $sql = "SELECT `entries`.*, 
    `users`.`name` AS `username`, 
    `users`.`uid` AS `user_uid`, 
    `items`.`name` AS `item_name`, 
    `items`.`year` AS `item_year`, 
    `items`.`uid` AS `item_uid`, 
    `types`.`uid` AS `item_type`
    FROM `entries` 
    INNER JOIN `users` ON `entries`.`user_id` = `users`.`id` 
    INNER JOIN `items` ON `entries`.`item_id` = `items`.`id` 
    INNER JOIN `types` ON `items`.`type_id` = `types`.`id` 
    WHERE `log_date` IS NOT NULL AND `users`.`uid` = ? 
    ORDER BY `log_date` DESC;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $user_uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $logs = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return $logs;
}

function prepareLogContainer() {
    
}

function renderDiary($logs) {

    $list = '';
    if(count($logs) > 0) {
        foreach($logs as $log) {
            $list .= prepareLogContainer($log['username'], $review['user_uid'], $review['id'], $review['rating'], $review['like'], $review['text'], $review['spoilers'], $review['item_name'], $review['item_uid'], $review['item_year'], $review['item_type'], 'list');
        }
    }
    
    $html = 
    '<section class="list_section grid items" list-name="browse">
    <h2></h2>
    <div class="list_container">
    <div class="list_limits">
    <ul class="list">
    '.$list.'
    </ul>
    </div>
    </div>
    </section>';

    echo $html;
    return;
}

function fetchItemReviews($conn, $user_id, $item_uid) {

    $subsql = "";
    $str2 = "";
    $types = "s";
    $params = [$item_uid];
    if(isset($user_id)) {
        $str = "SELECT COUNT(*) 
        FROM `review_likes` 
        WHERE `entry_id` = `entries`.`id` 
        LIMIT 1";

        $subsql = 
        ", 
        IF(`user_id` = ?, 1, 0) AS `yours`,
        (".$str.") AS `liked`";

        $str2 = "AND NOT `users`.`uid` = ?";

        $types = "isi";
        $params = [$user_id, $item_uid, $user_id];
    }


    $sql = "SELECT `entries`.*, 
    `users`.`name` AS `username`, 
    `users`.`uid` AS `user_uid`, 
    `items`.`name` AS `item_name`, 
    `items`.`year` AS `item_year`, 
    `items`.`uid` AS `item_uid`, 
    `types`.`uid` AS `item_type`
    $subsql
    FROM `entries` 
    INNER JOIN `users` ON `entries`.`user_id` = `users`.`id` 
    INNER JOIN `items` ON `entries`.`item_id` = `items`.`id` 
    INNER JOIN `types` ON `items`.`type_id` = `types`.`id` 
    WHERE `review_date` IS NOT NULL 
    AND `items`.`uid` = ? 
    $str2
    ORDER BY `review_date` DESC;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit;
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return $reviews;
}

function renderItemReviews($reviews) {

    $list = '';
    if(count($reviews) > 0) {
        foreach($reviews as $review) {
            $list .= prepareReviewContainer($review['username'], $review['user_uid'], $review['id'], $review['rating'], $review['like'], $review['text'], $review['spoilers'], $review['item_name'], $review['item_uid'], $review['item_year'], $review['item_type'], 'list');
        }
    }
    
    $html = 
    '<section class="list_section grid items" list-name="browse">
    <h2></h2>
    <div class="list_container">
    <div class="list_limits">
    <ul class="list">
    '.$list.'
    </ul>
    </div>
    </div>
    </section>';

    echo $html;
    return;
}