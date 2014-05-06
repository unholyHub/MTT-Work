<?php
include_once './controllers/Invoices.php';
$invoices=new Invoices();
print $invoices->OK_msg();
?>
