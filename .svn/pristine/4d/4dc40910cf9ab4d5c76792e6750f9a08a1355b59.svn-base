<?php
/* 
 * 
 */

include_once 'Database.php';

class Reservation extends Database{
    function __construct() {
        $this->object_of='Reservations';
        $this->table='reservations';
        $this->connect();
    }

    public function validate_data(Reservations $object){
        $errors=array();
        $subln=new Subline();
        $active_places=new Active_places();
        
        $subline=$subln->find(array(id=>$object->subline_id));
        if(strlen($object->place)>0){
            if($subline->is_reserved($object->place, $object->date)||$active_places->is_inactive($subline->line_id, $object->place, $object->date)) $errors[]='Това място вече е заето.';
        }
        if(strlen($object->birthday)==0) $errors[]='Моля въведете дата на раждане';
        if(strlen($object->date)==0)$errors[]='Не е отбелязана дата';
        if(strlen($object->place)==0) $errors[]='Не е отбелязано място';
        if(strlen($object->passenger_name)==0) $errors[]='Моля напишете име и фамилия на пътника';
        #if(strlen($object->passenger_passpor)==0) $errors[]='Моля напишете номер на лична карта';
        if(strlen($object->contact_phone)==0) $errors[]='Моля напишете телефон за връзка';
        if(strlen($object->contact_email)==0) $errors[]='Моля напишете e-mail за връзка';
        if($object->ticket_type==1){
            $subline=$subln->find(array(id=>$object->back_subline_id));

            if(strlen($object->place_back)>0){
                if($subline->is_reserved($object->place_back, $object->date_back)||$active_places->is_inactive($subline->line_id, $object->place_back, $object->date_back)) $errors[]='Място на връщане вече е заето.';
            }
            if(strlen($object->date_back)==0) $errors[]='Не е отбелязана дата на връщане';
            if(strlen($object->place_back)==0) $errors[]='Не е отбелязано място на връщане';
        }

        return $errors;
    }

    public function insert(Reservations $object,$buffer_id){
        $err=$this->validate_data($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            $this->query($result, "INSERT INTO  `reservation_buffer` (
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
                            `user_id`,
                            `buffer_id`
                            )
                            VALUES (
                            NULL ,  '$object->subline_id',  '$object->back_subline_id',  '$object->place',  '$object->place_back',  '$object->ticket_number',  '$object->passenger_name',  '$object->passenger_passpor',  '$object->contact_phone',  '$object->contact_email',  '$object->price',  '$object->discount_id',  '$object->birthday',  '$object->date',  '$object->date_back',  '$object->ticket_type', '$object->user_id', '$buffer_id'
                            );");
        }
    }

    public function clear_buffers($buffer_id){
        $this->query($result, "DELETE FROM `reservation_buffer` WHERE `reservation_buffer`.`buffer_id` = '$buffer_id'");
    }

    public function pay_for_ticket($reservation_id){
        
        $number = current($this->find(array('all' => true, 'limit' => 1, 'order' => 'ticket_number desc')));

        $ticketNumber = 1;
        if ($number) {
            $ticketNumber = intval(ltrim(str_ireplace(Env::TicketPrefix.'-','',$number->ticket_number),'0')) + 1; 
            echo $ticketNumber;
        }

        if (strlen($ticketNumber) < 10) {
            $ticketNumber = str_repeat('0', 10 - strlen($ticketNumber)) . $ticketNumber;
        }

        $ticketNumber = Env::TicketPrefix . '-' . $ticketNumber;
        $this->query($res, "UPDATE  `reservations` SET  `payed` =  '1', `last_update` = CURRENT_TIMESTAMP, ticket_number = '$ticketNumber' WHERE  `reservations`.`id` =$reservation_id AND ticket_number='' LIMIT 1");
        
    }

}
?>
