<?php

/*
 *
 */
include_once 'gui.php';
include_once './models/Active_place.php';
include_once './controllers/Lines.php';

class Active_places extends gui {

    public $id;
    public $line_id;
    public $inactive_places;
    public $from_date;
    public $to_date;

    function __construct() {
        
    }

    function Active_places($id, $line_id, $inactive_places, $from_date, $to_date) {
        $this->id = $id;
        $this->line_id = $line_id;
        $this->inactive_places = $inactive_places;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function active_place_form($line_id) {
        Session::user_auth();
        $ln = new Line();
        $line = $ln->find(array(id => $line_id));
        $this->page_contents = '<table class="usersTable" >
                    <tr>
                        <th><b>' . $line->line_name . '</b></th>
                    </tr>
                    <tr>
                        <td style="border-bottom:none;border-top:none">
                            <form action="' . $_SERVER[PHP_SELF] . '?lid=' . $line_id . '" method="POST">
                                 <input type="hidden" name="active_places[line_id]" value="' . $line_id . '" />
                                    <tr>
                                        <td style="border-bottom:none;border-top:none">
                                            <div class="clearfix">
                                             <label> От: </label> 
                                             <input class="datepicker" id="from_date" type="text" name="active_places[from_date]" value="' . ($this->from_date ? $this->from_date : date('Y-m-d')) . '" size="15" readonly="readonly" onclick=""/>
                                            </div>
                                            <div class="clearfix">
                                             <label class=""> До: </label> 
                                             <input class="datepicker" id="to_date" type="text" name="active_places[to_date]" value="' . ($this->to_date ? $this->to_date : date('Y-m-d')) . '" size="15" readonly="readonly" onclick="" /><br />
                                            </div>     
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border-bottom:none;border-top:none"> 
                                            <input id="inactive_places" type="text" name="active_places[inactive_places]" value="' . $this->inactive_places . '" />
                                            <input class="buttonStyle"  type="submit" value="Запази" name="add_inactive" />
                                        </td>
                                    </tr>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>' . $this->place_table($this->inactive_places) . '</td>
                    </tr>
                    
                  </table>';

        return $this->show_page();
    }

    public function place_table($inactive_places) {
        Session::user_auth();
        $places_buff = explode(',', $inactive_places);
        $places = array();
        foreach ($places_buff as $place) {
            $places[$place] = 1;
        }

        $places_table = '<div class="places">';
        for ($i = 1, $br = 0; $i <= 57; $i++) {
            if (($i == 2 || $br == 3) && $i < 54) {
                if (isset($places[$i]))
                    $places_table.='<div class="reserved" onclick="select_place(this,' . $i . ');" style="margin-right:55px;">' . $i . '</div>';
                else
                    $places_table.='<div class="notreserved" onclick="select_place(this,' . $i . ');" style="margin-right:55px;">' . $i . '</div>';
                $br = 0;
            }else {
                if (isset($places[$i]))
                    $places_table.='<div class="reserved" onclick="select_place(this,' . $i . ');">' . $i . '</div>';
                else
                    $places_table.='<div class="notreserved" onclick="select_place(this,' . $i . ');">' . $i . '</div>';
                $br++;
            }
        }
        $places_table.='</div>';
        return $places_table;
    }

    public function is_inactive($line_id, $place, $date) {
        $act = new Active_place();
        $inactive = $act->find(array(all => '', where => "line_id=$line_id AND from_date<='$date' AND to_date>='$date'"));
        if (isset($inactive[0])) {
            $places = explode(',', $inactive[0]->inactive_place);
            foreach ($places as $one_inactive_place) {
                if ($place == $one_inactive_place) {
                    return true;
                }
            }
        }
        return false;
    }

    public function save(array $elements) {
        Session::user_auth();
        $this->line_id = $elements[line_id];
        $this->inactive_places = $elements[inactive_places];
        $this->from_date = $elements[from_date];
        $this->to_date = $elements[to_date];

        $act = new Active_place();
        $act->create($this);

        header('location:./alllines.php');
    }

}

?>
