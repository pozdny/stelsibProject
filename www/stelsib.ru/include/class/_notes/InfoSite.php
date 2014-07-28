<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 13.03.14
 * Time: 13:32
 */

class InfoSite {
    public $Content;
    public function __construct(){
            $this->getInfo();
    }
    private function getInfo(){
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
            $this->Content = $array;
        }catch(Exception $e){
            echo $e->getMessage();
        }

    }
} 