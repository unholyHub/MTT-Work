<?php
include_once './controllers/Cities.php';

$cities=new Cities();
if(isset($_GET[dcity])){
    $cities->destroy((int)$_GET[dcity]);
}else{
    if(isset($_POST[save_city])){
        print $cities->save($_POST[city]);
    }else{
        print $cities->list_cities();
    }
}


?>
