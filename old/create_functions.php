<?php 
require_once("universal_functions.php");

function fetchItem($conn, $uid) {

    $sql = "SELECT * FROM `items` WHERE `uid` = ?;";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) { 
        header("location: /?error");
        exit; 
    }
    
    mysqli_stmt_bind_param($stmt, "s", $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $item = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    return $item;
}

function fetchQuickSearch($conn, $input) {

    $arr = explode(" ", $input); // delar string till array utefter mellanrum
    for($i = 0; $i < count($arr); $i++) {

        if(is_numeric($arr[$i])) { // om något ord från input är numeriskt

            $year = "%".$arr[$i]."%";
            array_splice($arr, $i, 1);
            $input = join($arr);
            break;
        }
    }
    $input = "%".$input."%";

    $stmt = mysqli_stmt_init($conn);

    if(!isset($year)) {

        $sql = "SELECT 
        `id`, 
        `name`, 
        `uid`, 
        `year` 
        FROM `items` 
        WHERE `name` LIKE ? 
        LIMIT 5
        ;";

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: /?error");
            exit;
        }

        mysqli_stmt_bind_param($stmt, "s", $input);

    } else {

        $sql = "SELECT 
        `id`, 
        `name`, 
        `year` 
        FROM `items` 
        WHERE `name` LIKE ? 
        AND `year` LIKE ? 
        LIMIT 5
        ;";

        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: /?error");
            exit;
        }

        mysqli_stmt_bind_param($stmt, "ss", $input, $year);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);

    return $items;
}

function preparePromptExclusive($for, $hide) {
    $date = date('Y-m-d');
    if($for === 'log') {
        $html =     
        '<div class="log_exclusive" '.$hide.'>
        <div class="form_segment log_date">
        <label for="log-date">When watched</label>
        <input type="date" value="'.$date.'" name="log-date" />
        </div>
        <div class="form_segment rewatch">
        <label for="rewatch">Have you seen this before?</label><!--todo: seen ändras till played om item-type=spel-->
        <input type="checkbox" name="rewatch" />
        </div>
        </div>';
    } elseif($for === 'review') {
        $html = 
        '<div class="review_exclusive" '.$hide.'>
        <div class="form_segment review_text">
        <label for="review-text">Write your review</label>
        <textarea name="review-text" maxlength="10000" cols="30" rows="10" style="resize: none;"></textarea>
        </div>
        <div class="form_segment review_date">
        <label for="review-date">Date of review</label>
        <input type="date" value="'.$date.'" name="review-date" />
        </div>
        <div class="form_segment review_spoilers">
        <label for="review-date">Does your review include spoilers?</label>
        <input type="checkbox" name="spoilers" />
        </div>
        </div>';
    } else {
        header("location: /?error");
        exit;
    }
    return $html;
}

function renderCreatePrompt($entry_type, $item, $user_id) {

    if($entry_type === 'log') { 
        $log_exclusive = preparePromptExclusive($entry_type, '');
        $review_exclusive = preparePromptExclusive('review', 'style="display: none !important;"');
        $expand_button = '<button type="button" name="toggle-review" class="button add">Attach Review</button>';
    } elseif ($entry_type === 'review') {
        $review_exclusive = preparePromptExclusive($entry_type, '');
        $log_exclusive = preparePromptExclusive('log', 'style="display: none !important;"');
        $expand_button = '<button type="button" name="toggle-log" class="button add">Attach Diary Entry</button>';
    } else {
        header("location: /?error");
        exit;
    }

    $stars = prepareOpenStars(0, 'off');

    $form = 
    '<form action="/create_receive.php" method="post">

    <input type="hidden" value="'.$item['id'].'" name="item-id" />
    <input type="hidden" value="0" name="like">
    <input type="hidden" value="null" name="rating">
    <input type="hidden" value="'.$user_id.'" name="user-id">

    '.$log_exclusive.$review_exclusive.'

    <button type="submit" name="submit-create" class="button">Submit</button>
    </form>';

    $html = 
    '<section id="create_section">
    <h2>'.$item['name'].'</h2>

    <div class="form_segment expand">
    <button type="button" name="toggle-rating" class="button add">Add Rating</button>
    '.$expand_button.'
    </div>

    <div class="form_segment rating">
    <div class="like_button off"></div>
    '.$stars.'
    </div>
    
    '.$form.'
    </section>';

    echo $html;
}

function submitRating($conn, $arr) {

    // kollar om du redan rate:at item:et
    $sql = "SELECT COUNT(*) 
    FROM `ratings` 
    WHERE `user_id` = ? AND `item_id` = ?;";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) { 
        header("location: /?error");
        exit; 
    }

    mysqli_stmt_bind_param($stmt, "ii",  $arr['user-id'],  $arr['item-id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    $count = mysqli_fetch_row($result)[0];
    mysqli_free_result($result);

    $date = date('Y-m-d H:i:s');

    if($count === 0) {

        // skapar din rating
        $sql = "INSERT INTO 
        `ratings` (`user_id`, `item_id`, `like`, `rating`, `created_date`, `last_edited_date`) 
        VALUES (?, ?, ?, ?, ?, ?);";
        
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) { 
            header("location: /?error");
            exit; 
        }

        mysqli_stmt_bind_param($stmt, "iiidss", $arr['user-id'], $arr['item-id'], $arr['like'], $arr['rating'], $date, $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

    } elseif($count === 1) {

        // updaterar din rating
        $sql = "UPDATE `ratings` 
        SET `like` = ?, `rating` = ?, `last_edited_date` = ? 
        WHERE `user_id` = ? AND `item_id` = ?;";
        
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) { 
            header("location: /?error");
            exit; 
        }

        mysqli_stmt_bind_param($stmt, "idsii",  $arr['like'],  $arr['rating'], $date, $arr['user-id'], $arr['item-id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

    } elseif($count > 1) {

        // om något gått fel och flera skapats så raderas alla förutom den som skapades först
        $sql = "DELETE FROM `ratings` 
        WHERE `user_id` = ? AND `item_id` = ? 
        ORDER BY `created_date` ASC 
        LIMIT ".($count-1).";";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) { 
            header("location: /?error");
            exit; 
        }

        mysqli_stmt_bind_param($stmt, "ii", $arr['user-id'], $arr['item-id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // uppdaterar den oraderade
        $sql = "UPDATE `ratings` 
        SET `like` = ?, `rating` = ?, `last_edited_date` = ? 
        WHERE `user_id` = ? AND `item_id` = ?;";
        
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) { 
            header("location: /?error");
            exit; 
        }

        mysqli_stmt_bind_param($stmt, "idsii", $arr['like'], $arr['rating'], $date, $arr['user-id'], $arr['item-id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    return;
}

function submitCreate($conn, $arr) {

    if(isset($arr['log-date']) && isset($arr['review-date'])) { 

        // validering:
        if(strlen($arr['review-text']) === 0) {
            header("location: /?error");
            exit;
        }

        if(isset($arr['rewatch'])) {
            $rewatch = 1;
        } else {
            $rewatch = 0;
        }
        if(isset($arr['spoilers'])) {
            $spoilers = 1;
        } else {
            $spoilers = 0;
        }

        // todo: om datumen är samma sätt kolumnerna till "" och ha ny kolumn date -- date skulle kunna användas annars också: innehåller det största datumet
        $sql = "INSERT INTO 
        `entries` (`user_id`, `item_id`, `log_date`, `review_date`, `like`, `rating`, `rewatch`, `text`, `spoilers`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) { 
            header("location: /?error");
            exit; 
        }

        mysqli_stmt_bind_param($stmt, "iissidisi", $arr['user-id'], $arr['item-id'], date('Y-m-d H:i:s', (strtotime($arr['log-date']) + strtotime(date('H:i:s')))), date('Y-m-d H:i:s', (strtotime($arr['review-date']) + strtotime(date('H:i:s')))), $arr['like'], $arr['rating'], $rewatch, $arr['review-text'], $spoilers);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        submitRating($conn, $arr);

    } elseif(isset($arr['log-date'])) {

        if(isset($arr['rewatch'])) {
            $rewatch = 1;
        } else {
            $rewatch = 0;
        }

        $sql = 
        "INSERT INTO `entries` (`user_id`, `item_id`, `log_date`, `like`, `rating`, `rewatch`) 
        VALUES (?, ?, ?, ?, ?, ?)
        ;";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) { 
            header("location: /?error");
            exit; 
        }

        mysqli_stmt_bind_param($stmt, "iisidi", $arr['user-id'], $arr['item-id'], date('Y-m-d H:i:s', (strtotime($arr['log-date']) + strtotime(date('H:i:s')))), $arr['like'], $arr['rating'], $rewatch);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        submitRating($conn, $arr);

    } elseif(isset($arr['review-date'])) {

        // validering:
        if(strlen($arr['review-text']) === 0) {
            header("location: /?error");
            exit;
        }

        if(isset($arr['spoilers'])) {
            $spoilers = 1;
        } else {
            $spoilers = 0;
        }
        
        $sql = "INSERT INTO 
        `entries` (`user_id`, `item_id`, `review_date`, `like`, `rating`, `text`, `spoilers`) 
        VALUES (?, ?, ?, ?, ?, ?, ?);";
        
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) { 
            header("location: /?error");
            exit; 
        }

        mysqli_stmt_bind_param($stmt, "iisidsi", $arr['user-id'], $arr['item-id'], date('Y-m-d H:i:s', (strtotime($arr['review-date']) + strtotime(date('H:i:s')))), $arr['like'], $arr['rating'], $arr['review-text'], $spoilers);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        submitRating($conn, $arr);

    } else {
        header("location: /?error");
        exit;
    }

    header("location: /");
    return;
}