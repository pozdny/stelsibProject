<?php
require_once('connection/DBClass.php');
require_once('include/config.php');
$mysqli = M_Core_DB::getInstance();
$query = "INSERT INTO ".TABLE_ADMIN_USERS." (name, login, password) VALUES ('Юрий Н.', '".md5('LAdmin_ssgu&@')."', '".md5('AdmStel2014_27J9$')."')";
$mysqli->query($query);

