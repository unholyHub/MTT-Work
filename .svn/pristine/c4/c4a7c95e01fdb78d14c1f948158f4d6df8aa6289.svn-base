<?php
include_once './controllers/Reservations.php';

$reserve=new Reservations();
if(isset($_POST[add_number])){
    $resH=new Reservation();
    $reserve=$resH->find(array(id=>(int)$_POST[rid]));
    $reserve->add_ticket_number($_POST[ticket_number]);
}else{
    print $reserve->add_ticket_number_form();
}
?>
