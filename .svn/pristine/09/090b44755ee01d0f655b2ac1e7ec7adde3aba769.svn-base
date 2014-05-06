<?php

/*
 * 
 */

include_once 'gui.php';
include_once './controllers/Line_points.php';
include_once './models/Subline.php';
include_once './models/City.php';
include_once './controllers/Travel_days.php';
include_once './controllers/Reservation_buffers.php';
include_once './controllers/Reservations.php';
include_once './controllers/Saved_places.php';

class Sublines extends gui {

    public $id;
    public $line_id;
    public $from_point_id;
    public $to_point_id;
    public $travel_time;
    public $price_oneway;
    public $price_twoway;

    function __construct() {
        
    }

    function Sublines($id, $line_id, $from_point_id, $to_point_id, $travel_time, $price_oneway, $price_twoway) {
        $this->id = $id;
        $this->line_id = $line_id;
        $this->from_point_id = $from_point_id;
        $this->to_point_id = $to_point_id;
        $this->travel_time = $travel_time;
        $this->price_oneway = $price_oneway;
        $this->price_twoway = $price_twoway;
    }
    
    public function travel_time_str(){
        $t_time = explode(':', $this->travel_time);
        
        return $t_time[0].' hours '.$t_time[1].' minutes';
    }

    public function save_all($elements, $back=false) {
        $this->line_id = $elements[line_id];
        foreach ($elements[price_oneway] as $key0 => $val) {
            foreach ($val as $key1 => $value) {
                $this->from_point_id = $key0;
                $this->to_point_id = $key1;
                $this->travel_time = $elements[travel_time][$key0][$key1];
                $this->price_oneway = $elements[price_oneway][$key0][$key1];
                $this->price_twoway = $elements[price_twoway][$key0][$key1];
                $this->save();
            }
        }
        if ($back)
            header('location: ./alllines.php');
        else
            header('location: ./set_travel_days.php?line_id=' . $elements[line_id] . '&back');
    }

    public function save() {
        $subline = new Subline();
        $subline->insert($this);
    }

    public function price_table_new($line_id, $back=false, $layout=true) {
        Session::user_auth(); //is user logged in
        $line_point = new Line_point();
        $city = new City();
        $points = $line_point->find(array(all => '', where => "line_id=$line_id", order => '`order` ' . ($back ? 'DESC' : 'ASC')));
        $subline = new Subline();


        $header = '<tr><td></td>';
        for ($i = 1; $i < sizeof($points); $i++) {//excluding the first point
            $header.='<th align="left">' . $city->city($points[$i]->city_id, 'bg') . '</th>';
        }

        $body = '';
        for ($i = 0; $i < sizeof($points) - 1; $i++) {//excluding the last point
            $elements = '';

            for ($j = 1; $j < sizeof($points); $j++) {
                if ($j < $i + 1)
                    $elements.='<td class="inactive"></td>';
                else {
                    $osubline = $subline->find(array(all => '', where => "from_point_id=" . $points[$i]->id . " AND to_point_id=" . $points[$j]->id));
                    $elements.='<td class="active">
                                   <table>
                                    <tr><td>
                                        Еднопосочен:
                                    </td><td>
                                        <input type="text" name="subline[price_oneway][' . $points[$i]->id . '][' . $points[$j]->id . ']" value="' . (isset($osubline[0]) ? $osubline[0]->price_oneway : '0') . '" size="3" />лв.<br />
                                    </td></tr>
                                    <tr><td>
                                        Двупосочен:
                                    </td><td>
                                        <input type="text" name="subline[price_twoway][' . $points[$i]->id . '][' . $points[$j]->id . ']" value="' . (isset($osubline[0]) ? $osubline[0]->price_twoway : '0') . '" size="3" />лв.
                                    </td></tr>
                                    <tr><td>
                                        Време на пътуване:
                                    </td><td>
                                        <input type="text" name="subline[travel_time][' . $points[$i]->id . '][' . $points[$j]->id . ']" value="' . (isset($osubline[0]) ? $osubline[0]->travel_time : '0') . '" size="3" />час(а).
                                    </td></tr>
                                    
                                   </table>
                                 </td>';
                }
            }

            $body.='<tr>
                      <th align="left">' . $city->city($points[$i]->city_id, 'bg') . '</th>
                      ' . $elements . '
                    <tr>';
        }

        $header.='</tr>';
        $this->page_contents = '
               <form action="./addsublines.php' . ($back || !$layout ? '?back' : '') . '" method="POST">
                <table class="sublines">
                    <input type="hidden" name="subline[line_id]" value="' . $line_id . '" />
                    ' . $header . '
                    ' . $body . '
                    <tr><td colspan="' . (sizeof($points)) . '" align="right">
                        <input type="submit" value="' . ($layout ? ($back ? 'Завърши' : 'Следваща стъпка') : 'Обнови') . '" name="submit_subline" />
                    </td></tr>
                </table>
               </form>';

        if ($layout)
            return $this->show_page();
        else
            return $this->page_contents;
    }

    public function does_travel($ticket_type) {
        if ($ticket_type == 'oneway') {
            if ($this->price_oneway == 0)
                return false;
            else
                return true;
        }elseif ($ticket_type == 'twoway') {
            if ($this->price_twoway == 0)
                return false;
            else
                return true;
        }else {
            return false;
        }
    }

    public function view_subline($date) {
        $city = new City();
        $point = new Line_point();
        $point_from = $point->find(array(id => $this->from_point_id));
        $point_to = $point->find(array(id => $this->to_point_id));
        $back = false;
        if ($point_from->order > $point_to->order)
            $back = true;
        $tdays = new Travel_days();
        $direction = ($back ? 1 : 0);
        if ($tdays->is_travelday($this, $date, $direction) && ($this->price_oneway > 0 || $this->price_twoway > 0)) {
            /*$allpoints = $point->find(array(all => '', where => "line_id=$this->line_id", order => ($back ? '`order` DESC' : '`order` ASC')));
            $br = 1;
            foreach ($allpoints as $allpoint) {
                if ($point_from->city_id == $allpoint->city_id || $point_to->city_id == $allpoint->city_id)
                    $points.='<b>' . $city->city($allpoint->city_id, locales::$current_locale) . '</b>';
                else
                    $points.=$city->city($allpoint->city_id, locales::$current_locale);

                if ($br < sizeof($allpoints))
                    $points.=' - ';
                $br++;
            }*/
            $promoH = new Promotion();
            $promo = $promoH->find(array(all => 1, where => "subline_id=$this->id AND expires >= '" . date('Y-m-d') . "'"));
            $price_twoway = $this->price_twoway;
            $price_oneway = $this->price_oneway;
            if (isset($promo[0])) {
                $price_twoway = $this->price_twoway - ($this->price_twoway * $promo[0]->promo_percent / 100);
                $price_oneway = $this->price_oneway - ($this->price_oneway * $promo[0]->promo_percent / 100);
            }
            
            $travelDayHandler = new Travel_day();
            $daysThatItTravels = current($travelDayHandler ->find(array('all'=>true,'where'=>"line_id = $this->line_id AND direction = $direction", 'limit'=>1)));
            

            $currency = new Currencies();
            return '<table width="100%" border="0">
                    <tr>
                        <th colspan="6">
                            '.rtrim(($daysThatItTravels->mon?locales::$text['mon'].',':'')
                            .($daysThatItTravels->tue?locales::$text['tue'].',':'')
                            .($daysThatItTravels->wed?locales::$text['wed'].',':'')
                            .($daysThatItTravels->thu?locales::$text['thu'].',':'')
                            .($daysThatItTravels->fri?locales::$text['fri'].',':'')
                            .($daysThatItTravels->sat?locales::$text['sat'].',':'')
                            .($daysThatItTravels->sun?locales::$text['sun'].',':''),',').'
                        </th>
                    </tr>
                    <tr>
                        <td>' . $city->city($point_from->city_id, locales::$current_locale) . ' - '.
                            $city->city($point_to->city_id, locales::$current_locale).'</td>
                        <td>
                            ' . locales::$text[search_departs] . ': ' . ($back ? date('H:i', strtotime($point_from->arrival_time_back) + ($point_from->stopover_back * 60)) : date('H:i', strtotime($point_from->arrival_time) + ($point_from->stopover * 60))) . '
                            ' . locales::$text[search_from] . ' ' . ($back ? (locales::$current_locale == 'bg' ? $point_from->bus_station_back_bg : $point_from->bus_station_back_en) : (locales::$current_locale == 'bg' ? $point_from->bus_station_bg : $point_from->bus_station_en)) . '
                        </td>
                        <td>
                            ' . locales::$text[search_travels] . ': ' . $this->travel_time . ' ' . locales::$text[hour] . '
                        </td>
                        <td>
                            ' . locales::$text[ticket_oneway] . ': ' . $price_oneway . ' BGN / ' . $currency->convert($price_oneway, 'eur') . ' EUR
                        </td>
                        <td>
                            ' . locales::$text[ticket_twoway] . ': ' . $price_twoway . ' BGN / ' . $currency->convert($price_twoway, 'eur') . ' EUR
                        </td>
                        <td align="right">
                            <a href="./?_subline=' . $this->id . '&_date=' . $date . '">' . locales::$text[search_buy] . '</a>
                        </td>
                    <tr>
                    </table>';
        } else {
            return '';
        }
    }
    
    public function view_with_json(){
        $travelDayHandler = new Travel_day();
        
        $line_point = new Line_point();
        
        $pointFrom = $line_point->find(array('id' => $this->from_point_id));
        $pointTo = $line_point->find(array('id' => $this->to_point_id));
        $direction = 0;
        if($pointFrom->order > $pointTo->order) $direction = 1;
        
        $promoH = new Promotion();
        $promo = $promoH->find(array(all => 1, where => "subline_id=$this->id AND expires >= '" . date('Y-m-d') . "'"));
        $price_twoway = $this->price_twoway;
        $price_oneway = $this->price_oneway;
        $has_promotion = false;
        if (isset($promo[0])) {
            $has_promotion = true;
            $price_twoway = $this->price_twoway - ($this->price_twoway * $promo[0]->promo_percent / 100);
            $price_oneway = $this->price_oneway - ($this->price_oneway * $promo[0]->promo_percent / 100);
        }
        
        $firstPoint = current($line_point->find(array('all'=>true, 'where'=>"line_id = $this->line_id", 'order'=>'`order` '.($direction?'DESC':'ASC'), 'limit'=>1)));
        $travelingInDays = 0;
        if($firstPoint->id != $pointFrom->id){
            $sublineHandler = new Subline();
            $startSubline = current($sublineHandler->find(array('all'=>true, 'where'=>"from_point_id = $firstPoint->id AND to_point_id = $pointFrom->id AND line_id = $this->line_id", 'limit'=>1)));
            if($direction){
                $dayStart = strtotime(date('Y-m-d 00:00',strtotime($firstPoint->arrival_time_back)));
                
                $dayEnd = strtotime($firstPoint->arrival_time_back." + $firstPoint->stopover_back minutes +  {$startSubline->travel_time_str()}");
            }else{
                $dayStart = strtotime(date('Y-m-d 00:00',strtotime($firstPoint->arrival_time)));
                $dayEnd = strtotime($firstPoint->arrival_time." + $firstPoint->stopover minutes +  {$startSubline->travel_time_str()}");
            }
            
            $travelingInDays = (int)(($dayEnd - $dayStart)/(60*60*24));
        }
        
        if($direction){
            $departureDay = strtotime(date('Y-m-d 00:00',strtotime($pointFrom->arrival_time_back)));
            $arrivalDay = strtotime($pointFrom->arrival_time_back." + {$this->travel_time_str()}");
        }else{
            $departureDay = strtotime(date('Y-m-d 00:00',strtotime($pointFrom->arrival_time)));
            $arrivalDay = strtotime($pointFrom->arrival_time." + {$this->travel_time_str()}");
        }
        
        $arrivingInDays = (int)(($arrivalDay - $departureDay)/(60*60*24));
        
        $dayConfig = array(
            0 => 'mon',
            1 => 'tue',
            2 => 'wed',
            3 => 'thu',
            4 => 'fri',
            5 => 'sat',
            6 => 'sun'
        );
        $daysThatItTravels = current($travelDayHandler ->find(array('all'=>true,'where'=>"line_id = $this->line_id AND direction = $direction", 'limit'=>1)));
        
        $daysThatItTravels_Buffer = array();
        $daysArrival_Buffer = array();
        
        foreach($dayConfig as $key => $weekDay){
            if($daysThatItTravels->{$weekDay}){
                $newKey = $key + $travelingInDays;
                if($newKey > 6){
                    $newKey = ($newKey - 6) - 1;
                }
                
                $daysThatItTravels_Buffer[$newKey] = locales::$text[$dayConfig[$newKey]];
                
                $arrivalKey = $newKey + $arrivingInDays;
                if($arrivalKey > 6){
                    $arrivalKey = ($arrivalKey - 6) - 1;
                }
                $daysArrival_Buffer[$arrivalKey] = locales::$text[$dayConfig[$arrivalKey]];
            }
        }
        // sort arrays
        //ksort($daysThatItTravels_Buffer);
        $daysThatItTravels_Buffer = array_values($daysThatItTravels_Buffer);
        
        //ksort($daysArrival_Buffer);
        $daysArrival_Buffer = array_values($daysArrival_Buffer);
        
        $currency = new Currencies();
        $discountHandler = new Discount();
        $discounts = $discountHandler->find(array('all'=>true));
        $discountList = array();
        foreach($discounts as $discount){
            $discounted_oneway = $discount->calculate_discount($this->price_oneway);
            $discounted_twoway = $discount->calculate_discount($this->price_twoway);
            $discountList[] = array(
                'name' => $discount->{'name_'.locales::$current_locale},
                'oneway' => $discounted_oneway.' лв. <br />'.$currency->convert($discounted_oneway, 'eur') . ' &euro;',
                'twoway' => $discounted_twoway.' лв. <br />'.$currency->convert($discounted_twoway, 'eur') . ' &euro;'
            );
        }
        $promoDiscountList = array();
        if($has_promotion)
        foreach($discounts as $discount){
            $discounted_oneway = $discount->calculate_discount($price_oneway);
            $discounted_twoway = $discount->calculate_discount($price_twoway);
            $promoDiscountList[] = array(
                'name' => $discount->{'name_'.locales::$current_locale},
                'oneway' => $discounted_oneway.' лв. <br />'.$currency->convert($discounted_oneway, 'eur') . ' &euro;',
                'twoway' => $discounted_twoway.' лв. <br />'.$currency->convert($discounted_twoway, 'eur') . ' &euro;'
            );
        }
        
        $city = new City();
        return array(
            'has_promotion'=>$has_promotion,
            'departure_days'=>$daysThatItTravels_Buffer,
            'arrival_days'=>$daysArrival_Buffer,
            'destination' => array(
                'from' => $city->city($pointFrom->city_id, locales::$current_locale),
                'to' => $city->city($pointTo->city_id, locales::$current_locale)
            ),
            'departs' => array(
                'time'=>($direction ? date('H:i', strtotime($pointFrom->arrival_time_back)) : date('H:i', strtotime($pointFrom->arrival_time))),
                'station' => ($direction ? (locales::$current_locale == 'bg' ? $pointFrom->bus_station_back_bg : $pointFrom->bus_station_back_en) : (locales::$current_locale == 'bg' ? $pointFrom->bus_station_bg : $pointFrom->bus_station_en))
            ),
            'arrives' => array(
                 'time'=>($direction ? date('H:i', strtotime($pointTo->arrival_time_back)) : date('H:i', strtotime($pointTo->arrival_time))),
                 'station' => ($direction ? (locales::$current_locale == 'bg' ? $pointTo->bus_station_back_bg : $pointTo->bus_station_back_en) : (locales::$current_locale == 'bg' ? $pointTo->bus_station_bg : $pointTo->bus_station_en))
            ),
            'travels' => $this->travel_time,
            'regular_price'=>array(
                'name' => (locales::$current_locale == 'bg'? 'Нормални':'Regular'),
                'oneway' => $this->price_oneway . ' лв. <br />'. $currency->convert($this->price_oneway, 'eur') . ' &euro;',
                'twoway' => $this->price_twoway . ' лв. <br />'. $currency->convert($this->price_twoway, 'eur') . ' &euro;',
                ),
            'regular_promo_price'=>array(
                'name' => (locales::$current_locale == 'bg'? 'Нормални':'Regular'),
                'oneway' => $price_oneway . ' лв. <br />'. $currency->convert($price_oneway, 'eur') . ' &euro;',
                'twoway' => $price_twoway . ' лв. <br />'. $currency->convert($price_twoway, 'eur') . ' &euro;',
                ),
            'discounts'=>$discountList,
            'promo_discounts'=>$promoDiscountList,
            'buy_url' => 'http://'.$_SERVER['HTTP_HOST'].'?_subline=' . $this->id.'&lang='.locales::$current_locale
        );
    }

    public function get_all_reserved_places($date) {
        $subline_hndl = new Subline();
        $point = new Line_point();
        $reserv = new Reservation();
        $rbuffer = new Reservation_buffer();

        $x0 = $point->find(array(id => $this->from_point_id));
        $y0 = $point->find(array(id => $this->to_point_id));
        $direction = 0;
        $reserved_places = array();
        if ($x0->order > $y0->order)
            $direction = 1;
        if ($direction == 0) {
            $all_sublines = $subline_hndl->find(array(all => 1, where => "line_id=$this->line_id AND `from_point_id` < `to_point_id`"));
            foreach ($all_sublines as $test_subline) {

                foreach (Saved_places::get_saved_places($test_subline->id) as $saved_place) {
                    $reserved_places[$saved_place] = true;
                }

                $x = $point->find(array(id => $test_subline->from_point_id));
                $y = $point->find(array(id => $test_subline->to_point_id));
                if ($x0->order < $y->order && $y0->order > $x->order) {
                    $reserved = $reserv->find(array(all => '', where => "((subline_id=$test_subline->id AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND `date_back`='" . $date . "')) AND payed<2"));
                    $resvBuff = $rbuffer->find(array(all => '', where => "(subline_id=$test_subline->id AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND `date_back`='" . $date . "')"));

                    foreach ($reserved as $ticket) {
                        if ((strtotime($ticket->date_back) == strtotime($date)) && $test_subline->id == $ticket->back_subline_id) {
                            $reserved_places[$ticket->place_back] = true;
                        } else {
                            $reserved_places[$ticket->place] = true;
                        }
                    }

                    foreach ($resvBuff as $ticketBuff) {
                        if ((strtotime($ticketBuff->date_back) == strtotime($date)) && $test_subline->id == $ticketBuff->back_subline_id) {
                            $reserved_places[$ticketBuff->place_back] = true;
                        } else {
                            $reserved_places[$ticketBuff->place] = true;
                        }
                    }
                }
            }
        } elseif ($direction == 1) {
            $all_sublines = $subline_hndl->find(array(all => 1, where => "line_id=$this->line_id AND `from_point_id` > `to_point_id`"));
            foreach ($all_sublines as $test_subline) {

                foreach (Saved_places::get_saved_places($test_subline->id) as $saved_place) {
                    $reserved_places[$saved_place] = true;
                }

                $x = $point->find(array(id => $test_subline->from_point_id));
                $y = $point->find(array(id => $test_subline->to_point_id));
                if ($x0->order > $y->order && $y0->order < $x->order) {
                    $reserved = $reserv->find(array(all => '', where => "((subline_id=$test_subline->id AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND `date_back`='" . $date . "'))  AND payed<2"));
                    $resvBuff = $rbuffer->find(array(all => '', where => "(subline_id=$test_subline->id AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND `date_back`='" . $date . "')"));

                    foreach ($reserved as $ticket) {
                        if ((strtotime($ticket->date_back) == strtotime($date)) && $test_subline->id == $ticket->back_subline_id) {
                            $reserved_places[$ticket->place_back] = true;
                        } else {
                            $reserved_places[$ticket->place] = true;
                        }
                    }

                    foreach ($resvBuff as $ticketBuff) {
                        if ((strtotime($ticketBuff->date_back) == strtotime($date)) && $test_subline->id == $ticketBuff->back_subline_id) {
                            $reserved_places[$ticketBuff->place_back] = true;
                        } else {
                            $reserved_places[$ticketBuff->place] = true;
                        }
                    }
                }
            }
        }

        return $reserved_places;
    }

    public function is_reserved($place, $date) {
        $subline_hndl = new Subline();
        $point = new Line_point();
        $reserv = new Reservation();
        $rbuffer = new Reservation_buffer();

        $x0 = $point->find(array(id => $this->from_point_id));
        $y0 = $point->find(array(id => $this->to_point_id));
        $direction = 0;
        if ($x0->order > $y0->order)
            $direction = 1;
        if ($direction == 0) {
            $all_sublines = $subline_hndl->find(array(all => 1, where => "line_id=$this->line_id AND `from_point_id` < `to_point_id`"));
        } elseif ($direction == 1) {
            $all_sublines = $subline_hndl->find(array(all => 1, where => "line_id=$this->line_id AND `from_point_id` > `to_point_id`"));
        }
        foreach ($all_sublines as $test_subline) {

            $x = $point->find(array(id => $test_subline->from_point_id));
            $y = $point->find(array(id => $test_subline->to_point_id));
            if ($direction == 0) {
                if ($x0->order < $y->order && $y0->order > $x->order) {
                    if (Saved_places::is_place_saved($test_subline->id, $place))
                        return true;
                    $reserved = $reserv->find(array(all => '', where => "((subline_id=$test_subline->id AND place=" . $place . " AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND place_back=" . $place . " AND `date_back`='" . $date . "')) AND payed<2", limit => 1));
                    $resvBuff = $rbuffer->find(array(all => '', where => "(subline_id=$test_subline->id AND place=" . $place . " AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND place_back=" . $place . " AND `date_back`='" . $date . "')", limit => 1));
                    if (isset($reserved[0]) || isset($resvBuff[0])) {
                        return true;
                    }
                }
            } elseif ($direction == 1) {
                if ($x0->order > $y->order && $y0->order < $x->order) {
                    if (Saved_places::is_place_saved($test_subline->id, $place))
                        return true;
                    $reserved = $reserv->find(array(all => '', where => "((subline_id=$test_subline->id AND place=" . $place . " AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND place_back=" . $place . " AND `date_back`='" . $date . "'))  AND payed<2", limit => 1));
                    $resvBuff = $rbuffer->find(array(all => '', where => "(subline_id=$test_subline->id AND place=" . $place . " AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND place_back=" . $place . " AND `date_back`='" . $date . "')", limit => 1));
                    if (isset($reserved[0]) || isset($resvBuff[0])) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function is_reserved_on_update($place, $date, $exclude_reservation_id) {
        $subline_hndl = new Subline();
        $point = new Line_point();
        $reserv = new Reservation();
        $rbuffer = new Reservation_buffer();

        $x0 = $point->find(array(id => $this->from_point_id));
        $y0 = $point->find(array(id => $this->to_point_id));
        $direction = 0;
        if ($x0->order > $y0->order)
            $direction = 1;
        if ($direction == 0) {
            $all_sublines = $subline_hndl->find(array(all => 1, where => "line_id=$this->line_id AND `from_point_id` < `to_point_id`"));
        } elseif ($direction == 1) {
            $all_sublines = $subline_hndl->find(array(all => 1, where => "line_id=$this->line_id AND `from_point_id` > `to_point_id`"));
        }
        foreach ($all_sublines as $test_subline) {

            $x = $point->find(array(id => $test_subline->from_point_id));
            $y = $point->find(array(id => $test_subline->to_point_id));
            if ($direction == 0) {
                if ($x0->order < $y->order && $y0->order > $x->order) {
                    if (Saved_places::is_place_saved_for_update($test_subline->id, $place))
                        return true;
                    $reserved = $reserv->find(array(all => '', where => "(((subline_id=$test_subline->id AND place=" . $place . " AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND place_back=" . $place . " AND `date_back`='" . $date . "')) AND payed<2) AND id <> $exclude_reservation_id"));
                    $resvBuff = $rbuffer->find(array(all => '', where => "((subline_id=$test_subline->id AND place=" . $place . " AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND place_back=" . $place . " AND `date_back`='" . $date . "')) AND id <> $exclude_reservation_id"));
                    if (isset($reserved[0]) || isset($resvBuff[0])) {
                        return true;
                    }
                }
            } elseif ($direction == 1) {
                if ($x0->order > $y->order && $y0->order < $x->order) {
                    if (Saved_places::is_place_saved_for_update($test_subline->id, $place))
                        return true;
                    $reserved = $reserv->find(array(all => '', where => "(((subline_id=$test_subline->id AND place=" . $place . " AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND place_back=" . $place . " AND `date_back`='" . $date . "'))  AND payed<2) AND id <> $exclude_reservation_id"));
                    $resvBuff = $rbuffer->find(array(all => '', where => "((subline_id=$test_subline->id AND place=" . $place . " AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND place_back=" . $place . " AND `date_back`='" . $date . "')) AND id <> $exclude_reservation_id"));
                    if (isset($reserved[0]) || isset($resvBuff[0])) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /* public function get_reserved($place, $date){//returns a reservation object or NULL
      $subline_hndl=new Subline();
      $point=new Line_point();
      $reserv=new Reservation();

      $x0=$point->find(array(id=>$this->from_point_id));//from Line_points object
      $y0=$point->find(array(id=>$this->to_point_id));//to line_point object

      $direction=0;
      if($x0->order>$y0->order) $direction=1;

      if($direction==0){
      $all_sublines=$subline_hndl->find(array(all=>1, where=>"line_id=$this->line_id AND `from_point_id` < `to_point_id`"));
      }elseif($direction==1){
      $all_sublines=$subline_hndl->find(array(all=>1, where=>"line_id=$this->line_id AND `from_point_id` > `to_point_id`"));
      }

      $reservations_buffer=array();
      foreach($all_sublines as $test_subline){
      $x=$point->find(array(id=>$test_subline->from_point_id));
      $y=$point->find(array(id=>$test_subline->to_point_id));
      if($direction==0){
      if($x0->order<$y->order && $y0->order>$x->order){
      $reserved=$reserv->find(array(all=>'', where=>"((subline_id=$test_subline->id AND place=".$place." AND `date`='".$date."') OR (back_subline_id=$test_subline->id AND place_back=".$place." AND `date_back`='".$date."')) AND payed<2"));
      if(isset($reserved[0])){
      $reservations_buffer[]=$reserved[0];
      }
      }
      }elseif($direction==1){
      if($x0->order>$y->order && $y0->order<$x->order){
      $reserved=$reserv->find(array(all=>'', where=>"((subline_id=$test_subline->id AND place=".$place." AND `date`='".$date."') OR (back_subline_id=$test_subline->id AND place_back=".$place." AND `date_back`='".$date."')) AND payed<2"));
      if(isset($reserved[0])){
      $reservations_buffer[]=$reserved[0];
      }
      }
      }

      }
      return $reservations_buffer;
      } */

    public function get_reserved_objects($date) {//returns a reservation object or NULL
        $subline_hndl = new Subline();
        $point = new Line_point();
        $reserv = new Reservation();

        $x0 = $point->find(array(id => $this->from_point_id)); //from Line_points object
        $y0 = $point->find(array(id => $this->to_point_id)); //to line_point object

        $direction = 0;
        if ($x0->order > $y0->order)
            $direction = 1;

        if ($direction == 0) {
            $all_sublines = $subline_hndl->find(array(all => 1, where => "line_id=$this->line_id AND `from_point_id` < `to_point_id`"));
        } elseif ($direction == 1) {
            $all_sublines = $subline_hndl->find(array(all => 1, where => "line_id=$this->line_id AND `from_point_id` > `to_point_id`"));
        }

        $reservations_buffer = array();
        foreach ($all_sublines as $test_subline) {
            $x = $point->find(array(id => $test_subline->from_point_id));
            $y = $point->find(array(id => $test_subline->to_point_id));
            if ($direction == 0) {
                if ($x0->order < $y->order && $y0->order > $x->order) {
                    $reserved = $reserv->find(array(all => '', where => "((subline_id=$test_subline->id AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND `date_back`='" . $date . "')) AND payed<2"));

                    if (sizeof($reserved) > 0) {
                        foreach ($reserved as $ticket) {
                            $reservations_buffer[] = $ticket;
                        }
                    }
                }
            } elseif ($direction == 1) {
                if ($x0->order > $y->order && $y0->order < $x->order) {
                    $reserved = $reserv->find(array(all => '', where => "((subline_id=$test_subline->id AND `date`='" . $date . "') OR (back_subline_id=$test_subline->id AND `date_back`='" . $date . "')) AND payed<2"));

                    if (sizeof($reserved) > 0) {
                        foreach ($reserved as $ticket) {
                            $reservations_buffer[] = $ticket;
                        }
                    }
                }
            }
        }
        return $reservations_buffer;
    }

    public function get_sales($date, $passenger_name, $passenger_passport, $ticket_number, &$page_links) {//returns a reservation object or NULL
        $subline_hndl = new Subline();
        $point = new Line_point();
        $reserv = new Reservation();
        if ($this->id) {//if is set a subline object
            $x0 = $point->find(array(id => $this->from_point_id)); //from Line_points object
            $y0 = $point->find(array(id => $this->to_point_id)); //to line_point object

            $direction = 0;
            if ($x0->order > $y0->order)
                $direction = 1;

            if ($direction == 0) {
                $all_sublines = $subline_hndl->find(array(all => 1, where => "line_id=$this->line_id AND `from_point_id` < `to_point_id`"));
            } elseif ($direction == 1) {
                $all_sublines = $subline_hndl->find(array(all => 1, where => "line_id=$this->line_id AND `from_point_id` > `to_point_id`"));
            }

            $reservations_buffer = array();
            foreach ($all_sublines as $test_subline) {
                $x = $point->find(array(id => $test_subline->from_point_id));
                $y = $point->find(array(id => $test_subline->to_point_id));
                if ($direction == 0) {
                    if ($x0->order < $y->order && $y0->order > $x->order) {
                        if (Session::access_is_allowed(all_sales_access)) {
                            $reserved = $reserv->find(array(all => '', where => "((subline_id=$test_subline->id AND `date` LIKE '%" . $date . "%') OR (back_subline_id=$test_subline->id AND `date_back` LIKE '%" . $date . "%')) AND passenger_name LIKE '%$passenger_name%' AND passenger_passpor LIKE '%$passenger_passport%' AND ticket_number LIKE '%$ticket_number%'"));
                        } else {
                            if (Session::access_is_allowed(show_epay_sales_access)) {
                                $reserved = $reserv->find(array(all => '', where => "(user_id=" . Session::current_user()->id . " OR user_id=0 OR ticket_type=2) AND ((subline_id=$test_subline->id AND `date` LIKE '%" . $date . "%') OR (back_subline_id=$test_subline->id AND `date_back` LIKE '%" . $date . "%')) AND passenger_name LIKE '%$passenger_name%' AND passenger_passpor LIKE '%$passenger_passport%' AND ticket_number LIKE '%$ticket_number%'"));
                            } else {
                                $reserved = $reserv->find(array(all => '', where => "(user_id=" . Session::current_user()->id . " OR ticket_type=2) AND ((subline_id=$test_subline->id AND `date` LIKE '%" . $date . "%') OR (back_subline_id=$test_subline->id AND `date_back` LIKE '%" . $date . "%')) AND passenger_name LIKE '%$passenger_name%' AND passenger_passpor LIKE '%$passenger_passport%' AND ticket_number LIKE '%$ticket_number%'"));
                            }
                        }

                        foreach ($reserved as $one_reservation) {
                            $reservations_buffer[] = $one_reservation;
                        }
                    }
                } elseif ($direction == 1) {
                    if ($x0->order > $y->order && $y0->order < $x->order) {
                        if (Session::access_is_allowed(all_sales_access)) {
                            $reserved = $reserv->find(array(all => '', where => "((subline_id=$test_subline->id AND `date` LIKE '%" . $date . "%') OR (back_subline_id=$test_subline->id AND `date_back` LIKE '%" . $date . "%')) AND passenger_name LIKE '%$passenger_name%' AND passenger_passpor LIKE '%$passenger_passport%' AND ticket_number LIKE '%$ticket_number%'"));
                        } else {
                            if (Session::access_is_allowed(show_epay_sales_access)) {
                                $reserved = $reserv->find(array(all => '', where => "(user_id=" . Session::current_user()->id . " OR user_id=0 OR ticket_type=2) AND ((subline_id=$test_subline->id AND `date` LIKE '%" . $date . "%') OR (back_subline_id=$test_subline->id AND `date_back` LIKE '%" . $date . "%')) AND passenger_name LIKE '%$passenger_name%' AND passenger_passpor LIKE '%$passenger_passport%' AND ticket_number LIKE '%$ticket_number%'"));
                            } else {
                                $reserved = $reserv->find(array(all => '', where => "(user_id=" . Session::current_user()->id . " OR ticket_type=2) AND ((subline_id=$test_subline->id AND `date` LIKE '%" . $date . "%') OR (back_subline_id=$test_subline->id AND `date_back` LIKE '%" . $date . "%')) AND passenger_name LIKE '%$passenger_name%' AND passenger_passpor LIKE '%$passenger_passport%' AND ticket_number LIKE '%$ticket_number%'"));
                            }
                        }

                        foreach ($reserved as $one_reservation) {
                            $reservations_buffer[] = $one_reservation;
                        }
                    }
                }
            }
        } else {

            if ($date != ''
                    || $passenger_name != ''
                    || $passenger_passport != ''
                    || $ticket_number != '') {
                $date_query = "(`date` LIKE '%" . $date . "%' OR `date_back` LIKE '%" . $date . "%')";
            } else {
                $date_query = "((`date` BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d', strtotime(date('Y-m-d')) + (60 * 60 * 24 * 1)) . "') OR (`date_back` BETWEEN '" . date('Y-m-d') . "' AND '" . date('Y-m-d', strtotime(date('Y-m-d')) + (60 * 60 * 24 * 1)) . "') OR ticket_type=2)";
            }

            if (Session::access_is_allowed(all_sales_access)) {
                $reservations_buffer = $reserv->paginate(array(all => '', page_size => 15, where => "($date_query) AND passenger_name LIKE '%$passenger_name%' AND passenger_passpor LIKE '%$passenger_passport%' AND ticket_number LIKE '%$ticket_number%'"), $links);
            } else {
                if (Session::access_is_allowed(show_epay_sales_access)) {
                    $reservations_buffer = $reserv->paginate(array(all => '', page_size => 15, where => "(user_id=" . Session::current_user()->id . " OR user_id=0 OR ticket_type=2) AND ($date_query) AND passenger_name LIKE '%$passenger_name%' AND passenger_passpor LIKE '%$passenger_passport%' AND ticket_number LIKE '%$ticket_number%'"), $links);
                } else {
                    $reservations_buffer = $reserv->paginate(array(all => '', page_size => 15, where => "(user_id=" . Session::current_user()->id . " OR ticket_type=2) AND ($date_query) AND passenger_name LIKE '%$passenger_name%' AND passenger_passpor LIKE '%$passenger_passport%' AND ticket_number LIKE '%$ticket_number%'"), $links);
                }
            }
        }
        $page_links = $links;
        return $reservations_buffer;
    }

    public function options_for_select($selected=null, $lang='bg') {
        $lns = new Line();
        $lines = $lns->find(array(all => 1));
        $subln = new Subline();
        $point = new Line_point();
        $city = new City();
        $options = '';
        $line_number = 1;
        foreach ($lines as $line) {
            $options.='<optgroup label="' . $city->city($line->from_city_id, $lang) . ' - ' . $city->city($line->to_city_id, $lang) . ' #' . $line_number . '">';
            $sublines = $subln->find(array(all => 1, where => "line_id=$line->id"));
            foreach ($sublines as $subline) {
                if($subline->price_oneway > 0 || $subline->price_twoway > 0){
                    $from = $point->find(array(id => $subline->from_point_id));
                    $to = $point->find(array(id => $subline->to_point_id));
                    $options.='<option value="' . $subline->id . '" ' . ($selected == $subline->id ? 'selected="selected"' : '') . '>' . $city->city($from->city_id, $lang) . ' - ' . $city->city($to->city_id, $lang) . '</option>';
                }
            }
            $options.='</optgroup>';
            $line_number++;
        }

        return $options;
    }
    
    //[UPDATE 2012]
    public function sublineDayExclusivePermissions($date, $direction) {
        $pointHandlers = new Line_point();
        $sublineHandler = new Subline();
        $pointFrom = $pointHandlers->find(array('id' => $this->from_point_id));

        if ($direction == 0) {
            $firstPoint = current($pointHandlers->find(array('all' => true, 'where' => "line_id=$this->line_id", 'order' => '`order` ASC', 'limit' => 1)));
        } else {
            $firstPoint = current($pointHandlers->find(array('all' => true, 'where' => "line_id=$this->line_id", 'order' => '`order` DESC', 'limit' => 1)));
        }
        if($pointFrom->id != $firstPoint->id){
            $grepSubline = current($sublineHandler->find(array('all' => true, 'where' => "from_point_id = $firstPoint->id AND to_point_id = $pointFrom->id", 'limit' => 1)));
                
            $subdate = $date;

            if ($direction == 0) {
                $subdate = strtotime($date . " $firstPoint->arrival_time" . " + {$grepSubline->travel_time_str()}");
            } else {
                $subdate = strtotime($date . " $firstPoint->arrival_time_back" . " + {$grepSubline->travel_time_str()}");
            }
            $travel_day_count = (int)(($subdate - strtotime(date('Y-m-d 00:00',strtotime($date))))/(60*60*24));
        }else{
            $travel_day_count = 0;
        }
        if ($travel_day_count > 0) {
            $day_of_week = strtolower(date('D', strtotime($date . " -$travel_day_count days")));
            $travel_day = new Travel_day();
            $travel_days = $travel_day->find((array('all' => true, 'where' => 'line_id=' . $this->line_id . " AND $day_of_week=1 AND direction=$direction", 'limit' => 1)));
            if (sizeof($travel_days) > 0) {
                return array(
                    strtolower(date('D', strtotime($date))) => 1
                );
            }
            return array(
                strtolower(date('D', strtotime($date))) => 0
            );
        }

        return -1;
    }
    //[UPDATE 2012]
    public function getBackSubline($date){
        $sublineHandler = new Subline();
        $backSubline = current($sublineHandler->find(array('all'=>true,'where'=>"from_point_id=$this->to_point_id AND to_point_id=$this->from_point_id AND line_id=$this->line_id",'limit'=>1)));
        
        if(empty($backSubline)) return -1;
        
        $lineController = new Lines();
        $linePointHandler = new Line_point();
        $travelDays = new Travel_days();
        
        $fromPoint = $linePointHandler->find(array('id'=>$backSubline->from_point_id));
        $toPoint = $linePointHandler->find(array('id'=>$backSubline->to_point_id));
        $direction = 0;
        if($fromPoint->order > $toPoint->order) $direction = 1;
        
        if($lineController->line_is_active($backSubline, $date) && $travelDays->is_travelday($backSubline, $date, $direction)){
            return $backSubline;
        }else{
            $fromCityId = $fromPoint->city_id;
            $toCityId = $toPoint->city_id;
            
            $result = null;
            $linePointHandler->query($result, 
                    "SELECT point_from.id AS point_from_id, point_to.id AS point_to_id, point_from.line_id AS line_id,
                            point_from.`order` as `from_order`, point_to.`order` as to_order
                    FROM  `line_points` AS point_from,  `line_points` AS point_to
                    WHERE point_from.city_id = {$fromCityId}
                    AND point_to.city_id = {$toCityId}
                    
                    AND point_from.line_id <> {$this->line_id}
                    AND point_to.line_id <> {$this->line_id}
                    
                    AND point_from.line_id = point_to.line_id"
                    );
           if($result){
               while($altPoints = mysql_fetch_assoc($result)){
                   
                   $altSubline = current($sublineHandler->find(array('all'=>true,'where'=>"from_point_id={$altPoints['point_from_id']} AND to_point_id={$altPoints['point_to_id']} AND line_id={$altPoints['line_id']}",'limit'=>1)));
                   if($altSubline){
                        $altSublineDirection = 0;
                        if($altPoints['from_order'] > $altPoints['to_order'])
                            $altSublineDirection = 1;
                        if($lineController->line_is_active($altSubline, $date) && $travelDays->is_travelday($altSubline, $date, $direction)){
                            return $altSubline;
                        }
                   }
               }
           }
        }
        
        return -1;
    }
    //[UPDATE 2012]
    public function getSubline($id, $date){
        if(strtotime($date) < strtotime(date('Y-m-d'))) {
            return -1;
        }
        
        $sublineHandler = new Subline();
        $subline = $sublineHandler->find(array('id'=>(int)$id));
        
        if(empty($subline)){
            return -1;
        }
        
        $lineController = new Lines();
        $linePointHandler = new Line_point();
        $travelDays = new Travel_days();
        
        $fromPoint = $linePointHandler->find(array('id'=>$subline->from_point_id));
        $toPoint = $linePointHandler->find(array('id'=>$subline->to_point_id));
        $direction = 0;
        if($fromPoint->order > $toPoint->order) $direction = 1;
        
        if($lineController->line_is_active($subline, $date) && $travelDays->is_travelday($subline, $date, $direction)){
            return $subline;
        }
        
        return -1;
    }
}

?>
