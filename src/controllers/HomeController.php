<?php
include 'Controller.php';

class HomeController extends Controller {
    private $entModel;
    private $socialModel;

    public function __construct($entModel, $socialModel) {
        $this->entModel = $entModel;
        $this->socialModel = $socialModel;
    }

    public function renderHome($clientUser) {
        $popular  = $this->entModel->getPopular();
        $activity  = $this->socialModel->getActivity($clientUser);
        ob_start();
        $this->render('home'); // Ladda in main view-content.
        $content = ob_get_clean(); // Spara content inför layout.
        include 'views/layout.php'; // Ladda in layout
    }
}