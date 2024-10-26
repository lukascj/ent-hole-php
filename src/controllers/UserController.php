<?php
include_once 'Controller.php';

class UserController extends Controller {
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    public function render_profile($handle) {
        $user = $this->userModel->find($handle);
        ob_start();
        $this->render('profile'); // Ladda in main view-content.
        $content = ob_get_clean(); // Spara content inför layout.
        include 'views/layout.php'; // Ladda in layout
    }

    public function create_user() {
        // Hantera angivet formulär.
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->save($_POST);
            header('Location: /users'); // Omdirigera efter sparning.
            exit;
        }
        include 'views/auth.php'; // Ladda formulär-view.
    }
}