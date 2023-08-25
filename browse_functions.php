<?php
require_once("universal_functions.php");

function renderBrowseFilter($conn, $search) {

    $genres = fetchGenres($conn);
    $glist = '';
    foreach($genres as $genre) {
        $glist .= '<option value="'.$genre['uid'].'">'.$genre['name'].'</option>';
    }

    $tags = fetchTags($conn);
    $talist = '';
    foreach($tags as $tag) {
        $talist .= '<option value="'.$tag['uid'].'">'.$tag['name'].'</option>';
    }

    $ylist = '';
    for($y = date("Y")+1; $y >= 1870; $y--) {

        $ylist .= '<option value="'.$y.'">'.$y.'</option>';

        if ($y % 10 == 0){
            $ylist .= '<option value="'.$y.'s" style="text-transform: lowercase !important;">'.$y.'s</option>';
        }
    }

    $tylist = '';
    $types = fetchTypes($conn);
    foreach($types as $type) {
        $tylist .= 
        '<div class="filter_option">
        <label for="type-'.$type['uid'].'">'.$type['name'].'</label>
        <input type="checkbox" name="type-'.$type['uid'].'" checked>
        </div>';
    }

    if($search !== false) {
        $search = '<input type="hidden" name="search" value="'.$search.'"></input>';
    }

    $html = 
    '<section id="filter">
    <form action="/browse_recieve.php" method="get">
    '.$search.'

    <div class="filter_segment">
    <div class="filter_option">
    <label for="sort-by">Sort by</label>
    <select name="sort-by">
    <option value="popularity">Popularity</option>
    <option value="rating">Rating</option>
    <option value="name">Name</option>
    </select>
    </div>

    <div class="filter_option extra">
    <select name="sort-by-popularity">
    <option value="week">This week</option>
    <option value="month">This month</option>
    <option value="all-time">All time</option>
    </select>
    </div>

    <div class="filter_option">
    <label for="genre">Genre</label>
    <select name="genre">
    <option value="any">Any</option>
    '.$glist.'
    </select>
    </div>

    <div class="filter_option">
    <label for="tag">Tag</label>
    <select name="tag">
    <option value="any">Any</option>
    '.$talist.'
    </select>
    </div>

    <div class="filter_option">
    <label for="year">Year</label>
    <select name="year">
    <option value="any">Any</option>
    '.$ylist.'
    </select>
    </div>
    </div>

    <div class="filter_segment">
    '.$tylist.'
    </div>

    <div class="button_container">
    <button type="submit" class="button">Apply</button>
    <div class="arrow u"></div>
    </div>

    </form>
    </section>';

    echo $html;
    return;
}

function redirectBrowse($filter_arr, $types_arr) {

    $url = '/';

    if(isset($filter_arr['users'])) {
        if(isset($filter_arr['search'])) {
            if(strlen($filter_arr['search']) === 0) {
                $url .= 'users';
            } else {
                $url .= 'search-users/'.$filter_arr['search'];
            }
        } else {
            $url .= 'users';
        }
    } else {
        if(isset($filter_arr['search'])) {
            if(strlen($filter_arr['search']) === 0) {
                $url .= 'browse';
            } else {
                $url .= 'search/'.$filter_arr['search'];
            }
        } else {
            $url .= 'browse';
        }
    }

    $arr = [];
    foreach($types_arr as $type) {
        if(isset($filter_arr['type-'.$type['uid']])) {
            array_push($arr, $type['uid']);
        }
    }
    if(count($arr) > 0 && !(count($arr) === count($types_arr))) {
        if(count($arr) === 1) {
            $url .= '?type='.$arr[0];
        } elseif(count($arr) > 1) {
            $url .= '?types='.implode('+', $arr);
        }
    }

    if(isset($filter_arr['genre']) && $filter_arr['genre'] !== 'any') {
        if(strpos($url, '?') !== false) {
            $url .= '&genre='.$filter_arr['genre'];
        } else {
            $url .= '?genre='.$filter_arr['genre'];
        }
    }

    if(isset($filter_arr['tag']) && $filter_arr['tag'] !== 'any') {
        if(strpos($url, '?') !== false) {
            $url .= '&tag='.$filter_arr['tag'];
        } else {
            $url .= '?tag='.$filter_arr['tag'];
        }
    }

    if(isset($filter_arr['year']) && $filter_arr['year'] !== 'any') {
        if($filter_arr['year'][-1] === "s") {
            if(strpos($url, '?') !== false) {
                $url .= '&decade='.$filter_arr['year'];
            } else {
                $url .= '?decade='.$filter_arr['year'];
            }
        } else {
            if(strpos($url, '?') !== false) {
                $url .= '&year='.$filter_arr['year'];
            } else {
                $url .= '?year='.$filter_arr['year'];
            }
        }
    }

    if(isset($filter_arr['sort-by'])) {
        switch($filter_arr['sort-by']) {
            case 'popularity':
                switch($filter_arr['sort-by-popularity']) {
                    case 'week':
                        $sort_by = 'sort-by=popularity-week';
                        break;
                    case 'month':
                        $sort_by = 'sort-by=popularity-month';
                        break;
                    case 'all-time':
                        $sort_by = 'sort-by=popularity-all-time';
                        break;
                }
                break;
            case 'rating':
                switch($filter_arr['sort-by-rating']) {
                    case 'high':
                        $sort_by = 'sort-by=rating-high';
                        break;
                    case 'low':
                        $sort_by = 'sort-by=rating-low';
                        break;
                    default:
                        $sort_by = 'sort-by=rating-high';
                        break;
                }
                break;
            case 'release':
                switch($filter_arr['sort-by-release']) {
                    case 'recent':
                        $sort_by = 'sort-by=release-recent';
                        break;
                    case 'old':
                        $sort_by = 'sort-by=release-old';
                        break;
                    default:
                        $sort_by = 'sort-by=release-recent';
                        break;
                }
                break;
            case 'title':
                switch($filter_arr['sort-by-title']) {
                    case 'desc':
                        $sort_by = 'sort-by=title-desc';
                        break;
                    case 'asc':
                        $sort_by = 'sort-by=title-asc';
                        break;
                    default:
                        $sort_by = 'sort-by=title-desc';
                        break;
                }
                break;
        }
        if($sort_by !== 'sort-by=popularity-week') {
            if(strpos($url, '?') !== false) {
                $url .= '&'.$sort_by;
            } else {
                $url .= '?'.$sort_by;
            }
        }
    }

    header("location: ".$url);
    return;
}

function fetchListBrowse($conn, $filter_arr, $type) {

    if($type === 'items') {

        $stmt = mysqli_stmt_init($conn);
        $values = [];
        $select = "";
        $from = "";
        $where = "";
        $from = "FROM `items`";

        if(isset($filter_arr["popular"])) {

            if($filter_arr["by"] === 'week') {
                $date = date('Y-m-d', strtotime('-1 week'));
                $tmp1 = "AND `ratings`.`created_date` > '$date'";
                $tmp2 = "AND `entries`.`log_date` > '$date'";
            } elseif($filter_arr["by"] === 'month') {
                $date = date('Y-m-d', strtotime('-1 month'));
                $tmp1 = "AND `ratings`.`created_date` > '$date'";
                $tmp2 = "AND `entries`.`log_date` > '$date'";
            } elseif($filter_arr["by"] === 'all') {
                $tmp1 = '';
                $tmp2 = '';
            } else {
                $date = date('Y-m-d', strtotime('-1 week'));
                $tmp1 = "AND `ratings`.`created_date` > '$date'";
                $tmp2 = "AND `entries`.`log_date` > '$date'";
            }
    
            $factor = "`popularity`";
            $select =  // todo: ändra så att om entry och rating är från samma tillfälle så räknas endast en av dem med
            "(
                (SELECT COUNT(*) FROM `ratings` WHERE `ratings`.`item_id` = `items`.`id` $tmp1) 
                + 
                (SELECT COUNT(*) FROM `entries` WHERE `entries`.`item_id` = `items`.`id` $tmp2)
            ) AS `popularity`,";
            $order = "DESC";
            
        } else {

            if(isset($filter_arr["search"])) {
                $where = "WHERE `items`.`name` LIKE ?";
                $search_str = "%".$filter_arr["search"]."%";
                array_push($values, $search_str); 
            }
    
            if(isset($filter_arr['types'])) {
    
                $types = explode(' ', $filter_arr['types']); // obs: php behandlar plus som mellanrum
                if(count($types) < 2) {
                    header("location: /?error");
                    exit;
                } else {
                    $in = "IN (".str_repeat("?, ", count($types)-1)."?)";
                }
    
                if($where !== "") {
                    $where .= " AND `types`.`uid` ".$in;
                } else {
                    $where .= "WHERE `types`.`uid` ".$in;
                }
    
                array_push($values, ...$types);
    
            } elseif(isset($filter_arr['type'])) {
    
                if($where !== "") {
                    $where .= " AND `type` = ?";
                } else {
                    $where .= "WHERE `type` = ?";
                }
    
                array_push($values, $filter_arr['type']);
            }
    
            
            if(isset($filter_arr["genre"])) {
    
                $from .= 
                " INNER JOIN `items_genres` ON `items`.`id` = `items_genres`.`item_id` 
                INNER JOIN `genres` ON `items_genres`.`genre_id` = `genres`.`id`";
    
                if($where !== "") {
                    $where .= " AND `genres`.`uid` = ?";
                } else {
                    $where = "WHERE `genres`.`uid` = ?";
                }
    
                array_push($values, $filter_arr['genre']);
            }
    
        
            if(isset($filter_arr["tag"])) {
    
                $from .= 
                " INNER JOIN `items_tags` ON `items`.`id` = `items_tags`.`item_id` 
                INNER JOIN `tags` ON `items_tags`.`tag_id` = `tags`.`id`";
    
                if($where !== "") {
                    $where .= " AND `tags`.`uid` = ?";
                } else {
                    $where = "WHERE `tags`.`uid` = ?";
                }
    
                array_push($values, $filter_arr['tag']);
            }
            
        
            if(isset($filter_arr['year'])) {
    
                if($where !== "") {
                    $where .= " AND `items`.`year` = ?";
                } else {
                    $where = "WHERE `items`.`year` = ?";
                }
    
                array_push($values, intval($filter_arr['year']));
    
            } elseif(isset($filter_arr['decade'])) {
    
                $y = intval(rtrim($filter_arr['decade'], "s"));
                $years = [];
    
                $lim = $y+10;
                for($y; $y < $lim; $y++) { 
                    array_push($years, $y); 
                }
    
                $in = "IN (".str_repeat("?, ", 9)."?)";
    
                if($where !== "") {
                    $where .= " AND `items`.`year` ".$in;
                } else {
                    $where = "WHERE `items`.`year` ".$in;
                }
    
                array_push($values, ...$years);
            }
    
    
            if(!isset($filter_arr["sort-by"])) {
                $factor = "`popularity`";
                $tmp = date('Y-m-d', strtotime('-1 week'));
                $select .=  // todo: ändra så att om entry och rating är från samma tillfälle så räknas endast en av dem med
                "(
                    (SELECT COUNT(*) FROM `ratings` WHERE `ratings`.`item_id` = `items`.`id` AND `ratings`.`created_date` > '$tmp') 
                    + 
                    (SELECT COUNT(*) FROM `entries` WHERE `entries`.`item_id` = `items`.`id` AND `entries`.`log_date` > '$tmp')
                ) AS `popularity`,";
                $order = "DESC";
    
            } else {
    
                switch($filter_arr["sort-by"]) {
                    case "popularity-month":
                        $factor = "`popularity`";
                        $tmp = date('Y-m-d', strtotime('-1 month'));
                        $select .= 
                        "(
                            (SELECT COUNT(*) FROM `ratings` WHERE `ratings`.`item_id` = `items`.`id` AND `ratings`.`created_date` > '$tmp')
                            + 
                            (SELECT COUNT(*) FROM `entries` WHERE `entries`.`item_id` = `items`.`id` AND `entries`.`log_date` > '$tmp')
                        ) AS `popularity`,";
                        $order = "DESC";
                        break;
                    case "popularity-all-time":
                        $factor = "`popularity`";
                        $select .= "(SELECT COUNT(*) FROM `ratings` WHERE `ratings`.`item_id` = `items`.`id`) AS `popularity`,";
                        $order = "DESC";
                        break;
                    case "rating-high":
                        $factor = "`rating`";
                        $select .= "(SELECT AVG(`rating`) FROM `ratings` WHERE `item_id` = `items`.`id`) AS `rating`,";
                        $order = "DESC";
                        break;
                    case "rating-low":
                        $factor = "`rating`";
                        $select .= "(SELECT AVG(`rating`) FROM `ratings` WHERE `item_id` = `items`.`id`) AS `rating`,";
                        $order = "ASC";
                        break;
                    case "title-desc":
                        $factor = "`items`.`name`";
                        $order = "DESC";
                        break;
                    case "title-asc":
                        $factor = "`items`.`name`";
                        $order = "ASC";
                        break;
                    default:
                        $factor = "`popularity`";
                        $tmp = date('Y-m-d', strtotime('-1 week'));
                        $select .= 
                        "(
                            (SELECT COUNT(*) FROM `ratings` WHERE `ratings`.`item_id` = `items`.`id` AND `ratings`.`created_date` > '$tmp')
                            + 
                            (SELECT COUNT(*) FROM `entries` WHERE `entries`.`item_id` = `items`.`id` AND `entries`.`log_date` > '$tmp')
                        ) AS `popularity`,";
                        $order = "DESC";
                        break;
                }
            }
        }

        if(isset($filter_arr['page'])) {
            if($filter_arr['page'] < 2) {
                header("location: /?error");
                exit;
            } else {
                $offset = "OFFSET ".(160*($filter_arr['page']-1));
            }
        } else {
            $offset = "";
        }
    
        $sql = "SELECT 
        `items`.`id`,
        `items`.`name`,
        `types`.`uid` AS `type`,
        `items`.`uid`,
        $select
        `items`.`year`
        $from
        INNER JOIN `types` ON `types`.`id` = `items`.`type_id`
        $where
        ORDER BY $factor $order
        LIMIT 160
        $offset
        ;";

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: /?error");
            exit;
        }

        $param_str = "";
        foreach($values as $val) {

            if(gettype($val) === "integer") {
                $param_str .= "i";

            } elseif(gettype($val) === "string") {
                $param_str .= "s";
                
            } elseif(gettype($val) === "double") {
                $param_str .= "d";

            } else {
                header("location: /?error");
                exit;
            }
        }

        if(strlen($param_str) > 0) { 
            mysqli_stmt_bind_param($stmt, $param_str, ...$values); 
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);

        return $items;

    } elseif($type === "users") {

        if(isset($filter_arr['search'])) {

            $stmt = mysqli_stmt_init($conn);
            $search_str = "%".$filter_arr["search"]."%";

            $sql = "SELECT `id`, `name`, `uid` FROM `users` WHERE `name` LIKE ? OR `uid` LIKE ? ORDER BY `name`;";

            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header("location: /?error");
                exit;
            }
    
            mysqli_stmt_bind_param($stmt, "ss", $search_str, $search_str); 
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);

        } else {

            $sql = "SELECT `id`, `name`, `uid` FROM `users` ORDER BY `name`;";
            $result = mysqli_query($conn, $sql);
            $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);

        }

        return $users;
    }
}

function fetchListBrowseRecent($conn, $user_id) {

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
    while ($id = mysqli_fetch_array($result, MYSQLI_NUM)[0]) {
        array_push($following, $id);  
    }
    mysqli_free_result($result);

    if(!(isset($following) || count($following) === 0)) {
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

function renderListBrowse($from_id, $fetched, $type) {

    if($type === 'items') {

        $list = '';
        if(count($fetched) > 0) {
            foreach($fetched as $item) {
                $list .= prepareItemContainer($item['name'], $item['uid'], $item['year'], $item['type'], 'list');
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
    } elseif($type === 'users') {

        $list = '';
        if(count($fetched) > 0) {
            foreach($fetched as $user) {
                $list .= prepareUserContainer($user['name'], $user['uid'], $user['id'], $from_id, $user['reviews'], $user['followers'], $user['completions'], 'list');
            }
        }
    
        $html = 
        '<section class="list_section grid users" list-name="browse">
        <h2></h2>
        <div class="list_container">
        <div class="list_limits">
        <ul class="list">
        '.$list.'
        </ul>
        </div>
        </div>
        </section>';

    } elseif($type === 'activity') {

        $list = 
        '<li class="special_container activity find_more">
        <a class="link find_more" href="/users">
        <p class="text">Find people to follow</p>
        <div class="plus"></div>
        </a>
        </li>';

        if(count($fetched) > 0) {
            foreach($fetched as $entry) {
                $list .= prepareActivityContainer($entry['username'], $entry['user_uid'], $entry['entry_id'], $entry['rating'], $entry['date'], $entry['rewatch'], $entry['text'], $entry['spoilers'], $entry['item_name'], $entry['item_uid'], $entry['item_year'], $entry['item_type'], 'list');
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
    }

    echo $html;
    return;
}