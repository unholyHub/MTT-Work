<?php
include_once './controllers/Cities.php';

$cities=new Cities();
if(isset($_POST[save_city]) && isset($_GET[id])){
    print $cities->update((int)$_GET[id], $_POST[city][name]);
}else{    
    print $cities->edit_city($_GET[id]);
}
?>
