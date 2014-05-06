<?php
include_once './controllers/Reservations.php';

$reserve=new Reservations();

print $reserve->print_ticket((int)$_GET['rid']);
?>
