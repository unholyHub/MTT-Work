<?php
/* 
 * 
 */

include_once 'Database.php';

class Discount extends Database{
    function __construct() {
        $this->object_of='Discounts';
        $this->table='discounts';
        $this->connect();
    }

    private function validate(Discounts $object){
        $errors=array();

        if(strlen($object->name_bg)==0) $errors[]='Моля въведете име на намалението на български.';
        if(strlen($object->name_en)==0) $errors[]='Моля въведете име на намалението на английски.';
        if($object->discount==0 || strlen($object->discount)==0) $errors[]='Моля въведете намаление в % или със сума.';
        return $errors;
    }

    public function insert(Discounts $object){
        $err=$this->validate($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            $this->query($result, "INSERT INTO  `discounts` (
                        `id` ,
                        `name_bg` ,
                        `name_en` ,
                        `description_bg` ,
                        `description_en` ,
                        `discount` ,
                        `discount_type`
                        )
                        VALUES (
                        NULL ,  '$object->name_bg', '$object->name_en', '$object->description_bg', '$object->description_en',  '$object->discount',  '$object->discount_type'
                        );");

            return '';
        }
    }

    public function update(Discounts $object){
        $err=$this->validate($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            $this->query($result, "UPDATE  `discounts` SET  `name_bg` =  '$object->name_bg',
                    `name_en` =  '$object->name_en',
                    `description_bg` =  '$object->description_bg',
                    `description_en` =  '$object->description_en',
                    `discount` =  '$object->discount',
                    `discount_type` =  '$object->discount_type' WHERE  `discounts`.`id` =$object->id LIMIT 1 ;");

            return '';
        }
    }

    public function delete($id){
        $this->query($result, "DELETE FROM `discounts` WHERE `discounts`.`id` = $id LIMIT 1");
    }
}
?>
