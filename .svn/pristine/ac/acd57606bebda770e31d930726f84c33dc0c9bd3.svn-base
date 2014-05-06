<?php
include_once './controllers/Invoices.php';
if(isset($_SESSION['buffer_id'])){
    $invoice=new Invoices();
    $invoice->create_invoice($_SESSION[buffer_id]);
}
?>
