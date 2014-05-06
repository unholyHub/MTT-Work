<?php
/* 
 *
 */
include_once 'gui.php';
include_once './models/Active_place.php';
include_once './controllers/Lines.php';

class Active_places extends gui{
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

    public function active_place_form($line_id){
        $this->template_name='agents';
        Session::user_auth();
        $ln=new Line();
        if(!$line_id){
            $city=new City();
            $lines=$ln->find(array(all=>1));
            $this->page_contents='<div class="forms">';
            foreach($lines as $line){
                $this->page_contents.='<div><a href="'.$_SERVER[PHP_SELF].'?lid='.$line->id.'">'.$line->id.': '.$city->city($line->to_city_id, locales::$current_locale).' - '.$city->city($line->from_city_id, locales::$current_locale).'</a></div>';
            }
            $this->page_contents.='</div>';
        }else{
            $line=$ln->find(array(id=>$line_id));
            $this->page_contents='<table align="center" class="forms">
                        <tr>
                            <td align="center"><b>'.$line->line_name.'</b></td>
                        </tr>
                        <tr>
                            <td>'.$this->place_table($this->inactive_places).'</td>
                        </tr>
                        <tr>
                            <td align="right">
                                <form action="'.$_SERVER[PHP_SELF].'?lid='.$line_id.'" method="POST">
                                    <input type="hidden" name="active_places[line_id]" value="'.$line_id.'" />
                                    '.locales::$text[reports_from_date].'<input id="from_date" type="text" name="active_places[from_date]" value="'.($this->from_date?$this->from_date:date('Y-m-d')).'" size="15" readonly="readonly" onclick="NewCssCal(\'from_date\',\'yyyymmdd\');"/>
                                    '.locales::$text[reports_to_date].'<input id="to_date" type="text" name="active_places[to_date]" value="'.($this->to_date?$this->to_date:date('Y-m-d')).'" size="15" readonly="readonly" onclick="NewCssCal(\'to_date\',\'yyyymmdd\');" /><br />
                                    <input id="inactive_places" type="text" name="active_places[inactive_places]" value="'.$this->inactive_places.'" size="30" />
                                    <input type="submit" value="'.locales::$text[tickets_add].'" name="add_inactive" />
                                </form>
                            </td>
                        </tr>
                      </table>';
        }
        if(!Session::access_is_allowed(save_places_access)) $this->page_contents='<h2 style="color:red; text-align:center;">'.locales::$text[no_permission].'</h2>';
        return $this->show_page();
    }

    public function place_table($inactive_places){

        $places_buff=explode(',', $inactive_places);
        $places=array();
        foreach($places_buff as $place){
            $places[$place]=1;
        }

        $places_table='<div class="places">';
        for($i=1,$br=0;$i<=53;$i++){
            if(($i==2 || $br==3) && $i<50){
                if(isset($places[$i])) $places_table.='<div class="reserved" onclick="select_place(this,'.$i.');" style="margin-right:55px;">'.$i.'</div>';
                else $places_table.='<div class="notreserved" onclick="select_place(this,'.$i.');" style="margin-right:55px;">'.$i.'</div>';
                $br=0;
            }else{
                if(isset($places[$i])) $places_table.='<div class="reserved" onclick="select_place(this,'.$i.');">'.$i.'</div>';
                else $places_table.='<div class="notreserved" onclick="select_place(this,'.$i.');">'.$i.'</div>';
                $br++;
            }
        }
        $places_table.='</div>';
        return $places_table;
    }

    public function is_inactive($line_id, $place, $date){
        $act=new Active_place();
        $inactive=$act->find(array(all=>'',where=>"line_id=$line_id AND from_date<='$date' AND to_date>='$date'"));
        if(isset($inactive[0])){
            $places=explode(',', $inactive[0]->inactive_places);
            foreach($places as $one_inactive_place){
                if($place==$one_inactive_place){
                    return true;
                }
            }
        }
        return false;
    }

    public function save(array $elements){
        $this->line_id=$elements[line_id];
        $this->inactive_places=$elements[inactive_places];
        $this->from_date=$elements[from_date];
        $this->to_date=$elements[to_date];

        $act=new Active_place();
        $act->create($this);

        header('location:./active_places.php');
    }
}
?>
