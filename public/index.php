<?php
    session_start(); // Tillåt sessions.
    include '../src/core/conn.php'; // Databaskoppling.
    include '../src/core/Router.php';
    include '../src/core/utility.php'; // Generella metoder.

    include '../src/models/User.php'; // User model.
    include '../src/controllers/UserController.php'; // User controller.

    if(!$_SESSION['is_logged_in']) { // Om ej inloggad.
        header('Location: /auth');
        exit; 
    }

    // Request för simpel routing.
    $request = $_SERVER['REQUEST_URI'];

    if($request === '/users/create') {
        $user_model = new User($conn);
        $user_controller = new UserController($user_model);
        $user_controller->createUser();
    } elseif(preg_match('/\/users\/[\w-]+/', $request, $matches)) {
        $user_model = new User($conn);
        $user_controller = new UserController($user_model);
        $user_controller->renderProfile($matches[1]);
    } else {
        http_response_code(404);
        echo "404 Not Found";
    }

    if(preg_match('/\/', $request)) {
        $home_controller->showHome();
    }
?> 
