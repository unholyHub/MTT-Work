<?php
/* 
 * 
 */

include_once 'Database.php';
class Returned_ticket extends Database{
    function __construct() {
        $this->object_of='Returned_tickets';
        $this->table='returned_tickets';
        $this->connect();
    }

    public function insert($reservation_id){
        return $this->insert_query("INSERT INTO  `returned_tickets` (
                `id` ,
                `reservation_id` ,
                `return_date`
                )
                VALUES (
                NULL ,  '$reservation_id', CURRENT_TIMESTAMP);");
   }

    static function return_date($reservation_id){
        $result=mysql_query("SELECT return_date FROM  `returned_tickets` WHERE reservation_id =$reservation_id");
        $row=mysql_fetch_object($result);
        return date('d.m.Y H:i',strtotime($row->return_date));
    }

}
?>
