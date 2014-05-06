<?php

/*
 * 
 */
include_once 'gui.php';
include_once './controllers/Lines.php';
include_once './models/Line_point.php';
include_once './models/City.php';

class Line_points extends gui {

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

    function Line_points($id, $line_id, $city_id, $stopover, $stopover_back, $arrival_time, $bus_station_bg, $bus_station_en, $arrival_time_back, $bus_station_back_bg, $bus_station_back_en, $order) {
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

    public function save_all(array $elements) {
        $lines = new Lines();
        $city = new City();
        $lines->line_name = $city->city($elements[point][0], 'bg') . ' - ' . $city->city($elements[point][sizeof($elements[point]) - 1], 'bg');
        $lines->from_city_id = $elements[point][0];
        $lines->to_city_id = $elements[point][sizeof($elements[point]) - 1];
        if (strlen($elements[from_date]) > 0 && strlen($elements[to_date]) > 0) {
            $lines->from_date = $elements[from_date];
            $lines->to_date = $elements[to_date];
        } else {
            $lines->from_date = null;
            $lines->to_date = null;
        }
        $this->line_id = $lines->create();


        foreach ($elements[point] as $key => $val) {
            $this->city_id = $val;
            $this->arrival_time = $elements[arrival_time][$key];
            $this->bus_station_bg = $elements[bus_station_bg][$key];
            $this->bus_station_en = $elements[bus_station_en][$key];
            $this->arrival_time_back = $elements[arrival_time_back][$key];
            $this->bus_station_back_bg = $elements[bus_station_back_bg][$key];
            $this->bus_station_back_en = $elements[bus_station_back_en][$key];
            $this->stopover = $elements[stopover][$key];
            $this->stopover_back = $elements[stopover_back][$key];
            $this->order = $key;

            $this->save();
        }

        header('location: ./set_travel_days.php?line_id=' . $this->line_id);
    }

    public function update_all($elements) {
        $lines = new Lines();
        $city = new City();
        $lines->id = $elements[line_id];
        $lines->line_name = $city->city($elements[point][0], 'bg') . ' - ' . $city->city($elements[point][sizeof($elements[point]) - 1], 'bg');
        $lines->from_city_id = $elements[point][0];
        $lines->to_city_id = $elements[point][sizeof($elements[point]) - 1];
        if (strlen($elements[from_date]) > 0 && strlen($elements[to_date]) > 0) {
            $lines->from_date = $elements[from_date];
            $lines->to_date = $elements[to_date];
        } else {
            $lines->from_date = null;
            $lines->to_date = null;
        }
        $lines->update_name();

        foreach ($elements[id] as $key => $val) {
            $this->id = $val;
            $this->city_id = $elements[point][$key];
            $this->arrival_time = $elements[arrival_time][$key];
            $this->bus_station_bg = $elements[bus_station_bg][$key];
            $this->bus_station_en = $elements[bus_station_en][$key];
            $this->arrival_time_back = $elements[arrival_time_back][$key];
            $this->bus_station_back_bg = $elements[bus_station_back_bg][$key];
            $this->bus_station_back_en = $elements[bus_station_back_en][$key];
            $this->stopover = $elements[stopover][$key];
            $this->stopover_back = $elements[stopover_back][$key];

            $this->update();
        }

        header('location: ./alllines.php');
    }

    public function save() {
        $line_point = new Line_point();
        $line_point->insert($this);
    }

    public function update() {
        $line_point = new Line_point();
        $line_point->update($this);
    }

    public function create_line_form() {
        Session::user_auth(); //is user logged in
        $city = new City();
        $this->page_contents = '
                <form action="' . $_SERVER[PHP_SELF] . '" method="POST">
                    <div id="point_container"   class="sectionDiv clearfix groupDiv"> 
                    <div class="clearfix" >
                      <div class="blockDivFromToDate " >
                        <div class="dateInputRight">
                         <ul class="daysList">
                            <li class="daysListNumber">
                                 <input id="demo_box_1" class="css-checkbox" type="checkbox" value="ON" onclick="enable_date_to_date(this);" />
                                <label for="demo_box_1" name="demo_lbl_1" class="css-label" >Отключи</label>
                            </li>
                         </ul>
                        </div>
                            <div class="dateInputRight"> 
                                <label  for="data_to_date">
                                От дата: </label>
                                <input  class="datepicker unselectable" readonly="true"  type="text" id="points_from_date" name="points[from_date]" value="" onclick="" readonly="readonly" disabled="disabled"/>
                            </div>    
                            <div class="dateInputRight">    
                                <label for="points_to_date">До дата: </label>
                                <input class="datepicker unselectable"  readonly="true" type="text" id="points_to_date" name="points[to_date]" value=""  readonly="readonly" disabled="disabled"/>
                            </div>
                            <div>
                                <a id="addPoint" href="javascript:add_point(&quot' . $city->options_for_cities('bg') . '&quot);" ><input class="buttonStyle" type="button" value="Добави Точка"></a>
                                <input name="submit_points" type="submit" class="buttonStyle" value="Следваща стъпка" />
                            </div>
                        </div>
                       
                     </div>   
                     <div class="clearfix divBox" style="1px solid black"> 
                            <div class="colDiv blockDivStopSelect">
                                <span class="GoAndBackStyle">Спирка #1</span>: 
                                <select  name="points[point][0]">
                                    ' . $city->options_for_cities('bg') . '
                                </select>
                            </div>
                            <div class="colDiv labelText blockDivGoAndBack">
                                <div class="GoAndBackStyle">Отиване</div>
                                <div>
                                    <label> Име на спирка:</label> <input  class="inputAddLineSize" name="points[bus_station_bg][0]" value="" />
                                    <label class="blockLabel">Bus station name:</label> <input class="inputAddLineSize" name="points[bus_station_en][0]" value="" />
                                </div>
                                <div>
                                    <label>Час</label><input name="points[arrival_time][0]" value="00:00"  class="time-lenght" />
                                    <label>Престой</label><input name="points[stopover][0]" value="0" class="stopover-lenght"/>
                                </div>
                            </div>
                            <div class="colDiv clearfix labelText blockDivGoAndBack">
                                <div class="GoAndBackStyle">Връщане</div> 
                                <div >
                                    <label >Име на спирка:</label> <input class="inputAddLineSize" name="points[bus_station_back_bg][0]" value="" />
                                    <label class="blockLabel">Bus station name:</label> <input class="inputAddLineSize" name="points[bus_station_back_en][0]" value="" />
                                </div>
                                <div>
                                    <label>Час</label><input   name="points[arrival_time_back][0]" value="00:00" class="time-lenght" />                        
                                    <label>Престой</label><input   name="points[stopover_back][0]" value="0"  class="stopover-lenght"/>
                                </div>
                            </div>
                    </div> 
                </div>
                    
                </form>';

        return $this->show_page();
    }

    public function edit_line_form($line_id) {
        Session::user_auth(); //is user logged in
        $line_points = new Line_point;
        $lines = $line_points->find(array(all => '', where => 'line_id=' . $line_id, order => '`order` ASC'));
        $city = new City();
        $ln = new Line();
        $main_line = $ln->find(array(id => $line_id));

        $elements = '';
        foreach ($lines as $key => $value) {
            $elements.='
                
                <div class="clearfix divBox">
                    <div class="colDiv GoAndBackStyle blockDivStopSelect">
                        <input  class="" type="hidden" name="points[id][' . $key . ']" value="' . $value->id . '" />Спирка
                        #' . ($key + 1) . ': <select name="points[point][' . $key . ']">
                            ' . $city->options_for_cities('bg', $value->city_id) . '
                        </select>
                    </div>
                    <div class="colDiv labelText blockDivGoAndBack">
                        <div class="GoAndBackStyle">Отиване</div>
                        <div>
                            <label> Име на спирка:</label> 
                            <input class="inputAddLineSize" name="points[bus_station_bg][' . $key . ']" value="' . $value->bus_station_bg . '" />
                            <label class="blockLabel">Bus station name:</label> 
                            <input class="inputAddLineSize" name="points[bus_station_en][' . $key . ']" value="' . $value->bus_station_en . '" />
                        </div>
                        <div>
                            <label>Час</label>
                            <input   name="points[arrival_time][' . $key . ']" value="' . $value->arrival_time . '" class="time-lenght"  />
                            <label>Престой</label>
                            <input   name="points[stopover][' . $key . ']" value="' . $value->stopover . '" class="stopover-lenght"  />
                        </div>
                    </div>
                    <div class="colDiv labelText blockDivGoAndBack">
                        <div class="GoAndBackStyle">Връщане</div>
                        <div>
                            <label> Име на спирка:</label>  
                            <input class="inputAddLineSize" name="points[bus_station_back_bg][' . $key . ']" value="' . $value->bus_station_back_bg . '" />
                            <label class="blockLabel">Bus station name:</label> 
                            <input class="inputAddLineSize" name="points[bus_station_back_en][' . $key . ']" value="' . $value->bus_station_back_en . '" />
                        </div>
                        <div>
                            <label>Час</label>
                            <input  name="points[arrival_time_back][' . $key . ']" value="' . $value->arrival_time_back . '" class="time-lenght"  />
                            <label>Престой</label>
                            <input name="points[stopover_back][' . $key . ']" value="' . $value->stopover_back . '" class="stopover-lenght"  />
                        </div>
                    </div>
               </div>     
                    ';
        }

        $this->page_contents = '
                <form action="' . $_SERVER[PHP_SELF] . '" method="POST">
                    <input type="hidden" name="points[line_id]" value="' . $line_id . '" />
                    <div class="sectionDiv groupDiv">
                        <div class="blockDivFromToDate">
                            <div class=" dateInputRight">
                        <div class="dateInputRight">
                            <ul class="daysList">
                               <li class="daysListNumber">
                                 <input id="demo_box_1" class="css-checkbox" type="checkbox" value="ON" onclick="enable_date_to_date(this);" />
                                 <label for="demo_box_1" name="demo_lbl_1" class="css-label" >Отключи</label>
                               </li>
                            </ul>
                        </div>
                                 <label class="" for="data_to_date">
                                От дата:</label>
                                <input class="datepicker  " readonly="true" type="text" id="points_from_date" name="points[from_date]" value="' . $main_line->from_date . '" onclick="" readonly="readonly" ' . ($main_line->from_date && $main_line->to_date ? '' : 'disabled="disabled"') . ' />
                            </div>
                            <div class="dateInputRight">
                                <label class="" for="points_to_date">До дата:</label> 
                                <input class="datepicker"  " readonly="true"  type="text" id="points_to_date" name="points[to_date]" value="' . $main_line->to_date . '" onclick="" readonly="readonly" ' . ($main_line->from_date && $main_line->to_date ? '' : 'disabled="disabled"') . '/>
                            </div>
                        </div>
                            ' . $elements . '
                     <div class="colDiv float-right blockDivFullLine">
                        <input class="buttonStyle" name="update_points" type="submit" value="Обнови" />
                    </div>
                </div>
                </form>';

        return $this->show_page();
    }

}

?>
