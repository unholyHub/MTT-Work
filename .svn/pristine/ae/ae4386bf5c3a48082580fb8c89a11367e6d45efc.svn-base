<?php
/* 
 * 
 */
include_once 'Database.php';
class User_access extends Database {
    function __construct() {
        $this->object_of='User_accesses';
        $this->table='user_access';
        $this->connect();
    }

    public function insert(User_accesses $object){
        if(sizeof($this->find(array(all=>1,where=>"user_id=$object->user_id")))>0){
            $this->query($res, "UPDATE  `user_access` SET  `administration_access` =  '".$object->administration_access."',
                                `sell_access` =  '".$object->sell_access."',
                                `agent_report_access` =  '".$object->agent_report_access."',
                                `travel_list_report_access` =  '".$object->travel_list_report_access."',
                                `date2date_report_access` =  '".$object->date2date_report_access."',
                                `destination_report_access` =  '".$object->destination_report_access."',
                                `all_sales_access` =  '".$object->all_sales_access."',
                                `sale_edit_access` =  '".$object->sale_edit_access."',
                                `sale_delete_access` =  '".$object->sale_delete_access."',
                                `sale_return_access` =  '".$object->sale_return_access."',
                                `show_epay_sales_access` =  '".$object->show_epay_sales_access."',
                                `save_places_access` =  '".$object->save_places_access."',
                                `has_payment_restrictions` =  '".$object->has_payment_restrictions."',
                                `free_places_access` =  '".$object->free_places_access."' WHERE  `user_access`.`user_id` =$object->user_id LIMIT 1 ;");
        }else{
            $this->insert_query("INSERT INTO  `user_access` (
                                    `id` ,
                                    `user_id` ,
                                    `administration_access` ,
                                    `sell_access` ,
                                    `agent_report_access` ,
                                    `travel_list_report_access` ,
                                    `date2date_report_access` ,
                                    `destination_report_access` ,
                                    `all_sales_access` ,
                                    `sale_edit_access` ,
                                    `sale_delete_access` ,
                                    `sale_return_access` ,
                                    `show_epay_sales_access`,
                                    `save_places_access`,
                                    `free_places_access`,
                                    `has_payment_restrictions`
                                    )
                                    VALUES (
                                    NULL ,
                                    '$object->user_id',
                                    '".$object->administration_access."',
                                    '".$object->sell_access."',
                                    '".$object->agent_report_access."',
                                    '".$object->travel_list_report_access."',
                                    '".$object->date2date_report_access."',
                                    '".$object->destination_report_access."',
                                    '".$object->all_sales_access."',
                                    '".$object->sale_edit_access."',
                                    '".$object->sale_delete_access."',
                                    '".$object->sale_return_access."',
                                    '".$object->show_epay_sales_access."',
                                    '".$object->save_places_access."',
                                    '".$object->free_places_access."',
                                    '".$object->has_payment_restrictions."'
                                    );");
        }
    }

}
?>
