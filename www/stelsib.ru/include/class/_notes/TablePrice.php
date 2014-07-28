<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 05.03.14
 * Time: 18:47
 */

class TablePrice {
    public $Content;
    public function __construct(){
        $this->getContent();
    }
    private function getContent(){
        $nav_tab_main = file_get_contents('./templates/nav-tab-main.tpl');
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
                        else $p1 = sprintf("%0.2f", round($row["p1"], 2));
                        if(!$p2) $p2 = '-';
                        else $p2 = sprintf("%0.2f", round($row["p2"], 2));
                        if(!$p3) $p3 = '-';
                        else $p3 = sprintf("%0.2f", round($row["p3"], 2));
                    }
                    if($flag){
                        $tr = '<tr class="tr-elem4"><td colspan="5">'.$title.'</td></tr>';
                    }
                    elseif($flag1){
                        $tr = file_get_contents('./templates/head-table-a.tpl');
                        $tr = str_replace( '{class}', $class, $tr );
                        $tr = str_replace( '{title}', $title, $tr );
                        $tr = str_replace( '{size}', $size, $tr );
                        $tr = str_replace( '{p1}', $p1, $tr );
                        $tr = str_replace( '{p2}', $p2, $tr );
                        $tr = str_replace( '{p3}', $p3, $tr );
                    }
                    else{
                        $tr = file_get_contents('./templates/body-table-a.tpl');
                        $tr = str_replace( '{class}', $class, $tr );
                        $tr = str_replace( '{title}', $title, $tr );
                        $tr = str_replace( '{size}', $size, $tr );
                        $tr = str_replace( '{p1}', $p1, $tr );
                        $tr = str_replace( '{p2}', $p2, $tr );
                        $tr = str_replace( '{p3}', $p3, $tr );
                    }
                    $tbody.= file_get_contents('./templates/price-table-td.tpl');
                    $tbody = str_replace( '{tr}', $tr, $tbody );
                }

                $html = file_get_contents('./templates/price-table.tpl');
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
                        if(!$p) $p = '-';
                        else $p = $row["price"];
                    }
                    if($flag){
                        $tr = file_get_contents('./templates/head-table-p.tpl');
                        $tr = str_replace( '{title}', $title, $tr );
                        $tr = str_replace( '{size}', $size, $tr );
                        $tr = str_replace( '{power}', $power, $tr );
                        $tr = str_replace( '{word}', $word, $tr );
                        $tr = str_replace( '{price}', $p, $tr );
                        $tr = str_replace( '{weight}', $weight, $tr );
                    }
                    elseif($flag1 || $flag3){
                        if($flag1) $tr = '<tr class="tr-elem4"><td colspan="6">'.$title.'</td></tr>';
                        else $tr = '<tr class="tr-elem4-2"><td colspan="6">'.$title.'</td></tr>';
                    }
                    elseif($flag2){
                        $tr = '<tr class="tr-elem4-1"><td colspan="4">'.$title.'</td><td>'.$p.'</td><td>'.$weight.'</td></tr>';
                    }
                    else{
                        $tr = file_get_contents('./templates/body-table-p.tpl');
                        $tr = str_replace( '{class}', $class, $tr );
                        $tr = str_replace( '{title}', $title, $tr );
                        $tr = str_replace( '{size}', $size, $tr );
                        $tr = str_replace( '{power}', $power, $tr );
                        $tr = str_replace( '{word}', $word, $tr );
                        $tr = str_replace( '{price}', $p, $tr );
                        $tr = str_replace( '{weight}', $weight, $tr );
                    }
                    $tbody.= file_get_contents('./templates/price-table-td.tpl');
                    $tbody = str_replace( '{tr}', $tr, $tbody );
                }

                $html  = file_get_contents('./templates/price-table.tpl');
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
