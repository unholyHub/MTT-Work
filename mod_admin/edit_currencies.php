<?php
include_once './controllers/Currencies.php';
if(isset($_GET[cid])){
    $currency=new Currency();
    $currencies=$currency->find(array(id=>(int)$_GET[cid]));
    if(isset($_POST[add_currency])){
        print $currencies->update($_POST[currencies]);
    }else{
        print $currencies->add_currency();
    }
}
?>
