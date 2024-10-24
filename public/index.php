<?php
    session_start(); // Tillåt sessions.
    include '../src/core/Conn.php'; // Databaskoppling.
    include '../src/core/Router.php';
    include '../src/core/utility.php'; // Generella metoder.

    include '../src/models/User.php'; // User model.
    include '../src/controllers/UserController.php'; // User controller.

    if(!$_SESSION['loggedIn']) { // Om ej inloggad.
        header('Location: /auth');
        exit; 
    }

    // Request för simpel routing.
    $request = $_SERVER['REQUEST_URI'];

    if(preg_match('/\/users\/[\w-]+/', $request, $matches)) {
        $userModel = new User($conn);
        $userController = new UserController($userModel);
        $userController->renderProfile($matches[1]);
    } elseif($request === '/users/create') {
        $userModel = new User($conn);
        $userController = new UserController($userModel);
        $usersController->createUser();
    } else {
        http_response_code(404);
        echo "404 Not Found";
    }

    if(preg_match('/\/', $request, $matches)) {
        $homeController->showHome();
    }


    if(isset($_SESSION['user-id'])) {
        // Om du är inloggad.
        $recent = fetchRecent($conn, $_SESSION['user-id']);
        // Om funktionen hittar aktivitet från de du följer.
        renderListRecent($recent);
    } 
    if(isset($_GET['popular'])) {
        $popular = fetchPopular($conn, $_GET['popular']);
        renderListPopular($popular, $_GET['popular']);
    } else {
        $popular = fetchPopular($conn, 'week');
        renderListPopular($popular, 'week');
    }
?> 
