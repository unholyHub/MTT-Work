<?php
/* 
 * 
 */

include_once 'Database.php';

class Travel_day extends Database{
    function __construct() {
        $this->object_of='Travel_days';
        $this->table='travel_days';
        $this->connect();
    }

    public function create(Travel_days $object){
        $tdays=$this->find(array(all=>'',where=>"line_id=$object->line_id AND direction=$object->direction"));
        if(sizeof($tdays)>0){
            $object->id=$tdays[0]->id;
            $this->query($result, "UPDATE  `travel_days` SET  `mon` =  '$object->mon',
                `tue` =  '$object->tue',
                `wed` =  '$object->wed',
                `thu` =  '$object->thu',
                `fri` =  '$object->fri',
                `sat` =  '$object->sat',
                `sun` =  '$object->sun' WHERE  `travel_days`.`id` =$object->id LIMIT 1 ;");
            return 1;
        }else{
            
            $this->query($result, "INSERT INTO  `travel_days` (
                    `id` ,
                    `line_id` ,
                    `direction`,
                    `mon` ,
                    `tue` ,
                    `wed` ,
                    `thu` ,
                    `fri` ,
                    `sat` ,
                    `sun`
                    )
                    VALUES (
                    NULL ,  '$object->line_id',  '$object->direction', '$object->mon',  '$object->tue',  '$object->wed',  '$object->thu',  '$object->fri',  '$object->sat',  '$object->sun'
                    );");
            return 0;
        }
    }
}
?>
