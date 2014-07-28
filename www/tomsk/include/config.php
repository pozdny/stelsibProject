<?php
if (!isset($_SESSION)) {
    session_start();
}

// Вывод заголовка с данными о кодировке страницы
header('Content-Type: text/html; charset=utf-8');
define(  'M_SITE', 'stelsib.ru');
define(  'H_NAME', 'stelsib.ru');
define ( 'HOME_URL', "http://".$_SERVER['SERVER_NAME'] );
define ( 'DOMEN_LOC', '' );
if (!defined("REG_ENG")) define ( 'REG_ENG', '_tomsk' );
define ( 'DOMEN', 'http://stelsib.ru' );
define ( 'ADMIN_PANEL', DOMEN_LOC.'/admin' );
define ( 'TOP_IMG', '<i class="fa fa-caret-up" title="вверх"></i>');
define ( 'BACK_IMG', '<i class="fa fa-caret-left" title="назад"></i>');
define ( 'EDIT_IMG_SITE', '<i class="fa fa-edit fa-2x" title="редактирование" ></i>');
define ( 'EDIT_IMG', '<img src="http://'.H_NAME.'/img/icons/edit_full.png" title="полное редактирование" />');
define ( 'EDIT_IMG_Q', '<img src="http://'.H_NAME.'/img/icons/edit_title.png" title="быстро изменить название позиции по-русски" />');
define ( 'EDIT_IMG_K', '<img src="http://'.H_NAME.'/img/icons/edit_key.png" title="изменить метатеги" />');
define ( 'DEL_IMG', '<img src="http://'.H_NAME.'/img/icons/delete.png" title="удалить" />');
define ( 'NODATA', '');
define ( 'WRONGDATA', '<div class="text-center">Неверные исходные данные</div>' );
//названия таблиц
define ( 'CATALOG_MENU',      'catalog_menu'.REG_ENG );
define ( 'CATALOG_SUBMENU',   'catalog_submenu'.REG_ENG );
define ( 'CATALOG_ALL',       'catalog_all' );
define ( 'NAVIGATOR',         'pages'.REG_ENG );
define ( 'COMPLECT',          'complects'.REG_ENG );
define ( 'CLASS_TABLE',       'class' );
define ( 'TABLE_ADMIN_USERS', 'admin_users' );
define ( 'ADMIN_CAT_M',       'admin_catalog_menu' );
define ( 'ADMIN_ACTIONS',     'admin_actions' );
define ( 'ADMIN_PROC',        'admin_proc' );
define ( 'ADMIN_PROC_M',      'proc' );
define ( 'RESP_TXT',          'response' );
define ( 'SCHET',             'schet' );
define ( 'TABLE_INFO',        'info_site'.REG_ENG );
define ( 'TABLE_PRICES_A',    'table_prices_a' );
define ( 'TABLE_PRICES_P',    'table_prices_p' );
define ( 'TABLE_IMAGES',      'images'.REG_ENG );
define ( 'TABLE_NEWS',        'news'.REG_ENG );
define ( 'TABLE_SITE',        'site' );
// первая страница
define ( 'FIRST_PAGE', 'products' );
//задержка в секундах
define ( 'REDIRECT_DELAY', 0 );
define ( 'REDIRECT_DELAY_INFO', 2 );
// права
define ( 'ALL_R', 'a,m' );
define ( 'A_R', 'a' );
define ( 'M_R', 'm' );

define ( 'COOKIE_TIME', 30 );
define ( 'SALT_LOG', 'gu&@' );
define ( 'SALT_PAS', '7J9$' );
define ( 'TEL', '+7 (383) 239-18-56' );
define ( 'PASS_LEN',  '6');

define ( 'REQUEST_URL', $_SERVER['REQUEST_URI'] );
define ( 'SAPI_NAME', php_sapi_name());
/*.........styles...........*/
define ( 'STYLE1', 'class="style1"');
define ( 'STYLE3', 'class="style3"' );
define ( 'STYLE5', 'class="style5"');
define ( 'STYLE7', 'class="style7"');
define ( 'STYLE8', 'class="style8"' );
define ( 'STYLE9', 'class="style9"' );
define ( 'STYLE10', 'class="style10"' );
define ( 'STYLE11', 'class="style11"' );
define ( 'STYLE12', 'class="style12"' );
define ( 'STYLE13', 'class="style13"' );
define ( 'STYLE15', 'class="style15"' );
define ( 'DOTTED', 'class="dotted"' );
//.......path img.............
define ( 'PATH_IMG', '/img/catalog/' );
define ( 'PATH_IMGD', '/img/catalog/' );
//.......path excel...........
define ( 'PATH_EXCEL', 'files/prices/' );
define ( 'PATH_EXCEL_LOC', $_SERVER["DOCUMENT_ROOT"].'/' );
// Максимальный размер файла вложения в мегабайтах (2мб)
define ( 'MAX_FILE_SIZE', 2048000 );

define ( 'SAVE_CODE', '2ihw6hhw5m31yho41lb5z');

//настройка временной зоны
$config['timezone'] = "Asia/Novosibirsk";
date_default_timezone_set($config['timezone']);