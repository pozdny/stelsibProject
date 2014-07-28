<?php
function wrapStrings($str)
{
    return nl2br($str);
}

function convertOpeningTags($str, $internalTag, $htmlTag, $attName, $folder, $attributes){
    $beginTag = 0;
    $endTag = 0;
    $beginHref = 0;
    $endHref = 0;
    $attribures = '';
    $internalTagFull = "[" . $internalTag . " ";
    while ($beginTag = stripos($str, $internalTagFull, $endTag)){
        $endTag = stripos($str, "]", $beginTag);
        $beginHref = $beginTag + strlen($internalTagFull);
        $endHref = $endTag - 1;
        $href = substr($str, $beginHref, $endHref - $beginHref + 1);
        $htmlTagFull = "<" . $htmlTag . " " . $attName . "=\"" . $folder . $href . "\"" . $attribures . ">";
        $str = substr_replace($str, $htmlTagFull, $beginTag, $endTag - $beginTag + 1);
    }
    return $str;
}
function convertComplexTags($str){
    $s = convertOpeningTags($str, "IMG", "IMG", "SRC", "img/", "");
    $s = convertOpeningTags($str, "FILE", "A", "HREF", "file/", "");
    $s = str_ireplace("[/FILE]", "</FILE>", $s);
    $s = convertOpeningTags($s, "A", "A", "HREF", "", "TARGET=\"_blank\"");
    $s = str_ireplace("[/A]", "</A>", $s);
    return $s;
}

function prepareText($str){
    $s = htmlspecialchars($str);
    $s = convertComplexTags($str);
    $s = wrapStrings($s);
    return $s;
}
function getOrderedListL( $matches )
{
    $tmp = trim( $matches[1] );
    $reg = "#\;+#isU";
    $message = preg_replace($reg, '|', $tmp);
    $arr = explode('|', $message);
    if( count($arr) == 1)
    {
        $reg = "#\s#isU";
        $message = preg_replace($reg, '|', $tmp);
        $arr = explode('|', $message);
    }
    $elements = '';
    $list = '';
    $count = sizeof($arr);
    foreach ( $arr as $value ) {
        if($count != 1)
        {
            $znak = ';';
        }
        else
        {
            $znak = '';
        }
        if($value == '') break;
        $elements.= '- '.trim($value).$znak.'<br />';
        $count--;
    }
    $list.=$elements;
    return $list;
}
function getOrderedList( $matches )
{
    if ( $matches[1] == '1' )
        $list = '<ol type="1">';
    else
        $list = '<ol type="a">';
    $tmp = trim( $matches[2] );
    $reg = "#\;+#isU";
    $message = preg_replace($reg, '|', $tmp);
    $arr = explode('|', $message);
    if( count($arr) == 1)
    {
        $reg = "#\s#isU";
        $message = preg_replace($reg, '|', $tmp);
        $arr = explode('|', $message);
    }
    $elements = '';
    $flag = '';
    $count = sizeof($arr);
    $num = $count;
    $znak_ul = ';';
    for ($i=0; $i<$num; $i++)
    {
        if($count != 1)
        {
            $znak = ';';
        }
        else
        {
            $znak = '';
        }
        if($arr[$i] == '') break;
        $reg_val = "#\[g]?#isU";
        $string = $arr[$i];
        $value  = $arr[$i];
        if($flag == false)
        {
            $pos = strpos($arr[$i], '<ul>');
            if ($pos !== false)
            {
                $flag = true;
                $value = substr($arr[$i], 0, $pos);
                $value_ul = substr($string, $pos);
                $znak = '';
                $znak_ul = ';';
                $elements.='<li>'.trim($value).$znak.$value_ul.$znak_ul;
            }
            else
            {
                $elements.='<li>'.trim($value).$znak.'</li>';
            }

        }
        else
        {
            $value = $arr[$i];
            $pos = strpos($arr[$i], '</ul>');
            if ($pos)
            {
                $value_ul = $value;
                $elements.= $value_ul.'</li>';
                $flag = false;
            }
            else
            {
                $value_ul=$value;
                $elements.=$value_ul.$znak_ul;
            }

        }

        $count--;
    }
    $list.=$elements;
    $list.='</ol>';
    return $list;
}
function getUnorderedList( $matches )
{

    $list = '<ul>';
    $tmp = trim( $matches[1] );
    $reg = "#\;+#isU";
    $message = preg_replace($reg, '|', $tmp);
    $arr = explode('|', $message);
    if( count($arr) == 1)
    {
        $reg = "#\s#isU";
        $message = preg_replace($reg, '|', $tmp);
        $arr = explode('|', $message);
    }
    $elements = '';
    $count = sizeof($arr);
    foreach ( $arr as $value ) {
        if($count != 1)
        {
            $znak = ';';
        }
        else
        {
            $znak = '';
        }
        if($value == '') break;
        $elements = $elements.'<li>'.trim($value).$znak.'</li>';
        $count--;
    }
    $list = $list.$elements;
    $list = $list.'</ul>';
    return $list;
}
function split_text($matches)
{
    return wordwrap($matches[1], 35, ' ',1);
}
//...................................преобразование тегов [] в <>......................//
function print_page($message)
{
    // Разрезаем слишком длинные слова
    $message = preg_replace_callback(
        "|([a-zа-я\d!]{35,})|i",
        "split_text",
        $message);

    // Тэги - [code], [php], [sql]
    preg_match_all( "#\[php\](.+)\[\/php\]#isU", $message, $matches );
    $cnt = count( $matches[0] );
    for ( $i = 0; $i < $cnt; $i++ ) {
        $phpBlocks[] = '<div class="codePHP">'.highlight_string( $matches[1][$i], true ).'</div>';
        // Вот над этим надо будет подумать - усовершенствовать рег. выражение
        // $phpBlocks[$i] = str_replace( '<div class="codePHP"><br />', '<div class="codePHP">', $phpBlocks[$i] );
        $uniqidPHP = '[php_'.uniqid('').']';
        $uniqidsPHP[] = $uniqidPHP;
        $message = str_replace( $matches[0][$i], $uniqidPHP, $message );
    }

    $spaces = array( ' ', "\t" );
    $entities = array( '&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;' );

    preg_match_all( "#\[code\](.+)\[\/code\]#isU", $message, $matches );
    $cnt = count( $matches[0] );

    for ( $i = 0; $i < $cnt; $i++ ) {
        $codeBlocks[] = '<div class="code">'.nl2br( str_replace( $spaces, $entities, htmlspecialchars( $matches[1][$i] ) ) ).'</div>';
        // Вот над этим надо будет подумать - усовершенствовать рег. выражение
        $codeBlocks[$i] = str_replace( '<div class="code"><br />', '<div class="code">', $codeBlocks[$i] );
        $uniqidCode = '[code_'.uniqid('').']';
        $uniqidsCode[] = $uniqidCode;
        $message = str_replace( $matches[0][$i], $uniqidCode, $message );
    }

    preg_match_all( "#\[sql\](.+)\[\/sql\]#isU", $message, $matches );
    $cnt = count( $matches[0] );
    for ( $i = 0; $i < $cnt; $i++ ) {
        $sqlBlocks[] = '<div class="codeSQL">'.highlight_sql( $matches[1][$i] ).'</div>';
        // Вот над этим надо будет подумать - усовершенствовать рег. выражение
        $sqlBlocks[$i] = str_replace( '<div class="codeSQL"><br />', '<div class="codeSQL">', $sqlBlocks[$i] );
        $uniqidSQL = '[sql_'.uniqid('').']';
        $uniqidsSQL[] = $uniqidSQL;
        $message = str_replace( $matches[0][$i], $uniqidSQL, $message );
    }

    preg_match_all( "#\[js\](.+)\[\/js\]#isU", $message, $matches );
    $cnt = count( $matches[0] );
    for ( $i = 0; $i < $cnt; $i++ ) {
        $jsBlocks[] = '<div class="codeJS">'.geshi_highlight($matches[1][$i], 'javascript', '', true).'</div>';
        // Вот над этим надо будет подумать - усовершенствовать рег. выражение
        $jsBlocks[$i] = str_replace( '<div class="codeJS"><code><br />', '<div class="codeJS"><code>', $jsBlocks[$i] );
        $uniqidJS = '[js_'.uniqid('').']';
        $uniqidsJS[] = $uniqidJS;
        $message = str_replace( $matches[0][$i], $uniqidJS, $message );
    }

    preg_match_all( "#\[css\](.+)\[\/css\]#isU", $message, $matches );
    $cnt = count( $matches[0] );
    for ( $i = 0; $i < $cnt; $i++ ) {
        $cssBlocks[] = '<div class="codeCSS">'.geshi_highlight($matches[1][$i], 'css', '', true).'</div>';
        // Вот над этим надо будет подумать - усовершенствовать рег. выражение
        $cssBlocks[$i] = str_replace( '<div class="codeCSS"><code><br />', '<div class="codeCSS"><code>', $cssBlocks[$i] );
        $uniqidCSS = '[css_'.uniqid('').']';
        $uniqidsCSS[] = $uniqidCSS;
        $message = str_replace( $matches[0][$i], $uniqidCSS, $message );
    }

    preg_match_all( "#\[html\](.+)\[\/html\]#isU", $message, $matches );
    $cnt = count( $matches[0] );
    for ( $i = 0; $i < $cnt; $i++ ) {
        $htmlBlocks[] = '<div class="codeHTML">'.geshi_highlight($matches[1][$i], 'html4strict', '', true).'</div>';
        // Вот над этим надо будет подумать - усовершенствовать рег. выражение
        $htmlBlocks[$i] = str_replace( '<div class="codeHTML"><br />', '<div class="codeHTML">', $htmlBlocks[$i] );
        $uniqidHTML = '[html_'.uniqid('').']';
        $uniqidsHTML[] = $uniqidHTML;
        $message = str_replace( $matches[0][$i], $uniqidHTML, $message );
    }
    /*
preg_match_all( "#\[img\][\s]*([\S]+)[\s]*\[\/img\]#isU", $message, $matches );
foreach ( $matches[0] as $src ) {
  $img = file_get_contents( $src );
  file_put_contents( );
}
*/


    $message = preg_replace("#\[b\](.+)\[\/b\]#isU", '<b>\\1</b>', $message);
    $message = preg_replace("#\[strong\](.+)\[\/strong\]#isU", '<strong>\\1</strong>', $message);
    $message = preg_replace("#\[i\](.+)\[\/i\]#isU", '<i>\\1</i>', $message);
    $message = preg_replace("#\[u\](.+)\[\/u\]#isU", '<u>\\1</u>', $message);
    $message = preg_replace("#\[h1\](.+)\[\/h1\]#isU", '<h1>\\1</h1>', $message);
    $message = preg_replace("#\[h2\](.+)\[\/h2\]#isU", '<h2>\\1</h2>', $message);
    $message = preg_replace("#\[h3\](.+)\[\/h3\]#isU", '<h3>\\1</h3>', $message);
    $message = preg_replace("#\[txt10\](.+)\[\/txt10\]#isU", '<span class="text10">\\1</span>', $message);
    $message = preg_replace("#\[txt13\](.+)\[\/txt13\]#isU", '<span class="text13">\\1</span>', $message);
    $message = preg_replace("#\[txt14\](.+)\[\/txt14\]#isU", '<span class="text14">\\1</span>', $message);
    $message = preg_replace("#\[txt15\](.+)\[\/txt15\]#isU", '<span class="text15">\\1</span>', $message);
    $message = preg_replace("#\[blockquote\](.+)\[\/blockquote\]#isU", '<blockquote>\\1</blockquote>', $message);

    $message = preg_replace("#\[quote\](.+)\[\/quote\]#isU",'<div class="quoteHead">Цитата</div><div class="quoteContent">\\1</div>',$message);
    $message = preg_replace("#\[quote=&quot;([- 0-9a-zа-яА-Я]{1,30})&quot;\](.+)\[\/quote\]#isU", '<div class="quoteHead">\\1 пишет:</div><div class="quoteContent">\\2</div>', $message);
    $message = preg_replace("#\[url\][\s]*([\S]+)[\s]*\[\/url\]#isU",'<a href="\\1" >\\1</a>',$message);
    $message = preg_replace("#\[url[\s]*=[\s]*([\S]+)[\s]*\][\s]*([^\[]*)\[/url\]#isU", '<a href="\\1" style="text-decoration:underline;">\\2</a>', $message);
    $message = preg_replace("#\[color=orange\](.+)\[\/color\]#isU",'<span style="color:#ff3202; font-width:normal">\\1</span>',$message);
    $message = preg_replace("#\[color=green\](.+)\[\/color\]#isU",'<span style="color:#5A8D2E; font-width:normal">\\1</span>',$message);
    $message = preg_replace("#\[color=blue\](.+)\[\/color\]#isU",'<span style="color:#2c52e3; font-width:normal">\\1</span>',$message);
    $message = preg_replace("#\[color=darkblue\](.+)\[\/color\]#isU",'<span style="color:darkblue; font-width:normal">\\1</span>',$message);

    $message = preg_replace_callback("#\[list\](.+;?)\[/list\]#siU","getUnorderedList",$message);
    $message = preg_replace_callback("#\[list=([a|1]|\s*)\](.*;?)\[/list\]#siU", 'getOrderedList',$message);
    $message = preg_replace_callback("#\[g\](.+;?)\[/g\]#siU", 'getOrderedListL',$message);

    $message = nl2br( $message);

    if ( isset( $uniqidCode ) ) $message = str_replace( $uniqidsCode, $codeBlocks, $message );
    if ( isset( $uniqidPHP ) ) $message = str_replace( $uniqidsPHP, $phpBlocks, $message );
    if ( isset( $uniqidSQL ) ) $message = str_replace( $uniqidsSQL, $sqlBlocks, $message );
    if ( isset( $uniqidJS ) ) $message = str_replace( $uniqidsJS, $jsBlocks, $message );
    if ( isset( $uniqidCSS ) ) $message = str_replace( $uniqidsCSS, $cssBlocks, $message );
    if ( isset( $uniqidHTML ) ) $message = str_replace( $uniqidsHTML, $htmlBlocks, $message );

    // Над этим тоже надо будет подумать
    $message = str_replace( '</div><br />', '</div>', $message );

    // Удаляем непарные теги - сам не знаю, нужно это делать или нет?
    // $tags = array( '[b]', '[/b]', '[i]', '[/i]', '[u]', '[/u]', '[code]', '[quote]', '[/quote]', '[/code]', '[url]', '[/url]' );
    // $message = str_replace( $tags, '', $message );

    return $message;
}

?>