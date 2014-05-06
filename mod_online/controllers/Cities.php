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
}
?>
