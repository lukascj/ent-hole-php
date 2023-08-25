<?php
require_once('conn/dbh.php');

function like($conn, $user_id, $entry_id) {
    $stmt = mysqli_stmt_init($conn);
    $sql = "INSERT INTO `review_likes` (`user_id`, `entry_id`) VALUES (?, ?);";
    
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $entry_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function unlike($conn, $user_id, $entry_id) {
    
    $stmt = mysqli_stmt_init($conn);
    $sql = "DELETE FROM `review_likes` WHERE `user_id` = ? AND `entry_id` = ?;";
    
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $entry_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
function delete_review($conn, $user_id, $entry_id) {
    
    $stmt = mysqli_stmt_init($conn);
    $sql = "DELETE FROM `review_likes` WHERE `user_id` = ? AND `entry_id` = ?;";
    
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $entry_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_stmt_init($conn);
    $sql = "DELETE FROM `entres` WHERE `id` = ? AND `user_id` = ?;";
    
    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /?error");
        exit();
    }
    
    mysqli_stmt_bind_param($stmt, "ii", $entry_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: /?error");
}

$entry_id = intval($_POST['entry_id']);
$user_id = intval($_POST['user_id']);

if($_POST['action'] === "like") {

    like($conn, $user_id, $entry_id);

} elseif($_POST['action'] === "unlike") {

    unlike($conn, $user_id, $entry_id);

} elseif($_POST['action'] === "delete") {

    delete_review($conn, $user_id, $entry_id);

}