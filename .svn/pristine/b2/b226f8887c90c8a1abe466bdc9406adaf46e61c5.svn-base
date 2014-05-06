<?php
include_once './controllers/Currencies.php';

$currencies=new Currencies();
if(isset($_GET[dcid])){
    $currencies->destroy((int)$_GET[dcid]);
}

print $currencies->view_currencies();

?>
