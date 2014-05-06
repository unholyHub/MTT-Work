<?php

/*
 * 
 */
include_once 'Database.php';

class Invoice extends Database {

    function __construct() {
        $this->object_of = 'Invoices';
        $this->table = 'invoices';
        $this->connect();
    }

    public function insert() {
        return $this->insert_query("INSERT INTO  `invoices` (
                                            `id` ,
                                            `created_on`
                                            )
                                            VALUES (
                                            NULL ,
                                            CURRENT_TIMESTAMP
                                            );");
    }

    public function clear_inactive_invoices() {
        $inv_el = new Invoice_element();
        $res = new Reservation();

        $inv = $this->find(array(all => '', where => "created_on<='" . date('Y-m-d H:i:s', time() - 60 * 60 * 3) . "'"));
        foreach ($inv as $invoice) {
            $elements = $inv_el->find(array(all => '', where => "invoice_id=$invoice->id"));
            foreach ($elements as $element) {
                $reservation = $res->find(array(id => $element->reservation_id));
                if ($reservation->payed == 0) {
                    
                    mysql_query("DELETE FROM `reservations` WHERE `reservations`.`id` = $element->reservation_id LIMIT 1");
                    
                    mysql_query("DELETE FROM `invoice_elements` WHERE `invoice_elements`.`id` = $element->id LIMIT 1");
                }
            }
        }

        //Clear buffer. Make sure nothing stays there.
        mysql_query("DELETE FROM `reservation_buffer` WHERE created_at <= '" . date('Y-m-d H:i:s', time() - 60 * 60 * 1) . "'");
    }

    public function clear_old_invoices() {
        //Clear invoices older than six months
        $inv_el = new Invoice_element();

        $inv = $this->find(array(all => '', where => "created_on<='" . date('Y-m-d H:i:s', strtotime('now - 6 months')) . "'"));
        foreach ($inv as $invoice) {
            $elements = $inv_el->find(array(all => '', where => "invoice_id=$invoice->id"));
            foreach ($elements as $element) {
                mysql_query("DELETE FROM `reservations` WHERE `reservations`.`id` = $element->reservation_id LIMIT 1");

                mysql_query("DELETE FROM `invoice_elements` WHERE `invoice_elements`.`id` = $element->id LIMIT 1");
            }
        }
        mysql_query("DELETE FROM `invoices` WHERE created_on <= '" . date('Y-m-d H:i:s', strtotime('now - 6 months')) . "'");
        mysql_query("DELETE FROM `reservations` WHERE `reservations`.`created` <= '".date('Y-m-d H:i:s', strtotime('now - 6 months'))."'");
    }
    
    public function delete($id) {
        $inv_el = new Invoice_element();
        $inv = $this->find(array('all' => true, 'where' => "id = $id"));
        foreach ($inv as $invoice) {
            $elements = $inv_el->find(array('all' => '', 'where' => "invoice_id = $invoice->id"));
            foreach ($elements as $element) {
                mysql_query("DELETE FROM `reservations` WHERE `reservations`.`id` = $element->reservation_id LIMIT 1");

                mysql_query("DELETE FROM `invoice_elements` WHERE `invoice_elements`.`id` = $element->id LIMIT 1");
            }
        }
    }

}

?>
