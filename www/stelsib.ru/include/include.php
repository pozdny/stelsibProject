<?php
// Подключаем файлы
require_once('functions.php');
require_once('library.php');
// Подключаем БД
require_once(DIR_PATH.'./connection/DBClass.php');
require_once('functions_global.php');

//расширения файлов, из которых происходит загрузка неинициализированных классов;
spl_autoload_extensions(".php");
require_once('class/Autoloader.php');
//регистрируем автозагрузчик классов
spl_autoload_register(array('Autoloader', 'loadClasses'));
//cache
header("Cache-Control: public");
header("Expires: " . date("r", time() + 3600));



