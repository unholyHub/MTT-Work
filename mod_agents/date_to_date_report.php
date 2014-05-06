<?php
include_once './controllers/Reports.php';

$report=new Reports();
print $report->date_to_date_report($_GET[date_from], $_GET[date_to]);

?>
