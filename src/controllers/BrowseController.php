<?php
class BrowseController extends Controller {
    protected $_entModel;
    protected $_userModel;

    public function __construct($entModel, $userModel) {
        $this->_entModel = $entModel;
        $this->_userModel = $userModel;
    }

    public function render_browse($type = 'default') {
        /* 
            IDEER: 
            - Popular in genre listor baserat på favorit-genre
            - Ent-typer
        */
        
        $page = 'browse';
        $popular = [];
        $highestRated = [];
        $random = [];

        switch($type) {
            case 'users':
                
                break;
            default: // case 'default'
                $popular['all'] = $this->_entModel->fetch_popular();
                $popular['horror'] = $this->_entModel->fetch_popular_in_genre('horror');
                $random = $this->_entModel->fetch_random();
                $highestRated = $this->_entModel->fetch_highest_rated();
                break;
        }

        ob_start();
        $this->render($page, compact(
            'popular',
            'random',
            'highestRated'
        ));
        $content = ob_get_clean();
        include 'views/layout.php'; 
    }
}