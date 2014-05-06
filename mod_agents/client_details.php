<?php
include_once './controllers/Reservations.php';

$reserve=new Reservation();
$reservation=new Reservations();
/*if(isset($_POST[save_client_details])){
        print $reservation->save_client_details($_POST[reserve]);
}else{
    if(isset($_GET[rid])){
        $reservation=$reserve->find(array(id=>(int)$_GET[rid]));
        print $reservation->edit_client_details();
    }
}*/
$reservation=$reserve->find(array(id=>(int)$_GET[rid]));
print $reservation->edit_ticket_info_cvs($_GET);
?>
