<?php
/* 
 * 
 */

include_once 'gui.php';
include_once './models/Discount.php';

class Discounts extends gui{
    public $id;
    public $name_bg;
    public $name_en;
    public $description_bg;
    public $description_en;
    public $discount;
    public $discount_type;

    function __construct() {
    }

    function Discounts($id, $name_bg, $name_en, $description_bg, $description_en, $discount, $discount_type) {
        $this->id = $id;
        $this->name_bg = $name_bg;
        $this->name_en = $name_en;
        $this->description_bg = $description_bg;
        $this->description_en = $description_en;
        $this->discount = $discount;
        $this->discount_type = $discount_type;
    }

    function o_clone(Discounts $object){
        $this->id = $object->id;
        $this->name_bg = $object->name_bg;
        $this->name_en = $object->name_en;
        $this->description_bg = $object->description_bg;
        $this->description_en = $object->description_en;
        $this->discount = $object->discount;
        $this->discount_type = $object->discount_type;
    }

    public function active_discounts(){
        $discountH=new Discount();
        $discounts=$discountH->find(array(all=>1));
        $html='<div class="promotions">
                <div class="promo_title">'.locales::$text[discount_types].'</div>';
        foreach($discounts as $discount){
            $html.='<div class="promo_link">- '.(locales::$current_locale=='bg'?$discount->name_bg: $discount->name_en).'</div>';
        }
        $html.='</div>';

        return $html;
    }
    
    public function calculate_discount($sum) {
        $new_price = 0;
        if ($this->discount_type == 0) {
            $new_price = (double) $sum - ((double) $sum * (double) $this->discount / 100);
        } elseif ($this->discount_type == 1) {
            $new_price = (double) $sum - (double) $this->discount;
        }
        return $new_price;
    }
}
?>
