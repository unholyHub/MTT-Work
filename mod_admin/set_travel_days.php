<?php

include_once './controllers/Travel_days.php';

$tdays = new Travel_days();
if (isset($_POST[set_days])) {
    $tdays->save($_POST[travel_day]);
} else {
    print $tdays->travel_days_form((int) $_GET[line_id], isset($_GET[back]));
}
?>
