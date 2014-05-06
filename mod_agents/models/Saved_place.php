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
}
?>
