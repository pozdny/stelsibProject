<?php
$home_url = 'http://'.HOST_NAME;
if($host == HOST_NAME){
    $home_url = '';
    $k = '';
    $reg_eng = '';
}
elseif($host == 'krasnoyarsk.'.HOST_NAME){
    $k = '0.02';
    $reg_eng = '_krasnoyarsk';
}
elseif($host == 'barnaul.'.HOST_NAME){
    $k = '0.008';
    $reg_eng = '_barnaul';
}
elseif($host == 'tomsk.'.HOST_NAME){
    $k = '0.01';
    $reg_eng = '_tomsk';
}
elseif($host == 'irkutsk.'.HOST_NAME){
    $k = '0.02';
    $reg_eng = '_irkutsk';
}
elseif($host == 'omsk.'.HOST_NAME){
    $k = '0.015';
    $reg_eng = '_omsk';
}
elseif($host == 'yakutsk.'.HOST_NAME){
    $k = '0.3';
    $reg_eng = '_yakutsk';
}
elseif($host == 'vladivostok.'.HOST_NAME){
    $k = '0.151';
    $reg_eng = '_vladivostok';
}
elseif($host == 'tumen.'.HOST_NAME){
    $k = '0.0371';
    $reg_eng = '_tumen';
}
elseif($host == 'ekaterinburg.'.HOST_NAME){
    $k = '0.0445';
    $reg_eng = '_ekaterinburg';
}
elseif($host == 'chelabinsk.'.HOST_NAME){
    $k = '0.0514';
    $reg_eng = '_chelabinsk';
}
elseif($host == 'perm.'.HOST_NAME){
    $k = '0.0457';
    $reg_eng = '_perm';
}
elseif($host == 'ufa.'.HOST_NAME){
    $k = '0.0525';
    $reg_eng = '_ufa';
}
elseif($host == 'kazan.'.HOST_NAME){
    $k = '0.056';
    $reg_eng = '_kazan';
}
elseif($host == 'nignij-novgorod.'.HOST_NAME){
    $k = '0.0491';
    $reg_eng = '_nignij_novgorod';
}
elseif($host == 'samara.'.HOST_NAME){
    $k = '0.0594';
    $reg_eng = '_samara';
}
elseif($host == 'volgograd.'.HOST_NAME){
    $k = '0.07';
    $reg_eng = '_volgograd';
}
elseif($host == 'voroneg.'.HOST_NAME){
    $k = '';
    $reg_eng = '_voroneg';
}
elseif($host == 'rostov-na-donu.'.HOST_NAME){
    $k = '0.0648';
    $reg_eng = '_rostov_na_donu';
}
elseif($host == 'st-peterburg.'.HOST_NAME){
    $k = '0.0742';
    $reg_eng = '_st_peterburg';
}
elseif($host == 'moscow.'.HOST_NAME){
    $k = '0.072';
    $reg_eng = '_moscow';
}
else{
    $home_url = '';
    $k = '';
    $reg_eng = '';
}