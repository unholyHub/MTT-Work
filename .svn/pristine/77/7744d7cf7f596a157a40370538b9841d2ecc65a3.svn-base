<?php
include_once './controllers/Reservations.php';
include_once './controllers/Invoices.php';
include_once '../mod_borica/Borica.php';

if(!isset($_SESSION['buffer_id'])){
    die('<h1 style="color:red; text-align:center">Поръчката ви отне много време. Моля опитайте отново.<br /> Your order took too long. Please try again.</h1>');
}

$invoice=new Invoices();
$invc=new Invoice();
$invoice_id=$invoice->create_invoice($_SESSION['buffer_id']);
$invoice_elem=new Invoice_element();
$invoices=new Invoice_elements();
$elements=$invoice_elem->find(array('all'=>1,'where'=>"invoice_id=$invoice_id"));
$reserve=new Reservation();
$reserve->clear_buffers($_SESSION['buffer_id']);

$description = 'Ticked Purchase With Invoice: #'.$invoice_id;

$data = new Borica();

$price = ((float)$invoices->calculate_sum($elements));
$language = strtoupper(isset($_SESSION['locale'])?$_SESSION['locale']:'bg');

$req = $data->createRequest($invoice_id, $description, $price, $language);

header("Location: {$req['url']}");

?>
