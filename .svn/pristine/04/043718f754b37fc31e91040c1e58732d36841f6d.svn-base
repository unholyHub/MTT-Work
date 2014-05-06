<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Lines
 *
 * @author root
 */
include_once 'gui.php';
include_once './models/Line.php';
include_once './controllers/Sublines.php';
include_once './controllers/Travel_days.php';

class Lines extends gui{
    public $id;
    public $line_name;
    public $from_city_id;
    public $to_city_id;
    public $from_date;
    public $to_date;

    function __construct() {
    }

    function Lines($id, $line_name, $from_city_id, $to_city_id, $from_date, $to_date) {
        $this->id = $id;
        $this->line_name = $line_name;
        $this->from_city_id = $from_city_id;
        $this->to_city_id = $to_city_id;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function create(){
        $line=new Line();
        return $line->insert($this);
    }

    public function update_name(){
        $line=new Line();
        $line->update($this->id, $this->line_name, $this->from_city_id, $this->to_city_id, $this->from_date, $this->to_date);
    }

    public function show_all(){
        Session::user_auth(); //is user logged in
        $lineModel=new Line();
        $lines=$lineModel->find(array(all=>''));
        $sublines=new Sublines();
        $tdays=new Travel_days();
        $this->page_contents='<div>               
                 <div class="blockDivCenterButton"><a href="./addline.php"><button class="buttonStyle">Добави линия</button></a></div>';
        $line_number = 1;
        foreach($lines as $line){
            $this->page_contents.='
                <section>
                    <div class="all-lines-go-length clearfix">
                        <div class="forms float-left" >
                            <b>#'.$line_number.' '.$line->line_name.'</b>
                            <hr />
                            <div class="clearfix"> <b>Отиване </b></div>
                            <div class="clearfix">
                                <a href="./active_places.php?lid='.$line->id.'"><input class="buttonStyle" type="submit" value="Забранени места"></a>
                                <a href="./edit_line_points.php?line='.$line->id.'"><input class="buttonStyle" type="submit" value="Редактирай"></a>
                                <a href="./deleteline.php?line='.$line->id.'" onclick="return confirm(\'Сигурни ли сте че искате да изтриете тази линия?\');"><input class="buttonStyle" type="submit" value="Изтрий"></a>
                            </div>
                        </div>
                        <div  class="float-right clearfix">'.$tdays->travel_days_form($line->id, false,false).'</div>
                   </div>
                   
                        <div>'.$sublines->price_table_new($line->id, false,false).'</div>
                  
                         <div class="formsBack"><b> Връщане </b></div>
                     <div>'.$tdays->travel_days_form($line->id, true,false).'</div>
                   
                        <div>'.$sublines->price_table_new($line->id, true, false).'</div>
                           
                        </section>
                        <hr style="height: 5px;background-color:gray"/>
                        ';
            $line_number++;
        }
        $this->page_contents.='</div>';
        
        return $this->show_page();
    }

}
?>
