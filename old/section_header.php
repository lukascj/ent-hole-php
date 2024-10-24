<?php

require_once("section_header_functions.php");

// för att undvika error-meddelandet "undefined array key" då de skickas som argument
$item_uid = false; 
$user_uid = false; 
$search_users = false;
if(isset($_GET['uid'])) { $item_uid = $_GET['uid']; }
if(isset($_SESSION['user-uid'])) { $user_uid = $_SESSION['user-uid']; }
if(isset($_GET['users'])) { $search_users = true; }

renderHeader($user_uid, $item_uid, $search_users); 

?>