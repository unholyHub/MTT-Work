<?php
/* 
 * 
 */
include_once 'gui.php';
include_once './models/Promotion.php';
include_once './controllers/Sublines.php';
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
            <div  class="blockDivCenterButton">
                        <a href="./promotion_form.php"><input type="submit" class="buttonStyle" value="Добави промоция"></a>
                    </div>
                <div style="border:1px solid black" class="sectionDiv groupDiv">
                    ';
        $p=new Promotion();
        $point=new Line_point();
        $city=new City();
        $subln=new Subline();
        $promos=$p->find(array(all=>1));

        foreach($promos as $promo){
            $subline=$subln->find(array(id=>$promo->subline_id));
            $this->page_contents.='
                <div class="clearfix divBox" >
                    <div  class="colDiv blockDivPromotion">
                        <span class="GoAndBackStyle">Дестинация:</span>
                        '.$city->city($point->find(array(id=>$subline->from_point_id))->city_id,'bg').' - '.$city->city($point->find(array(id=>$subline->to_point_id))->city_id,'bg').'
                    </div>
                    <div class="colDiv blockDivPromotion">
                        <span class="GoAndBackStyle">Процент:</span>
                        '.$promo->promo_percent.'
                    </div>
                    <div class="colDiv blockDivPromotion">
                        <span class="GoAndBackStyle">Изтича на:</span>
                        '.date('d.m.Y',strtotime($promo->expires)).'
                    </div>
                    <div class="colDiv blockDivFullLine">
                        <a href="./promotion_form.php?id='.$promo->id.'"><input class="buttonStyle" type="button" value="Редактирай"></a><a href="'.$_SERVER[PHP_SELF].'?did='.$promo->id.'"><input class="buttonStyle" type="button" value="Изтрий"></a>
                    </div>
                </div>';
        }
        $this->page_contents.='</div>';
        return $this->show_page();
    }

    public function promotion_form($err=null, $edit=false){
        Session::user_auth();
        $sublines=new Sublines();
        $this->page_contents='
                '.$this->errorOut($err).'
                <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    <table>
                    <tr>
                        <td>
                            <label>Дестинация:</label>
                        </td>
                        <td style="text-align:right">
                            <select class="citiesSelect" name="promotion[subline_id]">
                                '.$sublines->options_for_select($this->subline_id).'
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Процент:</label>
                        </td>
                        <td style="text-align:right">
                            <input type="text" name="promotion[promo_percent]" value="'.$this->promo_percent.'" size="4" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Дата на изтичане:</label>
                        </td>
                        <td style="text-align:right">
                            <input readonly="true" class="datepicker" type="text" id="_expires" name="promotion[expires]" value="'.$this->expires.'" onclick=""/>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:right" colspan="2">
                            <input class="buttonStyle" id="promotion_save" name="promotion_save" type="submit" value="'.($edit?'Обнови':'Добави').'" />
                        </td>
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
}
?>
