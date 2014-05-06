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
}
?>
