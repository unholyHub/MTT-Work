<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InvoiceSession
 *
 * @author alexander
 */
include_once 'gui.php';
include_once './controllers/Invoices.php';
include_once './controllers/Invoice_elements.php';
include_once './controllers/Session.php';
include_once './controllers/Reservations.php';

class InvoiceSession extends gui {

    public function __construct() {
        Session::user_auth();
        parent::__construct();
    }

    static function create() {
        if (!isset($_SESSION['invoice'])) {
            $invoice = new Invoice();
            $_SESSION['invoice']['id'] = $invoice->insert();
            $_SESSION['invoice']['elements'] = array();
        }
    }

    static function addInvoiceElement($reservationId) {
        if (!isset($_SESSION['invoice']) || !isset($_SESSION['invoice']['id'])) {
            self::create();
        }

        $ie = new Invoice_element();
        $ieCtrl = new Invoice_elements();

        $ieCtrl->invoice_id = $_SESSION['invoice']['id'];
        $ieCtrl->reservation_id = $reservationId;


        $_SESSION['invoice']['elements'][] = $ie->insert($ieCtrl);
    }

    public function index() {

        $this->page_contents = '';
        $data = array(
            '<div >',
            "<h1 class='invoiceNumber' >Invoice #{$_SESSION['invoice']['id']}</h1>",
            '</div>',
            '<div style="text-align: center;margin: 16px auto;">',
            '<a  href="./clear_invoice_session.php"><button  class="invoice" >' . locales::$text['clear_invoice'] . '</button></a>',
            '</div>'
        );

        if (isset($_SESSION['invoice']) && count($_SESSION['invoice']['elements'])) {
            $reservationsHandler = new Reservation();
            $reservations = $reservationsHandler->find(array(
                'all' => true,
                'where' => 'id IN (select reservation_id from invoice_elements where id in(null, ' . implode(',', $_SESSION['invoice']['elements']) . '))'
            ));

            $discount = new Discount();

            $sum = 0;
            foreach ($reservations as $reservation) {
                $disc = $discount->find(array(id => $reservation->discount_id));
                $sum = $sum + $reservation->price;

                $data[] = '<table class="divBox" id="parent"> ';
                $data[] = '<tr>';
                $data[] = '<td colspan="2" align="center"><a href="./delete_invoice_session_element.php?id=' . $reservation->id . '"><button class="invoice">' . locales::$text[table_date_delete_place] . '</button></a><td>';
                $data[] = '</tr>';
                $data[] = '<tr>';
                $data[] = '<th>' . locales::$text[report_place_back] . '</th>';
                $data[] = "<td>$reservation->place</td>";
                $data[] = '</tr>';
                $data[] = '<tr>';
                $data[] = '<th>' . locales::$text[report_ticket] . '</th>';
                $data[] = "<td>".($reservation->ticket_type == 0 || $reservation->ticket_type == 2 ? locales::$text[none] : $reservation->place_back)."</td>";
                $data[] = '</tr>';
                $data[] = '<tr>';
                $data[] = '<th>' . locales::$text[report_ticket_type] . '</th>';
                $data[] = '<td>'.($reservation->ticket_type == 0 ? locales::$text[ticket_oneway] : ($reservation->ticket_type == 2 ? locales::$text[ticket_opened_twoway] : locales::$text[ticket_twoway])).'</td>';
                $data[] = '</tr>';
                $data[] = '<tr>';
                $data[] = '<th>' . locales::$text[report_name] . '</th>';
                $data[] = '<td>'.ucwords($reservation->passenger_name).'</td>';
                $data[] = '</tr>';
                $data[] = '<tr>';
                $data[] = '<th>' . locales::$text[report_passport] . '</th>';
                $data[] = '<td>'.$reservation->passenger_passpor.'</td>';
                $data[] = '</tr>';
                $data[] = '<tr>';
                $data[] = '<th>'.locales::$text[report_discount].'</th>';
                $data[] = '<td>'.($reservation->discount_id == 0 ? locales::$text[none] : (locales::$current_locale == 'bg' ? $disc->name_bg : $disc->name_en)).'</td>';
                $data[] = '</tr>';
                $data[] = '<tr>';
                $data[] = '<th>'.locales::$text[report_date].'</th>';
                $data[] = '<td>'.date('d.m.Y', strtotime($reservation->date)).'</td>';
                $data[] = '</tr>';
                $data[] = '<tr>';
                $data[] = '<th>'.locales::$text[report_date_back].'</th>';
                $data[] = '<td>'.($reservation->ticket_type == 0 ? locales::$text[none] : date('d.m.Y', strtotime($reservation->date_back))).'</td>';
                $data[] = '</tr>';
                $date[] = '<tr>';
                $data[] = '<th>'.locales::$text[report_price].'</th>';
                $data[] = '<td>'.$reservation->price.'</td>';
                $data[] = '</tr>';
                $data[] = '</table>';
            }
        }

        if (count($reservations)) {
            
            $data[] = '<table style="background-color:white;border:none;border-spacing:10px;" id="parent"> ';
                $data[] = '<tr>';
                    $data[] = '<th>' . locales::$text['table_total_price'] . '</th>';
                    $data[] = "<td><span class='float-right'>$sum " . locales::$text['report_currency'] . "</span> </td>";
                $data[] = '</tr>';
                $data[] = '<tr>';
                    $data[] = "<td colspan='2'><button class='invoice float-right'>" . locales::$text['pay_invoice'] . "</button></td>";
                $data[] = '</tr>';
            $data[] = '</table>';
            $data[] = '<hr />';
         } else {
            $data[] = '<div align="center">' . locales::$text[none] . '</div>';
        }

        $this->page_contents .= '<div>' . implode("\n", $data) . '</div>';
        echo $this->show_page();
    }

    public function deleteElement() {
        if (isset($_SESSION['invoice']) && count($_SESSION['invoice']['elements'])) {
            $id = (int) $_GET['id'];
            $invoiceElement = new Invoice_element();
            $reservationHandler = new Reservation();

            $elements = $invoiceElement->find(array('all' => true, 'where' => "reservation_id='$id' and invoice_id='{$_SESSION['invoice']['id']}'"));
            foreach ($elements as $element) {
                $invoiceElement->query($result, "DELETE FROM invoice_elements WHERE id='$element->id'");
                unset($_SESSION['invoice']['elements'][array_search($element->id, $_SESSION['invoice']['elements'])]);
            }

            $reservationHandler->query($result, "DELETE FROM reservations WHERE id='$id'");
        }

        header('location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function clear() {
        if (isset($_SESSION['invoice']['id'])) {
            $invoice = new Invoice();

            $invoice->delete($_SESSION['invoice']['id']);

            $_SESSION['invoice']['elements'] = array();
        }

        header('location: ' . $_SERVER['HTTP_REFERER']);
    }

}

?>
