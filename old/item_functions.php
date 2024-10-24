<?php
require_once("universal_functions.php");

function fetchItemGenres($conn, $item_id) {

    $sql = "SELECT 
    `genres`.* 
    FROM `genres` 
    INNER JOIN `items_genres` ON `genres`.`id` = `items_genres`.`genre_id` 
    WHERE `items_genres`.`item_id` = $item_id
    ;";

    $result = mysqli_query($conn, $sql);
    $genres = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return $genres;
}

function fetchItemTags($conn, $item_id) {

    $sql = "SELECT 
    `tags`.* 
    FROM `tags` 
    INNER JOIN `items_tags` ON `tags`.`id` = `items_tags`.`tag_id` 
    WHERE `items_tags`.`item_id` = $item_id
    ;";
    
    $result = mysqli_query($conn, $sql);
    $tags = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return $tags;
}

function fetchItemCrewAndCollections($conn, $item_id) {

    $sql = "SELECT 
    `crew`.*, 
    `crew_roles`.`uid` AS `role`, 
    `items_crew`.`character`
    FROM `crew` 
    INNER JOIN `items_crew` ON `crew`.`id` = `items_crew`.`artist_id` 
    INNER JOIN `crew_roles` ON `items_crew`.`role_id` = `crew_roles`.`id` 
    WHERE `items_crew`.`item_id` = $item_id
    ;";

    $result = mysqli_query($conn, $sql);
    $crew = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    $sql = "SELECT 
    `collections`.* 
    FROM `collections` 
    INNER JOIN `items_collections` ON `collections`.`id` = `items_collections`.`collection_id` 
    WHERE `items_collections`.`item_id` = $item_id
    ;";

    $result = mysqli_query($conn, $sql);
    $collections = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return [$crew, $collections];
}

function fetchItemReviews($conn, $item_id, $list, $lim) {

    switch($list) {
        case 'popular':
            $sql = "SELECT 
            `entries`.*, 
            `users`.`uid` AS `user_uid`, 
            `users`.`name` AS `username`, 
            (
                SELECT 
                COUNT(*) 
                FROM `review_likes` 
                WHERE `entry_id` = `entries`.`id`
            ) AS `likes` 
            FROM `entries` 
            INNER JOIN `users` ON `entries`.`user_id` = `users`.`id` 
            WHERE `review_date` IS NOT NULL AND `item_id` = $item_id 
            ORDER BY `likes` DESC 
            LIMIT $lim
            ;";
            break;
        case 'recent':
            $sql = "SELECT 
            `entries`.*, 
            `users`.`uid` AS `user_uid`, 
            `users`.`name` AS `username`, 
            (
                SELECT 
                COUNT(*) 
                FROM `review_likes` 
                WHERE `entry_id` = `entries`.`id`
            ) AS `likes` 
            FROM `entries` 
            INNER JOIN `users` ON `entries`.`user_id` = `users`.`id` 
            WHERE `review_date` IS NOT NULL AND `item_id` = $item_id 
            ORDER BY `review_date` DESC 
            LIMIT $lim
            ;";
            break;
        case 'random':
            $sql = "SELECT 
            `entries`.*, 
            `users`.`uid` AS `user_uid`, 
            `users`.`name` AS `username`, 
            (
                SELECT 
                COUNT(*) 
                FROM `review_likes` 
                WHERE `entry_id` = `entries`.`id`
            ) AS `likes` 
            FROM `entries` 
            INNER JOIN `users` ON `entries`.`user_id` = `users`.`id` 
            WHERE `review_date` IS NOT NULL AND `item_id` = $item_id 
            ORDER BY RAND() 
            LIMIT $lim
            ;";
            break;
    }

    $result = mysqli_query($conn, $sql);
    $reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return $reviews;
}

function fetchItem($conn, $type, $uid, $user_id) {

    str_replace('-', '_', $type);

    if($type[-1] === 's') {
        $attr_table = '`items_attr_'.$type.'`';
    } else {
        $attr_table = '`items_attr_'.$type.'s`';
    }

    $column = '`'.$type.'_id`';

    $sql = 
    "SELECT 
    `items`.*, 
    `types`.`uid` AS `type_uid`,
    `types`.`name` AS `type_name`,
    (
        SELECT AVG(`rating`) 
        FROM `ratings` 
        WHERE `ratings`.`item_id` = `items`.`id`
    ) AS `rating`,
    NULL AS `your_rating`,
    $attr_table.*
    FROM `items` 
    INNER JOIN `types` ON `types`.`id` = `items`.`type_id`
    INNER JOIN $attr_table ON `items`.`id` = $attr_table.$column
    WHERE `types`.`uid` = ? AND `items`.`uid` = ? 
    LIMIT 1
    ;";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit;
    }

    mysqli_stmt_bind_param($stmt, "ss", $type, $uid);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $item = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    if($user_id) {

        $sql = 
        "SELECT *
        FROM `ratings` 
        WHERE `item_id` = ? AND `user_id` = ?
        LIMIT 1
        ;";
    
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: /?error");
            exit;
        }
    
        mysqli_stmt_bind_param($stmt, "ii", $item['id'], $user_id);
    
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        $your = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        if(isset($your) && count($your) > 0) {
            $item['your_completed'] = 1;
            $item['your_rating'] = $your['rating'];
            $item['your_liked'] = $your['like'];
        } else {
            $item['your_completed'] = 0;
            $item['your_rating'] = 0;
            $item['your_liked'] = 0;
        }
        
    } else {
        $item['your_completed'] = 0;
        $item['your_rating'] = 0;
        $item['your_liked'] = 0;
    }

    $genres = fetchItemGenres($conn, $item['id']);
    $tags = fetchItemGenres($conn, $item['id']);
    list($crew, $collections) = fetchItemCrewAndCollections($conn, $item['id']);
    $reviews_popular = fetchItemReviews($conn, $item['id'], 'popular', 10);
    $reviews_recent = fetchItemReviews($conn, $item['id'], 'recent', 10);
    $reviews_random = fetchItemReviews($conn, $item['id'], 'random', 10);

    $item['genres'] = $genres;
    $item['tags'] = $tags;
    $item['crew'] = $crew;
    $item['collections'] = $collections;
    $item['reviews_popular'] = $reviews_popular;
    $item['reviews_recent'] = $reviews_recent;
    $item['reviews_random'] = $reviews_random;

    return $item;
}

function prepareReviewList($reviews, $name) {

    $list = '';
    if(count($reviews) > 0) {
        foreach($reviews as $review) {
            $list .= prepareReviewContainerPosterless($review['username'], $review['user_uid'], $review['entry_id'], $review['likes'], $review['rating'], $review['like'], $review['text'], $review['spoilers'], 'list');
        }
        if(count($reviews) === 11) {
            $list .= 
            '<li class="item_container show_more">
            <a class="item_link show_more" href="/recent-activity">
            <p class="1">Show more</p>
            <p class="2">+</p>
            </a>
            </li>';
        }
    }
    
    $html =
    '<div list-name="'.strtolower($name).'">
    <div class="title_container">
    <h2>'.ucfirst($name).'</h2>
    <div class="arrow u"></div>
    </div>
    <div class="list_container"">
    <div class="list_limits">
    <ul class="list">
    '.$list.'
    </ul>
    </div>
    <div class="arrow d"></div>
    </div>
    </div>';

    return $html;
}

function renderItem($item, $user_id) {

    $review_list_popular = prepareReviewList($item['reviews_popular'], 'popular');
    $review_list_recent = prepareReviewList($item['reviews_recent'], 'recent');
    $review_list_random = prepareReviewList($item['reviews_random'], 'random');

    $poster_path = "'/img/".$item['type_uid']."/".$item['uid']."/".$item['uid']."-poster-full.jpg'";
    $bg_path = "'/img/".$item['type_uid']."/".$item['uid']."/".$item['uid']."-bg.jpg'";

    $item['directors'] = ['a', 'b'];
    $item['writers'] = ['a', 'c'];
    if(array_intersect($item['directors'], $item['writers']) === $item['writers']) {
        if(count($item['directors']) == 2) {
            $creators_str = 'Written and directed by <a href="">'.$item['directors'][0].'</a> and <a href="">'.$item['directors'][1].'</a>';
        } elseif(count($item['directors']) == 1) {
            $creators_str = 'Written and directed by <a href="">'.$item['directors'][0].'</a>';
        } elseif(count($item['directors']) == 0) {
            header('location: /?error');
            exit;
        }
    } else {
        if(count($item['directors']) == 2) {
            $creators_str = 'Directed by <a href="">'.$item['directors'][0].'</a> and <a href="">'.$item['directors'][1].'</a>';
        } elseif(count($item['directors']) == 1) {
            $creators_str = 'Directed by <a href="">'.$item['directors'][0].'</a>';
        } elseif(count($item['directors']) == 0) {
            header('location: /?error');
            exit;
        }
    
        if(count($item['writers']) == 2) {
            $creators_str .= ', written by <a href="">'.$item['writers'][0].'</a> and <a href="">'.$item['writers'][1].'</a>';
        } elseif(count($item['writers']) == 1) {
            $creators_str .= ', written by <a href="">'.$item['writers'][0].'</a>';
        } elseif(count($item['writers']) == 0) {
            header('location: /?error');
            exit;
        }
    }

    if($user_id) {
        if($item['your_completed'] === 1) {
            $check = '<div class="check_button on"></div>';
        } else {
            $check = '<div class="check_button off"></div>';
        }
        if($item['your_liked'] === 1) {
            $like = '<div class="like_button on"></div>';
        } else {
            $like = '<div class="like_button off"></div>';
        }
        if($item['your_rating'] === NULL) {
            $stars = prepareOpenStars(0, 'on');
        } else {
            $stars = prepareOpenStars($item['your_rating'], 'on');
        }

        $actions = 
        '<div id="actions">
        <div class="buttons">
        '.$check.'
        '.$like.'
        </div>
        <div class="rate">
        '.$stars.'
        </div>
        <div class="avg">
        <p>Avg: '.round($item['rating'], 2, PHP_ROUND_HALF_UP).'</p>
        </div>
        <div class="to_reviews">
        <a href="'.$_SERVER['REQUEST_URI'].'/reviews" class="button">See all reviews</a>
        </div>
        </div>';
    } else {
        $actions = 
        '<div id="actions">
        <div class="avg">
        <p>Avg: '.round($item['rating'], 2, PHP_ROUND_HALF_UP).'</p>
        </div>
        <div class="to_reviews">
        <a href="'.$_SERVER['REQUEST_URI'].'/reviews" class="button">See all reviews</a>
        </div>
        </div>';
    }

    $section1 = 
    '<section id="item_grid_container">

    <div class="left_container">
    <div id="main_poster" style="background-image: url('.$poster_path.');"></div>
    </div>

    <div id="title_container">
    <p class="main"><span class="title">'.$item['name'].'</span>  <a class="release" href="#">'.$item['year'].'</a>  <a class="type" href="#">'.$item['type_name'].'</a></p>
    <p class="creators">'.$creators_str.'</p>
    </div>

    <div class="right_container">
    '.$actions.'
    </div>

    <div id="description_container">
    <p class="desc">'.$item['description'].'</p>
    </div>

    </section>';

    // $section2 = 
    // '<section class="list_section multiple vertical">
    // <div class="button_container"><button type="button" class="button">See all reviews</button></div>'.
    // $review_list_popular.
    // $review_list_recent.
    // $review_list_random.'
    // </section>';

    // '<div id="bg" style="background-image: url('.$bg_path.');"></div>'

    $html = 
    // $section1.$section2;
    $section1;
    
    

    // om en director och en writer: "directed by [director], written by [writer]"
    // om director och writer är samma person: "written and directed by [person]"
    // om fler än två directors och fler än två writers: "show directors, show writers"
    // Directed by <a href="#">'.$item['directors'].'</a>, written by <a href="#">'.$item['writers'].'</a>, adapted from <a href="#">'.$item['source'].'</a></h3>



    echo $html;
    return;
}