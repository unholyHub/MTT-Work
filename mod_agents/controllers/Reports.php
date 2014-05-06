<?php

/*
 * 
 */

include_once 'gui.php';
include_once './controllers/Session.php';
include_once './controllers/Reservations.php';
include_once './controllers/Line_points.php';
include_once './controllers/Invoices.php';
include_once './controllers/Invoice_elements.php';

class Reports extends gui {

    function __construct() {
        $this->template_name = 'agents';
    }

    public function date_subline_report($subline_id, $date) {
        Session::user_auth();
        $subl = new Subline();
        $point = new Line_point();
        $subline = $subl->find(array(id => $subline_id));
        $city = new City();
        $currency = new Currencies();
        $this->template_name = 'reports';
        $travel_info = '
                <table style="float:left; font-size:14px; width:100px;">
                    <tr>
                        <td height="100" valign="bottom">' . locales::$text[report_reg_num] . '</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #cfcfcf"><input style="border:none; background-color:none; font-size:12px;" type="text" name="reg_num" value="" size="10"/></td>
                    </tr>
                    <tr>
                        <td height="100" valign="bottom">' . locales::$text[report_driver1] . '</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #cfcfcf"><textarea style="border:none; background-color:none; font-size:12px;" name="1dr" rows="2" cols="10"></textarea></td>
                    </tr>
                    <tr>
                        <td height="100" valign="bottom">' . locales::$text[report_driver2] . '</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #cfcfcf"><textarea style="border:none; background-color:none; font-size:12px;" name="2dr" rows="2" cols="10"></textarea></td>
                    </tr>
                    <tr>
                        <td height="100" valign="bottom">' . locales::$text[report_stewart] . '</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #cfcfcf"><textarea style="border:none; background-color:none; font-size:12px;" name="stewart" rows="2" cols="10"></textarea></td>
                    </tr>
                    <tr>
                        <td height="100" valign="bottom">' . locales::$text[report_destination] . '</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #cfcfcf">' . $city->city($point->find(array(id => $subline->from_point_id))->city_id, locales::$current_locale) . ' - ' . $city->city($point->find(array(id => $subline->to_point_id))->city_id, locales::$current_locale) . '</td>
                    </tr>
                    <tr>
                        <td height="100" valign="bottom">' . locales::$text[report_date] . '</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #cfcfcf">' . date('d.m.Y', strtotime($date)) . '</td>
                    </tr>
                </table>';
        $this->page_contents = $travel_info;
        $this->page_contents.='<table align="left" class="subline_date">
                <tr>
                    <td style="border:0;" colspan="6" border="0">
                        <input type="button" value="' . locales::$text['print'] . '" onclick="printReport(this);" />
                        <a href="xls_export.php?type=' . __FUNCTION__ . '&' . $_SERVER['QUERY_STRING'] . '" target="_blank"><input type="button" value="' . locales::$text['xls_export'] . '" /></a>
                    </td>
                </tr>
                <tr>
                    <th align="left" width="5">#</th>
                    <th align="left" width="10">' . locales::$text[report_place] . '</th>
                    <th align="left">' . locales::$text[report_ticket] . '</th>
                    <th align="left">' . locales::$text[report_name] . '</th>
                    <th align="left">' . locales::$text[report_price] . '</th>
                    <th align="left">' . locales::$text[report_reserved_by] . '</th>
                    <th align="left">' . locales::$text[report_destination] . '</th>
                    <th align="left">' . locales::$text[last_update] . '</th>
                </tr>';
        $places = array();

        $user = new User();

        $reservations = $subline->get_reserved_objects($date);
        for ($i = 1; $i <= 57; $i++) {

            //if($reservation!=null){
            foreach ($reservations as $reservation) {
                if (($reservation->place == $i && $reservation->date == $date) || ($reservation->place_back == $i && $reservation->date_back == $date)) {
                    if ($reservation->date == $date)
                        $current_subline = $subl->find(array(id => $reservation->subline_id));
                    elseif ($reservation->date_back == $date)
                        $current_subline = $subl->find(array(id => $reservation->back_subline_id));
                    $user_name = $user->find(array(id => $reservation->user_id));
                    $places[] = '<td>' . $i . '</td>
                            <td><span style="font-size:12px;">' . $reservation->ticket_number . '</span></td>
                            <td><span style="font-size:12px;">' . ucwords($reservation->passenger_name) . '</span></td>
                            <td><span style="font-size:12px;">' . $reservation->price . ' L / ' . $currency->convert($reservation->price, 'eur') . ' E</span></td>
                            <td><span style="font-size:11px;">' . $user_name->user . '</span></td>
                            <td><span style="font-size:12px;">' . $city->city($point->find(array(id => $current_subline->from_point_id))->city_id, locales::$current_locale) . ' - ' . $city->city($point->find(array(id => $current_subline->to_point_id))->city_id, locales::$current_locale) . '</span></td>
                            <td><span style="font-size:12px;">' . date('d.m.y H:i', strtotime($reservation->last_update)) . '</span></td>';
                }
            }
        }

        $count = 57;
        $per_page = 57;
        $br == 0;
        if (sizeof($places) > $count)
            $count = $count * (ceil(sizeof($places) / $count));
        for ($i = 0; $i < $count; $i++) {
            if ($br == $per_page) {
                $this->page_contents.='</table><br clear="all" /><br />
                        ' . $travel_info . '
                        <table align="left" class="subline_date">

                        <tr>
                            <th align="left" width="5">#</th>
                            <th align="left" width="10">' . locales::$text[report_place] . '</th>
                            <th align="left">' . locales::$text[report_ticket] . '</th>
                            <th align="left">' . locales::$text[report_name] . '</th>
                            <th align="left">' . locales::$text[report_price] . '</th>
                            <th align="left">' . locales::$text[report_reserved_by] . '</th>
                            <th align="left">' . locales::$text[report_destination] . '</th>
                            <th align="left">' . locales::$text[last_update] . '</th>
                        </tr>';
                $br = 0;
            }

            if (isset($places[$i])) {
                $this->page_contents.='
                            <tr>
                                <td>' . ($i + 1) . '</td>
                                ' . $places[$i] . '
                            </tr>';
            } else {
                $this->page_contents.='
                        <tr>
                            <td>' . ($i + 1) . '</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>';
            }
            $br++;
        }

        $this->page_contents.='</table>';
        if (!Session::access_is_allowed(travel_list_report_access))
            $this->page_contents = '<h2 align="center" style="color:red;">' . locales::$text[no_permission] . '</h2>';
        return $this->show_page();
    }

    public function subline_report($subline_id, $from, $to) {
        Session::user_auth();
        $this->template_name = 'reports';
        $this->page_contents = '';

        $reserv = new Reservation();
        if (Session::access_is_allowed(all_sales_access)) {
            //$reservations=$reserv->find(array(all=>1,where=>"payed<2 AND ((subline_id=$subline_id AND `date` BETWEEN '$from' AND '$to') OR (back_subline_id=$subline_id AND date_back BETWEEN '$from' AND '$to'))",order=>'`date_back`,`date` DESC'));
            $reservations = $reserv->find(array(all => 1, where => "payed > 0 AND payed < 2 AND ((subline_id=$subline_id) OR (back_subline_id=$subline_id)) AND created BETWEEN '$from 00:00:00' AND '$to 23:59:59'", order => '`created`,`date` DESC'));
        } else {
            if (Session::access_is_allowed(show_epay_sales_access)) {
                //$reservations=$reserv->find(array(all=>1,where=>"payed<2 AND (user_id=0 OR user_id=".Session::current_user()->id.") AND ((subline_id=$subline_id AND `date` BETWEEN '$from' AND '$to') OR (back_subline_id=$subline_id AND date_back BETWEEN '$from' AND '$to'))",order=>'`date_back`,`date` DESC'));
                $reservations = $reserv->find(array(all => 1, where => "payed > 0 AND payed < 2 AND (user_id=0 OR user_id=" . Session::current_user()->id . ") AND ((subline_id=$subline_id) OR (back_subline_id=$subline_id)) AND created BETWEEN '$from 00:00:00' AND '$to 23:59:59'", order => '`created`,`date` DESC'));
            } else {
                //$reservations=$reserv->find(array(all=>1,where=>"payed<2 AND (user_id=".Session::current_user()->id.") AND ((subline_id=$subline_id AND `date` BETWEEN '$from' AND '$to') OR (back_subline_id=$subline_id AND date_back BETWEEN '$from' AND '$to'))",order=>'`date_back`,`date` DESC'));
                $reservations = $reserv->find(array(all => 1, where => "payed > 0 AND payed < 2 AND (user_id=" . Session::current_user()->id . ") AND ((subline_id=$subline_id) OR (back_subline_id=$subline_id)) AND created BETWEEN '$from 00:00:00' AND '$to 23:59:59'", order => '`created`,`date` DESC'));
            }
        }
        $discount = new Discount();
        $user = new User();
        $currency = new Currencies();
        $this->page_contents.='<table class="landscape_report">
                <tr>
                    <td colspan="11" style="border:none;">
                        <input type="button" value="' . locales::$text['print'] . '" onclick="printReport(this);" />
                        <a href="xls_export.php?type=' . __FUNCTION__ . '&' . $_SERVER['QUERY_STRING'] . '" target="_blank"><input type="button" value="' . locales::$text['xls_export'] . '" /></a>
                    </td>
                </td>
                <tr>
                    <th align="left">' . locales::$text[report_place] . '</th>
                    <th align="left">' . locales::$text[report_place_back] . '</th>
                    <th align="left">' . locales::$text[report_ticket] . '</th>
                    <th align="left">' . locales::$text[report_ticket_type] . '</th>
                    <th align="left">' . locales::$text[report_name] . '</th>
                    <th align="left">' . locales::$text[report_passport] . '</th>
                    <th align="left">' . locales::$text[report_price] . '</th>
                    <th align="left">' . locales::$text[report_discount] . '</th>
                    <th align="left">' . locales::$text[report_date] . '</th>
                    <th align="left">' . locales::$text[report_date_back] . '</th>
                    <th align="left">' . locales::$text[report_reserved_by] . '</th>

                </tr>';
        $sum = 0;
        foreach ($reservations as $reservation) {
            $disc = $discount->find(array(id => $reservation->discount_id));

            $usr = $user->find(array(id => $reservation->user_id));
            $sum = $sum + $reservation->price;
            $this->page_contents.='
                <tr>
                    <td>' . $reservation->place . '</td>
                    <td>' . ($reservation->ticket_type == 0 || $reservation->ticket_type == 2 ? locales::$text[none] : $reservation->place_back) . '</td>
                    <td>' . ($reservation->ticket_number == '' ? locales::$text[none] : $reservation->ticket_number) . '</td>
                    <td>' . ($reservation->ticket_type == 0 ? locales::$text[ticket_oneway] : ($reservation->ticket_type == 2 ? locales::$text[ticket_opened_twoway] : locales::$text[ticket_twoway])) . '</td>
                    <td>' . ucwords($reservation->passenger_name) . '</td>
                    <td>' . $reservation->passenger_passpor . '</td>
                    <td>' . $reservation->price . ' BGN / ' . $currency->convert($reservation->price, 'eur') . ' EUR</td>
                    <td>' . ($reservation->discount_id == 0 ? locales::$text[none] : (locales::$current_locale == 'bg' ? $disc->name_bg : $disc->name_en)) . '</td>
                    <td>' . date('d.m.Y', strtotime($reservation->date)) . '</td>
                    <td>' . ($reservation->ticket_type == 0 ? locales::$text[none] : date('d.m.Y', strtotime($reservation->date_back))) . '</td>
                    <td>' . ($reservation->user_id == 0 ? Env::OnlineSysName : $usr->user) . '</td>
                </tr>';
        }
        $subln = new Subline();
        $subline = $subln->find(array(id => $subline_id));
        $point = new Line_point();
        $city = new City();
        $this->page_contents.='
                <tr>
                    <td colspan="7" align="right">' . locales::$text[report_sum] . ': ' . $sum . ' BGN / ' . $currency->convert($sum, 'eur') . ' EUR</td>
                    <td colspan="2">' . locales::$text[reports_from_date] . ' ' . date('d.m.Y', strtotime($from)) . ' ' . locales::$text[reports_to_date] . ' ' . date('d.m.Y', strtotime($to)) . '</td>
                    <td colspan="2">' . locales::$text[tickets_destination] . '' . $city->city($point->find(array(id => $subline->from_point_id))->city_id, locales::$current_locale) . ' - ' . $city->city($point->find(array(id => $subline->to_point_id))->city_id, locales::$current_locale) . '</td>
                </tr>
                </table>';

        if (!Session::access_is_allowed(destination_report_access))
            $this->page_contents = '<h2 align="center" style="color:red;">' . locales::$text[no_permission] . '</h2>';
        return $this->show_page();
    }

    public function date_to_date_report($from, $to) {
        Session::user_auth();
        $this->template_name = 'reports';
        $this->page_contents = '';

        $reserv = new Reservation();
        if (Session::access_is_allowed(all_sales_access)) {
            $reservations = $reserv->find(array(all => 1, where => "payed > 0 AND payed<2 AND ((`date` BETWEEN '$from 00:00:00' AND '$to 23:59:59') OR (date_back BETWEEN '$from 00:00:00' AND '$to 23:59:59'))"));
        } else {
            if (Session::access_is_allowed(show_epay_sales_access)) {
                $reservations = $reserv->find(array(all => 1, where => "payed > 0 AND payed<2 AND (user_id=0 OR user_id=" . Session::current_user()->id . ") AND ((`date` BETWEEN '$from 00:00:00' AND '$to 23:59:59') OR (date_back BETWEEN '$from 00:00:00' AND '$to 23:59:59'))"));
            } else {
                $reservations = $reserv->find(array(all => 1, where => "payed > 0 AND payed<2 AND (user_id=" . Session::current_user()->id . ") AND ((`date` BETWEEN '$from 00:00:00' AND '$to 23:59:59') OR (date_back BETWEEN '$from 00:00:00' AND '$to 23:59:59'))"));
            }
        }
        $discount = new Discount();
        $user = new User();
        $currency = new Currencies();
        $this->page_contents.='<table class="landscape_report">
                <tr>
                    <td colspan="11" style="border:none;">
                        <input type="button" value="' . locales::$text['print'] . '" onclick="printReport(this);" />
                        <a href="xls_export.php?type=' . __FUNCTION__ . '&' . $_SERVER['QUERY_STRING'] . '" target="_blank"><input type="button" value="' . locales::$text['xls_export'] . '" /></a>
                    </td>
                </td>
                <tr>
                    <th align="left">' . locales::$text[report_place] . '</th>
                    <th align="left">' . locales::$text[report_place_back] . '</th>
                    <th align="left">' . locales::$text[report_ticket] . '</th>
                    <th align="left">' . locales::$text[report_ticket_type] . '</th>
                    <th align="left">' . locales::$text[report_name] . '</th>
                    <th align="left">' . locales::$text[report_passport] . '</th>
                    <th align="left">' . locales::$text[report_price] . '</th>
                    <th align="left">' . locales::$text[report_discount] . '</th>
                    <th align="left">' . locales::$text[report_destination] . '</th>
                    <th align="left">' . locales::$text[report_reserved_by] . '</th>

                </tr>';
        $sum = 0;
        $subln = new Subline();
        $point = new Line_point();
        $city = new City();
        foreach ($reservations as $reservation) {
            $subline = $subln->find(array(id => $reservation->subline_id));
            $disc = $discount->find(array(id => $reservation->discount_id));
            $usr = $user->find(array(id => $reservation->user_id));
            $sum = $sum + $reservation->price;
            $this->page_contents.='
                <tr>
                    <td>' . $reservation->place . '</td>
                    <td>' . ($reservation->ticket_type == 0 || $reservation->ticket_type == 2 ? locales::$text[none] : $reservation->place_back) . '</td>
                    <td>' . ($reservation->ticket_number == '' ? locales::$text[none] : $reservation->ticket_number) . '</td>
                    <td>' . ($reservation->ticket_type == 0 ? locales::$text[ticket_oneway] : ($reservation->ticket_type == 2 ? locales::$text[ticket_opened_twoway] : locales::$text[ticket_twoway])) . '</td>
                    <td>' . ucwords($reservation->passenger_name) . '</td>
                    <td>' . $reservation->passenger_passpor . '</td>
                    <td>' . $reservation->price . ' BGN / ' . $currency->convert($reservation->price, 'eur') . ' EUR</td>
                    <td>' . ($reservation->discount_id == 0 ? locales::$text[none] : (locales::$current_locale == 'bg' ? $disc->name_bg : $disc->name_en)) . '</td>
                    <td>' . $city->city($point->find(array(id => $subline->from_point_id))->city_id, locales::$current_locale) . ' - ' . $city->city($point->find(array(id => $subline->to_point_id))->city_id, locales::$current_locale) . '</td>
                    <td>' . ($reservation->user_id == 0 ? Env::OnlineSysName : $usr->user) . '</td>
                </tr>';
        }

        $this->page_contents.='
                <tr>
                    <td colspan="7" align="right">' . locales::$text[report_sum] . ': ' . $sum . ' BGN / ' . $currency->convert($sum, 'eur') . ' EUR</td>
                    <td colspan="4">' . locales::$text[reports_from_date] . ' ' . date('d.m.Y', strtotime($from)) . ' ' . locales::$text[reports_to_date] . ' ' . date('d.m.Y', strtotime($to)) . '</td>
                </tr>
                </table>';
        if (!Session::access_is_allowed(date2date_report_access))
            $this->page_contents = '<h2 align="center" style="color:red;">' . locales::$text[no_permission] . '</h2>';
        return $this->show_page();
    }

    public function agent_report($user_id, $from, $to) {
        Session::user_auth();
        $this->template_name = 'reports';
        $this->page_contents = '';

        $reserv = new Reservation();
        //$reservations=$reserv->find(array(all=>1,where=>"user_id=$user_id AND ((`date` BETWEEN '$from' AND '$to') OR (date_back BETWEEN '$from' AND '$to'))"));
        $reservations = $reserv->find(array(all => 1, where => "user_id=$user_id AND created BETWEEN '$from 00:00:00' AND '$to 23:59:59'"));
        $discount = new Discount();
        $user = new User();
        $currency = new Currencies();
        $this->page_contents.='<table class="landscape_report">
                <tr>
                    <td colspan="11" style="border:none;">
                        <input type="button" value="' . locales::$text['print'] . '" onclick="printReport(this);" />
                        <a href="xls_export.php?type=' . __FUNCTION__ . '&' . $_SERVER['QUERY_STRING'] . '" target="_blank"><input type="button" value="' . locales::$text['xls_export'] . '" /></a>
                    </td>
                </td>
                <tr>
                    <th align="left">' . locales::$text[report_place] . '</th>
                    <th align="left">' . locales::$text[report_place_back] . '</th>
                    <th align="left">' . locales::$text[report_ticket] . '</th>
                    <th align="left">' . locales::$text[report_ticket_type] . '</th>
                    <th align="left">' . locales::$text[report_name] . '</th>
                    <th align="left">' . locales::$text[report_passport] . '</th>
                    <th align="left">' . locales::$text[report_price] . '</th>
                    <th align="left">' . locales::$text[report_discount] . '</th>
                    <th align="left">' . locales::$text[report_date] . '</th>
                    <th align="left">' . locales::$text[report_date_back] . '</th>
                    <th align="left">' . locales::$text[report_destination] . '</th>

                </tr>';
        $sum = 0;
        $subln = new Subline();
        $city = new City();
        $point = new Line_point();
        foreach ($reservations as $reservation) {
            $subline = $subln->find(array(id => $reservation->subline_id));
            $disc = $discount->find(array(id => $reservation->discount_id));
            $usr = $user->find(array(id => $reservation->user_id));
            $sum = $sum + $reservation->price;
            $this->page_contents.='
                <tr>
                    <td>' . $reservation->place . '</td>
                    <td>' . ($reservation->ticket_type == 0 || $reservation->ticket_type == 2 ? locales::$text[none] : $reservation->place_back) . '</td>
                    <td>' . ($reservation->ticket_number == '' ? locales::$text[none] : $reservation->ticket_number) . '</td>
                    <td>' . ($reservation->ticket_type == 0 ? locales::$text[ticket_oneway] : ($reservation->ticket_type == 2 ? locales::$text[ticket_opened_twoway] : locales::$text[ticket_twoway])) . '</td>
                    <td>' . ucwords($reservation->passenger_name) . '</td>
                    <td>' . $reservation->passenger_passpor . '</td>
                    <td>' . $reservation->price . ' BGN / ' . $currency->convert($reservation->price, 'eur') . ' EUR</td>
                    <td>' . ($reservation->discount_id == 0 ? locales::$text[none] : (locales::$current_locale == 'bg' ? $disc->name_bg : $disc->name_en)) . '</td>
                    <td>' . date('d.m.Y', strtotime($reservation->date)) . '</td>
                    <td>' . ($reservation->ticket_type == 0 ? locales::$text[none] : date('d.m.Y', strtotime($reservation->date_back))) . '</td>
                    <td>' . $city->city($point->find(array(id => $subline->from_point_id))->city_id, locales::$current_locale) . ' - ' . $city->city($point->find(array(id => $subline->to_point_id))->city_id, locales::$current_locale) . '</td>
                </tr>';
        }
        $this->page_contents.='
                <tr>
                    <td colspan="7" align="right">' . locales::$text[report_sum] . ': ' . $sum . ' BGN / ' . $currency->convert($sum, 'eur') . ' EUR</td>
                    <td colspan="2">' . locales::$text[reports_from_date] . ' ' . date('d.m.Y', strtotime($from)) . ' ' . locales::$text[reports_to_date] . ' ' . date('d.m.Y', strtotime($to)) . '</td>
                    <td colspan="2">' . locales::$text[report_reserved_by] . ': ' . ($reservation->user_id == 0 ? Env::OnlineSysName : $usr->user) . '</td>
                </tr>
                </table>';

        if (!Session::access_is_allowed(agent_report_access))
            $this->page_contents = '<h2 align="center" style="color:red;">' . locales::$text[no_permission] . '</h2>';
        return $this->show_page();
    }

    public function invoices_report($from, $to) {
        Session::user_auth();
        $this->template_name = 'reports';
        $this->page_contents = '';

        $invoice = new Invoice();
        $invoices = $invoice->find(array(all => 1, where => "created_on between '$from 00:00:00' AND '$to 23:59:59'"));
        $invoice_element = new Invoice_element();

        $reserv = new Reservation();
        $discount = new Discount();
        $user = new User();
        $currency = new Currencies();
        $this->page_contents.='<table class="landscape_report">
                <tr>
                    <td colspan="11" style="border:none;">
                        <input type="button" value="' . locales::$text['print'] . '" onclick="printReport(this);" />
                        <a href="xls_export.php?type=' . __FUNCTION__ . '&' . $_SERVER['QUERY_STRING'] . '" target="_blank"><input type="button" value="' . locales::$text['xls_export'] . '" /></a>
                    </td>
                </td>
                <tr>
                    <th align="left">Invoice</th>
                    <th align="left">' . locales::$text[report_place] . '</th>
                    <th align="left">' . locales::$text[report_place_back] . '</th>
                    <th align="left">' . locales::$text[report_date] . '</th>
                    <th align="left">' . locales::$text[report_date_back] . '</th>
                    <th align="left">' . locales::$text[report_ticket] . '</th>
                    <th align="left">' . locales::$text[report_ticket_type] . '</th>
                    <th align="left">' . locales::$text[report_name] . '</th>
                    <th align="left">' . locales::$text[report_price] . '</th>
                    <th align="left">' . locales::$text[report_discount] . '</th>
                    <th align="left">' . locales::$text[report_destination] . '</th>
                </tr>';
        $sum = 0;
        $subln = new Subline();
        $point = new Line_point();
        $city = new City();
        foreach ($invoices as $one_invoice) {

            $invoice_elements = $invoice_element->find(array(all => 1, where => "invoice_id=$one_invoice->id"));

            foreach ($invoice_elements as $one_element) {
                $reservation = $reserv->find(array(id => $one_element->reservation_id));

                $subline = $subln->find(array(id => $reservation->subline_id));
                $disc = $discount->find(array(id => $reservation->discount_id));
                $usr = $user->find(array(id => $reservation->user_id));
                $sum = $sum + $reservation->price;
                $this->page_contents.='
                    <tr>
                        <td>' . $one_element->invoice_id . '</td>
                        <td>' . $reservation->place . '</td>
                        <td>' . ($reservation->ticket_type == 0 || $reservation->ticket_type == 2 ? locales::$text[none] : $reservation->place_back) . '</td>
                        <td>' . date('d.m.Y', strtotime($reservation->date)) . '</td>
                        <td>' . ($reservation->ticket_type == 0 ? locales::$text[none] : date('d.m.Y', strtotime($reservation->date_back))) . '</td>
                        <td>' . ($reservation->ticket_number == '' ? locales::$text[none] : $reservation->ticket_number) . '</td>
                        <td>' . ($reservation->ticket_type == 0 ? locales::$text[ticket_oneway] : ($reservation->ticket_type == 2 ? locales::$text[ticket_opened_twoway] : locales::$text[ticket_twoway])) . '</td>
                        <td>' . ucwords($reservation->passenger_name) . '</td>
                        <td>' . $reservation->price . ' BGN / ' . $currency->convert($reservation->price, 'eur') . ' EUR</td>
                        <td>' . ($reservation->discount_id == 0 ? locales::$text[none] : (locales::$current_locale == 'bg' ? $disc->name_bg : $disc->name_en)) . '</td>
                        <td>' . $city->city($point->find(array(id => $subline->from_point_id))->city_id, locales::$current_locale) . ' - ' . $city->city($point->find(array(id => $subline->to_point_id))->city_id, locales::$current_locale) . '</td>
                    </tr>';
            }
        }

        $this->page_contents.='
                <tr>
                    <td colspan="9" align="right">' . locales::$text[report_sum] . ': ' . $sum . ' BGN / ' . $currency->convert($sum, 'eur') . ' EUR</td>
                    <td colspan="2"></td>
                </tr>
                </table>';
        if (!Session::access_is_allowed(all_sales_access))
            $this->page_contents = '<h2 align="center" style="color:red;">' . locales::$text[no_permission] . '</h2>';
        return $this->show_page();
    }

    public function free_places_report($from, $to, $subline_id) {
        Session::user_auth();
        set_time_limit(0);
        $this->template_name = 'agents';
        if (Session::access_is_allowed(free_places_access)) {
            $sublines = new Sublines();
            $this->page_contents = '
                <div class="clearfix" style="width:100%;">
                        <form action="' . $_SERVER[PHP_SELF] . '">
                            <div class="reportsWidth cleafix">
                                <div class="textInput clearfix">
                                   <label> ' . locales::$text[tickets_destination] . '</label>
                                       <select class="sublineSelect" name="subline">
                                        ' . $sublines->options_for_select($subline_id, locales::$current_locale) . '
                                    </select>
                                 </div>
                                 <div class="textInput clearfix">
                                     <label >' . locales::$text[reports_from_date] . '  </label>
                                         <input type="text" class="datepicker" readonly="true" id="subline_date_from" name="date_from" value="' . (strlen($from) > 0 ? $from : date('Y-m-d')) . '" onclick="NewCssCal(\'subline_date_from\',\'yyyymmdd\');" readonly="readonly"/>
                                 </div>
                                 <div class="textInput clearfix">
                                     <label>' . locales::$text[reports_to_date] . '</label>
                                     <input type="text" class="datepicker" readonly="true" id="subline_date_to" name="date_to" value="' . (strlen($to) > 0 ? $to : date('Y-m-d')) . '" onclick="NewCssCal(\'subline_date_to\',\'yyyymmdd\');" readonly="readonly"/>
                                </div>
                                <div class="textInput clearfix">
                                     <input class="invoice float-right"  type="submit" value="' . locales::$text[reports_view] . '" />
                                </div>
                            </div>
                        </form>
                      </div>';

            if (strlen($from) > 0 && strlen($to) > 0 && strlen($subline_id) > 0) {
                $subline = new Subline();
                $tdays = new Travel_days();
                $active_places = new Active_places();
                $point = new Line_point();
                $city = new City();
                $sublines = $subline->find(array(id => $subline_id));
                $point_from = $point->find(array(id => $sublines->from_point_id));
                $point_to = $point->find(array(id => $sublines->to_point_id));
                $direction = (($point_from->order > $point_to->order) ? '1' : '0');
                $day = 60 * 60 * 24;
                $date = strtotime($from);
                $datecells = '';

                while ($date <= strtotime($to)) {
                    $places = '';
                    for ($i = 1, $br = 0; $i <= 53; $i++) {
                        if ($tdays->is_travelday($sublines, date('Y-m-d', $date), $direction)) {
                            $reserved_places = $sublines->get_all_reserved_places(date('Y-m-d', $date));
                            if (/* !$sublines->is_reserved($i, date('Y-m-d',$date)) */!isset($reserved_places[$i]) && !$active_places->is_inactive($sublines->line_id, $i, date('Y-m-d', $date))) {
                                $places.='<div class="notreserved" style="margin-right:5px;">' . $i . '</div>';
                            }
                        }
                    }
//                    $datecells.='<tr>
//                        <td colspan="2" >' . $places . '</td>
//                        </tr>';
//                    $date = $date + $day;
                    $datecells.='<div class="free-date-and-places divBox clearfix">
                        <div class="free-places-date clearfix">'.date('d.m.Y',$date).'</div>
                        <div class="free-places clearfix">'.$places.'</div>
                        </div>';
                    $date=$date+$day;
                }
//                $this->page_contents.='
//                    
//                    <table class="tableFreeSpaces">
//                        <tr>
//                        <td> ' . date('d.m.Y', $date) . ' </td>
//                        <td>' . $city->city($point_from->city_id, locales::$current_locale) . ' - ' . $city->city($point_to->city_id, locales::$current_locale) . '</td>
//                        </tr>
//                        <tr>
//                        <td>
//                        <div style="display:block;margin-left:auto;margin-right:">
//                            ' . $datecells . '
//                        </div>
//                        </td>
//                            </tr>
//                    </table>';

                $this->page_contents.='

                    <div class="free-places-wrapper clearfix" >
                        <div class="clearfix free-spaces-line">' . $city->city($point_from->city_id, locales::$current_locale) . ' - ' . $city->city($point_to->city_id, locales::$current_locale) . '</div>
                            ' . $datecells . '
                    </div>';
            }
        } else {
            $this->page_contents = '<h2 align="center" style="color:red;">' . locales::$text[no_permission] . '</h2>';
        }

        return $this->show_page();
    }

    public function choose_report() {
        Session::user_auth();
        $subline = new Sublines();
        $usr = new User();
        $users = $usr->find(array(all => 1));
        $optuser = '';
        foreach ($users as $user) {
            $optuser.='<option value="' . $user->id . '">' . $user->user . '</option>';
        }
        $this->page_contents = '
                <script type="text/javascript">
                    function show_report(url,width) {
                        window.open( url, "myReport", "height = 600, width = "+width+", scrollbars = 1, menubar = 1" )
                    }
                </script>
            <div class="reports-wrapper">
                <div class="reports-couples-divs clearfix">
                        <div class="reportsWidth clearfix">
                           <h2>' . locales::$text[reports_date_subline] . '</h2>
                           <form action="./date_destination_report.php" onsubmit="show_report(\'./date_destination_report.php?\'+Form.serialize(this),800); return false;">
                               <div  class="textInput clearfix">
                                   <label>' . locales::$text[tickets_destination] . '</label>
                                   <select name="subline">
                                   ' . $subline->options_for_select(null, locales::$current_locale) . '
                                   </select>
                               </div>
                               <div class="textInput clearfix">
                                   <label for="date_subline_date">  ' . locales::$text[tickets_date] . '</label>
                                   <input  class="datepicker" readonly="true"  type="text" id="date_subline_date" name="date" value="' . date('Y-m-d') . '" onclick="NewCssCal(\'date_subline_date\',\'yyyymmdd\');" readonly="readonly"/>
                               </div>
                               <div class="textInput clearfix">
                                   <input class="invoice float-right" type="submit" value="' . locales::$text[reports_view] . '" />
                               </div>
                           </form>
                        </div>
                    </div>
                  
                  
                <div class="reports-couples-divs clearfix">
                    
                    <div class="reportsWidth  clearfix">
                    <h2>' . locales::$text[reports_date_to_date] . '</h2>
                    <form action="./date_to_date_report.php" onsubmit="show_report(\'./date_to_date_report.php?\'+Form.serialize(this),1122); return false;">
                        <div class="textInput clearfix">
                           <label for="date_to_date_from"> ' . locales::$text[reports_from_date] . '</label>
                           <input type="text"  class="datepicker" readonly="true"  id="date_to_date_from" name="date_from" value="' . date('Y-m-d') . '" onclick="NewCssCal(\'date_to_date_from\',\'yyyymmdd\');" readonly="readonly"/>
                        </div>
                        <div class="textInput clearfix" >
                           <label for="date_to_date_to"> ' . locales::$text[reports_to_date] . '</label>
                           <input type="text"  class="datepicker" readonly="true"  id="date_to_date_to" name="date_to" value="' . date('Y-m-d') . '" onclick="NewCssCal(\'date_to_date_to\',\'yyyymmdd\');" readonly="readonly"/>
                        </div>   
                        <div class="textInput clearfix">
                            <input class="invoice float-right" type="submit" value="' . locales::$text[reports_view] . '" />
                        </div>
                    </form>
                    </div>
                </div>    
                
                
               <div class="reports-couples-divs  clearfix">
                    <hr />
                    <div class="reportsWidth clearfix">
                    <h2>Invoices</h2>
                    <form action="./invoice_report.php" onsubmit="show_report(\'./invoice_report.php?\'+Form.serialize(this),1122); return false;" >
                        <div class="textInput clearfix">
                            <label for="invoice_from">' . locales::$text[reports_from_date] . '</label>
                             <input  class="datepicker" readonly="true"  type="text" id="invoice_from" name="date_from" value="' . date('Y-m-d') . '" onclick="NewCssCal(\'invoice_from\',\'yyyymmdd\');" readonly="readonly"/>
                        </div>
                        <div class="textInput clearfix"> 
                            <label for="invoice_to"> ' . locales::$text[reports_to_date] . '</label>
                            <input  class="datepicker" readonly="true"  type="text" id="invoice_to" name="date_to" value="' . date('Y-m-d') . '" onclick="NewCssCal(\'invoice_to\',\'yyyymmdd\');" readonly="readonly"/>
                        </div>
                        <div class="textInput clearfix">
                            <input class="invoice float-right" type="submit" value="' . locales::$text[reports_view] . '" />
                        </div>       
                        </div>
                    </form>
                </div>
                
                ' . (Session::access_is_allowed(agent_report_access) ? '
                    

                    
                    <div class="reports-couples-divs clearfix">
                        <div class="reportsWidth clearfix">
                        <hr />
                        <h2>' . locales::$text[reports_agents_date_to_date] . '</h2>
                        <form action="./agent_report.php" onsubmit="show_report(\'./agent_report.php?\'+Form.serialize(this),1122); return false;">
                                <div  class="textInput clearfix">
                                    <label>' . locales::$text[reports_agent] . '</label>
                                    <select name="agent">
                                        <option value="0">' . Env::OnlineSysName . '</option>' . $optuser . '
                                    </select>
                                 </div>
                                 <div class="textInput clearfix">
                                    <label for="agent_date_from">' . locales::$text[reports_from_date] . '</label>
                                    <input  class="datepicker" readonly="true"  type="text" id="agent_date_from" name="date_from" value="' . date('Y-m-d') . '" onclick="NewCssCal(\'agent_date_from\',\'yyyymmdd\');" readonly="readonly"/>
                                 </div>
                                 <div class="textInput clearfix">
                                    <label for="agent_date_to">' . locales::$text[reports_to_date] . '</label>
                                    <input  class="datepicker" readonly="true"  type="text" id="agent_date_to" name="date_to" value="' . date('Y-m-d') . '" onclick="NewCssCal(\'agent_date_to\',\'yyyymmdd\');" readonly="readonly"/>
                                 </div>
                                 <div class="textInput clearfix">
                                   <input class="invoice float-right" type="submit" value="' . locales::$text[reports_view] . '" />
                                 </div>
                            </div>
                            </form>
                         </div>
                        ' : '') . '
                    

                
                    <div class="reports-couples-divs clearfix">
                          
                          <div class="reportsWidth clearfix">
                                <hr />
                                <h2>' . locales::$text[reports_sublines_date_to_date] . '</h2>
                                <form action="./subline_to_date_report.php" onsubmit="show_report(\'./subline_to_date_report.php?\'+Form.serialize(this),1122); return false;">
                                    <div  class="textInput clearfix">
                                        <label>' . locales::$text[tickets_destination] . '</label>
                                            <select name="subline">' . $subline->options_for_select(null, locales::$current_locale) . '</select>
                                        </div>
                                        <div class="textInput clearfix">
                                            <label for="subline_date_from">' . locales::$text[reports_from_date] . '</label>
                                            <input  class="datepicker" readonly="true"  type="text" id="subline_date_from" name="date_from" value="' . date('Y-m-d') . '" onclick="NewCssCal(\'subline_date_from\',\'yyyymmdd\');" readonly="readonly"/>
                                        </div>
                                        <div class="textInput clearfix">                        
                                            <label for="subline_date_to">' . locales::$text[reports_to_date] . '</label>
                                            <input  class="datepicker" readonly="true"  type="text" id="subline_date_to" name="date_to" value="' . date('Y-m-d') . '" onclick="NewCssCal(\'subline_date_to\',\'yyyymmdd\');" readonly="readonly"/>
                                        </div>
                                        <div class="textInput clearfix">
                                            <input class="invoice float-right" type="submit" value="' . locales::$text[reports_view] . '" />
                                        </div>    
                            </div>
                      </div>      
                      <div class="reports-couples-divs clearfix">
                      <hr />
                      <div style="text-align:center" class="reportsWidth clearfix ">
                          <h2><a href="./free_places.php">' . locales::$text[reports_free_places] . '</a></h2>
                      </div>
                        </form>
                     </div>
              </div>          
               ';


        return $this->show_page();
    }

}

?>
