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

        $action = $arResult->ACTION;
        $pos1 = $arResult->POS1;
        $pos2 = $arResult->POS2;
        $pos3 = $arResult->POS3;
        $data = $arResult->DATA;
        $elem = '';
        if(isset($data["AdminMenu"])) $AdminMenu = $data["AdminMenu"];
        if(isset($data["Submenu"])) $Submenu = $data["Submenu"];

        $icon = '&nbsp;&nbsp;'.'<i class="fa fa-caret-right"></i>'.'&nbsp;&nbsp;';
        $link = ADMIN_PANEL;
        $crumbs = '<a href="'.$link.'">Главная админ</a>';
        if($action !='' && $pos1 == ''){
            foreach($AdminMenu as $key => $value){
                if($action == $value["eng"]){
                    $crumbs.= $icon.$value["title"];
                }
            }
        }
        else{
            foreach($AdminMenu as $key => $value){
                if($action == $value["action"]){
                    $title = $value["title"];
                    $crumbs.= $icon.'<a href="'.$link.'/'.$value["eng"].'">'.$title.'</a>';
                    if($action == 'edit_table'){
                        $table = $pos2;
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
                    elseif($action == 'edit_catalog'){
                        if($pos2 == 'menu'){
                            $crumbs.= $icon.$data["title"];
                        }
                        elseif($pos2 == 'submenu'){
                            $crumbs.= $icon.'<a href="'.$link.'/'.$action.'/'.$pos1.'/menu">'.$data["title"].'</a>';
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



        $html = file_get_contents('./templates/bread-crumb.tpl');
        $html = str_replace( '{crumbs}', $crumbs, $html );
        $this->Content = $html;
    }
} 