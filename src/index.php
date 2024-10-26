<?php
    session_start(); // Tillåt sessions

    include_once './core/conn.php'; // Databaskoppling
    //include './src/core/Router.php';
    include_once './core/utility.php'; // Generella metoder

    include_once './models/Ent.php'; // Ent model.
    include_once './models/User.php'; // User model.
    include_once './controllers/UserController.php'; // User controller
    include_once './controllers/HomeController.php'; // Home controller

    /* if(!$_SESSION['isLoggedIn']) { // Om ej inloggad
        header('Location: /auth');
        exit; 
    } */

    // Request för simpel routing
    $request = $_SERVER['REQUEST_URI'];
    $splitReq = explode('/', $request);

    if($splitReq[0] === 'users') {
        $userModel = new User($conn);
        $userController = new UserController($userModel);
        if($request === '/users/create') {
            $userController->create_user();
        } elseif(preg_match('/\/users\/[\w-]+/', $request, $matches)) {
            $userController->render_profile($matches[1]);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }

    if($request === '/') {
        $userModel = new User($conn);
        $entModel = new Ent($conn);
        $homeController = new HomeController($entModel, $userModel);
        $homeController->render_home(['id'=>1, 'handle'=>'user1']);
    }
?> 
