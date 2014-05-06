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
}
?>
