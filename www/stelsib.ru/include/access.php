<?php
function access()
{
    $MM_authorizedUsers = "a,m";
    $MM_restrictGoTo = '/admin-panel?'.SAVE_CODE;
    if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_Username']['rights']))))
    {
        //header("Location: ".$MM_restrictGoTo);
        exit;
    }
}
function access_rights($ar)
{
    $MM_authorizedUsers = $ar;
    $MM_restrictGoTo = '/admin-panel?'.SAVE_CODE;
    if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username']["name"], $_SESSION['MM_Username']['rights']))))
    {
        //header("Location: ".$MM_restrictGoTo);
        exit;
    }
}
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) {

  // For security, start by assuming the visitor is NOT authorized.
  $isValid = false;

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username.
  // Therefore, we know that a user is NOT logged in if that Session variable is blank.
  if (!empty($UserName))
  {
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login.
    // Parse the strings into arrays.
    $arrUsers = explode(",", $strUsers);
    $arrGroups = explode(",", $strGroups);
    if (in_array($UserName, $arrUsers))
	{
      $isValid = true;
    }
    // Or, you may restrict access to only certain users based on their username.
    if (in_array($UserGroup, $arrGroups))
	{
      $isValid = true;
    }
    if (($strUsers == "") && false)
	{
      $isValid = true;
    }
  }
  return $isValid;
}

