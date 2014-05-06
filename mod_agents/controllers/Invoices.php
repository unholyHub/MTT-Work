<?php
/* 
 * 
 */
include_once 'gui.php';
include_once './models/Invoice.php';
include_once './controllers/Invoice_elements.php';
//include_once './controllers/Reservation_buffers.php';
include_once './controllers/Reservations.php';
class Invoices extends gui{
    public $id;
    public $created_on;

    function __construct() {
    }

    function Invoices($id, $created_on) {
        $this->id = $id;
        $this->created_on = $created_on;
    }

    public function create_invoice($buffer_id){
        $buffers=new Reservation_buffer();
        $buff=$buffers->find(array(all=>1,where=>"buffer_id='$buffer_id'"));
        $error='';
        if(sizeof($buff)>0){
            $new_invoice=new Invoice();
            
            $new_invoice_id = $new_invoice->insert();;

            $reservations=array();
            foreach($buff as $buffer){
                $reservations[]=$buffer->purchase();
            }
            if((int)$new_invoice_id>0){
                $invoice_elements=new Invoice_elements();
                foreach($reservations as $reservation_id){
                    if($reservation_id>0) $invoice_elements->add_element($new_invoice_id, $reservation_id);
                    else $error='<h1 style="color:red;">Едно или повече от местата които избрахте са заети. Моля опитайте отново / One or more of the places that you have selected are reserved. Please try again.</h1>';
                }
            }
        }
        if(strlen($error)) die($error);
        return $new_invoice_id;
    }

    public function invoice_payment_recieved(){
        $invoice_elem=new Invoice_element();
        $reservation=new Reservation();
        $elements=$invoice_elem->find(array(all=>1,where=>"invoice_id=$this->id"));
        foreach($elements as $element){
            $reservation->pay_for_ticket($element->reservation_id);
        }
    }
    
    public function show_invoice_payement_details($operation, $message){
        require_once './payment/payment_response_validation.php';
        $body = '<h1 align="center">'.  strtoupper(payment_response($operation)).'</h1>'.
                $message;
        $this->page_contents = $body;
        return $this->show_page();
    }
    
    public function invoice_details(){
        $invoice_elem=new Invoice_element();
        $reservations=new Reservation();
        $elements=$invoice_elem->find(array(all=>1,where=>"invoice_id=$this->id"));
        $currency=new Currencies();
        $subln=new Subline();
        $city=new City();
        $point=new Line_point();
        $emails=array();
        $message='<table width="100%" style="font-size:12px;" border="1">';
        foreach($elements as $element){
            $reservation=$reservations->find(array(id=>$element->reservation_id));
            $emails[$reservation->contact_email]=true;

            $subline=$subln->find(array(id=>$reservation->subline_id));
            $message.='<tr>
                            <th colspan="9" align="center">'.$city->city($point->find(array(id=>$subline->from_point_id))->city_id, (locales::$current_locale=='bg'?'bg':'en')).' - '.$city->city($point->find(array(id=>$subline->to_point_id))->city_id, (locales::$current_locale=='bg'?'bg':'en')).'</th>
                        </tr>
                        <tr>
                            <th align="left" valign="top">'.locales::$text[report_place].'</th>
                            <th align="left" valign="top">'.locales::$text[report_place_back].'</th>
                            <th align="left" valign="top">'.locales::$text[report_ticket_type].'</th>
                            <th align="left" valign="top">'.locales::$text[report_name].'</th>
                            <th align="left" valign="top">'.locales::$text[report_passport].'</th>
                            <th align="left" valign="top">'.locales::$text[report_price].'</th>
                            <th align="left" valign="top">'.locales::$text[report_discount].'</th>
                            <th align="left" valign="top">'.locales::$text[report_date].'</th>
                            <th align="left" valign="top">'.locales::$text[report_date_back].'</th>
                        </tr>
                        <tr>
                            <td>'.$reservation->place.'</td>
                            <td>'.($reservation->ticket_type==0?locales::$text[none]:$reservation->place_back).'</td>
                            <td>'.($reservation->ticket_type==0?locales::$text[ticket_oneway]:locales::$text[ticket_twoway]).'</td>
                            <td>'.$reservation->passenger_name.'</td>
                            <td>'.$reservation->passenger_passpor.'</td>
                            <td>'.$reservation->price.' BGN / '.$currency->convert($reservation->price, 'eur').' EUR</td>
                            <td>'.($reservation->discount_id==0?locales::$text[none]:(locales::$current_locale=='bg'?$disc->name_bg:$disc->name_en)).'</td>
                            <td>'.date('d.m.Y',strtotime($reservation->date)).'</td>
                            <td>'.($reservation->ticket_type==0?locales::$text[none]:date('d.m.Y',strtotime($reservation->date_back))).'</td>
                       </tr>';
        }
        $message.='</table><br />
        '.locales::$text[terms_of_travel];
        return array(
            'message'=>$message,
            'emails'=>$emails
        );
    }

    public function send_invioice_details($details){
        $emails = $details['emails'];
        $message = $details['message'];
        foreach($emails as $email=>$val){
            $this->send_email($email, $message);
        }
    }

    private function send_email($email,$message){
        $header='From: ' . Env::SalesMail . "\r\n" .'MIME-Version: 1.0' . "\r\n";
        $header .= 'Content-type: text/html; charset="utf-8"' . "\r\n";
        $header .= 'Content-Transfer-Encoding: 8bit';
        $subject='Purchase confirmed';
        $message = $message;

        if(mail($email, $subject, $message, $header)) {
            return 0;
        }else{
            return -1;
        }
    }

    public function OK_msg(){
        $this->page_contents='<h2 align="center" style="margin-top:80px; color:red;">'.locales::$text[ok_msg].'</h2>';

        return $this->show_page();
    }
}
?>
