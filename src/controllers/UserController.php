<?php
include_once 'Controller.php';
include_once './core/utility.php';

class UserController extends Controller {
    private $_userModel;

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

    public function create_user() {
        // Hantera givet formulär
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(any_empty($_POST)) {
                header("location: /forms?error=empty-input");
                exit;
            }
            if($this->invalid_handle($_POST['handle'])) {
                header("location: /forms?error=invalid-handle");
                exit;
            }
            if($this->invalid_email($_POST['handle'])) {
                header("location: /forms?error=invalid-email");
                exit;
            }
            if($this->pwd_match($_POST['pwd'], $_POST['pwdre'])) {
                header("location: /forms?error=different-passwords");
                exit;
            }
            if($this->_userModel->handle_exists($_POST['handle'], $_POST['email'])) {
                header("location: /forms?error=handle-taken");
                exit;
            }
            $this->_userModel->create($_POST);
            header('location: /home?success=created-user'); // Omdirigera efter sparning
            exit;
        }
    }

    public function login_user($handle, $pwd) {
        $user = $this->_userModel->fetch_by_handle($handle);
        if(any_empty([$handle, $pwd])) {
            header("location: /forms?error=empty-input");
            exit;
        }
        if(!$user) {
            header("location: /auth?error=wrong-login");
            exit;
        }
        if(!password_verify($pwd, $user['pwd'])) {
            header("location: /auth?error=wrong-login");
            exit;
        } else {
            session_start();
            $_SESSION['user-id'] = $user['id'];
            $_SESSION['user-handle'] = $user['handle'];
            header("location: /?success=logged-in");
            exit;
        }
    }
}