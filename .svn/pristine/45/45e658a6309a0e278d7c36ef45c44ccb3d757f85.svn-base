<?php

/*
 *
 */

include_once 'gui.php';
include_once './models/Reservation.php';
include_once './controllers/Reservation_buffers.php';
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
        $api = new PdfApi($this->print_ticket($_GET['rid']));
        header('Content-Disposition: attachment; filename="Online-ticket(' . (int) $_GET['rid'] . ').pdf"');
        header('Content-type: application/pdf');
        echo $api->output();
    }

    public function print_ticket($reservation_id) {
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

    public function reserve_form($subline, $date, $twoway = null, $date_back = null, $back_subline_id = null) {
        if (empty($back_subline_id))
            $back_subline_id = -1;

        if ($subline != -1) {
            $discout = new Discount();
            $discounts = $discout->find(array(all => ''));

            $old_price = ($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ? $subline->price_twoway : $subline->price_oneway);

            $promoH = new Promotion();
            $promo = $promoH->find(array(all => 1, where => "subline_id=$subline->id AND expires >= '" . date('Y-m-d') . "' "));
            if (isset($promo[0]) && $old_price > 0) {
                $old_price = $old_price - ($old_price * $promo[0]->promo_percent / 100);
            }

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

                return '<form style="font-size:13px;" action="save_data.php" method="POST" onsubmit="new Ajax.Updater(
                                                                \'reservation_form\',
                                                                \'./save_data.php?' . $_SERVER[QUERY_STRING] . '\',
                                                                {asynchronous:true, evalScripts:true,
                                                                onLoaded:function(request){refresh_seats(' . ($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ? '1' : '0') . ',\'' . $_SERVER[QUERY_STRING] . '\');},
                                                                onLoading:function(request){$(\'reservation_form\').innerHTML=loading;},
                                                                parameters:Form.serialize(this)});
                                                                return false;" >
                        <input type="hidden" name="reserve[subline_id]" value="' . $subline->id . '" />
                        ' . ($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ? '<input type="hidden" name="reserve[back_subline_id]" value="' . $back_subline_id . '" />' : '') . '
                        <input type="hidden" name="reserve[ticket_type]" value="' . ($twoway && $date_back && strtotime($date_back) > strtotime($date) ? '1' : '0') . '" />
                        <table>
                            <tr>
                                <td>' . locales::$text[tickets_destination] . '</td>
                                <td>' . $destination . '</td>
                            </tr>
                            <tr>
                                <td>' . locales::$text[tickets_going_date] . '</td>
                                <td>
                                    <input type="hidden" name="reserve[date]" value="' . $date . '"/>
                                    ' . date('d.m.Y', strtotime($date)) . '
                                </td>
                           </tr>
                        ' . ($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ?
                                '<tr>
                                <td>' . locales::$text[tickets_back_date] . '</td>
                                <td>
                                    <input type="hidden" name="reserve[date_back]" value="' . $date_back . '" />
                                    ' . date('d.m.Y', strtotime($date_back)) . '
                                </td>
                            </tr>' : '') . '
                        <tr>
                            <td>' . locales::$text[tickets_place] . '</td>
                            <td>
                                <div class="floatLeft">
                                   <div class="greenInputsLeft floatLeft"></div>
                                   <div class="greenInputsMiddle inputPointsMiddle floatLeft">
                                      <div class="containerGreenInput">
                                         <input type="text" id="reserve_place" name="reserve[place]" value="" size="2" style="width: 15px;" readonly="readonly"/><br />
                                      </div>
                                   </div>
                                   <div class="greenInputsRight floatLeft"></div>
                                   <div class="clear"></div>
                                </div>
                                <div>
                                <br/>
                                <small><i>(' . locales::$text[hint_place] . ')</i></small>
                                </div>
                            </td>
                        </tr>
                        ' . ($back_subline_id != -1 && $twoway && $date_back && strtotime($date_back) > strtotime($date) ?
                                '<tr>
                                <td>' . locales::$text[tickets_place_back] . '</td>
                                <td>
                                    <input type="text" id="reserve_place_back" name="reserve[place_back]" value="" size="2" readonly="readonly"/><br />
                                    <small><i>(' . locales::$text[hint_place_back] . ')</i></small>
                                </td>
                            </tr>' : '') . '
                         <tr>
                            <td>' . locales::$text[tickets_discount] . '</td>
                            <td>
                                <select name="reserve[discount_id]" onchange="show_price_discount(this);" style="width:100px"> 
                                ' . $options_for_discount . '            
                                </select><br/>                                
                                <small><i>(' . locales::$text[hint_discount] . ')</i></small>
                            </td>
                         </tr>
                         <tr>
                            <td>' . locales::$text[tickets_passenger_name] . '</td>
                            <td>
                                <div class="greenInput floatLeft">
                                   <div class="greenInputsLeft floatLeft"></div>
                                    <div class="greenInputsMiddle inputPointsMiddle floatLeft">
                                       <div class="containerGreenInput">
                                          <input type="text" name="reserve[passenger_name][0]" value="" />
                                       </div>
                                    </div>
                                   <div class="greenInputsRight floatLeft"></div>
                                   <div class="clear"></div>
                                </div>
                                <div><small><i>(' . Locales::$text['personal_name_info'] . ')</i></small></div>
                                <div class="greenInput floatLeft">
                                   <div class="greenInputsLeft floatLeft"></div>
                                    <div class="greenInputsMiddle inputPointsMiddle floatLeft">
                                       <div class="containerGreenInput">
                                          <input type="text" name="reserve[passenger_name][1]" value="" />
                                       </div>
                                    </div>
                                   <div class="greenInputsRight floatLeft"></div>
                                   <div class="clear"></div>
                                </div>
                                <div><small><i>(' . Locales::$text['family_name_info'] . ')</i></small></div>
                            </td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_passenger_passport] . '</td>
                            <td>
                                <div class="greenInput floatLeft">
                                   <div class="greenInputsLeft floatLeft"></div>
                                    <div class="greenInputsMiddle inputPointsMiddle floatLeft">
                                       <div class="containerGreenInput">
                                          <input type="text" name="reserve[passenger_passpor]" value="" /><br />
                                       </div>
                                    </div>
                                   <div class="greenInputsRight floatLeft"></div>
                                   <div class="clear"></div>
                                </div>
                                   <small><i>(' . locales::$text[hint_passport] . ')</i></small>
                            </td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_birthday] . '</td>
                            <td>
                            <div class="greenInput floatLeft">
                                   <div class="greenInputsLeft floatLeft"></div>
                                    <div class="greenInputsMiddle inputPointsMiddle floatLeft">
                                       <div class="containerGreenInput">
                                          <input type="text" id="birthday" name="reserve[birthday]" value="" style="width:67px" onclick="NewCssCal(\'birthday\',\'yyyymmdd\');" readonly="readonly" readonly="readonly"/>
                                       </div>
                                    </div>
                                   <div class="greenInputsRight floatLeft"></div>
                                   <div class="clear"></div>
                            </div>
                            </td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_phone] . '</td>
                            <td>
                                <div class="greenInput floatLeft">
                                       <div class="greenInputsLeft floatLeft"></div>
                                        <div class="greenInputsMiddle inputPointsMiddle floatLeft">
                                           <div class="containerGreenInput">
                                              <input type="text" name="reserve[contact_phone]" value="" /><br />
                                           </div>
                                        </div>
                                       <div class="greenInputsRight floatLeft"></div>
                                       <div class="clear"></div>
                                </div>
                                <small><i>(' . locales::$text[hint_phone] . ')</i></small>   
                            </td>
                        </tr>
                        <tr>
                        <td>' . locales::$text[tickets_email] . '</td>
                        <td>
                        <div class="greenInput floatLeft">
                            <div class="greenInputsLeft floatLeft"></div>
                             <div class="greenInputsMiddle inputPointsMiddle floatLeft">
                                <div class="containerGreenInput">
                                   <input type="text" name="reserve[contact_email]" value="" /><br />
                                </div>
                             </div>
                            <div class="greenInputsRight floatLeft"></div>
                            <div class="clear"></div>
                         </div>
                         </td>
                        </tr>
                        <tr>
                            <td>' . locales::$text[tickets_price] . '</td>
                            <td><span id="price_container">' . (double) $old_price . ' BGN / ' . $currency->convert((double) $old_price, 'eur') . ' EUR</span></td>
                        </tr>
                        <tr>
                            <td align="right" colspan="2">                                                          
                                <div class="floatRight rightSideSearchingForm">
                                    <div class="bigGreenButton">
                                         <div class="bigGreenButtonRight floatRight"></div>
                                         <div class="bigGreenButtonMiddle floatRight">
                                        <div>                           
                                        <input style="background:transparent;border:none;cursor:pointer;" type="submit" value="' . locales::$text[tickets_add] . '" />  ' . (isset($_GET['bgr']) ? '<input type="hidden" name="reserve[bgr]" value="true" />' : '') . '
                                        </div>
                                            </div>
                                            <div class=" bigGreenButtonLeft floatRight">
                                            </div>
                                            <div class="clear">
                                            </div>
                                        </div>
                                </div>


                            </td>
                        </tr>
                        </table>
                    </form>
                    <div id="cart_list" align="left">' . $this->view_cart_count($_SESSION['buffer_id']) . '</div>';
            }
        }
        return '<table>
                        <tr>
                            <td>Дестинация:</td>
                            <td>' . $destination . '</td>
                        </tr>
                        <tr>
                            <th colspan="2" align="center">' . locales::$text[tickets_not_active] . '</td>
                        </tr>
                    </table>';
    }

    public function reserve_cvs($subline_id, $date, $twoway = null, $date_back = null, $layout = true) {
        Session::clear_session();

        if (isset($_GET['bgr'])) {
            setcookie('bgr', 'true', time() + (60 * 60 * 24));
        }

        if (isset($_COOKIE['bgr'])) {
            $_GET['bgr'] = $_COOKIE['bgr'];
        }

        $this->buffer_session_start();
        $ticket_type = 'oneway';
        $subline = new Sublines();
        $subline = $subline->getSubline($subline_id, $date);
        if ($twoway && strtotime($date_back) > strtotime($date)) {
            $ticket_type = 'twoway';
            if ($subline != -1) {
                $subline_back = $subline->getBackSubline($date_back);
            } else {
                $subline_back = -1;
            }
        }

        $reserve_views = '<div>
                        <form action="' . $_SERVER[PHP_SELF] . '" method="get" onsubmit="new Ajax.Updater(
                                                                \'main_window\',
                                                                \'./MainWINDOW.php\',
                                                                {method: \'get\',
                                                                onLoading: function(){ $(\'main_window\').innerHTML=loading_app; },
                                                                parameters:Form.serialize(this)});
                                                                return false;" >
                           <div class="dateTwoway" >
                              ' . (isset($_GET[bgr]) ? '
                              <input type="hidden" name="bgr" value="true" />' : '') . '
                              <input type="hidden" name="_subline" value="' . $subline_id . '" />
                              <br />
                              <div class="floatLeft date">
                                  ' . locales::$text[tickets_date] . '
                              </div>
                              <div class="daysSearchingForm floatLeft">
                                <div class="greenInputsLeft floatLeft"></div>
                                    <div class="greenInputsMiddle floatLeft">
                                        <div class="containerGreenInput floatLeft">
                                          <input type="text" id="_date" name="_date" value="' . $date . '" onclick="NewCssCal(\'_date\',\'yyyymmdd\');" readonly="readonly" />
                                        </div>
                                    </div>
                                <div class="greenInputsRight floatLeft"></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="floatLeft twoWayCheckbox">
                                    <input id="twoday_checkbox" type="checkbox" name="twoway" value="1" onclick="show_back_date();" ' . ($twoway ? 'checked="checked"' : '') . ' /> 
                                </div>
                                <div class="floatLeft date">
                                <label for="twoday_checkbox">
                                ' . locales::$text[tickets_twoway] . '
                                    </label>
                                </div>
                                <div id="_back_date" style="display:none;" class="daysSearchingForm floatLeft">
                                         <div class="greenInputsLeft floatLeft"></div>
                                            <div class="greenInputsMiddle floatLeft">
                                                <div class="containerGreenInput">
                                                  <input id="_back_date_input" type="text"  name="_date_back" value="' . $date_back . '"  onclick="NewCssCal(\'_back_date_input\',\'yyyymmdd\');" readonly="readonly" />
                                                </div>                                
                                        </div>
                                    <div class="greenInputsRight floatLeft"></div>
                                    <div class="clear"></div>
                                </div>
                                <div class="submitArrows floatLeft rightSideSearchingForm" >
                                <div class="bigGreenButton">
                                     <div class="bigGreenButtonLeft floatLeft"></div>
                                     <div class="bigGreenButtonMiddle floatLeft">
                                 <div>                                
                                    <input style="margin:1px 0px 0px -1px;width: auto;border: none;background: transparent;cursor:pointer;" type="submit" value=">>"/>
                                    </div>
                                        </div>
                                        <div class="bigGreenButtonRight floatLeft">
                                        </div>
                                        <div class="clear">
                                        </div>
                                    </div>
                            </div>
                            </div>
                       </form>
                       <div id="reservation_form" class="reserve_form">
                        ' . $this->reserve_form($subline, $date, $twoway, $date_back, ($subline_back != -1 ? $subline_back->id : null)) . '
                       </div>
                       </div>
                       <div class="places clearfix" id="oneway">
                       <div class="head">' . locales::$text[tickets_going] . '</div>
                       ' . $this->bus_places($subline, $date) . '
                       </div>
                       <div class="places" id="twoway_back">
                       ' . ($ticket_type == 'twoway' ? '<div class="head">' . locales::$text[tickets_back] . '</div>' . $this->bus_places($subline_back, $date_back, '_back') : '') . '
                       </div>
                        <div class="reservations">';
        $reserve_views.='</div>';

        $this->page_contents = $reserve_views;
        if ($layout) {
            $this->page_contents = '<div id="main_window">' . $this->page_contents . '</div>';
            return $this->show_page();
        }
        else
            return $this->page_contents;
    }

    private function buffer_session_start() {
        if (!isset($_SESSION['buffer_id'])) {
            $_SESSION['buffer_id'] = md5(time());
        }
    }

    public function view_cart_count($buffer_id) {
        $buffer = new Reservation_buffer();
        $cart = $buffer->find(array(all => 1, where => "buffer_id='$buffer_id'"));
        return '
            <div class="basketAndTerms">  
                    <a style="float:left;" href="#" onclick="window.open (\'./view_cart.php\',\'mywindow\',\'status=1,width=794,height=500\'); return false;">' . locales::$text[tickets_in_basket] . ' ' . sizeof($cart) . ' ' . locales::$text[tickets_count] . '</a>
                    ' . (sizeof($cart) > 0 ? '
                 <div class="layout-block-c">
                    <a class="sublineSearchBufferCellLink classic-buy-button box-corners-all" style=":hover{color: yellow}" rer="nofollow" target="_blank" href="./pay.php" onclick="window.location.reload();">' . locales::$text[tickets_buy] . '</a>' : '') . '
                </div>
            </div>
            <br>
            <div class="terms" >
                    <a target="_blank" href="./travel_conditions.php">' . locales::$text[travel_terms] . '</a>
            </div>';
    }

    public function view_cart_buffer($buffer_id) {
        $this->template_name = 'reports';
        $buffer = new Reservation_buffer();
        $cart = $buffer->find(array(all => 1, where => "buffer_id='$buffer_id'"));
        $html = '';
        $html = '<style>
                    body, html {
                    margin:0;
                    padding:0;
                    width: 100%;
                    }
                    .cart-table {
                        font-size: 14px;
                        width: 100%;
                        border-collapse: collapse;
                    }
                    
                    .cart-table td,.cart-table th{
                        border: 1px solid gray;
                    }
                    .cart-table th {
                        color: white;
                        background:black;
                    }
                    .cart-table td {
                        background: white;
                    }
                    .cart-table .buy-button {
                        padding: 10px;
                        font-size: 22px;
                    }
                   </style>
                   <table class="cart-table">
                    <tr>
                        <th></th>
                        <th>' . locales::$text[report_destination] . '</th>
                        <th>' . locales::$text[report_ticket_type] . '</th>
                        <th>' . locales::$text[report_place] . '</th>
                        <th>' . locales::$text[report_place_back] . '</th>
                        <th>' . locales::$text[report_name] . '</th>
                        <th>' . locales::$text[report_price] . '</th>
                        <th>' . locales::$text[report_date] . '</th>
                        <th>' . locales::$text[report_date_back] . '</th>
                    </tr>';
        if (sizeof($cart) > 0) {
            foreach ($cart as $element)
                $html.=$element->view_cart_element();
        } else {
            $html.='<tr>
                        <td colspan="9" align="center">' . locales::$text[tickets_basket_empty] . '</td>
                    </tr>';
        }
        $html.='</table>';

        $this->page_contents = $html;
        return $this->show_page();
    }

    public function save(array $elements) {
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

        if (isset($elements['bgr']))
            $this->user_id = 61;
        else
            $this->user_id = 0;

        $subln = new Sublines();
        $subline = $subln->getSubline($this->subline_id, $this->date);
        if ($subline == -1) {
            return $this->errorOut(array('Грешка при избирането на дестинация.<pre>' . print_r($elements, true) . '</pre>'));
        }

        $this->price = $subline->price_oneway;

        $promoH = new Promotion();
        $promo = $promoH->find(array(all => 1, where => "subline_id=$subline->id AND expires >= '" . date('Y-m-d') . "'"));
        if (isset($promo[0])) {
            $this->price = $this->price - ($this->price * $promo[0]->promo_percent / 100);
        }

        if ($this->ticket_type == 1) {
            $this->price = $subline->price_twoway;
            if (isset($promo[0])) {
                $this->price = $this->price - ($this->price * $promo[0]->promo_percent / 100);
            }
            $this->date_back = $elements[date_back];
            $this->place_back = $elements[place_back];
            $this->back_subline_id = $elements['back_subline_id'];
            $back_subline = $subln->getSubline($this->back_subline_id, $this->date_back);
            if ($back_subline == -1) {
                return $this->errorOut(array('Грешка при избирането на дестинация за връщане.'));
            }
        }

        if ($this->discount_id != 0) {
            $discount = new Discount;
            $disc = $discount->find(array(id => $this->discount_id));
            $this->price = ($disc->discount_type == 0 ? ($this->price - ($this->price * $disc->discount / 100)) : ($this->price - $disc->discount));
        }

        $reserve = new Reservation();
        $err = $reserve->insert($this, $_SESSION['buffer_id']);
        return $this->errorOut($err);
    }

}

?>
