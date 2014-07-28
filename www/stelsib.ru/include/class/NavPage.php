<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 10.05.14
 * Time: 17:15
 */

class NavPage {
    public $totalPages;
    public $pageNum;
    public $filename;
    public $u;
    public $admin;
    public $k;
    public $Content;
    public function __construct($totalPages, $pageNum, $filename, $u, $admin='', $k=''){
        $this->totalPages = $totalPages;
        $this->pageNum = $pageNum;
        $this->filename = $filename;
        $this->u = $filename;
        $this->admin= $admin;
        $this->k = $k;
        $this->navPage();
    }
    function navPage()
    {
        $html = '';
        $uri = 'http://'.HOST.$this->filename;
        $urii = '';
        $totalPages = $this->totalPages;
        if ($this->totalPages > 1 )
        {
            // Проверяем нужна ли стрелка "В начало"
            // Находим две ближайшие станицы с обоих краев, если они есть
            $tpr = '';
            $tpl = '';
            $r=1;
            $le=1;
            $pageleft = '';
            $pageleft_l = '';
            if($this->k == '') $k = 10;
            else $k = $this->k;
            $left = '&nbsp;&nbsp;'.'<i class="fa fa-angle-double-left "></i>'.'&nbsp;&nbsp;';
            $right = '&nbsp;&nbsp;'.'<i class="fa fa-angle-double-right "></i>'.'&nbsp;&nbsp;';
            $pageNum = $this->pageNum;

            if($totalPages<=$k)
            {
                $total = $totalPages;
                while($le<$total)
                {
                    if ( $pageNum  > $le )
                    {
                        $num_p = $pageNum-$le;
                        if($num_p == 1)
                        {
                            $pos = strrpos($uri, '/');
                            if($pos)
                            {
                                $urii = substr($uri, 0, -1);
                            }
                            $num_str = '';
                        }
                        else $num_str = 'page='.$num_p;
                        $pageleft = ' <li><a  href="'.$urii.$num_str.'">'.($pageNum-$le).'</a></li>'."\n";
                    }
                    else
                        $pageleft = '';

                    $tpl = $pageleft.$tpl;
                    $le++;
                }
                while($r<$total)
                {
                    if ( $pageNum + $r <= $total)
                        $pageright = ' <li><a  href="'.$uri.'page='.($pageNum + $r).'">'.($pageNum + $r).'</a></li>'."\n";
                    else
                        $pageright = '';
                    $tpr = $tpr.$pageright;
                    $r++;
                }

            }
            else
            {
                $cel = ceil($pageNum/$k);
                $total = $cel * $k;
                if ( $pageNum >= $k )
                {

                    $pos = strrpos($uri, '/');
                    if($pos)
                    {
                        $urii = substr($uri, 0, -1);
                    }
                    $startpage = '<li><a href="'.$urii.'" >'.$left.'</a></li>'."\n";
                }
                else
                    $startpage = '';

                if ( $pageNum < $totalPages  )
                    $endpage = '<li><a href="'.$uri.'page='.$totalPages.'" >'.$right.'</a></li>'."\n";
                else
                    $endpage = '';
                //.............left........................
                $modul = $pageNum%$k;
                while($le<$total)
                {
                    $j = floor($pageNum/$k);
                    if($modul)
                    {
                        $modul1 = ($pageNum - $r)%$k;
                        if ( $pageNum  > $le )
                        {

                            if($modul1)
                            {
                                $pageleft = '<li><a  href="'.$uri.'page='.($pageNum-$le).'">'.($pageNum-$le).'</a></li>'."\n";
                            }
                            else
                            {

                                for($i = 0, $p = 1, $pk = $k; $i<$j; $i++, $p+=$k, $pk+=$k)
                                {
                                    $urii = $uri;
                                    if($p == 1)
                                    {
                                        $pos = strrpos($uri, '/');
                                        if($pos)
                                        {
                                            $urii = substr($uri, 0, -1);
                                        }
                                        $num_str = '';
                                    }
                                    else
                                    {

                                        $num_str = 'page='.$p;
                                    }
                                    $pageleft_l.= '<li><a  href="'.$urii.$num_str.'">'.$p.'-'.($pk-1).'</a></li>'."\n";
                                    if($p == 1) $p--;
                                }
                                $pageleft = $pageleft_l.'<li><a  href="'.$uri.'page='.($pageNum-$le).'">'.($pageNum-$le).'</a></li>'."\n";


                                $le = $total;
                            }
                        }
                        else
                            $pageleft = '';


                    }
                    else
                    {

                        for($i = 0, $p = 1, $pk = $k; $i<$j; $i++, $p+=$k, $pk+=$k)
                        {
                            $urii = $uri;
                            if($p == 1)
                            {
                                $pos = strrpos($uri, '/');
                                if($pos)
                                {
                                    $urii = substr($uri, 0, -1);
                                }
                                $num_str = '';
                            }
                            else $num_str = 'page='.$p;
                            $pageleft.= '<li><a  href="'.$urii.$num_str.'">'.$p.'-'.($pk-1).'</a></li>'."\n";
                            if($p == 1) $p--;
                        }
                        $le = $total;

                    }
                    $tpl = $pageleft.$tpl;
                    $le++;
                    $r++;
                }
                //.............right........................
                $r = 1;
                if($pageNum !=$totalPages){
                    while($r<=$total)
                    {

                        if($modul)
                        {
                            if ( $pageNum + $r <= $total)
                            {
                                if($pageNum + $r == $total)
                                {
                                    $right_list = $pageNum + $r + $k;
                                    if($right_list > $totalPages)
                                    {
                                        $right_list = $totalPages;
                                    }
                                    $pageright = '<li><a  href="'.$uri.'page='.($pageNum + $r).'">'.($pageNum + $r).'-'.$right_list.'</a></li>'."\n";
                                    $endpage = '<li><a href="'.$uri.'page='.$totalPages.'" >'.$right.'</a></li>'."\n";
                                    $tpr.= $pageright;
                                    break;
                                }
                                else
                                {
                                    if($pageNum == $totalPages)
                                    {
                                        $pageright = '';
                                        $tpr.= $pageright;
                                        break;
                                    }
                                    if($pageNum + $r == $totalPages)
                                    {
                                        $endpage = '';
                                        $pageright = '<li><a  href="'.$uri.'page='.($pageNum + $r).'">'.($pageNum + $r).'</a></li>'."\n";
                                        $r = $total;
                                    }
                                    else
                                    {
                                        $pageright = '<li><a  href="'.$uri.'page='.($pageNum + $r).'">'.($pageNum + $r).'</a></li>'."\n";
                                    }


                                }


                            }
                            else
                            {
                                if ( $pageNum + $r <= $totalPages)
                                {
                                    if($pageNum + $r == $totalPages)
                                    {

                                        $endpage = '';
                                    }
                                    $pageright = '<li><a  href="'.$uri.'page='.($pageNum + $r).'">'.($pageNum + $r).'</a></li>'."\n";
                                }
                                else
                                {
                                    $pageright = '';
                                }
                            }
                            $tpr.= $pageright;
                            $r++;
                        }
                        else
                        {
                            $total1 = $total + $k;
                            if ( $pageNum + $r <= $total1)
                            {
                                if($pageNum + $r == $total1)
                                {
                                    $right_list = $pageNum + $r + $k;
                                    if($right_list > $totalPages)
                                    {
                                        $right_list = $totalPages;
                                    }
                                    $pageright = '<li><a  href="'.$uri.'page='.($pageNum + $r).'">'.($pageNum + $r).'-'.$right_list.'</a></li>'."\n";
                                    $endpage = '<a href="'.$uri.'page='.$totalPages.'" >'.$right.'</a>'."\n";
                                    $tpr.= $pageright;
                                    break;
                                }
                                else
                                {
                                    if($pageNum + $r == $totalPages)
                                    {
                                        $pageright = '<li><a  href="'.$uri.'page='.($pageNum + $r).'">'.($pageNum + $r).'</a></li>'."\n";
                                        $endpage = '';
                                        $tpr.= $pageright;
                                        break;
                                    }
                                    else
                                    {
                                        $pageright = '<li><a  href="'.$uri.'page='.($pageNum + $r).'">'.($pageNum + $r).'</a></li>'."\n";
                                    }

                                }

                            }
                            else
                            {
                                if($pageNum + $r <= $totalPages)
                                {
                                    if($pageNum + $r == $totalPages)
                                    {
                                        $endpage = '';
                                    }
                                    $pageright = '<li><a  href="'.$uri.'page='.($pageNum + $r).'">'.($pageNum + $r).'</a></li>'."\n";
                                }
                                else
                                    $pageright = '';
                            }
                            $tpr.= $pageright;
                            $r++;
                        }
                    }
                }
                $tpl = $startpage.$tpl;
                $tpr=$tpr.$endpage;
            }

            $html.= '<div class="navpage"><ul><li class="titleList">Страница:</li>';
            $html.= $tpl.'<li class="current">'.$pageNum.'</li>'.$tpr;
            $html.= '</ul>';
            $html.= '</div>';


        }
        $this->Content = $html;
    }
} 