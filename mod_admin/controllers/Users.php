<?php
/* 
 * 
 */

include_once 'gui.php';
include_once './models/User.php';
include_once './controllers/User_accesses.php';

class Users extends gui{
    public $id;
    public $user;
    public $passwd;
    public $access;

    function __construct() {
    }

    function Users($id, $user, $passwd, $access) {
        $this->id = $id;
        $this->user = $user;
        $this->passwd = $passwd;
        $this->access = $access;
    }

    public function save($elements){
        $user=new User();
        $status=$user->adduser($elements[user], $elements[passwd], $elements[confirm_passwd]);

        $this->user=$elements[user];
        if(is_array($status)){
            return $this->adduser_form($status);
        }else{
            header('location: ./user_access.php?user_id='.$status);
        }
    }

    public function update($elements){
        $user=new User();
        $status=$user->update($elements[id], $elements[user], $elements[passwd], $elements[confirm_passwd]);

        if(is_array($status)){
            $this->user=$elements[user];
            return $this->edit_user($status);
        }else{
            header('location: ./listusers.php');
        }
    }

    public function destroy($id){
        $user=new User();
        $user->delete($id);
        header('location: ./listusers.php');
    }

    public function adduser_form($err=''){
        Session::user_auth();//is user logged in

        $this->page_contents='
                '.$this->errorOut($err).'
                <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    <table class="forms" align="center">
                        
                        <tr><td>
                            Потребителско име:
                        </td><td>
                            <input type="text" name="user[user]" value="'.$this->user.'" />
                        </td></tr>
                        <tr><td>
                            Парола:
                        </td><td>
                            <input type="password" name="user[passwd]" value="" />
                        </td></tr>
                        <tr><td>
                            Потвърди паролата:
                        </td><td>
                            <input type="password" name="user[confirm_passwd]" value="" />
                        </td></tr>
                        <tr><td colspan="2" align="right">
                            <input type="submit" class="buttonStyle" value="Добави" name="add_user" />
                        </td></tr>
                    </table>
                </form>';

        return $this->show_page();
    }


    public function list_users(){
        Session::user_auth();//is user logged in
        $user=new User();
        $users=$user->find(array(all=>''));
        $this->page_contents='
                <div class="blockDivCenterButton">
                <a href="./adduser.php"><button class="buttonStyle">Добави потребител</button></a> 
                </div>
                <table class="usersTable">
                 
                <thead>               
                
                <tr>
                    <th>Потребителско име</th>
                    <th>Настройки</th>
                </tr>
                </thead>';
        foreach($users as $user){
            $this->page_contents.='
                <tbody>
                    <tr border="1">
                        <td>'.$user->user.'</td>
                        <td>
                            <a href="./edituser.php?id='.$user->id.'"><button class="buttonStyle">Смени парола</button></a>
                            <a href="./user_access.php?user_id='.$user->id.'"><button class="buttonStyle">Права на достъп</button></a>
                            <a href="?del_id='.$user->id.'" onclick="return confirm(\'Сигурни ли сте че искате да изтриете потребителя?\');"><button class="buttonStyle">Изтрий</button></a>
                        </td>
                    </tr>
                    </tbody>';
        }
        $this->page_contents.='</table>';

        return $this->show_page();
    }

    public function edit_user($err=''){
        Session::user_auth();//is user logged in
         $this->page_contents='
                    '.$this->errorOut($err).'
                <form action="'.$_SERVER[PHP_SELF].'" method="POST">
                    <input type="hidden" name="user[id]" value="'.$this->id.'" />
                    <table class="forms" align="center">
                        
                        <tr><td>
                            Потребителско име:
                        </td><td>
                            <input type="text" name="user[user]" value="'.$this->user.'" />
                        </td></tr>
                        <tr><td>
                            Нова парола:
                        </td><td>
                            <input type="password" name="user[passwd]" value="" />
                        </td></tr>
                        <tr><td>
                            Потвърди паролата:
                        </td><td>
                            <input type="password" name="user[confirm_passwd]" value="" />
                        </td></tr>
                        <tr><td colspan="2" align="right">
                            <input class="buttonStyle" type="submit" value="Обнови" name="update_user" />
                        </td></tr>
                    </table>
                </form>';

        return $this->show_page();
    }    
}
?>
