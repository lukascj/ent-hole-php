<?php
    session_start(); // Tillåt sessions
    include_once './core/conn.php'; // Databaskoppling
    include_once './core/utility.php'; // Generella funktioner
    include_once './core/router.php'; // Router klass
    include_once './models/Ent.php';
    include_once './models/User.php';
    include_once './controllers/UserController.php';
    include_once './controllers/HomeController.php';

    // Ladda environment-variabler
    load_env();
    // Koppla till databasen
    $conn = create_conn();

    // Request för simpel routing
    $requestPath = $_SERVER['REQUEST_URI'];
    $splitPath = explode('/', $request);
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    print_r($splitPath);

    if($requestPath != '/auth') {
        // Gå till inlogg-formulär om ej inloggad
        if(!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
            header('location: /auth?error=not-logged-in');
            exit; 
        }
    }
    if($requestPath === '/') {
        $userModel = new User($conn);
        $entModel = new Ent($conn);
        $homeController = new HomeController($entModel, $userModel);
        $homeController->render_home(['id'=>1, 'handle'=>'user1']);
    }
    if($splitPath[1] === 'users') {
        $userModel = new User($conn);
        $userController = new UserController($userModel);
        if($requestPath === '/users/create') {
            $userController->create_user();
        } elseif(preg_match('/\/users\/[\w-]+/', $request, $matches)) {
            $userController->render_profile($matches[1]);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }


?> 
