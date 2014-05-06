<?php
/* 
 * 
 */
include_once 'gui.php';
include_once './models/Promotion.php';
include_once './controllers/Sublines.php';
include_once './controllers/Line_points.php';
include_once './models/City.php';

class Promotions extends gui{
    public $id;
    public $subline_id;
    public $promo_percent;
    public $expires;

    function __construct() {
    }

    function Promotions($id, $subline_id, $promo_percent, $expires) {
        $this->id = $id;
        $this->subline_id = $subline_id;
        $this->promo_percent = $promo_percent;
        $this->expires = $expires;
    }

    public function lits_promotions(){
        Session::user_auth();
        $this->page_contents='
                <table class="forms" align="center" width="800">
                    <tr>
                        <td colspan="5" align="right"><a href="./promotion_form.php">Добави промоция</a></td>
                    </tr>
                    <tr>
                        <th>Дестинация</th>
                        <th>Процент</th>
                        <th>Изтича на</th>
                    </tr>';
        $p=new Promotion();
        $point=new Line_point();
        $city=new City();
        $subln=new Subline();
        $promos=$p->find(array(all=>1));

        foreach($promos as $promo){
            $subline=$subln->find(array(id=>$promo->subline_id));
            $this->page_contents.='
                    <tr>
                        <td>'.$city->city($point->find(array(id=>$subline->from_point_id))->city_id,'bg').' - '.$city->city($point->find(array(id=>$subline->to_point_id))->city_id,'bg').'</td>
                        <td>'.$promo->promo_percent.'</td>
                        <td>'.date('d.m.Y',strtotime($promo->expires)).'</td>
                        <td><a href="./promotion_form.php?id='.$promo->id.'">Редактирай</a> | <a href="'.$_SERVER[PHP_SELF].'?did='.$promo->id.'">Изтрий</a></td>
                    </tr>';
        }
        $this->page_contents.='</table>';
        return $this->show_page();
    }

    public function promotion_form($err=null, $edit=false){
        Session::user_auth();
        $sublines=new Sublines();
        $this->page_contents='
                '.$this->errorOut($err).'
                <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    <table align="center" class="froms">
                        <tr>
                            <td>Дестинация:</td>
                            <td><select name="promotion[subline_id]">
                                '.$sublines->options_for_select($this->subline_id).'
                             </select></td>
                            <td>Процент:</td>
                            <td><input type="text" name="promotion[promo_percent]" value="'.$this->promo_percent.'" size="4" /></td>
                            <td>Дата на изтичане:</td>
                            <td><input type="text" id="_expires" name="promotion[expires]" value="'.$this->expires.'" onclick="NewCssCal(\'_expires\',\'yyyymmdd\');" readonly="readonly"/></td>
                            <td><input name="promotion_save" type="submit" value="'.($edit?'Обнови':'Добави').'" /></td>
                        </tr>
                    </table>
                </form>';

        return $this->show_page();
    }

    public function save(array $elements){
        Session::user_auth();
        $this->subline_id=$elements[subline_id];
        $this->promo_percent=$elements[promo_percent];
        $this->expires=$elements[expires];

        $promo=new Promotion();
        $err=$promo->create($this);
        if(is_array($err)){
            return $this->promotion_form($err);
        }else{
            header('location: ./list_promotions.php');
        }
    }

    public function destroy($id){
        Session::user_auth();
        $promo=new Promotion();
        $promo->delete($id);
        header('location: ./list_promotions.php');
    }

    public function active_promotions(){
        $promo=new Promotion();
        $promotions=$promo->find(array(all=>1, where=>"expires>='".date('Y-m-d')."'"));
        $html='';
        if(sizeof($promotions)>0){
            $html.="<div class='promotions'>
                <div class='promo_title'>".locales::$text[promotion_text]."</div>";
            $subln=new Subline();
            $city=new City();
            $point=new Line_point();
            foreach($promotions as $promotion){
                $subline=$subln->find(array(id=>$promotion->subline_id));

                $html.='<div class="promo_link" ><a href="./?_subline='.$subline->id.'&_date='.date('Y-m-d',time()+(60*60*24)).'">'.$city->city($point->find(array(id=>$subline->from_point_id))->city_id, locales::$current_locale).' - '.$city->city($point->find(array(id=>$subline->to_point_id))->city_id, locales::$current_locale).'
                 - '.$promotion->promo_percent.' % </a></div>';
            }
            $html.='</div>';
        }

        return $html;
    }
}
?>
