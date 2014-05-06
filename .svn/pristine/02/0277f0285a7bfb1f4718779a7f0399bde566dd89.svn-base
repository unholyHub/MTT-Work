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

    public function list_discounts(){
        Session::user_auth();

        $elements='';
        $discount=new Discount();
        $discounts=$discount->find(array(all=>''));
        foreach($discounts as $disc){
            $elements.='<tr>
                            <td>'.$disc->name_bg.' / '.$disc->name_en.'</td>
                            <td>'.$disc->description_bg.' / '.$disc->description_en.'</td>
                            <td align="right">'.$disc->discount.'</td>
                            <td>'.($disc->discount_type==0?'Процент':'Сума').'</td>
                            <td>
                                <a href="./adddiscount.php?id='.$disc->id.'">Редактирай</a> |
                                <a href="./discountlist.php?del_id='.$disc->id.'" onclick="return confirm(\'Сигурни ку сте че искате да изтриете този запис?\');">Изтрий</a>
                            </td>
                       </tr>';
        }
        $this->page_contents='
                <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    <table align="center" class="forms" width="800">
                        <tr>
                            <td colspan="5" align="right"><a href="./adddiscount.php">Добави намаление</a></td>
                        </tr>
                        <tr>
                            <th>Име</th>
                            <th>Описание</th>
                            <th>Намаление</th>
                            <th>Тип</th>
                        </tr>
                        '.$elements.'
                    </table>
                </form>';

        return $this->show_page();
        
    }

    public function add_discount_form($errors='',$id=null){
        Session::user_auth();
        if($id!=null){
            $discount=new Discount();
            $this->o_clone($discount->find(array(id=>$id)));
        }
        
        $this->page_contents='
                '.$this->errorOut($errors).'
                <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    '.(($id!=null)?'<input type="hidden" name="discount[id]" value="'.$this->id.'" />':'').'
                    <table class="forms" align="center">
                        <tr>
                            <td valign="top">Име:</td>
                            <td>BG: <input type="text" name="discount[name_bg]" value="'.$this->name_bg.'" /></td>
                            <td>EN: <input type="text" name="discount[name_en]" value="'.$this->name_en.'" /></td>
                        </tr>
                        <tr>
                            <td  valign="top">Описание:</td>
                            <td>BG:<br /> <textarea name="discount[description_bg]" rows="4" cols="20">'.$this->description_bg.'</textarea></td>
                            <td>EN:<br /><textarea name="discount[description_en]" rows="4" cols="20">'.$this->description_en.'</textarea></td>
                        </tr>
                        <tr>
                            <td  valign="top">Тип намаление:</td>
                            <td colspan=2>
                                <input type="radio" name="discount[discount_type]" value="0" '.($this->discount_type==0?'checked="checked"':'').' /> Намаление в %
                                <input type="radio" name="discount[discount_type]" value="1" '.($this->discount_type==1?'checked="checked"':'').' /> Намаление със сума
                            </td>
                        </tr>
                        <tr>
                            <td  valign="top">Намаление:</td>
                            <td colspan=2><input type="text" name="discount[discount]" value="'.$this->discount.'" size="4" /></td>
                        </tr>
                        <tr>
                            <td colspan="3" align="right"><input type="submit" value="Добави" name="add_discount" /></td>
                        </tr>
                    </table>
                </form>';

        return $this->show_page();
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

    public function save($elements){
        Session::user_auth();
        $this->name_bg=$elements[name_bg];
        $this->name_en=$elements[name_en];
        $this->description_bg=$elements[description_bg];
        $this->description_en=$elements[description_en];
        $this->discount=$elements[discount];
        $this->discount_type=$elements[discount_type];

        $discount=new Discount();
        if(isset($elements[id])){
            $this->id=(int)$elements[id];
            $status=$discount->update($this);
            if(is_array($status)) return $this->add_discount_form($status,$this->id);
        }else{
            $status=$discount->insert($this);
            if(is_array($status)) return $this->add_discount_form($status);
        }
        header('location: ./discountlist.php');
    }

    public function destroy($id){
        Session::user_auth();
        $discount=new Discount();
        $discount->delete($id);
        header('location: ./discountlist.php');
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
