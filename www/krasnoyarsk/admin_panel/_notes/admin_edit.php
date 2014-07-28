<?php
header("Content-Type: text/plain");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
require_once('../connection/DBClass.php');
require_once('../include/config.php');
require_once('../../'.M_SITE.'/include/functions.php');
require_once('../../'.M_SITE.'/include/string.php');

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
        if (isset( $_POST["table"]))
        {
            $table = $_POST['table'];
        }
        $num = $_POST['num'];
        switch($num)
        {
            case 1: change_title($id, $value, $table);
                break;
            case 2: del_action($id, $value, $table);
                break;
            case 3: put_action($id, $value, $table);
                break;
            case 4: edit_action($id, $value, $table);
                break;
            default: change_title($id, $value, $table);
        }


    }

}

function change_title($id, $value, $table){

}

//удаление
function del_action($id, $value, $table)
{
    $mysqli = M_Core_DB::getInstance();
    if($table == CATALOG_MENU)// Удалить категорию товара
    {

    }
    elseif($table == CATALOG_SUBMENU)// Удалить подкатегорию товара
    {

    }
    elseif($table == COMPLECT)// Удалить комплект
    { //echo '<pre>'; print_r($value); echo '</pre>';
        $arr = array();
        foreach($value as $key=>$val){
            if($val['name'] == 'del[]')  $arr[] = ($val['value']);
            if($val['name'] == 'link')  $link = ($val['value']);
        }
        //echo '<pre>'; print_r($arr); echo '</pre>';
        $arr = implode( ',',$arr);
        $query = "SELECT ".CATALOG_MENU.".eng FROM ".COMPLECT."
                  INNER JOIN ".CATALOG_MENU." ON ".COMPLECT.".menu_id = ".CATALOG_MENU.".id
			      WHERE ".COMPLECT.".id IN (".$arr.")";
        $mysqli->_execute($query);
        $row = $mysqli->fetch();
        $eng =  $row['eng'];

        $query = 'DELETE FROM '. $table.' WHERE id IN ('.$arr.')';
        $mysqli->query($query);
        $options=array(
            "link" => $link
        );
        echo json_encode($options);
    }
    else // Удалить позицию из таблиц $table
    {
        $query = 'DELETE FROM '. $table.' WHERE id ='.$id;
        $mysqli->query($query);
        $options=array(
            "link"=>$value
        );
        echo json_encode($options);

    }

}
//добавление пунктов
function put_action($id, $value, $table){
    $mysqli = M_Core_DB::getInstance();
    if($table == COMPLECT){
        foreach($value as $key=>$val){
            if($val['name'] == 'addinput[]'){
                $title = GetFormValue($val['value']);
                $query = sprintf('INSERT INTO '.$table.' (`title`, `menu_id`) VALUES (%s, %s)',
                    GetSQLValueString($title, 'text'),
                    GetSQLValueString($id, 'int'));
                $mysqli->query($query);
            }
            if($val['name'] == 'link')  $link = ($val['value']);
        }
        $options=array(
            "link" => $link
        );
        echo json_encode($options);
    }
    elseif($table == TABLE_NEWS){
        foreach($value as $key=>$val){
            if($val['name'] == 'title')       $title   = GetFormValue($val['value']);
            if($val['name'] == 'content')     $content     = ($val['value']);
            if($val['name'] == 'link')        $link        = ($val['value']);
        }
        $date = date('Y-m-d H:i:s');
        $query = sprintf('INSERT INTO '.$table.' (`title`, `content`, `date`) VALUES (%s, %s, %s)',
            GetSQLValueString($title, 'text'),
            GetSQLValueString($content, 'text'),
            GetSQLValueString($date, 'date'));
        $mysqli->query($query);
        $options=array(
            "link" => $link
        );
        echo json_encode($options);
    }
}
//редактирование пунктов
function edit_action($id, $value, $table){
    $mysqli = M_Core_DB::getInstance();
    if($table == CATALOG_MENU){
        $query = "SELECT * FROM ".$table."
                  WHERE id = ".$id;
        $mysqli->_execute($query);
        $row = $mysqli->fetch();
        $titlepage   = $row['titlepage'];
        $keywords    = $row['keywords'];
        $description = $row['description'];
        $title       = $row['title'];
        $zagolovok   = $row['zagolovok'];
        $content     = $row['content'];
        foreach($value as $key=>$val){
            if($val['name'] == 'titlepage')   $titlepage   = GetFormValue($val['value']);
            if($val['name'] == 'keywords')    $keywords    = GetFormValue($val['value']);
            if($val['name'] == 'description') $description = GetFormValue($val['value']);
            if($val['name'] == 'rus')         $title       = GetFormValue($val['value']);
            if($val['name'] == 'zagolovok')   $zagolovok   = GetFormValue($val['value']);
            if($val['name'] == 'content')     $content     = ($val['value']);
            if($val['name'] == 'content2')    $content2    = ($val['value']);
            if($val['name'] == 'link')        $link        = ($val['value']);
        }
        $query = sprintf("UPDATE ".$table." SET `title`=%s, `zagolovok`=%s, `content`=%s, `content2`=%s, `titlepage`=%s, `keywords`=%s, `description`=%s WHERE id=%s",
            GetSQLValueString($title, "text"),
            GetSQLValueString($zagolovok, "text"),
            GetSQLValueString($content, "text"),
            GetSQLValueString($content2, "text"),
            GetSQLValueString($titlepage, "text"),
            GetSQLValueString($keywords, "text"),
            GetSQLValueString($description, "text"),
            GetSQLValueString($id, "int"));
        $mysqli->query($query);
        $options=array(
            "link" => $link
        );
        echo json_encode($options);
    }
    elseif($table == CATALOG_SUBMENU){
        $query = "SELECT * FROM ".$table."
                  WHERE id = ".$id;
        $mysqli->_execute($query);
        $row = $mysqli->fetch();
        $titlepage   = $row['titlepage'];
        $keywords    = $row['keywords'];
        $description = $row['description'];
        $title       = $row['title'];
        $zagolovok   = $row['zagolovok'];
        $eng         = $row['eng'];
        $content     = $row['content'];
        foreach($value as $key=>$val){
            if($val['name'] == 'titlepage')   $titlepage   = GetFormValue($val['value']);
            if($val['name'] == 'keywords')    $keywords    = GetFormValue($val['value']);
            if($val['name'] == 'description') $description = GetFormValue($val['value']);
            if($val['name'] == 'rus')         $title     = GetFormValue($val['value']);
            if($val['name'] == 'eng')         $eng       = GetFormValue($val['value']);
            if($val['name'] == 'zagolovok')   $zagolovok = GetFormValue($val['value']);
            if($val['name'] == 'content')     $content   =($val['value']);
            if($val['name'] == 'link')        $link      = ($val['value']);
        }
        $query = sprintf("UPDATE ".$table." SET `title`=%s, `zagolovok`=%s, `eng`=%s, `content`=%s, `titlepage`=%s, `keywords`=%s, `description`=%s  WHERE id=%s",
            GetSQLValueString($title, "text"),
            GetSQLValueString($zagolovok, "text"),
            GetSQLValueString($eng, "text"),
            GetSQLValueString($content, "text"),
            GetSQLValueString($titlepage, "text"),
            GetSQLValueString($keywords, "text"),
            GetSQLValueString($description, "text"),
            GetSQLValueString($id, "int"));
        $mysqli->query($query);
        $options=array(
            "link" => $link
        );
        echo json_encode($options);
    }
    elseif($table == NAVIGATOR){
        $query = "SELECT * FROM ".NAVIGATOR."
                  WHERE id = ".$id;
        $mysqli->_execute($query);
        $row = $mysqli->fetch();
        $titlepage   = $row['titlepage'];
        $keywords    = $row['keywords'];
        $description = $row['description'];
        $zagolovok   = $row['zagolovok'];
        $content     = $row['content'];
        foreach($value as $key=>$val){
            if($val['name'] == 'titlepage')   $titlepage   = GetFormValue($val['value']);
            if($val['name'] == 'keywords')    $keywords    = GetFormValue($val['value']);
            if($val['name'] == 'description') $description = GetFormValue($val['value']);
            if($val['name'] == 'zagolovok')   $zagolovok   = GetFormValue($val['value']);
            if($val['name'] == 'content')     $content     = ($val['value']);
            if($val['name'] == 'link')        $link        = ($val['value']);
        }
        $query = sprintf("UPDATE ".$table." SET `zagolovok`=%s, `content`=%s, `titlepage`=%s, `keywords`=%s, `description`=%s  WHERE id=%s",
            GetSQLValueString($zagolovok, "text"),
            GetSQLValueString($content, "text"),
            GetSQLValueString($titlepage, "text"),
            GetSQLValueString($keywords, "text"),
            GetSQLValueString($description, "text"),
            GetSQLValueString($id, "int"));
        $mysqli->query($query);
        $options=array(
            "link" => $link
        );
        echo json_encode($options);
    }
    elseif($table == TABLE_ADMIN_USERS){
        $query = "SELECT * FROM ".TABLE_ADMIN_USERS."
                  WHERE id = ".$id;
        $mysqli->_execute($query);
        $row = $mysqli->fetch();
        foreach($value as $key=>$val){
            if($val['name'] == 'name')     $name     = GetFormValue($val['value']);
            if($val['name'] == 'login')    $login    = GetFormValue($val['value']);
            if($val['name'] == 'password') $password = GetFormValue($val['value']);
            if($val['name'] == 'link')        $link        = ($val['value']);
        }
        if(empty($name))     $name     = $row['name'];
        if(empty($login))    $login    = $row['login'];
        else $login = md5($login.SALT_LOG);
        if(empty($password)) $password = $row['password'];
        else $password = md5($password.SALT_PAS);
        $query = sprintf("UPDATE ".$table." SET `name`=%s, `login`=%s, `password`=%s WHERE id=%s",
            GetSQLValueString($name, "text"),
            GetSQLValueString($login, "text"),
            GetSQLValueString($password, "text"),
            GetSQLValueString($id, "int"));
        $mysqli->query($query);
        $options=array(
            "link" => $link
        );
        echo json_encode($options);
    }
    elseif($table == TABLE_INFO){
        foreach($value as $key=>$val){
            if($val['name'] == 'link') {
                $link = ($val['value']);
                continue;
            }
            $content = $val["value"];
            $query = sprintf("UPDATE ".$table." SET `content`=%s WHERE eng LIKE '".$val["name"]."'",
                GetSQLValueString($content, "text"));
            $mysqli->query($query);
        }
        $options=array(
            "link" => $link
        );
        echo json_encode($options);
    }
    elseif($table == TABLE_NEWS){
        $query = "SELECT * FROM ".TABLE_NEWS."
                  WHERE id = ".$id;
        $mysqli->_execute($query);
        $row = $mysqli->fetch();
        $title    = $row['title'];
        $content  = $row['content'];
        foreach($value as $key=>$val){
            if($val['name'] == 'title')       $title   = GetFormValue($val['value']);
            if($val['name'] == 'content')     $content = ($val['value']);
            if($val['name'] == 'link')        $link    = ($val['value']);
        }
        $query = sprintf("UPDATE ".$table." SET `title`=%s, `content`=%s  WHERE id=%s",
            GetSQLValueString($title, "text"),
            GetSQLValueString($content, "text"),
            GetSQLValueString($id, "int"));
        $mysqli->query($query);
        $options=array(
            "link" => $link
        );
        echo json_encode($options);
    }
    else{
        foreach($value as $key=>$val){
            if($val['name'] == 'content')     $content = ($val['value']);
            $query = sprintf("UPDATE ".$table." SET `content`=%s WHERE id=%s",
                GetSQLValueString($content, "text"),
                GetSQLValueString($id, "int"));
            $mysqli->query($query);
        }
        $options=array(
            "link" => '/admin/'
        );
        echo json_encode($options);
    }



}


