<?php

function fetchTypes($conn) {
    $sql = "SELECT * FROM `types` ORDER BY `id`;";
    $result = mysqli_query($conn, $sql);
    $types = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $types;
}

function fetchGenres($conn) {
    $sql = "SELECT * FROM `genres` ORDER BY `name`;";
    $result = mysqli_query($conn, $sql);
    $genres = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $genres;
}

function fetchTags($conn) {
    $sql = "SELECT * FROM `tags` ORDER BY `name`;";
    $result = mysqli_query($conn, $sql);
    $tags = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    return $tags;
}

function prepareItemContainer($name, $uid, $year, $type, $for) {
    $path = "'/img/".$type."/".$uid."/".$uid."-poster-small.jpg'";
    $url = '/type-'.$type.'/'.$uid;

    if($for === "list") {
        $html = 
        '<li class="item_container" data-item-name="'.$name.'" data-item-year="'.$year.'">
        <a class="item_link poster" href="'.$url.'" style="background-image: url('.$path.'); background-size: cover; background-position: center; background-repeat: no-repeat;"></a>
        </li>';
    } else {
        $html = 
        '<div class="item_container" data-item-name="'.$name.'" data-item-year="'.$year.'">
        <a class="item_link poster" href="'.$url.'" style="background-image: url('.$path.'); background-size: cover; background-position: center; background-repeat: no-repeat;"></a>
        </div>';
    }

    return $html;
}

function prepareActivityContainer($user, $user_uid, $entry_id, $rating, $date, $rewatch, $review, $spoilers, $item_name, $item_uid, $item_year, $item_type, $for) {
    $img_path = "'/img/".$item_type."/".$item_uid."/".$item_uid."-poster-small.jpg'";
    $user_url = '/user-'.$user_uid;
    $entry_url = $user_url.'/entry?id='.$entry_id;

    $stars = prepareClosedStars($rating);

    if($rewatch == 1) { 
        $rewatch = '<div class="icon rewatch"></div>';
    } else { $rewatch = ''; }
    if(isset($review)) { 
        $review = '<div class="icon review"></div>';
    } else { $review = ''; }
    if($spoilers == 1) { 
        $spoilers = '<div class="icon spoilers"></div>';
    } else { $spoilers = ''; }

    if($for === "list") {
        $html = 
        '<li class="activity_container" data-item-name="'.$item_name.'" data-item-year="'.$item_year.'">
        <div class="main">
        <div class="user_container">
        <a class="user_link" href="'.$user_url.'">'.$user.'</a>
        </div>
        <a class="activity_link" href="'.$entry_url.'">
        <div class="poster" style="background-image: url('.$img_path.');"></div>
        <div class="rating">
        '.$stars.'
        '.$review.'
        </div>
        </a>
        </div>
        <div class="outer">
        <p class="date">'.date('Y-m-d', strtotime($date)).'</p>
        '.$rewatch.'
        '.$spoilers.'
        </div>
        </li>';
    } else {
        $html = 
        '<div class="activity_container" data-item-name="'.$item_name.'" data-item-year="'.$item_year.'">
        <div class="main">
        <div class="user_container">
        <a class="user_link" href="'.$user_url.'">'.$user.'</a>
        </div>
        <a class="activity_link" href="'.$entry_url.'">
        <div class="poster" style="background-image: url('.$img_path.');"></div>
        <div class="rating">
        <ul class="star_container">
        '.$stars.'
        </ul>
        '.$review.'
        </div>
        </a>
        </div>
        <div class="outer">
        <p class="date">'.date('Y-m-d', strtotime($date)).'</p>
        '.$rewatch.'
        '.$spoilers.'
        </div>
        </div>';
    }

    return $html;
}

function prepareRatingContainer($rating, $like, $item_name, $item_uid, $item_year, $item_type, $for) {
    $img_path = "'/img/".$item_type."/".$item_uid."/".$item_uid."-poster-small.jpg'";
    $item_url = '/type-'.$item_type.'/'.$item_uid;

    if($like === 1) {
        $like_str = '<div class="icon like"></div>';
    } elseif($like === 0) {
        $like_str = '';
    }

    $stars = prepareClosedStars($rating);

    $html = 
    '<li class="rating_container" data-item-name="'.$item_name.'" data-item-year="'.$item_year.'">
    <a class="item_link" href="'.$item_url.'">
    <div class="poster" style="background-image: url('.$img_path.');"></div>
    <div class="rating">
    '.$stars.'
    '.$like_str.'
    </div>
    </a>
    </li>';

    return $html;
}

function prepareReviewContainer($user, $user_uid, $entry_id, $rating, $like, $review, $spoilers, $item_name, $item_uid, $item_year, $item_type, $for) {
    $img_path = "'/img/".$item_type."/".$item_uid."/".$item_uid."-poster-small.jpg'";
    $item_url = '/'.$item_type.'/'.$item_uid;
    $user_url = '/users/'.$user_uid;
    $entry_url = $user_url.'/entry?id='.$entry_id;

    $stars = prepareClosedStars($rating);

    if($spoilers == 1) { 
        $spoilers = 
        '<div class="spoiler_prompt">
        <div class="icon_spoilers"></div>
        <button type="button" name="reveal_spoilers" class="button">Reveal</button>
        </div>';
        $text = '<p hidden>'.$review.'</p>';
    } else { 
        $spoilers = ''; 
        $text = '<p>'.$review.'</p>';
    }
    if($like == 1) { 
        $like = '<li class="icon_like"></li>';
    } else { $like = ''; }

    $html = 
    '<li class="review_container">
    <div class="review_item_container" data-item-name="'.$item_name.'" data-item-year="'.$item_year.'">
    <a class="review_item_link poster" href="'.$item_url.'" style="background-image: url('.$img_path.'); background-size: cover; background-position: center; background-repeat: no-repeat;"></a>
    <a href="'.$entry_url.'" class="button">Full</a>
    <div class="like_button"></div><p>Like review</p>
    </div>
    <div class="top_container">
    <a href="'.$user_url.'" class="user">'.$user.'</a>
    <ul class="star_container">
    '.$stars.$like.'
    </ul>
    </div>
    <div class="review_text">
    '.$spoilers.$text.'
    </div>
    </li>';

    return $html;
}

function prepareReviewContainerPosterless($user, $user_uid, $entry_id, $likes, $rating, $like, $review, $spoilers, $for) {
    $user_url = '/users/'.$user_uid;
    $entry_url = $user_url.'/entry?id='.$entry_id;

    $stars = prepareClosedStars($rating);

    if($spoilers == 1) { 
        $spoilers = 
        '<div class="spoiler_prompt">
        <div class="icon spoilers"></div>
        <button type="button" name="reveal_spoilers" class="button">Reveal</button>
        </div>';
        $text = '<p hidden>'.$review.'</p>';
    } else { 
        $spoilers = ''; 
        $text = '<p>'.$review.'</p>';
    }
    if($like == 1) { 
        $like = '<div class="icon like"></div>';
    } else { $like = ''; }

    $html = 
    '<li class="review_container posterless">
    <div class="grid rating">
    <a href="'.$user_url.'" class="user">'.$user.'</a>
    '.$stars.$like.'
    </div>
    <div class="grid text">
    '.$spoilers.$text.'
    </div>
    <div class="grid bottom">
    <a href="'.$entry_url.'" class="button">Full</a>
    <div class="like_box"><div class="like_button"></div><p class="like_count">'.$likes.'</p></div>
    </div>
    </li>';

    return $html;
}

function prepareUserContainer($name, $uid, $id, $from_id, $reviews, $followers, $completions, $for) {
   
    $html = 
    '<li class="user_container">
    <a href="/user-'.$uid.'" class="user_link">
    <div class="top">
    <p class="name">'.$name.'</p>
    <p class="uid">@'.$uid.'</p>
    </div>
    <div class="bottom">
    <p class="stats followers">Followers: <span>osubsodaucbaouebf'.$followers.'</span></p>
    <p class="stats reviews">Reviews: <span>'.$reviews.'</span></p>
    <p class="stats completions">Completions: <span>'.$completions.'</span></p>
    </div>
    </a>
    <button type="button" class="button follow" data-to-id="'.$id.'" data-from-id="'.$from_id.'">Follow</button>
    </li>';

    return $html;
}

function prepareClosedStars($rating) {

    /* todo: validering för rating */

    $i = 0;
    $stars = '';
    for($j = 0.5; $j <= $rating; $j += 0.5) {
        if($i % 2 == 0) {
            $dir = 'l';
        } else {
            $dir = 'r';
        }
        $stars .= '<li class="half_star '.$dir.'"></li>';
        $i++;
    }

    $html = 
    '<ul class="star_container closed" data-rating="'.$rating.'";">
    '.$stars.'
    </ul>';

    return $html;
}

function prepareOpenStars($rating, $state) {

    /* todo: validering för rating */

    if($state != 'on' && $state != 'off') {
        header("location: /?error");
        exit;
    }

    $i = 0;
    $stars = '';
    for($j = 0.5; $j <= 5; $j += 0.5) {
        if($j <= $rating) {
            $state2 = 'on';
        } elseif($j > $rating) {
            $state2 = 'off';
        }
        if($i % 2 == 0) {
            $dir = 'l';
        } else {
            $dir = 'r';
        }
        $stars .= '<li class="half_star '.$dir.' '.$state2.'" data-nbr="'.$j.'"></li>';
        $i++;
    }

    $html = 
    '<ul class="star_container open '.$state.'";">
    '.$stars.'
    </ul>';

    return $html;
}

function followUser($conn, $from_id, $to_id) {

    $sql = 
    "INSERT INTO `follow` (`from_id`, `to_id`) 
    VALUES (?, ?);";
    
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, "ii", $from_id, $to_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function unfollowUser($conn, $from_id, $to_id) {
    
    $sql = 
    "DELETE FROM `follow` 
    WHERE `from_id` = ? AND `to_id` = ?;";
    
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, "ii", $from_id, $to_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}