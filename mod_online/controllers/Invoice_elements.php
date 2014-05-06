<?php
/* 
 * 
 */
include_once 'gui.php';
include_once './models/Invoice_element.php';
include_once './controllers/Reservations.php';
class Invoice_elements extends gui{
    public $id;
    public $invoice_id;
    public $reservation_id;

    function __construct() {
    }

    function Invoice_elements($id, $invoice_id, $reservation_id) {
        $this->id = $id;
        $this->invoice_id = $invoice_id;
        $this->reservation_id = $reservation_id;
    }

    public function add_element($invoice_id,$reservation_id){
        $new_element=new Invoice_element();
        $this->invoice_id=$invoice_id;
        $this->reservation_id=$reservation_id;
        $new_element->insert($this);
    }

    public function calculate_sum(array $elements){
        $reservation=new Reservation();
        $sum_price=0;
        foreach($elements as $element){
            $reserve=$reservation->find(array(id=>$element->reservation_id));
            $sum_price=$sum_price+$reserve->price;
        }
        return $sum_price;
    }

    public function view_invoice_elements(array $elements,$submit_url, $ezp_submit_url,$ENCODED,$CHECKSUM,$layout=false){
        $reservation=new Reservation();
        $currency=new Currencies();
        $sum_price=0;
        $this->page_contents='
                           <h1 align="center">'.locales::$text[tickets_confirm_purchase].'</h1>
                           <table align="center" style="font-size:12px; border:1px solid #113361;">
                            <tr>
                                <th align="left">'.locales::$text[report_destination].'</th>
                                <th align="left">'.locales::$text[report_ticket_type].'</th>
                                <th align="left">'.locales::$text[report_place].'</th>
                                <th align="left">'.locales::$text[report_place_back].'</th>
                                <th align="left">'.locales::$text[report_date].'</th>
                                <th align="left">'.locales::$text[report_date_back].'</th>
                                <th align="left">'.locales::$text[report_name].'</th>
                                <th align="left">'.locales::$text[report_price].'</th>
                            </tr>';
        foreach($elements as $element){
            $reserve=$reservation->find(array(id=>$element->reservation_id));
            $this->page_contents.=$reserve->view_reservation();
            $sum_price=$sum_price+$reserve->price;
        }
        $this->page_contents.='<tr>
                                    <td colspan="7" align="right">'.locales::$text[report_sum].':</td>
                                    <td>'.$sum_price.' BGN / '.$currency->convert($sum_price, 'eur').' EUR</td>
                               </tr>
                               <tr>
                                    <td colspan="8">'.locales::$text[general_terms].'</td>
                               </tr>
                               <tr>
                                    <td colspan="4" align="center">
                                        <form action="'.$submit_url.'" method=POST>
                                        <input type=hidden name=PAGE value="paylogin">
                                        <input type=hidden name=LANG value="'.(locales::$current_locale=='bg'?'bg':'en').'">
                                        <input type=hidden name=ENCODED value="'.$ENCODED.'">
                                        <input type=hidden name=CHECKSUM value="'.$CHECKSUM.'">
                                        <input type=hidden name=RES value="1">
                                        <input type=hidden name=URL_OK value="http://group-ood.com/reservations/paied.php">
                                        <input type=hidden name=URL_CANCEL value="http://group-ood.com/reservations/search.php">
                                        './*<input type="submit" value="'.locales::$text[tickets_buy].'" />*/'
                                        <input type="image" src="./images/epay.jpg"/>
                                        </FORM>
                                    </td>
                                    <td colspan="4" align="center">
                                        <form action="'.$ezp_submit_url.'" method=POST>
                                        <input type=hidden name=PAGE value="paylogin">
                                        <input type=hidden name=LANG value="'.(locales::$current_locale=='bg'?'bg':'en').'">
                                        <input type=hidden name=ENCODED value="'.$ENCODED.'">
                                        <input type=hidden name=CHECKSUM value="'.$CHECKSUM.'">
                                        <input type=hidden name=RES value="1">
                                        <input type=hidden name=URL_OK value="http://group-ood.com/reservations/paied.php">
                                        <input type=hidden name=URL_CANCEL value="http://group-ood.com/reservations/search.php">
                                        './*<input type="submit" value="'.locales::$text[tickets_buy].'" />*/'
                                        <input type="image" src="./images/ezp.png"/>
                                        </FORM>
                                    </td>
                               </tr>
                               </table>';
        if($layout) return $this->show_page();
        else return $this->page_contents;
    }
}
?>
