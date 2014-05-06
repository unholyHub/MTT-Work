<?php
/* 
 * controller gui controls the website's temlate
 */
ob_start();
session_start();
include_once dirname(dirname(__DIR__)).'/config/env.php';
include_once 'Session.php';
class gui {
    protected $template_name='default';
    protected $page_contents='PLEASE SET THE PAGE CONTENT PROPERTY';//{{page_contents}}
    private $html;

    function __construct() {
    }

    protected function show_page(){//generates the the html code of the page
        $this->html=file_get_contents('./templates/'.$this->template_name.'.html');

        $this->html=str_replace('{{menu}}', $this->menu(), $this->html);
        $this->html=str_replace('{{page_contents}}', $this->page_contents, $this->html);
        
        return $this->html;
    }

    protected function errorOut($errors){
        if(!is_array($errors)){
            $error_out='';
        }else{
            $error_out='<div class="errors">';
            $error_out.='<div>Има '.sizeof($errors).' грешки.</div>';
            $error_out.='<ul style="list-style:none">';
            foreach($errors as $error) $error_out.='<li>'.$error.'</li>';
            $error_out.='</ul>
                         </div>';
        }
        return $error_out;
    }

    private function menu(){
        return (Session::logged_in()?'
            <ul>
                <li><a href="./login.php?logout">Изход</a></li>':'<ul> <li><a href="./login.php">Вход</a></li>').'
                <li><a href="./alllines.php">Линии</a></li>
                <li><a href="./listusers.php">Потребители</a></li>
                <li><a href="./cities.php">Градове</a></li>
                <li><a href="./discountlist.php">Намаления</a></li>
                <li><a href="./list_promotions.php">Промoции</a></li>
                <li><a href="./currencies.php">Валути</a></li>
                <li><a href="./saved_places_list.php">Запазени места</a></li>
            </ul>';
    }
}
?>
