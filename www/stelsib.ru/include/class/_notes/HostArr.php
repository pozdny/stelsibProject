<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 28.05.14
 * Time: 20:22
 */

class HostArr {
    public $Content;
    public function __construct(){
        $this->Content = $this->getHostArr();
    }
    public function getHostArr(){
        $mysqli = M_Core_DB::getInstance();
        $reg_arr = null;
        $query = "SELECT * FROM ".TABLE_SITE." ORDER BY title";
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows() > 0){
                while($row = $mysqli->fetch()){
                    if($row["title"] == 'Новосибирск') $tchk = '';
                    else $tchk = '.';
                    $reg_arr[$row["region"]] = array(
                        "host" => $row["eng"].$tchk.HOST_NAME,
                        "title" => $row["title"]
                    );
                }
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }

        return $reg_arr;
    }
} 