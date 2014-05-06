<?php
/* 
 *
 */

include_once 'Database.php';

class User extends Database{
    function __construct() {
        $this->object_of='Users';
        $this->table='users';
        $this->connect();
    }


    private function validate_user($user,$passwd,$confirm_passwd){
        $errors=array();
        $users=$this->find(array(all=>'',where=>"user='$user'"));

        if(sizeof($users)>0) $errors[]='Потребителят съществува';
        if(strlen($user)<3) $errors[]='Потребителското име трябва да е повече от 2 символа';
        if(strlen($passwd)<4) $errors[]='Паролата трябва да е повече от 3 символа';
        if($passwd != $confirm_passwd) $errors[]='Паролата не е потвърдена успешно';

        return $errors;
    }

    public function adduser($user,$passwd,$confirm_passwd,$id=false){

        $errors=$this->validate_user($user, $passwd, $confirm_passwd);
        if(sizeof($errors)>0){
            return $errors;
        }else{

            mysql_query("INSERT INTO  `users` (
                            `id` ,
                            `user` ,
                            `passwd` ,
                            `access`
                            )
                            VALUES (
                            NULL ,  '$user',  '$passwd',  '1'
                            );");

            return mysql_insert_id();
        }
    }

    private function validate_user_update($id,$user,$passwd,$confirm_passwd){
        $errors=array();
        $users=$this->find(array(all=>'',where=>"user='$user' AND id <> $id"));

        if(sizeof($users)>0) $errors[]='Потребителят съществува';
        if(strlen($user)<3) $errors[]='Потребителското име трябва да е повече от 2 символа';
        if(strlen($passwd)<4) $errors[]='Паролата трябва да е повече от 3 символа';
        if($passwd != $confirm_passwd) $errors[]='Паролата не е потвърдена успешно';

        return $errors;
    }

    public function update($id,$user,$passwd,$confirm_passwd){
        $errors=$this->validate_user_update($id,$user, $passwd, $confirm_passwd);
        if(sizeof($errors)>0){
            return $errors;
        }else{

            mysql_query("UPDATE  `users` SET  `user` =  '$user',
                        `passwd` =  '$passwd' WHERE  `users`.`id` =$id LIMIT 1 ;");

            return null;
        }
    }

    public function delete($id){
        mysql_query("DELETE FROM `users` WHERE `users`.`id` = $id LIMIT 1");
    }

}
?>
