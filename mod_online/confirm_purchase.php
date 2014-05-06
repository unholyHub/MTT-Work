<?php
include_once './controllers/Reservations.php';
if(isset($_SESSION['buffer_id'])){
    $reserve=new Reservations();
    print $reserve->payment_confirm($_SESSION[buffer_id]);
}
?>
