<?php
//...............функция приветствия.....................//
function privet()
{
    global $arResult;
    $host = $arResult->HOST;
    $html = '';
    if(isset($arResult->UsernameEnter) && $arResult->UsernameEnter["enter"] == 'Y')
    {
        $time = $arResult->UsernameEnter["last_date"];
        if($time == '0000-00-00 00:00:00')
        {
            $time =  get_string_time(time());
        }
        else
        {
            $time = date( "d.m.Y, H:i:s", strtotime($time));
        }

        $html.= '<div id="header-hello" '.STYLE8.'>Добро пожаловать на сайт, <strong>'.$arResult->UsernameEnter['name'].'!</strong> '."\n";
        $html.= '&nbsp;Ваш последний визит '.$time.',';
        $html.= getOnlineUsers().', ';
        if(!isset($_COOKIE["superadmin"]) && $host != HOST_NAME){
            $html.= '&nbsp;&nbsp;<a href="'.DOMEN_LOC.'/admin/logout" '.STYLE3.'>выход</a>';
        }
        elseif($host == HOST_NAME){
            $html.= '&nbsp;&nbsp;<a href="'.DOMEN_LOC.'/admin/logout" '.STYLE3.'>выход</a>';
        }
        elseif(isset($_COOKIE["superadmin"]) && $host != HOST_NAME){
            $html.= '&nbsp;&nbsp;<a href="'.DOMEN.'" '.STYLE3.'>сначала закрыть stelsib.ru</a>';
        }

        $html.= '</div>'."\n";
    }
    return $html;
}
/*....................Количество юзеров on-line......................*/
function OnlineUsers() {
    $data=DIR_PATH."./files/online.dat";
    $time=time();
    $past_time=time()-600;
    $online_array = array();
    $readdata=fopen($data,"r") or die("Не могу открыть файл $data");
    $data_array=file($data);
    fclose($readdata);
    if (getenv('HTTP_X_FORWARDED_FOR')){
        $user = getenv('HTTP_X_FORWARDED_FOR');
    }
    else
    {
        $user = getenv('REMOTE_ADDR');
    }


    $d=count($data_array);
    for($i=0;$i<$d;$i++)
    {
        list($live_user,$last_time)=explode("::","$data_array[$i]");
        if($live_user!=""&&$last_time!=""):
            if($last_time<$past_time):
                $live_user="";
                $last_time="";
            endif;
            if($live_user!=""&&$last_time!="")
            {
                if($user==$live_user)
                {
                    $online_array[]="$user::$time\r\n";
                }
                else
                    $online_array[]="$live_user::$last_time";
            }
        endif;
    }

    if(isset($online_array)):
        foreach($online_array as $i=>$str)
        {
            if($str=="$user::$time\r\n")
            {
                $ok=$i;
                break;
            }
        }
        foreach($online_array as $j=>$str)
        {
            if($ok==$j) { $online_array[$ok]="$user::$time\r\n"; break;}
        }
    endif;

    $writedata=fopen($data,"w") or die("Не могу открыть файл $data");
    flock($writedata,2);
    if(sizeof($online_array) == 0){
        $online_array[]="$user::$time\r\n";
    }
    else{
        //print_r(sizeof($online_array));
    }
    foreach($online_array as $str)
        fputs($writedata,"$str");
    flock($writedata,3);
    fclose($writedata);

    $readdata=fopen($data,"r") or die("Не могу открыть файл $data");
    $data_array=file($data);
    fclose($readdata);
    $online=count($data_array);

}
function getOnlineUsers() {
    $data=DIR_PATH."./files/online.dat";
    $time=time();
    $past_time=time()-600;
    $online_array = array();
    $readdata=fopen($data,"r") or die("Не могу открыть файл $data");
    $data_array=file($data);
    fclose($readdata);

    if (getenv('HTTP_X_FORWARDED_FOR'))
        $user = getenv('HTTP_X_FORWARDED_FOR');
    else
        $user = getenv('REMOTE_ADDR');

    $d=count($data_array);
    for($i=0;$i<$d;$i++)
    {
        list($live_user,$last_time)=explode("::","$data_array[$i]");
        if($live_user!=""&&$last_time!=""):
            if($last_time<$past_time):
                $live_user="";
                $last_time="";
            endif;
            if($live_user!=""&&$last_time!="")
            {
                if($user==$live_user)
                {
                    $online_array[]="$user::$time\r\n";
                }
                else
                    $online_array[]="$live_user::$last_time";
            }
        endif;
    }

    if(isset($online_array)):
        foreach($online_array as $i=>$str)
        {
            if($str=="$user::$time\r\n")
            {
                $ok=$i;
                break;
            }
        }
        foreach($online_array as $j=>$str)
        {
            if($ok==$j) { $online_array[$ok]="$user::$time\r\n"; break;}
        }
    endif;

    $writedata=fopen($data,"w") or die("Не могу открыть файл $data");
    flock($writedata,2);
    if($online_array=="") $online_array[]="$user::$time\r\n";
    foreach($online_array as $str)
        fputs($writedata,"$str");
    flock($writedata,3);
    fclose($writedata);

    $readdata=fopen($data,"r") or die("Не могу открыть файл $data");
    $data_array=file($data);
    fclose($readdata);
    $online=count($data_array);
    switch ($online)
    {
        case 1:
            $icon = '&nbsp;<img src="'.HOME_PATH.'/img/icons/User.png" width=12px id="img_user"/>'."\n";
            $stroka = ' посетитель';
            break;
        case 2:
        case 3:
        case 4:
            $stroka = ' посетителя';
            $icon = '&nbsp;<img src="'.HOME_PATH.'/img/icons/Users.png" width=12px id="img_users"/>'."\n";
            break;
        default:
            $icon = '&nbsp;<img src="'.HOME_PATH.'/img/icons/Users.png" width=12px id="img_users"/>'."\n";
            $stroka = ' посетителей';
    }

    $online = $icon.' Он-лайн:&nbsp;<strong>'.$online.'</strong><a href="'.ADMIN_PANEL.'/online">'.$stroka.'</a>';
    return $online;
}

//..............................функция ошибок.............................//
function error404($sapi_name, $request_url)
{
    if ($sapi_name == 'cgi' || $sapi_name == 'cgi-fcgi')
    {
        header('Status: 404 Not Found');
    }
    else
    {
        header($_SERVER['SERVER_PROTOCOL'] . '404 Not Found');
    }
    header( 'Refresh: '.REDIRECT_DELAY.'; url=/error.php?id=404&request='.$request_url );
}

function GetFormValue($in_Val, $trim_Val = 0, $u_Case = false, $trim_symbols=false) {
  $ret = trim(addslashes(htmlspecialchars(strip_tags($in_Val))));
  if ($trim_Val)
    $ret = substr($ret, 0, $trim_Val);
  if ($u_Case)
    $ret = strtoupper($ret);

    if ($trim_symbols) {
        $my_len = strlen($ret);
        for ($pos = 0; $pos<$my_len;$pos++) {
            if (!is_numeric(substr($ret,$pos,1))) {
                $ret = substr($ret,0,$pos);
                break;
            }
        }
    }
    return $ret;
}
function getEndStr($count)
{
    $num = $count%10;
    $string = (string)$count;
    if (strlen($count) == 2 && $string{0} == 1)
    {
        $str = '';
    }
    else
    {
        switch($num)
        {
            case 1: $str = 'а';
                break;
            case 2:
            case 3:
            case 4: $str = 'и';
                break;

            default: $str = '';
        }
    }
    return $str;
}
function IPv4($start, $end)
{
    $ip_start = $start;
    $ip_end = $end;

    $long_ip_start=ip2long($ip_start);
    $long_ip_end=ip2long($ip_end);

    $dif = $long_ip_end-$long_ip_start;
    for($i=0; $i<=$dif; $i++)
    {
        $array[] = long2ip($long_ip_start+$i);
    }
    return $array;
}
function get_string_time($date)
{
    $date_time_array = getdate( $date );
    $mon = $date_time_array['mon'];
    $mday = $date_time_array['mday'];
    $year = $date_time_array['year'];
    $hours = $date_time_array['hours'];
    $minutes = $date_time_array['minutes'];
    $seconds = $date_time_array['seconds'];
    $time = $year.'-'.$mon.'-'.$mday.' '.$hours.':'.$minutes.':'.$seconds;
    $time = date( "d.m.Y, H:i:s", strtotime($time));
    return $time;
}

//..........кнопка edit.............................................
function buttonEditPage()
{
    global $arResult;
    $action = $arResult->ACTION;
    if($action == 'MainPage')
    {
        $button = '<div id="buttonEdit"><a href="'.DOMEN_LOC.'/admin/edit_content/MainPage">'.EDIT_IMG_SITE.'</a></div>';

    }
    else
    {
        if($action == 'news'){
            $button = '<div id="buttonEdit"><a href="'.DOMEN_LOC.'/admin/'.$action.'">'.EDIT_IMG_SITE.'</a></div>';
        }
        else{
            $button = '<div id="buttonEdit"><a href="'.DOMEN_LOC.'/admin/edit_content/'.$action.'">'.EDIT_IMG_SITE.'</a></div>';
        }


    }
    return $button;
}
function buttonEditCatalog()
{
    global $arResult;
    $action = $arResult->ACTION;
    if(isset($arResult->POS1) && $arResult->POS1 !=''){
        $button = '<div id="buttonEdit"><a href="'.DOMEN_LOC.'/admin/edit_catalog/'.$action.'/submenu/'.$arResult->POS1.'">'.EDIT_IMG_SITE.'</a></div>';
    }
    else{
        $button = '<div id="buttonEdit"><a href="'.DOMEN_LOC.'/admin/edit_catalog/'.$action.'/menu">'.EDIT_IMG_SITE.'</a></div>';
    }
    return $button;
}
//........проверка существования изображения........................
function FindImg($img, $path, $tab, $id)
{
    $img_empty = 'empty.jpg';
    $image_dir_path = DIR_PATH.PATH_IMG.$path;
    $image_dir = opendir($image_dir_path);
    $i = 0;
    $image_files = null;
    while (($image_file = readdir($image_dir)) !==false)
    {
        if (($image_file != ".") && ($image_file != ".."))
        {
            $image_files[$i] = basename($image_file);
            $i++;
        }
    }
    closedir($image_dir);
    $image_files_count = count($image_files);
    if ($image_files_count)
    {
        sort($image_files);
        for ($i = 0; $i <$image_files_count; $i++)
        {
            if($image_files[$i] == $img)
            {
                return $img;
            }
        }


    }
    return $img_empty;
}
//.................проверка существования файла..................//
function FindFileStuct($file_name, $path)
{

    $str_dir_path = DIR_PATH .$path;
    $str_dir = opendir($str_dir_path);
    $i = 0;
    $str_files = null;
    while (($str_file = readdir($str_dir)) !==false)
    {
        if (($str_file != ".") && ($str_file != ".."))
        {
            $str_files[$i] = basename($str_file);
            $i++;
        }
    }
    closedir($str_dir);
    $str_files_count = count($str_files);
    if ($str_files_count)
    {
        sort($str_files);
        for ($i = 0; $i < $str_files_count; $i++)
        {
            if($str_files[$i] == $file_name)
            {
                return 1;
            }
        }
    }
    return 0;
}
//очистка данных от обратных слешей
function strips(&$el) {
    if (is_array($el))
        foreach($el as $k=>$v)
            strips($el[$k]);
    else $el = stripslashes($el);
}
//вермя выполнения скрипта
function get_microtime($time_start, $time_end)
{
    $time = $time_end - $time_start;
    return $time;
}
//автологин
function autoLogin()
{
    $mysqli = M_Core_DB::getInstance();//echo '<pre>';print_r($_COOKIE);echo '</pre>';
    $login    = $_COOKIE['name'];
    $password = $_COOKIE['password'];
    $login    = $login.SALT_LOG;
    $password = $password.SALT_PAS;
    // Выполняем запрос на получение данных пользователя из БД
    $query = "SELECT *, UNIX_TIMESTAMP(last_visit) as unix_last_visit
            FROM ".TABLE_ADMIN_USERS."
            WHERE login='".md5($login )."'
			AND password='".md5( $password )."'
			LIMIT 1";
    $mysqli->_execute($query);
    // Если пользователь с таким логином и паролем не найден -
    // значит данные неверные и надо их удалить
    if ( $mysqli->num_rows() == 0 ) {
        $tmppos = strrpos( $_SERVER['SERVER_NAME'], '/' ) + 1;
        $path = substr( $_SERVER['SERVER_NAME'], 0, $tmppos );
        if(!isset($_COOKIE["superadmin"])){
            setcookie( 'autologin', '', time() - 1, $path );
            setcookie( 'name', '', time() - 1, $path );
            setcookie( 'password', '', time() - 1, $path );
            setcookie( 'group', '', time() - 1, $path );
            setcookie( 'host', '', time() - 1, $path );
        }
        return false;
    }

    $user = $mysqli->fetch();
    $_SESSION['MM_Username'] = $user;//echo '<pre>';print_r( $_SESSION['MM_Username']);echo '</pre>';

    $query = "SELECT * FROM ".TABLE_ADMIN_USERS."
	        WHERE id=".$_SESSION['MM_Username']['id'] ;
    $mysqli->_execute($query);
    $res1 = $mysqli->fetch();

    $_SESSION['last_visit'] = $res1;

    $query = "UPDATE ".TABLE_ADMIN_USERS."
	        SET last_visit=NOW()
			WHERE id=".$_SESSION['MM_Username']['id'];
    $mysqli->query($query);
    $_SESSION['once'] = $res1;
    return true;
}
