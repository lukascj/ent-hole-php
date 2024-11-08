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
    include_once './controllers/BrowseController.php';

    // Ladda environment-variabler
    load_env();
    // Koppla till databasen
    $conn = create_conn();

    // Samla request-variabler att använda
    $request['uri'] = $_SERVER['REQUEST_URI'];
    $parsedUri = parse_url($request['uri']);
    $request['path'] = $parsedUri['path'];
    unset($parsedUri);
    $request['split-path'] = explode('/', trim($request['path'], '/'));
    $request['method'] = $_SERVER['REQUEST_METHOD'];

    // Hur man använder routern... inte helt fungerande.
    /* $router = new Router();
    $action = function($conn, $session) {
        $userModel = new User($conn);
        $entModel = new Ent($conn);
        $homeController = new HomeController($entModel, $userModel);
        $homeController->render_home($session['user_id']);
    };
    $router->get('/', $action);
    $router->dispatch($request); */

    // Routes
    if($request['method'] === 'GET') {

        /* --- GET ROUTES --- */

        $params = $_GET;
        if($request['path'] === '/') { 
            
            // Index
            protect_route($_SESSION);
            $userModel = new User($conn);
            $entModel = new Ent($conn);
            $homeController = new HomeController($entModel, $userModel);
            $homeController->render_home($_SESSION['user-id']);
            exit;

        } elseif(in_array($request['path'], ['/auth', '/forms'])) { 
            
            // Formulär - login / signup
            $authController = new AuthController();
            $authController->render_forms();
            exit;

        } elseif($request['path'] === '/seed') {

            // Seed
            // protect_route($_SESSION, 'admin');
            include_once './core/seed.php';
            echo "Running seed.php<br>";
            $seed = new Seed($conn);
            $seed->reset_db();
            $seed->apply_schema();
            $seed->populate();
            $seed->see_all();
            exit;

        } elseif($request['split-path'][0] === 'users') {

            // Users och subpaths
            if($request['path'] === '/users') {

                // Browse users
                $browseController = new BrowseController();
                $browseController->render_browse('users');
                exit;

            } elseif(preg_match('/\/users\/[\w-]+/', $request['path'], $matches)) {

                // User
                $userModel = new User($conn);
                $userController = new UserController($userModel);
                $userHandle = $matches[1];
                $userController->render_profile($userHandle);
                exit;

            }
        } elseif($request['path'] === '/logout') {

            unset($_SESSION['user-id']);
            unset($_SESSION['user-handle']);
            unset($_SESSION['logged-in']);
            header('location: /auth');
            exit;

        }
    } else if($request['method'] === 'POST') {

        /* --- POST ROUTES --- */

        $params = $_POST;
        if(in_array($request['path'], ['/users/create', '/signup'])) {

            // Recieve signup, create users
            $userModel = new User($conn);
            $userController = new UserController($userModel);
            $userController->create_user($params);
            header("location: /auth?success=user-created");
            exit;

        } elseif($request['path'] === '/login') {

            // Recieve login, read user
            $userModel = new User($conn);
            $userController = new UserController($userModel);
            $user = $userController->login($params['handle/email'], $params['pwd']);
            $_SESSION['user-id'] = $user['id'];
            $_SESSION['user-handle'] = $user['handle'];
            $_SESSION['logged-in'] = true;
            unset($user);
            header("location: /?success=logged-in");
            exit;

        }
    } else {

        http_response_code(404);
        echo "404 Not Found";
        
    }

?> 
