<?php
/**
 * Created by PhpStorm.
 * User: Valentina
 * Date: 14.05.14
 * Time: 12:19
 */

class PopProd {
    public $Content;
    static $COL = 4;
    public function __construct(){
        $this->Content = $this->getRowPop();
    }
    private function getRowPop(){
        $left_menu = $this->getLeftMenu();
        $pop = $this->getPopProd();
        $html = file_get_contents(DIR_PATH.'./templates/tpl/pop-main.tpl');
        $html = str_replace( '{left_menu}', $left_menu, $html );
        $html = str_replace( '{pop}', $pop, $html );
        return $html;
    }
    private function getLeftMenu(){
        global $Catalog;
        $li = '';
        foreach($Catalog as $key => $menu){
            $eng_menu   = $menu["eng"];
            $title_menu = $menu["title"];

            $a_link = '<a href="/'.$eng_menu.'"><span>'.$title_menu.'</span></a>';

            $li.='<li class="li_menu">'.$a_link;
            if(isset($menu["Submenu"])){
                $li.='<ul class="submenu">';
                foreach($menu["Submenu"] as $key => $submenu){
                    $eng_submenu   = $submenu["eng"];
                    $title_submenu = $submenu["title"];
                    $a_link_sub = '<a href="/'.$eng_menu.'/'.$eng_submenu.'"><span>'.$title_submenu.'</span></a>';
                    $li.='<li class="li_submenu">'.$a_link_sub.'</li>';
                }
                $li.='</ul>';
            }
            $li.='</li>';
        }
        $html = file_get_contents(DIR_PATH.'./templates/tpl/pop-left-menu.tpl');
        $html = str_replace( '{li}', $li, $html );
        return $html;
    }
    private function getPopProd(){
        $mysqli = M_Core_DB::getInstance();
        $html = '';
        $tab = COMPLECT;
        $path_img_small = 'complects/small/';
        $query = "SELECT ".COMPLECT.".id, ".COMPLECT.".title, ".COMPLECT.".img, ".COMPLECT.".alt, ".COMPLECT.".img_title, ".COMPLECT.".price, ".CATALOG_MENU.".eng FROM ".CATALOG_MENU."
                  INNER JOIN ".COMPLECT." ON ".CATALOG_MENU.".id = ".COMPLECT.".menu_id
                  WHERE pop=1
                  ORDER BY ".COMPLECT.".id DESC
                  LIMIT ".self::$COL;
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows() > 0){
                while($row = $mysqli->fetch()){
                    $title = $row["title"];
                    $price = sprintf("%0.2f", $row["price"] + ($row["price"]*RATE));
                    $eng = $row["eng"];
                    $img_small = FindImg($row["img"], $path_img_small, $tab, $row["id"]);
                    $img = '<img src="'.HOME_PATH.PATH_IMG.$path_img_small.$img_small.'" alt="'.$row["alt"].'" title="'.$row["img_title"].'">';
                    $html.= file_get_contents(DIR_PATH.'./templates/tpl/pop-one.tpl');
                    $html = str_replace( '{eng}', $eng, $html );
                    $html = str_replace( '{img}', $img, $html );
                    $html = str_replace( '{title}', $title, $html );
                    $html = str_replace( '{price}', $price, $html );
                }
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $html;
    }
} 