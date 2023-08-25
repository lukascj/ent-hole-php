<?php
require_once("conn/dbh.php");
require_once("create_functions.php");

if(isset($_POST['csearch'])) {
    
    require_once("conn/dbh.php");
    $items = fetchQuickSearch($conn, rtrim($_POST['input'])); // rtrim tar bort mellanrum i början eller slutet på strängen
    echo json_encode($items);

} elseif(isset($_POST['submit-create'])) {
    submitCreate($conn, $_POST);
}