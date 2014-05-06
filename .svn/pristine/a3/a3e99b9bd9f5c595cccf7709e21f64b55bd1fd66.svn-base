<?php
/*
 */

include_once 'Database.php';

class Saved_place extends Database{
    function __construct() {
        $this->table='saved_places';
        $this->object_of='Saved_places';
        $this->connect();
    }

    public function validate(Saved_places $object){
        $places=explode(',', $object->places);
        $err=array();
        foreach($places as $place){
            $svd_places=$this->find(array(all=>1,where=>"subline_id=$object->subline_id AND places LIKE '%$place%' AND user_id <> $object->user_id"));
            if(sizeof($svd_places)>0) $err[]="Място $place вече е заето от друг потребител.";
        }
        return $err;
    }

    public function create(Saved_places $object){
        $err=$this->validate($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            if($object->id){
                $this->query($res, "UPDATE  `saved_places` SET  `subline_id` =  '$object->subline_id',
                                `user_id` =  '$object->user_id',
                                `places` =  '$object->places' WHERE  `saved_places`.`id` =$object->id LIMIT 1 ;");
            }else{
                $this->insert_query("INSERT INTO  `saved_places` (
                                        `id` ,
                                        `subline_id` ,
                                        `user_id` ,
                                        `places`
                                        )
                                        VALUES (
                                        NULL ,  '$object->subline_id',  '$object->user_id',  '$object->places'
                                        );
                                        ");
                return null;
            }
        }
    }

    public function delete($id){
        mysql_query("DELETE FROM `saved_places` WHERE `saved_places`.`id` = ".(int)$id." LIMIT 1");
    }
}
?>
