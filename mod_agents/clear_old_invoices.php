<?php
include_once './controllers/Invoices.php';
$inv=new Invoice();
$inv->clear_old_invoices();
?>
