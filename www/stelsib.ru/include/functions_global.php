<?php
function getArResult()
{
    include_once( dirname( __FILE__ ) . '/class/ARR_Query.php' );
    $arResult_query = new ARR_Query();

    $arResult = $arResult_query;
	
	return $arResult;
}
