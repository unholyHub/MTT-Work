<?php
include_once 'controllers/Line_points.php';
$line_points=new Line_points();
if(isset($_POST['submit_points'])){
    $line_points->save_all($_POST['points']);
}else{
    print $line_points->create_line_form();
}
?>
