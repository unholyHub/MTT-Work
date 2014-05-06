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
