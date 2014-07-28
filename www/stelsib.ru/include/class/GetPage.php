<?php
class GetPage
{
	private $Action     = '';
	private $Titlepage  = 'Стелсиб';
    private $Keywords   = 'Стелсиб';
    private $Description  = 'Стелсиб';
	public $Content     = '';
	private $Head       = '';
    private $TopMenu    = '';
    private $BreadCrumb = '';
	private $Left       = '';
    public static $Footer     = '';
	private $TopLink    = '';
    public static $BottomLink = '';
    private $CatElem    = 'no';
	public static $Nikname   = '';
	const AUTHOR = 'Валентина Позднякова';
	const TITLE  = 'Стелсиб';
	public static $Title = 'Главная';
    private $exclud = 'yakutsk';

	public function __construct($action)
	{
		$this->Action = $action;
        $this->getTopMenu();
        $this->getHead();
		$this->getLeft();
		$this->getFooter();
		$this->getTopLink();
        $this->getBottomLink();
		$this->getMetatags();
		$this->getTitle();
		$this->getTemplate();
    }
	private function getTitle()
	{
        global $arResult;
        $Data = $arResult->DATA;
        if(isset($Data["Submenu"]))
        {
            $Submenu = $Data["Submenu"];
            $title = $Submenu["zagolovok"];
        }
        else{
            if(isset($Data["zagolovok"]))
            {
                $title = $Data["zagolovok"];
            }
            else{
                $title = '';
            }
        }
        self::$Title = $title;
	}
	private function getMetatags()
	{
        global $arResult;
        global $Catalog;
        $action = $arResult->ACTION;
        $Pages = $arResult->DATA["TopMenu"];
        $pos1 = $arResult->POS1;
        $menu_id = $arResult->DATA['id'];
        $titlepage   = '';
        $keywords    = '';
        $description = '';
        if($action == 'admin-panel') $this->Titlepage = "Логин".' - '.$this->Titlepage;
        else{
            if(isset($Pages) && sizeof($Pages) !=0){
                foreach($Pages as $key => $value){
                    if($value["link"] != 'arhivnye-stellazhi' && $value["link"] != 'polochnye-stellazhi')
                    {
                        if($action == $value["link"]){
                            if($value["titlepage"] !='')
                                $titlepage = $value["titlepage"];
                            if($value["keywords"] !='')
                                $keywords = $value["keywords"];
                            if($value["description"] !='')
                                $description = $value["description"];
                        }
                    }
                    else
                    {
                        if(isset($Catalog) && sizeof($Catalog) !=0){
                            if($pos1 == ''){
                                foreach($Catalog as $key => $menu){
                                    if($action == $menu["eng"]){
                                        if($menu["titlepage"] !='')
                                            $titlepage = $menu["titlepage"];
                                        if($menu["keywords"] !='')
                                            $keywords = $menu["keywords"];
                                        if($menu["description"] !='')
                                            $description = $menu["description"];
                                    }
                                }
                            }
                            else{
                                foreach($Catalog as $key => $menu){
                                    $Submenu = $menu["Submenu"];
                                    foreach($Submenu as $key => $submenu){
                                        if($pos1 == $submenu["eng"] && $submenu["menu_id"] == $menu_id){
                                            if($submenu["titlepage"] !='')
                                                $titlepage = $submenu["titlepage"];
                                            if($submenu["keywords"] !='')
                                                $keywords = $submenu["keywords"];
                                            if($submenu["description"] !='')
                                                $description = $submenu["description"];
                                        }
                                    }

                                }
                            }

                        }

                    }

                }
            }
            $this->Titlepage = $titlepage;
            $this->Keywords    = $keywords;
            $this->Description = $description;
        }
	}
	private function getTopLink()
	{
		$this->TopLink.= '<link rel="stylesheet" href="'.HOME_PATH.'/css/bootstrap/bootstrap.min.css" type="text/css" />'."\n";
        $this->TopLink.= '<link rel="stylesheet" href="/css/style.css" type="text/css" />'."\n";
        $this->TopLink.= '<link  rel="stylesheet" href="/css/formstyler.css" type="text/css">'."\n";
        //$this->TopLink.= '<link rel="stylesheet" href="'.HOME_PATH.'/css/order.css" type="text/css" />'."\n";
        $this->TopLink.= '<script type="text/javascript" src="'.HOME_PATH.'/js/jquery/jquery-2.0.3.js"></script>'."\n";
        //$this->TopLink.= '<script type="text/javascript" src="'.HOME_PATH.'/js/jquery/jquery-migrate-1.1.1.js"></script>'."\n";
        $this->TopLink.= '<script type="text/javascript" src="/js/jquery/jquery.formstyler.min.js" ></script>'."\n";
        $this->TopLink.= '<link rel="icon" href="'.HOME_PATH.'/favicon.ico" type="image/x-icon" />'."\n";
        $this->TopLink.= '<link rel="shortcut icon" href="'.HOME_PATH.'/favicon.ico" type="image/x-icon" />';
	}
    private function getBottomLink()
    {
        self::$BottomLink.= '<script type="text/javascript" src="'.HOME_PATH.'/js/bootstrap/bootstrap.min.js"></script>'."\n";
        self::$BottomLink.= '<script type="text/javascript" src="'.HOME_PATH.'/js/jquery/jquery.fancybox.pack.js?v=2.1.5"></script>'."\n";
        self::$BottomLink.= '<script type="text/javascript" src="'.HOME_PATH.'/js/jquery/jquery.validate.js"></script>'."\n";
        self::$BottomLink.= '<script type="text/javascript" src="'.HOME_PATH.'/js/scripts.js"></script>'."\n";
    }

    private function getTopMenu()
    {
        global $arResult;
        $TopMenu = $arResult->DATA["TopMenu"];
        $li = '';
        if(isset($TopMenu)){
            foreach($TopMenu as $key => $value){
                if($value["category"] == 1 && $value["link"] == $this->Action)
                    $this->CatElem = "yes";
            }
            foreach($TopMenu as $key => $value){
                if($value["top_menu"] == 1){
                    $id_link = $value["link"];
                    $link = HOME_URL;
                    ($value["link"] == $this->Action)? $active_class=' active': $active_class='';
                    if($value["link"] == 'arhivnye-stellazhi' && $this->CatElem == 'yes') $active_class=' active';
                    ($value["link"] == 'MainPage')? $link = $link : $link.= '/'.$value["link"];
                    if($value["link"] == 'arhivnye-stellazhi'){
                        $id_link = 'arhivnye_stellazhi';
                        $value['title'] = "Каталог";
                    }
                    $li.= '<li class="li_'.$id_link.$active_class.'"><a href="'.$link.'" >'.$value["title"].'</a></li>';
                }
            }
        }
        $action = '/result-search';
        $search = file_get_contents(DIR_PATH.'./templates/tpl/search-form.tpl');
        $search = str_replace( '{action}', $action, $search );
        $search = str_replace( '{path}', HOME_PATH, $search );
        $html = file_get_contents(DIR_PATH.'./templates/tpl/top-menu.tpl');
        $html = str_replace( '{li}',$li, $html);
        $html = str_replace( '{search_form}',$search, $html);
        $this->TopMenu = $html;
    }
	private function getHead()
	{
        $adm_arr = array(
            'voroneg',
            'moscow'
         );
        global $arResult;
        $hello = privet();
        $host = $arResult->HOST;
        $selected = '';
        $job_time = '';
        $options = '';
        if($arResult->UsernameEnter["enter"] == 'Y')
        {
            $block_true = 'block-true';
        }
        else
        {
            $block_true = 'block-false';
        }
        $name  = $arResult->INFO_SITE["name"];
        $phone = $arResult->INFO_SITE["phone"];
        $email = $arResult->INFO_SITE["email"];
        $reg_arr = HostArr::getHostArr();
        foreach($reg_arr as $key => $value){

            if($host == $value["host"]){
                $selected = 'selected="selected"';
                if($value["host"] == HOST_NAME){
                    $job_time = '';
                }
                else{
                    $time = $arResult->INFO_SITE["shadule"];
                    $job_time = file_get_contents(DIR_PATH.'./templates/tpl/header-job-time.tpl');
                    $job_time = str_replace( '{time}', $time, $job_time );
                }
            }
            else{
                $selected = '';
            }
            if(in_array($value["eng"], $adm_arr) && $arResult->UsernameEnter["enter"] != "Y") continue;
            $options.= '<option '.$selected.' value="'.$value["host"].'">'.$value["title"].'</option>';
        }
		$html = file_get_contents(DIR_PATH.'./templates/tpl/header.tpl');
        $html = str_replace( '{block_true}', $block_true, $html );
		$html = str_replace( '{hello}',$hello, $html);
        $html = str_replace( '{options}',$options, $html);
        $html = str_replace( '{name}',$name, $html);
        $html = str_replace( '{phone}',$phone, $html);
        $html = str_replace( '{email}',$email, $html);
        $html = str_replace( '{job_time}',$job_time, $html);
		$this->Head = $html;
	}
	private function getLeft()
	{
        global $arResult;
        global $Catalog;
        $li = '';
        $action = $arResult->ACTION;
        $pos1   = $arResult->POS1;
        foreach($Catalog as $key => $menu){
            $eng_menu   = $menu["eng"];
            $title_menu = $menu["title"];
            ($eng_menu == $action)? $li_menu = "li_menuActiv" : $li_menu = "li_menu";

            $a_link = '<a href="'.DOMEN_LOC.'/'.$eng_menu.'"><span id="'.$eng_menu.'">'.$title_menu.'</span></a>';

            $li.='<li class="'.$li_menu.'">'.$a_link;
            if(isset($menu["Submenu"])){
                 $li.='<ul class="submenu">';
                 foreach($menu["Submenu"] as $key => $submenu){
                     $eng_submenu   = $submenu["eng"];
                     $title_submenu = $submenu["title"];
                     $a_link_sub = '<a href="'.DOMEN_LOC.'/'.$eng_menu.'/'.$eng_submenu.'"><span>'.$title_submenu.'</span></a>';
                     ($eng_submenu == $pos1 && $menu["eng"] == $action)? $li_submenu = "li_submenuActiv" : $li_submenu = "li_submenu";
                     $li.='<li class="'.$li_submenu.'">'.$a_link_sub.'</li>';
                }
                $li.='</ul>';
            }
            $li.='</li>';
        }
		$html = file_get_contents(DIR_PATH.'./templates/tpl/left.tpl');
        $html = str_replace( '{-admin}','', $html);
		$html = str_replace( '{li}',$li, $html);
		$this->Left = $html;
	}
	private  function getFooter()
	{
        global $arResult;

        if(isset($arResult->INFO_SITE)){
            $info_site = $arResult->INFO_SITE;
            $schet_1 = $info_site["schet"];
        }
        else $schet_1 = '';
        if($arResult->UsernameEnter["enter"] == "Y"){
            $class_true = 'block-true';
        }
        else{
            $class_true = 'block-false';
        }
		$date = date('Y');

		$html = file_get_contents(DIR_PATH.'./templates/tpl/footer.tpl');
		$html = str_replace( '{date}',$date, $html);
        $html = str_replace( '{class_true}',$class_true, $html);
        $html = str_replace( '{schet_1}',$schet_1, $html);
		self::$Footer = $html;
	}
	
	private function getTemplate()
	{
        global $arResult;
		$head        = $this->Head;
        $top_menu    = $this->TopMenu;
		$footer      = self::$Footer;
		$titlepage   = $this->Titlepage;
        $keywords    = $this->Keywords;
        $description = $this->Description;
		$top_link    = $this->TopLink;
        $bottom_link = self::$BottomLink;
        $bread_obj   = new BreadCrumbs();
        $breadcrumbs = $bread_obj->Content;
		$title       = self::$Title;
        $titleblock = file_get_contents(DIR_PATH.'./templates/tpl/title.tpl');
        $titleblock = str_replace( '{title}',$title, $titleblock);
        $edit       = $this->getEdit();
        $predzakaz  = '';
        if( $arResult->ACTION == "MainPage" || $arResult->ACTION == "admin-panel")
        {
            $pop_obj = new PopProd();
            $row_pop = $pop_obj->Content;
            $row_bottom_info = file_get_contents(DIR_PATH.'./templates/tpl/bottom-info.tpl');
            $col_content = "col-xs-12";
        }
        else
        {
            $row_pop = '';
            $row_bottom_info = '';
            $col_content = "col-xs-9";
        }

        $predzakaz = '<div id="order_online"  data-toggle="modal" data-target="#myModalPred" role="button"><i class="fa fa-edit" title="Предзаказ"></i> Предзаказ</div>';
        $backcall = '<div id="order_backcall"  data-toggle="modal" data-target="#myModalBack" role="button"><i class="fa fa-phone" title="Обратный звонок"></i> <div class="cen">Обратный<br> звонок</div> </div>';

		switch($this->Action)
		{
			case 'MainPage':
                $left = '';
                $titleblock = "";
                $content = $this->getMainPage();
			break;
			case 'about':
            case 'oplata':
            case 'contacts':
            case 'prices':
                $left = $this->Left;
                $content = $this->StaticPage();
			break;
            case 'arhivnye-stellazhi':
            case 'polochnye-stellazhi':
                $left = $this->Left;
                $content = $this->CatalogPage();
            break;
            case 'news':
                $left = $this->Left;
                $content = $this->NewsPage();
            break;
            case 'result-search':
                $left = $this->Left;
                $content = $this->getResultSearch();
            break;
            case 'admin-panel':
                $left = '';
                $titleblock = "";
                $content = $this->getAdminPanel();
            break;
			default:
            $left = '';
            $content = $this->getMainPage();
		}
		
		$html = file_get_contents(DIR_PATH.'./templates/tpl/index.tpl');
		$html = str_replace( '{top_link}',$top_link, $html);
		$html = str_replace( '{titlepage}',$titlepage, $html);
        $html = str_replace( '{keywords}',$keywords, $html);
        $html = str_replace( '{description}',$description, $html);
		$html = str_replace( '{head}',$head, $html);
        $html = str_replace( '{top_menu}',$top_menu, $html);
		$html = str_replace( '{left}', $left, $html );
        $html = str_replace( '{edit}', $edit, $html );
        $html = str_replace( '{breadcrumbs}', $breadcrumbs, $html );
        $html = str_replace( '{title}', $titleblock, $html );
		$html = str_replace( '{content}', $content, $html );
		$html = str_replace( '{footer}', $footer, $html );
        $html = str_replace( '{bottom_link}',$bottom_link, $html);
        $html = str_replace( '{col_content}',$col_content, $html);
        $html = str_replace( '{row_pop}',$row_pop, $html);
        $html = str_replace( '{row_bottom_info}',$row_bottom_info, $html);
        $html = str_replace( '{predzakaz}',$predzakaz, $html);
        $html = str_replace( '{backcall}',$backcall, $html);
		$this->Content = $html;
		
	}
	private function getMainPage()
	{
        global $arResult;

        $host = $arResult->HOST;
        $topmenu = $arResult->DATA["TopMenu"];
        $title = '';
        $content = '';
        $include_content = '';
        $link = DOMEN_LOC.'/arhivnye-stellazhi';
        $link2 = DOMEN_LOC.'/polochnye-stellazhi';
        foreach($topmenu as $key => $value){
            if($value["link"] == 'MainPage'){
                $title = $value["zagolovok"];
                $content = print_page($value["content"]);
            }
        }
        if($content == ''){
            $content = NODATA;
        }
        $promo_obj = new Promo();
        $promo = $promo_obj->Content;

        $news_obj = new News(4);
        $news = $news_obj->Content;
        if($host !=HOST_NAME && $host != $this->exclud.'.'.HOST_NAME){
            $include_content = print_page(file_get_contents(DIR_PATH.'./templates/tpl/include-blocks/mainPage.tpl'));
        }
        $html = file_get_contents(DIR_PATH.'./templates/tpl/page-main.tpl');
        $html = str_replace( '{title}', $title, $html );
        $html = str_replace( '{link}', $link, $html );
        $html = str_replace( '{link2}', $link2, $html );
        $html = str_replace( '{promo}', $promo, $html );
        $html = str_replace( '{content}', $content, $html );
        $html = str_replace( '{include_content}', $include_content, $html );
        $html = str_replace( '{news}', $news, $html );
		return $html;
	}
    private function NewsPage()
    {
        global $arResult;
        if($arResult->ACTION !='' && $arResult->POS1 == ''){
            $news_obj = new News(10);
            $html = $news_obj->Content;

            return $html;
        }
        if($arResult->ACTION !='' && $arResult->POS1 != ''){
            $news_obj = new News();
            $html = $news_obj->Content;
            return $html;

        }
    }
	private function StaticPage()
	{
        return $this->fileContent();
	}
    private function CatalogPage()
    {
        global $arResult;
        //echo '<pre>'; print_r($arResult); echo '</pre>';
        if($arResult->ACTION !='' && $arResult->POS1 == ''){
            return $this->MenuContent();

        }
        if($arResult->ACTION !='' && $arResult->POS1 != ''){
            return $this->SubMenuContent();
        }
    }
	protected function fileContent()
	{
        global $arResult;
        $host = $arResult->HOST;
        $html = '';
        $include_content = '';
        $Data    = $arResult->DATA; //priint_r($arResult);
        $content = print_page($Data["content"]);
        $table_content = '';
        if($content == ''){
            $content = NODATA;
        }
        if($host !=HOST_NAME){
            if($arResult->ACTION == 'oplata' && $host != $this->exclud.'.'.HOST_NAME){
               $include_content = print_page(file_get_contents(DIR_PATH.'./templates/tpl/include-blocks/oplataPage.tpl'));
            }
            elseif($arResult->ACTION == 'prices' && $host != $this->exclud.'.'.HOST_NAME){
                $include_content = print_page(file_get_contents(DIR_PATH.'./templates/tpl/include-blocks/pricesPage.tpl'));
            }
            else{
                $include_content = '';
            }
        }
		$html = $include_content.file_get_contents(DIR_PATH.'./templates/tpl/content.tpl');
		$html = str_replace( '{content}', $content, $html );
        if($this->Action == 'prices'){
            $table = new TablePrice();
            $table_content = $table->Content;
            if($content == NODATA && $table_content == NODATA){
                $html = NODATA;
                return $html;
            }
        }
        $html.= $table_content;
		return $html;
	}
    protected function MenuContent()
    {
        global $arResult;
        $Data = $arResult->DATA;
        $host = $arResult->HOST;
        $toptext = '';
        $bottomtext = '';
        $content = '';
        $content2 = '';
        $include_content = '';
        $toptext = print_page($Data["content"]);
        $bottomtext = print_page($Data["content2"]);
        if($toptext !='')
        {
            $content = file_get_contents(DIR_PATH.'./templates/tpl/catalog-toptext.tpl');
            $content = str_replace( '{content}', $toptext, $content );

            if($host !=HOST_NAME && $host != $this->exclud.'.'.HOST_NAME){
                $include_content = print_page(file_get_contents(DIR_PATH.'./templates/tpl/include-blocks/catalogPage.tpl'));
            }
            $content.= $include_content;
        }
        if($Data["class"] !=''){
            $class_name = $Data["class"];
            $element = new $class_name($Data);
            if(isset($Data["Complects"]) && sizeof($Data["Complects"]) !=0){
                $images = $element->content;
            }
            else{
                $images = NODATA;
            }
        }
        if($bottomtext !='')
        {
            $content2 = file_get_contents(DIR_PATH.'./templates/tpl/catalog-bottomtext.tpl');
            $content2 = str_replace( '{content}', $bottomtext, $content2 );
        }

        $html = file_get_contents(DIR_PATH.'./templates/tpl/catalog-content.tpl');
        $html = str_replace( '{content}', $content, $html );
        $html = str_replace( '{images}', $images, $html );
        $html = str_replace( '{content2}', $content2, $html );

        return $html;
    }
    protected function SubMenuContent(){
        global $arResult;
        $html = '';
        if(isset($arResult->DATA["Submenu"])) $Submenu = $arResult->DATA["Submenu"];//echo '<pre>'; print_r($Submenu); echo '</pre>';
        if(isset($Submenu)){
            $content = print_page($Submenu["content"]);
            if($Submenu["class"] !=''){
                $class_name = $Submenu["class"];
                $element = new $class_name('', $Submenu, $arResult->DATA["id"]);
                if(isset($Submenu["Images"])  && $Submenu["Images"] !=''){
                    $images = $element->images_view;
                }
                else{
                    $images = NODATA;
                }
                $table_price = $element->table_view;
            }
            $html = file_get_contents(DIR_PATH.'./templates/tpl/catalog-content-sub.tpl');
            $html = str_replace( '{content}', $content, $html );
            $html = str_replace( '{images}', $images, $html );
            $html = str_replace( '{table_price}', $table_price, $html );
        }
        return $html;
    }
    protected function getResultSearch(){
        $search_word = $_POST['search_word'];
        $search_result = new Search($search_word);
        $html = $search_result->Content;
        return $html;
    }
    protected function getAdminPanel(){

        $html = $this->getMainPage();
        $html.= file_get_contents(DIR_PATH.'./templates/modal.tpl');
        return $html;
    }
    private function getEdit(){
        global $arResult;
        $html = '';
        if($arResult->UsernameEnter["enter"] == "Y")
        {
            $mainpage = array('MainPage', 'about', 'oplata', 'contacts', 'prices', 'news' );
            if( $arResult->ACTION == "polochnye-stellazhi" || $arResult->ACTION == "arhivnye-stellazhi")
            {
                $html = buttonEditCatalog();
            }
            elseif(in_array($arResult->ACTION, $mainpage) && $arResult->POS1 =='')
            {
                $html = buttonEditPage();
            }
        }
        return $html;
    }


}
