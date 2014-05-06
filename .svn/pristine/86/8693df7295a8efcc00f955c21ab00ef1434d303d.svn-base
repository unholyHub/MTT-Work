<?php
/* 
 * 
 */

include_once 'Database.php';

class Reservation extends Database{
    const TicketPrefix = 'MTT';
    
    function __construct() {
        $this->object_of='Reservations';
        $this->table='reservations';
        $this->connect();
    }

//    public function validate_data(Reservations $object){
//        $errors=array();
//        $subln=new Subline();
//        $active_places=new Active_places();
//        
//        $subline=$subln->find(array(id=>$object->subline_id));
//        if(strlen($object->place)>0){
//            if($subline->is_reserved($object->place, $object->date)||$active_places->is_inactive($subline->line_id, $object->place, $object->date)) $errors[]='Това място вече е заето.';
//        }
//        if(strlen($object->birthday)==0) $errors[]='Моля въведете дата на раждане';
//        if(strlen($object->date)==0)$errors[]='Не е отбелязана дата';
//        if(strlen($object->place)==0) $errors[]='Не е отбелязано място';
//        if(strlen($object->passenger_name)==0) $errors[]='Моля напишете име и фамилия на пътника';
//        #if(strlen($object->passenger_passpor)==0) $errors[]='Моля напишете номер на лична карта';
//        if(strlen($object->contact_phone)==0) $errors[]='Моля напишете телефон за връзка';
//        if(strlen($object->contact_email)==0) $errors[]='Моля напишете e-mail за връзка';
//        if($object->ticket_type==1){
//            $subline=$subln->find(array(id=>$object->back_subline_id));
//
//            if(strlen($object->place_back)>0){
//                if($subline->is_reserved($object->place_back, $object->date_back)||$active_places->is_inactive($subline->line_id, $object->place_back, $object->date_back)) $errors[]='Място на връщане вече е заето.';
//            }
//            if(strlen($object->date_back)==0) $errors[]='Не е отбелязана дата на връщане';
//            if(strlen($object->place_back)==0) $errors[]='Не е отбелязано място на връщане';
//        }
//
//        return $errors;
//    }

    public function validate_agent_data(Reservations $object){
        $errors=array();
        $subln=new Subline();
        $active_places=new Active_places();

        $subline=$subln->find(array(id=>$object->subline_id));
        if(strlen($object->place)>0){
            if($subline->is_reserved($object->place, $object->date)||$active_places->is_inactive($subline->line_id, $object->place, $object->date)) $errors[]=locales::$text[errors_place_taken];
        }
        // if(strlen($object->birthday)==0) $errors[]=locales::$text[errors_birthday];
        // if(strlen($object->ticket_number) == 0 && $object->payed==1) $errors[]=locales::$text[errors_number];
        if(strlen($object->date)==0)$errors[]=locales::$text[errors_date];
        if(strlen($object->place)==0) $errors[]=locales::$text[errors_place];
        if(strlen($object->passenger_name)==0) $errors[]=locales::$text[errors_passenger_name];
        //if(strlen($object->passenger_passpor)==0) $errors[]=locales::$text[errors_passenger_passport];
        //if(strlen($object->contact_phone)==0) $errors[]=locales::$text[errors_phone];
        //if(strlen($object->contact_email)==0) $errors[]=locales::$text[errors_email];
        if($object->ticket_type==1){
            $subline=$subln->find(array(id=>$object->back_subline_id));

            if(strlen($object->place_back)>0){
                if($subline->is_reserved($object->place_back, $object->date_back)||$active_places->is_inactive($subline->line_id, $object->place_back, $object->date_back)) $errors[]=locales::$text[errors_place_back_taken];
            }
            if(strlen($object->date_back)==0)$errors[]=locales::$text[errors_date_back];
            if(strlen($object->place_back)==0) $errors[]=locales::$text[errors_place_back];
        }

        return $errors;
    }

    public function pay_for_ticket($reservation_id){
        if (Session::access_is_allowed('has_payment_restrictions')) {
            $count = current($this->find(array('all' => true, 'limit' => 1, 'order' => 'ticket_number desc')));

            $ticketNumber = 1;
            if ($count) {
                $ticketNumber = intval(ltrim(str_ireplace(Env::TicketPrefix.'-','',$count->ticket_number),'0')) + 1; 
            }

            if (strlen($ticketNumber) < 10) {
                $ticketNumber = str_repeat('0', 10 - strlen($ticketNumber)) . $ticketNumber;
            }

            $ticketNumber = Env::TicketPrefix . '-' . $ticketNumber;
        }
        
        $this->query($res, "UPDATE  `reservations` SET  `payed` =  '1', `last_update` = CURRENT_TIMESTAMP, ticket_number = '$ticketNumber' WHERE  `reservations`.`id` =$reservation_id AND ticket_number='' LIMIT 1");
    }

    public function insert_agent_data(Reservations $object,$agent_id){
        $err=$this->validate_agent_data($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            
            if(!Session::access_is_allowed('has_payment_restrictions')) {
                $count = current($this->find(array('all'=>true, 'limit'=>1, 'order'=>'ticket_number desc')));

                $ticketNumber = 1;
                if($count) {
                   $ticketNumber = intval(ltrim(str_ireplace(Env::TicketPrefix.'-','',$count->ticket_number),'0')) + 1;  
                }

                if(strlen($ticketNumber) < 10) {
                    $ticketNumber = str_repeat('0', 10 - strlen($ticketNumber)) . $ticketNumber;
                }

                $object->ticket_number = Env::TicketPrefix . '-' . $ticketNumber;
            }
            
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
                            NULL ,  '$object->subline_id',  '$object->back_subline_id',  '$object->place',  '$object->place_back',  '$object->ticket_number',  '$object->passenger_name',  '$object->passenger_passpor',  '$object->contact_phone',  '$object->contact_email',  '$object->price',  '$object->discount_id',  '$object->birthday',  '$object->date',  '$object->date_back',  '$object->ticket_type',  '$object->payed', '$agent_id', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
                            );");
        }
    }

    public function validate_update_agent_data(Reservations $object){
        $errors=array();
        $subln=new Subline();
        $active_places=new Active_places();

        $subline=$subln->find(array(id=>$object->subline_id));
        if(strlen($object->place)>0){
            if($subline->is_reserved_on_update($object->place, $object->date, $object->id) || $active_places->is_inactive($subline->line_id, $object->place, $object->date)) $errors[]=locales::$text[errors_place_taken];
        }
        //if(strlen($object->birthday)==0) $errors[]=locales::$text[errors_birthday];
        //if(strlen($object->ticket_number)==0) $errors[]=locales::$text[errors_number];
        if(strlen($object->date)==0)$errors[]=locales::$text[errors_date];
        if(strlen($object->place)==0) $errors[]=locales::$text[errors_place];
        if(strlen($object->passenger_name)==0) $errors[]=locales::$text[errors_passenger_name];
        //if(strlen($object->passenger_passpor)==0) $errors[]=locales::$text[errors_passenger_passport];
        //if(strlen($object->contact_phone)==0) $errors[]=locales::$text[errors_phone];
        //if(strlen($object->contact_email)==0) $errors[]=locales::$text[errors_email];
        if($object->ticket_type==1){
            $subline=$subln->find(array(id=>$object->back_subline_id));

            if(strlen($object->place_back)>0){
                if($subline->is_reserved_on_update($object->place_back, $object->date_back, $object->id)||$active_places->is_inactive($subline->line_id, $object->place_back, $object->date_back)) $errors[]=locales::$text[errors_place_back_taken];
            }
            if(strlen($object->date_back)==0)$errors[]=locales::$text[errors_date_back];
            if(strlen($object->place_back)==0) $errors[]=locales::$text[errors_place_back];
        }

        return $errors;
    }

    public function update_agent_data(Reservations $object){
        $err=$this->validate_update_agent_data($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            $this->query($result, "UPDATE  `reservations` SET  `place` =  '$object->place',
                                    `place_back` =  '$object->place_back',
                                    `passenger_name` =  '$object->passenger_name',
                                    `subline_id` = '$object->subline_id',
                                    `back_subline_id` = '$object->back_subline_id',
                                    `passenger_passpor` =  '$object->passenger_passpor',
                                    `contact_phone` =  '$object->contact_phone',
                                    `contact_email` =  '$object->contact_email',
                                    `price` =  '$object->price',
                                    `discount_id` =  '$object->discount_id',
                                    `birthday` =  '$object->birthday',
                                    `date` =  '$object->date',
                                    `date_back` =  '$object->date_back',
                                    `ticket_type` =  '$object->ticket_type' WHERE  `reservations`.`id` =$object->id LIMIT 1 ;");
            
        }
    }

    public function add_ticket_number(Reservations $object){
        $this->query($result, "UPDATE  `reservations` SET  `ticket_number` =  '$object->ticket_number', `last_update`=CURRENT_TIMESTAMP, `payed`=1 WHERE  `reservations`.`id` =$object->id LIMIT 1 ;");
    }

    private function validate_finalization(Reservations $object){
        $errors=array();
        $subln=new Subline();

        $subline=$subln->find(array(id=>$object->back_subline_id));
        if(strlen($object->place_back)>0){
            if($subline->is_reserved($object->place_back, $object->date_back)) $errors[]=locales::$text[errors_place_back_taken];
        }

        if($object->place_back=='' || $object->place_back==0) $errors[]=locales::$text[errors_place];
        if(strlen($object->date_back)==0)$errors[]=locales::$text[errors_date_back];
        return $errors;
    }
    
    public function finalize_opened_ticket(Reservations $object){
        $err=$this->validate_finalization($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            $this->query($r, "UPDATE  `reservations` SET  `place_back` =  '$object->place_back',
                        `date_back` =  '$object->date_back',
                        `ticket_type` =  '1',
                        `back_subline_id` =  '$object->back_subline_id',`last_update`=CURRENT_TIMESTAMP WHERE  `reservations`.`id` =$object->id LIMIT 1 ;");
            return '';
        }
    }

    private function validate_client_details(Reservations $object){
        $errors=array();
        if(strlen($object->passenger_name)==0) $errors[]=locales::$text[errors_passenger_name];
        return $errors;
    }

    public function update_client_details(Reservations $object){
        $err=$this->validate_client_details($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            $this->query($result, "UPDATE  `reservations` SET  `passenger_name` =  '$object->passenger_name',
                            `passenger_passpor` =  '$object->passenger_passpor',
                            `birthday` = '$object->birthday',
                            `contact_phone` =  '$object->contact_phone',
                            `contact_email` =  '$object->contact_email', `last_update`=CURRENT_TIMESTAMP WHERE  `reservations`.`id` =$object->id LIMIT 1 ;");
            return null;
        }
    }

    public function ticket_return($reservation_id){
        $reservation=$this->find(array(id=>$reservation_id));
        if(($reservation->user_id==Session::current_user()->id || $reservation->user_id==0 || Session::access_is_allowed(administration_access))&& Session::access_is_allowed(sale_return_access)){
            $returned_tickets=new Returned_ticket();
            $this->query($res, "UPDATE  `reservations` SET  `payed` =  '2', `last_update`=CURRENT_TIMESTAMP WHERE  `reservations`.`id` =$reservation_id LIMIT 1 ;");
            $returned_tickets->insert($reservation_id);
        }
    }

    public function delete($id){
        $reservation=$this->find(array(id=>$id));
        if(Session::access_is_allowed(sale_delete_access)){
            $this->query($re, "DELETE FROM `reservations` WHERE `reservations`.`id` = $id LIMIT 1");
            $this->query($rf, "DELETE FROM `returned_tickets` WHERE `returned_tickets`.`reservation_id` = $id LIMIT 1");
        }
    }

}
?>
