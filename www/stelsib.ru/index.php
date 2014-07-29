<?php
if (!isset($_SESSION)) {
    session_start();
}
//определение пути до файла
define('HOST_NAME', 'stelsib_new');
if(HOST_NAME == 'stelsib.ru'){
    $ch = '/';
}
else{
    $ch = '\\';
}

$pos = strrpos((__DIR__), $ch);
$str_host = substr((__DIR__), $pos+1);
$dir = substr((__DIR__), 0, $pos+1);


define('FULL_PATH', $dir);
define('MAIN_SITE', 'stelsib.ru');

$host = $_SERVER['HTTP_HOST'];
$_SESSION['host_name'] = $host;
$dir_main_site = FULL_PATH.MAIN_SITE.'/';

define ( 'DIR_PATH', $dir_main_site );
//подключаем файл с зависимостями
require_once('vendor/autoload.php');
require_once('include/config_host.php');

define ( 'HOME_PATH', $home_url );
define ( 'HOST', $host );
define ( 'RATE', $k );
define ( 'REG_ENG', $reg_eng );

// подключаем config.php
require_once('include/config.php');
//подключаем файлы
require_once('include/include.php');


$time_start = microtime(true);
$session_current_id = session_id();
$arResult = getArResult();
if  (((isset($arResult->UsernameEnter["enter"]) &&  $arResult->UsernameEnter["enter"] != "Y") || !isset($arResult->UsernameEnter["enter"])) && !isset($_SESSION['once']) && isset( $_COOKIE['superadmin'] )  ){
        autoLogin();//echo '<pre>';print_r( $_SESSION);echo '</pre>';
        header("Location:". $_SERVER['REQUEST_URI']);
}

elseif(isset($arResult->UsernameEnter["enter"]) &&  $arResult->UsernameEnter["enter"] != "Y" && !isset($_SESSION['once']) && isset( $_COOKIE['autologin'] ) ){

    autoLogin();
}
else{

}
OnlineUsers();
//cчитываем action
$action  = $arResult->ACTION;
if(($action == 'admin-panel' && $arResult->save_code !=SAVE_CODE) || $arResult->WRONGDATA){
    error404(SAPI_NAME, REQUEST_URL);
}
else{
    //очистка данных от обратных слешей
    strips($arResult->DATA);
    //выносим данные каталога в отдельную переменную
    $Catalog = $arResult->DATA["Catalog"];
    //в зависимости от значения action выводим данные на страницу
    $getPage      = new GetPage($action);
    $content      = $getPage->Content;
    echo $content;

    //$time_end = microtime(true);
    //$time = get_microtime($time_start, $time_end);
    //echo $time;
}

