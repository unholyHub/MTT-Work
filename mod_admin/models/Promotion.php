<?php
/*
 */

include_once 'Database.php';
class Promotion extends Database{
    function __construct() {
        $this->table='promotions';
        $this->object_of='Promotions';
        $this->connect();
    }

    private function validate(Promotions $object){
        $errors=array();
        if(strlen($object->promo_percent)==0) $errors[]='Моля въведенте процент на намалението.';
        return $errors;
    }

    public function create(Promotions $object){
        $err=$this->validate($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            if(sizeof($this->find(array(all=>"",where=>"subline_id=$object->subline_id")))>0){
                $this->query($result, "UPDATE  `promotions` SET
                                    `promo_percent` =  '$object->promo_percent',
                                    `expires` =  '$object->expires' WHERE  `promotions`.`subline_id` =$object->subline_id LIMIT 1 ;");
                return null;
            }else{
                $this->insert_query("INSERT INTO  `promotions` (
                                    `id` ,
                                    `subline_id` ,
                                    `promo_percent` ,
                                    `expires`
                                    )
                                    VALUES (
                                    NULL ,  '$object->subline_id',  '$object->promo_percent', '$object->expires'
                                    );");
                return null;
            }
        }
    }

    public function delete($id){
        $this->query($r, "DELETE FROM `promotions` WHERE `promotions`.`id` = $id LIMIT 1");
    }

}
?>
