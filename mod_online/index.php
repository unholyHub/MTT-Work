<?php
error_reporting(0);
include_once './controllers/Reservations.php';
if(empty($_GET['_date'])){
    $_GET['_date'] = date('Y-m-d',strtotime('now + 1 day'));
}
$reserve=new Reservations();
print $reserve->reserve_cvs((int)$_GET['_subline'], $_GET['_date'],$_GET['twoway'],$_GET['_date_back']);
?>
