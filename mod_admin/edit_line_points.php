<?php
include_once './controllers/Line_points.php';


$line_points=new Line_points();
if(isset($_POST['update_points'])){
    $line_points->update_all($_POST[points]);
}else{
    print $line_points->edit_line_form((int)$_GET['line']);
}

?>
