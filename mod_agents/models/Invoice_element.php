<?php
/* 
 * 
 */
include_once 'Database.php';
class Invoice_element extends Database{
    function __construct() {
        $this->object_of='Invoice_elements';
        $this->table='invoice_elements';
        $this->connect();
    }

    public function insert(Invoice_elements $object){
        return $this->insert_query("INSERT INTO  `invoice_elements` (
                                `id` ,
                                `invoice_id` ,
                                `reservation_id`
                                )
                                VALUES (
                                NULL ,  '$object->invoice_id',  '$object->reservation_id'
                                );
                                ");
    }

}
?>
