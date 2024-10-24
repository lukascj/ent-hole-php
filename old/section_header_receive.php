<?php

function logOut() {
    session_start();
    session_unset();
    session_destroy();

    header("location: /");
    exit;
}

if(isset($_GET['logout'])) {
    logOut();
}