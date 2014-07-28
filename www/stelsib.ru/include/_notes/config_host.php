<?php
if($host == HOST_NAME){
    $home_url = '';
    $k = '';
    $reg_eng = '';
}
elseif($host == 'krasnoyarsk.'.HOST_NAME){
    $home_url = 'http://'.HOST_NAME;
    $k = '0.02';
    $reg_eng = '_krasnoyarsk';
}
elseif($host == 'barnaul.'.HOST_NAME){
    $home_url = 'http://'.HOST_NAME;
    $k = '0.008';
    $reg_eng = '_barnaul';
}
elseif($host == 'tomsk.'.HOST_NAME){
    $home_url = 'http://'.HOST_NAME;
    $k = '0.01';
    $reg_eng = '_tomsk';
}
elseif($host == 'irkutsk.'.HOST_NAME){
    $home_url = 'http://'.HOST_NAME;
    $k = '0.02';
    $reg_eng = '_irkutsk';
}
elseif($host == 'omsk.'.HOST_NAME){
    $home_url = 'http://'.HOST_NAME;
    $k = '0.015';
    $reg_eng = '_omsk';
}
elseif($host == 'yakutsk.'.HOST_NAME){
    $home_url = 'http://'.HOST_NAME;
    $k = '0.3';
    $reg_eng = '_yakutsk';
}
else{
    $home_url = '';
    $k = '';
    $reg_eng = '';
}