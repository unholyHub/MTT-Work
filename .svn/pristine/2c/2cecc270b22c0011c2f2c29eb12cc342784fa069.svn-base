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
        $this->page_contents='
            <div class="blockDivCenterButton"><a href="./add_currencies.php"><input class="buttonStyle" type="submit" value="Добави валута"></a></div>
              <table class="addCenter usersTable" >
                <tr>
                    <th>Валута</th>
                    <th>Индекс</th>
                    <th>Настройки</th>
                </tr>';
        foreach($currencies as $currency){
            $this->page_contents.='<tr>
                        <td>'.$currency->currency.'</td>
                        <td>'.$currency->index.'</td>
                        <td>
                            <a href="./edit_currencies.php?cid='.$currency->id.'"><input class="buttonStyle" type="button" value="Редактирай"></a>
                            <a href="'.$_SERVER[PHP_SELF].'?dcid='.$currency->id.'"><input class="buttonStyle" type="button" value="Изтрий"></a>
                        </td>
                    </tr>';
        }

        $this->page_contents.='</table>';
        return $this->show_page();
    }

    public function add_currency($errors=null){
        Session::user_auth();
        $this->page_contents=$this->errorOut($errors).'
                <table align="center" style="background-color: white;">
                    <tr>
                        <td>
                            <form action="'.$_SERVER[PHP_SELF].'?cid='.$this->id.'" method="POST">
                             <table>
                                <tr>
                                    <td>
                                        <label for="currencies[currency]">
                                            Код на валутата:
                                        </label>
                                    </td>
                                    <td >  
                                        <input class="currenciesInput" type="text" id="currencies[currency]" name="currencies[currency]" value="'.$this->currency.'" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="currencies[index]">
                                            Индекс:
                                        </label>
                                    </td>
                                    <td>
                                        <input class="currenciesInput" id="currencies[index]"  type="text" name="currencies[index]" value="'.$this->index.'" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: right">
                                        <input class="buttonStyle" type="submit" value="'.($this->id?'Обнови':'Добави').'" name="add_currency" />
                                    </td>
                                </tr>
                            </table>
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
