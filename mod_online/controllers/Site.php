<?php
/* 
 * 
 */
include_once 'gui.php';
class Site extends gui{
    function __construct() {
        
    }

    public function terms_of_travel(){
        $this->page_contents=locales::$text[terms_of_travel];
        return $this->show_page();
    }

    public function general_conditions(){
        $this->page_contents=locales::$text[general_conditions];
        return $this->show_page();
    }

}
?>
