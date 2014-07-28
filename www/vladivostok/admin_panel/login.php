<?php
header("Content-Type: text/plain");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
require_once('../connection/DBClass.php');
require_once('../include/config.php');
require_once('../../'.M_SITE.'/include/functions.php');
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
{
    header('Content-Type:text/html; charset=utf-8');
    exit('Данные отправлены не через AJAX');
}
elseif(isset($_POST['arr']))
{
    header('Content-Type:text/html; charset=utf-8');
    $mysqli = M_Core_DB::getInstance();
    $error     = '';
    $login     = '';
    $password  = '';
    $autologin = '';
    //обрабатываем массив $arr
    $array = $_POST['arr'];
    if(isset($array[0]))
        $login     = $array[0]['value'];
    if(isset($array[1]))
        $password  = $array[1]['value'];
    if(isset($array[2]))
        $autologin = $array[2]['value'];
    $login    = GetFormValue(substr($login, 0, 20));
    $password = GetFormValue(substr($password, 0, 20));
    if ( empty( $login )  )
        $error = 'empty_login';
    if ( empty( $password )  )
        $error.= 'empty_password';
    if (empty($error))
    {
        $login_cook = $login;
        $password_cook = $password;
        $login    = $login.SALT_LOG;
        $password = $password.SALT_PAS;
        // Выполняем запрос на получение данных пользователя из БД
        $query = "SELECT * FROM ".TABLE_ADMIN_USERS."
                         WHERE login='".md5($login)."'
						 AND password='".md5($password)."'
			             LIMIT 1";
        $mysqli->_execute($query);
        $row_name = $mysqli->fetch();
        if ($row_name == 0 )
        {
            echo 'no';
        }
        else
        {
            $rights  = $row_name['rights'];
            $name    = $row_name['name'];
            $_SESSION['MM_Username'] = $row_name;
            setLastVisit();
            setcookie( 'name', '', time() - 1, "/" );
            setcookie( 'password', '', time() - 1, "/" );
            setcookie( 'group', '', time() - 1, "/" );
            setcookie( 'host', '', time() - 1, "/" );
            setcookie( 'name', $login_cook, time() + 3600*24*COOKIE_TIME, "/");
            setcookie( 'password', $password_cook, time() + 3600*24*COOKIE_TIME, "/");
            setcookie( 'group', $rights, time() + 3600*24*COOKIE_TIME, "/");
            setcookie( 'host', $_SESSION['host_name'], time() + 3600*24*COOKIE_TIME, "/");
            if($autologin=='yes')
            {
                setcookie( 'autologin', 'yes', time() + 3600*24*COOKIE_TIME, "/");
            }
            echo 'ok';
        }
    }
    else
    {
        if($error == 'empty_login')
            echo 'empty_login';
        else if($error == 'empty_password')
            echo 'empty_password';
        else
            echo 'empty_both';
    }
}
else echo 'empty_both';

function setLastVisit()
{
    $mysqli = M_Core_DB::getInstance();
    if ( isset( $_SESSION['MM_Username']) )
    {
        $query = "SELECT * FROM ".TABLE_ADMIN_USERS."
	        WHERE id=".$_SESSION['MM_Username']['id'] ;
        $mysqli->_execute( $query );
        $row = $mysqli->fetch();

        $_SESSION['last_visit'] = $row;
        $query = "UPDATE ".TABLE_ADMIN_USERS."
	        SET last_visit=NOW()
			WHERE id=".$_SESSION['MM_Username']['id'] ;
        $mysqli->query( $query );
    }
}
