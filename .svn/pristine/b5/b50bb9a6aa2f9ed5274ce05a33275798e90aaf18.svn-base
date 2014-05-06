<?php
/* 
 * 
 */
include_once 'gui.php';
include_once './controllers/Lines.php';
include_once './models/Line_point.php';
include_once './models/City.php';

class Line_points extends gui{
    public $id;
    public $line_id;
    public $city_id;
    public $stopover;
    public $stopover_back;
    public $arrival_time;
    public $bus_station_bg;
    public $bus_station_en;
    public $arrival_time_back;
    public $bus_station_back_bg;
    public $bus_station_back_en;
    public $order;

    function __construct() {
    }

    function Line_points($id, $line_id, $city_id, $stopover,$stopover_back,$arrival_time, $bus_station_bg, $bus_station_en, $arrival_time_back, $bus_station_back_bg, $bus_station_back_en ,$order) {
        $this->id = $id;
        $this->line_id = $line_id;
        $this->city_id = $city_id;
        $this->stopover = $stopover;
        $this->stopover_back = $stopover_back;
        $this->arrival_time = $arrival_time;
        $this->bus_station_bg = $bus_station_bg;
        $this->bus_station_en = $bus_station_en;
        $this->arrival_time_back = $arrival_time_back;
        $this->bus_station_back_bg = $bus_station_back_bg;
        $this->bus_station_back_en = $bus_station_back_en;
        $this->order = $order;
    }

    public function save_all(array $elements){
        $lines=new Lines();
        $city=new City();
        $lines->line_name=$city->city($elements[point][0],'bg').' - '.$city->city($elements[point][sizeof($elements[point])-1],'bg');
        $lines->from_city_id=$elements[point][0];
        $lines->to_city_id=$elements[point][sizeof($elements[point])-1];
        if(strlen($elements[from_date])>0 && strlen($elements[to_date])>0){
            $lines->from_date = $elements[from_date];
            $lines->to_date = $elements[to_date];
        }else{
            $lines->from_date = null;
            $lines->to_date = null;
        }
        $this->line_id=$lines->create();
        
        
        foreach($elements[point] as $key=>$val){
            $this->city_id=$val;
            $this->arrival_time=$elements[arrival_time][$key];
            $this->bus_station_bg=$elements[bus_station_bg][$key];
            $this->bus_station_en=$elements[bus_station_en][$key];
            $this->arrival_time_back=$elements[arrival_time_back][$key];
            $this->bus_station_back_bg=$elements[bus_station_back_bg][$key];
            $this->bus_station_back_en=$elements[bus_station_back_en][$key];
            $this->stopover=$elements[stopover][$key];
            $this->stopover_back=$elements[stopover_back][$key];
            $this->order=$key;
            
            $this->save();
        }

        header('location: ./set_travel_days.php?line_id='.$this->line_id);
    }

    public function update_all($elements){
        $lines=new Lines();
        $city=new City();
        $lines->id=$elements[line_id];
        $lines->line_name=$city->city($elements[point][0],'bg').' - '.$city->city($elements[point][sizeof($elements[point])-1],'bg');
        $lines->from_city_id=$elements[point][0];
        $lines->to_city_id=$elements[point][sizeof($elements[point])-1];
        if(strlen($elements[from_date])>0 && strlen($elements[to_date])>0){
            $lines->from_date = $elements[from_date];
            $lines->to_date = $elements[to_date];
        }else{
            $lines->from_date = null;
            $lines->to_date = null;
        }
        $lines->update_name();
        
        foreach($elements[id] as $key=>$val){
            $this->id=$val;
            $this->city_id=$elements[point][$key];
            $this->arrival_time=$elements[arrival_time][$key];
            $this->bus_station_bg=$elements[bus_station_bg][$key];
            $this->bus_station_en=$elements[bus_station_en][$key];
            $this->arrival_time_back=$elements[arrival_time_back][$key];
            $this->bus_station_back_bg=$elements[bus_station_back_bg][$key];
            $this->bus_station_back_en=$elements[bus_station_back_en][$key];
            $this->stopover=$elements[stopover][$key];
            $this->stopover_back=$elements[stopover_back][$key];

            $this->update();
        }

        header('location: ./alllines.php');
    }

    public function save(){
        $line_point=new Line_point();
        $line_point->insert($this);
    }

    public function update(){
        $line_point=new Line_point();
        $line_point->update($this);
    }

    public function create_line_form(){
        Session::user_auth();//is user logged in
        $city=new City();
        $this->page_contents='
                <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    <table class="forms" align="center">
                    <tr><td colspan="2">
                        <table id="point_container" align="center" width="100%">
                            <tr>
                                <td colspan="3" align="center">
                                    От дата: <input type="text" id="points_from_date" name="points[from_date]" value="" onclick="NewCssCal(\'points_from_date\',\'yyyymmdd\');" readonly="readonly" />
                                    До дата: <input type="text" id="points_to_date" name="points[to_date]" value="" onclick="NewCssCal(\'points_to_date\',\'yyyymmdd\');" readonly="readonly" />
                                </td>
                            </tr>
                            <tr>
                                <th>Спирка</th>
                                <th>Отиване [Автогара, Време, Престой]</th>
                                <th>Връщане [Автогара, Време, Престой]</th>
                            </tr>
                            <tr><td>
                                #1: <select name="points[point][0]">
                                    '.$city->options_for_cities('bg').'
                                </select>
                             </td><td>
                                BG: <input name="points[bus_station_bg][0]" value="" />
                                EN: <input name="points[bus_station_en][0]" value="" /><input name="points[arrival_time][0]" value="00:00" size="5" /><input name="points[stopover][0]" value="0" size="5" />
                             </td>
                             <td>
                                BG: <input name="points[bus_station_back_bg][0]" value="" />
                                EN: <input name="points[bus_station_back_en][0]" value="" /><input name="points[arrival_time_back][0]" value="00:00" size="5" /><input name="points[stopover_back][0]" value="0" size="5" />
                             </td></tr>
                        </table>
                    </td></tr>
                    <tr><td>
                        <a href="javascript:add_point(&quot'.$city->options_for_cities('bg').'&quot);" >Добави Точка</a>
                    </td>
                    <td align="right">
                        <input name="submit_points" type="submit" value="Следваща стъпка" />
                    </td></tr>
                    </table>
                </form>';

        return $this->show_page();
    }

    public function edit_line_form($line_id){
        Session::user_auth();//is user logged in
        $line_points=new Line_point;
        $lines=$line_points->find(array(all=>'',where=>'line_id='.$line_id, order=>'`order` ASC'));
        $city=new City();
        $ln=new Line();
        $main_line=$ln->find(array(id=>$line_id));

        $elements='';
        foreach($lines as $key => $value){
            $elements.='<tr><td>
                            <input type="hidden" name="points[id]['.$key.']" value="'.$value->id.'" />
                            #'.($key+1).': <select name="points[point]['.$key.']">
                              '.$city->options_for_cities('bg',$value->city_id).'
                             </select>
                             </td><td>
                                BG: <input name="points[bus_station_bg]['.$key.']" value="'.$value->bus_station_bg.'" />
                                EN: <input name="points[bus_station_en]['.$key.']" value="'.$value->bus_station_en.'" />
                                <input name="points[arrival_time]['.$key.']" value="'.$value->arrival_time.'" size="5" />
                                <input name="points[stopover]['.$key.']" value="'.$value->stopover.'" size="5" />
                             </td>
                             <td>
                                BG: <input name="points[bus_station_back_bg]['.$key.']" value="'.$value->bus_station_back_bg.'" />
                                EN: <input name="points[bus_station_back_en]['.$key.']" value="'.$value->bus_station_back_en.'" />
                                <input name="points[arrival_time_back]['.$key.']" value="'.$value->arrival_time_back.'" size="5" />
                                <input name="points[stopover_back]['.$key.']" value="'.$value->stopover_back.'" size="5" />
                             </td></tr>';
        }

        $this->page_contents='
                <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    <input type="hidden" name="points[line_id]" value="'.$line_id.'" />
                    <table class="forms" align="center">
                    <tr><td>
                        <table id="point_container" align="center">
                            <tr>
                                <td colspan="3" align="center">
                                    От дата: <input type="text" id="points_from_date" name="points[from_date]" value="'.$main_line->from_date.'" onclick="NewCssCal(\'points_from_date\',\'yyyymmdd\');" readonly="readonly" />
                                    До дата: <input type="text" id="points_to_date" name="points[to_date]" value="'.$main_line->to_date.'" onclick="NewCssCal(\'points_to_date\',\'yyyymmdd\');" readonly="readonly" />
                                </td>
                            </tr>
                            <tr>
                                <th>Спирка</th>
                                <th>Отиване [Автогара, Време, Престой]</th>
                                <th>Връщане [Автогара, Време, Престой]</th>
                            </tr>
                            '.$elements.'
                        </table>
                    </td></tr>
                    <td align="right" colspan="4">
                        <input name="update_points" type="submit" value="Обнови" />
                    </td></tr>
                    </table>
                </form>';

        return $this->show_page();
    }
}
?>
