<?php
/* 
 * 
 */

include_once 'Database.php';
class Currency extends Database{
    function __construct() {
        $this->object_of='Currencies';
        $this->table='currencies';
        $this->connect();
    }

    private function validate(Currencies $object){
        $errors=array();
        if(strlen($object->currency)==0) $errors[]='Моля въведете код на валутата';
        if($object->index==0) $errors[]='Индекса трябва да е по-голям от 0';
        return $errors;
    }

    public function insert(Currencies $object){
        $err=$this->validate($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            return $this->insert_query("INSERT INTO  `currencies` (
                                        `id` ,
                                        `currency` ,
                                        `index`
                                        )
                                        VALUES (
                                        NULL ,  '$object->currency',  '$object->index'
                                        );");
        }
    }

    public function update(Currencies $object){
        $err=$this->validate($object);
        if(sizeof($err)>0){
            return $err;
        }else{
            return $this->query($r,"UPDATE  `currencies` SET  `currency` =  '$object->currency',
                                        `index` =  '$object->index' WHERE  `currencies`.`id` =$object->id LIMIT 1 ;");
        }
    }

    public function delete($id){
        $this->query($r, "DELETE FROM `currencies` WHERE `currencies`.`id` = $id LIMIT 1");
    }

}
?>
