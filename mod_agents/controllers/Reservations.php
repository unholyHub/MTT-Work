<?php

/*
 *
 */

include_once 'gui.php';
include_once './models/Reservation.php';
include_once './controllers/Sublines.php';
include_once './controllers/Lines.php';
include_once './controllers/Travel_days.php';
include_once './controllers/Line_points.php';
include_once './controllers/Discounts.php';
include_once './models/City.php';
include_once './controllers/Promotions.php';
include_once './controllers/Currencies.php';
include_once './controllers/Active_places.php';
include_once './controllers/Returned_tickets.php';
include_once './controllers/InvoiceSession.php';

class Reservations extends gui {

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
    public $payed;
    public $user_id;
    public $last_update;
    public $created;

    function __construct() {
        
    }

    function Reservations($id, $subline_id, $back_subline_id, $place, $place_back, $ticket_number, $passenger_name, $passenger_passpor, $contact_phone, $contact_email, $price, $discount_id, $birthday, $date, $date_back, $ticket_type, $payed, $user_id, $last_update, $created) {
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
        $this->payed = $payed;
        $this->user_id = $user_id;
        $this->last_update = $last_update;
        $this->created = $created;
    }

    public function ticket2pdf() {
        require '../mod_pdf/PdfApi.php';
        $api = new PdfApi($this->print_ticket($_GET['rid'], true));
        header('Content-Disposition: attachment; filename="Online-ticket(' . (int) $_GET['rid'] . ').pdf"');
        header('Content-type: application/pdf');
        echo $api->output();
    }

    public function print_ticket($reservation_id, $isPdf = false) {
        Session::user_auth();
        $reserve = new Reservation();
        $reservation = $reserve->find(array(id => (int) $_GET['rid']));
        $currency = new Currencies();

        $ticket_type = 'Oneway / Еднопосочен';
        $agentName = 'Online Payments';

        if ($reservation->user_id > 0) {
            $userModel = new User();
            $user = $userModel->find(array('id' => $reservation->user_id));
            $agentName = $user->user;
        }

        if ($reservation->ticket_type == 1)
            $ticket_type = 'Twoway / Двупосочен';
        elseif ($reservation->ticket_type == 2)
            $ticket_type = 'Twoway opened / Двупосочен отворен';

        $city = new City();
        $sbln = new Subline();
        $subline = $sbln->find(array(id => $reservation->subline_id));
        $back_subline = $sbln->find(array(id => $reservation->back_subline_id));
        $point = new Line_point();
        $point_from = $point->find(array(id => $subline->from_point_id));
        $point_to = $point->find(array(id => $subline->to_point_id));

        if ($point_from->order < $point_to->order) {
            $departs = date('H:i d.m.Y', strtotime($reservation->date . ' ' . $point_from->arrival_time) + (60 * $point_from->stopover));
            $arrives = date('H:i d.m.Y', strtotime($departs . " +{$subline->travel_time_str()}"));
        } else {
            $departs = date('H:i d.m.Y', strtotime($reservation->date . ' ' . $point_from->arrival_time_back) + (60 * $point_from->stopover_back));
            $arrives = date('H:i d.m.Y', strtotime($departs . " +{$subline->travel_time_str()}"));
        }

        if (!empty($back_subline)) {
            $back_point_from = $point->find(array(id => $back_subline->from_point_id));
            $back_point_to = $point->find(array(id => $back_subline->to_point_id));
            if ($back_point_from->order > $back_point_to->order) {
                $departs_back = date('H:i d.m.Y', strtotime($reservation->date_back . ' ' . $point_to->arrival_time_back) + (60 * $point_from->stopover_back));
                $arrives_back = date('H:i d.m.Y', strtotime($departs_back . " + {$back_subline->travel_time_str()}"));
            } else {
                $departs_back = date('H:i d.m.Y', strtotime($reservation->date_back . ' ' . $point_to->arrival_time) + (60 * $point_from->stopover));
                $arrives_back = date('H:i d.m.Y', strtotime($departs_back . " + {$back_subline->travel_time_str()}"));
            }
        }

        $html = '<style type="text/css">body{ width: 100%; padding:0; margin: 0; }</style>
                    <table width="100%" style="border: 1px solid black; font-size:14px;">
                                    <tr>
                                        <td colspan="3" style="border-bottom: 1px dashed gray;">
                                            <table style="width: 100%">
                                             <tr>
                                                <td>
                                                ' . ucwords($reservation->passenger_name) . '
                                                </td>
                                                <td align="right">
                                                    <h3 style="margin:0;">No. ' . strtoupper($reservation->ticket_number) . '</h3>
                                                </td>
                                             </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th align="left" style="border-bottom: 1px dashed gray;">
                                            From - To / От - до
                                        </th>
                                        <th align="left" style="border-bottom: 1px dashed gray;">
                                            Departure - arrival / Заминава - пристига
                                        </th>
                                        <th align="left" style="border-bottom: 1px dashed gray;">
                                            Seat / Мясно
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style="" width="45%">
                                            <div>' . $city->city($point_from->city_id, 'en') . '</div>
                                        </td>
                                        <td style="">
                                            ' . $departs . '
                                        </td>
                                        <td style="" rowspan="2">
                                            ' . $reservation->place . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <div>' . $city->city($point_to->city_id, 'en') . '</div>
                                        </td>
                                        <td style="">
                                            ' . $arrives . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                    </tr>
                                    ' . ($reservation->ticket_type == 1 ?
                        '<tr>
                                        <td style="">
                                            <div>' . $city->city($point_to->city_id, 'en') . '</div>
                                        </td>
                                        <td style="">
                                            ' . $departs_back . '
                                        </td>
                                        <td style="" rowspan="2">
                                            ' . $reservation->place_back . '
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <div>' . $city->city($point_from->city_id, 'en') . '</div>
                                        </td>
                                        <td style="">
                                            ' . $arrives_back . '
                                        </td>
                                    </tr>' : '') . '
                                    <tr>
                                        <td colspan="3"></td>
                                    </tr>
                                    <tr>
                                        <th align="left" style="border-bottom: 1px dashed gray;">
                                            Service / Услуга
                                        </th>
                                        <th align="left" style="border-bottom: 1px dashed gray;">
                                            Ticket type / Тип билет
                                        </th>
                                        <th align="left" style="border-bottom: 1px dashed gray;">
                                            Price / Цена
                                        </th>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px;" valign="top">
                                            Traveling ticket / Билет за пътуване
                                        </td>
                                        <td style="" valign="top">
                                            ' . $ticket_type . '
                                        </td>
                                        <td style="" valign="top">
                                            ' . $reservation->price . ' BGN <br /> ' . $currency->convert($reservation->price, 'eur') . ' EUR
                                        </td>
                                    </tr>
                                </table>';

        $html = str_ireplace('${content}', $html, file_get_contents('./templates/ticket_template.html'));
        if (!$isPdf) {
            $html = '<div>
                       <button onclick="this.parentNode.style.display=\'none\'; window.print(); this.parentNode.style.display=\'block\';">' . locales::$text['print'] . '</button>
                       <a href="./ticket2pdf.php?rid=' . $reservation->id . '"><button>PDF</button></a>
                    </div>' .
                    $html;
        }

        return $html;
    }

    public function bus_places($subline, $date, $form = '') {
        /* $tdays=new Travel_days();

          $lpoint=new Line_point();
          $ln = new Lines();
          $point_from=$lpoint->find(array(id=>$subline->from_point_id));
          $point_to=$lpoint->find(array(id=>$subline->to_point_id));
          if($point_from->order>$point_to->order){
          $direction=1;
          }else{
          $direction=0;
          }

          if($ln->line_is_active($subline, $date) && $tdays->is_travelday($subline, $date, $direction)){
          $reserv=new Reservation();
          $rbuffer=new Reservation_buffer(); */
        $places_table = '';
        if ($subline != -1) {
            $active_places = new Active_places();
            $reserved_places = $subline->get_all_reserved_places($date);
            for ($i = 1, $br = 0; $i <= 57; $i++) {
                if (($i == 2 || $br == 3) && $i < 54) {
                    $places_table.='<div ' . (/* $subline->is_reserved($i, $date) */isset($reserved_places[$i]) || $active_places->is_inactive($subline->line_id, $i, $date) ? 'class="reserved"' : 'class="notreserved" onclick="set_place(\'' . $form . '\',' . $i . ',this);"') . ' style="margin-right:55px;">' . $i . '</div>';
                    $br = 0;
                } else {
                    $places_table.='<div ' . (/* $subline->is_reserved($i, $date) */isset($reserved_places[$i]) || $active_places->is_inactive($subline->line_id, $i, $date) ? 'class="reserved"' : 'class="notreserved" onclick="set_place(\'' . $form . '\',' . $i . ',this);"') . '>' . $i . '</div>';
                    $br++;
                }
            }
        } else {
            $places_table.=locales::$text[tickets_not_traveling];
        }
        return $places_table;
    }

//    public function reserve_form($subline, $date, $twoway=null, $date_back=null,$back_subline_id = null){
//        if(empty($back_subline_id)) $back_subline_id = -1;
//        
//        if($subline != -1){
//            $discout=new Discount();
//            $discounts=$discout->find(array(all=>''));
//
//            $old_price=($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back)>strtotime($date)?$subline->price_twoway:$subline->price_oneway);
//
//            $promoH=new Promotion();
//            $promo=$promoH->find(array(all=>1,where=>"subline_id=$subline->id AND expires >= '".date('Y-m-d')."' "));
//            if(isset($promo[0]) && $old_price>0){
//                $old_price=$old_price-($old_price*$promo[0]->promo_percent/100);
//            }
//
//            $lpoint=new Line_point();
//            $point_from=$lpoint->find(array(id=>$subline->from_point_id));
//            $point_to=$lpoint->find(array(id=>$subline->to_point_id));
//            $city=new City();
//            $destination=$city->city($point_from->city_id, locales::$current_locale).' - '.$city->city($point_to->city_id, locales::$current_locale);
//
//            $currency=new Currencies();
//            if($old_price>0){
//                $options_for_discount='<option id="'.$old_price.' BGN / '.$currency->convert($old_price, 'eur').' EUR" value="0">'.locales::$text[none].'</option>';
//
//
//                foreach($discounts as $disc){
//                    if($disc->discount_type==0){
//                        $new_price=(double)$old_price-((double)$old_price*(double)$disc->discount/100);
//                    }elseif($disc->discount_type==1){
//                        $new_price=(double)$old_price-(double)$disc->discount;
//                    }
//
//                    if(locales::$current_locale=='bg'){
//                        $options_for_discount.='<option id="'.$new_price.' BGN / '.$currency->convert($new_price, 'eur').' EUR" value="'.$disc->id.'">'.$disc->name_bg.'</option>';
//                    }else{
//                        $options_for_discount.='<option id="'.$new_price.' BGN / '.$currency->convert($new_price, 'eur').' EUR" value="'.$disc->id.'">'.$disc->name_en.'</option>';
//                    }
//                }
//
//                return '<form style="font-size:13px;" action="save_data.php" method="POST" onsubmit="new Ajax.Updater(
//                                                                \'reservation_form\',
//                                                                \'./save_data.php?'.$_SERVER[QUERY_STRING].'\',
//                                                                {asynchronous:true, evalScripts:true,
//                                                                onLoaded:function(request){refresh_seats('.($back_subline_id!=-1 && $twoway && $date_back && strtotime($date_back)>strtotime($date)?'1':'0').',\''.$_SERVER[QUERY_STRING].'\');},
//                                                                onLoading:function(request){$(\'reservation_form\').innerHTML=loading;},
//                                                                parameters:Form.serialize(this)});
//                                                                return false;" >
//                        <input type="hidden" name="reserve[subline_id]" value="'.$subline->id.'" />
//                        '.($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back)>strtotime($date)?'<input type="hidden" name="reserve[back_subline_id]" value="'.$back_subline_id.'" />':'').'
//                        <input type="hidden" name="reserve[ticket_type]" value="'.($twoway && $date_back && strtotime($date_back)>strtotime($date)?'1':'0').'" />
//                        <table>
//                            <tr>
//                                <td>'.locales::$text[tickets_destination].'</td>
//                                <td>'.$destination.'</td>
//                            </tr>
//                            <tr>
//                                <td>'.locales::$text[tickets_going_date].'</td>
//                                <td>
//                                    <input type="hidden" name="reserve[date]" value="'.$date.'"/>
//                                    '.date('d.m.Y',strtotime($date)).'
//                                </td>
//                           </tr>
//                        '.($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back)>strtotime($date)?
//                           '<tr>
//                                <td>'.locales::$text[tickets_back_date].'</td>
//                                <td>
//                                    <input type="hidden" name="reserve[date_back]" value="'.$date_back.'" />
//                                    '.date('d.m.Y',strtotime($date_back)).'
//                                </td>
//                            </tr>':'').'
//                        <tr>
//                            <td>'.locales::$text[tickets_place].'</td>
//                            <td><input type="text" id="reserve_place" name="reserve[place]" value="" size="2" readonly="readonly"/><br />
//                                <i>('.locales::$text[hint_place].')</i>
//                            </td>
//                        </tr>
//                        '.($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back)>strtotime($date)?
//                           '<tr>
//                                <td>'.locales::$text[tickets_place_back].'</td>
//                                <td>
//                                    <input type="text" id="reserve_place_back" name="reserve[place_back]" value="" size="2" readonly="readonly"/><br />
//                                    <i>('.locales::$text[hint_place_back].')</i>
//                                </td>
//                            </tr>':'').'
//                         <tr>
//                            <td>'.locales::$text[tickets_discount].'</td>
//                            <td>
//                                <select name="reserve[discount_id]" onchange="show_price_discount(this);" style="width:100px;">
//                                    '.$options_for_discount.'
//                                </select><br />
//                                <i>('.locales::$text[hint_discount].')</i>
//                            </td>
//                         </tr>
//                         <tr>
//                            <td>'.locales::$text[tickets_passenger_name].'</td>
//                            <td><input type="text" name="reserve[passenger_name]" value="" /><br /><i>('.locales::$text[hint_name].')</i></td>
//                        </tr>
//                        <tr>
//                            <td>'.locales::$text[tickets_passenger_passport].'</td>
//                            <td><input type="text" name="reserve[passenger_passpor]" value="" /><br /><i>('.locales::$text[hint_passport].')</i></td>
//                        </tr>
//                        <tr>
//                            <td>'.locales::$text[tickets_birthday].'</td>
//                            <td><input type="text" id="birthday" name="reserve[birthday]" value="" onclick="NewCssCal(\'birthday\',\'yyyymmdd\');" readonly="readonly" readonly="readonly"/></td>
//                        </tr>
//                        <tr>
//                            <td>'.locales::$text[tickets_phone].'</td>
//                            <td><input type="text" name="reserve[contact_phone]" value="" /><br /><i>('.locales::$text[hint_phone].')</i></td>
//                        </tr>
//                        <tr>
//                            <td>'.locales::$text[tickets_email].'</td>
//                            <td><input type="text" name="reserve[contact_email]" value="" /><br /><i>('.locales::$text[hint_email].')</i></td>
//                        </tr>
//                        <tr>
//                            <td>'.locales::$text[tickets_price].'</td>
//                            <td><span id="price_container">'.(double)$old_price.' BGN / '.$currency->convert((double)$old_price, 'eur').' EUR</span></td>
//                        </tr>
//                        <tr>
//                            <td align="right" colspan="2">
//                                <input type="submit" value="'.locales::$text[tickets_add].'" />
//                                '.(isset($_GET['bgr'])?'<input type="hidden" name="reserve[bgr]" value="true" />':'').'
//                            </td>
//                        </tr>
//                        </table>
//                    </form>
//                    <div id="cart_list" align="left">'.$this->view_cart_count($_SESSION['buffer_id']).'</div>';
//            }
//        }
//        return '<table>
//                        <tr>
//                            <td>Дестинация:</td>
//                            <td>'.$destination.'</td>
//                        </tr>
//                        <tr>
//                            <th colspan="2" align="center">'.locales::$text[tickets_not_active].'</td>
//                        </tr>
//                    </table>';
//    }
//    public function reserve_cvs($subline_id, $date, $twoway=null, $date_back=null, $layout=true){
//        Session::clear_session();
//        
//        if(isset($_GET['bgr'])){
//            setcookie('bgr', 'true', time() + (60*60*24));
//        }
//        
//        if(isset($_COOKIE['bgr'])){
//            $_GET['bgr'] = $_COOKIE['bgr'];
//        }
//        
//        $this->buffer_session_start();
//        $ticket_type='oneway';
//        $subline = new Sublines();
//        $subline = $subline->getSubline($subline_id, $date);
//        if($twoway && strtotime($date_back) > strtotime($date)){
//            $ticket_type='twoway';
//            if($subline != -1){
//                $subline_back = $subline->getBackSubline($date_back);
//            }else{
//                $subline_back = -1;
//            }
//        }
//
//        $reserve_views='<div align="center">
//                        <form action="'.$_SERVER[PHP_SELF].'" method="get" onsubmit="new Ajax.Updater(
//                                                                \'main_window\',
//                                                                \'./MainWINDOW.php\',
//                                                                {method: \'get\',
//                                                                onLoading: function(){ $(\'main_window\').innerHTML=loading_app; },
//                                                                parameters:Form.serialize(this)});
//                                                                return false;" >
//                            <div>
//                            '.(isset($_GET[bgr])?'<input type="hidden" name="bgr" value="true" />':'').'
//                            <input type="hidden" name="_subline" value="'.$subline_id.'" />
//                            <br />'.locales::$text[tickets_date].'<input type="text" id="_date" name="_date" value="'.$date.'" onclick="NewCssCal(\'_date\',\'yyyymmdd\');" readonly="readonly" />
//                            <input type="checkbox" name="twoway" value="1" onclick="show_back_date();" '.($twoway?'checked="checked"':'').' /> '.locales::$text[tickets_twoway].'
//                            <input type="text" id="_back_date" name="_date_back" value="'.$date_back.'" '.($twoway?'':'disabled="disabled" style="display:none;"').' onclick="NewCssCal(\'_back_date\',\'yyyymmdd\');" readonly="readonly" />
//                            <input type="submit" value="Go" />
//                            </div>
//                       </form>
//                       <div id="reservation_form" class="reserve_form">
//                        '.$this->reserve_form($subline, $date, $twoway, $date_back, ($subline_back!=-1?$subline_back->id:null)).'
//                       </div>
//                       </div>
//                       <div class="places" id="oneway">
//                       <div class="head">'.locales::$text[tickets_going].'</div>
//                       '.$this->bus_places($subline, $date).'
//                       </div>
//                       <div class="places" id="twoway_back">
//                       '.($ticket_type == 'twoway'?'<div class="head">'.locales::$text[tickets_back].'</div>'.$this->bus_places($subline_back, $date_back, '_back'):'').'
//                       </div>
//                        <div class="reservations">';
//        $reserve_views.='</div>';
//
//        $this->page_contents=$reserve_views;
//        if($layout){
//            $this->page_contents='<div id="main_window">'.$this->page_contents.'</div>';
//            return $this->show_page();
//        }
//        else return $this->page_contents;
//    }

    public function agents_cvs($subline_id, $date, $twoway = null, $date_back = null, $opened_twoway = null, $layout = true) {
        Session::user_auth();
        $this->template_name = 'agents';
        if (Session::access_is_allowed(sell_access)) {
            //$line_point=new Line_point();
            $subln = new Subline();
            $all_sublns = new Sublines();

            if ($subline_id) {
                $sublines = $all_sublns->getSubline($subline_id, $date);
            }

            $agent_view = '<form action="' . $_SERVER[PHP_SELF] . '" method="get">
                             <div class="checkPoint clearfix floatLeft">
                                <div class="textInput clearfix">
                                    <label for="select-destination" class="floatLeft">
                                         ' . locales::$text[tickets_destination] . '
                                    </label>     
                                    <select name="_subline" id="select-destination" class="float-right padding-10">
                                        ' . $all_sublns->options_for_select($subline_id, locales::$current_locale) . '
                                    </select>
                                </div>
                                <div class=" textInput clearfix">
                                    <label for="_date" class="cursor-pointer floatLeft">' . locales::$text[tickets_date] . '</label>
                                    <input type="text" id="_date" name="_date" class="datepicker float-right padding-10" readonly="true"  value="' . $date . '" onclick="NewCssCal(\'_date\',\'yyyymmdd\');" readonly="readonly" style="width:110px;"/>
                                </div>
                                <div class="textInput clearfix">
                                    <span class="invoice floatLeft">
                                      <input type="checkbox" id="twoway" name="twoway" value="1" class="css-checkbox" onclick="show_back_date();" ' . ($twoway ? 'checked="checked"' : '') . ' /> 
                                      <label for="twoway" class="css-label">' . locales::$text[tickets_twoway] . '</label>
                                    </span>
                                    <input  type="text"  class="datepicker datepickerWidth float-right padding-10" readonly="true"  id="_back_date" name="_date_back" value="' . $date_back . '" ' . ($twoway ? '' : 'disabled="disabled" style="display:none;" ') . ' onclick="NewCssCal(\'_back_date\',\'yyyymmdd\');" readonly="readonly"/>
                                </div>  
                                <div class="textInput clearfix">
                                  <span class="invoice floatLeft">
                                      <input id="opened_twoway" class="css-checkbox" value="1" onclick="deselect_twoway();" ' . ($opened_twoway ? 'checked="checked"' : '') . '  type="checkbox" />
                                      <label for="opened_twoway" name="demo_lbl_1" class="css-label">' . locales::$text[tickets_opened_twoway] . '</label>
                                  </span>
                                </div>
                                <div class="textInput clearfix">
                                  <input  class="invoice float-right" type="submit" value="Go" />
                               </div>
                            </div>
                    </form> ';

            if ($twoway && strtotime($date_back) > strtotime($date)) {
                $subln = new Subline();
                //$ticket_type='twoway';
                if ($sublines != -1) {
                    $subline_back = $sublines->getBackSubline($date_back);
                } else {
                    $subline_back = -1;
                }
            }
            if ($subline_id) {
                $agent_view.='
                        <div class="checkPointForms clearfix">
                           
                           <div class="places checkpoint-left clearfix" id="oneway">
                           <div class="head">' . locales::$text[tickets_going] . '</div>
                           ' . $this->bus_places($sublines, $date) . '
                           </div>
                           ' . ($twoway && $date_back && strtotime($date_back) > strtotime($date) ? '<div class="places checkpoint-left clearfix" id="twoway_back"><div class="head">' . locales::$text[tickets_back] . '</div>' . $this->bus_places($subline_back, $date_back, '_back') . '</div>': '') . '
                            <div id="reservation_form" class="reserve_form checkpoint-left clearfix">
                            ' . $this->agent_form($sublines, $date, $twoway, $date_back, ($subline_back != -1 ? $subline_back->id : null), $opened_twoway) . '
                           </div>    
                        </div>';
            }

            $this->page_contents = $agent_view;
        } else {
            $this->page_contents = '<h2 style="color:red; text-align:center;">' . locales::$text[no_permission] . '</h2>';
        }
        return $this->show_page();
    }

    public function agent_form($subline, $date, $twoway = null, $date_back = null, $back_subline_id = null, $opened_twoway = null) {
        Session::user_auth();
        if (empty($back_subline_id))
            $back_subline_id = -1;
        if ($subline != -1) {
            $discout = new Discount();
            $discounts = $discout->find(array(all => ''));

            $old_price = (($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date)) ||
                    ($opened_twoway) ? $subline->price_twoway : $subline->price_oneway);

            $lpoint = new Line_point();
            $point_from = $lpoint->find(array(id => $subline->from_point_id));
            $point_to = $lpoint->find(array(id => $subline->to_point_id));
            $city = new City();
            $destination = $city->city($point_from->city_id, locales::$current_locale) . ' - ' . $city->city($point_to->city_id, locales::$current_locale);

            $currency = new Currencies();
            if ($old_price > 0) {
                $options_for_discount = '<option id="' . $old_price . ' BGN / ' . $currency->convert($old_price, 'eur') . ' EUR" value="0">' . locales::$text[none] . '</option>';


                foreach ($discounts as $disc) {
                    if ($disc->discount_type == 0) {
                        $new_price = (double) $old_price - ((double) $old_price * (double) $disc->discount / 100);
                    } elseif ($disc->discount_type == 1) {
                        $new_price = (double) $old_price - (double) $disc->discount;
                    }

                    if (locales::$current_locale == 'bg') {
                        $options_for_discount.='<option id="' . $new_price . ' BGN / ' . $currency->convert($new_price, 'eur') . ' EUR" value="' . $disc->id . '">' . $disc->name_bg . '</option>';
                    } else {
                        $options_for_discount.='<option id="' . $new_price . ' BGN / ' . $currency->convert($new_price, 'eur') . ' EUR" value="' . $disc->id . '">' . $disc->name_en . '</option>';
                    }
                }
                return '<form action="agent_data.php" method="POST" onsubmit="new Ajax.Updater(
                                                                \'reservation_form\',
                                                                \'./agent_data.php?' . $_SERVER['QUERY_STRING'] . '\',
                                                                {asynchronous:true, evalScripts:true,
                                                                onLoaded:function(request){refresh_seats(' . ($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ? '1' : '0') . ',\'' . $_SERVER[QUERY_STRING] . '\');},
                                                                onLoading:function(request){$(\'reservation_form\').innerHTML=loading;},
                                                                parameters:Form.serialize(this)});
                                                                return false;">
                        <input type="hidden" name="reserve[subline_id]" value="' . $subline->id . '" />
                        ' . ($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ? '<input type="hidden" name="reserve[back_subline_id]" value="' . $back_subline_id . '" />' : '') . '
                        <input type="hidden" name="reserve[ticket_type]" value="' . ($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ? '1' : ($opened_twoway ? '2' : '0')) . '" />
                        <table>
                            <tr>
                                <td>' . locales::$text[tickets_destination] . '</td>
                                <td>
                                    <div class="float-right">' . $destination . '</div>
                                </td>
                            </tr>
                            <tr>
                                <td>' . locales::$text[tickets_going_date] . '</td>
                                <td>
                                    <input type="hidden" name="reserve[date]" value="' . $date . '"/>
                                    <div class="float-right">' . date('d.m.Y', strtotime($date)) . '</div>
                                </td>
                           </tr>
                        ' . ($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ?
                                '<tr>
                                <td>' . locales::$text[tickets_back_date] . '</td>
                                <td>
                                    <input type="hidden" name="reserve[date_back]" value="' . $date_back . '" />
                                    <div class="float-right">' . date('d.m.Y', strtotime($date_back)) . '</div>
                                </td>
                            </tr>' : '') . '
                            <tr>
                                <td>' . locales::$text[tickets_place] . '</td>
                                <td>
                                    <input type="text" class="unselectable float-right padding-10" id="reserve_place" name="reserve[place]" value="" style="width: 75px;" />
                                </td>
                            </tr>
                                ' . ($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ?
                            '<tr>
                                <td>' . locales::$text[tickets_place_back] . '</td>
                                <td>
                                    <input type="text" class="unselectable float-right padding-10" id="reserve_place_back" name="reserve[place_back]" value="" style="width: 75px;" />
                                </td>
                            </tr>' : '') . '
                         <tr>
                            <td>' . locales::$text[tickets_discount] . '</td>
                            <td>
                                <select name="reserve[discount_id]" onchange="show_price_discount(this);" class="default-input-width float-right padding-10">
                                    ' . $options_for_discount . '
                                </select>
                            </td>
                         </tr>
                         <tr>
                            <td>' . locales::$text[tickets_passenger_name] . '</td>
                            <td>
                                <div><input type="text" name="reserve[passenger_name][0]" value="" class="default-input-width float-right padding-10" /></div>
                                <div><small><i>' . locales::$text['personal_name_info'] . '</i></small></div>
                                <div><input type="text" name="reserve[passenger_name][1]" value="" class="default-input-width float-right padding-10" /></div>
                                <div><small><i>' . locales::$text['family_name_info'] . '</i></small></div>
                            </td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_passenger_passport] . '</td>
                            <td><input type="text" name="reserve[passenger_passpor]" value="" class="default-input-width float-right padding-10" /></td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_birthday] . '</td>
                            <td><input class="datepicker default-input-width float-right padding-10" type="text" id="birthday" name="reserve[birthday]" value="" onclick="NewCssCal(\'birthday\',\'yyyymmdd\');" readonly="readonly"/></td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_phone] . '</td>
                            <td><input type="text" name="reserve[contact_phone]" value="" class="default-input-width float-right padding-10" /></td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_email] . '</td>
                            <td><input type="text" name="reserve[contact_email]" value="" class="default-input-width float-right padding-10" /></td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_price] . '</td>
                            <td><span id="price_container">' . (double) $old_price . ' BGN / ' . $currency->convert((double) $old_price, 'eur') . ' EUR</span></td>
                        </tr>
                        
                        <tr>
                            <td align="right" colspan="2"><input class="invoice" type="submit" value="' . locales::$text[tickets_add] . '" /></td>
                        </tr>
                        </table>
                    </form>';
            }
        }
        return '<table>
                        <tr>
                            <td>Дестинация:</td>
                            <td>' . $destination . '</td>
                        </tr>
                        <tr>
                            <th colspan="2" align="center">Дестинацията не е активна</td>
                        </tr>
                    </table>';
    }

    public function edit_ticket_info_cvs(array $form) {
        Session::user_auth();
        if (isset($form[editing])) {
            if (isset($form[_date]) && $form[_date] > date('Y-m-d') && $this->date > date('Y-m-d'))
                $this->date = $form[_date];
            if (isset($form[_date_back]) && $form[_date_back] > date('Y-m-d') && $this->date_back > date('Y-m-d'))
                $this->date_back = $form[_date_back];
            if (isset($form[twoway]))
                $this->ticket_type = 1;
            elseif (isset($form[opened_twoway]))
                $this->ticket_type = 2;
            else
                $this->ticket_type = 0;
        }

        $this->template_name = 'agents';

        if (Session::access_is_allowed(sale_edit_access) && $this->payed != 2) {
            if ($this->ticket_type == 1) {
                if ($this->date_back <= date('Y-m-d')) {
                    $this->page_contents = '<h2 style="color:red; text-align:center;">' . locales::$text[no_permission] . '</h2>';
                    return $this->show_page();
                }
            }
            //$line_point=new Line_point();
            $subln = new Sublines();
            $sublnHandler = new Subline();
            //$all_sublns=new Sublines();
            $departure_subline_is_editable = true;
            if ($this->subline_id) {
                $sublines = $subln->getSubline($this->subline_id, $this->date);
                if ($sublines == -1) {
                    $departure_subline_is_editable = false;
                    $sublines = $sublnHandler->find(array('id' => $this->subline_id));
                }
            }
            $agent_view = '<form action="' . $_SERVER[PHP_SELF] . '" method="get">
                                  <div class="sellsEditDiv">
                                    <div class="textInput clearfix">
                                            <input type="hidden" name="rid" value="' . $this->id . '"/>
                                            <span class="floatLeft padding-10">' . locales::$text[tickets_date] . '</span>
                                            <input type="text" class="float-right" id="_date" name="_date" value="' . $this->date . '" onclick="NewCssCal(\'_date\',\'yyyymmdd\');" readonly="readonly" style="width:105px;"/>
                                  
                                            ' . ($this->ticket_type == 1 ?'  </div>
                                    <div class="textInput clearfix">     
                                      <span class="invoice floatLeft" >
                                         <input class="css-checkbox" type="checkbox" id="twoway" name="twoway" value="1" onclick="show_back_date();" ' . ($this->ticket_type == 1 ? 'checked="checked"' : '') . ' /> 
                                         <label class="css-label" for="twoway">' . locales::$text[tickets_twoway] . '</label>
                                      </span>
                                         <input class="float-right" type="text" id="_back_date" name="_date_back" value="' . $this->date_back . '" ' . ($this->ticket_type == 1 ? '' : 'disabled="disabled" style="display:none;"') . ' onclick="NewCssCal(\'_back_date\',\'yyyymmdd\');" readonly="readonly" style="width:105px;" />' : '') . '
                                    </div>
                                    <div class="textInput clearfix">
                                        <span class="invoice floatLeft  ">
                                            <input type="checkbox" class="css-checkbox" id="opened_twoway" name="opened_twoway" value="1" onclick="deselect_twoway();" ' . ($this->ticket_type == 2 ? 'checked="checked"' : '') . ' /> 
                                            <label for="opened_twoway" class="css-label">' . locales::$text[tickets_opened_twoway] . '</label>
                                        </span>
                                    </div>
                                    <div class="textInput clearfix">
                                        <input class="invoice float-right" type="submit" name="editing" value="Go" />
                                    </div>
                                  </div>  
                            </form>';

            if ($this->ticket_type == 1 && strtotime($this->date_back) > strtotime($this->date)) {
                $subln = new Subline();

                //$subline_back=$subln->find(array(all=>1,where=>"from_point_id=".$sublines->to_point_id." AND to_point_id=".$sublines->from_point_id." AND line_id=".$sublines->line_id.""));
                $subline_back = $sublines->getBackSubline($this->date_back);
            }
            if ($this->subline_id) {
                $agent_view.='
                    <div class="sellsEditForms">
                        <div id="reservation_form" class="reserve_form">
                            ' . $this->agent_edit_form($sublines, $this->date, ($this->ticket_type == 1), ($subline_back != -1 ? $subline_back : null), $this->date_back, null, ($this->ticket_type == 2)) . '
                        </div>
                        <div class="places" id="oneway">
                        <div class="head">' . locales::$text[tickets_going] . '</div>
                           ' . $this->bus_places(($departure_subline_is_editable ?
                                        $sublines : -1), $this->date) . '
                        </div>
                        <div class="places" id="twoway_back">
                           ' . ($this->ticket_type == 1 && $this->date_back && strtotime($this->date_back) > strtotime($this->date) ? '<div class="head">' . locales::$text[tickets_back] . '</div>' . $this->bus_places($subline_back, $this->date_back, '_back') : '') . '
                        </div>
                     </div>';
            }

            $this->page_contents = $agent_view;
        } else {
            $this->page_contents = '<h2 style="color:red; text-align:center;">' . locales::$text[no_permission] . '</h2>';
        }
        return $this->show_page();
    }

    public function agent_edit_form($subline, $date, $twoway = null, $back_subline = null, $date_back = null, $back_subline_id = null, $opened_twoway = null) {
        Session::user_auth();
        if ($subline != -1) {
            if (empty($back_subline))
                $back_subline = -1;
            $discout = new Discount();
            $discounts = $discout->find(array(all => ''));

            $old_price = (($back_subline != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date)) ||
                    ($opened_twoway) ? $subline->price_twoway : $subline->price_oneway);

            if ($this->user_id == 0 || $this->user_id == 61) {
                $promoH = new Promotion();
                $promo = $promoH->find(array(all => 1, where => "subline_id=$subline->id AND expires >= '" . date('Y-m-d') . "' "));
                if (isset($promo[0]) && $old_price > 0) {
                    $old_price = $old_price - ($old_price * $promo[0]->promo_percent / 100);
                }
            }

            $lpoint = new Line_point();
            $point_from = $lpoint->find(array(id => $subline->from_point_id));
            $point_to = $lpoint->find(array(id => $subline->to_point_id));
            $city = new City();
            $destination = $city->city($point_from->city_id, locales::$current_locale) . ' - ' . $city->city($point_to->city_id, locales::$current_locale);

            //$ln=new Lines();
            $currency = new Currencies();
            if ($old_price > 0) {
                $options_for_discount = '<option id="' . $old_price . ' BGN / ' . $currency->convert($old_price, 'eur') . ' EUR" value="0">' . locales::$text[none] . '</option>';


                foreach ($discounts as $disc) {
                    if ($disc->discount_type == 0) {
                        $new_price = (double) $old_price - ((double) $old_price * (double) $disc->discount / 100);
                    } elseif ($disc->discount_type == 1) {
                        $new_price = (double) $old_price - (double) $disc->discount;
                    }

                    if (locales::$current_locale == 'bg') {
                        $options_for_discount.='<option id="' . $new_price . ' BGN / ' . $currency->convert($new_price, 'eur') . ' EUR" value="' . $disc->id . '" ' . ($this->discount_id == $disc->id ? 'selected="selected"' : '') . '>' . $disc->name_bg . '</option>';
                    } else {
                        $options_for_discount.='<option id="' . $new_price . ' BGN / ' . $currency->convert($new_price, 'eur') . ' EUR" value="' . $disc->id . '" ' . ($this->discount_id == $disc->id ? 'selected="selected"' : '') . '>' . $disc->name_en . '</option>';
                    }
                }
                $cur_disc_mod = new Discount();
                $cur_disc = $cur_disc_mod->find(array(id => $this->discount_id));
                $current_price = $old_price;
                if ($cur_disc) {
                    if ($cur_disc->discount_type == 0) {
                        $current_price = (double) $old_price - ((double) $old_price * (double) $cur_disc->discount / 100);
                    } elseif ($cur_disc->discount_type == 1) {
                        $current_price = (double) $old_price - (double) $cur_disc->discount;
                    }
                }

                list($firstName, $lastName) = explode(' ', $this->passenger_name);

                return '<form action="agent_edit_data.php" method="POST" onsubmit="new Ajax.Updater(
                                                                \'reservation_form\',
                                                                \'./agent_edit_data.php?' . $_SERVER[QUERY_STRING] . '\',
                                                                {asynchronous:true, evalScripts:true,
                                                                onLoaded:function(request){refresh_seats(' . ($twoway && $date_back && strtotime($date_back) > strtotime($date) ? '1' : '0') . ',\'_subline=' . $subline->id . '&_date=' . $this->date . '' . ($date_back ? '&_date_back=' . $date_back : '') . '\');},
                                                                onLoading:function(request){$(\'reservation_form\').innerHTML=loading;},
                                                                parameters:Form.serialize(this)});
                                                                return false;" >
                        <input type="hidden" name="reserve[subline_id]" value="' . $subline->id . '" />
                        ' . ($back_subline != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ? '<input type="hidden" name="reserve[back_subline_id]" value="' . $back_subline->id . '" />' : '') . '
                        <input type="hidden" name="reserve[id]" value="' . $this->id . '" />
                        <input type="hidden" name="reserve[ticket_type]" value="' . ($twoway && $date_back && strtotime($date_back) > strtotime($date) ? '1' : ($opened_twoway ? '2' : '0')) . '" />
                        <table>
                            <tr>
                                <td>' . locales::$text[tickets_destination] . '</td>
                                <td><div class="float-right">' . $destination . '</div></td>
                            </tr>
                            
                            <tr>
                                <td>' . locales::$text[tickets_going_date] . '</td>
                                <td>
                                    <div class="float-right">
                                        <input type="hidden" name="reserve[date]" value="' . $date . '"/>
                                        ' . date('d.m.Y', strtotime($date)) . '
                                    </div>     
                                </td>
                           </tr>
                        ' . ($back_subline != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ?
                                '<tr>
                                <td>' . locales::$text[tickets_back_date] . '</td>
                                <td>
                                    <div class="float-right">
                                        <input type="hidden" name="reserve[date_back]" value="' . $date_back . '" />
                                        ' . date('d.m.Y', strtotime($date_back)) . '
                                    </div>        
                                </td>
                            </tr>' : '') . '
                        <tr>
                            <td>' . locales::$text[tickets_place] . '</td>
                            <td><input type="text" class="unselectable" id="reserve_place" name="reserve[place]" value="' . $this->place . '" style="width: 75px;" /></td>
                        </tr>
                        ' . ($back_subline != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ?
                                '<tr>
                                <td>' . locales::$text[tickets_place_back] . '</td>
                                <td>
                                    <input type="text" id="reserve_place_back" name="reserve[place_back]" value="' . $this->place_back . '"  style="width: 75px;" readonly="readonly"/>
                                </td>
                            </tr>' : '') . '
                         <tr>
                            <td>' . locales::$text[tickets_discount] . '</td>
                            <td>
                                <select name="reserve[discount_id]" onchange="show_price_discount(this);" style="width:160px;">
                                    ' . $options_for_discount . '
                                </select>
                            </td>
                         </tr>
                         <tr>
                            <td>' . locales::$text[tickets_passenger_name] . '</td>
                            <td>
                                <div><input type="text" name="reserve[passenger_name][0]" value="' . $firstName . '" /></div>
                                <div><small><i>' . locales::$text['personal_name_info'] . '</i></small></div>
                                <div><input type="text" name="reserve[passenger_name][1]" value="' . $lastName . '" /></div>
                                <div><small><i>' . locales::$text['family_name_info'] . '</i></small></div>
                            </td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_passenger_passport] . '</td>
                            <td><input type="text" name="reserve[passenger_passpor]" value="' . $this->passenger_passpor . '" /></td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_birthday] . '</td>
                            <td><input type="text" class="datepicker" id="birthday" name="reserve[birthday]" value="' . $this->birthday . '" onclick="NewCssCal(\'birthday\',\'yyyymmdd\');" readonly="readonly"/></td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_phone] . '</td>
                            <td><input type="text" name="reserve[contact_phone]" value="' . $this->contact_phone . '" /></td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_email] . '</td>
                            <td><input type="text" name="reserve[contact_email]" value="' . $this->contact_email . '" /></td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_price] . '</td>
                            <td><span class="float-right" id="price_container">' . (double) $current_price . ' BGN / ' . $currency->convert((double) $current_price, 'eur') . ' EUR</span></td>
                        </tr>
                        <tr>
                            <td align="right" colspan="2"><input class="invoice " type="submit" value="' . locales::$text[edit] . '" /></td>
                        </tr>
                        </table>
                    </form>';
            }
        }
        return '<table>
                        <tr>
                            <td>Дестинация:</td>
                            <td>' . $destination . '</td>
                        </tr>
                        <tr>
                            <th colspan="2" align="center">Дестинацията не е активна</td>
                        </tr>
                    </table>';
    }

//    private function buffer_session_start(){
//        if(!isset($_SESSION['buffer_id'])){
//            $_SESSION['buffer_id']=md5(time());
//        }
//    }
//    public function view_cart_count($buffer_id){
//        $buffer=new Reservation_buffer();
//        $cart=$buffer->find(array(all=>1,where=>"buffer_id='$buffer_id'"));
//        return '<div style="float:left; width:98%; background-color:#AED9FA; padding:15px;" >
//                    <a style="float:left;" href="#" onclick="window.open (\'./view_cart.php\',\'mywindow\',\'status=1,width=794,height=500\'); return false;">'.locales::$text[tickets_in_basket].' '.sizeof($cart).' '.locales::$text[tickets_count].'</a>
//                    '.(sizeof($cart)>0?'<a style="float:right; color:red;" href="./pay.php">'.locales::$text[tickets_buy].'</a>':'').'
//                </div>
//                <div><a target="_blank" style="padding:5px;color:#1800a9; text-decoration:underline;" href="./travel_conditions.php">'.locales::$text[travel_terms].'</a></div>';
//    }
//    public function view_cart_buffer($buffer_id){
//        $this->template_name='reports';
//        $buffer=new Reservation_buffer();
//        $cart=$buffer->find(array(all=>1,where=>"buffer_id='$buffer_id'"));
//        $html='';
//            $html='<table align="center" style="font-size:12px; width:790px; border-spacing:0; background-color:#B7C6DB">
//                    <tr>
//                        <th align="left">'.locales::$text[report_destination].'</th>
//                        <th align="left">'.locales::$text[report_ticket_type].'</th>
//                        <th align="left">'.locales::$text[report_place].'</th>
//                        <th align="left">'.locales::$text[report_place_back].'</th>
//                        <th align="left">'.locales::$text[report_name].'</th>
//                        <th align="left">'.locales::$text[report_price].'</th>
//                        <th align="left">'.locales::$text[report_date].'</th>
//                        <th align="left">'.locales::$text[report_date_back].'</th>
//                    </tr>';
//        if(sizeof($cart)>0){
//            foreach($cart as $element) $html.=$element->view_cart_element();
//        }else{
//            $html.='<tr>
//                        <td colspan="8" align="center" style="color:red;">'.locales::$text[tickets_basket_empty].'</td>
//                    </tr>';
//        }
//        $html.='</table>';
//
//        $this->page_contents=$html;
//        return $this->show_page();
//    }
//    public function save(array $elements){
//        $this->ticket_type = $elements[ticket_type];
//        $this->passenger_name = $elements[passenger_name];
//        $this->passenger_passpor = $elements[passenger_passpor];
//        $this->contact_phone = $elements[contact_phone];
//        $this->contact_email = $elements[contact_email];
//        $this->date = $elements[date];
//        $this->place = $elements[place];
//        $this->subline_id = $elements[subline_id];
//        $this->discount_id = $elements[discount_id];
//        $this->birthday = $elements[birthday];
//
//        if(isset($elements['bgr'])) $this->user_id=61;
//        else $this->user_id=0;
//
//        $subln=new Sublines();
//        $subline = $subln->getSubline($this->subline_id, $this->date);
//        if($subline==-1){
//            return $this->errorOut(array('Грешка при избирането на дестинация.<pre>'.print_r($elements,true).'</pre>'));
//        }
//
//        $this->price = $subline->price_oneway;
//
//        $promoH=new Promotion();
//        $promo=$promoH->find(array(all=>1,where=>"subline_id=$subline->id AND expires >= '".date('Y-m-d')."'"));
//        if(isset($promo[0])){
//            $this->price=$this->price-($this->price*$promo[0]->promo_percent/100);
//        }
//
//        if($this->ticket_type==1){
//            $this->price = $subline->price_twoway;
//            if(isset($promo[0])){
//                $this->price=$this->price-($this->price*$promo[0]->promo_percent/100);
//            }
//            $this->date_back = $elements[date_back];
//            $this->place_back = $elements[place_back];
//            $this->back_subline_id = $elements['back_subline_id'];
//            $back_subline = $subln->getSubline($this->back_subline_id, $this->date_back);
//            if($back_subline == -1) {
//                return $this->errorOut (array('Грешка при избирането на дестинация за връщане.'));
//            }
//        }
//
//        if($this->discount_id != 0){
//            $discount=new Discount;
//            $disc=$discount->find(array(id=>$this->discount_id));
//            $this->price=($disc->discount_type==0?($this->price-($this->price*$disc->discount/100)):($this->price - $disc->discount));
//        }
//
//        $reserve = new Reservation();
//        $err = $reserve->insert($this, $_SESSION['buffer_id']);
//        return $this->errorOut($err);
//    }

    public function agent_save(array $elements) {
        Session::user_auth();
        if (Session::access_is_allowed(sell_access)) {
            $this->ticket_type = $elements[ticket_type];
            $this->ticket_number = $elements[ticket_number];
            $this->passenger_name = implode(' ', $elements[passenger_name]);
            $this->passenger_passpor = $elements[passenger_passpor];
            $this->contact_phone = $elements[contact_phone];
            $this->contact_email = $elements[contact_email];
            $this->date = $elements[date];
            $this->place = $elements[place];
            $this->subline_id = $elements[subline_id];
            $this->discount_id = $elements[discount_id];
            $this->birthday = $elements[birthday];
            $this->payed = 1;

            $subln = new Sublines();
            $subline = $subln->getSubline($this->subline_id, $this->date);
            if ($subline == -1) {
                return $this->errorOut(array('Грешка при избирането на дестинация.'));
            }

            $this->price = $subline->price_oneway;
            //$promoH=new Promotion();
            //$promo=$promoH->find(array(all=>1,where=>"subline_id=$subline->id"));
            //if(isset($promo[0])){
            //    $this->price=$promo[0]->promo_price_oneway;
            //}

            if ($this->ticket_type == 1) {
                $this->price = $subline->price_twoway;
                //if(isset($promo[0])){
                //    $this->price=$promo[0]->promo_price_twoway;
                //}
                $this->date_back = $elements[date_back];
                $this->place_back = $elements[place_back];

                $this->back_subline_id = $elements['back_subline_id'];
                $subline_back = $subln->getSubline($this->back_subline_id, $this->date_back);

                if ($subline_back == -1) {
                    return $this->errorOut(array('Грешка при избиране на дестинация за връщане. <pre>' . print_r($elements, true) . '</pre>'));
                }
            } elseif ($this->ticket_type == 2) {
                $this->price = $subline->price_twoway;

                $this->back_subline_id = 0;
            }

            if ($this->discount_id != 0) {
                $discount = new Discount;
                $disc = $discount->find(array(id => $this->discount_id));
                $this->price = ($disc->discount_type == 0 ? ($this->price - ($this->price * $disc->discount / 100)) : ($this->price - $disc->discount));
            }

            if ((int) $elements['is_reservation'] || Session::access_is_allowed("has_payment_restrictions")) {
                $this->payed = 0;
            }

            $reserve = new Reservation();
            $err = $reserve->insert_agent_data($this, Session::current_user()->id);
            if (!is_array($err)) {
                $reserveId = $err;
                if (Session::access_is_allowed("has_payment_restrictions")) {
                    InvoiceSession::addInvoiceElement($reserveId);
                }
                return '<center>
                            <input type="button" class="invoice" value="' . locales::$text['print_ticket_number'] . $this->ticket_number . '" onclick="window.open(\'./ticket_print.php?rid=' . $err . '\',\'Ticket\', \'height = 400, width = 800, scrollbars = 1, menubar = 1\');" />
                        </center>';
            } else {
                return $this->errorOut($err);
            }
        }
    }

    public function edit_agent_save(array $elements) {
        Session::user_auth();
        if (Session::access_is_allowed(sale_edit_access)) {
            $reserve = new Reservation();

            $this->id = $elements[id];
            $this->ticket_type = $elements[ticket_type];

            $this->passenger_name = implode(' ', $elements[passenger_name]);
            $this->passenger_passpor = $elements[passenger_passpor];
            $this->contact_phone = $elements[contact_phone];
            $this->contact_email = $elements[contact_email];
            $this->date = $elements[date];
            $this->place = $elements[place];
            $this->subline_id = $elements[subline_id];
            $this->discount_id = $elements[discount_id];
            $this->birthday = $elements[birthday];

            $subln = new Sublines();
            $subline = $subln->getSubline($this->subline_id, $this->date);
            if ($subline == -1) {
                return $this->errorOut(array('Грешка при избиране на дестинация.'));
            }

            $this->price = $subline->price_oneway;


            $oldData = $reserve->find(array(id => $elements[id]));
            $promoH = new Promotion();
            $promo = new Promotions();
            $allowPromotion = false;
            if ($oldData->user_id == 0 || $oldData->user_id == 61) {
                $allowPromotion = true;
            }

            if ($allowPromotion) {
                $promo = $promoH->find(array(all => 1, where => "subline_id=$subline->id AND expires >= '" . date('Y-m-d') . "'"));
                if (isset($promo[0])) {
                    $this->price = $this->price - ($this->price * $promo[0]->promo_percent / 100);
                }
            }

            if ($this->ticket_type == 1) {
                $this->price = $subline->price_twoway;

                if ($allowPromotion) {
                    if (isset($promo[0])) {
                        $this->price = $this->price - ($this->price * $promo[0]->promo_percent / 100);
                    }
                }

                $this->date_back = $elements[date_back];
                $this->place_back = $elements[place_back];

                $this->back_subline_id = $elements['back_subline_id'];
                $subline_back = $subln->getSubline($this->back_subline_id, $this->date_back);

                if ($subline_back == -1) {
                    return $this->errorOut(array('Грешка при избиране на дестинация за връщане.'));
                }
            } elseif ($this->ticket_type == 2) {
                $this->price = $subline->price_twoway;

                if ($allowPromotion) {
                    if (isset($promo[0])) {
                        $this->price = $this->price - ($this->price * $promo[0]->promo_percent / 100);
                    }
                }

                $this->back_subline_id = 0;
            }

            if ($this->discount_id != 0) {
                $discount = new Discount;
                $disc = $discount->find(array(id => $this->discount_id));
                $this->price = ($disc->discount_type == 0 ? ($this->price - ($this->price * $disc->discount / 100)) : ($this->price - $disc->discount));
            }

            $err = $reserve->update_agent_data($this, Session::current_user()->id);
            return $this->errorOut($err);
        }
    }

    public function browse_list($subline_id, $date, $passenger_name, $passenger_passport, $ticket_number) {
        Session::user_auth();
        $this->template_name = 'agents';
        $city = new City();
        $subline = new Sublines();
        $subln = new Subline();
        if ($subline_id != 0) {
            $subline = $subln->find(array(id => $subline_id));
        }
        $res = $subline->get_sales($date, $passenger_name, $passenger_passport, $ticket_number, $links);

        $this->page_contents = '
                   <form action="' . $_SERVER[PHP_SELF] . '" method="get">
                        <div class="ticketDestination clearfix">
                            <div class="textInput clearfix">
                                <label>
                                ' . locales::$text[tickets_destination] . '
                                </label> 
                                <select name="subline" class="ticketdest">"
                                    <option value="0">' . locales::$text[none] . '</option>
                                    ' . $subline->options_for_select($subline_id, locales::$current_locale) . '
                                </select>
                            </div>
                            <div  class="textInput clearfix">
                                <label for="_date"> 
                                    ' . locales::$text[tickets_date] . '
                                </label>
                                <input style="width:110px" class="datepicker" type="text" id="_date" name="_date" value="' . $date . '" onclick="NewCssCal(\'_date\',\'yyyymmdd\');" readonly="readonly"/>
                            </div>
                            <div  class="textInput clearfix">
                                <label for="passenger-name">
                                    ' . locales::$text[tickets_passenger_name] . '
                                </label>
                                    <input id="passenger-name" type="text" name="passenger_name" value="' . $passenger_name . '"/>
                            </div>  
                            <div  class="textInput clearfix" >
                                <label for="passenger-passport">
                                    ' . locales::$text[tickets_passenger_passport] . '
                                </label>
                                <input id="passenger-passport" type="text" name="passenger_passport" value="' . $passenger_passport . '" />
                            </div>
                            <div  class="textInput clearfix">
                                <label for="ticket_number">
                                     ' . locales::$text[tickets_number] . '
                                </label>
                                <input id="ticket_number" type="text" name="ticket_number" value="' . $ticket_number . '" />
                            </div>
                            <div class="textInput clearfix">
                                    <input class="invoice float-right" type="submit" value="Go" />
                            </div>
                        </div>
                    </form>
                    <div class="ticket-person-stats-div clearfix">
                        <div align="center">' . $links . '</div>
                        <div>' . $this->list_reservations($res) . '</div>
                    
                        <div align="center">' . $links . '</div>
                    </div>         
                    ';
        return $this->show_page();
    }

//    public function view_reservation(){
//        $user=new User();
//        $subln=new Subline();
//        $point=new Line_point();
//        $city=new City();
//        $currency=new Currencies();
//
//        $subline=$subln->find(array(id=>$this->subline_id));
//        
//        $point_from=$point->find(array(id=>$subline->from_point_id));
//        $point_to=$point->find(array(id=>$subline->to_point_id));
//        $back_back=false;
//        if($this->ticket_type==1){
//            $back_subline=$subln->find(array(id=>$this->back_subline_id));
//            $back_point_from=$point->find(array(id=>$subline->from_point_id));
//            $back_point_to=$point->find(array(id=>$subline->to_point_id));
//            if($back_point_from->order > $back_point_to->order) $back=true;
//        }
//        $back=false;
//        if($point_from->order > $point_to->order) $back=true;
//        $td='<tr>
//                                <td>'.$city->city($point_from->city_id, locales::$current_locale).' - '.$city->city($point_to->city_id, locales::$current_locale).'</td>
//                                <td>'.($this->ticket_type==0?locales::$text[ticket_oneway]:locales::$text[ticket_twoway]).'</td>
//                                <td>'.$this->place.'</td>
//                                <td>'.($this->ticket_type==1?$this->place_back:locales::$text[none]).'</td>
//                                <td>'.date('d.m.Y',strtotime($this->date)).' / '.(date('H:i',strtotime($back?$point_from->arrival_time_back:$point_from->arrival_time)+(($back?$point_from->stopover_back:$point_from->stopover)*60))).'</td>
//                                <td>'.($this->ticket_type==1?date('d.m.Y',strtotime($this->date_back)).' / '.(date('H:i',strtotime($back_back?$point_to->arrival_time_back:$point_to->arrival_time)+(($back_back?$point_to->stopover_back:$point_to->stopover)*60))):locales::$text[none]).'</td>
//                                <td>'.ucwords($this->passenger_name).'</td>
//                                <td><b>'.$this->price.' BGN / '.$currency->convert($this->price, 'eur').' EUR</b></td>
//                            </tr>';
//        return $td;
//    }

    public function list_reservations(array $reservations) {
        $user = new User();
        $subln = new Subline();
        $point = new Line_point();
        $city = new City();
        $discounts = new Discount();
        $currency = new Currencies();
        $this->page_contents = '';
        foreach ($reservations as $reservation) {
            $discount_desc = locales::$text[none];
            if ($reservation->discount_id != 0) {
                $current_discount = $discounts->find(array(id => $reservation->discount_id));
                if (locales::$current_locale == 'bg')
                    $discount_desc = $current_discount->name_bg;
                else
                    $discount_desc = $current_discount->name_en;
            }
            $subline = $subln->find(array(id => $reservation->subline_id));
            $point_from = $point->find(array(id => $subline->from_point_id));
            $point_to = $point->find(array(id => $subline->to_point_id));

            $back = false;
            if ($point_from->order > $point_to->order) {
                $back = true;
            }

            $current_user = $user->find(array(id => $reservation->user_id));

            $ticket_type = locales::$text[ticket_oneway];
            if ($reservation->ticket_type == 1) {
                $ticket_type = locales::$text[ticket_twoway];

                $back_subline = $subln->find(array(id => $reservation->back_subline_id));
                $back_point_from = $point->find(array(id => $back_subline->from_point_id));
                $back_point_to = $point->find(array(id => $back_subline->to_point_id));

                $return_back = false;
                if ($back_point_from->order > $back_point_to->order) {
                    $return_back = true;
                }
            } elseif ($reservation->ticket_type == 2) {
                $ticket_type = locales::$text[ticket_opened_twoway];
            }

            if ($reservation->payed == 0) {
                $payed = '<b style="color:red; text-decoration:blink;">' . locales::$text[ticket_not_paied] . '</b>';
            } elseif ($reservation->payed == 1) {
                $payed = '<b style="color:green;">' . locales::$text[ticket_paied] . '</b>';
            } elseif ($reservation->payed == 2) {
                $payed = '<b style="color:yellow;">' . locales::$text[returned] . ': ' . Returned_ticket::return_date($reservation->id) . '</b>';
            }

            $this->page_contents.='
                <div id="parentSells" class="lists divBox clearfix">
                        <h2 align="center">' . $city->city($point_from->city_id, locales::$current_locale) . ' - ' . $city->city($point_to->city_id, locales::$current_locale) . '</h2>
                            <div class="ticket-info clearfix" >
                                 <table class="lists-ticket-info clearfix">
                                            <tr>
                                                 <th>' . locales::$text[table_ticket_number] . '</th>
                                                 <td>' . (strlen($reservation->ticket_number) > 0 ? '<a onclick="window.open(\'./ticket_print.php?rid=' . $reservation->id . '\',\'Ticket\', \'height = 400, width = 800, scrollbars = 1, menubar = 1\'); return false;" href="#"><button class="invoice">' . $reservation->ticket_number . '</button></a>' : '<a href="./add_ticket_number.php?rid=' . $reservation->id . '"><button class="invoice">' . locales::$text[add_ticket_number] . '</button></a>') . '</td>
                                            </tr>
                                            <tr>
                                                <th>' . locales::$text[table_ticket_type] . '</th>
                                                <td>' . $ticket_type . '</td>
                                            </tr>
                                            <tr>
                                                <th>' . locales::$text[table_place] . '</th>
                                                <td>' . $reservation->place . '</td>
                                            </tr>
                                             <tr>
                                                <th>' . locales::$text[table_place_back] . '</th>
                                                <td>' . ($reservation->ticket_type == 1 ? $reservation->place_back : locales::$text[none]) . '</td>
                                            </tr>
                                            <tr>
                                                <th>' . locales::$text[table_date] . '</th>
                                                
                                                <td>' . date('d.m.Y', strtotime($reservation->date)) . '</td>
                                            </tr>
                                            <tr>
                                                <th>' . locales::$text[table_date_time] . '</th>
                                                
                                                <td>' . (date('H:i', strtotime(($back ? $point_from->arrival_time_back : $point_from->arrival_time)) + ($point_from->stopover * 60))) . '</td>  
                                            </tr>
                                            <tr>
                                                <th>' . locales::$text[table_date_back] . '</th>
                                                <td>' . ($reservation->ticket_type == 1 ? date('d.m.Y', strtotime($reservation->date_back)) . ' / ' . (date('H:i', strtotime(($return_back ? $back_point_from->arrival_time_back : $back_point_from->arrival_time)) + ($back_point_from->stopover_back * 60))) : locales::$text[none]) . '</td>
                                                
                                            </tr>
                                            <tr>
                                                <td colspan="2" align="center">' . $payed . '</td>
                                            </tr>
                                  </table>
                              </div>
                        <div class="person-info clearfix">
                           <div style="border-bottom:1px dotted gray" class="clearfix">
                                <label>' . locales::$text[tickets_passenger_name] . '</label>
                                <span>' . ucwords($reservation->passenger_name) . '</span>
                           </div>
                           <div style="border-bottom:1px dotted gray" class="clearfix">
                                <label>' . locales::$text[tickets_birthday] . '</label>
                                <span>' . ($reservation->birthday != '0000-00-00' ? date('d.m.Y', strtotime($reservation->birthday)) : locales::$text[none]) . '</span>
                           </div>
                           <div style="border-bottom:1px dotted gray" class="clearfix">
                                <label>' . locales::$text[tickets_passenger_passport] . '</label>
                                <span>' . $reservation->passenger_passpor . '</span>
                            </div>
                            <div style="border-bottom:1px dotted gray" class="clearfix">
                                <label>' . locales::$text[tickets_phone] . '</label>
                                <span>' . $reservation->contact_phone . '</span>
                            </div>
                            <div style="border-bottom:1px dotted gray" class="clearfix">
                                <label>' . locales::$text[tickets_email] . '</label>
                                <span>' . $reservation->contact_email . '</span>
                            </div>
                            <div style="border-bottom:1px dotted gray" class="clearfix">
                                <label>' . locales::$text[tickets_price] . '</label>
                                <span>' . $reservation->price . ' BGN / ' . $currency->convert($reservation->price, 'eur') . ' EUR</span>
                            </div>
                            <div style="border-bottom:1px dotted gray" class="clearfix">
                                <label>' . locales::$text[tickets_discount] . '</label>
                                <span>' . $discount_desc . '</span>
                            </div>
                            <div style="border-bottom:1px dotted gray" class="clearfix">
                                <label>' . locales::$text[reserved_by] . '</label>
                                <span>' . ($reservation->user_id == 0 ? Env::OnlineSysName : $current_user->user) . '</span>
                            </div>
                            <div style="border-bottom:1px dotted gray" class="clearfix">
                                <label>' . locales::$text[last_update] . ': </label>
                                <span>' . date('d.m.Y H:i', strtotime($reservation->last_update)) . '</span>
                            </div>
                       </div>
                       <div class="clear-div-both"></div>
                       <div class="float-right clearfix padding-10">
                            ' . (((Session::current_user()->id == $reservation->user_id || $reservation->user_id == 0 || Session::access_is_allowed(administration_access)) && Session::access_is_allowed(sale_edit_access)) ? '<a href="client_details.php?rid=' . $reservation->id . '" ><button class="invoice">' . locales::$text[edit] . '</button></a>' : '') . '

                            ' . (Session::access_is_allowed(sale_delete_access) ? '<a href="' . $_SERVER[PHP_SELF] . '?drid=' . $reservation->id . '" onclick="return confirm(\'' . locales::$text[confirm_msg] . '\');"><button class="invoice">' . locales::$text[delete] . '</button></a>' : '') . '

                            ' . ($reservation->payed == 1 &&
                        strlen($reservation->ticket_number) > 0 &&
                        ((Session::current_user()->id == $reservation->user_id || $reservation->user_id == 0 || Session::access_is_allowed(administration_access)) && Session::access_is_allowed(sale_return_access) && strtotime($reservation->date) >= strtotime(date('Y-m-d'))) ? '<a href="./return_ticket.php?rid=' . $reservation->id . '" onclick="return confirm(\'' . locales::$text[confirm_msg] . '\');"><button class="invoice">' . locales::$text[ticket_return] . '</button></a>' : '') . '

                            ' . ($reservation->ticket_type == 2 ? '<a href="./finalize_open_ticket.php?rid=' . $reservation->id . '" onclick="return confirm(\'' . locales::$text[confirm_msg] . '\');"><button class="invoice">' . locales::$text[add_back_date_back_place] . '</a>' : '') . '</button>
                      </div>
               </div>
                   ';
        }
        return $this->page_contents;
    }

    public function add_ticket_number_form() {
        Session::user_auth();
        if (!isset($_GET[rid]))
            header('location: ./list_reservations.php');

        $this->template_name = 'agents';
        $this->page_contents = '<form action="' . $_SERVER[PHP_SELF] . '" method="POST">
                <input type="hidden" name="rid" value="' . $_GET[rid] . '" />
                <div class="addnumber forms" align="center">
                    ' . locales::$text[tickets_number] . '
                    <input type="text" name="ticket_number" value="" />
                    <input class="invoice " type="submit" value="' . locales::$text[add_ticket_number] . '" name="add_number" />
               </div>
                </form>';
        return $this->show_page();
    }

    public function edit_client_details($errors = null) {
        Session::user_auth();
        $this->template_name = 'agents';
        list($firstName, $lastName) = explode(' ', $this->passenger_name);
        $this->page_contents = $this->errorOut($errors) . '<form action="' . $_SERVER[PHP_SELF] . '" method="POST">
                    <input type="hidden" name="reserve[id]" value="' . $this->id . '" />
                    <table class="forms" align="center">
                        <tr>
                            <td>Име на пътника: </td>
                            <td>
                                <div><input type="text" name="reserve[passenger_name][0]" value="' . $firstName . '" /></div>
                                <div><small><i>' . locales::$text['personal_name_info'] . '</i></small></div>
                                <div><input type="text" name="reserve[passenger_name][1]" value="' . $lastName . '" /></div>
                                <div><small><i>' . locales::$text['family_name_info'] . '</i></small></div>
                            </td>
                        </tr>
                        <tr>
                            <td>Номер на паспорт: </td>
                            <td><input type="text" name="reserve[passenger_passpor]" value="' . $this->passenger_passpor . '" /></td>
                        </tr>
                        <tr>
                            <td>Рожденна дата: </td>
                            <td><input type="text" class="datepicker" id="birthday" name="reserve[birthday]" value="' . $this->birthday . '" onclick="NewCssCal(\'birthday\',\'yyyymmdd\');" readonly="readonly"/></td>
                        </tr>
                        <tr>
                            <td>Телефон за контакти: </td>
                            <td><input type="text" name="reserve[contact_phone]" value="' . $this->contact_phone . '" /></td>
                        </tr>
                        <tr>
                            <td>E-mail за контакти: </td>
                            <td><input type="text" name="reserve[contact_email]" value="' . $this->contact_email . '" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right"><input type="submit" value="' . locales::$text[tickets_add] . '" name="save_client_details" /></td>
                        </tr>
                    </table>
                </form>';
        if (!Session::access_is_allowed(sale_edit_access)) {
            $this->page_contents = '<h2 align="center" style="color:red">' . locales::$text[no_permission] . '</h2>';
        }
        return $this->show_page();
    }

    public function save_client_details(array $elements) {
        $this->id = $elements[id];
        $this->passenger_name = implode(' ', $elements[passenger_name]);
        $this->passenger_passpor = $elements[passenger_passpor];
        $this->birthday = $elements[birthday];
        $this->contact_phone = $elements[contact_phone];
        $this->contact_email = $elements[contact_email];

        $reserve = new Reservation();
        $err = $reserve->update_client_details($this);
        if (is_array($err)) {
            return $this->edit_client_details($err);
        } else {
            header('location: ./list_reservations.php');
        }
    }

    public function add_ticket_number($number) {
        Session::user_auth();
        if (strlen($this->ticket_number) == 0) {
            $this->ticket_number = $number;
            $reserving = new Reservation();
            $reserving->add_ticket_number($this);
        }
        header('location: ./list_reservations.php');
    }

    public function finalize_ticket($date_back, $place_back, $back_subline_id) {
        Session::user_auth();
        if ($this->ticket_type == 2) {
            $this->date_back = $date_back;
            $this->place_back = $place_back;
            $this->back_subline_id = $back_subline_id;
            $reserve = new Reservation();
            $err = $reserve->finalize_opened_ticket($this);
            if (is_array($err)) {
                return $this->finalize_ticket_form($date_back, $back_subline_id, $err);
            } else {
                header('location: ./list_reservations.php');
            }
        } else {
            header('location: ./list_reservations.php');
        }
    }

    public function return_ticket($reservation_id) {
        $reserve = new Reservation();
        $reserve->ticket_return($reservation_id);
        header('location: ./list_reservations.php');
    }

    public function finalize_ticket_form($date, $new_subline = null, $err = null) {
        Session::user_auth();

        if (!isset($_GET['rid']) && !isset($_POST['rid']))
            header('location: ./list_reservations.php');

        $this->template_name = 'agents';


        $subln = new Subline();
        $subline = new Sublines();
        $backSubline = -1;
        if (strtotime($this->date) < strtotime($date)) {
            $backSubline = $subline->getSubline($new_subline, $date);
        }
        $subline = $subln->find(array('id' => $this->subline_id));

        $point = new Line_point();
        $from_point = $point->find(array(id => $subline->from_point_id));
        $to_point = $point->find(array(id => $subline->to_point_id));

        $points_to = $point->find(array('all' => true, 'where' => "city_id=" . $from_point->city_id));
        $points_from = $point->find(array('all' => true, 'where' => "city_id=" . $to_point->city_id));
        $city = new City();
        $line = new Line();

        $subline_var = array();
        foreach ($points_from as $point_from) {
            foreach ($points_to as $point_to) {
                $subline_buffer = $subln->find(array(all => true, where => "from_point_id=" . $point_from->id . " AND to_point_id=" . $point_to->id));
                foreach ($subline_buffer as $buffer) {
                    $subline_var[] = $buffer;
                }
            }
        }
        $options_for_subline = '';
        foreach ($subline_var as $single_object) {
            $options_for_subline.='<OPTGROUP LABEL="' . $city->city($line->find(array(id => $single_object->line_id))->from_city_id, locales::$current_locale) . ' - ' . $city->city($line->find(array(id => $single_object->line_id))->to_city_id, locales::$current_locale) . ' ' . $single_object->line_id . '">
                <option value="' . $single_object->id . '" ' . ($new_subline ? ($single_object->id == $new_subline ? 'selected="selected"' : '') : ($subline->id == $single_object->id ? 'selected="selected"' : '')) . '>' . $city->city($point->find(array(id => $single_object->from_point_id))->city_id, locales::$current_locale) . ' - ' . $city->city($point->find(array(id => $single_object->to_point_id))->city_id, locales::$current_locale) . '</option>
                </optgroup>';
        }

        $this->page_contents = '<table align="center" class="forms" width="600">
                <tr>
                    <td colspan="2">
                        <form action="' . $_SERVER[PHP_SELF] . '?">
                        ' . locales::$text[tickets_date] . '
                        <input type="hidden" name="rid" value="' . $this->id . '" />
                        <input type="text"  id="_date" name="_date" value="' . $date . '" onclick="NewCssCal(\'_date\',\'yyyymmdd\');" />
                        <select name="new_subline">
                        ' . $options_for_subline . '
                        </select>
                        <input class="invoice" type="submit" value="Go" />
                        </form>
                    </td>
                </tr>
                ' . ($date ?
                        '<tr>
                <td valign="top">
                    ' . $this->errorOut($err) . '
                    <form action="' . $_SERVER[PHP_SELF] . '" method="POST">
                        ' . locales::$text[tickets_place] . '
                        <input type="hidden" name="rid" value="' . $this->id . '" />
                        <input id="reserve_place_back" type="text" name="place" value="" size="2" readonly="readonly" />
                        <input type="hidden" name="date" value="' . $date . '" /><br />
                        <input type="hidden" name="subline_id" value="' . ($new_subline ? $new_subline : $subline->id) . '" /><br />
                        <input type="submit" value="' . locales::$text[tickets_add] . '" name="add_place_date_back" />
                    </form>
                </td>
                <td class="places" id="twoway_back">' . ($new_subline ? $this->bus_places($backSubline, $date, '_back') : $this->bus_places($subline, $date, '_back')) . '</td>
                </tr>' : '') . '
                </table>';

        return $this->show_page();
    }

    public function destroy($id) {
        Session::user_auth();
        $reserve = new Reservation();
        $reserve->delete($id);
        header('location: ' . $_SERVER['HTTP_REFERER']);
    }

}

?>
