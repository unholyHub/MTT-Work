<?php
/* 
 * 
 */
include_once 'gui.php';
include_once './models/User.php';
include_once './controllers/Users.php';
class Session extends gui{
    public $user;

    function __construct() {
    }

    static function current_user(){
        $user=new User();
        $current_user=$user->find(array(id=>$_SESSION['user_id']));
        return $current_user;
    }

    static function access_is_allowed($access_point){
        if(!isset($_SESSION['user_id'])){
            return false;
        }else{
            $ua=new User_access();
            $uai=$ua->find(array(all=>1,where=>"user_id=".Session::current_user()->id));
            if(isset($uai[0])){
                switch ($access_point){
                    case administration_access:
                        return $uai[0]->administration_access;
                        break;
                    case sell_access:
                        return $uai[0]->sell_access;
                        break;
                    case agent_report_access:
                        return $uai[0]->agent_report_access;
                        break;
                    case travel_list_report_access:
                        return $uai[0]->travel_list_report_access;
                        break;
                    case date2date_report_access:
                        return $uai[0]->date2date_report_access;
                        break;
                    case destination_report_access:
                        return $uai[0]->destination_report_access;
                        break;
                    case all_sales_access:
                        return $uai[0]->all_sales_access;
                        break;
                    case sale_edit_access:
                        return $uai[0]->sale_edit_access;
                        break;
                    case sale_delete_access:
                        return $uai[0]->sale_delete_access;
                        break;
                    case sale_return_access:
                        return $uai[0]->sale_return_access;
                        break;
                    case show_epay_sales_access:
                        return $uai[0]->show_epay_sales_access;
                        break;
                    case save_places_access:
                        return $uai[0]->save_places_access;
                        break;
                    case free_places_access:
                        return $uai[0]->free_places_access;
                        break;
                }
            }else{
                return false;
            }
        }
    }

    static function user_auth(){
        if(!isset($_SESSION['user_id'])){
            header('location:./login.php');
        }elseif(!Session::access_is_allowed(administration_access)){
            header('location:./login.php');
        }
    }

    static function logged_in(){
        if(!isset($_SESSION['user_id'])) return false;
        else return true;
    }

    public function login_form($err=''){
        $this->page_contents='
                '.$this->errorOut($err).'
                <form action="'.$_SERVER[PHP_SELF].'" method="post">
                    <table class="forms">
                        <tr>
                            <td>Потребителско име: </td>
                            <td class="float-right"><input type="text" name="login[username]" value="'.$this->user->user.'" /></td>
                        </tr>
                        <tr>
                            <td>Парола:</td>
                            <td class="float-right"><input type="password" name="login[passwd]" value="" /></td>
                            <td class="float-right">
                           
                            </td>
                        </tr>
                        <tr> 
                            <td colspan="2"> <input class="buttonStyle float-right" type="submit" value="Login" name="log_user" /></td>
                        </tr>
                    </table>
                </form>';

        return $this->show_page();
    }

    public function create_session($elements){
        $user=new User();
        $ua=new User_access();
        $elements=array_map('mysql_real_escape_string',$elements);
        $login=$user->find(array(all=>'',where=>"user='$elements[username]' AND passwd='$elements[passwd]'"));

        if(isset($login[0])){
            $uai=$ua->find(array(all=>1,where=>"user_id=".$login[0]->id));
            if(isset($uai[0]) && $uai[0]->administration_access==1){
                $_SESSION['user_id']=$login[0]->id;

                header("location: ./alllines.php");
                return '';
            }else{
                $this->user->user=$elements[username];
                $err[]='Нямате достъп.';
                return $this->login_form($err);
            }
        }else{
            $this->user->user=$elements[username];
            $err[]='Грешно потребителско име или парола.';
            return $this->login_form($err);
        }

    }

    public function destroy_session(){
        unset($_SESSION['user_id']);
        header('location: ./login.php');
    }
}
?>
