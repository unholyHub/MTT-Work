<?php
/* 
 * 
 */

include_once 'gui.php';
include_once './models/Travel_day.php';

class Travel_days extends gui{
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

    function o_clone($object){
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

    public function travel_days_form($line_id, $back=false, $layout=true){
        if($line_id!=null){
            $tday=new Travel_day();
            $tdays=$tday->find(array(all=>'',where=>'line_id='.$line_id.' AND direction='.($back?'1':'0')));
            if(sizeof($tdays)>0) $this->o_clone($tdays[0]);
        }

        $this->page_contents='
            <form action="./set_travel_days.php" method="POST">
                <input type="hidden" name="travel_day[line_id]" value="'.$line_id.'" />
                <input type="hidden" name="travel_day[direction]" value="'.($back?'1':'0').'" />
                <table align="center" class="forms" width="500">
                    <tr>
                        <th>Понеделник</th>
                        <th>Вторник</th>
                        <th>Сряда</th>
                        <th>Четвъртък</th>
                        <th>Петък</th>
                        <th>Събота</th>
                        <th>Неделя</th>
                    </tr>
                    <tr>
                        <td align="center"><input type="checkbox" name="travel_day[mon]" value="1" '.($this->mon==1?'checked="checked"':'').'/></td>
                        <td align="center"><input type="checkbox" name="travel_day[tue]" value="1" '.($this->tue==1?'checked="checked"':'').'/></td>
                        <td align="center"><input type="checkbox" name="travel_day[wed]" value="1" '.($this->wed==1?'checked="checked"':'').'/></td>
                        <td align="center"><input type="checkbox" name="travel_day[thu]" value="1" '.($this->thu==1?'checked="checked"':'').'/></td>
                        <td align="center"><input type="checkbox" name="travel_day[fri]" value="1" '.($this->fri==1?'checked="checked"':'').'/></td>
                        <td align="center"><input type="checkbox" name="travel_day[sat]" value="1" '.($this->sat==1?'checked="checked"':'').'/></td>
                        <td align="center"><input type="checkbox" name="travel_day[sun]" value="1" '.($this->sun==1?'checked="checked"':'').'/></td>
                    </tr>
                    <tr>
                        <td align="right" colspan="7"><input type="submit" value="'.($layout?'Следваща стъпка':'Обнови').'" name="set_days" /></td>
                    </tr>
                </table>
            </form>';
        if($layout){
            return $this->show_page();
        }else{
            return $this->page_contents;
        }
        
    }

    public function save($elements){
        $this->line_id = $elements[line_id];
        $this->direction = $elements[direction];
        $this->mon = $elements[mon];
        $this->tue = $elements[tue];
        $this->wed = $elements[wed];
        $this->thu = $elements[thu];
        $this->fri = $elements[fri];
        $this->sat = $elements[sat];
        $this->sun = $elements[sun];
        $tday=new Travel_day();
        $stat=$tday->create($this);
        if($stat==0){
            header('location: ./addsublines.php?line='.$this->line_id.($this->direction==1?'&back':''));
        }else{
            header('location: ./alllines.php');
        }
        
    }

    public function is_travelday($subline,$date,$direction){
        if(isset($_GET['twoway'])){
            if($subline->price_twoway <= 0) return false;
        }else{
            if($subline->price_oneway <=0 ) return false;
        }
        $tday=new Travel_day();
        $day=strtolower(date('D',strtotime($date)));
        $tdays = current($tday->find(array(all=>'',where=>'line_id='.$subline->line_id." AND direction=$direction", 'limit'=>1)));
        
        $day_permission = $subline->sublineDayExclusivePermissions($date,$direction);
        
        if($day_permission!=-1){
            $day_set = current(array_keys($day_permission));
            $tdays->{$day_set} = $day_permission[$day_set];
        }

        if(isset($_SESSION['user_id'])){
            if($tdays->{$day} == 1 /*&& strtotime($date)>=strtotime(date('Y-m-d'))*/){
                return true;
            }else{
                return false;
            }
        }else{
            if($tdays->{$day} == 1 && strtotime($date)>strtotime(date('Y-m-d'))){
                return true;
            }else{
                return false;
            }
        }
    }

}
?>
