<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Line
 *
 * @author root
 */
include_once 'Database.php';
class Line extends Database{

    function __construct() {
        $this->table = 'lines';
        $this->object_of = 'Lines';
        $this->connect();
    }

    public function insert(Lines $object){
        mysql_query("INSERT INTO  `lines` (
                    `id` ,
                    `line_name`,
                    `from_city_id`,
                    `to_city_id`,
                    `from_date`,
                    `to_date`
                    )
                    VALUES (
                    NULL ,  '$object->line_name', '$object->from_city_id', '$object->to_city_id', 
                        ".($object->from_date?"'$object->from_date'":"NULL").",
                        ".($object->to_date?"'$object->to_date'":"NULL")."
                    );");
        return mysql_insert_id();
    }

    public function update($id, $line_name, $from_city_id, $to_city_id, $from_date, $to_date){
        mysql_query("UPDATE  `lines` SET  
                    `line_name` =  '$line_name',
                    `from_city_id`='$from_city_id',
                    `to_city_id`='$to_city_id' ,
                    `from_date`=".($from_date?"'$from_date'":"NULL").",
                    `to_date`=".($from_date?"'$to_date'":"NULL")."
                    WHERE  `lines`.`id` = $id LIMIT 1 ;");
    }

    public function delete($id){
        mysql_query("DELETE FROM `lines` WHERE `lines`.`id` = $id LIMIT 1");
        mysql_query("DELETE FROM `line_points` WHERE `line_points`.`line_id` = $id");
        mysql_query("DELETE FROM `sublines` WHERE `sublines`.`line_id` = $id");
        mysql_query("DELETE FROM `travel_days` WHERE `travel_days`.`line_id` = $id");
        header('location: alllines.php');
    }
}
?>
