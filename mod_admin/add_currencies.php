<?php
include_once './controllers/Currencies.php';
$currencies=new Currencies();
if(isset($_POST[add_currency])){
    print $currencies->create($_POST[currencies]);
}else{
    print $currencies->add_currency();
}

?>
