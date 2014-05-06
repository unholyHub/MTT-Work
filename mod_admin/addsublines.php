<?php
include_once 'controllers/Sublines.php';

$sublines=new Sublines();
if(isset($_POST['submit_subline'])){
    $sublines->save_all($_POST[subline],isset($_GET[back]));
}else{
    print $sublines->price_table_new((int)$_GET['line'],isset($_GET[back]));
}

?>
