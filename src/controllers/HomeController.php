<?php
include 'Controller.php';

class HomeController extends Controller {
    private $_entModel;
    private $_userModel;

    public function __construct($entModel, $userModel) {
        $this->_entModel = $entModel;
        $this->_userModel = $userModel;
    }

    public function render_home($clientUser) {
        $popular  = $this->_entModel->fetch_popular();
        $activity  = $this->_userModel->fetch_activity($clientUser);
        ob_start();
        $this->render('home'); // Ladda in main view-content.
        $content = ob_get_clean(); // Spara content inför layout.
        include 'views/layout.php'; // Ladda in layout
    }
}