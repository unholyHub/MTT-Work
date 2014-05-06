<?php
include_once './controllers/Reservations.php';
if(isset($_SESSION[buffer_id])){
    $reserve=new Reservations();
    print $reserve->view_cart_buffer($_SESSION[buffer_id]);
}
?>
