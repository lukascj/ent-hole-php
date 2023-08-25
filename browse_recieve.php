<?php
    
require_once("conn/dbh.php");
require_once("universal_functions.php");
require_once("browse_functions.php");

redirectBrowse($_GET, fetchTypes($conn));