<?php
/**
 * Created by PhpStorm.
 * User: Valentina
 * Date: 06.05.14
 * Time: 15:15
 */

class News {
    public $Content;
    public $limit = 0;
    public $pageNum = 1;
    public function __construct($limit=''){
        $this->limit = $limit;
        $this->parseUrl();
    }
    private function parseUrl(){
        global $arResult;
        $result = $_SERVER['REQUEST_URI'];
        $resultArray = preg_split("/(\/)/", $result, -1, PREG_SPLIT_NO_EMPTY);//echo '<pre>';print_r($resultArray);echo '</pre>';
        //echo '<pre>';print_r(($resultArray));echo '</pre>';
        if($arResult->ACTION == 'MainPage' || $arResult->ACTION == 'admin-panel'){
            $this->getNews($this->limit);
        }
        elseif($arResult->ACTION == 'edit_news'){
            $this->getNewsAssoc($resultArray[2]);
        }
        else{
            if(sizeof($resultArray)>1){
                $this->checkUrl($resultArray[1]);

            }
            else{

                $this->getNewsPage($this->limit);
            }
        }
    }
    private function getNewsAssoc($id){
        $mysqli = M_Core_DB::getInstance();
        if($id !=''){
            $row = '';
            $query = "SELECT * FROM ".TABLE_NEWS."
                  WHERE id=".$id;
            try{
                $mysqli->_execute($query);
                if($mysqli->num_rows() > 0){
                    $row = $mysqli->fetch();
                }

            }catch(Exception $e){
                echo $e->getMessage();
            }
            $this->Content = $row;
        }
    }
    private function checkUrl($get){
        $mysqli = M_Core_DB::getInstance();
        $str=strstr($get, 'page=');
        if($str){
            $arr = preg_split('/\=/', $str, -1, PREG_SPLIT_NO_EMPTY);
            $num = intval($arr[1]);
            if(!$num){
                error404(SAPI_NAME, REQUEST_URL);
            }
            else{
                $this->pageNum = $num;
                $this->getNewsPage($this->limit);
            }
        }
        else{
            $num = intval($get);
            if(!$num){
                error404(SAPI_NAME, REQUEST_URL);
            }
            else{
                $query = "SELECT * FROM ".TABLE_NEWS."
                  ORDER BY date DESC";
                try{
                    $mysqli->_execute($query);
                    if($mysqli->num_rows() > 0){
                        $flag = false;
                        while($row = $mysqli->fetch()){
                            if($row["id"] == $num){
                                $flag = true;
                                break;
                            }
                        }
                        if(!$flag) error404(SAPI_NAME, REQUEST_URL);
                    }
                    $this->getNewsOne($num);
                }
                catch(Exception $e){
                    echo $e->getMessage();
                }

            }
        }
    }
    private function getNews($maxRows){
        $mysqli = M_Core_DB::getInstance();
        $html = '';
        $all_news = '';
        $startRow = 0;
        $query = "SELECT * FROM ".TABLE_NEWS."
                  ORDER BY date DESC";
        $query_limit = sprintf("%s LIMIT %d, %d", $query, $startRow, $maxRows);
        try{
            $mysqli->_execute($query_limit);
            if($mysqli->num_rows() > 0){
                while($row = $mysqli->fetch()){
                    $id = $row["id"];
                    $title = $row["title"];
                    $date = $this->getStringTime($row["date"]);
                    $all_news.= file_get_contents(DIR_PATH.'./templates/tpl/page-main-news-one.tpl');
                    $all_news = str_replace( '{id}', $id, $all_news );
                    $all_news = str_replace( '{title}', $title, $all_news );
                    $all_news = str_replace( '{date}', $date, $all_news );
                    $all_news = str_replace( '{back}', '', $all_news );
                }
                $html = file_get_contents(DIR_PATH.'./templates/tpl/page-main-news.tpl');
                $html = str_replace( '{all_news}', $all_news, $html );
            }
        }catch(Exception $e){
            echo $e->getMessage();
        }

        $this->Content = $html;
    }
    private function getNewsPage($maxRows){
        $mysqli = M_Core_DB::getInstance();
        $html = '';
        $pageNum = $this->pageNum;

        if ( $pageNum < 1 || $pageNum == '') $pageNum = 1;
        $startRow = ($pageNum-1)* $maxRows;
        $query = "SELECT * FROM ".TABLE_NEWS."
                  ORDER BY date DESC";
        $query_limit = sprintf("%s LIMIT %d, %d", $query, $startRow, $maxRows);

        try{
            $mysqli->_execute($query_limit);
            if($mysqli->num_rows() > 0){
                while($row = $mysqli->fetch()){
                    $id = $row["id"];
                    $title = '<a href="/news/'.$id.'">'.$row["title"].'</a>';
                    $date = $this->getStringTime($row["date"]);
                    $html.= file_get_contents(DIR_PATH.'./templates/tpl/news-one.tpl');
                    $html = str_replace( '{date}', $date, $html );
                    $html = str_replace( '{title}', $title, $html );
                    $html = str_replace( '{content}', '', $html );
                    $html = str_replace( '{img}', '', $html );
                    $html = str_replace( '{back}', '', $html );
                }

            }
            $mysqli->_execute($query);
            $totalPages = ceil($mysqli->num_rows()/$maxRows);
            if($pageNum > $totalPages && $mysqli->num_rows() > 0) error404(SAPI_NAME, REQUEST_URL);
            if($mysqli->num_rows() > 3){
                $filename = '/news/';
                $uri = 'page';
                $nav_obj = new navPage($totalPages, $pageNum, $filename, $uri, '' );
                $nav = $nav_obj->Content;
                $html.= $nav;
            }

        }catch(Exception $e){
            echo $e->getMessage();
        }
        $this->Content = $html;
    }
    private function getNewsOne($id){
        $mysqli = M_Core_DB::getInstance();
        global $arResult;
        $html = '';
        $query = "SELECT * FROM ".TABLE_NEWS."
                  WHERE id=".$id;
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows() > 0){
                $row = $mysqli->fetch();
                $title = $row["title"];
                $content = print_page($row["content"]);
                $date = $this->getStringTime($row["date"]);
                $back = '<a href="/news">'.BACK_IMG.' все новости </a>';
                $html.= file_get_contents(DIR_PATH.'./templates/tpl/news-one.tpl');
                $html = str_replace( '{date}', $date, $html );
                $html = str_replace( '{title}', $title, $html );
                $html = str_replace( '{content}', $content, $html );
                $html = str_replace( '{img}', '', $html );
                $html = str_replace( '{back}', $back, $html );

            }

        }catch(Exception $e){
            echo $e->getMessage();
        }
        $this->Content = $html;
    }
    private function getStringTime($date)
    {
        $time = date( "d.m.Y", strtotime($date));
        return $time;
    }

} 