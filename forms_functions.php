<?php

// kollar om något fält är tomt
function emptyInput($array) {
    foreach($array as $element) {
        if(empty($element)) {
            return true;
        }
    }
    return false;
}

// kollar om användarnamnet använder godkända tecken
function invalidUid($username) {
    return (!preg_match("/^[a-zA-Z0-9]*$/", $username));
}

// validerar emailen
function invalidEmail($email) {
    return (!filter_var($email, FILTER_VALIDATE_EMAIL));
}

// kollar så att lösenorden matchar
function pwdMatch($pwd, $pwdre) {
    return ($pwd !== $pwdre);
}

// kollar om användarnamnet (redan) existerar
function uidExists($conn, $username, $email) {

    $sql = "SELECT * 
    FROM `users` 
    WHERE `uid` = ? OR `email` = ?
    ;";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /forms?error");
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if($row) {
        return $row;
    } else {
        return false;
    }
}

// skapar användaren
function createUser($conn, $name, $email, $uid, $pwd) {
    $sql = 
    "INSERT INTO `users` (`name`, `email`, `uid`, `pwd`) 
    VALUES (?, ?, ?, ?);";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: /forms?error");
        exit;
    }

    $hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);
    
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $uid, $hashed_pwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// loggar in användaren
function loginUser($conn, $uid_or_email, $pwd) {

    $tmp = uidExists($conn, $uid_or_email, $uid_or_email);
    if(!$tmp) {
        header("location: /forms?error=wronglogin");
        exit;
    } else {
        $user = $tmp;
    }

    $pwd_hashed = $user["pwd"];
    $verify_pwd = password_verify($pwd, $pwd_hashed);

    if(!$verify_pwd) {
        header("location: /forms?error=wronglogin");
        exit;

    } else {

        session_start();
        $_SESSION['user-id'] = $user['id'];
        $_SESSION['user-uid'] = $user['uid'];
    }
}

function submitSignup($conn, $name, $email, $uid, $pwd, $pwdre) {

    if(emptyInput([$name, $email, $uid, $pwd, $pwdre]) !== false) {
        header("location: /forms?error=emptyinput");
        exit;
    }
    if(invalidUid($uid) !== false) {
        header("location: /forms?error=invaliduid");
        exit;
    }
    if(invalidEmail($email) !== false) {
        header("location: /forms?error=invalidemail");
        exit;
    }
    if(pwdMatch($pwd, $pwdre) !== false) {
        header("location: /forms?error=differentpasswords");
        exit;
    }
    if(uidExists($conn, $uid, $email) !== false) {
        header("location: /forms?error=usernametaken");
        exit;
    }

    createUser($conn, $name, $email, $uid, $pwd);
    loginUser($conn, $uid, $pwd);
    header("location: /");
    return;
}

function submitLogin($conn, $uid_or_email, $pwd) {

    if(emptyInput([$uid_or_email, $pwd]) !== false) {
        header("location: /forms?error=emptyinput");
        exit;
    }
    
    loginUser($conn, $uid_or_email, $pwd);
    header("location: /");
    return;
}

function renderForms() {

    $html = 
    '<button type="button" name="form_choice" value="login" class="button">Log in</button>

    <section id="signup">
    <h2>Sign up</h2>
    <form action="forms_receive.php" method="post" id="signup_form">
    <input type="text" name="name" placeholder="Name...">
    <input type="text" name="email" placeholder="Email..">
    <input type="text" name="uid" placeholder="Username..">
    <input type="password" name="pwd" placeholder="Password..">
    <input type="password" name="pwdre" placeholder="Repeat...">
    <button type="submit" name="submit-signup" class="button">Sign Up</button>
    </form>
    </section>';

    echo $html;
    return;
}