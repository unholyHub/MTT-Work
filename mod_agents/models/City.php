<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of City
 *
 * @author root
 */

include_once 'Database.php';
class City extends Database{
    function __construct() {
        $this->object_of='Cities';
        $this->table='group_city';
        $this->connect();
    }

    public function options_for_cities($language,$selected=false){
        $language=($language=='bg'?'bg':'en');
        $result=mysql_query("SELECT * FROM `$this->table` WHERE lang='$language'");

        $opt='';
        while($row=mysql_fetch_row($result)){
            $opt.='<option value=\''.$row[2].'\' '.($selected==$row[2]?'selected=\'selected\'':'').'>'.$row[3].'</option>';
        }
        return $opt;
    }

    public function city($id,$lang){
        $lang=($lang=='bg'?'bg':'en');
        $result=mysql_query("SELECT city FROM $this->table WHERE ID_city=$id AND lang='$lang'");
        
        $row=mysql_fetch_row($result);
        return $row[0];
    }

    public function insert($lang,$ID_city,$city){
            mysql_query("INSERT INTO  `group_city` (
                        `ID` ,
                        `lang` ,
                        `ID_city` ,
                        `city`
                        )
                        VALUES (
                        NULL ,  '$lang',  '$ID_city',  '$city'
                        );");
            return mysql_insert_id();
    }

    public function update($id,$city){
        if(strlen($city)>0){
            mysql_query("UPDATE  `group_city` SET  `city` =  '$city' WHERE  `group_city`.`ID` =$id LIMIT 1 ;");
            return '';
        }else{
            $errors[]='Моля въведете името на града';
            return $errors;
        }
    }

}
?>
