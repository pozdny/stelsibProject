<?php
/**
 * Created by PhpStorm.
 * User: Valentina
 * Date: 15.05.14
 * Time: 21:34
 */

class Promo {
    public $Content;
    public function __construct(){
        $this->Content = $this->getPromo();
    }
    private function getPromo(){
        $mysqli = M_Core_DB::getInstance();
        $html = '';
        $query = "SELECT ".COMPLECT.".id, ".COMPLECT.".title, ".COMPLECT.".img, ".COMPLECT.".alt, ".COMPLECT.".img_title, ".COMPLECT.".price, ".CATALOG_MENU.".eng FROM ".CATALOG_MENU."
                  INNER JOIN ".COMPLECT." ON ".CATALOG_MENU.".id = ".COMPLECT.".menu_id
                  WHERE promo=1
                  LIMIT 1";
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows() > 0){
                $row = $mysqli->fetch();
                $tab = COMPLECT;
                $path_img_small = 'complects/small/';
                $title = $row["title"];
                $price = sprintf("%0.2f", $row["price"] + ($row["price"]*RATE));
                $link = '/'.$row["eng"];
                $img_small = FindImg($row["img"], $path_img_small, $tab, $row["id"]);
                $img = '<img src="'.HOME_PATH.PATH_IMG.$path_img_small.$img_small.'" alt="'.$row["alt"].'" title="'.$row["img_title"].'">';
                $html.= file_get_contents(DIR_PATH.'./templates/tpl/promo-main.tpl');
                $html = str_replace( '{link}', $link, $html );
                $html = str_replace( '{img}', $img, $html );
                $html = str_replace( '{title}', $title, $html );
                $html = str_replace( '{price}', $price, $html );
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $html;
    }
} 