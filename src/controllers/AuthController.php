<?php
class AuthController extends Controller {
    // protected $_userModel;

    /* public function __construct($userModel) {
        $this->_userModel = $userModel;
    } */

    public function render_forms() {
        ob_start();
        $this->render('auth');
        $content = ob_get_clean();
        include 'views/layout.php';
    }
}