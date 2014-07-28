<?php
/**
 * Created by PhpStorm.
 * User: Valunya
 * Date: 21.01.14
 * Time: 23:35
 */

class Menu {
    public $Content;
    public function __construct($kind, $rights = ''){
        if($kind == 'topmenu')
            $this->getTopMenu();
        if($kind == 'leftmenu')
            $this->getLeftMenu($rights);
    }
    private function getTopMenu(){
        $mysqli = M_Core_DB::getInstance();
        $query = "SELECT *
		          FROM ".NAVIGATOR;
        $mysqli->_execute($query);
        while($row = $mysqli->fetch()){
            $arr_menu[] = array(
                "id" => $row["id"],
                "title" => $row["title"],
                "zagolovok" => $row["zagolovok"],
                "link" => $row["link"],
                "content" => $row["content"],
                "titlepage" => $row["titlepage"],
                "keywords" => $row["keywords"],
                "description" => $row["description"],
                "category" => $row["category"],
                "top_menu" => $row["top_menu"]
            );
        }
        $this->Content = $arr_menu;
    }
    private function getLeftMenu($rights){
        $mysqli = M_Core_DB::getInstance();
        if($rights == 'a')
        {
            $query = "SELECT ".ADMIN_CAT_M.".id, ".ADMIN_CAT_M.".title, ".ADMIN_CAT_M.".eng FROM ".ADMIN_CAT_M."
                      WHERE ".ADMIN_CAT_M.".eng !='MainPage' AND ".ADMIN_CAT_M.".id !='14' AND ".ADMIN_CAT_M.".id !='16' ORDER BY ".ADMIN_CAT_M.".id ASC";
            $mysqli->_execute($query);

        }
        elseif($rights == 'm')
        {
            $query = "SELECT ".ADMIN_CAT_M.".id, ".ADMIN_CAT_M.".title, ".ADMIN_CAT_M.".eng FROM ".ADMIN_CAT_M."
                      WHERE ".ADMIN_CAT_M.".eng !='MainPage' AND ".ADMIN_CAT_M.".rights REGEXP '^".$rights."$'AND ".ADMIN_CAT_M.".id !='14' AND ".ADMIN_CAT_M.".id !='16' AND ".ADMIN_CAT_M.".eng !='online' ORDER BY ".ADMIN_CAT_M.".id ASC";
            $mysqli->_execute($query);

        }
        while($row = $mysqli->fetch()){
            $arr_menu[] = array(
                "id" => $row["id"],
                "title" => $row["title"],
                "eng" => $row["eng"]
            );
        }
        $this->Content = $arr_menu;
    }

} 