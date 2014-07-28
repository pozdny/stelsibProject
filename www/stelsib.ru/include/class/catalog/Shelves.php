<?php
/**
 * Created by PhpStorm.
 * User: Valunya
 * Date: 12.01.14
 * Time: 21:23
 */

abstract class Shelves {
    public static $description = "Unknown Shelves";

    public function getDescription(){
        return self::$description;
    }
    public abstract function cost();
    public function getContentCat($row){//echo '<pre>';print_r($row); echo '</pre>';
        //проверяем изображение на наличие
        $tab = COMPLECT;
        $path_img_big = 'complects/';
        $path_img_small = 'complects/small/';
        $numS = 1;
        $content = '';
        $i = 2;
        if(isset($row["Complects"]) && sizeof($row["Complects"]) !=0){
            $num = sizeof($row["Complects"]);
            foreach($row["Complects"] as $key=>$value){
                $title_elem = $value["title"];
                if($i == 2){
                    $content.='<div class="row">';
                }
                $img_big   = FindImg($value["img"], $path_img_big, $tab, $value["id"]);
                $img_small = FindImg($value["img"], $path_img_small, $tab, $value["id"]);
                $link = '<a class="galeri" title="'.$value["img_title"].'" href="'.HOME_PATH.PATH_IMG.$path_img_big.$img_big.'"><div class="lupa"></div></a>';
                $img = '<img src="'.HOME_PATH.PATH_IMG.$path_img_small.$img_small.'" alt="'.$value["alt"].'" title="'.$value["img_title"].'" >';
                $description = print_page($value["content"]);
                $price = sprintf("%.02f",$value["price"] + ($value["price"]*RATE));
                $content.= file_get_contents(DIR_PATH.'./templates/tpl/complect-image.tpl');
                $content = str_replace( '{span}', "col-xs-6", $content );
                $content = str_replace( '{title}', $title_elem, $content );
                $content = str_replace( '{link}', $link, $content );
                $content = str_replace( '{img}', $img, $content );
                $content = str_replace( '{description}', $description, $content );
                $content = str_replace( '{price}', $price, $content );
                $i--;
                if(!$i || ($i == 1 && $num == $numS)){
                    $content.='</div>';
                    $i = 2;
                }
                $numS++;
            }

        }

        return $content;
    }
    public function getImagesSub($row){//echo '<pre>';print_r($row); echo '</pre>';
        //проверяем изображение на наличие
        $content='';
        $tab = CATALOG_ALL;
        $path_img_big = 'catalog_all/';
        $path_img_small = 'catalog_all/small/';
        if(isset($row["Images"])  && $row["Images"] !=''){
            $content ='<div class="block-single-img clearfix">';
            foreach($row["Images"] as $key=>$value){
                $title = $value["img_title"];
                $img_big   = FindImg($value["img"], $path_img_big, $tab, $value["id"]);
                $img_small = FindImg($value["img"], $path_img_small, $tab, $value["id"]);
                $link = '<a class="galeri" title="'.$value["img_title"].'" href="'.HOME_PATH.PATH_IMG.$path_img_big.$img_big.'"><div class="lupa"></div></a>';
                $img = '<img src="'.HOME_PATH.PATH_IMG.$path_img_small.$img_small.'" alt="'.$value["alt"].'" title="'.$value["img_title"].'">';
                $content.= file_get_contents(DIR_PATH.'./templates/tpl/sub-image.tpl');
                $content = str_replace( '{link}', $link, $content );
                $content = str_replace( '{img}', $img, $content );
                $content = str_replace( '{title}', $title, $content );
            }
            $content.='</div>';
        }
        return $content;
    }
    public function getTableSub($row, $cat_id){//echo '<pre>';print_r($row); echo '</pre>';
        $content = '';
        $table = new TablePrice(true, $row["id"], $cat_id);
        $content = $table->Content;
        return $content;
    }
} 