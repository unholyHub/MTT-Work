<?php
require_once './models/Invoice.php';
require_once './controllers/Invoices.php';

$orderID = 0;
if(isset($_GET['eBorica'])) {
    require_once '../mod_borica/Borica.php';
    $borica = new Borica();
    $response = $borica->getResponse($_GET['eBorica']);
    $orderID = (int) $response['ORDER_ID'];
} else {
    $orderID = $_GET['orderID'];
    $responseCode = $_GET['responseCode'];
}

$inv = new Invoice();
$current_invoice = $inv->find(array('id' => $orderID));

if ($current_invoice != null) {
    $details = $current_invoice->invoice_details();
    if(isset($_GET['eBorica']) && $response['RESPONSE_CODE'] == '00') {
        $current_invoice->invoice_payment_recieved();
        $current_invoice->send_invioice_details($details);
        header("location: {$_SERVER['PHP_SELF']}?orderID={$response['ORDER_ID']}&responseCode={$response['RESPONSE_CODE']}");
        exit(0);
    }
    
    print $current_invoice->show_invoice_payement_details($responseCode, $details['message']);
}
?>
