<?php
if (!isset($_SESSION)) {
  session_start();
}
function edit_content($r)
{
    global $arResult;
    global $Pages;
	access();
	access_rights($r);
    $tab = NAVIGATOR;
	$GoTo = ADMIN_PANEL.'/pages';
    $pos1 = '';
	if($arResult->POS1 !='')
	{
		$pos1 = $arResult->POS1;
	}
	else
	{
		header("Location: ".$GoTo);
	}

    foreach($Pages as $key => $arr){
        if($arr["link"] == $pos1){
            $row = $arr;
        }
    }


    $content = $row['content'];
	$name_content = 'content';
	$helpbox = 'helpbox';
	$i = '1';
	$addbbcode20 = '<select name="addbbcode20" onChange="bbfontstyle(\'[color=\' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + \']\', \'[/color]\', \''.$i.'\'); this.selectedIndex=0;" onMouseOver="helpline(\'s\', \''.$i.'\')" onMouseOut="helpline(\'h\', \''.$i.'\')">';

	$table_redact = file_get_contents( DIR_PATH.'admin_panel/templates/table_redact.tpl');
	$table_redact = str_replace( '{content}', $content, $table_redact );
	$table_redact = str_replace( '{name_content}', $name_content, $table_redact );
	$table_redact = str_replace( '{helpbox}', $helpbox, $table_redact );
	$table_redact = str_replace( '{i}', $i, $table_redact );
	$table_redact = str_replace( '{addbbcode20}', $addbbcode20, $table_redact );
	////.......................................................
	$zagolovok = htmlspecialchars ($row['zagolovok']);
	$title = '<a href="'.DOMEN_LOC.'/'.$row['link'].'">'.$row['title'].'</a>';
	$back = '<a href="'.ADMIN_PANEL.'/pages" '.STYLE12.'>'.BACK_IMG.' назад</a>';
	$html = file_get_contents( DIR_PATH.'admin_panel/templates/edit_content.tpl');
	$html = str_replace( '{back}', $back, $html );
	$html = str_replace( '{title}', $title, $html );
	$html = str_replace( '{zagolovok}', $zagolovok, $html );
	$html = str_replace( '{id}', $row['id'], $html );
	$html = str_replace( '{table_redact}', $table_redact, $html );
    $html = str_replace( '{table}', $tab, $html );
    $html = str_replace( '{link}', $_SERVER['REQUEST_URI'], $html);

 return $html;
}
//.................titlepage, keywords, description главных страниц........................
function edit_metatags($r)
{
	access();
	access_rights($r);
    global $arResult;
    global $Pages;
    $tab = NAVIGATOR;
    $row_c = array();
	$GoTo = ADMIN_PANEL.'/pages';
	if($arResult->POS1 !='')
	{			   
		$pos1 = $arResult->POS1;
	}
	else
	{
        header("Location: ".$GoTo);
	}

    foreach($Pages as $key => $arr){
        if($arr["link"] == $pos1){
            $row_c = $arr;
        }
    }
	$titlepage = htmlspecialchars ($row_c['titlepage']);
	$keywords = $row_c['keywords'];
	$description = $row_c['description'];
    $title = '<a href="'.DOMEN_LOC.'/'.$row_c['link'].'">'.$row_c['title'].'</a>';
	$back = '<a href="'.ADMIN_PANEL.'/pages" '.STYLE12.'>'.BACK_IMG.' назад</a>';
	$html = file_get_contents( DIR_PATH.'admin_panel/templates/edit_metatags.tpl');
	$html = str_replace( '{back}', $back, $html );
	$html = str_replace( '{title}', $title, $html );
    $html = str_replace( '{titlepage}', $titlepage, $html );
	$html = str_replace( '{keywords}', $keywords, $html );
	$html = str_replace( '{description}', $description, $html );
	$html = str_replace( '{id}', $row_c['id'], $html );
    $html = str_replace( '{table}', $tab, $html);
    $html = str_replace( '{link}', $_SERVER['REQUEST_URI'], $html);

 return $html;
}

//.................titlepage, keywords, description страниц категорий товара........................
function edit_metatags_other($r)
{
	access();
	access_rights($r);
    global $arResult;
    global $Catalog;
    $menu_id = $arResult->DATA['id'];
	$GoTo = ADMIN_PANEL;
    if($arResult->POS1 !='')
    {
        $pos1 = $arResult->POS1;
    }
    else
    {
        header("Location: ".$GoTo);
    }
    if($arResult->POS2 !='')
    {
        $tab = 'catalog_'.$arResult->POS2.REG_ENG;
    }
	else
	{
		header("Location: ".$GoTo); 
	}
    if($arResult->POS3 !='')
    {
        $pos3 = $arResult->POS3;
    }
    foreach($Catalog as $key => $value){
        if($tab == CATALOG_MENU){
            if($value["eng"] == $pos1){
                $id          = $value["id"];
                $title       = '<a href="'.DOMEN_LOC.'/'.$value["eng"].'" >'.$value["title"].'</a>';
                $titlepage   = $value["titlepage"];
                $keywords    = $value["keywords"];
                $description = $value["description"];
            }
        }
        else{
            $Submenu = $value["Submenu"];
            foreach($Submenu as $key1 => $sub){
                if($sub["eng"] == $pos3 && $sub["menu_id"] == $menu_id){
                    $id          = $sub["id"];
                    $title       = '<a href="'.DOMEN_LOC.'/'.$pos1.'/'.$sub["eng"].'" >'.$sub["title"].'</a>';
                    $titlepage   = $sub["titlepage"];
                    $keywords    = $sub["keywords"];
                    $description = $sub["description"];
                }
            }
        }
    }
    $back = '<a href="'.ADMIN_PANEL.'/products" '.STYLE12.'>'.BACK_IMG.' назад</a>';
	$html = file_get_contents( DIR_PATH.'admin_panel/templates/edit_metatags.tpl');
	$html = str_replace( '{title}', $title, $html );
    $html = str_replace( '{titlepage}', $titlepage, $html );
	$html = str_replace( '{keywords}', $keywords, $html );
	$html = str_replace( '{description}', $description, $html );
	$html = str_replace( '{id}', $id, $html );
	$html = str_replace( '{back}', $back, $html );
    $html = str_replace( '{table}', $tab, $html);
    $html = str_replace( '{link}', $_SERVER['REQUEST_URI'], $html);
	
 return $html;
}


// вид добавления позиций внизу таблицы
function view_add_menu()
{
    global $arResult;
	$action = $arResult->ACTION;
    if($action == 'products')
    {
        $position = 'категорию';
        $title    = 'название категории';
        $get_action   = ADMIN_PANEL.'/add_menu';
        $table    = CATALOG_MENU;
    }
    elseif($action == TABLE_ADMIN_USERS)
    {
        $position = 'оператора';
        $title = 'Ф.И.О. оператора';
        $get_action = ADMIN_PANEL.'/add_position';
        $table    = TABLE_ADMIN_USERS;
    }

    $html = file_get_contents( DIR_PATH.'admin_panel/templates/form_add_menu.tpl');
    $html = str_replace( '{action}', $get_action, $html );
    $html = str_replace( '{position}', $position, $html );
    $html = str_replace( '{title}', $title, $html );
    $html = str_replace( '{table}', $table, $html );
    return $html;
}

// добавлнеие позиций в таблицы
function add_position($r)
{
    $mysqli = M_Core_DB::getInstance();
    access();
    access_rights($r);
    if(isset($_POST["table"]))
        $table = $_POST["table"];
    if(isset($_POST["title"]))
        $title = GetFormValue($_POST["title"]);

    if($table == TABLE_ADMIN_USERS)// добавлнеие  оператора
    {
        $mes = 'оператора';
        $punkt = '`name`';
        $error = '';
        $punkt2 = ', `login`, `password`, `rights`';
        $login = md5($title.SALT_LOG);
        $password = md5($title.SALT_PAS);

        $query  = "SELECT * FROM ".$table."
			   		WHERE ".$punkt." REGEXP '^".$title."$'";
        $mysqli->_execute($query);
        $row_e  = $mysqli->fetch();
        if ( preg_match( "/[&$^%#*@!+=(){}:;\/]+$/ui",  $title) )
            $error = $error.'<li>название содержит недопустимые символы (пробел, %, $ и т.д.)</li>'."\n";
        if ( empty($title))
            $error = $error.'<li>название '.$mes.' не заполнено</li>'."\n";
        if($row_e >0)
        {
            $error = $error.'<li>оператор с именем '.$title.' уже существует!!!</li><'."\n";
        }
        if(empty($error))
        {
            if($table == TABLE_ADMIN_USERS)
            {
                $query = sprintf("INSERT INTO ".$table." (".$punkt.$punkt2.") VALUES (%s, %s, %s, %s)",
                    GetSQLValueString($title, "text"),
                    GetSQLValueString($login, "text"),
                    GetSQLValueString($password, "text"),
                    GetSQLValueString('m', "text"));
            }

            $mysqli->query($query);
            $messages = new Messages('info', 'Название '.$mes.' добавлено', $table );
            echo $messages->Content;
        }
        else
        {
            $messages = new Messages('error', '<ul>'.$error.'</ul>', $table );
            echo $messages->Content;
        }

    }



}
function edit_menu(){
    $mysqli = M_Core_DB::getInstance();
    global $arResult;
    global $Catalog;
    $Complects = $arResult->DATA["Complects"];
    $GoTo = ADMIN_PANEL;
    $tab = CATALOG_MENU;
    $input_tab = CATALOG_SUBMENU;
    $complect_tab = COMPLECT;
    if(isset($arResult->UsernameEnter["group"])){
        $m_r = $arResult->UsernameEnter["group"];
    }
    if(isset($arResult->POS1) && $arResult->POS1 !='')
    {
        $eng = $arResult->POS1;
    }
    else
    {
        header("Location: ".$GoTo);
        exit;
    }
    //делаем закрытые для модераторов поля заблокированными
    if($m_r == 'm')
    {
        $disabled = "disabled='disabled'";
    }
    foreach($Catalog as $key => $value){
        if($value["eng"] == $eng){
            $rus =  htmlspecialchars($value['title'], ENT_QUOTES);
            $menu_id = $value["id"];
            $zagolovok = $value["zagolovok"];
            $content = $value["content"];
            $content2 = $value["content2"];
            $name_pos = '<a href="'.DOMEN_LOC.'/'.$eng.'" >'.$rus.'</a>';
        }
    }


    //ссылка метатегов
    $query = "SELECT title, link FROM ".ADMIN_ACTIONS." ORDER BY id LIMIT 1,1";
    $mysqli->_execute($query);
    $row = $mysqli->fetch();
    $link_met = $row['link'];
    $txt = 'категории';

    /*
        ==================
        [BLOCK 1]
        ==================
    */
    $legend1  = 'Список подкатегорий в данной категории ('.$name_pos.')';
    $sub_td = '';
    $header_sub = '<th>id</th><th>Название</th><th>F</th><th>M</th>';
    //список подкатегорий в категории
    foreach($Catalog as $key => $menu){
        if($menu["id"] == $menu_id){
            if(sizeof($menu["Submenu"]) > 0 && $menu["Submenu"] !=''){
                foreach($menu["Submenu"] as $key2 => $value){
                    $num_id = $value["id"];
                    $title_sub = '<a class="text-info" href="'.DOMEN_LOC.'/'.$eng.'/'.$value['eng'].'">'.$value["title"].'</a>';
                    $edit_full = '<a href="'.ADMIN_PANEL.'/edit_catalog/'.$eng.'/submenu/'.$value['eng'].'">'.EDIT_IMG.'</a>';
                    $edit_key  = '<a href="'.ADMIN_PANEL.'/'.$link_met.'_other/'.$eng.'/submenu/'.$value["eng"].'" ><span class="img_edit" >'.EDIT_IMG_K.'</span></a>';
                    $sub_td.= file_get_contents( DIR_PATH.'admin_panel/templates/input_submenuTD.tpl');
                    $sub_td = str_replace( '{del_td}', '', $sub_td );
                    $sub_td = str_replace( '{pop_td}', '', $sub_td );
                    $sub_td = str_replace( '{promo_td}', '', $sub_td );
                    $sub_td = str_replace( '{num_id}', $num_id, $sub_td );
                    $sub_td = str_replace( '{title}', $title_sub, $sub_td );
                    $sub_td = str_replace( '{edit_full}', $edit_full, $sub_td );
                    $sub_td = str_replace( '{edit_td}', '<td width="5%">'.$edit_key.'</td>', $sub_td );
                }
                $submenu_items = file_get_contents( DIR_PATH.'admin_panel/templates/input_submenuT.tpl');
                $submenu_items = str_replace( '{id}', '', $submenu_items );
                $submenu_items = str_replace( '{header}', $header_sub, $submenu_items );
                $submenu_items = str_replace( '{td}', $sub_td, $submenu_items );
                $submenu_items = str_replace( '{del_button}', '', $submenu_items );
            }
            else{
                $submenu_items = NODATA;
            }
        }
    }

    $block1 = file_get_contents( DIR_PATH.'admin_panel/templates/block1.tpl');
    $block1 = str_replace( '{formElements}', '', $block1);
    $block1 = str_replace( '{table}', '', $block1);
    $block1 = str_replace( '{method}', '', $block1);
    $block1 = str_replace( '{action}', '', $block1);
    $block1 = str_replace( '{legend}', $legend1, $block1);
    $block1 = str_replace( '{submenu_items}', $submenu_items, $block1);
    $block1 = str_replace( '{input_button}', '', $block1);
    $block1 = str_replace( '{hidden}', '', $block1 );
    $block1 = str_replace( '{add_elements}', '', $block1 );
    /*
        ==================
        [COMPLECTS]
        ==================
    */
    $legend2  = 'Список комплектов в данной категории ('.$name_pos.')';
    $com_td = '';
    $header_com = '<th>del</th><th>pop</th><th>promo</th></th><th>id</th><th>Название</th><th>F</th>';
    //список комплектов в категории
    if(sizeof($Complects) > 0 && $Complects !=''){
        $complect_obj = new Complects();
        $countPop = $complect_obj->getComplectPop();
        $countPromo = $complect_obj->getComplectPromo();
        foreach($Complects as $key => $value){
            ($value["pop"] == 1)? $pop_tf = 'checked="checked"': $pop_tf = '';
            ($value["promo"] == 1)? $promo_tf = 'checked="checked"': $promo_tf = '';
            ($countPop == 4 && $pop_tf != 'checked="checked"')? $pop_d = 'disabled="disabled"': $pop_d = '';
            ($countPromo == 1 && $promo_tf != 'checked="checked"')? $promo_d = 'disabled="disabled"': $promo_d = '';
            $num_id = $value["id"];
            $del_check = '<input type="checkbox" name="del[]"  value="'.$value['id'].'"/>';
            $pop_check = '<input type="checkbox" name="pop[]" id="pop[]" value="'.$value['id'].'" '.$pop_tf.' '.$pop_d.'/>';
            $promo_check = '<input type="checkbox" name="promo[]" id="promo[]" value="'.$value['id'].'" '.$promo_tf.' '.$promo_d.'/>';
            $title_sub = '<a class="text-info" href="'.DOMEN_LOC.'/'.$eng.'">'.$value["title"].'</a>';
            $edit_full = '<a href="'.ADMIN_PANEL.'/edit_catalog/'.$eng.'/complect/'.$value['id'].'">'.EDIT_IMG.'</a>';
            $com_td.= file_get_contents( DIR_PATH.'admin_panel/templates/input_submenuTD.tpl');
            $com_td = str_replace( '{del_td}', '<td>'.$del_check.'</td>', $com_td );
            $com_td = str_replace( '{pop_td}', '<td>'.$pop_check.'</td>', $com_td );
            $com_td = str_replace( '{promo_td}', '<td>'.$promo_check.'</td>', $com_td );
            $com_td = str_replace( '{num_id}', $num_id, $com_td );
            $com_td = str_replace( '{title}', $title_sub, $com_td );
            $com_td = str_replace( '{edit_full}', $edit_full, $com_td );
            $com_td = str_replace( '{edit_td}', '', $com_td );
        }
        $del_button = '<button type="button" class="btn btn-danger" id="button_del">Удалить</button>';
        $complects_items = file_get_contents( DIR_PATH.'admin_panel/templates/input_submenuT.tpl');
        $complects_items = str_replace( '{id}', 'id="ComplectTable"', $complects_items );
        $complects_items = str_replace( '{header}', $header_com, $complects_items );
        $complects_items = str_replace( '{td}', $com_td, $complects_items );
        $complects_items = str_replace( '{del_button}', $del_button, $complects_items );
    }
    else{
        $complects_items = NODATA;
    }

    $legend3  = 'Добавить комплект';
    $add_input = file_get_contents( DIR_PATH.'admin_panel/templates/add_input.tpl');
    $add_input = str_replace( '{legend}', $legend3, $add_input );
    $add_input = str_replace( '{add_id}', $menu_id, $add_input );

    $add_elements = file_get_contents( DIR_PATH.'admin_panel/templates/add_elements.tpl');
    $add_elements = str_replace( '{table}', COMPLECT, $add_elements );
    $add_elements = str_replace( '{add_input}', $add_input,  $add_elements );
    $add_elements = str_replace( '{method}', '',  $add_elements );
    $add_elements = str_replace( '{action}', '',  $add_elements );
    $add_elements = str_replace( '{link}', $_SERVER['REQUEST_URI'], $add_elements);

    $complects = file_get_contents( DIR_PATH.'admin_panel/templates/complects.tpl');
    $complects = str_replace( '{table}', COMPLECT, $complects );
    $complects = str_replace( '{legend}', $legend2, $complects );
    $complects = str_replace( '{complects_items}', $complects_items, $complects );
    $complects = str_replace( '{add_elements}', $add_elements, $complects );
    $complects = str_replace( '{link}', $_SERVER['REQUEST_URI'], $complects);
    /*
            ==================
            [BLOCK 2]
            ==================
     */
    $legend4 = "Данные категории (id=".$menu_id.')';
    $h1_text = file_get_contents( DIR_PATH.'admin_panel/templates/h1_text.tpl');
    $h1_text = str_replace( '{zagolovok}', $zagolovok, $h1_text );
    /*....................................table_redact............................................*/
    $name_content = 'content';
    $helpbox = 'helpbox';
    $i = '1';
    $addbbcode20 = '<select name="addbbcode20" onChange="bbfontstyle(\'[color=\' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + \']\', \'[/color]\', \''.$i.'\'); this.selectedIndex=0;" onMouseOver="helpline(\'s\', \''.$i.'\')" onMouseOut="helpline(\'h\', \''.$i.'\')">';
    $table_redact = file_get_contents( DIR_PATH.'admin_panel/templates/table_redact.tpl');
    $table_redact = str_replace( '{content}', $content, $table_redact );
    $table_redact = str_replace( '{name_content}', $name_content, $table_redact );
    $table_redact = str_replace( '{helpbox}', $helpbox, $table_redact );
    $table_redact = str_replace( '{i}', $i, $table_redact );
    $table_redact = str_replace( '{addbbcode20}', $addbbcode20, $table_redact );
    /*....................................table_redact2............................................*/
    $name_content = 'content2';
    $helpbox = 'helpbox2';
    $i = '2';
    $addbbcode20 = '<select name="addbbcode20" onChange="bbfontstyle(\'[color=\' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + \']\', \'[/color]\', \''.$i.'\'); this.selectedIndex=0;" onMouseOver="helpline(\'s\', \''.$i.'\')" onMouseOut="helpline(\'h\', \''.$i.'\')">';
    $table_redact2 = file_get_contents( DIR_PATH.'admin_panel/templates/table_redact.tpl');
    $table_redact2 = str_replace( '{content}', $content2, $table_redact2 );
    $table_redact2 = str_replace( '{name_content}', $name_content, $table_redact2 );
    $table_redact2 = str_replace( '{helpbox}', $helpbox, $table_redact2 );
    $table_redact2 = str_replace( '{i}', $i, $table_redact2 );
    $table_redact2 = str_replace( '{addbbcode20}', $addbbcode20, $table_redact2 );

    $block2   = file_get_contents( DIR_PATH.'admin_panel/templates/block2.tpl');
    $block2 = str_replace( '{table}', $tab, $block2 );
    $block2 = str_replace( '{legend}', $legend4, $block2 );
    $block2 = str_replace( '{rus}', $rus, $block2 );
    $block2 = str_replace( '{eng}', $eng, $block2 );
    $block2 = str_replace( '{h1_text}', $h1_text, $block2 );
    $block2 = str_replace( '{table_redact}', $table_redact, $block2 );
    $block2 = str_replace( '{table_redact2}', $table_redact2, $block2 );
    $block2 = str_replace( '{disabled}', 'disabled', $block2 );
    $block2 = str_replace( '{edit_id}', $menu_id, $block2 );
    $block2 = str_replace( '{link}', $_SERVER['REQUEST_URI'], $block2);
    //ссылка назад
    $back_link = ADMIN_PANEL.'/products';
    $back = '<a href="'.$back_link.'" '.STYLE12.'>'.BACK_IMG.' назад</a>';
    /*................................................................................................*/
    $html   = file_get_contents( DIR_PATH.'admin_panel/templates/edit_menu.tpl');
    $html = str_replace( '{back}', $back, $html );
    $html = str_replace( '{txt}', $txt, $html );
    $html = str_replace( '{block1}', $block1, $html );
    $html = str_replace( '{complects}', $complects, $html );
    $html = str_replace( '{block2}', $block2, $html );

    return $html;
}
function edit_complect(){
    global $arResult;
    $tab = COMPLECT;
    $Complects = $arResult->DATA["Complects"];
    $menu_eng = $arResult->DATA["eng"];
    if(isset($arResult->POS1) && $arResult->POS1 !=''){
        $cat = $arResult->POS1;
    }
    if(isset($arResult->POS3) && $arResult->POS3 !='')
    {
        $id = $arResult->POS3;
    }
    foreach($Complects as $key => $value){
        if($value["id"] == $id){
            $rus =  htmlspecialchars($value['title'], ENT_QUOTES);
            $content = $value["content"];
            $name_pos = '<a href="'.DOMEN_LOC.'/'.$cat.'" >'.$rus.'</a>';
            $price = sprintf("%.2f", round($value["price"], 2));
            $img = $value["img"];
            $alt = $value["alt"];
            $img_title = $value["img_title"];
        }
    }
    //ссылка назад
    $back_link = ADMIN_PANEL.'/edit_catalog/'.$cat.'/menu';
    $back = '<a href="'.$back_link.'" '.STYLE12.'>'.BACK_IMG.' назад</a>';
    //foto
    $legend = "Изображение";
    $img_now = ' (текущее '.$img.')';

    //проверяем изображение на наличие
    $path_img = 'complects/small/';
    $img = FindImg($img, $path_img, $tab, $id);
    $img = '<img src="'.HOME_PATH.PATH_IMG.$path_img.$img.'" width="98px"/>';
    $name_del = 'delete';
    $name_img = 'addimg['.$id.']';
    $name_alt = 'addimg[alt_'.$id.']';
    $name_img_title = 'addimg[title_'.$id.']';

    $main_img = file_get_contents( DIR_PATH.'admin_panel/templates/edit_fotoIcon.tpl');
    $main_img = str_replace( '{legend}', $legend, $main_img );
    $main_img = str_replace( '{img_now}', $img_now, $main_img );
    $main_img = str_replace( '{name_img}', $name_img, $main_img );
    $main_img = str_replace( '{name_alt}', $name_alt, $main_img );
    $main_img = str_replace( '{name_img_title}', $name_img_title, $main_img );
    $main_img = str_replace( '{alt}', $alt, $main_img );
    $main_img = str_replace( '{img_title}', $img_title, $main_img );
    $main_img = str_replace( '{img}', $img, $main_img );
    $main_img = str_replace( '{name_del}', $name_del, $main_img );
    /*....................................table_redact............................................*/
    $name_content = 'content';
    $helpbox = 'helpbox';
    $i = '1';
    $addbbcode20 = '<select name="addbbcode20" onChange="bbfontstyle(\'[color=\' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + \']\', \'[/color]\', \''.$i.'\'); this.selectedIndex=0;" onMouseOver="helpline(\'s\', \''.$i.'\')" onMouseOut="helpline(\'h\', \''.$i.'\')">';
    $table_redact = file_get_contents( DIR_PATH.'admin_panel/templates/table_redact.tpl');
    $table_redact = str_replace( '{content}', $content, $table_redact );
    $table_redact = str_replace( '{name_content}', $name_content, $table_redact );
    $table_redact = str_replace( '{helpbox}', $helpbox, $table_redact );
    $table_redact = str_replace( '{i}', $i, $table_redact );
    $table_redact = str_replace( '{addbbcode20}', $addbbcode20, $table_redact );
    /*................................................................................................*/
    $action = ADMIN_PANEL.'/get_edit_complect';
    $html   = file_get_contents( DIR_PATH.'admin_panel/templates/edit_element.tpl');
    $html = str_replace( '{action}', $action, $html );
    $html = str_replace( '{back}', $back, $html );
    $html = str_replace( '{title}', $name_pos, $html );
    $html = str_replace( '{zagolovok}', $rus, $html );
    $html = str_replace( '{table_redact}', $table_redact, $html );
    $html = str_replace( '{content}', $content, $html );
    $html = str_replace( '{price}', $price, $html );
    $html = str_replace( '{main_img}', $main_img, $html );
    $html = str_replace( '{table}', $tab, $html );
    $html = str_replace( '{menu_eng}', $menu_eng, $html );
    $html = str_replace( '{id}', $id, $html );
    return $html;
}

function get_edit_complect(){
    $mysqli = M_Core_DB::getInstance();
    $GoTo = ADMIN_PANEL;//echo '<pre>'; print_r($_POST); echo '</pre>';
    $_SESSION['prevPage'] = $_SERVER['HTTP_REFERER'];
    $GoToSeccess = $_SESSION['prevPage'];
    if ((isset($_POST["MM_edit"])) && ($_POST["MM_edit"] == "MM_edit")){
        if(isset($_POST['zagolovok'])){
            $title = GetFormValue($_POST['zagolovok']);
        }
        if(isset($_POST['content'])){
            $content = GetFormValue($_POST['content']);
        }
        if(isset($_POST['price'])){
            $price = round($_POST['price'], 2);
        }
        if(isset($_FILES['addimg'])){
            $file = $_FILES['addimg'];
        }
        if(isset($_POST['addimg'])){
            $keys = $_POST['addimg'];
        }
        if(isset($_POST['delete'])){
            $delete = $_POST['delete'];
        }
        if(isset($_POST['edit_id'])){
            $id = $_POST['edit_id'];
        }
        if(isset($_POST['menu_eng'])){
            $menu_eng = $_POST['menu_eng'];
        }
        $error = '';
        $dest = COMPLECT."/";
        $destS = COMPLECT."/small/";
        $tab = COMPLECT;

        if ( empty( $title ) )
            $error = $error.'<li>не заполнено название позиции по-русски</li>'."\n";
        //if ( preg_match( "/[&$^%#*@!+=(){}:;\/]+$/ui",  $name) )
            //$error = $error.'<li>название содержит недопустимые символы (пробел, %, $ и т.д.)</li>'."\n";

        //удаление отмеченных фото иконок для работ
        if(isset($_POST['delete']) && $_POST['delete'] == 'yes')
        {
            $query = "SELECT img FROM ".$tab." WHERE id =".$id;
            $mysqli->_execute($query);
            $row = $mysqli->fetch();
            $file_name = $row['img'];
            $file = './'.PATH_IMGD.COMPLECT.'/'.$row['img'];
            $fileS = './'.PATH_IMG.COMPLECT.'/small/'.$row['img'];
            $hasAFile = FindFileStuct($file_name, PATH_IMG.$dest);
            if($hasAFile && $file_name !="empty.jpg")
            {
                unlink($file);
            }
            $hasAFile = FindFileStuct($file_name, PATH_IMG.$destS);
            if($hasAFile && $file_name !="empty.jpg")
            {
                unlink($fileS);
            }
            $img = '';
            $query = sprintf("UPDATE ".$tab." SET img=%s WHERE id=%s",
                GetSQLValueString($img, "text"),
                GetSQLValueString($id, "int"));
            $mysqli->query($query);
        }
        else{
            if(isset($_FILES['addimg'])){
                //добавление изображения
                $edit_error = editIMG($file, $id, $keys, $tab, $menu_eng);
                $str = substr($edit_error, 0, 1);
                if(!$str)
                {
                    $str = substr($edit_error, 1);
                    $error.=$str;
                }
            }
        }
        if(!empty($error)){
            $messages = new Messages('error', '<ul>'.$error.'</ul>' );
            echo $messages->Content;

        }
        else{
            $query = sprintf("UPDATE ".$tab." SET title=%s, content=%s, price=%s WHERE id=%s",
                GetSQLValueString($title, "text"),
                GetSQLValueString($content, "text"),
                GetSQLValueString($price, "float"),
                GetSQLValueString($id, "int"));
            $mysqli->query($query);
            header(sprintf("Location: %s", $GoToSeccess));
       }
    }
    else{
       header("Location: ".$GoTo);
       exit;
    }
}
function edit_submenu(){
    global $arResult;
    if(isset($arResult->DATA["Submenu"])) $Submenu = $arResult->DATA["Submenu"]; //echo '<pre>'; print_r($arResult); echo '</pre>';
    if(isset($Submenu["Images"])) $Images = $Submenu["Images"];
    $tab = CATALOG_SUBMENU;
    $input_tab = CATALOG_SUBMENU;
    if(isset($arResult->POS1) && $arResult->POS1 !=''){
        $cat = $arResult->POS1;
    }
    if(isset($arResult->POS3) && $arResult->POS3 !='')
    {
        $eng = $arResult->POS3;
    }
    if(isset($Submenu)){
        if($Submenu["eng"] == $eng){
            $menu_id = $Submenu["id"];
            $rus =  htmlspecialchars($Submenu["title"], ENT_QUOTES);
            $zagolovok = $Submenu["zagolovok"];
            $content = $Submenu["content"];
            $name_pos = '<a href="'.DOMEN_LOC.'/'.$cat.'/'.$eng.'" >'.$rus.'</a>';

        }
        /*
        ==================
        [BLOCK 1]
        ==================
    */
        $legend  = 'Список изображений в данной подкатегории ('.$name_pos.')';
        $legend1  = 'Добавить изображение в подкатегорию';
        $sub_td = '';

        //список изображений в подкатегории
        if($Images !=''){
            $tab = CATALOG_SUBMENU;
            $path_img_small = CATALOG_ALL.'/small/';
            $header_sub = '<th>del</th><th>id</th><th>Название</th>';
            foreach($Images as $key => $value){
                $del_check = '<input type="checkbox" name="del[]"id="del[]" value="'.$value['id'].'"/>';
                $num_id = $value["id"];
                $img_small = FindImg($value["img"], $path_img_small, $tab, $value["id"]);
                $img_now   = $value["img"];
                $img       = '<img src="'.HOME_PATH.PATH_IMG.$path_img_small.$img_small.'" width="30px">';
                $img_id    = 'id_icon_'.$value["id"];
                $img_name  = 'editfile['.$value["id"].']';
                $alt       = $value["alt"];
                $img_title = $value["img_title"];
                $alt_name  = 'editfile[alt_'.$value["id"].']';
                $img_title_name = 'editfile[title_'.$value["id"].']';
                $img_data = file_get_contents( DIR_PATH.'admin_panel/templates/edit_images.tpl');
                $img_data = str_replace( '{img_now}', $img_now, $img_data );
                $img_data = str_replace( '{img}', $img, $img_data );
                $img_data = str_replace( '{id}', $img_id, $img_data );
                $img_data = str_replace( '{img_name}', $img_name, $img_data );
                $img_data = str_replace( '{alt}', $alt, $img_data );
                $img_data = str_replace( '{img_title}', $img_title, $img_data );
                $img_data = str_replace( '{alt_name}', $alt_name, $img_data );
                $img_data = str_replace( '{img_title_name}', $img_title_name, $img_data );

                $sub_td.= file_get_contents( DIR_PATH.'admin_panel/templates/input_imagesTD.tpl');
                $sub_td = str_replace( '{del_td}', '<td>'.$del_check.'</td>', $sub_td );
                $sub_td = str_replace( '{num_id}', $num_id, $sub_td );
                $sub_td = str_replace( '{img_data}', $img_data, $sub_td );
            }
            $edit_button = '<button type="submit" class="btn btn-primary">Изменить</button>';
            $submenu_items = file_get_contents( DIR_PATH.'admin_panel/templates/input_submenuT.tpl');
            $submenu_items = str_replace( '{id}', '', $submenu_items );
            $submenu_items = str_replace( '{header}', $header_sub, $submenu_items );
            $submenu_items = str_replace( '{td}', $sub_td, $submenu_items );
            $submenu_items = str_replace( '{del_button}', $edit_button, $submenu_items );
        }
        else{
            $submenu_items = NODATA;
        }

        $add_input =  file_get_contents( DIR_PATH.'admin_panel/templates/add_input_img.tpl');
        $add_input = str_replace( '{legend}', $legend1, $add_input);
        $add_input = str_replace( '{add_id}', $menu_id, $add_input);
        $add_input = str_replace( '{id}', $menu_id, $add_input);
        $add_input = str_replace( '{tab}', TABLE_IMAGES, $add_input);

        $action = 'action="'.ADMIN_PANEL.'/get_edit_images"';
        $method = 'method="POST"';

        $add_elements = file_get_contents( DIR_PATH.'admin_panel/templates/add_elements.tpl');
        $add_elements = str_replace( '{table}', $input_tab, $add_elements);
        $add_elements = str_replace( '{add_input}', $add_input, $add_elements);
        $add_elements = str_replace( '{action}', $action, $add_elements);
        $add_elements = str_replace( '{method}', $method, $add_elements);

        $hidden = '<input type="hidden" value="MM_edit" name="MM_edit">'."\n";
        $hidden.= '<input type="hidden" value="'.$menu_id.'" name="id">';
        $block1 = file_get_contents( DIR_PATH.'admin_panel/templates/block1.tpl');
        $block1 = str_replace( '{formElements}', 'formElements', $block1);
        $block1 = str_replace( '{table}', $input_tab, $block1);
        $block1 = str_replace( '{method}', $method, $block1);
        $block1 = str_replace( '{action}', $action, $block1);
        $block1 = str_replace( '{legend}', $legend, $block1);
        $block1 = str_replace( '{submenu_items}', $submenu_items, $block1);
        $block1 = str_replace( '{input_button}', '', $block1);
        $block1 = str_replace( '{hidden}', $hidden, $block1 );
        $block1 = str_replace( '{add_elements}', $add_elements, $block1 );

        /*
                ==================
                [BLOCK 2]
                ==================
         */
        $legend4 = "Данные подкатегории (id=".$menu_id.')';
        $h1_text = file_get_contents( DIR_PATH.'admin_panel/templates/h1_text.tpl');
        $h1_text = str_replace( '{zagolovok}', $zagolovok, $h1_text );
        /*....................................table_redact............................................*/
        $name_content = 'content';
        $helpbox = 'helpbox';
        $i = '1';
        $addbbcode20 = '<select name="addbbcode20" onChange="bbfontstyle(\'[color=\' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + \']\', \'[/color]\', \''.$i.'\'); this.selectedIndex=0;" onMouseOver="helpline(\'s\', \''.$i.'\')" onMouseOut="helpline(\'h\', \''.$i.'\')">';
        $table_redact = file_get_contents( DIR_PATH.'admin_panel/templates/table_redact.tpl');
        $table_redact = str_replace( '{content}', $content, $table_redact );
        $table_redact = str_replace( '{name_content}', $name_content, $table_redact );
        $table_redact = str_replace( '{helpbox}', $helpbox, $table_redact );
        $table_redact = str_replace( '{i}', $i, $table_redact );
        $table_redact = str_replace( '{addbbcode20}', $addbbcode20, $table_redact );

        $block2   = file_get_contents( DIR_PATH.'admin_panel/templates/block2.tpl');
        $block2 = str_replace( '{table}', $tab, $block2 );
        $block2 = str_replace( '{legend}', $legend4, $block2 );
        $block2 = str_replace( '{rus}', $rus, $block2 );
        $block2 = str_replace( '{eng}', $eng, $block2 );
        $block2 = str_replace( '{h1_text}', $h1_text, $block2 );
        $block2 = str_replace( '{table_redact}', $table_redact, $block2 );
        $block2 = str_replace( '{table_redact2}', 'нет', $block2 );
        $block2 = str_replace( '{disabled}', '', $block2 );
        $block2 = str_replace( '{edit_id}', $menu_id, $block2 );
        $block2 = str_replace( '{link}', $_SERVER['REQUEST_URI'], $block2);
        //ссылка назад
        $back_link = ADMIN_PANEL.'/edit_catalog/'.$cat.'/menu';
        $back = '<a href="'.$back_link.'" '.STYLE12.'>'.BACK_IMG.' назад</a>';
        $txt = 'подкатегории';
        /*................................................................................................*/
        $html   = file_get_contents( DIR_PATH.'admin_panel/templates/edit_menu.tpl');
        $html = str_replace( '{back}', $back, $html );
        $html = str_replace( '{txt}', $txt, $html );
        $html = str_replace( '{block1}', $block1, $html );
        $html = str_replace( '{complects}', '', $html );
        $html = str_replace( '{block2}', $block2, $html );
    }
    else{
        $html = WRONGDATA;
    }
    return $html;
}
function get_edit_images(){
    $mysqli = M_Core_DB::getInstance();
    $GoTo = ADMIN_PANEL;
    $error = '';
    $input_tab_img = TABLE_IMAGES;
    $_SESSION['prevPage'] = $_SERVER['HTTP_REFERER'];
    $GoToSeccess = $_SESSION['prevPage'];
    if ((isset($_POST["MM_edit"])) && ($_POST["MM_edit"] == "MM_edit")){//echo '<pre>';print_r($_POST); echo '</pre>';
        //удаление отмеченных фото
        if(isset($_POST['del']))
        {
            $dest = CATALOG_ALL."/";
            $destS = CATALOG_ALL."/small/";
            $del_array = $_POST['del'];

            $n = count($del_array); //echo '<pre>'; print_r($del_array); echo '</pre>';
            if($n)
            {
                for($i=0; $i<$n; $i++)
                {
                    $query = "SELECT img FROM ".$input_tab_img." WHERE id =".$del_array[$i];
                    $mysqli->_execute($query);
                    $row = $mysqli->fetch();
                    $file_name = $row['img'];

                    $file = './'.PATH_IMGD.CATALOG_ALL.'/'.$row['img'];
                    $fileS = './'.PATH_IMG.CATALOG_ALL.'/small/'.$row['img'];
                    $hasAFile = FindFileStuct($file_name, PATH_IMG.$dest);
                    if($hasAFile && $file_name !="empty.jpg")
                    {
                        unlink($file);
                    }
                    $hasAFile = FindFileStuct($file_name, PATH_IMG.$destS);
                    if($hasAFile && $file_name !="empty.jpg")
                    {
                        unlink($fileS);
                    }


                }
                $key_del_array = implode( ',',$del_array);
                $query = 'DELETE FROM '.$input_tab_img.' WHERE id IN ('.$key_del_array.')';
                $mysqli->query($query);

            }
        }
        if(isset($_FILES['editfile']) || isset($_POST['editfile']))
        {
            $editfile        = $_FILES['editfile'];
            $edit_key_alt    = $_POST['editfile'];
            $id              = $_POST['id'];

            //echo '<pre>';print_r($editfile); echo '</pre>';
            //echo '<pre>';print_r($_POST); echo '</pre>';
            $edit_error = editIMG($editfile, $id, $edit_key_alt, $input_tab_img);
            $str = substr($edit_error, 0, 1);
            if(!$str)
            {
                $str = substr($edit_error, 1);
                $error.=$str;
            }
        }

        if(!empty($error)){
            $messages = new Messages('error', '<ul>'.$error.'</ul>' );
            echo $messages->Content;
        }
        else{

            header(sprintf("Location: %s", $GoToSeccess));
        }
    }
    elseif(isset($_POST['add_input_img'])){
        $key             = $_FILES['addimg'];
        $key_alt         = $_POST['addimg'];
        $id              = $_POST['id'];
        //echo '<pre>';print_r($_POST); echo '</pre>';
        $put_error = putIMG($key, $id, $key_alt, $input_tab_img);
        $str = substr($put_error, 0, 1);
        if(!$str)
        {
            $str = substr($put_error, 1);
            $error.=$str;
        }
        if(!empty($error)){
            $messages = new Messages('error', '<ul>'.$error.'</ul>' );
            echo $messages->Content;
        }
        else{

            header(sprintf("Location: %s", $GoToSeccess));
        }
    }
    else{
        header("Location: ".$GoTo);
        exit;
    }
}
/*

function edit_all(){
    global $arResult;
    global $Catalog;
    $tab = CATALOG_ALL;

    $menu_eng = $arResult->DATA["eng"];
    if(isset($arResult->POS1) && $arResult->POS1 !=''){
        $cat = $arResult->POS1;
    }
    if(isset($arResult->POS3) && $arResult->POS3 !='')
    {
        $id = $arResult->POS3;
    }
    foreach($Catalog as $key => $menu){
        foreach($menu["Submenu"] as $key2 => $submenu){
            if(sizeof($submenu["All"]) > 0 && $submenu["All"] !=''){
                foreach($submenu["All"] as $key3 => $value){
                    if($value["id"] == $id){
                        $cat = $menu["eng"];
                        $sub = $submenu["eng"];
                        $rus =  htmlspecialchars($value['title'], ENT_QUOTES);
                        $content = $value["content"];
                        $name_pos = '<a href="'.DOMEN_LOC.'/'.$cat.'/'.$sub.'" >'.$rus.'</a>';
                        $price = sprintf("%.2f", round($value["price"], 2));
                        $img = $value["img"];
                        $alt = $value["alt"];
                        $img_title = $value["img_title"];
                    }

                }
            }

        }

    }
    //ссылка назад
    $back_link = ADMIN_PANEL.'/edit_catalog/'.$cat.'/submenu/'.$sub;
    $back = '<a href="'.$back_link.'" '.STYLE12.'>'.BACK_IMG.' назад</a>';
    //foto
    $legend = "Изображение";
    $img_now = ' (текущее '.$img.')';

    //проверяем изображение на наличие
    $path_img = CATALOG_ALL.'/small/';
    $img = FindImg($img, $path_img, $tab, $id);
    $img = '<img src="'.PATH_IMG.$path_img.$img.'" width="98px"/>';
    $name_del = 'delete';
    $name_img = 'addimg['.$id.']';
    $name_alt = 'addimg[alt_'.$id.']';
    $name_img_title = 'addimg[title_'.$id.']';

    $main_img = file_get_contents( 'admin_panel/templates/edit_fotoIcon.tpl');
    $main_img = str_replace( '{legend}', $legend, $main_img );
    $main_img = str_replace( '{img_now}', $img_now, $main_img );
    $main_img = str_replace( '{name_img}', $name_img, $main_img );
    $main_img = str_replace( '{name_alt}', $name_alt, $main_img );
    $main_img = str_replace( '{name_img_title}', $name_img_title, $main_img );
    $main_img = str_replace( '{alt}', $alt, $main_img );
    $main_img = str_replace( '{img_title}', $img_title, $main_img );
    $main_img = str_replace( '{img}', $img, $main_img );
    $main_img = str_replace( '{name_del}', $name_del, $main_img );

    //....................................table_redact............................................
    $name_content = 'content';
    $helpbox = 'helpbox';
    $i = '1';
    $addbbcode20 = '<select name="addbbcode20" onChange="bbfontstyle(\'[color=\' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + \']\', \'[/color]\', \''.$i.'\'); this.selectedIndex=0;" onMouseOver="helpline(\'s\', \''.$i.'\')" onMouseOut="helpline(\'h\', \''.$i.'\')">';
    $table_redact = file_get_contents( 'admin_panel/templates/table_redact.tpl');
    $table_redact = str_replace( '{content}', $content, $table_redact );
    $table_redact = str_replace( '{name_content}', $name_content, $table_redact );
    $table_redact = str_replace( '{helpbox}', $helpbox, $table_redact );
    $table_redact = str_replace( '{i}', $i, $table_redact );
    $table_redact = str_replace( '{addbbcode20}', $addbbcode20, $table_redact );
    //................................................................................................
    $action = ADMIN_PANEL.'/get_edit_all';
    $html   = file_get_contents( 'admin_panel/templates/edit_element.tpl');
    $html = str_replace( '{action}', $action, $html );
    $html = str_replace( '{back}', $back, $html );
    $html = str_replace( '{title}', $name_pos, $html );
    $html = str_replace( '{zagolovok}', $rus, $html );
    $html = str_replace( '{table_redact}', $table_redact, $html );
    $html = str_replace( '{price}', $price, $html );
    $html = str_replace( '{main_img}', $main_img, $html );
    $html = str_replace( '{table}', $tab, $html );
    $html = str_replace( '{menu_eng}', $menu_eng, $html );
    $html = str_replace( '{id}', $id, $html );
    return $html;
}
function get_edit_all(){
    $mysqli = M_Core_DB::getInstance();
    $GoTo = ADMIN_PANEL;//echo '<pre>'; print_r($_POST); echo '</pre>';
    $_SESSION['prevPage'] = $_SERVER['HTTP_REFERER'];
    $GoToSeccess = $_SESSION['prevPage'];
    if ((isset($_POST["MM_edit"])) && ($_POST["MM_edit"] == "MM_edit")){
        if(isset($_POST['zagolovok'])){
            $title = GetFormValue($_POST['zagolovok']);
        }
        if(isset($_POST['content'])){
            $content = GetFormValue($_POST['content']);
        }
        if(isset($_POST['price'])){
            $price = round($_POST['price'], 2);
        }
        if(isset($_FILES['addimg'])){
            $file = $_FILES['addimg'];
        }
        if(isset($_POST['addimg'])){
            $keys = $_POST['addimg'];
        }
        if(isset($_POST['delete'])){
            $delete = $_POST['delete'];
        }
        if(isset($_POST['edit_id'])){
            $id = $_POST['edit_id'];
        }
        if(isset($_POST['menu_eng'])){
            $menu_eng = $_POST['menu_eng'];
        }
        $error = '';
        $dest = CATALOG_ALL."/";
        $destS = CATALOG_ALL."/small/";
        $tab = CATALOG_ALL;


        if ( empty( $title ) )
            $error = $error.'<li>не заполнено название позиции по-русски</li>'."\n";
        //if ( preg_match( "/[&$^%#*@!+=(){}:;\/]+$/ui",  $name) )
        //$error = $error.'<li>название содержит недопустимые символы (пробел, %, $ и т.д.)</li>'."\n";

        //удаление отмеченных фото иконок для работ
        if(isset($_POST['delete']) && $_POST['delete'] == 'yes')
        {
            $query = "SELECT img FROM ".$tab." WHERE id =".$id;
            $mysqli->_execute($query);
            $row = $mysqli->fetch();
            $file_name = $row['img'];
            $file = './'.PATH_IMGD.COMPLECT.'/'.$row['img'];
            $fileS = './'.PATH_IMG.COMPLECT.'/small/'.$row['img'];
            $hasAFile = FindFileStuct($file_name, PATH_IMG.$dest);
            if($hasAFile && $file_name !="empty.jpg")
            {
                unlink($file);
            }
            $hasAFile = FindFileStuct($file_name, PATH_IMG.$destS);
            if($hasAFile && $file_name !="empty.jpg")
            {
                unlink($fileS);
            }
            $img = '';
            $query = sprintf("UPDATE ".$tab." SET img=%s WHERE id=%s",
                GetSQLValueString($img, "text"),
                GetSQLValueString($id, "int"));
            $mysqli->query($query);
        }
        else{
            if(isset($_FILES['addimg'])){
                //добавление изображения
                $edit_error = editIMG($file, $id, $keys, $tab, $menu_eng);
                $str = substr($edit_error, 0, 1);
                if(!$str)
                {
                    $str = substr($edit_error, 1);
                    $error.=$str;
                }
            }
        }
        if(!empty($error)){
           return showErrorMessage( '<ul>'.$error.'</ul>', '' );

        }
        else{
            $query = sprintf("UPDATE ".$tab." SET title=%s, content=%s, price=%s  WHERE id=%s",
                GetSQLValueString($title, "text"),
                GetSQLValueString($content, "text"),
                GetSQLValueString($price, "float"),
                GetSQLValueString($id, "int"));
            $mysqli->query($query);
            header(sprintf("Location: %s", $GoToSeccess));
        }
    }
    else{
        header("Location: ".$GoTo);
        exit;
    }
}
*/
//редактирование разных таблиц
function edit_table($r)
{
    $mysqli = M_Core_DB::getInstance();
    access();
    access_rights($r);
    global $arResult;
    $GoTo = ADMIN_PANEL;
    $html = '';
    if(isset($arResult->POS1))
    {
        $id = $arResult->POS1;
    }
    else
    {
        header("Location: ".$GoTo);
    }
    if(isset($arResult->POS2))
    {
        $tab = $arResult->POS2;
    }
    else
    {
        header("Location: ".$GoTo);
    }

    if($tab == TABLE_ADMIN_USERS)
    {

    }

    $query  = "SELECT name
	           FROM ".$tab."
			   WHERE id=".$id;
    $mysqli->_execute($query);
    $row = $mysqli->fetch();
    $back    = '<a href="'.ADMIN_PANEL.'/'.$tab.'" '.STYLE12.'>'.BACK_IMG.' назад</a>';
    $legend = $row["name"];
    $html.= file_get_contents( DIR_PATH.'admin_panel/templates/edit_table_admin.tpl' );
    $html = str_replace( '{back}',$back, $html);
    $html = str_replace( '{table}',$tab, $html);
    $html = str_replace( '{legend}',$legend, $html);
    $html = str_replace( '{name}',$legend, $html);
    $html = str_replace( '{id}',$id, $html);
    $html = str_replace( '{link}', $_SERVER['REQUEST_URI'], $html);
    return $html;
}
// вид добавления новости внизу таблицы
function view_add_menu_news()
{
    $table      = TABLE_NEWS;
    /*....................................table_redact............................................*/
    $content = '';
    $name_content = 'content';
    $helpbox = 'helpbox';
    $i = '1';
    $addbbcode20 = '<select name="addbbcode20" onChange="bbfontstyle(\'[color=\' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + \']\', \'[/color]\', \''.$i.'\'); this.selectedIndex=0;" onMouseOver="helpline(\'s\', \''.$i.'\')" onMouseOut="helpline(\'h\', \''.$i.'\')">';

    $table_redact = file_get_contents( DIR_PATH.'admin_panel/templates/table_redact.tpl');
    $table_redact = str_replace( '{content}', $content, $table_redact );
    $table_redact = str_replace( '{name_content}', $name_content, $table_redact );
    $table_redact = str_replace( '{helpbox}', $helpbox, $table_redact );
    $table_redact = str_replace( '{i}', $i, $table_redact );
    $table_redact = str_replace( '{addbbcode20}', $addbbcode20, $table_redact );

    if(HOST != HOST_NAME){
        $disabled = 'disabled';
    }
    $html = file_get_contents( DIR_PATH.'admin_panel/templates/add_news.tpl');
    $html = str_replace( '{table}', $table, $html );
    $html = str_replace( '{title}', '', $html );
    $html = str_replace( '{table_redact}', $table_redact, $html );
    $html = str_replace( '{disabled}', $disabled, $html );
    $html = str_replace( '{id}', '', $html );
    $html = str_replace( '{link}', $_SERVER['REQUEST_URI'], $html );
    return $html;
}
/*........................редактирование данных из таблицы news........*/
function edit_news($r)
{
    global $arResult;
    access();
    access_rights($r);
    $tab = TABLE_NEWS;

    $news_obj = new News();
    $row = $news_obj->Content;
    $content = $row['content'];
    $name_content = 'content';
    $helpbox = 'helpbox';
    $i = '1';
    $addbbcode20 = '<select name="addbbcode20" onChange="bbfontstyle(\'[color=\' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + \']\', \'[/color]\', \''.$i.'\'); this.selectedIndex=0;" onMouseOver="helpline(\'s\', \''.$i.'\')" onMouseOut="helpline(\'h\', \''.$i.'\')">';

    $table_redact = file_get_contents( DIR_PATH.'admin_panel/templates/table_redact.tpl');
    $table_redact = str_replace( '{content}', $content, $table_redact );
    $table_redact = str_replace( '{name_content}', $name_content, $table_redact );
    $table_redact = str_replace( '{helpbox}', $helpbox, $table_redact );
    $table_redact = str_replace( '{i}', $i, $table_redact );
    $table_redact = str_replace( '{addbbcode20}', $addbbcode20, $table_redact );
    ////.......................................................
    $title = htmlspecialchars ($row['title']);
    $title1 = '<a href="http://'.HOST.'/news/'.$row['id'].'">'.$row['title'].'</a>';
    $back = '<a href="'.ADMIN_PANEL.'/news" '.STYLE12.'>'.BACK_IMG.' назад</a>';
    $html = file_get_contents( DIR_PATH.'admin_panel/templates/edit_news.tpl');
    $html = str_replace( '{back}', $back, $html );
    $html = str_replace( '{title1}', $title1, $html );
    $html = str_replace( '{title}', $title, $html );
    $html = str_replace( '{id}', $row['id'], $html );
    $html = str_replace( '{table_redact}', $table_redact, $html );
    $html = str_replace( '{table}', $tab, $html );
    $html = str_replace( '{link}', $_SERVER['REQUEST_URI'], $html);

    return $html;
}


