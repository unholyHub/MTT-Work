<?php
/* 
 * 
 */

include_once 'gui.php';
include_once './models/Reservation_buffer.php';
include_once './controllers/Sublines.php';
include_once './controllers/Discounts.php';
include_once './models/City.php';

class Reservation_buffers extends gui {
    public $id;
    public $subline_id;
    public $back_subline_id;
    public $place;
    public $place_back;
    public $ticket_number;
    public $passenger_name;
    public $passenger_passpor;
    public $contact_phone;
    public $contact_email;
    public $price;
    public $discount_id;
    public $birthday;
    public $date;
    public $date_back;
    public $ticket_type;
    public $user_id;
    public $buffer_id;

    function __construct() {
    }

    function Reservation_buffers($id, $subline_id, $back_subline_id, $place, $place_back, $ticket_number, $passenger_name, $passenger_passpor, $contact_phone, $contact_email, $price, $discount_id, $birthday, $date, $date_back, $ticket_type, $user_id, $buffer_id) {
        $this->id = $id;
        $this->subline_id = $subline_id;
        $this->back_subline_id = $back_subline_id;
        $this->place = $place;
        $this->place_back = $place_back;
        $this->ticket_number = $ticket_number;
        $this->passenger_name = $passenger_name;
        $this->passenger_passpor = $passenger_passpor;
        $this->contact_phone = $contact_phone;
        $this->contact_email = $contact_email;
        $this->price = $price;
        $this->discount_id = $discount_id;
        $this->birthday = $birthday;
        $this->date = $date;
        $this->date_back = $date_back;
        $this->ticket_type = $ticket_type;
        $this->user_id = $user_id;
        $this->buffer_id = $buffer_id;
    }

//    public function view_cart_element(){
//        $disc='Няма';
//        $main_price=$this->price;
//
//        $subline=new Subline();
//        $current_subline=$subline->find(array(id=>$this->subline_id));
//        $points=new Line_point();
//        $point_from=$points->find(array(id=>$current_subline->from_point_id));
//        $point_to=$points->find(array(id=>$current_subline->to_point_id));
//        $city=new City();
//        $currency=new Currencies();
//        return '<tr>
//                    <td >'.$city->city($point_from->city_id, locales::$current_locale).' - '.$city->city($point_to->city_id, locales::$current_locale).'</td>
//                    <td >'.($this->ticket_type==0?locales::$text[ticket_oneway]:locales::$text[ticket_twoway]).'</td>
//                    <td >'.$this->place.'</td>
//                    <td >'.($this->place_back!=0?$this->place_back:locales::$text[none]).'</td>
//                    <td >'.$this->passenger_name.'</td>
//                    <td >'.$main_price.' BGN / '.$currency->convert($main_price, 'eur').' EUR</td>
//                    <td>'.date('d.m.Y',strtotime($this->date)).'</td>
//                    <td>'.($this->ticket_type!=0?date('d.m.Y',strtotime($this->date_back)):locales::$text[none]).'</td>
//                </tr>';
//    }
//
//    public function purchase(){
//        $rbuff=new Reservation_buffer();
//        return $rbuff->insert_to_main_list($this);
//    }
}
?>
