<?php
/* 
 * 
 */

include_once 'gui.php';
include_once './models/Currency.php';
class Currencies extends gui{
    public $id;
    public $currency;
    public $index;

    function __construct() {
        
    }

    function Currencies($id, $currency, $index) {
        $this->id = $id;
        $this->currency = $currency;
        $this->index = $index;
    }

    

    public function convert($sum, $convert_to){
        $currency=new Currency();
        $c=$currency->find(array(all=>'',where=>"currency='$convert_to'"));
        
        return round($sum/$c[0]->index,2);
    }
}
?>
