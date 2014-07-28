<?php
//..........................функции администрирования......................//
//....................ссылки в header...........................//
require_once('functions_edit_admin.php');
function admin_header_link()
{
	$html = '
	<link rel="stylesheet" href="'.HOME_PATH.'/css/bootstrap/bootstrap.min.css" type="text/css" />
	<link  rel="stylesheet" href="'.HOME_PATH.'/css/formstyler.css" type="text/css">
	<link rel="stylesheet" href="/css/style.css" type="text/css" />
	<link rel="stylesheet" href="'.HOME_PATH.'/css/admin.css" type="text/css" />
	<script type="text/javascript" src="'.HOME_PATH.'/js/jquery/jquery-2.0.3.js"></script>
	<script type="text/javascript" src="'.HOME_PATH.'/js/bootstrap/bootstrap.min.js"></script>
	<script type="text/javascript" src="'.HOME_PATH.'/js/send_message.js"></script>
	<script type="text/javascript" src="'.HOME_PATH.'/js/jq.admin.js"></script>
	<script type="text/javascript" src="'.HOME_PATH.'/js/jquery/jquery.formstyler.min.js" ></script>
	<script type="text/javascript" src="'.HOME_PATH.'/js/scripts.js"></script>
	<link rel="icon" href="'.HOME_PATH.'/favicon.ico" type="image/x-icon"  />
	<link rel="shortcut icon" href="'.HOME_PATH.'/favicon.ico" type="image/x-icon"  />';
	return $html;
}
function admin_head()
{
    global $arResult;
    $site = $arResult->REGION;
    $host = $arResult->HOST;
    $selected = '';
    $options = '';
    $loc = 'http://'.$_SESSION['host_name'];
    $month = array("01" => "января", "02" => "февраля", "03" => "марта", "04" => "апреля", "05" => "мая", "06" => "июня", "07" => "июля", "08" => "августа", "09" => "сентября",
        "10" => "октября", "11" => "ноября", "12" => "декабря"
    );
    $week = array('воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота');
    $date_m = strtr(date('m'), $month);
    $date_w = $week[date('w')];
    $date_d = date('d');
    $now_date = $date_d.' '.$date_m.' '.date('Y').', '.$date_w;
    $hello = privet();
    $date = '<span '.STYLE11.'><span '.DOTTED.'>'.$now_date.'</span></span>';
    $reg_arr = HostArr::getHostArr();
    if($arResult->UsernameEnter["enter"] == 'Y')
    {
        $block_true = '';
    }
    else
    {
        $block_true = '';
    }
    foreach($reg_arr as $key => $value){

        if($host == $value["host"]){
            $selected = 'selected="selected"';
        }
        else{
            $selected = '';
        }
        if(isset($_COOKIE["superadmin"])){
            $options.= '<option '.$selected.' value="'.$value["host"].'/admin/products">'.$value["title"].'</option>';
        }
        else{
            if($selected !=''){
                $options.= '<option '.$selected.' value="'.$value["host"].'/admin/products">'.$value["title"].'</option>';
                break;
            }
        }
    }
    $html = file_get_contents(DIR_PATH.'./admin_panel/templates/tpl/header.tpl' );
    $html = str_replace( '{block_true}', $block_true, $html );
    $html = str_replace( '{site}', $site, $html );
    $html = str_replace( '{loc}', $loc, $html );
    $html = str_replace( '{options}', $options, $html );
    $html = str_replace( '{date}', $date, $html );
    $html = str_replace( '{hello}', $hello, $html );

    return $html;
}
function admin_footer()
{
    global $arResult;

    if(isset($arResult->INFO_SITE)){
        $info_site = $arResult->INFO_SITE;
        $schet_1 = $info_site["schet"];
    }
    else $schet_1 = '';
    if($arResult->UsernameEnter["enter"] == "Y"){
        $class_true = 'block-true';
    }
    else{
        $class_true = 'block-false';
    }
    $date = date('Y');

    $html = file_get_contents(DIR_PATH.'./templates/tpl/footer.tpl');
    $html = str_replace( '{date}',$date, $html);
    $html = str_replace( '{class_true}',$class_true, $html);
    $html = str_replace( '{schet_1}',$schet_1, $html);

    return $html;
}

//................ правка главные страницы сайта.........................//
function admin_main_page()
{
    $mysqli = M_Core_DB::getInstance();
    global $Pages;
	$html ='<table class="table table-bordered table-condensed" >';
	foreach($Pages as $key => $row_n){
        if($row_n["category"] !=1 && $row_n["link"] !="result-search"){
            $query = "SELECT title, link FROM ".ADMIN_ACTIONS." ORDER BY id";
            $mysqli->_execute($query);
            $product = '';
            $title = '<a href="'.DOMEN_LOC.'/'.$row_n['link'].'" '.STYLE1.'>'.$row_n['title'].'</a>';
            $td = '<td>'.$title.'</td>';
            while($row_d =  $mysqli->fetch())
            {
                $product.= '<tr class="submenu_td2">
                            <td><a href="'.ADMIN_PANEL.'/'.$row_d['link'].'/'.$row_n['link'].'" '.STYLE1.'>'.$row_d['title'].'</a></td>
                            </tr>';
            }
            $submenu_td = 'submenu_prod';
            $html.= file_get_contents( DIR_PATH.'admin_panel/templates/admin_product.tpl' );
            $html = str_replace( '{td}',$td, $html);
            $html = str_replace( '{product}',$product, $html);
            $html = str_replace( '{submenu_td}', $submenu_td, $html);
        }
	}
	$html.='</table>';

	return $html;
}

//................редактирование данных товара.........................//
function admin_product()
{
    $mysqli = M_Core_DB::getInstance();
    global $Catalog;
    //...............категория товара..............................//
    $i = 0;
    $html = '';
    $query = "SELECT title, link FROM ".ADMIN_ACTIONS." ORDER BY id LIMIT 1,1";
    $mysqli->_execute($query);
    $row_d = $mysqli->fetch();
    $html.='<table class="table table-bordered table-condensed" >';
    $html.='<thead><tr><th width="50%">Наименование</th><th width="35%">english</th><th>F</th><th>M</th></tr></thead>';
    foreach($Catalog as $key =>$row_c){
        $num = $row_c['id'];
        $product = '';
        $classHidden     = '';
        $reg = preg_match( "#(default_[0-9]{1,})$#ui", $row_c["eng"] );
        $num_k = true;
        if($reg || !$num_k)
        {
            $classHidden = ' class="classHidden"';
        }
        $title     = '<a href="'.DOMEN_LOC.'/'.$row_c['eng'].'" '.STYLE1.' id="a_'.$num.'">'.$row_c['title'].'</a>';
        $title_eng = '<a href="'.DOMEN_LOC.'/'.$row_c['eng'].'"  '.STYLE9.'>'.$row_c['eng'].'</a>';
        $edit_full =  '<a href="'.ADMIN_PANEL.'/edit_catalog/'.$row_c['eng'].'/menu">'.EDIT_IMG.'</a>';
        $edit_key  =  '<a href="'.ADMIN_PANEL.'/'.$row_d['link'].'_other/'.$row_c['eng'].'/menu"><span class="img_edit" >'.EDIT_IMG_K.'</span></a>';
        $td =  '<td id="td_'.$num.'"'.$classHidden.' >'.$title.'</td>
		        <td>'.$title_eng.'</td>
			    <td align="center">'.$edit_full.'</td>
				<td align="center">'.$edit_key.'</td>';
        foreach($row_c["Submenu"] as $key=>$row_n){
            //...............подкатегория товара..............................//
            $num_sub = $row_n['id'];
            $classHidden     = '';
            $reg = preg_match( "#(default_[0-9]{1,})$#ui", $row_n["eng"] );
            $reg2 = preg_match( "#(default_[0-9]{1,})$#ui", $row_c["eng"] );
            $num_k = true;
            if($reg || $reg2 || !$num_k)
            {
                $classHidden = ' class="classHidden"';
            }
            $title_sub     = '<a href="'.DOMEN_LOC.'/'.$row_c['eng'].'/'.$row_n['eng'].'" '.STYLE1.' id="a_sub_'.$num_sub.'">'.$row_n['title'].'</a>';
            $title_sub_eng = '<a href="'.DOMEN_LOC.'/'.$row_c['eng'].'/'.$row_n['eng'].'" '.STYLE10.'>'.$row_n['eng'].'</a>';
            $edit_sub_full =  '<a href="'.ADMIN_PANEL.'/edit_catalog/'.$row_c['eng'].'/submenu/'.$row_n['eng'].'">'.EDIT_IMG.'</a>';
            $edit_sub_key  =  '<a href="'.ADMIN_PANEL.'/'.$row_d['link'].'_other/'.$row_c['eng'].'/submenu/'.$row_n['eng'].'"><span class="img_edit" >'.EDIT_IMG_K.'</span></a>';
            $product.= '<tr class="submenu_td2">
						<td id="td_sub_'.$num_sub.'"'.$classHidden.'>'.$title_sub.'</td>
						<td >'.$title_sub_eng.'</td>
						<td align="center">'.$edit_sub_full.'</td>
						<td align="center">'.$edit_sub_key.'</td>
						</tr>';
        }
        $submenu_td = 'submenu_prod';
        $html.= file_get_contents( DIR_PATH.'admin_panel/templates/admin_product.tpl' );
        $html = str_replace( '{td}',$td, $html);
        $html = str_replace( '{product}',$product, $html);
        $html = str_replace( '{submenu_td}', $submenu_td, $html);
    }
    $html.='</table>';
    //$html.=view_add_menu();
    $html.='<a href="#top" '.STYLE12.'>'.TOP_IMG.' Наверх</a>';
    return $html;
}

//редактирование данных разных таблиц
function admin_dif_table($tab)
{
    $mysqli = M_Core_DB::getInstance();
    //................редактирование данных таблиц пользователи, новости.........................//
    if($tab == TABLE_ADMIN_USERS)
    {
        $select_item = 'id, name';
        $title_main  = 'Ф.И.О.';
        $title_tab   =  '';
        $order_by    = 'id ASC';
        $action      = 'edit_table';
        $condition   = 'WHERE rights = "m"';
    }
    elseif($tab.REG_ENG == TABLE_NEWS)
    {
        $select_item = 'id, title, date';
        $title_main  = 'Заголовок';
        $title_tab   = 'Дата';
        $order_by    = 'date DESC';
        $action      = 'edit_news';
        $condition   = '';
    }
    if($tab.REG_ENG == TABLE_NEWS)
    {
        $table = $tab.REG_ENG;
        //echo '<pre>';print_r($_GET);echo '</pre>';
        $str=strstr($_GET["action"], 'page=');
        if($str){
            $arr = preg_split('/\=/', $str, -1, PREG_SPLIT_NO_EMPTY);
            $num = intval($arr[1]);
            if(!$num){
                error404(SAPI_NAME, REQUEST_URL);
            }
            else{
                $pageNum = $num;
                if ( $pageNum < 1 ) $pageNum = 1;
            }
        }
        else{
            $pageNum = 1;
        }
        $maxRows = 20;
        $startRow = ($pageNum-1)* $maxRows;
        $query = "SELECT ".$select_item." FROM ".$table." ".$condition."
			   ORDER BY ".$order_by;
        $query_limit = sprintf("%s LIMIT %d, %d", $query, $startRow, $maxRows);
        try{
            $x = $mysqli->queryQ($query_limit);
            $mysqli->_execute($query);
            $totalPages = ceil($mysqli->num_rows()/$maxRows);
            if($pageNum > $totalPages && $mysqli->num_rows() > 0) error404(SAPI_NAME, REQUEST_URL);
            $filename = '/admin/news/';
            $uri = 'page';
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    else{
        $query = "SELECT ".$select_item." FROM ".$tab." ".$condition."
                  ORDER BY ".$order_by;
        try{
            $x = $mysqli->queryQ($query);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    $header='<tr><th width="48%">'.$title_main.'</th><th width="35%">'.$title_tab.'</th><th>F</th><th>D</th></tr>';


    $html = '';
    $product = '';
    $content = '';
    $edit_metatags = '';
    $title_eng_str = '';
    $table = '';

    if($mysqli->num_r($x) > 0)
    {
        while($row_c = $mysqli->fetchAssoc($x))
        {
            $num = $row_c['id'];
            if($tab == TABLE_ADMIN_USERS)
            {
                $title = '<span id="a_'.$num.'" '.STYLE1.'>'.stripslashes($row_c['name']).'</span>';
            }
            elseif($tab.REG_ENG == TABLE_NEWS)
            {
                $title_eng_str = date( "d.m.Y H:i:s", strtotime($row_c["date"]));
                $title = '<span id="a_'.$num.'" '.STYLE1.'>'.stripslashes($row_c['title']).'</span>';
            }
            else
            {
                $title = '<span id="a_'.$num.'" >'.stripslashes($row_c['title']).'</span>';

            }
            $edit_full = '<a href="'.ADMIN_PANEL.'/'.$action.'/'.$row_c['id'].'/'.$tab.'">'.EDIT_IMG.'</a>';
            $edit = '<span class="img_edit" onclick="EditDifTab(\''.$num.'\', \''.$tab.'\')">'.EDIT_IMG_Q.'</span>';
            if($tab.REG_ENG == TABLE_NEWS){
                $del = '<span class="del_button '.$tab.REG_ENG.'" id="'.$num.'">'.DEL_IMG.'</span>';
            }
            else{
                $del = '<span class="del_button '.$tab.'" id="'.$num.'">'.DEL_IMG.'</span>';
            }

            $td = '<td id="td_'.$num.'" >'.$title.'</td>
                <td><span >'.$title_eng_str.'</span></td>
                <td align="center">'.$edit_full.'</td>';
            $td.=	$edit_metatags;
            $td.=  '<td align="center">'.$del.'</td>';

            $content.= file_get_contents( DIR_PATH.'admin_panel/templates/admin_product.tpl' );
            $content = str_replace( '{td}',$td, $content);
            $content = str_replace( '{product}',$product, $content);
            $content = str_replace( '{submenu_td}','', $content);

        }
        $table.=file_get_contents( DIR_PATH.'admin_panel/templates/table_main.tpl' );
        $table = str_replace( '{header}', $header, $table);
        $table = str_replace( '{content}', $content, $table);
        $table = str_replace( '{id}', 'id="dif_tab"', $table);
        $table = str_replace( '{link}', 'class = "'.$_SERVER['REQUEST_URI'].'"', $table);
        $top = '<a href="#top" '.STYLE12.'>'.TOP_IMG.' Наверх</a>';
    }
    else
    {
        $top = '';
        $table = '<div align="center">Нет пунктов</div>';
    }

    if($tab.REG_ENG == TABLE_NEWS)
    {
        $nav_obj = new NavPage($totalPages, $pageNum, $filename, $uri, 'admin', 20);
        $nav = $nav_obj->Content;
        $html.=$table.$nav.view_add_menu_news();
        $list = '';
        //$html.=$list.view_add_menu_news();
    }
    else{
        $html.=$table.view_add_menu();
    }


    $html.= $top;
    return $html;
}

//вывод ip пользователей on-line
//////////////////////////////////////////////////////////////////////////////////
function OnlineUsersIP() {
	include('arrays.php');
	$data="./files/online.dat";
    $html = '';

        $data_array=file($data);

         if (getenv('HTTP_X_FORWARDED_FOR'))
               $user = getenv('HTTP_X_FORWARDED_FOR');
        else
             $user = getenv('REMOTE_ADDR');

	foreach ($data_array as $line_num => $data_array) {
	$data_arr = htmlspecialchars($data_array);
	$ip = substr($data_arr, 0, strpos($data_arr,':'));
	if($ip == "178.49.41.212"){
		$ip = $ip." (Admin)";
	}
	elseif($ip == $user)
	{
		$ip = $ip." (Твой IP)";
    }
	elseif($ip == '195.62.7.7')
	{
		$ip = $ip." (Юра Н.)";
	}
    $html.=  "<b>IP</b> : " . $ip . "<br />\n";

}
return $html;
}


//Выход
function logout()
{
  //to fully log out a visitor we need to clear the session varialbles
  if ( isset( $_COOKIE['autologin'] ) ) setcookie( 'autologin', '', time() - 1, "/");
  if ( isset( $_COOKIE['superadmin'] ) && HOME_URL == DOMEN){
      setcookie( 'superadmin', '', time() - 1, "/", HOST_NAME);
  }

  $_SESSION['MM_Username'] = NULL;
  $_SESSION['once'] = NULL;
  $_SESSION['last_visit'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['once']);
  unset($_SESSION['last_visit']);
  unset($_SESSION['host_name']) ;
  $logoutGoTo = 'http://'.$_SERVER['SERVER_NAME'].'/'.DOMEN_LOC;
  if ($logoutGoTo)
  {
    header("Location: $logoutGoTo");
    exit;
  }


}
//watermark
function watermark($img, $input_tab)
{
    $q = 100;
    $slesh = strrpos($img, '/');
    $filename = substr($img, $slesh+1);

    // получим размеры исходного изображения
    $size_img = getimagesize($img);
    $no_water = 300;
    $w_max = 5000;
    $img_path = $img;
    $img_save = $img;
    if($size_img[0] < $no_water)
    {
        if($input_tab == COMPLECT)
        {
            resizeimg($img_save, '', 2, '', 'save', COMPLECT);
        }
        else
        {
            resizeimg($img_save, '', 4, '', 'save', CATALOG_ALL);
        }
        return true;
    }
    else
    {
        if($size_img[0] > $w_max)
        {
            // файл и новый размер

            $percent = 0.2;

            // тип содержимого
            /*if ($size_img[2]==2)      header('Content-type: image/jpg');
            else if ($size_img[2]==1) header('Content-type: image/gif');
            else if ($size_img[2]==3) header('Content-type: image/png'); */

            // получение нового размера
            list($width, $height) = getimagesize($img);
            $newwidth = $width * $percent;
            $newheight = $height * $percent;

            // загрузка
            $thumb = imagecreatetruecolor($newwidth, $newheight);
            if ($size_img[2]==2)      $source = imagecreatefromjpeg($img);
            else if ($size_img[2]==1) $source = imagecreatefromgif($img);
            else if ($size_img[2]==3) $source = imagecreatefrompng($img);

            // изменение размера
            imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

            // сохраняем после уменьшения в тот же файл
            if ($size_img[2]==2) imagejpeg($thumb, $img, $q);
            else if ($size_img[2]==1) imagegif($thumb, $img, $q);
            else if ($size_img[2]==3) imagepng($thumb, $img, $q);
        }
        $watermark = imagecreatefrompng(DIR_PATH.'/img/icons/watermark.png');
        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);

        if (strstr($img_path, '.jpg')) $img = imagecreatefromjpeg($img_path);
        elseif (strstr($img_path, '.JPG')) $img = imagecreatefromjpeg($img_path);
        elseif (strstr($img_path, '.png')) $img = imagecreatefrompng($img_path);
        elseif (strstr($img_path, '.PNG')) $img = imagecreatefrompng($img_path);
        elseif (strstr($img_path, '.gif')) $img = imagecreatefromgif($img_path);
        if ($img === false)
        {
            return false;
        }

        $size = getimagesize($img_path);
        $dest_x = ($size[0] - $watermark_width)/2;
        $dest_y = ($size[1] - $watermark_height)/2;

        $str_type = gettype($img);
        if($str_type == 'string')
        {
            unlink($img_path);
            return false;
        }
        imagealphablending($img, true);
        imagealphablending($watermark, true);

        imagecopy($img,$watermark,$dest_x,$dest_y,0,0,$watermark_width,$watermark_height);
        //масштабируем фото и сохраняем в папке small

        if($input_tab == COMPLECT)
        {
            resizeimg($img_save, '', 2, '', 'save', COMPLECT);
        }
        else
        {
            resizeimg($img_save, '', 4, '', 'save', CATALOG_ALL);
        }

        if($input_tab == COMPLECT)
        {
            $img_save = DIR_PATH.PATH_IMGD.'complects/'.$filename;
        }
        else
        {
            $img_save = DIR_PATH.PATH_IMGD.CATALOG_ALL.'/'.$filename;
        }

        if (strstr($img_path, '.jpg')) imagejpeg($img, $img_save, $q);
        elseif (strstr($img_path, '.JPG')) imagejpeg($img, $img_save);
        elseif (strstr($img_path, '.png')) imagepng($img, $img_save);
        elseif (strstr($img_path, '.PNG')) imagepng($img, $img_save);
        elseif (strstr($img_path, '.gif')) imagegif($img, $img_save, $q);

        imagedestroy($img);
        imagedestroy($watermark);
        return true;
    }
}
function putIMG($key, $all_id, $key_alt, $input_tab)
{
    $mysqli = M_Core_DB::getInstance();//echo '<pre>'; print_r( $key);echo '</pre>';
    $tab      = $input_tab;
    $value    = (array_values($key));
    $titleR   = (array_values($value[0]));
    $altAR = (array_values($key_alt));
    $n = count($value);
    $k = count($titleR);
    $g = 0;
    $num = 2;

    for($j = 0; $j<$k; $j++)
    {
        for($i = 0 ; $i<$num; $g++, $i++)
        {
            $array_alt[$i] = $altAR[$g];
        }

        for($i = 0; $i<$n; $i++)
        {
            $array = (array_values($value[$i]));
            $k = count($array);

            $array_main[$i] = $array[$j];

        }
        $alt            = $array_alt[0];
        $img_title      = $array_alt[1];
        //$desc_foto      = $array_alt[2];
        $add_foto       = $array_main[0];
        $tmp_file_name  = $array_main[2];
        $size           = $array_main[4]/1024;
        $error          = '';
        if(!empty($add_foto))
        {
            $extensions = array( ".jpg", ".jpeg", ".JPG", ".JPEG", ".gif", ".bmp", ".png", ".PNG" );
            $ext  = strrchr($add_foto, "." );
            $position = array_search($ext, $extensions);
            if ($position !== false){
                $param = $extensions[$position];
            }
            else
                $error.= '<li>недопустимый формат файла изображения '.$add_foto.' (можно jpg, JPG, gif, bmp, png)</li>'."\n";
            //if ( !preg_match( "/^[A-z_.\d\s]+$/ui",  $add_foto))
            //$error.='<li>название файла изображения '.$add_foto.' содержит русские буквы или недопустимые символы (пробел, %, $ и т.д.)</li>'."\n";

            if ( $size == 0 )
            {
                $error.='<li>размер файла изображения  '.$add_foto.' больше '.(ceil(MAX_FILE_SIZE/1024/1024)).' мб</li>'."\n";
            }
            $size = ceil($size);
            if ( $size > MAX_FILE_SIZE/1024 )
            {
                $error.='<li>размер файла изображения  '.$add_foto.' больше '.(ceil(MAX_FILE_SIZE/1024/1024)).' мб</li>'."\n";
            }
            $query = "SELECT id FROM ".$input_tab." ORDER BY id DESC LIMIT 1";
            $mysqli->_execute($query);
            $row = $mysqli->fetch();
            $last_id = $row['id']+1;
            $pos = strpos($add_foto, $param);
            $add_foto = substr($add_foto, 0, $pos);
            $add_foto.= '_'.$last_id.$param;

            $path = CATALOG_ALL."/";
            $all_tab = CATALOG_SUBMENU;

            $dest_file_name = DIR_PATH.PATH_IMG.$path . $add_foto;

            if(empty($error))
            {
                move_uploaded_file($tmp_file_name, $dest_file_name);

                watermark($dest_file_name, $input_tab);

                $query = "SELECT title FROM ".$all_tab." WHERE ".$all_tab.".id = ".$all_id;
                $mysqli->_execute($query);
                $row = $mysqli->fetch();
                $title_all = $row['title'];
                if($alt == NULL)       $alt = $title_all;
                if($img_title == NULL) $img_title = $title_all;

                $query = sprintf("INSERT INTO ".$tab." (id, img, alt, img_title, sub_id ) VALUES (%s, %s, %s, %s, %s)",
                    GetSQLValueString($last_id, "int"),
                    GetSQLValueString($add_foto, "text"),
                    GetSQLValueString($alt, "text"),
                    GetSQLValueString($img_title, "text"),
                    GetSQLValueString($all_id, "int"));
                $mysqli->query($query);
            }

        }
    }
    if(!empty($error))
    {

        return '0'.$error;
    }
    else
    {
        return '1';
    }

}
function editIMG($key, $id, $key_alt, $input_tab)
{
    $mysqli = M_Core_DB::getInstance();
    $tab      = $input_tab;
    $value    = (array_values($key));//echo '<pre>';print_r($value);echo '</pre>';
    $array_key=array_keys($key["name"]);
    $titleR   = (array_values($value[0]));
    $altAR = (array_values($key_alt));
    $n = count($value);
    $k = count($titleR);
    $g = 0;
    $num = 2;

    for($j = 0; $j<$k; $j++)
    {
        for($i = 0 ; $i<$num; $g++, $i++)
        {
            $array_alt[$i] = $altAR[$g];
        }

        for($i = 0; $i<$n; $i++)
        {
            $array = (array_values($value[$i]));
            $k = count($array);

            $array_main[$i] = $array[$j];

        }
        $id_foto        = $array_key[$j];
        $alt            = $array_alt[0];
        $img_title      = $array_alt[1];
        $add_foto       = $array_main[0];
        $tmp_file_name  = $array_main[2];
        $size           = $array_main[4]/1024;
        $error          = '';

        if(!empty($add_foto))
        {
            $extensions = array( ".jpg", ".jpeg", ".JPG", ".JPEG", ".gif", ".bmp", ".png", ".PNG" );
            $ext  = strrchr($add_foto, "." );
            $position = array_search($ext, $extensions);
            if ($position !== false){
                $param = $extensions[$position];
            }
            else
                $error.= '<li>недопустимый формат файла изображения '.$add_foto.' (можно jpg, JPG, gif, bmp, png)</li>'."\n";
            //if ( !preg_match( "/^[A-z_.\d\s]+$/ui",  $add_foto))
                //$error.='<li>название файла изображения '.$add_foto.' содержит русские буквы или недопустимые символы (пробел, %, $ и т.д.)</li>'."\n";

            if ( $size == 0 )
            {
                $error.='<li>размер файла изображения  '.$add_foto.' больше '.(ceil(MAX_FILE_SIZE/1024/1024)).' мб</li>'."\n";
            }
            $size = ceil($size);
            if ( $size > MAX_FILE_SIZE/1024 )
            {
                $error.='<li>размер файла изображения  '.$add_foto.' больше '.(ceil(MAX_FILE_SIZE/1024/1024)).' мб</li>'."\n";
            }
            $pos = strpos($add_foto, $param);
            $add_foto = substr($add_foto, 0, $pos);
            $add_foto.= '_'.$id_foto.$param;
            if($tab == COMPLECT)
            {
                $path = "complects/";
                $all_tab = COMPLECT;
            }
            else{
                $path = CATALOG_ALL."/";
                $all_tab = CATALOG_SUBMENU;
            }

            $dest_file_name = DIR_PATH.PATH_IMG.$path . $add_foto;

            if(empty($error))
            {
                move_uploaded_file($tmp_file_name, $dest_file_name);

                watermark($dest_file_name, $input_tab);

                $query = "SELECT title FROM ".$all_tab." WHERE ".$all_tab.".id = ".$id_foto;
                $mysqli->_execute($query);
                $row = $mysqli->fetch();
                $title = $row['title'];
                if($alt == NULL)       $alt = $title;
                if($img_title == NULL) $img_title = $title;
                if($tab == TABLE_IMAGES){
                    $query = sprintf("UPDATE ".$tab." SET img=%s, alt=%s, img_title=%s WHERE id=%s",
                        GetSQLValueString($add_foto, "text"),
                        GetSQLValueString($alt, "text"),
                        GetSQLValueString($img_title, "text"),
                        GetSQLValueString($id_foto, "int"));
                }
                else{
                    $query = sprintf("UPDATE ".$tab." SET img=%s, alt=%s, img_title=%s WHERE id=%s",
                        GetSQLValueString($add_foto, "text"),
                        GetSQLValueString($alt, "text"),
                        GetSQLValueString($img_title, "text"),
                        GetSQLValueString($id_foto, "int"));
                }
                $mysqli->query($query);
            }
        }
        elseif(empty($add_foto) && (!empty($alt) || !empty($img_title)) || (empty($alt) && empty($img_title)))
        {
            if($tab == TABLE_IMAGES){
                $query = sprintf("UPDATE ".$tab." SET alt=%s, img_title=%s WHERE id=%s",
                    GetSQLValueString($alt, "text"),
                    GetSQLValueString($img_title, "text"),
                    GetSQLValueString($id_foto, "int"));
            }
            else{
                $query = sprintf("UPDATE ".$tab." SET alt=%s, img_title=%s WHERE id=%s",
                    GetSQLValueString($alt, "text"),
                    GetSQLValueString($img_title, "text"),
                    GetSQLValueString($id_foto, "int"));
            }
            $mysqli->query($query);

        }

    }
    if(!empty($error))
    {

        return '0'.$error;
    }
    else
    {
        return '1';
    }

}


function backLink($http_referer, $now_link)
{
	if(isset($_SESSION[back_edit]) && $_SESSION[back_edit] == 'yes')
	{
		$_SESSION[back_link] = $_SESSION[back_link];
		$_SESSION[back_edit] == 'no';

	}
	else
	{
		if(isset($_SESSION[back_one]))
		{
			$_SESSION[back_link] = $_SESSION[back_one];
			unset($_SESSION[back_one]);
		}
		else
		{
			 $_SESSION[back_one] = $http_referer;
			 $_SESSION[back_link] = $http_referer;
		}


		$_SESSION[back_edit] == 'no';
	}
	$link = $_SESSION[back_link];
	return $link;
}
/*...........функции пропорций фото.......................*/
function resizeimg($f, $w='', $type, $q='', $s='', $menu_eng)
{
// f - имя файла
// type - способ масштабирования
// q - качество сжатия
// src - исходное изображение
// dest - результирующее изображение
// w - ширниа изображения
// ratio - коэффициент пропорциональности
// str - текстовая строка
    if (!file_exists($f)) return false;
// тип преобразования, если не указаны размеры
    if ($type == 0) $w = 30;  // квадратная 70x70
    if ($type == 1) $w = 115;  // квадратная 115x115
    if ($type == 2){
        $w = 140; // пропорциональная шириной 140
        $h = 160;
    }
    if ($type == 3) $w = 230;  // квадратная 115x115
    if ($type == 4) $w = 120;  // квадратная 115x115
    $dstX = 0;
    $dstY = 0;

    $s_img = getimagesize($f);
    if ($s_img === false) return false;

// качество jpeg по умолчанию
    if ($q == '')
    {
        $q = 100;
    }

// создаём исходное изображение на основе
// исходного файла и опеределяем его размеры

    if ($s_img[2]==2)      $src = imagecreatefromjpeg($f);
    else if ($s_img[2]==1) $src = imagecreatefromgif($f);
    else if ($s_img[2]==3) $src = imagecreatefrompng($f);

    $w_src = imagesx($src);
    $h_src = imagesy($src);



// если размер исходного изображения
// отличается от требуемого размера
    if ($w_src != $w)
    {
        // операции для получения прямоугольного файла
        if ($type==2)
        {
            // вычисление пропорций

            if($w_src > $h_src)//если ширина больше высоты вычисляем смещение вниз по Y картинки
            {
                $ratio = $w_src/$w;
                $w_dest = round($w_src/$ratio);
                $h_dest = round($h_src/$ratio);
                $dstY = ($h - $h_dest)/2;
                $dstX = ($w - $w_dest)/2;
            }
            if($h_src > $w_src)//если высота больше ширины вычисляем смещение вниз по X картинки
            {
                $ratio = $h_src/$w;
                $w_dest = round($w_src/$ratio);
                $h_dest = round($h_src/$ratio);
                $dstX = ($h_dest - $w_dest)/2;
                $dstY = ($h - $h_dest)/2;
            }
            if($h_src == $w_src)//если высота равна ширине
            {
                $w_dest = $w;
                $h_dest = $w;
            }
//echo '$ratio:'.$w_src.'/'.$w.'='.$ratio.'<br />';
//echo '$w_dest:'.$w_src.'/'.$ratio.'='.$w_dest.'<br />';
//echo '$h_dest:'.$h_src.'/'.$ratio.'='.$h_dest.'<br />';
            // создаём пустую картинку с белым фоном
            $dest = imagecreatetruecolor($w,$h);
            imagefill($dest, 0, 0, 0xffffff);
            imagecopyresized($dest, $src, $dstX, $dstY, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
        }


        // операции для получения файла с пропорциональным изображением отцентрированныя по квадрату
        if($type==4)
        {
            // вычисление пропорций

            if($w_src > $h_src)//если ширина больше высоты вычисляем смещение вниз по Y картинки
            {
                $ratio = $w_src/$w;
                $w_dest = round($w_src/$ratio);
                $h_dest = round($h_src/$ratio);
                $dstY = ($w_dest - $h_dest)/2;
            }
            if($h_src > $w_src)//если высота больше ширины вычисляем смещение вниз по X картинки
            {
                $ratio = $h_src/$w;
                $w_dest = round($w_src/$ratio);
                $h_dest = round($h_src/$ratio);
                $dstX = ($h_dest - $w_dest)/2;
            }
            if($h_src == $w_src)//если высота равна ширине
            {
                $w_dest = $w;
                $h_dest = $w;
            }
//echo '$ratio:'.$w_src.'/'.$w.'='.$ratio.'<br />';
//echo '$w_dest:'.$w_src.'/'.$ratio.'='.$w_dest.'<br />';
//echo '$h_dest:'.$h_src.'/'.$ratio.'='.$h_dest.'<br />';
            // создаём пустую картинку с белым фоном
            $dest = imagecreatetruecolor($w,$w);
            imagefill($dest, 0, 0, 0xffffff);
            imagecopyresized($dest, $src, $dstX, $dstY, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
        }
        // операции для получения квадратного файла
        if ($type==0||$type==1||$type==3)
        {
            // создаём пустую квадратную картинку
            // важно именно truecolor!, иначе будем иметь 8-битный результат
            $dest = imagecreatetruecolor($w,$w);

            // вырезаем квадратную серединку по x, если фото горизонтальное
            if ($w_src>$h_src)
                imagecopyresized($dest, $src, 0, 0,
                    round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                    0, $w, $w, min($w_src,$h_src), min($w_src,$h_src));

            // вырезаем квадратную верхушку по y,
            // если фото вертикальное (хотя можно тоже серединку)
            if ($w_src<$h_src)
                imagecopyresized($dest, $src, 0, 0, 0,
                    round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                    $w, $w, min($w_src,$h_src), min($w_src,$h_src));

            // квадратная картинка масштабируется без вырезок
            if ($w_src==$h_src)
                imagecopyresized($dest, $src, 0, 0, 0, 0, $w, $w, $w_src, $w_src);
        }
        if($s == 'save')
        {
            //Сохраняем уменьшенную копию
            $slesh = strrpos($f, '/');
            $filename = substr($f, $slesh+1);
            if($menu_eng == COMPLECT){
                $save_path  = DIR_PATH.PATH_IMGD.'complects/small/'.$filename;
            }
            elseif($menu_eng == CATALOG_ALL){
                $save_path  = DIR_PATH.PATH_IMGD.CATALOG_ALL.'/small/'.$filename;
            }

            if ($s_img[2]==2) imagejpeg($dest, $save_path, $q);
            else if ($s_img[2]==1) imagegif($dest, $save_path, $q);
            else if ($s_img[2]==3) imagepng($dest, $save_path);
        }
        else
        {
            // Выводим уменьшенную копию в поток вывода
            if ($s_img[2]==2)      header('Content-type: image/jpg');
            else if ($s_img[2]==1) header('Content-type: image/gif');
            else if ($s_img[2]==3) header('Content-type: image/png');
            if ($s_img[2]==2) imagejpeg($dest);
            else if ($s_img[2]==1) imagegif($dest);
            else if ($s_img[2]==3) imagepng($dest);

        }


        imagedestroy($dest);
        imagedestroy($src);
        return true;
    }

}
function getBreadCrumbs(){
    $bread_obj   = new BreadCrumbsAdmin();
    $breadcrumbs = $bread_obj->Content;
    return $breadcrumbs;
}
function admin_info_site($fild){
    $mysqli = M_Core_DB::getInstance();
    $html = '';
    $content = '';
    $table = TABLE_INFO;
    if($fild == SCHET)
    {
        $query = "SELECT * FROM ".$table." WHERE eng LIKE '".$fild."%'";
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows() > 0){
                $row = $mysqli->fetch();
                $content = $row["content"];
                $id      = $row["id"];
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        $legend = 'Счетчик 1';

        $html = file_get_contents(DIR_PATH.'admin_panel/templates/edit_schet.tpl' );
        $html = str_replace( '{table}', $table, $html);
        $html = str_replace( '{legend}', $legend, $html);
        $html = str_replace( '{content}', $content, $html);
        $html = str_replace( '{link}', $_SERVER['REQUEST_URI'], $html);
    }
    else{
        $query = "SELECT * FROM ".$table;
        $mysqli->_execute($query);
        if($mysqli->num_rows() > 0){
            while($row = $mysqli->fetch()){
                //echo '<pre>';print_r($row);echo '</pre>';
                if($row["eng"] == 'name'){
                    $name = $row["content"];
                }
                if($row["eng"] == 'phone'){
                    $phone = $row["content"];
                }
                if($row["eng"] == 'shadule'){
                    $shadule = $row["content"];
                }
                if($row["eng"] == 'email'){
                    $email = $row["content"];
                }
            }
        }
        $title = 'Данные организации';
        $html = file_get_contents( DIR_PATH.'admin_panel/templates/edit_info.tpl' );
        $html = str_replace( '{table}', $table, $html);
        $html = str_replace( '{title}', $title, $html);
        $html = str_replace( '{name}', $name, $html);
        $html = str_replace( '{phone}', $phone, $html);
        $html = str_replace( '{shadule}', $shadule, $html);
        $html = str_replace( '{email}', $email, $html);
        $html = str_replace( '{link}', $_SERVER['REQUEST_URI'], $html);
    }
    return $html;
}
function admin_prices()
{
    $price = new GetPrice();
    $price->getForm();
    $html = $price->Content;

    return $html;
}