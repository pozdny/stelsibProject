<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 05.03.14
 * Time: 18:47
 */

class TablePrice {
    public $Content;
    public function __construct($sub = false, $sub_id='', $cat_id=''){
        if(!$sub) $this->getPriceContent();
        else $this->getTableSub($sub_id, $cat_id);
    }
    private function getTableSub($sub_id, $cat_id){
        $mysqli = M_Core_DB::getInstance();
        $html = '';
        if($cat_id == 1){
            $tab = TABLE_PRICES_A;
            $query = "SELECT * FROM ".CATALOG_SUBMENU."
                      WHERE id=".$sub_id;
            $mysqli->_execute($query);
            $row = $mysqli->fetch();
            $eng = $row["eng"];
            if($eng == 'options') $eng.= '-a';
            $query = "SELECT * FROM ".$tab."
                      WHERE sub_id=".$sub_id;
            try{
                $mysqli->_execute($query);
                if($mysqli->num_rows() > 0){
                    $tr = '';
                    while($row = $mysqli->fetch()){
                        $title = $row["title"];
                        $size = $row["size"];
                        if($size == '') $size = '-';
                        $p1 = sprintf("%0.2f", round($row["p1"] + $row["p1"]*RATE, 2));
                        $p2 = sprintf("%0.2f", round($row["p2"] + $row["p2"]*RATE, 2));
                        $p3 = sprintf("%0.2f", round($row["p3"] + $row["p3"]*RATE, 2));
                        if($p1 == '' || $p1 == 0) $p1 = '-';
                        if($p2 == '' || $p2 == 0) $p2 = '-';
                        if($p3 == '' || $p3 == 0) $p3 = '-';
                        $class = 'class="tr-bold"';
                        $tr.= file_get_contents(DIR_PATH.'./templates/tpl/tables/body-'.$eng.'.tpl');
                        $tr = str_replace( '{class}', $class, $tr );
                        $tr = str_replace( '{title}', $title, $tr );
                        $tr = str_replace( '{size}', $size, $tr );
                        $tr = str_replace( '{p1}', $p1, $tr );
                        $tr = str_replace( '{p2}', $p2, $tr );
                        $tr = str_replace( '{p3}', $p3, $tr );
                    }
                    $head = file_get_contents(DIR_PATH.'./templates/tpl/tables/head-'.$eng.'.tpl');
                    $tbody = file_get_contents(DIR_PATH.'./templates/tpl/tables/price-table-sub.tpl');
                    $tbody = str_replace( '{head}', $head, $tbody );
                    $tbody = str_replace( '{tr}', $tr, $tbody );
                    $html = file_get_contents(DIR_PATH.'./templates/tpl/tables/price-table-submain.tpl');
                    $html = str_replace( '{tbody}', $tbody, $html );
                }
                else{
                    $html = NODATA;
                }
                $this->Content = $html;
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
        }
        else{
            $tab = TABLE_PRICES_P;
            $query = "SELECT * FROM ".CATALOG_SUBMENU."
                      WHERE id=".$sub_id;
            $mysqli->_execute($query);
            $row = $mysqli->fetch();
            $eng = $row["eng"];
            if($eng == 'options') $eng.= '-p';
            $query = "SELECT * FROM ".$tab."
                      WHERE sub_id=".$sub_id;
            try{
                $mysqli->_execute($query);
                if($mysqli->num_rows() > 0){
                    $tr = '';
                    while($row = $mysqli->fetch()){
                        $cell_id = $row["cell_id"];
                        $title = $row["title"];
                        $size = $row["size"];
                        $power = $row["power"];
                        $word = $row["word"];
                        $weight = $row["weight"];
                        if($size == '') $size = '-';
                        $price = sprintf("%0.2f", round($row["price"] + $row["price"]*RATE, 2));
                        if($price == '') $price = '-';
                        if($word == '') $word = '-';
                        if($power == '') $power = '-';
                        if($weight == '') $weight = '-';
                        if($eng == "options-p"){
                            if(intval($weight)){
                                $weight+= $weight*RATE;
                            }
                            $price = $row["price"];
                        }
                        if($price == '') $price = '-';
                        if($cell_id == 2 && $eng == 'rami'){
                            $tr.= '<tr class="tr-elem4"><td colspan="5">'.$title.'</td></tr>';
                        }
                        elseif($cell_id == 2 && $eng == 'nastil'){
                            $tr.= '<tr class="tr-elem4"><td colspan="6">'.$title.'</td></tr>';
                        }
                        elseif($cell_id == 4 && $eng == 'balki'){
                            $price = $row["price"];
                            $tr.= '<tr class="tr-elem4"><td colspan="4">'.$title.'</td><td>'.$price.'</td><td></td></tr>';
                        }
                        elseif($cell_id == 4 && $eng == 'options-p'){
                            $price = $row["weight"];
                            $tr.= '<tr class="tr-elem4"><td colspan="3">'.$title.'</td><td></td><td>'.$price.'</td></tr>';
                        }
                        else{
                            $tr.= file_get_contents(DIR_PATH.'./templates/tpl/tables/body-'.$eng.'.tpl');
                            $tr = str_replace( '{title}', $title, $tr );
                            $tr = str_replace( '{size}', $size, $tr );
                            $tr = str_replace( '{power}', $power, $tr );
                            $tr = str_replace( '{word}', $word, $tr );
                            $tr = str_replace( '{price}', $price, $tr );
                            $tr = str_replace( '{weight}', $weight, $tr );
                        }

                    }
                    $head = file_get_contents(DIR_PATH.'./templates/tpl/tables/head-'.$eng.'.tpl');
                    $tbody = file_get_contents(DIR_PATH.'./templates/tpl/tables/price-table-sub.tpl');
                    $tbody = str_replace( '{head}', $head, $tbody );
                    $tbody = str_replace( '{tr}', $tr, $tbody );
                    $html = file_get_contents(DIR_PATH.'./templates/tpl/tables/price-table-submain.tpl');
                    $html = str_replace( '{tbody}', $tbody, $html );
                }
                else{
                    $html = NODATA;
                }
                $this->Content = $html;
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
        }



    }
    private function getPriceContent(){
        $nav_tab_main = file_get_contents(DIR_PATH.'./templates/tpl/tables/nav-tab-main.tpl');
        $nav_tab_main = str_replace( '{arhivn}', $this->getTableArhivn(), $nav_tab_main );
        $nav_tab_main = str_replace( '{polochn}', $this->getTablePolochn(), $nav_tab_main );
        $this->Content = $nav_tab_main;
        //$this->Content = $this->getTableArhivn().$this->getTablePolochn();
    }
    private function getTableArhivn(){
        $mysqli = M_Core_DB::getInstance();
        $query = "SELECT * FROM ".TABLE_PRICES_A;
        $html = '';
        $tbody   = '';

        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows() > 0){
                while($row = $mysqli->fetch()){
                    $flag = false;
                    $flag1 = false;
                    $title  = $row["title"];
                    $size   = $row["size"];
                    $p1     = $row["p1"];
                    $p2     = $row["p2"];
                    $p3     = $row["p3"];
                    $class  = '';
                    if($row["cell_id"] == 1){
                        $flag1 = true;
                    }
                    elseif($row["cell_id"] == 2){
                        $class = 'class="tr-bold tr-elem4"';
                    }
                    elseif($row["cell_id"] == 3){
                        $flag = true;
                    }
                    else{
                        if(!$size) $size = '-';
                        if(!$p1) $p1 = '-';
                        else $p1 = sprintf("%0.2f", round($row["p1"] + $row["p1"]*RATE, 2));
                        if(!$p2) $p2 = '-';
                        else $p2 = sprintf("%0.2f", round($row["p2"] + $row["p2"]*RATE, 2));
                        if(!$p3) $p3 = '-';
                        else $p3 = sprintf("%0.2f", round($row["p3"] + $row["p3"]*RATE, 2));
                    }
                    if($flag){
                        $tr = '<tr class="tr-elem4"><td colspan="5">'.$title.'</td></tr>';
                    }
                    elseif($flag1){
                        $tr = file_get_contents(DIR_PATH.'./templates/tpl/tables/head-table-a.tpl');
                        $tr = str_replace( '{class}', $class, $tr );
                        $tr = str_replace( '{title}', $title, $tr );
                        $tr = str_replace( '{size}', $size, $tr );
                        $tr = str_replace( '{p1}', $p1, $tr );
                        $tr = str_replace( '{p2}', $p2, $tr );
                        $tr = str_replace( '{p3}', $p3, $tr );
                    }
                    else{
                        $tr = file_get_contents(DIR_PATH.'./templates/tpl/tables/body-table-a.tpl');
                        $tr = str_replace( '{class}', $class, $tr );
                        $tr = str_replace( '{title}', $title, $tr );
                        $tr = str_replace( '{size}', $size, $tr );
                        $tr = str_replace( '{p1}', $p1, $tr );
                        $tr = str_replace( '{p2}', $p2, $tr );
                        $tr = str_replace( '{p3}', $p3, $tr );
                    }
                    $tbody.= file_get_contents(DIR_PATH.'./templates/tpl/tables/price-table-td.tpl');
                    $tbody = str_replace( '{tr}', $tr, $tbody );
                }

                $html = file_get_contents(DIR_PATH.'./templates/tpl/tables/price-table.tpl');
                $html = str_replace( '{tbody}', $tbody, $html );
            }
            else{
                $html = NODATA;
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $html;
    }
    private function getTablePolochn(){
        $mysqli = M_Core_DB::getInstance();
        $query = "SELECT * FROM ".TABLE_PRICES_P;
        $html = '';
        $tbody   = '';
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows() > 0){
                $flag4 = false;
                while($row = $mysqli->fetch()){
                    $flag = false;
                    $flag1 = false;
                    $flag2 = false;
                    $flag3 = false;
                    $title  = $row["title"];
                    $size   = $row["size"];
                    $power  = $row["power"];
                    $word   = $row["word"];
                    $p      = $row["price"];
                    $weight = $row["weight"];
                    $class  = '';
                    if($row["cell_id"] == 1){
                        $flag = true;
                    }
                    elseif($row["cell_id"] == 2){
                        $flag3 = true;
                    }
                    elseif($row["cell_id"] == 3){
                        $flag1 = true;
                    }
                    elseif($row["cell_id"] == 4){
                        $flag2 = true;
                    }
                    else{
                        if(!$p){
                            $p = '-';
                        }
                        else{
                            $p = $row["price"];
                            if(intval($p)){
                                $p = $row["price"] + $row["price"]*RATE;
                            }
                        }
                    }
                    if($flag){
                        $tr = file_get_contents(DIR_PATH.'./templates/tpl/tables/head-table-p.tpl');
                        $tr = str_replace( '{title}', $title, $tr );
                        $tr = str_replace( '{size}', $size, $tr );
                        $tr = str_replace( '{power}', $power, $tr );
                        $tr = str_replace( '{word}', $word, $tr );
                        $tr = str_replace( '{price}', $p, $tr );
                        $tr = str_replace( '{weight}', $weight, $tr );
                    }
                    elseif($flag1 || $flag3){
                        if($flag1){
                            $tr = '<tr class="tr-elem4"><td colspan="6">'.$title.'</td></tr>';
                            if($title == "Аксессуары"){
                                $flag4 = true;
                            }
                        }
                        else $tr = '<tr class="tr-elem4-2"><td colspan="6">'.$title.'</td></tr>';
                    }
                    elseif($flag2){
                        $tr = '<tr class="tr-elem4-1"><td colspan="4">'.$title.'</td><td>'.$p.'</td><td>'.$weight.'</td></tr>';
                    }
                    else{
                        if($flag4){
                            if(intval($weight))
                                $weight = $row["weight"] + $row["weight"]*RATE;
                        }
                        $tr = file_get_contents(DIR_PATH.'./templates/tpl/tables/body-table-p.tpl');
                        $tr = str_replace( '{class}', $class, $tr );
                        $tr = str_replace( '{title}', $title, $tr );
                        $tr = str_replace( '{size}', $size, $tr );
                        $tr = str_replace( '{power}', $power, $tr );
                        $tr = str_replace( '{word}', $word, $tr );
                        $tr = str_replace( '{price}', $p, $tr );
                        $tr = str_replace( '{weight}', $weight, $tr );
                    }
                    $tbody.= file_get_contents(DIR_PATH.'./templates/tpl/tables/price-table-td.tpl');
                    $tbody = str_replace( '{tr}', $tr, $tbody );
                }

                $html  = file_get_contents(DIR_PATH.'./templates/tpl/tables/price-table.tpl');
                $html  = str_replace( '{tbody}', $tbody, $html );
            }
            else{
                $html = NODATA;
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
        return $html;
    }

}
