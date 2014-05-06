<?php
include_once './controllers/Reservations.php';

$reserveh=new Reservation();
$reserve=new Reservations();
if(isset($_POST[add_place_date_back])){
    $reserve=$reserveh->find(array(id=>(int)$_POST[rid]));
    print $reserve->finalize_ticket($_POST[date],$_POST[place],$_POST[subline_id]);
}else{
    if(isset($_GET[rid])){
        $reserve=$reserveh->find(array(id=>(int)$_GET[rid]));
    }
    print $reserve->finalize_ticket_form($_GET[_date],$_GET[new_subline]);
}
?>
