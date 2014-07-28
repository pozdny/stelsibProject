<?php
/**
 * Created by PhpStorm.
 * User: Valunya
 * Date: 22.01.14
 * Time: 23:08
 */

class BreadCrumbs {
    public $Content;
    public function __construct(){
        $this->getBreadCrumb();
    }
    private  function getBreadCrumb(){
        global $arResult; //echo '<pre>';print_r($arResult);echo '</pre>';

        if(!$arResult->is_category){
            $html = '';
        }
        else{
            $data = $arResult->DATA;
            $icon = '&nbsp;&nbsp;'.'<i class="fa fa-angle-double-right "></i>'.'&nbsp;&nbsp;';
            $link = HOME_URL;
            $crumbs = '<a href="'.$link.'">Главная</a>';
            if($arResult->POS1){
                $crumbs.= $icon.'<a href="/'.$data["eng"].'">'.$data["title"].'</a>';
            }
            else{
                $crumbs.= $icon.$data["title"];
            }
            if(isset($data["Submenu"])){
                $crumbs.= $icon.$data["Submenu"]["title"];
            }

            $html = file_get_contents(DIR_PATH.'./templates/bread-crumb.tpl');
            $html = str_replace( '{crumbs}', $crumbs, $html );
        }
        $this->Content = $html;
    }

} 