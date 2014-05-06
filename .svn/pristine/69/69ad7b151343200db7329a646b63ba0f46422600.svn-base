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
            $elements.='
                <div class="clearfix divBox" >
                    <div  class="colDivDiscount blockDivDiscount">                    
                        <div>
                            <span class="GoAndBackStyle">Име:</span>
                            '.$disc->name_bg.' / '.$disc->name_en.'
                        </div>
                    </div>
                    <div class="colDivDiscount blockDivDiscount">
                        <div>
                            <span class="GoAndBackStyle">Описание:</span>
                            '.$disc->description_bg.' / '.$disc->description_en.'
                        </div>
                    </div>
                    <div class="colDivDiscount blockDivDiscount">
                        <div>
                            <span class="GoAndBackStyle">Намаление:</span>
                            '.$disc->discount.'
                        </div>
                    </div>
                    <div class="colDivDiscount blockDivDiscount">
                        <div>
                            <span class="GoAndBackStyle">Tип:</span>
                            '.($disc->discount_type==0?'Процент':'Сума').'
                        </div>
                    </div>
                    <div  class="colDivDiscount blockDivFullLine">
                         <a href="./adddiscount.php?id='.$disc->id.'" ><input class="buttonStyle" type="button" value="Редактирай"></a>
                         <a href="./discountlist.php?del_id='.$disc->id.'" onclick="return confirm(\'Сигурни ли сте че искате да изтриете този запис?\');" class="buttonStyle">Изтрий</a>
                    </div>
                 </div>';
        }
        $this->page_contents='
                <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    <div class="blockDivCenterButton ">
                         <a href="./adddiscount.php"><input type="button" class="buttonStyle" value="Добави намаление"></a>
                    </div >
                    <div style="border:1px solid black" class="sectionDiv groupDiv">
                    
                    <div class=" " >
                        '.$elements.'
                            </div>
                    </div>
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
                   <div class="sectionDivDiscount  groupDiv">
                    <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    '.(($id!=null)?'<input type="hidden" name="discount[id]" value="'.$this->id.'" />':'').'
                    <div class="colDivDiscount  blockDivDiscountAdd">

                            <label for="discount[name_bg]">Име</label>
                            <input id="discount[name_bg]" type="text" name="discount[name_bg]" value="'.$this->name_bg.'" />
                            <br/>
                            <label for="discount[description_bg]">Описание</label>
                            <textarea id="discount[description_bg]" name="discount[description_bg]">'.$this->description_bg.'</textarea>

                    </div>
                    <div class="colDivDiscount  blockDivDiscountAdd">
                            <label for="discount[name_en]">Name</label>
     
                            <input id="discount[name_en]" type="text" name="discount[name_en]" value="'.$this->name_en.'" />
                                <br/>
                            <label>Description</label>
                             <textarea name="discount[description_en]" >'.$this->description_en.'</textarea> 
  
                    </div>
                    <div class="discountLeft blockDivDiscountAdd"></div>
                       
                       <div>
                            <label class="radioLeft">Тип на намаление</label>
                            <label for="discount_percent" class="radioLeft"> 
                            <input id="discount_percent" type="radio" name="discount[discount_type]" value="0" '.($this->discount_type==0?'checked="checked"':'').' />
                                Намаление в %</label>
                            <label for="discount_sum" class="radioLeft"> 
                            <input type="radio" id="discount_sum" name="discount[discount_type]" value="1" '.($this->discount_type==1?'checked="checked"':'').' />
                                Намаление със сума</label>
                       </div>
                       <div class="blockDivDiscountAdd">
                            <label for="discount[discount]" >Намаление:</label>
                            <input id="discount[discount]" type="text" name="discount[discount]" value="'.$this->discount.'" />
                       </div>
                       <div class="colDivDiscount  blockDivFullLine">
                        <input class="buttonStyle" type="submit" value="Добави" name="add_discount" />
                      </div>
                    </form>
                </div>';

        return $this->show_page();
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


}
?>
