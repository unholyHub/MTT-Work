<?php
/* 
 * 
 */

include_once 'gui.php';
include_once './models/City.php';

class Cities extends gui{
    public $ID;
    public $lang;
    public $ID_city;
    public $city;

    function __construct() {
    }

    function Cities($ID, $lang, $ID_city, $city) {
        $this->ID = $ID;
        $this->lang = $lang;
        $this->ID_city = $ID_city;
        $this->city = $city;
    }

    public function city_form($city='',$errors=''){
        Session::user_auth();//is user logged in
        $this->page_contents='
                '.$this->errorOut($errors).'
                <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    <table class="forms" align="center">
                        <tr>
                            <td>Име[Български]: </td>
                            <td><input type="text" name="city[bg]" value="'.$city[bg].'" /></td>
                            <td>Име[Английски]</td>
                            <td><input type="text" name="city[en]" value="'.$city[en].'" /></td>
                            <td><input type="submit" value="Запази" name="save_city" /></td>
                        </tr>
                    </table>
                </form>';

        return $this->page_contents;
    }

    public function edit_city($id, $errors=''){
        Session::user_auth();//is user logged in
        $city=new City();
        $cities=$city->find(array(id=>$id));
        $this->page_contents='
            '.$this->errorOut($errors).'
            <form action="'.$_SERVER[PHP_SELF].'?id='.$id.'" method="POST">
                <table class="forms" align="center">
                    <tr>
                        <td>Име:</td>
                        <td><input type="text" name="city[name]" value="'.$cities->city.'" /></td>
                        <td><input type="submit" value="Запази" name="save_city" /></td>
                    </tr>
                </table>
            </form>
            ';

        return $this->show_page();
    }

    public function list_cities($elements='',$errors=''){
        Session::user_auth();//is user logged in
        $city=new City();
        $cities=$city->find(array(all=>'',order=>'ID_city ASC'));

        $this->page_contents=$this->city_form($elements,$errors);
        $this->page_contents.='<table class="forms" align="center" width="500">';
        foreach($cities as $c){
                    $this->page_contents.='<tr>
                                <td>'.$c->city.'</td>
                                <td>'.$c->lang.'</td>
                                <td><a href="./editcity.php?id='.$c->ID.'">Редактирай</a></td>
                            </tr>';
        }
        $this->page_contents.='</table>';
        return $this->show_page();
    }

    public function save($elements){
        $city=new City();
        $cc=$city->find(array(all=>'',order=>'ID_city DESC',limit=>1));
        $errors=$this->validate_save($elements);
        if(sizeof($errors)>0){
            return $this->list_cities($elements,$errors);
        }else{

            if(isset($cc[0])) $count=$cc[0]->ID_city+1;
            else $count=1;

            foreach($elements as $key=>$val) $city->insert($key, $count, $val);
            return '';
        }
    }

    public function update($id,$city){
        $oCity=new City();
        $status=$oCity->update($id, $city);
        if(is_array($status)){
            return $this->edit_city($id, $status);
        }else{
            header('location: ./cities.php');
        }
    }

    private function validate_save($elements){
        $errors=array();
        foreach($elements as $lang=>$city){
            if(strlen($city)==0) $errors[0]='Моля въведете имената на градовете';
        }
        return $errors;
    }
}
?>
