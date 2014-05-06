<?php
/* 
 * 
 */

include_once 'Database.php';
class Active_place extends Database{
    function __construct() {
        $this->object_of='Active_places';
        $this->table='active_places';
        $this->connect();
    }

    public function create(Active_places $object){
        $active=$this->find(array(all=>'', where=>'line_id='.$object->line_id));
        if(isset($active[0])){
            $this->query($res, "UPDATE  `active_places` SET  `line_id` =  '$object->line_id',
                        `inactive_places` =  '$object->inactive_places',
                        `from_date` =  '$object->from_date',
                        `to_date` =  '$object->to_date' WHERE  `active_places`.`id` =".$active[0]->id." LIMIT 1 ;");
        }else{
            $this->insert_query("INSERT INTO  `active_places` (
                                    `id` ,
                                    `line_id` ,
                                    `inactive_places` ,
                                    `from_date` ,
                                    `to_date`
                                    )
                                    VALUES (
                                    NULL ,  '$object->line_id',  '$object->inactive_places',  '$object->from_date',  '$object->to_date'
                                    );");
        }
    }
}
?>
