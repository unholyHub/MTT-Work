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
                if(isset($uai[0]->{$access_point})) {
                    return $uai[0]->{$access_point};
                } else {
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    static function user_auth(){
        if(!isset($_SESSION['user_id'])){
            header('location:./login.php');
        }
    }

    static function logged_in(){
        if(!isset($_SESSION['user_id'])) return false;
        else return true;
    }

    static function clear_session(){
        unset($_SESSION[user_id]);
    }

    public function login_form($err=''){
        $this->template_name='agents';
        $this->page_contents='
                '.$this->errorOut($err).'
                <form action="'.$_SERVER[PHP_SELF].'" method="post">
                    <table class="forms" align="center">
                        <tr>
                            <td>'.locales::$text[login_username].'</td>
                            <td><input type="text" name="login[username]" value="'.$this->user->user.'" /></td>
                        </tr>
                        <tr>
                            <td>'.locales::$text[login_password].'</td>
                            <td><input type="password" name="login[passwd]" value="" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right"><input class="invoice" type="submit" value="'.locales::$text[login_log].'" name="log_user" /></td>
                        </tr>
                    </table>
                </form>';

        return $this->show_page();
    }

    public function create_session($elements){
        $user=new User();
        $elements=array_map('mysql_real_escape_string',$elements);
        $login=$user->find(array(all=>'',where=>"user='$elements[username]' AND passwd='$elements[passwd]' AND access>0"));
        if(isset($login[0])){

            $_SESSION['user_id']=$login[0]->id;
            
            header("location: ./agents.php");
            return '';
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
