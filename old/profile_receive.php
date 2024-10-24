<?php
require_once("conn/dbh.php");
require_once("universal_functions.php");

$to_id = intval($_POST['to_id']);
$from_id = intval($_POST['from_id']);

if($_POST['action'] === "follow") {
    followUser($conn, $from_id, $to_id);
} elseif($_POST['action'] === "unfollow") {
    unfollowUser($conn, $from_id, $to_id);
}