<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 15.05.14
 * Time: 12:11
 */

class Complects {
    public $Content;
    public function __construct($eng = ''){
        $this->getComplectSub($eng);
    }
    private function getComplectSub($eng){
        $mysqli = M_Core_DB::getInstance();
        $query = "SELECT * FROM ".CATALOG_MENU."
                  WHERE eng LIKE '".$eng."'";
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows()>0){
                $row = $mysqli->fetch();
                $query = "SELECT * FROM ".COMPLECT."
                          WHERE menu_id= ".$row["id"];
                try{
                    $mysqli->_execute($query);
                    if($mysqli->num_rows()>0){
                        $arr_complect = array();
                        //заносим в массив все комплекты которые относятся к данному виду категории
                        while($row = $mysqli->fetch()){
                            $arr_complect[] = array(
                                "id" => $row["id"],
                                "title" => $row["title"],
                                "img" => $row["img"],
                                "alt" => $row["alt"],
                                "img_title" => $row["img_title"],
                                "content" => $row["content"],
                                "price" => $row["price"],
                                "pop" => $row["pop"],
                                "promo" => $row["promo"]
                            );
                        }
                        $this->Content = $arr_complect;
                    }

                }
                catch(Exception $e){
                    echo $e->getMessage();
                }
            }

        }
        catch(Exception $e){
            echo $e->getMessage();
        }

    }
    public function getComplectPop(){
        $mysqli = M_Core_DB::getInstance();
        $query = "SELECT * FROM ".COMPLECT."
                  WHERE pop=1";
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows()>0){
                return $mysqli->num_rows();
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    public function getComplectPromo(){
        $mysqli = M_Core_DB::getInstance();
        $query = "SELECT * FROM ".COMPLECT."
                  WHERE promo=1";
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows()>0){
                return $mysqli->num_rows();
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }

} 