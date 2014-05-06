<?php

/*
 * 
 */

include_once 'gui.php';
include_once './models/Travel_day.php';

class Travel_days extends gui {

    public $id;
    public $line_id;
    public $direction;
    public $mon;
    public $tue;
    public $wed;
    public $thu;
    public $fri;
    public $sat;
    public $sun;

    function __construct() {
        
    }

    function Travel_days($id, $line_id, $direction, $mon, $tue, $wed, $thu, $fri, $sat, $sun) {
        $this->id = $id;
        $this->line_id = $line_id;
        $this->direction = $direction;
        $this->mon = $mon;
        $this->tue = $tue;
        $this->wed = $wed;
        $this->thu = $thu;
        $this->fri = $fri;
        $this->sat = $sat;
        $this->sun = $sun;
    }

    function o_clone($object) {
        $this->id = $object->id;
        $this->line_id = $object->line_id;
        $this->direction = $object->direction;
        $this->mon = $object->mon;
        $this->tue = $object->tue;
        $this->wed = $object->wed;
        $this->thu = $object->thu;
        $this->fri = $object->fri;
        $this->sat = $object->sat;
        $this->sun = $object->sun;
    }

    public function travel_days_form($line_id, $back = false, $layout = true) {
        if ($line_id != null) {
            $tday = new Travel_day();
            $tdays = $tday->find(array(all => '', where => 'line_id=' . $line_id . ' AND direction=' . ($back ? '1' : '0')));
            if (sizeof($tdays) > 0)
                $this->o_clone($tdays[0]);
        }

        if ($back) {
            $dir = 1;
        } else {
            $dir = 2;
        }

        $dayUniqueId = $line_id . '_' . $dir;

        $this->page_contents = '
            <form action="./set_travel_days.php" method="POST">
                <input type="hidden" name="travel_day[line_id]" value="' . $line_id . '" />
                <input type="hidden" name="travel_day[direction]" value="' . ($back ? '1' : '0') . '" />
                  <fieldset class="day-list-field">
                    <ul class="daysList unselectable">
                        <li>
                            <b>Ден от седмицата</b>
                        </li>
                        <li class="daysListNumber clearfix">
                            <label class="label_check js-label-group-button" for="mon_' . $dayUniqueId . '">
                            <input class="label_check"  id="mon_' . $dayUniqueId . '" type="checkbox" name="travel_day[mon]" value="1" ' . ($this->mon == 1 ? 'checked="checked"' : '') . '/>Понеделник</label>
                        </li>    
                        <li class="daysListNumber clearfix">
                            <label class="label_check js-label-group-button" for="tue_' . $dayUniqueId . '">
                            <input class="label_check" id="tue_' . $dayUniqueId . '" type="checkbox" name="travel_day[tue]" value="1" ' . ($this->tue == 1 ? 'checked="checked"' : '') . '/>Вторник</label>
                        </li>
                        <li class="daysListNumber clearfix">
                            <label class="label_check js-label-group-button" for="wed_' . $dayUniqueId . '">
                            <input class="label_check" id="wed_' . $dayUniqueId . '" type="checkbox" name="travel_day[wed]" value="1" ' . ($this->wed == 1 ? 'checked="checked"' : '') . '/>Сряда</label>
                        </li>
                        
                        <li class="daysListNumber clearfix">
                            <label class="label_check js-label-group-button" for="thu_' . $dayUniqueId . '">
                            <input class="label_check" id="thu_' . $dayUniqueId . '" type="checkbox" name="travel_day[thu]" value="1" ' . ($this->thu == 1 ? 'checked="checked"' : '') . '/>Четвъртък</label>
                        </li>
                        <li class="daysListNumber clearfix">
                            <label class="label_check js-label-group-button" for="fri_' . $dayUniqueId . '">
                            <input class="label_check" id="fri_' . $dayUniqueId . '" type="checkbox" name="travel_day[fri]" value="1" ' . ($this->fri == 1 ? 'checked="checked"' : '') . '/>Петък</label>
                        </li>
                        <li class="daysListNumber clearfix">
                            <label class="label_check js-label-group-button" for="sat_' . $dayUniqueId . '">
                            <input class="label_check" id="sat_' . $dayUniqueId . '" type="checkbox" name="travel_day[sat]" value="1" ' . ($this->sat == 1 ? 'checked="checked"' : '') . '/>Събота</label>
                        </li>
                        <li class="daysListNumber clearfix">
                            <label class="label_check js-label-group-button" for="sun_' . $dayUniqueId . '">
                            <input class="label_check" id="sun_' . $dayUniqueId . '" type="checkbox" name="travel_day[sun]" value="1" ' . ($this->sun == 1 ? 'checked="checked"' : '') . '/>Неделя</label>
                       </li>
                       <li>
                            <input class="buttonStyle"  type="submit" value="' . ($layout ? 'Следваща стъпка' : 'Обнови') . '" name="set_days" />
                       </li>
                </ul>
                </fieldset> 
                
            </form>';
        if ($layout) {
            return $this->show_page();
        } else {
            return $this->page_contents;
        }
    }

    public function save($elements) {
        $this->line_id = $elements[line_id];
        $this->direction = $elements[direction];
        $this->mon = $elements[mon];
        $this->tue = $elements[tue];
        $this->wed = $elements[wed];
        $this->thu = $elements[thu];
        $this->fri = $elements[fri];
        $this->sat = $elements[sat];
        $this->sun = $elements[sun];
        $tday = new Travel_day();
        $stat = $tday->create($this);
        if ($stat == 0) {
            header('location: ./addsublines.php?line=' . $this->line_id . ($this->direction == 1 ? '&back' : ''));
        } else {
            header('location: ./alllines.php');
        }
    }

}

?>
