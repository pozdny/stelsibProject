<?php
/**
 * Created by PhpStorm.
 * User: Valunya
 * Date: 23.01.14
 * Time: 22:42
 */

class Search {
    public $Content;
    public $Word;
    public function __construct($word){
        $this->getSearch($word);
    }
    private function getSearch($search_word){
        global $Catalog;
        $mysqli = M_Core_DB::getInstance();
        $search_word = substr($search_word, 0, 64);
        $search_word = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search_word);
        $search_word = trim(preg_replace("/\s(\S{1,2})\s/", " ", preg_replace("/[ ]+/", "  "," $search_word ")));
        $search_word = preg_replace("/[ ]+/", " ", $search_word);
        $this->Word  = $search_word;
        $search_word = mb_strtolower($search_word, 'UTF-8');
        $search_word = GetFormValue($search_word);
        $length = strlen($search_word);
        $like = '';
        $num_total = 0;
        $html  = '';
        $like  = '';
        $like1 = '';
        $like2 = '';
        if($length == 3)
        {
            $like  = "OR ".CATALOG_ALL.".title LIKE '%".$search_word."%' OR ".CATALOG_ALL.".content LIKE '%".$search_word."%'";
            $like1 = "OR ".CATALOG_SUBMENU.".title LIKE '%".$search_word."%' OR ".CATALOG_SUBMENU.".eng LIKE '%".$search_word."%' OR ".CATALOG_SUBMENU.".content LIKE '%".$search_word."%'";
            $like2 = "OR ".CATALOG_MENU.".title LIKE '%".$search_word."%' OR ".CATALOG_MENU.".eng LIKE '%".$search_word."%' OR ".CATALOG_MENU.".content LIKE '%".$search_word."%'";
        }
        $highlight = str_replace(" ", "|", $search_word);

        if($search_word !='')
        {

            /*.............CATALOG_ALL...................................................................................................................*/
            $query = "SELECT DISTINCT ".CATALOG_ALL.".title, ".CATALOG_SUBMENU.".eng AS submenu_eng, ".CATALOG_MENU.".eng AS menu_eng FROM ".CATALOG_ALL."
			  INNER JOIN ".CATALOG_SUBMENU." ON ".CATALOG_ALL.".submenu_id = ".CATALOG_SUBMENU.".id
			  INNER JOIN ".CATALOG_MENU." ON ".CATALOG_SUBMENU.".menu_id = ".CATALOG_MENU.".id
			  WHERE MATCH (".CATALOG_ALL.".title, ".CATALOG_ALL.".content) AGAINST('>".$search_word."*' IN BOOLEAN MODE) ".$like;
            try{
                $all = $mysqli->queryQ($query);
                $num_all = $mysqli->num_r($all);
                if(!$num_all){
                    $query = "SELECT DISTINCT ".CATALOG_ALL.".title, ".CATALOG_SUBMENU.".eng AS submenu_eng, ".CATALOG_MENU.".eng AS menu_eng FROM ".CATALOG_ALL."
                      INNER JOIN ".CATALOG_SUBMENU." ON ".CATALOG_ALL.".submenu_id = ".CATALOG_SUBMENU.".id
                      INNER JOIN ".CATALOG_MENU." ON ".CATALOG_SUBMENU.".menu_id = ".CATALOG_MENU.".id
                      WHERE ".CATALOG_ALL.".title LIKE '%".$search_word."%' OR ".CATALOG_ALL.".content LIKE '%".$search_word."%'";
                    try{
                        $all = $mysqli->queryQ($query);
                        $num_all = $mysqli->num_r($all);

                    }
                    catch(Exception $e){
                        echo $e->getMessage();
                    }
                }
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
            /*.............CATALOG_SUBMENU...................................................................................................................*/
            $query = "SELECT ".CATALOG_SUBMENU.".title, ".CATALOG_SUBMENU.".eng AS submenu_eng, ".CATALOG_MENU.".eng AS menu_eng FROM ".CATALOG_SUBMENU."
			          INNER JOIN ".CATALOG_MENU." ON ".CATALOG_SUBMENU.".menu_id = ".CATALOG_MENU.".id
			          WHERE MATCH(".CATALOG_SUBMENU.".title, ".CATALOG_SUBMENU.".content, ".CATALOG_SUBMENU.".eng) AGAINST('>".$search_word."*' IN BOOLEAN MODE) ".$like1;
            try{
                $submenu = $mysqli->queryQ($query);
                $num_submenu = $mysqli->num_r($submenu);
                if(!$num_submenu){
                    $query = "SELECT ".CATALOG_SUBMENU.".title, ".CATALOG_SUBMENU.".eng AS submenu_eng, ".CATALOG_MENU.".eng AS menu_eng FROM ".CATALOG_SUBMENU."
			                  INNER JOIN ".CATALOG_MENU." ON ".CATALOG_SUBMENU.".menu_id = ".CATALOG_MENU.".id
			                  WHERE ".CATALOG_SUBMENU.".title LIKE '%".$search_word."%' OR ".CATALOG_SUBMENU.".content LIKE '%".$search_word."%'";

                        $submenu = $mysqli->queryQ($query);
                        $num_submenu = $mysqli->num_r($submenu);


                }
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
            /*.............CATALOG_MENU...................................................................................................................*/
            $query = "SELECT ".CATALOG_MENU.".title, ".CATALOG_MENU.".eng AS menu_eng FROM ".CATALOG_MENU."
			          WHERE MATCH(".CATALOG_MENU.".title, ".CATALOG_MENU.".eng, ".CATALOG_MENU.".content) AGAINST('>".$search_word."*' IN BOOLEAN MODE) ".$like2;
            try{
                $menu = $mysqli->queryQ($query);
                $num_menu = $mysqli->num_r($menu);
                if(!$num_menu){
                    $query = "SELECT ".CATALOG_MENU.".title, ".CATALOG_MENU.".eng AS menu_eng FROM ".CATALOG_MENU."
		                     WHERE ".CATALOG_MENU.".title LIKE '%".$search_word."%' OR ".CATALOG_MENU.".eng LIKE '%".$search_word."%' OR ".CATALOG_MENU.".content LIKE '%".$search_word."%'";
                    $menu = $mysqli->queryQ($query);
                    $num_menu = $mysqli->num_r($menu);


                }
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
            /*.............RESULT............................................................................................................................*/
            $link = HOME_URL.'/';
            if($num_all || $num_submenu || $num_menu){
                $i=1;
                if($num_all)
                {
                    $num_total+= $num_all;
                    while($row_all = $mysqli->fetchAssoc($all)){
                        $html.= '<span '.STYLE7.'>'.$i.'.</span> '.$this->getSearch_link($highlight, $link, $row_all['title'], $row_all['menu_eng'], $row_all['submenu_eng'])."\n";
                        $i++;
                    }
                }
                if($num_submenu)
                {
                    $num_total+= $num_submenu;
                    while($row_submenu = $mysqli->fetchAssoc($submenu)){
                        $html.= '<span '.STYLE7.'>'.$i.'.</span> '.$this->getSearch_link($highlight, $link, $row_submenu['title'], $row_submenu['menu_eng'], $row_submenu['submenu_eng'])."\n";
                        $i++;
                    }
                }
                if($num_menu)
                {
                    $num_total+= $num_menu;
                    while($row_menu = $mysqli->fetchAssoc($menu)){
                        $html.= '<span '.STYLE7.'>'.$i.'.</span> '.$this->getSearch_link($highlight, $link, $row_menu['title'], $row_menu['menu_eng'], '')."\n";
                        $i++;
                    }
                }
            }
            else{
                $html = "По Вашему запросу ничего не найдено";
            }
        }
        else{
            $html = "Введите поисковое слово или фразу";
        }
        $StrEnd = getEndStr($num_total);
        $str = '<div><span class="dotted"><span '.STYLE1.'>'.$this->Word.'</span></span></div>'."\n";
        $str.= 'Найдено <strong>'.$num_total.'</strong> строк'.$StrEnd.'<br /><br />'."\n";
        $this->Content.= $str.$html;
    }

    private function getSearch_link($highlight, $link, $title, $menu_eng, $submenu_eng)
    {
        $html = '';
        if($menu_eng !='' && $submenu_eng !='')
        {

            $title = preg_replace("/(".$highlight.")/ui", "<span class='hilight'>$0</span>", $title);
            $html.= '<a href="'.$link.$menu_eng.'/'.$submenu_eng.'" class="bluelink" target="_blank">'.$title.'</a><br />'."\n";
            $html.= '<a href="'.$link.$menu_eng.'/'.$submenu_eng.'" class="style5" target="_blank">'.$link.$menu_eng.'/'.$submenu_eng.'</a><br /><br />'."\n";
        }
        if($menu_eng !='' && $submenu_eng == '')
        {
            $title = preg_replace("/(".$highlight.")/ui", "<span class='hilight'>$0</span>", $title);
            $html.= '<a href="'.$link.$menu_eng.'" class="bluelink" target="_blank">'.$title.'</a><br />'."\n";
            $html.= '<a href="'.$link.$menu_eng.'" class="style5" target="_blank">'.$link.$menu_eng.'</a><br /><br />'."\n";
        }

        return $html;
    }

} 