<?php
/*
 */

include_once 'gui.php';
include_once './models/Saved_place.php';
include_once './controllers/Users.php';
include_once './controllers/Sublines.php';

class Saved_places extends gui{
    public $id;
    public $subline_id;
    public $user_id;
    public $places;

    function __construct() {/*data is extracted from the model*/}

    public function saved_places_form($err=null){
        Session::user_auth();
        $subln=new Sublines();
        $usr=new User();
        $users=$usr->find(array(all=>1));
        $options_for_users='';
        foreach($users as $user){
            if($user->id == $this->user_id) $options_for_users.='<option value="'.$user->id.'" selected="selected">'.$user->user.'</option>';
            else $options_for_users.='<option value="'.$user->id.'">'.$user->user.'</option>';
        }
        $this->page_contents='<form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    '.$this->errorOut($err).'
                    <table style="background:white" class="usersTable">
                    
                    <tr>
                        <td style="border-bottom:none">
                            <select class="citiesSelect" name="saved_places[subline_id]">
                                '.$subln->options_for_select($this->subline_id).'
                            </select>
                            <select name="saved_places[user_id]">
                                '.$options_for_users.'
                            </select>
                              
                            '.($this->id?'<input type="hidden" name="saved_places[id]" value="'.$this->id.'" />':'').'
                           
                        </td>
                        
                   </tr>
                   <tr>
                        <td style="border-top:none">
                            <input id="saved_places" type="text" name="saved_places[places]" value="'.$this->places.'" />
                            <input type="submit" class="buttonStyle"  value="Запази" name="add_saved_places" />
                        </td>
                   </tr>
                    <tr>
                        <td>'.$this->place_table($this->places).'</td>
                    </tr>
                    
                  </table>
                  </form>';

        return $this->show_page();
    }

    public function place_table($saved_places){
        Session::user_auth();
        $saved_places=str_ireplace('{', '', $saved_places);
        $saved_places=str_ireplace('}', '', $saved_places);
        $places_buff=explode(',', $saved_places);
        $places=array();
        foreach($places_buff as $place){
            $places[$place]=1;
        }

        $places_table='<div class="places">';
        for($i=1,$br=0;$i<=57;$i++){
            if(($i==2 || $br==3) && $i<54){
                if(isset($places[$i])) $places_table.='<div class="reserved" onclick="save_place(this,'.$i.');" style="margin-right:55px;">'.$i.'</div>';
                else $places_table.='<div class="notreserved" onclick="save_place(this,'.$i.');" style="margin-right:55px;">'.$i.'</div>';
                $br=0;
            }else{
                if(isset($places[$i])) $places_table.='<div class="reserved" onclick="save_place(this,'.$i.');">'.$i.'</div>';
                else $places_table.='<div class="notreserved" onclick="save_place(this,'.$i.');">'.$i.'</div>';
                $br++;
            }
        }
        $places_table.='</div>';
        return $places_table;
    }

    public function list_saved_places(){
        Session::user_auth();
        $usr=new User();
        $subln=new Subline();
        $point=new Line_point();
        $city=new City();
        $line=new Line();
        $sp=new Saved_place();
        $saved_pls=$sp->find(array(all=>1));
        $this->page_contents='
                        <div class="blockDivCenterButton">
                            <a href="./saved_places.php"><input class="buttonStyle" type="button" value="Запази места"></а>
                        </div>    
                        <div style="border:1px solid black" class="sectionDiv groupDiv">
                    ';
        foreach($saved_pls as $place){
            $user=$usr->find(array(id=>$place->user_id));
            $subline=$subln->find(array(id=>$place->subline_id));
            $place->places=str_ireplace('{', '', $place->places);
            $place->places=str_ireplace('}', '', $place->places);
            $selected_line=$line->find(array(id=>$subline->line_id));
            $this->page_contents.=
                '<div class="clearfix divBox" >
                    <div class="colDiv blockDivSavedPlaces">
                        <span class="GoAndBackStyle">Подлиния:</span>
                        '.$city->city($point->find(array(id=>$subline->from_point_id))->city_id, 'bg').' - '.$city->city($point->find(array(id=>$subline->to_point_id))->city_id, 'bg').'
                    </div>
                    <div class="colDiv blockDivSavedPlaces">
                        <span class="GoAndBackStyle">Линия:</span>
                        '.$selected_line->id.' '.$selected_line->line_name.'
                    </div>
                    <div class="colDiv blockDivSavedPlaces">
                        <span class="GoAndBackStyle">Потребител:</span>'.$user->user.'
                    </div>
                    <div class="colDiv blockDivSavedPlaces">
                        <span class="GoAndBackStyle">Места:</span>
                        '.$place->places.'
                    </div>
                    <div class="colDiv blockDivFullLine">
                        <a href="./saved_places.php?spid='.$place->id.'"><input class="buttonStyle" type="button" value="Редактирай"></a>   
                        <a onclick="return confirm(\'Сигурни ли сте?\');" href="'.$_SESSION[PHP_SELF].'?del_spid='.$place->id.'"><input class="buttonStyle" type="button" value="Изтрий"></a>
                    </div>
                  </div> ';
        }
        $this->page_contents.='</div>';
        return $this->show_page();
    }

    public function save(array $elements){
        Session::user_auth();
        $this->id=$elements[id];
        $this->subline_id=$elements[subline_id];
        $this->user_id=$elements[user_id];
        $this->places=$elements[places];

        $sp=new Saved_place();
        $err=$sp->create($this);
        if(is_array($err))
            return $this->saved_places_form($err);
        else
            header('location: ./saved_places_list.php');
    }

    public function destroy($id){
        Session::user_auth();
        $sp=new Saved_place();
        $sp->delete($id);
        header('location: ./saved_places_list.php');
    }

}
?>
