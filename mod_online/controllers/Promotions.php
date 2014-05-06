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
