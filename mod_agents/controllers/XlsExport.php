<?php

include_once 'gui.php';
include_once './controllers/Session.php';
include_once './controllers/Reservations.php';
include_once './controllers/Line_points.php';
include_once './controllers/Invoices.php';
include_once './controllers/Invoice_elements.php';

class XlsExport {
    const CellDelimeter = ';';
    const RowDelimeter = "\n";
    public function __construct() {
        Session::user_auth();
    }
    
    public function init() {
        if(isset($_GET['type'])) {
            if(method_exists($this, $_GET['type'])) {
                $this->{$_GET['type']}();
            }
        }
    }
    
    private function __array2csv(array $array = array()) {
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"export_".  uniqid().".xls\"");
        
        foreach($array as $row) {
            echo implode(self::CellDelimeter, $row) . self::RowDelimeter;
        }
    }
    
    private function __noAccess() {
        $this->template_name='reports';
        $this->page_contents='<h2 align="center" style="color:red;">'.locales::$text[no_permission].'</h2>';
    }
    
    private function date_subline_report() {
        $date = $_GET['date'];
        $subline_id =  (int)$_GET['subline'];
        
        $subl=new Subline();
        $point=new Line_point();
        $subline=$subl->find(array(id=>(int)$_GET['subline']));
        $city=new City();
        $currency=new Currencies();
        
        $data = array(
            array(locales::$text[reports_date_subline]),
            array(null),
            array(null, locales::$text[report_reg_num]),
            array(null,locales::$text[report_driver1]),
            array(null, locales::$text[report_driver2]),
            array(null, locales::$text[report_stewart]),
            array(null, locales::$text[report_destination]),
            array(null, locales::$text[report_date], date('d.m.Y',strtotime($_GET['date']))),
            array(),
            array(
                '#',
                locales::$text['report_place'],
                locales::$text['report_ticket'],
                locales::$text['report_name'],
                locales::$text['report_price'].' (BGN)',
                locales::$text['report_price'].' (EUR)',
                locales::$text['report_reserved_by'],
                locales::$text['report_destination'],
                locales::$text['last_update']
            )
        );
        
        $user=new User();

        $reservations = $subline->get_reserved_objects($date);
        $i = 1;
        foreach ($reservations as $reservation) {

            if ($reservation->date == $date) {
                $current_subline = $subl->find(array(id => $reservation->subline_id));
            } elseif ($reservation->date_back == $date) {
                $current_subline = $subl->find(array(id => $reservation->back_subline_id));
            }

            $user_name = $user->find(array(id => $reservation->user_id));

            $data[] = array(
                $i,
                $reservation->place,
                $reservation->ticket_number,
                ucwords($reservation->passenger_name),
                $reservation->price,
                $currency->convert($reservation->price, 'eur'),
                $user_name->user,
                $city->city($point->find(array(id => $current_subline->from_point_id))->city_id, locales::$current_locale) . ' - ' . $city->city($point->find(array(id => $current_subline->to_point_id))->city_id, locales::$current_locale),
                date('d.m.y H:i', strtotime($reservation->last_update))
            );
            
            $i++;
        }
        
        $this->__array2csv($data);
    }
    
    private function subline_report() {
        if(!Session::access_is_allowed('destination_report_access')) {
           $this->__noAccess();
        };
        
        $from = $_GET['date_from'];
        $to = $_GET['date_to'];
        $subline_id = (int)$_GET['subline'];
        
        $reserv=new Reservation();
        
        if(Session::access_is_allowed(all_sales_access)){
            //$reservations=$reserv->find(array(all=>1,where=>"payed<2 AND ((subline_id=$subline_id AND `date` BETWEEN '$from' AND '$to') OR (back_subline_id=$subline_id AND date_back BETWEEN '$from' AND '$to'))",order=>'`date_back`,`date` DESC'));
            $reservations=$reserv->find(array(all=>1,where=>"payed > 0 AND payed < 2 AND ((subline_id=$subline_id) OR (back_subline_id=$subline_id)) AND created BETWEEN '$from 00:00:00' AND '$to 23:59:59'",order=>'`created`,`date` DESC'));
        }else{
            if(Session::access_is_allowed(show_epay_sales_access)){
                //$reservations=$reserv->find(array(all=>1,where=>"payed<2 AND (user_id=0 OR user_id=".Session::current_user()->id.") AND ((subline_id=$subline_id AND `date` BETWEEN '$from' AND '$to') OR (back_subline_id=$subline_id AND date_back BETWEEN '$from' AND '$to'))",order=>'`date_back`,`date` DESC'));
                $reservations=$reserv->find(array(all=>1,where=>"payed > 0 AND payed < 2 AND (user_id=0 OR user_id=".Session::current_user()->id.") AND ((subline_id=$subline_id) OR (back_subline_id=$subline_id)) AND created BETWEEN '$from 00:00:00' AND '$to 23:59:59'",order=>'`created`,`date` DESC'));
            }else{
                //$reservations=$reserv->find(array(all=>1,where=>"payed<2 AND (user_id=".Session::current_user()->id.") AND ((subline_id=$subline_id AND `date` BETWEEN '$from' AND '$to') OR (back_subline_id=$subline_id AND date_back BETWEEN '$from' AND '$to'))",order=>'`date_back`,`date` DESC'));
                $reservations=$reserv->find(array(all=>1,where=>"payed > 0 AND payed < 2 AND (user_id=".Session::current_user()->id.") AND ((subline_id=$subline_id) OR (back_subline_id=$subline_id)) AND created BETWEEN '$from 00:00:00' AND '$to 23:59:59'",order=>'`created`,`date` DESC'));
            }
        }
        $discount=new Discount();
        $user=new User();
        $currency=new Currencies();
        
        $data = array(
            array(locales::$text[reports_sublines_date_to_date]),
            array(
                locales::$text[report_place],
                locales::$text[report_place_back],
                locales::$text[report_ticket],
                locales::$text[report_ticket_type],
                locales::$text[report_name],
                locales::$text[report_passport],
                locales::$text[report_price].' (BGN)',
                locales::$text[report_price].' (EUR)',
                locales::$text[report_discount],
                locales::$text[report_date],
                locales::$text[report_date_back],
                locales::$text[report_reserved_by]
            )
        );
        
        $sum = 0;
        foreach ($reservations as $reservation) {
            $disc = $discount->find(array(id => $reservation->discount_id));

            $usr = $user->find(array(id => $reservation->user_id));
            $sum = $sum + $reservation->price;
            $data[] = array(
                $reservation->place,
                ($reservation->ticket_type == 0 || $reservation->ticket_type == 2 ? locales::$text[none] : $reservation->place_back),
                ($reservation->ticket_number == '' ? locales::$text[none] : $reservation->ticket_number),
                ($reservation->ticket_type == 0 ? locales::$text[ticket_oneway] : ($reservation->ticket_type == 2 ? locales::$text[ticket_opened_twoway] : locales::$text[ticket_twoway])),
                ucwords($reservation->passenger_name),
                $reservation->passenger_passpor,
                $reservation->price,
                $currency->convert($reservation->price, 'eur'),
                ($reservation->discount_id == 0 ? locales::$text[none] : (locales::$current_locale == 'bg' ? $disc->name_bg : $disc->name_en)),
                date('d.m.Y', strtotime($reservation->date)),
                ($reservation->ticket_type == 0 ? locales::$text[none] : date('d.m.Y', strtotime($reservation->date_back))),
                ($reservation->user_id == 0 ? Env::OnlineSysName : $usr->user)
            );
        }
        
        $subln=new Subline();
        $subline=$subln->find(array(id=>$subline_id));
        $point=new Line_point();
        $city=new City();
        
        $data[] = array(
            null, null, null, null, null, null, null, null, 
            locales::$text[report_sum].': '.$sum,
            locales::$text[reports_from_date].' '.date('d.m.Y',strtotime($from)).' '.locales::$text[reports_to_date].' '.date('d.m.Y',strtotime($to)),
            locales::$text[tickets_destination].''.$city->city($point->find(array(id=>$subline->from_point_id))->city_id,locales::$current_locale).' - '.$city->city($point->find(array(id=>$subline->to_point_id))->city_id,locales::$current_locale)
        );
        
        $this->__array2csv($data);
    }
    
    private function date_to_date_report() {
        if(!Session::access_is_allowed('date2date_report_access')) $this->__noAccess ();
        $from = $_GET['date_from'];
        $to = $_GET['date_to'];
        $subline_id = $_GET['subline'];
        
        $reserv=new Reservation();
        if(Session::access_is_allowed(all_sales_access)){
            $reservations=$reserv->find(array(all=>1,where=>"payed > 0 AND payed<2 AND ((`date` BETWEEN '$from 00:00:00' AND '$to 23:59:59') OR (date_back BETWEEN '$from 00:00:00' AND '$to 23:59:59'))"));
        }else{
            if(Session::access_is_allowed(show_epay_sales_access)){
                $reservations=$reserv->find(array(all=>1,where=>"payed > 0 AND payed<2 AND (user_id=0 OR user_id=".Session::current_user()->id.") AND ((`date` BETWEEN '$from 00:00:00' AND '$to 23:59:59') OR (date_back BETWEEN '$from 00:00:00' AND '$to 23:59:59'))"));
            }else{
                $reservations=$reserv->find(array(all=>1,where=>"payed > 0 AND payed<2 AND (user_id=".Session::current_user()->id.") AND ((`date` BETWEEN '$from 00:00:00' AND '$to 23:59:59') OR (date_back BETWEEN '$from 00:00:00' AND '$to 23:59:59'))"));
            }
        }
        $discount=new Discount();
        $user=new User();
        $currency=new Currencies();
        
        $data = array(
            array(locales::$text[reports_date_to_date]),
            array(
                locales::$text[report_place],
                locales::$text[report_place_back],
                locales::$text[report_ticket],
                locales::$text[report_ticket_type],
                locales::$text[report_name],
                locales::$text[report_passport],
                locales::$text[report_price] . ' (BGN)',
                locales::$text[report_price] . ' (EUR)',
                locales::$text[report_discount],
                locales::$text[report_destination],
                locales::$text[report_reserved_by]
            )
        );
        
        $sum=0;
        $subln=new Subline();
        $point=new Line_point();
        $city=new City();
        foreach($reservations as $reservation) {
            $subline=$subln->find(array(id=>$reservation->subline_id));
            $disc=$discount->find(array(id=>$reservation->discount_id));
            $usr=$user->find(array(id=>$reservation->user_id));
            $sum=$sum+$reservation->price;
                $data[] = array(
                    $reservation->place,
                    ($reservation->ticket_type==0 || $reservation->ticket_type==2?locales::$text[none]:$reservation->place_back),
                    ($reservation->ticket_number==''?locales::$text[none]:$reservation->ticket_number),
                    ($reservation->ticket_type==0?locales::$text[ticket_oneway]:($reservation->ticket_type==2?locales::$text[ticket_opened_twoway]:locales::$text[ticket_twoway])),
                    ucwords($reservation->passenger_name),
                    $reservation->passenger_passpor,
                    $reservation->price,
                    $currency->convert($reservation->price, 'eur'),
                    ($reservation->discount_id==0?locales::$text[none]:(locales::$current_locale=='bg'?$disc->name_bg:$disc->name_en)),
                    $city->city($point->find(array(id=>$subline->from_point_id))->city_id,locales::$current_locale).' - '.$city->city($point->find(array(id=>$subline->to_point_id))->city_id,locales::$current_locale),
                    ($reservation->user_id==0?Env::OnlineSysName:$usr->user)
                );
        }
        
        $data[] = array(
            null, null, null, null, null, null, null, null, 
            locales::$text[report_sum].': '.$sum,
            locales::$text[reports_from_date].' '.date('d.m.Y',strtotime($from)).' '.locales::$text[reports_to_date].' '.date('d.m.Y',strtotime($to))
        );
        
        $this->__array2csv($data);
    }
    
    private function agent_report() {
        if(!Session::access_is_allowed(agent_report_access)) $this->__noAccess ();
        
        $user_id = (int)$_GET['agent'];
        $from = $_GET['date_from'];
        $to = $_GET['date_to'];
        
        $reserv=new Reservation();
        //$reservations=$reserv->find(array(all=>1,where=>"user_id=$user_id AND ((`date` BETWEEN '$from' AND '$to') OR (date_back BETWEEN '$from' AND '$to'))"));
        $reservations=$reserv->find(array(all=>1,where=>"user_id=$user_id AND created BETWEEN '$from 00:00:00' AND '$to 23:59:59'"));
        $discount=new Discount();
        $user=new User();
        $currency=new Currencies();
        
        $data = array(
            array(locales::$text[reports_agents_date_to_date]),
            array(
                locales::$text[report_place],
                locales::$text[report_place_back],
                locales::$text[report_ticket],
                locales::$text[report_ticket_type],
                locales::$text[report_name],
                locales::$text[report_passport],
                locales::$text[report_price].' (BGN)',
                locales::$text[report_price].' (EUR)',
                locales::$text[report_discount],
                locales::$text[report_date],
                locales::$text[report_date_back],
                locales::$text[report_destination]
            )
        );
        
        $sum=0;
        $subln=new Subline();
        $city=new City();
        $point=new Line_point();
        foreach ($reservations as $reservation) {
            $subline = $subln->find(array(id => $reservation->subline_id));
            $disc = $discount->find(array(id => $reservation->discount_id));
            $usr = $user->find(array(id => $reservation->user_id));
            $sum = $sum + $reservation->price;
            
            $data [] = array(
                $reservation->place,
                ($reservation->ticket_type == 0 || $reservation->ticket_type == 2 ? locales::$text[none] : $reservation->place_back),
                ($reservation->ticket_number == '' ? locales::$text[none] : $reservation->ticket_number),
                ($reservation->ticket_type == 0 ? locales::$text[ticket_oneway] : ($reservation->ticket_type == 2 ? locales::$text[ticket_opened_twoway] : locales::$text[ticket_twoway])),
                ucwords($reservation->passenger_name),
                $reservation->passenger_passpor,
                $reservation->price,
                $currency->convert($reservation->price, 'eur'),
                ($reservation->discount_id == 0 ? locales::$text[none] : (locales::$current_locale == 'bg' ? $disc->name_bg : $disc->name_en)),
                date('d.m.Y', strtotime($reservation->date)),
                ($reservation->ticket_type == 0 ? locales::$text[none] : date('d.m.Y', strtotime($reservation->date_back))),
                $city->city($point->find(array(id => $subline->from_point_id))->city_id, locales::$current_locale) . ' - ' . $city->city($point->find(array(id => $subline->to_point_id))->city_id, locales::$current_locale)
            );
        }
        
        $data[] = array(
            null, null, null, null, null, null, null, null, 
            locales::$text[report_sum].': '.$sum,
            locales::$text[reports_from_date].' '.date('d.m.Y',strtotime($from)).' '.locales::$text[reports_to_date].' '.date('d.m.Y',strtotime($to)),
            locales::$text[report_reserved_by].': '.($reservation->user_id==0?Env::OnlineSysName:$usr->user)
        );
        
        $this->__array2csv($data);
    }
    
    private function invoices_report() {
        if(!Session::access_is_allowed(all_sales_access)) $this->__noAccess();
        
        $from = $_GET['date_from'];
        $to = $_GET['date_to'];
        
        $invoice=new Invoice();
        $invoices=$invoice->find(array(all=>1,where=>"created_on between '$from 00:00:00' AND '$to 23:59:59'"));
        $invoice_element=new Invoice_element();

        $reserv=new Reservation();
        $discount=new Discount();
        $user=new User();
        $currency=new Currencies();
        
        $data = array(
            array('Invoices'),
            array (
                'Invoice',
                locales::$text[report_place],
                locales::$text[report_place_back],
                locales::$text[report_date],
                locales::$text[report_date_back],
                locales::$text[report_ticket],
                locales::$text[report_ticket_type],
                locales::$text[report_name],
                locales::$text[report_price].' (BGN)',
                locales::$text[report_price].' (EUR)',
                locales::$text[report_discount],
                locales::$text[report_destination]
            )
        );
        
        $sum=0;
        $subln=new Subline();
        $point=new Line_point();
        $city=new City();
        foreach($invoices as $one_invoice){

            $invoice_elements=$invoice_element->find(array(all=>1,where=>"invoice_id=$one_invoice->id"));

            foreach ($invoice_elements as $one_element) {
                $reservation = $reserv->find(array(id => $one_element->reservation_id));

                $subline = $subln->find(array(id => $reservation->subline_id));
                $disc = $discount->find(array(id => $reservation->discount_id));
                $usr = $user->find(array(id => $reservation->user_id));
                $sum = $sum + $reservation->price;
                $data[] = array(
                    $one_element->invoice_id,
                    $reservation->place,
                    ($reservation->ticket_type == 0 || $reservation->ticket_type == 2 ? locales::$text[none] : $reservation->place_back),
                    date('d.m.Y', strtotime($reservation->date)),
                    ($reservation->ticket_type == 0 ? locales::$text[none] : date('d.m.Y', strtotime($reservation->date_back))),
                    ($reservation->ticket_number == '' ? locales::$text[none] : $reservation->ticket_number),
                    ($reservation->ticket_type == 0 ? locales::$text[ticket_oneway] : ($reservation->ticket_type == 2 ? locales::$text[ticket_opened_twoway] : locales::$text[ticket_twoway])),
                    ucwords($reservation->passenger_name),
                    $reservation->price,
                    $currency->convert($reservation->price, 'eur'),
                    ($reservation->discount_id == 0 ? locales::$text[none] : (locales::$current_locale == 'bg' ? $disc->name_bg : $disc->name_en)),
                    $city->city($point->find(array(id => $subline->from_point_id))->city_id, locales::$current_locale) . ' - ' . $city->city($point->find(array(id => $subline->to_point_id))->city_id, locales::$current_locale)
                );
            }
        }
        
        $data[] = array(
            null, null, null, null, null, null, null, null, null, null, null,
            locales::$text[report_sum].': '.$sum.' BGN / '.$currency->convert($sum, 'eur').' EUR'
        );
        
        $this->__array2csv($data);
    }
}
?>
