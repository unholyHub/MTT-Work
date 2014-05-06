<?php
/* 
 * 
 */

include_once 'Database.php';

class Line_point extends Database{
    function __construct() {
        $this->table = 'line_points';
        $this->object_of = 'Line_points';
        $this->connect();
    }

    public function insert(Line_points $object){
        mysql_query("INSERT INTO  `line_points` (
                    `id` ,
                    `line_id` ,
                    `city_id` ,
                    `stopover`,
                    `stopover_back`,
                    `arrival_time`,
                    `bus_station_bg`,
                    `bus_station_en`,
                    `arrival_time_back`,
                    `bus_station_back_bg`,
                    `bus_station_back_en`,
                    `order`
                    )
                    VALUES (
                    NULL ,  '$object->line_id',  '$object->city_id',  '$object->stopover', '$object->stopover_back', '$object->arrival_time', '$object->bus_station_bg', '$object->bus_station_en', '$object->arrival_time_back', '$object->bus_station_back_bg', '$object->bus_station_back_en', '$object->order'
                    );");
        return mysql_insert_id();
    }

    public function update(Line_points $object){
        mysql_query("UPDATE  `line_points` SET  `city_id` =  '$object->city_id',
                `stopover` =  '$object->stopover',
                `stopover_back`= '$object->stopover_back',
                `arrival_time` =  '$object->arrival_time',
                `bus_station_bg` =  '$object->bus_station_bg',
                `bus_station_en` =  '$object->bus_station_en',
                `arrival_time_back` =  '$object->arrival_time_back',
                `bus_station_back_bg` =  '$object->bus_station_back_bg',
                `bus_station_back_en` =  '$object->bus_station_back_en' WHERE  `line_points`.`id` =$object->id LIMIT 1 ;");
    }
}
?>
