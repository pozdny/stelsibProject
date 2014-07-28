<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 05.03.14
 * Time: 19:32
 */
define('BASE_PATH',dirname(__FILE__));
define('BASE_DEBUG',false);
class Autoloader {
    public static $enableIncludePath=true;
    private static $_includePaths;						// list of include paths
    public static $classMap=array(
        'Spreadsheet_Excel_Reader' => 'readexcel/reader.php'
    );
    private static $_catalogClasses=array(
        'Shelves' => '/catalog/Shelves.php',
        'Arhivniy' => '/catalog/Arhivniy.php',
        'Polochniy' => '/catalog/Polochniy.php',
        'Beam' => '/catalog/Beam.php',
        'Cheek' => '/catalog/Cheek.php',
        'Coner' => '/catalog/Coner.php',
        'Desk' => '/catalog/Desk.php',
        'Frame' => '/catalog/Frame.php',
        'Metae' => '/catalog/Metae.php',
        'OptionsA' => '/catalog/OptionsA.php',
        'OptionsP' => '/catalog/OptionsP.php',
        'Shelf' => '/catalog/Shelf.php'

    );

    public static function loadClasses($className)
    {
        // use include so that the error PHP file may appear
        if(isset(self::$classMap[$className])){
            include(self::$classMap[$className]);
        }
        elseif(isset(self::$_catalogClasses[$className])){
            include(BASE_PATH.self::$_catalogClasses[$className]);
        }
        else
        {
            // include class file relying on include_path
            if(strpos($className,'\\')===false)  // class without namespace
            {
                if(self::$enableIncludePath===false)
                {
                    foreach(self::$_includePaths as $path)
                    {
                        $classFile=$path.DIRECTORY_SEPARATOR.$className.'.php';
                        if(is_file($classFile))
                        {
                            include($classFile);
                            if(BASE_DEBUG && basename(realpath($classFile))!==$className.'.php')
                                throw new Exception('Class name "{class}" does not match class file "{file}".');
                            break;
                        }
                    }
                }
                else{
                    include($className.'.php');
                }

            }
            else  // class name with namespace in PHP 5.3
            {
                $namespace=str_replace('\\','.',ltrim($className,'\\'));
                if(($path=self::getPathOfAlias($namespace))!==false)
                    include($path.'.php');
                else
                    return false;
            }
            return class_exists($className,false) || interface_exists($className,false);
        }
        return true;

    }
} 