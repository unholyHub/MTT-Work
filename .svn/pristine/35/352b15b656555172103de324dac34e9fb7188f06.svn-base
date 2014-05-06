<?php
/* 
 * controller gui controls the website's temlate
 */

ob_start();
session_start();
include_once dirname(dirname(__DIR__)).'/config/env.php';
include_once './locales/locales.php';
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
        $this->html=str_replace('{{page_menu}}', $this->page_menu(), $this->html);
        $this->html=str_replace('{{title}}', $this->title(), $this->html);
        $this->html=str_replace('{{footer}}', locales::$text[footer], $this->html);
        $this->html=str_replace('{{metatags}}', str_replace(' | ', ',', $this->title()), $this->html);
        $this->html=str_replace('{{metadescription}}', str_replace(' | ', ',', $this->title()), $this->html);
        $this->html=str_replace('{{QUERY_STRING}}', preg_replace('/\&lang=../is','',$_SERVER['QUERY_STRING']).'&', $this->html);
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

    private function menu(){
        $qstr=preg_replace('/(^|\&)lang=.*?$/i', '', $_SERVER[QUERY_STRING]);
        return (Session::logged_in()?'<a href="./login.php?logout">'.locales::$text[menu_logout].'</a>':'<a href="./login.php">'.locales::$text[menu_login].'</a>').'
                <a href="./agents.php">'.locales::$text[menu_tickets].'</a>
                <a href="./list_reservations.php">'.locales::$text[menu_reservations].'</a>
                <a href="./reports.php">'.locales::$text[menu_reports].'</a>
                <div style="float:right; height: 30px;">
                    <a style="background:transparent; line-height:0;padding:0;margin:0; float:none; width:inherit;" href="'.(strlen($qstr)>0?'?'.$qstr.'&':'?').'lang=bg"><img src="./images/bg.png" /></a>
                    <a style="background:transparent; line-height:0;padding:0;margin:0; float:none; width:inherit;" href="'.(strlen($qstr)>0?'?'.$qstr.'&':'?').'lang=en"><img src="./images/en.png" /></a>
                </div>';
    }

    private function page_menu(){
        $qstr=preg_replace('/(^|\&)lang=.*?$/i', '', $_SERVER[QUERY_STRING]);
        return '<table width="100%">
                        <TR valign="top">
                            <TD colspan="5" height="20">
                                <img src="../images/strelka_big.gif" align="left">
                                <a href="../about.php"><i>'.locales::$text[menu_about].'</i></a>
                            </td>
                        </TR>
                        <TR valign="top">
                            <TD width="3%" height="20">&nbsp;</TD>
                            <TD colspan="4">
                                <img src="../images/strelka_big.gif" align="left">
                            <a href="../contacts.php"><i>'.locales::$text[menu_contacts].'</i></a></TD>
                        </TR>
                        <TR valign="top">
                            <TD width="3%" height="20">&nbsp;</TD>
                            <TD width="3%">&nbsp;</TD>
                            <TD colspan="3">
                                <img src="../images/strelka_big.gif" align="left">
                            <a href="./search.php"><i>'.locales::$text[menu_buslines].'</i></a></TD>
                        </TR>
                        <TR valign="top">
                            <TD width="3%" height="20">&nbsp;</TD>
                            <TD width="3%">&nbsp;</TD>
                            <TD width="3%">&nbsp;</TD>
                            <TD  colspan="2">
                                <img src="../images/strelka_big.gif" align="left">
                            <a href="../busrent.php"><i>'.locales::$text[menu_busrent].'</i></a></TD>
                        </TR>
                        <TR valign="top">
                            <TD width="3%" height="20">&nbsp;</TD>
                            <TD width="3%">&nbsp;</TD>
                            <TD width="3%">&nbsp;</TD>
                            <TD width="3%">&nbsp;</TD>
                            <TD>
                                <span style="float:left">
                                    <img src="../images/strelka_big.gif" align="left">
                                    <a href="../offices.php"><i>'.locales::$text[menu_offices].'</i></a>
                                </span>
                                <span style="float:right">
                                    <a href="'.(strlen($qstr)>0?'?'.$qstr.'&':'?').'lang=bg">
                                        <img src="../images/bg.png" />
                                    </a>
                                    <a href="'.(strlen($qstr)>0?'?'.$qstr.'&':'?').'lang=en">
                                        <img src="../images/en.png" />
                                    </a>
                                    <a href="'.(strlen($qstr)>0?'?'.$qstr.'&':'?').'lang=cz">
                                        <img src="../images/cz.png" />
                                    </a>
                                    <a href="'.(strlen($qstr)>0?'?'.$qstr.'&':'?').'lang=hn">
                                        <img src="../images/hn.png" />
                                    </a>
                                </span>
                            </TD>
                        </TR>
                    </table>';
    }

    private function title(){
        return locales::$text[title];
    }
}
?>
