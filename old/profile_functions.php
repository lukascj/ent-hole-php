<?php

function fetchUser($conn, $uid, $visitor_uid, $visitor_id) {

    if(isset($visitor_id)) {

        if($uid === $visitor_uid) {

            $sql = "SELECT 
            `id`, 
            `name`, 
            `uid`,
            1 AS `you`,
            0 AS `following` 
            FROM `users` 
            WHERE `uid` = ? 
            ORDER BY `name` 
            LIMIT 1;";

            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header("location: /?error0");
                exit;
            }

            mysqli_stmt_bind_param($stmt, "s", $uid);
    
        } else {
    
            $sql = "SELECT 
            `id`, 
            `name`, 
            `uid`,
            0 AS `you`,
            (
                SELECT COUNT(*) 
                FROM `follow` 
                WHERE `from_id` = ? AND `to_id` = `users`.`id`
            ) AS `following`, 
            (
                SELECT COUNT(*) 
                FROM `ratings` 
                WHERE `user_id` = `users`.`id`
            ) AS `total` 
            FROM `users` 
            WHERE `uid` = ? 
            ORDER BY `name` 
            LIMIT 1;";

            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header("location: /?error1");
                exit;
            }

            mysqli_stmt_bind_param($stmt, "is", $visitor_id, $uid);

        }
    } else {

        $sql = "SELECT 
        `id`, 
        `name`, 
        `uid`, 
        NULL AS `you`, 
        NULL AS `following`,
        (
            SELECT COUNT(*) 
            FROM `ratings` 
            WHERE `user_id` = `users`.`id`
        ) AS `total` 
        FROM `users` 
        WHERE `uid` = ? 
        ORDER BY `name` 
        LIMIT 1;";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: /?error2");
            exit;
        }

        mysqli_stmt_bind_param($stmt, "s", $uid);

    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    // om något gått fel och det skapats duplikanter av följningar:
    if($user['following'] > 1) {

        $lim = $user['following'] - 1;

        $sql = 
        "DELETE FROM `follow` 
        WHERE `from_id` = ? AND `to_id` = ? 
        LIMIT $lim;";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: /?error3");
            exit;
        }

        mysqli_stmt_bind_param($stmt, "ii", $$visitor_id, $user['id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $user['following'] = 1;
    }

    return $user;
}

function renderProfile($user, $visitor_id) {

    if(isset($visitor_id) && $user['you'] !== 1) { // om du är inloggad
        if($user['following'] === 0) {
            $follow_button = '<button type="button" class="button follow" data-to-id="'.$user['id'].'" data-from-id="'.$visitor_id.'">Follow</button>';
        } elseif($user['following'] === 1) {
            $follow_button = '<button type="button" class="button unfollow" data-to-id="'.$user['id'].'" data-from-id="'.$visitor_id.'">Unfollow</button>';
        } else {
            header("location: /?error");
            exit;
        }  
    } elseif(isset($visitor_id) && $user['you'] === 1) {
        $follow_button = '';
    }

    $html =
    '<h2 class="username">'.$user['name'].'</h2>
    '.$follow_button.'
    <a class="button" href="/user-'.$user['uid'].'/ratings">Ratings</a>
    <a class="button" href="/user-'.$user['uid'].'/reviews">Reviews</a>
    <a class="button" href="/user-'.$user['uid'].'/diary">Diary</a>';

    echo $html;
    return;
}