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

    static function is_place_saved($subline_id,$place){
        $sp=new Saved_place();
        if(Session::logged_in()) $places=$sp->find(array(all=>1,where=>"subline_id=$subline_id AND places LIKE '%{{$place}}%' AND user_id <> ".Session::current_user()->id));
        else $places=$sp->find(array(all=>1,where=>"subline_id=$subline_id AND places LIKE '%{{$place}}%'"));
        if(sizeof($places)>0) return true;
        else return false;
    }

    static function get_saved_places($subline_id){
        $sp=new Saved_place();
        if(Session::logged_in()){
            $places=$sp->find(array(all=>1,where=>"subline_id=$subline_id AND user_id <> ".Session::current_user()->id));
        }else{
            $places=$sp->find(array(all=>1,where=>"subline_id=$subline_id"));
        }

        $saved_places=array();

        foreach($places as $splace){
            $splace->places=str_ireplace('{', '', $splace->places);
            $splace->places=str_ireplace('}', '', $splace->places);
            $mplaces=explode(',',$splace->places);
            foreach($mplaces as $oplace){
                $saved_places[]=$oplace;
            }
        }

        return $saved_places;
    }

    static function is_place_saved_for_update($subline_id,$place){
        $sp=new Saved_place();
        if(Session::logged_in()){
            if(Session::access_is_allowed(administration_access)) return false;//the administrator can edit everything

            $places=$sp->find(array(all=>1,where=>"subline_id=$subline_id AND places LIKE '%{{$place}}%' AND user_id <> ".Session::current_user()->id));
        }
        else $places=$sp->find(array(all=>1,where=>"subline_id=$subline_id AND places LIKE '%{{$place}}%'"));
        if(sizeof($places)>0) return true;
        else return false;
    }

}
?>
