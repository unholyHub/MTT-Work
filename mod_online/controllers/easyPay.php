<?php
include_once 'gui.php';
class easyPay extends gui {
    function __construct() {/*explicite*/}

    public function leechIDN(){
        $request='';
        if($_POST){
            $count=0;
            foreach($_POST as $key=>$value){
                $request.="$key=$value";
                if($count < sizeof($_POST)-1){
                    $request.='&';
                }
                $count++;
            }
        }
        //$request='';
        $ch = @curl_init("https://www.epay.bg/ezp/reg_bill.cgi");
        @curl_setopt($ch, CURLOPT_POST      ,1);
        @curl_setopt($ch, CURLOPT_POSTFIELDS    ,$request);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
        @curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
        $respose = @curl_exec($ch);

        
        if(preg_match('/IDN=(.*?)$/', $respose, $match)){
            $respose = $match[1];
            $this->page_contents="<br /><h1 style='color:red; text-align:center;'>Код за плащане на гише на EasyPay: <span class='ezp-code'><span>$respose</span></span></h1>
                <h2 style='text-align:center;'>Заявката за плащане ще бъде валидна в рамките на 2 часа. За повече информация посетете сайта на <a style='color: blue; font-size: 16px;' target='_blank' href='http://www.easypay.bg'>EasyPay</a>.</h2>
                 <b style='float: left; padding:10px;'>
                    <ul>
                    <li>Не е необходима предварителна регистрация в системата, достатъчно е да предоставите на оператора на гишето информация за плащането (код за плащане на гише на EasyPay).</li>
                    <li>След внасяне на сума за плащане на сметка, системата автоматично отчита погасяване на задълженията на клиента към съответното дружество.</li>
                    <li>След успешно потвърдено и приключило плащане, преводът не може да се анулира. За да се върне парична сума, трябва да се обърнете <a style='color: blue; font-size: 14px;' target=\"_blank\" href=\"http://www.easypay.bg/?p=contacts\" title=\"Контакти\">към централата на Изипей АД</a>.</li>
                    </ul>
                 </b>";
        }elseif (preg_match('/ERR=(.*?)$/', $respose, $match)) {
            $respose = $match[1];
            $this->page_contents="<h1 style='color:red; font-size: 18px; text-align:center;'>Грешка при създаване на заявката към EasyPay: $respose</h1>
                    <h2 style='text-align:center; font-size: 16px;'>За повече информация се свържете с оператор на <a style='color: blue; font-size: 16px;' target='_blank' href='http://epay.bg'>ePay</a>.</h2>";
        }
        
        

        print $this->show_page();
    }
}
?>
