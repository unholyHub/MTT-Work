<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Lines
 *
 * @author root
 */
include_once 'gui.php';
include_once './models/Line.php';
include_once './controllers/Sublines.php';
include_once './controllers/Travel_days.php';

class Lines extends gui{
    public $id;
    public $line_name;
    public $from_city_id;
    public $to_city_id;
    public $from_date;
    public $to_date;

    function __construct() {
    }

    function Lines($id, $line_name, $from_city_id, $to_city_id, $from_date, $to_date) {
        $this->id = $id;
        $this->line_name = $line_name;
        $this->from_city_id = $from_city_id;
        $this->to_city_id = $to_city_id;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function create(){
        $line=new Line();
        return $line->insert($this);
    }

    public function update_name(){
        $line=new Line();
        $line->update($this->id, $this->line_name, $this->from_city_id, $this->to_city_id, $this->from_date, $this->to_date);
    }

    public function show_all(){
        Session::user_auth();//is user logged in
        $lineModel=new Line();
        $lines=$lineModel->find(array(all=>''));
        $sublines=new Sublines();
        $tdays=new Travel_days();
        $this->page_contents='<table align="center">
               <tr>
                    <td align="right" colspan="2"><a href="./addline.php">Добави линия</a></td>
               </tr>';
        foreach($lines as $line){
            $this->page_contents.='
                    <tr>
                        <td align="left" class="forms" >
                            <b>'.$line->line_name.'</b>
                            <div style="float:right;">
                            <a href="./edit_line_points.php?line='.$line->id.'">Редактирай</a> |
                            <a href="./deleteline.php?line='.$line->id.'" onclick="return confirm(\'Сигурни ли сте че искате да изтриете тази линия?\');">Изтрий</a>
                            </div>
                        </td>
                     </tr>
                     <tr>
                     <td align="left">'.$tdays->travel_days_form($line->id, false,false).'</td>
                    </tr>
                    <tr>
                        <td>'.$sublines->price_table_new($line->id, false,false).'</td>
                    </tr>
                    <tr>
                     <td align="left">'.$tdays->travel_days_form($line->id, true,false).'</td>
                    </tr>
                    <tr>
                        <td>'.$sublines->price_table_new($line->id, true, false).'</td>
                    </tr>';
        }
        $this->page_contents.='</table>';
        
        return $this->show_page();
    }
    
    public function search_serialized(){
        $citiesHandler = new City();
        $fromCity = trim($_GET['from']);
        $toCity = trim($_GET['to']);
        $body = null;
        if (!empty($toCity) && !empty($fromCity)) {
            $cityFrom = current($citiesHandler->find(array('all' => true, 'where' => "city like '$fromCity'", 'limit' => 1)));
            $cityTo = current($citiesHandler->find(array('all' => true, 'where' => "city like '$toCity'", 'limit' => 1)));
            if(!empty($cityFrom) && !empty($cityTo)){
                $line_point = new Line_point();
                $sublines = $line_point->point_to_point_sublines($cityFrom->ID_city, $cityTo->ID_city);
                foreach($sublines as $subline){
                    if($subline->price_oneway > 0 || $subline->price_oneway > 0){
                        $body []= $subline->view_with_json();
                    }
                }
            }
        }
        
        $this->page_contents = serialize($body);
        $this->template_name = 'search_frame';
        return $this->show_page();
    }

    public function search_form($from,$to,$date){
        Session::clear_session();
        $line_point=new Line_point();
        if($to&&$from&&$date) $sublines=$line_point->point_to_point_sublines($from, $to);

        $promotions=new Promotions();
        $discounts=new Discounts();

        $city=new City();
        $this->page_contents='
            <table align="center" width="1000px">
            <tr><td valign="top">

            <form action="'.$_SERVER[PHP_SELF].'">
              <table>
                <tr>
                    <td>'.locales::$text['from'].'</td>
                    <td>
                        <select name="from">
                            '.$city->options_for_cities(locales::$current_locale, $from).'
                        </select>
                    </td>
                    <td>'.locales::$text['to'].'</td>
                    <td>
                        <select name="to">
                            '.$city->options_for_cities(locales::$current_locale, $to).'
                        </select>
                    </td>
                    <td>'.locales::$text[tickets_date].'</td>
                    <td>
                        <input type="text" id="date_search" name="date" value="'.$date.'" onclick="NewCssCal(\'date_search\',\'yyyymmdd\');" readonly="readonly" />
                    </td>
                    <td><input type="submit" value="'.locales::$text[search].'" /></td>
                </tr>
              </table>
            </form>
            '.($to&&$from&&$date?$this->search_results($sublines,$date):'').'

            </td>
            <td valign="top">
                <img src="./images/bus1.jpg" width="394"/><br />
                '.$promotions->active_promotions().'
                '.$discounts->active_discounts().'
            </td>
            </tr>
            </table>';

        return $this->show_page();
    }

    public function line_is_active(Sublines $subline, $date){
        $ln = new Line();
        $line = $ln->find(array(id=>$subline->line_id));
        if($line->from_date&&$line->to_date){
            if(strtotime($line->from_date)<=strtotime($date)&&strtotime($line->to_date)>=strtotime($date)){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    public function search_results(array $sublines,$date){
        $html='';
        if(sizeof($sublines)>0){
            foreach($sublines as $subline){
                if($this->line_is_active($subline, $date)){
                    $html.=$subline->view_by_day();
                }
            }
        }
        return $html;
    }
    public function search_results_agents(array $sublines,$date){
        $html='';
        if(sizeof($sublines)>0&&strlen($date)>0){
            foreach($sublines as $subline){
                if($this->line_is_active($subline, $date)){
                    $html.=$subline->view_subline_agent($date);
                }
            }
        }
        return $html;
    }

}
?>
