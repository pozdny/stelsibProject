<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 12.03.14
 * Time: 15:13
 */

class BreadCrumbsAdmin {
    public $Content;
    public function __construct(){
        $this->getBreadCrumb();
    }
    private  function getBreadCrumb(){
        $mysqli = M_Core_DB::getInstance();
        global $arResult;
        global $Pages;
        //echo '<pre>';print_r($arResult);echo '</pre>';
        $query = "SELECT ".ADMIN_CAT_M.".id, ".ADMIN_CAT_M.".title, ".ADMIN_CAT_M.".eng, ".ADMIN_PROC.".eng as action FROM ".ADMIN_CAT_M."
                      INNER JOIN ".ADMIN_PROC_M." ON ".ADMIN_PROC_M.".admin_menu_id = ".ADMIN_CAT_M.".id
                      INNER JOIN ".ADMIN_PROC." ON ".ADMIN_PROC_M.".proc_id = ".ADMIN_PROC.".id
                      WHERE ".ADMIN_CAT_M.".eng !='MainPage' AND ".ADMIN_CAT_M.".id !='14' AND ".ADMIN_CAT_M.".id !='16' ORDER BY ".ADMIN_CAT_M.".id ASC";
        $mysqli->_execute($query);
        while($row = $mysqli->fetch()){
            $arr_menu[] = $row;

        }
        //echo '<pre>';print_r($arr_menu);echo '</pre>';

        $action = $arResult->ACTION;
        $pos1 = $arResult->POS1;
        $pos2 = $arResult->POS2;
        $pos3 = $arResult->POS3;
        $data = $arResult->DATA;
        $elem = '';

        if(isset($data["Submenu"])) $Submenu = $data["Submenu"];

        $icon = '&nbsp;&nbsp;'.'<i class="fa fa-caret-right"></i>'.'&nbsp;&nbsp;';
        $link = ADMIN_PANEL;
        $crumbs = '<a href="'.$link.'">Главная админ</a>';
        if($action !='' && $pos1 == ''){
            foreach($arr_menu as $key => $value){
                if($action == $value["eng"]){
                    $crumbs.= $icon.$value["title"];
                    break;
                }
            }
        }
        else{
            foreach($arr_menu as $key => $value){
                if($action == $value["action"]){
                    $title = $value["title"];
                    $crumbs.= $icon.'<a href="'.$link.'/'.$value["eng"].'">'.$title.'</a>';
                    if($action == 'edit_table' || $action == 'edit_news'){
                        if($action == 'edit_news') $table = $pos2.REG_ENG;
                        else  $table = $pos2;
                        $id = $pos1;
                        $query = "SELECT * FROM ".$table."
                                  WHERE id= ".$id;
                        $mysqli->_execute($query);
                        $row = $mysqli->fetch();
                        if($table == TABLE_ADMIN_USERS){
                            $name = $row["name"];
                        }
                        else{
                            $name = $row["title"];
                        }
                        $crumbs.= $icon.$name;
                    }
                    elseif($action == 'edit_catalog' || $action == 'edit_metatags_other'){
                        if($pos2 == 'menu'){
                            $crumbs.= $icon.$data["title"];
                        }
                        elseif($pos2 == 'submenu'){
                                $act = 'edit_catalog';
                            $crumbs.= $icon.'<a href="'.$link.'/'.$act.'/'.$pos1.'/menu">'.$data["title"].'</a>';
                            if(isset($Submenu)){
                                $elem = $Submenu["title"];
                                $crumbs.= $icon.$elem;
                            }
                        }
                        elseif($pos2 == 'complect'){
                            $crumbs.= $icon.'<a href="'.$link.'/'.$action.'/'.$pos1.'/menu">'.$data["title"].' (Комплекты)</a>';
                            foreach($data["Complects"] as $key => $value){
                                if($value["id"] == $pos3){
                                    $elem = $value["title"];
                                }
                            }
                            $crumbs.= $icon.$elem;
                        }
                    }
                    else{
                        foreach($Pages as $key => $value){
                            if($pos1 == $value["link"]){
                                $crumbs.= $icon.$value["title"];
                            }
                        }
                    }

                }
            }

        }



        $html = file_get_contents(DIR_PATH.'./templates/bread-crumb.tpl');
        $html = str_replace( '{crumbs}', $crumbs, $html );
        $this->Content = $html;
    }
} 