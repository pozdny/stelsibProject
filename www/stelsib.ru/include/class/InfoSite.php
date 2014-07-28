<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 13.03.14
 * Time: 13:32
 */

class InfoSite {

    public function __construct(){
            $this->getInfo();
    }
    public static function getInfo(){
        $mysqli = M_Core_DB::getInstance();
        $array = array();
        $query = "SELECT *
		          FROM ".TABLE_INFO;
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows() > 0){
                while($row = $mysqli->fetch()){
                    $array[$row["eng"]] = $row["content"];
                }
            }
            return $array;
        }catch(Exception $e){
            echo $e->getMessage();
        }

    }
    public static function getSessionId(){
        $mysqli = M_Core_DB::getInstance();
        $tab = 'info_site';
        $content = '';
        $query = "SELECT content FROM ".$tab." WHERE eng LIKE 'session_id'";
        $mysqli->_execute($query);
        if($mysqli->num_rows() > 0){
           $row = $mysqli->fetch();
           $content = $row["content"];

        }
        return $content;
    }
} 