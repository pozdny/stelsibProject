<?php
/**
 * Created by PhpStorm.
 * User: Valentina
 * Date: 17.06.14
 * Time: 17:01
 */
if (!isset($_SESSION)) {
    session_start();
}
header("Content-Type: text/plain");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
require_once('../connection/DBClass.php');
require_once('../include/config.php');
require_once('../include/functions.php');
require_once('../include/string.php');

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
{
    header('Content-Type:text/javascript; charset=utf-8');
    exit('Данные отправлены не через AJAX');
}
else{
    header('Content-Type:text/javascript; charset=utf-8');

    if (isset( $_POST["num"]))
    {
        if (isset( $_POST["id"])) $id = $_POST['id'];
        else $id = 1;
        if (isset( $_POST["value"]))
        {
            $value = $_POST['value'];
        }
        $num = $_POST['num'];
        switch($num)
        {
            case 1: Predzakaz($value);
                break;
            case 2: BackCall($value);
                break;
            default: Predzakaz($value);
        }
    }
}
function Predzakaz($value){
    $mysqli = M_Core_DB::getInstance();
    $kind_arr = array();
    $kind = '';
    foreach($value as $key=>$val){
        if($val['name'] == 'kindOrder')          $kind_arr[] = GetFormValue($val['value']);
        if($val['name'] == 'nameOrder')          $name       = GetFormValue($val['value']);
        if($val['name'] == 'cityOrder')          $city       = GetFormValue($val['value']);
        if($val['name'] == 'mailOrder')          $mail       = GetFormValue($val['value']);
        if($val['name'] == 'phoneOrder')         $phone      = GetFormValue($val['value']);
        if($val['name'] == 'caracterOrder')      $caracter   = GetFormValue($val['value']);
    }

    if(sizeof($kind_arr)>1){
        $kind = implode( ', ',$kind_arr);
    }
    else{
        $kind = $kind_arr[0];
    }
    $mail_arr = array(
        'kind' => $kind,
        'name' => $name,
        'city' => $city,
        'mail' => $mail,
        'phone'=> $phone,
        'caracter' => $caracter
    );

    $rez = sendmail($mail_arr, 'predzakaz');
    $options=array(
        "rez"=>$rez

    );
    echo json_encode($options);
}
function BackCall($value){
    foreach($value as $key=>$val){
        if($val['name'] == 'nameOrder')          $name       = GetFormValue($val['value']);
        if($val['name'] == 'phoneOrder')         $phone      = GetFormValue($val['value']);
        if($val['name'] == 'caracterOrder')      $caracter   = GetFormValue($val['value']);
    }
    $mail_arr = array(
        'name' => $name,
        'phone'=> $phone,
        'caracter' => $caracter
    );

    $rez = sendmail($mail_arr, 'backcall');
    $options=array(
        "rez"=>$rez

    );
    echo json_encode($options);
}
function sendmail($arr, $str)
{
    // Подключаем класс FreakMailer
    require_once('../lib/MailClass.inc');
    // Читаем настройки config
    if($str == 'predzakaz'){
        $subject = 'Предзаказ';
    }
    else{
        $subject = 'Заказ обратного звонка';
    }

    $htmlBody = createMailMessage($arr, $str);

    // инициализируем класс
    $mailer = new FreakMailer();

    // Устанавливаем тему письма
    $email = $mailer->to_email;
    $name = $mailer->to_name;
    $mailer->Subject = $subject;

    // Задаем тело письма
    $mailer->Body = $htmlBody;
    $mailer->IsHTML(false);


    // Добавляем адрес в список получателей
    $mailer->AddAddress($email, $name);

    if($mailer->Send() != false)
    {
        $mailer->ClearAddresses();
        $mailer->ClearAttachments();
        return '1';
    }
    else
    {
        $mailer->ClearAddresses();
        $mailer->ClearAttachments();
        return '0';
    }

}
function createMailMessage($arr, $str)
{
    if($str == 'predzakaz'){
        $prod = $arr["kind"];
        $name = $arr["name"];
        $city = $arr["city"];
        $mail = $arr["mail"];
        $phone = $arr["phone"];
        $caracter = $arr["caracter"];
        $html = file_get_contents('../templates/tpl/predzakazMail.tpl');
        $html = str_replace( '{prod}',$prod, $html);
        $html = str_replace( '{name}',$name, $html);
        $html = str_replace( '{city}',$city, $html);
        $html = str_replace( '{mail}',$mail, $html);
        $html = str_replace( '{phone}',$phone, $html);
        $html = str_replace( '{comments}',$caracter, $html);
    }
    else{
        $name = $arr["name"];
        $phone = $arr["phone"];
        $caracter = $arr["caracter"];
        $html = file_get_contents('../templates/tpl/backCallMail.tpl');
        $html = str_replace( '{name}',$name, $html);
        $html = str_replace( '{phone}',$phone, $html);
        $html = str_replace( '{comments}',$caracter, $html);
    }

    return $html;
}








