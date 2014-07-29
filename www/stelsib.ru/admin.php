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

require_once('include/config_host.php');

define ( 'HOME_PATH', $home_url );
define ( 'HOST', $host );
define ( 'RATE', $k );
define ( 'REG_ENG', $reg_eng );

// подключаем config.php
require_once('include/config.php');

require_once('include/include_admin.php');
$session_current_id = session_id();
$arResult = getArResult();
if(((isset($arResult->UsernameEnter["enter"]) &&  $arResult->UsernameEnter["enter"] != "Y") || !isset($arResult->UsernameEnter["enter"])) && !isset($_SESSION['once']) && isset( $_COOKIE['superadmin'] )){
      autoLogin();//echo '<pre>';print_r( $_SESSION);echo '</pre>';
      header("Location:". $_SERVER['REQUEST_URI']);
}

//echo '<pre>'; print_r($arResult); echo '</pre>';
$action  = $arResult->ACTION;
if(($action !='admin' && !(isset($_SESSION['MM_Username']))) || $action == 'admin' && !(isset($_SESSION['MM_Username']))){
    if($action == 'products'){
        $req_url = '/admin';
    }
    else{
        $req_url = REQUEST_URL;
    }
    error404(SAPI_NAME, $req_url);
}
elseif(isset($arResult->WRONGDATA) && $arResult->WRONGDATA){
    error404(SAPI_NAME, REQUEST_URL);
}
else{
    //очистка данных от обратных слешей
    strips($arResult->DATA);
    $rights = '';
    $Pages = $arResult->DATA["TopMenu"];
    $Catalog = $arResult->DATA["Catalog"];
    if  ($arResult->UsernameEnter["enter"] == "Y" && !isset($_SESSION['once']) && isset( $_COOKIE['autologin'] ) ) autoLogin();
    OnlineUsers();
    $titlepage = 'Панель администрирования';
    if ($arResult->UsernameEnter["group"] !='')
    {
        $rights = $arResult->UsernameEnter["group"];
    }
    if ($arResult->ACTION =='' || $arResult->ACTION == 'admin')
    {
        if($rights == 'a')
        {
            $arResult->ACTION = FIRST_PAGE;
        }
        else
            $arResult->ACTION = FIRST_PAGE;
    }
    $content = '';
    $actions = array(
        'logout',
        'pages',
        'products',
        'schet',
        'prices',
        'news',
        'admin_users',
        'edit_content',
        'get_edit_content',
        'edit_metatags',
        'get_edit_metatags',
        'edit_metatags_other',
        'edit_menu',
        'add_position',
        'edit_table',
        'get_edit_tab',
        'edit_catalog',
        'edit_menu',
        'get_edit_complect',
        'edit_submenu',
        'get_edit_images',
        'info_site',
        'edit_news'
    );
    if(!in_array($arResult->ACTION, $actions)){

    }
    $action = $arResult->ACTION;
    switch($action){
        case 'logout': logout();
            break;
        case 'pages':
            $content = pages(ALL_R);
            break;
        case 'products':
            $content = products(ALL_R);
            break;
        case 'prices':
            $content = prices(ALL_R);
            break;
        case 'schet':
            $content = schet(ALL_R);
            break;
        case 'admin_users':
            $content = dif_table(A_R);
            break;
        case 'news':
            $content = dif_table(ALL_R);
        break;
        case 'info_site':
            $content = info_site(ALL_R);
            break;
        case 'edit_content':
            $content = edit_content(ALL_R);
            break;
        case 'edit_metatags':
            $content = edit_metatags(ALL_R);
            break;
        case 'edit_metatags_other':
            $content = edit_metatags_other(ALL_R);
            break;
        case 'add_position': add_position(ALL_R);
            break;
        case 'edit_table':
            $content = edit_table(ALL_R);
            break;
        case 'edit_catalog':
            $content = edit_catalog(ALL_R);
            break;
        case 'get_edit_complect': get_edit_complect();
            break;
        case 'edit_submenu':
            $content = edit_submenu();
            break;
        case 'get_edit_images':get_edit_images();
            break;
        case 'edit_news':
            $content = edit_news(ALL_R);
            break;
        default:
            $content = products(ALL_R);

    }
    $titlepage = "Административная панель";
    $top_link = admin_header_link();
    $head = admin_head();
    $left = left();
    $title = getTitle();
    $col_content = "col-xs-9";
    $footer = admin_footer();
    $breadcrumbs = getBreadCrumbs();

    $CONTENT_columns = "CONTENT_big";

    $html = file_get_contents( DIR_PATH.'admin_panel/templates/tpl/index.tpl' );
    $html = str_replace( '{titlepage}', $titlepage, $html );
    $html = str_replace( '{top_link}', $top_link, $html );
    $html = str_replace( '{head}', $head, $html );
    $html = str_replace( '{top_menu}','', $html);
    $html = str_replace( '{CONTENT_columns}', $CONTENT_columns, $html );
    $html = str_replace( '{left}', $left, $html );
    $html = str_replace( '{edit}', '', $html );
    $html = str_replace( '{breadcrumbs}', $breadcrumbs, $html );
    $html = str_replace( '{title}', $title, $html );
    $html = str_replace( '{content}', $content, $html );
    $html = str_replace( '{col_content}',$col_content, $html);
    $html = str_replace( '{footer}', $footer, $html );
    echo $html;
}

function left()
{
    $array_pages = array();
    $array_products = array();
    $array_dif_tab = array();
    require('include/arrays.php');
    global $arResult;
    $AdminMenu = $arResult->DATA["AdminMenu"];
    $li = '';

    $action = $arResult->ACTION;
    foreach($AdminMenu as  $menu){
        $eng_menu   = $menu["eng"];
        $title_menu = $menu["title"];
        ($eng_menu == $action || ((in_array($action, $array_pages)) && $eng_menu == $array_pages[0])
                              || ((in_array($action, $array_products)) && $eng_menu == $array_products[0])
                              || ((in_array($action, $array_dif_tab)) && $eng_menu == $array_dif_tab[0])
                              || 'edit_'.$eng_menu == $action
        )? $li_menu = "li_menuActiv" : $li_menu = "li_menu";


        $li.='<a href="/admin/'.$eng_menu.'"><li class="'.$li_menu.'"><span>'.$title_menu.'</span></a>';

        $li.='</li>';
    }
    $html = file_get_contents( DIR_PATH.'admin_panel/templates/tpl/left.tpl' );
    $html = str_replace( '{li}', $li, $html );


    return $html;
}
function getTitle(){
    $array_pages = array();
    $array_products = array();
    $array_dif_tab = array();
    require('include/arrays.php');
    global $arResult;
    $AdminMenu = $arResult->DATA["AdminMenu"];
    $action = $arResult->ACTION;
    $title = '';
    foreach($AdminMenu as $value){
        $eng_menu   = $value["eng"];
        $title_menu = $value["title"];
        if($eng_menu == $action || ((in_array($action, $array_pages)) && $eng_menu == $array_pages[0])
            || ((in_array($action, $array_products)) && $eng_menu == $array_products[0])
            || ((in_array($action, $array_dif_tab)) && $eng_menu == $array_dif_tab[0])
            || 'edit_'.$eng_menu == $action){
            $title = $title_menu;
            break;
        }
        else{
            $title= "товары";
        }
    }
    $titleblock = file_get_contents(DIR_PATH.'admin_panel/templates/tpl/title.tpl');
    $titleblock = str_replace( '{title}',$title, $titleblock);
    return $titleblock;
}

function pages()
{
   // access();
   // access_rights($r);
    //главные страницы сайта
    $html = admin_main_page();

    return $html;
}
function products($r)
{
    //access();
    access_rights($r);
    unset($_SESSION['menu']);

    //страницы категорий, подкатегорий и товара
    $html = admin_product();
    return $html;
}
function dif_table($r)
{
    global $arResult;
    access();
    access_rights($r);
    //таблицы
    $html= admin_dif_table($arResult->ACTION);
    return $html;
}
function edit_catalog($r){
    global $arResult;
    access();
    access_rights($r);
    $table = '';
    $html = '';
    if(isset($arResult->POS2) && $arResult->POS1 !=''){
        $table = $arResult->POS2;
    }
    if($table == 'menu'){
        $html = edit_menu();
    }
    elseif($table == 'complect'){
        $html = edit_complect();
    }
    elseif($table == 'submenu'){
        $html = edit_submenu();
    }
    return $html;
}
function schet($r)
{
    global $arResult;
    $table = $arResult->ACTION;
    access();
    access_rights($r);
    //Счетчики
    $html= admin_info_site($table);
    return $html;
}
function info_site($r)
{
    global $arResult;
    $table = $arResult->ACTION;
    access();
    access_rights($r);
    //Разная информация
    $html= admin_info_site($table);
    return $html;
}
function prices($r)
{
    access();
    access_rights($r);
    $html = admin_prices();
    return $html;

}