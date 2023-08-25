<?php

require_once("conn/dbh.php");
require_once("forms_functions.php");

if (isset($_POST["submit-login"])) {
    submitLogin($conn, $_POST["uid"], $_POST["pwd"]);
}
if (isset($_POST["submit-signup"])) {
    submitSignup($conn, $_POST["name"], $_POST["email"], strtolower($_POST["uid"]), $_POST["pwd"], $_POST["pwdre"]);
}