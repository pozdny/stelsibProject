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
    public function getContentCat($row){
        $title_elem = '<h4>'.$row["title"].'</h4>';
        //проверяем изображение на наличие
        $tab = COMPLECT;
        $path_img_big = COMPLECT.'/';
        $path_img_small = COMPLECT.'/small/';

        $img_big   = FindImg($row["img"], $path_img_big, $tab, $row["id"]);
        $img_small = FindImg($row["img"], $path_img_small, $tab, $row["id"]);
        $link = '<a class="galeri" title="'.$row["title"].'" href="'.PATH_IMG.$path_img_big.$img_big.'"><div class="lupa"></div></a>';
        $img = '<img src="'.PATH_IMG.$path_img_small.$img_small.'" alt="'.$row["alt"].'" title="'.$row["img_title"].'">';
        $description = print_page($row["content"]);
        $price = sprintf("%.02f",$row["price"]);
        $content = file_get_contents('./templates/block-element-catalog.tpl');
        $content = str_replace( '{span}', "col-xs-6 col-sm-6 col-md-6 col-lg-6", $content );
        $content = str_replace( '{title}', $title_elem, $content );
        $content = str_replace( '{link}', $link, $content );
        $content = str_replace( '{img}', $img, $content );
        $content = str_replace( '{description}', $description, $content );
        $content = str_replace( '{price}', $price, $content );
        return $content;
    }
    public function getImagesSub($row){//echo '<pre>';print_r($row); echo '</pre>';
        //проверяем изображение на наличие
        $content='';
        $tab = CATALOG_ALL;
        $path_img_big = CATALOG_ALL.'/';
        $path_img_small = CATALOG_ALL.'/small/';
        if(isset($row["Images"])  && $row["Images"] !=''){
            $content ='<div class="block-single-img clearfix">';
            foreach($row["Images"] as $key=>$value){
                $title = $value["img_title"];
                $img_big   = FindImg($value["img"], $path_img_big, $tab, $value["id"]);
                $img_small = FindImg($value["img"], $path_img_small, $tab, $value["id"]);
                $link = '<a class="galeri" title="'.$value["img_title"].'" href="'.PATH_IMG.$path_img_big.$img_big.'"><div class="lupa"></div></a>';
                $img = '<img src="'.PATH_IMG.$path_img_small.$img_small.'" alt="'.$value["alt"].'" title="'.$value["img_title"].'">';
                $content.= file_get_contents('./templates/block-element.tpl');
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