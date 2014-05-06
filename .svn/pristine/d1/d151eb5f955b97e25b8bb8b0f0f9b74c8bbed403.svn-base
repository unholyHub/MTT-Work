<?php
/*
 *
 */

include_once 'Database.php';

class Reservation_buffer extends Database{
    function __construct() {
        $this->object_of='Reservation_buffers';
        $this->table='reservation_buffer';
        $this->connect();
    }

    public function insert_to_main_list(Reservation_buffers $object){
        $count='';
        $count=$this->query($res, "SELECT * FROM reservations WHERE ((place=$object->place AND date='$object->date' AND subline_id=$object->subline_id) OR (place_back=$object->place AND date_back='$object->date' AND back_subline_id=$object->subline_id)) AND payed<2");
        $count_back='';
        if($object->ticket_type==1) $count_back=$this->query($res_back, "SELECT * FROM reservations WHERE ((place=$object->place_back AND date='$object->date_back' AND subline_id=$object->back_subline_id) OR (place_back=$object->place_back AND date_back='$object->date_back' AND back_subline_id=$object->back_subline_id)) AND payed<2");
        
        if($count==0&&$count_back==0){            
            return $this->insert_query("INSERT INTO  `reservations` (
                            `id` ,
                            `subline_id` ,
                            `back_subline_id` ,
                            `place` ,
                            `place_back` ,
                            `ticket_number` ,
                            `passenger_name` ,
                            `passenger_passpor` ,
                            `contact_phone` ,
                            `contact_email` ,
                            `price` ,
                            `discount_id` ,
                            `birthday` ,
                            `date` ,
                            `date_back` ,
                            `ticket_type` ,
                            `payed`,
                            `user_id`,
                            `last_update`,
                            `created`
                            )
                            VALUES (
                            NULL ,  '$object->subline_id',  '$object->back_subline_id',  '$object->place',  '$object->place_back',  '$object->ticket_number',  '$object->passenger_name',  '$object->passenger_passpor',  '$object->contact_phone',  '$object->contact_email',  '$object->price',  '$object->discount_id',  '$object->birthday',  '$object->date',  '$object->date_back',  '$object->ticket_type',  '0', '$object->user_id', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
                            );");
        }
        return 0;
    }
}
?>
