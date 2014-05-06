<?php
/* 
 * 
 */

include_once 'gui.php';
include_once './models/City.php';

class Cities extends gui{
    public $ID;
    public $lang;
    public $ID_city;
    public $city;

    function __construct() {
    }

    function Cities($ID, $lang, $ID_city, $city) {
        $this->ID = $ID;
        $this->lang = $lang;
        $this->ID_city = $ID_city;
        $this->city = $city;
    }
    
    function search_form() {
        $this->city = new City();
        if(!isset($_POST['search'])) {
            if($_SESSION['locale'] == 'en') {
                $_POST['search']['from'] = 'Plovdiv';
                $_POST['search']['to'] = 'Thessaloniki';
            } else {
                $_POST['search']['from'] = 'Пловдив';
                $_POST['search']['to'] = 'Солун';
            }
        }
        require dirname(dirname(__DIR__)) . '/api/BTMLibApi.php';
        
        $btmApi = new BTMLibApi($_POST['search']['from'], $_POST['search']['to'], $_SESSION['locale']);
        $btmApi->loadDestinations();
        $this->results = $btmApi->data();
        
        $this->render('./templates/search_form.php');
    }
}
?>
