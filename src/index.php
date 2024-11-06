<?php
    session_start(); // Tillåt sessions
    include_once './core/conn.php'; // Databaskoppling
    include_once './core/utility.php'; // Generella funktioner
    include_once './core/router.php'; // Router-klass
    include_once './models/Ent.php';
    include_once './models/User.php';
    include_once './controllers/UserController.php';
    include_once './controllers/HomeController.php';
    include_once './controllers/AuthController.php';

    // Ladda environment-variabler
    load_env();
    // Koppla till databasen
    $conn = create_conn();

    $request['uri'] = $_SERVER['REQUEST_URI'];
    $parsedUri = parse_url($request['uri']);
    $request['path'] = $parsedUri['path'];
    $request['split-path'] = explode('/', trim($request['path'], '/'));
    $request['method'] = $_SERVER['REQUEST_METHOD'];
    
    if($request['path'] != '/auth') {
        // Gå till inlogg-formulär om ej inloggad
        if(!isset($_SESSION['is-logged-in']) || !$_SESSION['is-logged-in']) {
            header('location: /auth');
            exit; 
        }
    }

    if($request['path'] == '/seed') {
        include_once './core/seed.php';
        echo "Running seed.php<br>";
        $seed = new Seed($conn);
        $seed->reset_db();
        $seed->apply_schema();
        $seed->populate();
        $seed->see_all();
    } 
    if($request['path'] === '/') {

        $userModel = new User($conn);
        $entModel = new Ent($conn);
        $homeController = new HomeController($entModel, $userModel);
        $homeController->render_home(1);
    } 
    if($request['path'] === '/auth') { 
        $authController = new AuthController();
        $authController->render_forms();
    } 
    if($request['split-path'][0] === 'users') {
        $userModel = new User($conn);
        $userController = new UserController($userModel);
        if($request['path'] === '/users/create') {
            $userController->create_user();
        } elseif(preg_match('/\/users\/[\w-]+/', $request['path'], $matches)) {
            $userController->render_profile($matches[1]);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
?> 
