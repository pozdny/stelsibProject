<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 20.03.14
 * Time: 12:42
 */

class GetPrice {
    public $Content;
    public function __construct()
    {}
    public function getForm(){
        if(isset($_POST["MM_edit"]) && $_POST["MM_edit"] == 'MM_edit'){
           $this->loadExcel();
        }

        $legend = "Загрузить прайсы";
        $title  = 'Прайс "Архивные стеллажи" (Price_Arhivn.xml) <span class="text-muted">(тип файла Excel 97-2003 (*.xls))</span>';
        $title1 = 'Прайс "Полочные стеллажи"(Price_Polochn.xml) <span class="text-muted">(тип файла Excel 97-2003 (*.xls))</span>';
        $name = 'price_upload';
        $name1 = 'price1_upload';

        $price = file_get_contents( 'admin_panel/templates/price_one.tpl' );
        $price = str_replace( '{title}', $title, $price );
        $price = str_replace( '{title1}', $title1, $price );
        $price = str_replace( '{name}', $name, $price );
        $price = str_replace( '{name1}', $name1, $price );

        //$notice = file_get_contents( 'admin_panel/templates/notice.tpl' );

        $html = file_get_contents( 'admin_panel/templates/main_price.tpl' );
        $html = str_replace( '{legend}', $legend, $html);
        $html = str_replace( '{price}', $price, $html);
        //$html = str_replace( '{notice}', $notice, $html);
        $this->Content = $html;
    }
    private function loadExcel(){
        $mysqli = M_Core_DB::getInstance();
        $files = '';
        if (isset($_FILES["price_upload"]) && !empty($_FILES["price_upload"]["name"]) && (preg_match("/price_arhivn/i", $_FILES["price_upload"]["name"]))){
            $tab = TABLE_PRICES_A;
            $file_name = $_FILES["price_upload"]["name"]; //echo '<pre>';print_r($_FILES["price_upload"]);echo '</pre>';
            $dest_file_name_loc = PATH_EXCEL_LOC.$file_name;
            $data = $this->loadFile($tab, $_FILES["price_upload"]);

            for($j=0, $k=1; $j<1; $j++)
            {
                $i = 2;
                for (; $i<=$data->sheets[$j]["numRows"]; $i++)
                {
                    $title     = '';
                    $size      = '';
                    $p1        = '';
                    $p2        = '';
                    $p3        = '';
                    $cell_id   = '';
                    if(!empty($data->sheets[$j]["cells"][$i][1])){
                        $cell_id = addslashes(trim($data->sheets[$j]["cells"][$i][1]));
                    }
                    if($cell_id == '0')
                    {
                        continue;
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][2])){
                        $title = addslashes(trim($data->sheets[$j]["cells"][$i][2]));
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][3])){
                        $size  = addslashes(trim($data->sheets[$j]["cells"][$i][3]));
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][4])){
                        $p1    = addslashes(trim($data->sheets[$j]["cells"][$i][4]));
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][5])){
                        $p2    = addslashes(trim($data->sheets[$j]["cells"][$i][5]));
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][6])){
                        $p3    = addslashes(trim($data->sheets[$j]["cells"][$i][6]));
                    }


                    $query="INSERT INTO ".$tab." (`id`,`title`, `size`, `p1`, `p2`, `p3`, `cell_id`) VALUES('".$k."', '".$title."', '".$size."', '".$p1."', '".$p2."', '".$p3."', '".$cell_id."')";
                    $mysqli->query($query);
                    $k++;
                }
            }
            $files = $file_name.', ';
            unlink($dest_file_name_loc);
        }
        if(isset($_FILES["price1_upload"]) && !empty($_FILES["price1_upload"]["name"]) && (preg_match("/price_polochn/i", $_FILES["price1_upload"]["name"]))){
            $tab = TABLE_PRICES_P;
            $file_name = $_FILES["price1_upload"]["name"]; //echo '<pre>';print_r($_FILES["price_upload"]);echo '</pre>';
            $dest_file_name_loc = PATH_EXCEL_LOC.$file_name;
            $data = $this->loadFile($tab, $_FILES["price1_upload"]);
            for($j=0, $k=1; $j<1; $j++)
            {
                $i = 4;
                for (; $i<=$data->sheets[$j]["numRows"]; $i++)
                {
                    $title     = '';
                    $size      = '';
                    $power     = '';
                    $word      = '';
                    $p         = '';
                    $weight    = '';
                    $cell_id   = '';
                    if(!empty($data->sheets[$j]["cells"][$i][1])){
                        $cell_id = addslashes(trim($data->sheets[$j]["cells"][$i][1]));
                    }
                    if($cell_id == '0')
                    {
                        continue;
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][2])){
                        $title = addslashes(trim($data->sheets[$j]["cells"][$i][2]));
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][3])){
                        $size  = addslashes(trim($data->sheets[$j]["cells"][$i][3]));
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][4])){
                        $power    = addslashes(trim($data->sheets[$j]["cells"][$i][4]));
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][5])){
                        $word    = addslashes(trim($data->sheets[$j]["cells"][$i][5]));
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][6])){
                        $p    = addslashes(trim($data->sheets[$j]["cells"][$i][6]));
                    }
                    if(!empty($data->sheets[$j]["cells"][$i][7])){
                        $weight    = addslashes(trim($data->sheets[$j]["cells"][$i][7]));
                    }


                    $query="INSERT INTO ".$tab." (`id`,`title`, `size`, `power`, `word`, `price`, `weight`, `cell_id`) VALUES('".$k."', '".$title."', '".$size."', '".$power."', '".$word."', '".$p."', '".$weight."', '".$cell_id."')";
                    $mysqli->query($query);
                    $k++;
                }
            }
            $files.= $file_name.', ';
            unlink($dest_file_name_loc);
        }
        if (isset($_FILES["price_upload"]) && (!empty($_FILES["price_upload"]["name"])) && (preg_match("/price_arhivn/i", $_FILES["price_upload"]["name"]))  || isset($_FILES["price1_upload"]) && (!empty($_FILES["price1_upload"]["name"])) && (preg_match("/price_polochn/i", $_FILES["price1_upload"]["name"])))
        {
            $messages = new Messages('info', 'Файл ('.$files.') успешно загружен в базу данных!', 'prices' );
            echo $messages->Content;
        }
    }
    private function loadFile($tab, $file_array){
        $mysqli = M_Core_DB::getInstance(); //echo '<pre>';print_r($file_array);echo '</pre>';
        $file_name = $file_array["name"];
        $tmp_file_name = $file_array["tmp_name"];
        $dest_file_name_loc = PATH_EXCEL_LOC.$file_name;
        $dest_file_name = PATH_EXCEL.$file_name;
        copy($tmp_file_name, $dest_file_name_loc);
        move_uploaded_file($tmp_file_name, $dest_file_name);

        $data = new Spreadsheet_Excel_Reader();
        $data->setOutputEncoding("UTF-8"); //Кодировка выходных данных
        $data->read($file_name);
        //СТАРТ Считывание из файла Excel и запись в БД
        $query = 'DELETE FROM '.$tab;
        $mysqli->query($query);

        return $data;
    }
} 