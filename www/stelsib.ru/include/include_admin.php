<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 05.02.14
 * Time: 19:07
 */

// Подключаем файлы
require_once('functions.php');
// Подключаем БД
require_once('./connection/DBClass.php');
require_once('functions_global.php');
//расширения файлов, из которых происходит загрузка неинициализированных классов;
spl_autoload_extensions(".php");
require_once('class/Autoloader.php');
//регистрируем автозагрузчик классов
spl_autoload_register(array('Autoloader', 'loadClasses'));
//подключаем админ.функции
require_once('functions_admin.php');
require_once('access.php');
require_once('string.php');
require_once('library.php');