<?php
/* 
 * 
 */

include_once 'gui.php';
include_once './models/Currency.php';
class Currencies extends gui{
    public $id;
    public $currency;
    public $index;

    function __construct() {
        
    }

    function Currencies($id, $currency, $index) {
        $this->id = $id;
        $this->currency = $currency;
        $this->index = $index;
    }

    public function view_currencies(){
        Session::user_auth();
        
        $currency_model=new Currency();
        $currencies=$currency_model->find(array(all=>''));
        $this->page_contents='<table align="center" class="forms" width="500">
                <tr>
                    <td colspan="3" align="right"><a href="./add_currencies.php">Добави валута</a></td>
                </tr>
                <tr>
                    <th>Валута</th>
                    <th>Индекс</th>
                </tr>';
        foreach($currencies as $currency){
            $this->page_contents.='<tr>
                        <td>'.$currency->currency.'</td>
                        <td>'.$currency->index.'</td>
                        <td><a href="./edit_currencies.php?cid='.$currency->id.'">Редактирай</a> |
                        <a href="'.$_SERVER[PHP_SELF].'?dcid='.$currency->id.'">Изтрий</a></td>
                    </tr>';
        }

        $this->page_contents.='</table>';
        return $this->show_page();
    }

    public function add_currency($errors=null){
        Session::user_auth();
        $this->page_contents=$this->errorOut($errors).'
                <table align="center" class="forms">
                    <tr>
                        <td>
                            <form action="'.$_SERVER[PHP_SELF].'?cid='.$this->id.'" method="POST">
                                Код на валутата:
                                <input type="text" name="currencies[currency]" value="'.$this->currency.'" />
                                Индекс:
                                <input type="text" name="currencies[index]" value="'.$this->index.'" />
                                <input type="submit" value="'.($this->id?'Обнови':'Добави').'" name="add_currency" />
                            </form>
                        </td>
                    </tr>
                </table>';

        return $this->show_page();
    }

    public function convert($sum, $convert_to){
        $currency=new Currency();
        $c=$currency->find(array(all=>'',where=>"currency='$convert_to'"));
        
        return round($sum/$c[0]->index,2);
    }

    public function create(array $elements){
        Session::user_auth();
        $this->id=null;
        $this->currency=$elements[currency];
        $this->index=$elements[index];

        $new_currency=new Currency();
        $err=$new_currency->insert($this);
        if(is_array($err)){
            return $this->add_currency($err);
        }else{
            header('location: ./currencies.php');
        }
    }

    public function update(array $elements){
        Session::user_auth();
        $this->currency=$elements[currency];
        $this->index=$elements[index];

        $new_currency=new Currency();
        $err=$new_currency->update($this);
        if(is_array($err)){
            return $this->add_currency($err);
        }else{
            header('location: ./currencies.php');
        }
    }

    public function destroy($id){
        Session::user_auth();
        $currency=new Currency();
        $currency->delete($id);
        header('location: ./currencies.php');
    }
}
?>
