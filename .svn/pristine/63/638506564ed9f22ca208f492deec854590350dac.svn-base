<?php
/* 
 * controller gui controls the website's temlate
 */

ob_start();
session_start();
include_once dirname(dirname(__DIR__)).'/config/env.php';
include_once './locales/locales.php';
class gui {
    protected $template_name='default';
    protected $page_contents='PLEASE SET THE PAGE CONTENT PROPERTY';//{{page_contents}}
    private $html;

    function __construct() {
    }

    protected function show_page(){//generates the the html code of the page
        $this->html=file_get_contents('./templates/'.$this->template_name.'.html');

        //$this->html=str_replace('{{menu}}', $this->menu(), $this->html);
        $this->html=str_replace('{{page_contents}}', $this->page_contents, $this->html);
        //$this->html=str_replace('{{page_menu}}', $this->page_menu(), $this->html);
        //$this->html=str_replace('{{title}}', $this->title(), $this->html);
        //$this->html=str_replace('{{footer}}', locales::$text[footer], $this->html);
        //$this->html=str_replace('{{metatags}}', str_replace(' | ', ',', $this->title()), $this->html);
        //$this->html=str_replace('{{metadescription}}', str_replace(' | ', ',', $this->title()), $this->html);
        //$this->html=str_replace('{{QUERY_STRING}}', preg_replace('/\&lang=../is','',$_SERVER['QUERY_STRING']).'&', $this->html);
        return $this->html;
    }

    protected function errorOut($errors){
        if(!is_array($errors)){
            $error_out='';
        }else{
            $error_out='<div class="errors">';
            $error_out.='<div>'.locales::$text[there_are].' '.sizeof($errors).' '.locales::$text[errors].'.</div>';
            $error_out.='<ul>';
            foreach($errors as $error) $error_out.='<li>'.$error.'</li>';
            $error_out.='</ul>
                         </div>';
        }
        return $error_out;
    }

    private function title(){
        return locales::$text[title];
    }
    
    protected function render($template) {
        ob_start();
        include $template;
        $contents = ob_get_contents();
        ob_end_clean();
        
        $this->page_contents = $contents;
        echo $this->show_page();
    }
}
?>
