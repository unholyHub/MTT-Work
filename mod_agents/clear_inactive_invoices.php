<?php
include_once './controllers/Invoices.php';
$inv=new Invoice();
$inv->clear_inactive_invoices();
?>
