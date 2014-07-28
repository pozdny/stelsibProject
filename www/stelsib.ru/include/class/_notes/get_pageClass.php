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
	private $Rights     = '';
    private $CatElem    = 'no';
	public static $Nikname   = '';
	const AUTHOR = 'Валентина Позднякова';
	const TITLE  = 'Стелсиб';
	public static $Title = 'Главная';

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
		$mysqli = M_Core_DB::getInstance();
        global $arResult;
        $action = $arResult->ACTION;
        $Pages = $arResult->DATA["TopMenu"];
        $pos1 = $arResult->POS1;
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
                        global $Catalog;
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
                                        if($pos1 == $submenu["eng"]){
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
            $this->Titlepage = $titlepage.' - '.$this->Titlepage;
            $this->Keywords    = $keywords;
            $this->Description = $description;
        }
	}
	private function getTopLink()
	{
		$this->TopLink.= '<link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css" />'."\n";
        $this->TopLink.= '<link rel="stylesheet" href="/css/style.css" type="text/css" />'."\n";
        $this->TopLink.= '<script type="text/javascript" src="/js/jquery.js"></script>'."\n";
        $this->TopLink.= '<script type="text/javascript" src="/js/bootstrap.js"></script>'."\n";
        $this->TopLink.= '<link rel="icon" href="/favicon.ico" type="image/x-icon" />'."\n";
        $this->TopLink.= '<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />';
	}
    private function getBottomLink()
    {
        self::$BottomLink.= '<script type="text/javascript" src="/js/jquery.fancybox.js"></script>'."\n";
        //$this->BottomLink.= '<script type="text/javascript" src="/js/modernizr.js"></script>'."\n";
        self::$BottomLink.= '<script type="text/javascript" src="/js/leftMenu.plugin.js"></script>'."\n";
        self::$BottomLink.= '<script type="text/javascript" src="/js/script.js"></script>'."\n";
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
                    ($value["link"] == $this->Action)? $class='class="li_active"': $class='';
                    if($value["link"] == 'arhivnye-stellazhi' && $this->CatElem == 'yes') $class='class="li_active"';
                    ($value["link"] == 'MainPage')? $link = $link : $link.= '/'.$value["link"];
                    if($value["link"] == 'arhivnye-stellazhi') $id_link = 'arhivnye_stellazhi';
                    $li.= '<li '.$class.' id="id_'.$id_link.'"><a href="'.$link.'" ><span>'.$value["title"].'</span></a></li>';
                }
            }
        }
        $action = '/result-search';
        $search = file_get_contents('./templates/searchform.tpl');
        $search = str_replace( '{action}', $action, $search );
        $html = file_get_contents('./templates/top-menu.tpl');
        $html = str_replace( '{li}',$li, $html);
        $html = str_replace( '{search-form}',$search, $html);
        $this->TopMenu = $html;
    }
	private function getHead()
	{
        global $arResult;
        $hello = privet();
        if($arResult->UsernameEnter["enter"] == 'Y')
        {
            $block_true = 'block_true';
        }
        else
        {
            $block_true = 'block_false';
        }
		$html = file_get_contents('./templates/head.tpl');
        $html = str_replace( '{block_true}', $block_true, $html );
		$html = str_replace( '{hello}',$hello, $html);
		$this->Head = $html;
	}
	private function getLeft()
	{
        global $arResult;
        global $Catalog;
        $li = '';
        $action = $arResult->ACTION;
        $pos1   = $arResult->POS1;
        $a_link = '';
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
		$html = file_get_contents('./templates/left.tpl');
        $html = str_replace( '{-sidebarInner}','-sidebarInner', $html);
        $html = str_replace( '{-admin}','', $html);
		$html = str_replace( '{li}',$li, $html);
		$this->Left = $html;
	}
	private  function getFooter()
	{
		$date = date('Y');
		$html = file_get_contents('./templates/foot.tpl');
		$html = str_replace( '{date}',$date, $html);
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
        $block_undermain = 'id="block_undermain"';
        $titleblock = file_get_contents('./templates/title.tpl');
        $titleblock = str_replace( '{title}',$title, $titleblock);
        $edit       = $this->getEdit();
        if( $arResult->ACTION == "MainPage" || $arResult->ACTION == "admin-panel")
        {
            $CONTENT_columns = "CONTENT_columns_one";
        }
        else
        {
            $CONTENT_columns = "CONTENT_big";
        }
		switch($this->Action)
		{
			case 'MainPage':
                $left = '';
                $span = "col-xs-12 col-sm-12 col-md-12 col-lg-12";
                $block_undermain = "";
                $titleblock = "";
                $content = $this->getMainPage();
			break;
			case 'about':
            case 'oplata':
            case 'contacts':
                $left = $this->Left;
                $span = "col-xs-9 col-sm-9 col-md-9 col-lg-9";
                $content = $this->StaticPage();
			break;
            case 'arhivnye-stellazhi':
            case 'polochnye-stellazhi':
                $left = $this->Left;
                $span = "col-xs-9 col-sm-9 col-md-9 col-lg-9";
                $content = $this->CatalogPage();
            break;
            case 'result-search':
                $left = $this->Left;
                $span = "col-xs-9 col-sm-9 col-md-9 col-lg-9";
                $content = $this->getResultSearch();
            break;
            case 'admin-panel':
                $left = '';
                $span = "col-xs-12 col-md-12 col-lg-12";
                $block_undermain = "";
                $titleblock = "";
                $content = $this->getAdminPanel();
            break;
			default:
            $left = '';
            $span = "col-xs-12 col-md-12 col-lg-12";
            $content = $this->getMainPage();
		}
		
		$html = file_get_contents('./templates/index.tpl');
		$html = str_replace( '{top_link}',$top_link, $html);
		$html = str_replace( '{titlepage}',$titlepage, $html);
        $html = str_replace( '{keywords}',$keywords, $html);
        $html = str_replace( '{description}',$description, $html);
		$html = str_replace( '{head}',$head, $html);
        $html = str_replace( '{top_menu}',$top_menu, $html);
        $html = str_replace( '{CONTENT_columns}', $CONTENT_columns, $html );
		$html = str_replace( '{left}', $left, $html );
        $html = str_replace( '{block_undermain}', $block_undermain, $html );
        $html = str_replace( '{edit}', $edit, $html );
        $html = str_replace( '{span}', $span, $html );
        $html = str_replace( '{breadcrumbs}', $breadcrumbs, $html );
        $html = str_replace( '{title}', $titleblock, $html );
		$html = str_replace( '{content}', $content, $html );
		$html = str_replace( '{footer}', $footer, $html );
        $html = str_replace( '{bottom_link}',$bottom_link, $html);
		$this->Content = $html;
		
	}
	private function getMainPage()
	{
        global $arResult;
        $topmenu = $arResult->DATA["TopMenu"];
        $title = '';
        $content = '';
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
        $html = file_get_contents('./templates/content-main.tpl');
        $html = str_replace( '{title}', $title, $html );
        $html = str_replace( '{link}', $link, $html );
        $html = str_replace( '{link2}', $link2, $html );
        $html = str_replace( '{content}', $content, $html );
		return $html;
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
        $Data    = $arResult->DATA;
        $content = print_page($Data["content"]);
        if($content == ''){
            $content = NODATA;
        }
		$html = file_get_contents('./templates/content.tpl');
		$html = str_replace( '{content}', $content, $html );
		return $html;
	}
    protected function MenuContent()
    {
        global $arResult;
        //подключаем классы
        require_once('catalog/include_classes.php');
        $Data = $arResult->DATA;
        $num = sizeof($Data["Complects"]);
        $numS = 1;
        $i = 2;
        $bquote = '';
        $content = '';
        $blockquote = print_page($Data["content"]);
        if($blockquote !='')
        {
            $bquote = file_get_contents('./templates/catalog-blockquote.tpl');
            $bquote = str_replace( '{content}', $blockquote, $bquote );
        }
        if(isset($Data["Complects"]) && sizeof($Data["Complects"]) !=0){
            foreach($Data["Complects"] as $key => $value){
                if($i == 2){
                    $content.='<div class="row">';
                }
                $class_name = $Data["class"];
                $complect = new $class_name($value);
                $content.= $complect->content;
                $i--;
                if(!$i || ($i == 1 && $num == $numS)){
                    $content.='</div>';
                    $i = 2;
                }
                $numS++;
            }
        }
        else{
            $content = NODATA;
        }

        $html = file_get_contents('./templates/catalog-content.tpl');
        $html = str_replace( '{blockquote}', $bquote, $html );
        $html = str_replace( '{content}', $content, $html );

        return $html;
    }
    protected function SubMenuContent(){
        global $arResult;
        require_once('catalog/include_classes.php');
        $Submenu = $arResult->DATA["Submenu"];
        if(isset($Submenu)){
            $blockquote = print_page($Submenu["content"]);
            $content = "";
            $bquote = "";
            if($blockquote !='')
            {
                $bquote = file_get_contents('./templates/catalog-blockquote.tpl');
                $bquote = str_replace( '{content}', $blockquote, $bquote );
            }
            if(isset($Submenu["Elements"])  && sizeof($Submenu["Elements"]) !=0 && $Submenu["class"] !=''){
                foreach($Submenu["Elements"] as $key => $value){
                    $content.='<div class="row">';
                    $class_name = $Submenu["class"];
                    $element = new $class_name('', $value);
                    $content.= $element->content;
                    $content.='</div>';
                }
            }
            else{
                $content = NODATA;
            }
        }

        $html = file_get_contents('./templates/catalog-content.tpl');
        $html = str_replace( '{blockquote}', $bquote, $html );
        $html = str_replace( '{content}', $content, $html );
        return $html;
    }
    protected function getResultSearch(){
        require_once('Search.php');
        $search_word = $_POST['search_word'];
        $search_result = new Search($search_word);
        $html = $search_result->Content;
        return $html;
    }
    protected function getAdminPanel(){

        $html = $this->getMainPage();
        $html.= file_get_contents('./templates/modal.tpl');
        return $html;
    }
    private function getEdit(){
        global $arResult;
        $html = '';
        if($arResult->UsernameEnter["enter"] == "Y")
        {
            $mainpage = array('MainPage', 'about', 'oplata', 'contacts' );
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
