<?php
class ARR_Query {
    public $ACTION='MainPage';
	public $POS1;
    public $POS2;
    public $POS3;
    public $HOST = '';
    public $REGION = 'Новосибирск';
    public $is_category = '';
    public $save_code = '';
    public $DATA = array(
        "id" => "",
        "title" => "",
        "zagolovok" => "",
        "eng" => "",
        "content" => "",
        "content2" => "",
        "Complects" => array(
            "id" => "",
            "title" => "",
            "img" => "",
            "content" => ""
        ),
        "Submenu" => array(
            "id" => "",
            "title" => "",
            "zagolovok" => "",
            "eng" => "",
            "content" => "",
            "Images" => array(
                "id" => "",
                "img" => "",
                "alt" => "",
                "img_title" => ""
            )
        ),
        "Catalog" => array(),
        "TopMenu" => array(),
        "AdminMenu" => array()
    );
    public $NEWS_DATA = array();
    public $UsernameEnter = array(
        "enter" => "",
        "name"  => "",
        "last_date" => "",
        "group" => ""
    );
    public $Scripts = array(
		'girld' => 'off',
		'effect' => 'off'
	);
    public $INFO_SITE = array();
    public $WRONGDATA = false;

	function init(){
        $SxGeo = new SxGeo('SxGeo_GLCity.dat', SXGEO_BATCH | SXGEO_MEMORY);
        $ip = $_SERVER["REMOTE_ADDR"];
        $city = $SxGeo->getCityFull($ip);
        $region_name = $city["region_name"];
        $reg_obj = new HostArr();
        $reg_arr = $reg_obj->getHostArr(); //echo '<pre>';print_r($reg_arr); echo '</pre>';
        /*$adm_arr = array(
            'vladivostok',
            'tumen',
            'ekaterinburg',
            'chelabinsk'
        );*/
        $host = $_SERVER['HTTP_HOST'];
        //$region_name = "Novosibirsk";
        /*if($region_name == "Novosibirsk" && $host !=HOST_NAME)
        {
            header("Location: http://".HOST_NAME);
        }*/
        if($region_name == "Krasnoyarsk" && $host !='krasnoyarsk.'.HOST_NAME)
        {
            header("Location: http://krasnoyarsk.".HOST_NAME);
        }

        foreach($reg_arr as $key => $value){
            if($region_name == $key){
                $host = $value["host"];
            }
            else {
                $host = $_SERVER['HTTP_HOST'];
            }

        }
        $this->HOST = $host;

        foreach($reg_arr as $key => $value){
            if($this->HOST == $value["host"]){
                $this->REGION = $value["title"];
                $_SESSION["region"] = $this->REGION;
            }
        }

        $result = $_SERVER['REQUEST_URI'];
        //проверка входа в админ панель
        if (preg_match ("/([^a-zA-Z0-9\.\/\-\_\#\?\=])/", $result)) {
            $this->WRONGDATA = true;
            return;
        }
        $resultArray = preg_split("/(\/)/", $result, -1, PREG_SPLIT_NO_EMPTY);//echo '<pre>';print_r($resultArray);echo '</pre>';
        $array_url = array();
        if(isset($resultArray[0]) &&  $resultArray[0] == $_SERVER["SERVER_NAME"])
        {
            foreach ($resultArray as $value) {
                if ($value != SERV_NAME)
                    $array_url[] = $value ;
            }
        }
        else{
            $array_url = $resultArray;
        }
        if(isset($array_url[0]) && $array_url[0] != ''){
            $this->ACTION = $array_url[0];
        }
        if (preg_match("/admin-panel/i", $this->ACTION)) {
            $resultArray = preg_split("/(\?)/", $this->ACTION, -1, PREG_SPLIT_NO_EMPTY);
            $array_url = array();
            if(isset($resultArray[0]) &&  $resultArray[0] == $_SERVER["SERVER_NAME"])
            {
                foreach ($resultArray as $value) {
                    if ($value != SERV_NAME)
                        $array_url[] = $value ;
                }
            }
            else{
                $array_url = $resultArray;
            }
            $this->ACTION = $array_url[0];
            if(isset($array_url[1]) && $array_url[1] != ''){
                $this->save_code = $array_url[1];
            }
        }

        if($this->ACTION !='' && $this->ACTION == 'admin-panel' ){//.........................вывод логин формы
            //добавляем состав верхнего меню в DATA
            $topmenu = new Menu('topmenu');
            $this->DATA["TopMenu"] = $topmenu->Content;
        }
        elseif($this->ACTION !='' && $this->ACTION == 'admin'){  // .............................админ панель
            if(isset($array_url[1]) && $array_url[1] != ''){
                $this->ACTION = $array_url[1];
            }
            if(isset($array_url[2]) && $array_url[2] != ''){
                $this->POS1 = $array_url[2];
                //........................................Заполняем Complect для админ панели

                if(!isset($array_url[3]) || (isset($array_url[3]) && $array_url[3] != 'all')){
                    $this->get_data($array_url[1], $array_url[2], 'admin');
                }

            }
            if(isset($array_url[3]) && $array_url[3] != ''){
                $this->POS2 = $array_url[3];
            }
            if(isset($array_url[4]) && $array_url[4] != ''){
                $this->POS3 = $array_url[4];
                //........................................Заполняем Submenu для админ панели
                if($array_url[3] != 'complect' && $array_url[3] != 'all' && !$this->WRONGDATA)
                    $this->get_submenu($array_url[4]);
            }
            if(!$this->WRONGDATA){
                //добавляем состав каталога в DATA
                $catalog = new Catalog();
                $this->DATA["Catalog"] = $catalog->Content;
            }
        }
        else
        { //если не вход в админ панель то проверяем все остальные адреса
            $this->page_site($array_url);
        }
		if (isset($_GET['page']))
		{
			$this->PAGE_NUM = $_GET['page'];
		}
        //данные об авторизации

        if(isset($_SESSION['MM_Username']) && $_SESSION['MM_Username']!='')
        {
            $this->UsernameEnter["enter"] = 'Y';
            $this->UsernameEnter["name"] = $_SESSION['MM_Username']['name'];
            if(isset($_SESSION['last_visit']) && $_SESSION['last_visit'] !=''){
                $this->UsernameEnter["last_date"] = $_SESSION['last_visit']['last_visit'];
            }
            $this->UsernameEnter["group"] = $_SESSION['MM_Username']["rights"];

            //добавляем состав верхнего меню в DATA
            $topmenu = new Menu('topmenu');
            $this->DATA["TopMenu"] = $topmenu->Content;
            //добавляем состав левого меню в DATA
            $adminmenu = new Menu('leftmenu', $this->UsernameEnter["group"]);
            $this->DATA["AdminMenu"] = $adminmenu->Content;
        }
        //добавляем состав TABLE_INFO в arResult
        $this->INFO_SITE = InfoSite::getInfo();
        /*foreach($reg_arr as $key => $value){
            if(in_array($value["eng"], $adm_arr) && $value["eng"].'.'.HOST_NAME == $this->HOST && $this->UsernameEnter["enter"] != "Y"){
                $this->WRONGDATA = true;
                return;
            }
        }*/
	}
	function query( ) {
		$this->init();
	}
	function __construct() {		
			$this->query();		
	}
    //....................все данные которые касаются страниц сайта (Каталог, Верхнее меню)
    function page_site($array_url){
        $mysqli = M_Core_DB::getInstance();
        //если параметров больше чем два значит 404
        if(sizeof($array_url) > 2){
            $this->WRONGDATA = true;
            return;
        }
        if(isset($array_url[0]) &&  $array_url[0] != ''){
            $query = "SELECT * FROM ".NAVIGATOR."
                          WHERE link LIKE '".$array_url[0]."'
                          AND category = 0";
            try{
                $mysqli->_execute($query);
                if($mysqli->num_rows() > 0){
                    $row = $mysqli->fetch();
                    $this->ACTION = $array_url[0];
                    $this->DATA = array(
                        "id" => $row["id"],
                        "title" => $row["title"],
                        "zagolovok" => $row["zagolovok"],
                        "eng" => $row["link"],
                        "content" => $row["content"]
                    );
                    if(isset($array_url[1]) &&  $array_url[1] != '' && $array_url[0] != 'news'){
                        $this->WRONGDATA = true;
                        return;
                    }
                }
                else{
                    $this->get_data($array_url[0], $array_url[0], 'site');
                }
            }
            catch(Exception $e){
                echo $e->getMessage();
            }
        }
        if(isset($array_url[1]) &&  $array_url[1] != '' && $array_url[0] != 'news'){

            if(!$this->WRONGDATA){
                $this->get_submenu($array_url[1]);
            }

            $this->POS1 = $array_url[1];
        }
        //добавляем состав каталога в DATA
        $catalog = new Catalog();
        $this->DATA["Catalog"] = $catalog->Content;
        //добавляем состав верхнего меню в DATA
        $topmenu = new Menu('topmenu');
        $this->DATA["TopMenu"] = $topmenu->Content;

    }

    function get_data($action, $eng, $str = 'site'){
        $mysqli = M_Core_DB::getInstance();

        $query = "SELECT * FROM ".CATALOG_MENU."
                  WHERE eng LIKE '".$eng."'";
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows()>0){
                $row = $mysqli->fetch();
                $this->is_category = 1;
                $complect_obj = new Complects($eng);
                $arr_complect = $complect_obj->Content;
                //находим название класса
                $query = "SELECT DISTINCT ".CLASS_TABLE.".title FROM ".CLASS_TABLE."
                          INNER JOIN ".CATALOG_MENU." ON ".CLASS_TABLE.".id=".$row["class_id"];
                try{
                    $mysqli->_execute($query);
                    $row2 = $mysqli->fetch();
                    $class = $row2["title"];

                    $this->DATA = array(
                        "id" => $row["id"],
                        "title" => $row["title"],
                        "zagolovok" => $row["zagolovok"],
                        "eng" => $row["eng"],
                        "content" => $row["content"],
                        "content2" => $row["content2"],
                        "class"   => $class,
                        "Complects" => $arr_complect,
                        "Catalog"  => $this->DATA["Catalog"],
                        "TopMenu"  => $this->DATA["TopMenu"],
                        "AdminMenu"=> $this->DATA["AdminMenu"]

                    );
                    $this->ACTION = $action;
                }
                catch(Exception $e){
                    echo $e->getMessage();
                }

            }
            else{
                if($str == 'admin'){
                    return;
                }
                else{
                    $this->WRONGDATA = true;
                }
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    function get_submenu($eng){
        $mysqli = M_Core_DB::getInstance();
        $query = "SELECT ".CATALOG_SUBMENU.".id, ".CATALOG_SUBMENU.".title, ".CATALOG_SUBMENU.".zagolovok, ".CATALOG_SUBMENU.".eng, ".CATALOG_SUBMENU.".class_id, ".CATALOG_SUBMENU.".content FROM ".CATALOG_SUBMENU."
                          INNER JOIN ".CATALOG_MENU." ON ".CATALOG_SUBMENU.".menu_id = ".$this->DATA["id"]."
                          WHERE ".CATALOG_SUBMENU.".eng LIKE '".$eng."'";
        try{
            $mysqli->_execute($query);
            if($mysqli->num_rows() > 0){
                $arr_images = null;
                $row = $mysqli->fetch();
                $query = "SELECT * FROM ".TABLE_IMAGES."
                              WHERE sub_id= ".$row["id"];
                try{
                    $mysqli->_execute($query);
                    if($mysqli->num_rows() > 0){
                        while($row1 = $mysqli->fetch()){
                            $arr_images[] = array(
                                "id" => $row1["id"],
                                "img" => $row1["img"],
                                "alt" => $row1["alt"],
                                "img_title" => $row1["img_title"]

                            );
                        }
                    }
                    //находим название класса
                    $query = "SELECT DISTINCT ".CLASS_TABLE.".title FROM ".CLASS_TABLE."
                              INNER JOIN ".CATALOG_SUBMENU." ON ".CLASS_TABLE.".id=".$row["class_id"];
                    try{
                        $mysqli->_execute($query);
                        $row2 = $mysqli->fetch();
                        $class = $row2["title"];

                        $arr_submenu = array(
                            "id" => $row["id"],
                            "title" => $row["title"],
                            "zagolovok" => $row["zagolovok"],
                            "eng" => $row["eng"],
                            "content" => $row["content"],
                            "class" => $class,
                            "Images" => $arr_images
                        );

                        $this->DATA["Submenu"] = $arr_submenu;
                    }
                    catch(Exception $e){
                        echo $e->getMessage();
                    }
                }
                catch(Exception $e){
                    echo $e->getMessage();
                }
            }
            else{
                $this->WRONGDATA = true;
                return;
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
}

