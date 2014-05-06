<?php
/* 
 * 
 */

include_once 'Database.php';
class Subline extends Database{
    function __construct() {
        $this->object_of='Sublines';
        $this->table='sublines';
        $this->connect();
    }


    public function insert(Sublines $object){
        $result=mysql_query("SELECT id FROM sublines WHERE line_id=".$object->line_id." AND from_point_id=".$object->from_point_id." AND to_point_id=".$object->to_point_id);
        $count=mysql_num_rows($result);
        if($count==''){
            mysql_query("INSERT INTO  `sublines` (
                        `id` ,
                        `line_id`,
                        `from_point_id` ,
                        `to_point_id` ,
                        `travel_time`,
                        `price_oneway`,
                        `price_twoway`
                        )
                        VALUES (
                        NULL,  '$object->line_id', '$object->from_point_id',  '$object->to_point_id',  $object->travel_time, '$object->price_oneway',  '$object->price_twoway'
                        );");
            return mysql_insert_id();
        }else{
            
            $row=mysql_fetch_row($result);
            mysql_query("UPDATE  `sublines` SET  `travel_time` =  '$object->travel_time',
                `price_oneway` =  '$object->price_oneway',
                `price_twoway` =  '$object->price_twoway' WHERE  `sublines`.`id` =$row[0] LIMIT 1 ;");
        }
    }
}
?>
