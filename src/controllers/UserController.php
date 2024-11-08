<?php
include_once 'Controller.php';
include_once './core/utility.php';

class UserController extends Controller {
    protected $_userModel;

    public function __construct($userModel) {
        $this->_userModel = $userModel;
    }

    public function render_profile($handle) {
        $user = $this->_userModel->find($handle);
        ob_start();
        $this->render('profile'); // Ladda in main view-content.
        $content = ob_get_clean(); // Spara content inför layout.
        include 'views/layout.php'; // Ladda in layout
    }

    // Kollar om användarnamnet använder godkända tecken
    public function invalid_handle($handle) {
        return !preg_match("/^[a-zA-Z0-9]*$/", $handle);
    }

    // Validerar emailen
    public function invalid_email($email) {
        return !filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Kollar så att lösenorden matchar
    public function pwd_match($pwd, $pwdre) {
        return $pwd !== $pwdre;
    }

    public function create_user($params) {
        // Hantera givet formulär
        if(any_empty($params, ['name', 'email', 'handle', 'pwd', 'pwdre'])) {
            header("location: /auth?error=empty-input");
            exit;
        }
        if($this->invalid_handle($params['handle'])) {
            header("location: /auth?error=invalid-handle");
            exit;
        }
        if($this->invalid_email($params['email'])) {
            header("location: /auth?error=invalid-email");
            exit;
        }
        if($this->pwd_match($params['pwd'], $params['pwdre'])) {
            header("location: /auth?error=different-passwords");
            exit;
        }
        if($this->_userModel->handle_taken($params['handle'])) {
            header("location: /auth?error=handle-taken");
            exit;
        }
        $this->_userModel->create($params);
        return;
    }

    public function login($handle, $pwd) {
        if(any_empty([$handle, $pwd])) {
            header("location: /auth?error=empty-input");
            exit;
        }
        $user = $this->_userModel->fetch($handle);
        if(!isset($user)) {
            header("location: /auth?error=wrong-login");
            exit;
        }
        if(!password_verify($pwd, $user['pwd'])) {
            header("location: /auth?error=wrong-login");
            exit;
        }
        unset($user['pwd']);
        return $user;
    }
}