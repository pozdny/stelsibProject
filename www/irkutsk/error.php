<?php
if(isset($_GET['id']))
{
	$id = $_GET['id'];
	$id = abs(intval($id));
}
else
{
	$id = 400;
}

// ассоциативный массив кодов и описаний
$a[401] = "Требуется авторизация";
$a[403] = "Пользователь не прошел аутентификацию, доступ запрещен";
$a[404] = "Документ не найден";
$a[500] = "Внутренняя ошибка сервера";
$a[503] = "Внутренняя ошибка сервера";
$a[400] = "Неправильный запрос";
$server_name     = $_SERVER['SERVER_NAME'];
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $a[$id]; ?></title>
<meta charset="utf-8" >
<meta http-equiv="refresh" content="25;url=http://<?echo $server_name ?>">
</head>
<body>
<?php
//настройка временной зоны
$config['timezone'] = "Asia/Novosibirsk";
date_default_timezone_set($config['timezone']);
//объявляем необходимые переменные

if(isset($_GET['request']))
{
	$request_url = $_GET['request'];
}
else
{
	$request_url = $_SERVER['REQUEST_URI'];
}
//echo '<pre>';print_r($_SERVER);echo '</pre>';
$remote_addr     = $_SERVER['REMOTE_ADDR'];
$http_user_agent = $_SERVER['HTTP_USER_AGENT'];
// определяем дату и время в стандартном формате
$time = date("d.m.Y H:i:s");
// эта переменная содержит тело сообщения
switch($id)
{
	case 404:
	$body = 'Запрошенный Вами URL: <b>'.$server_name.$request_url.' </b> не найден (Not Found)!<br />
Можете вернуться на <a href="http://'.$server_name.'">главную страницу</a> и пройти по нужной ссылке на сайте <br />
или пройти по одной из следующий ссылок:<hr>
<a href="http://'.$server_name.'">Главная </a> |
<a href="/about">О компании </a> |
<a href="/arhivnye-stellazhi">Каталог </a> |
<a href="/oplata">Оплата и доставка </a> |
<a href="/contacs">Контакты</a> |
<a href="/prices">Цены</a> |
<a href="/news">Новости</a>
<hr>';
    break;
	case 500:
	$body = 'Запрошенный Вами URL: <b>'.$server_name.$request_url.' </b> временно недоступен.<br />
Возможные причины: технические работы на сервере, ошибка сервера, либо другое.<br />
Попробуйте зайти позже.
<hr>';
    break;
	case 503:
	$body = 'Запрошенный Вами URL: <b>'.$server_name.$request_url.' </b> временно недоступен.<br />
Возможные причины: технические работы на сервере, ошибка сервера, либо другое.<br />
Попробуйте зайти позже.
<hr>';
    break;
    default:
	 $body = 'Запрошенный Вами URL: <b>'.$server_name.$request_url.' </b> не найден (Not Found)!<br />
Можете вернуться на <a href="http://'.$server_name.'">главную страницу</a> и пройти по нужной ссылке на сайте <br />
или пройти по одной из следующий ссылок:<hr>
<a href="http://'.$server_name.'">На главную </a> |
<a href="/about">О компании </a> |
<a href="/catalog">Каталог </a> |
<a href="/oplata">Оплата и доставка </a> |
<a href="/contacs">Контакты</a> |
<a href="/prices">Цены</a> |
<a href="/news">Новости</a>
<hr>';
}
$finish = 'Ваш IP: <b>'.$remote_addr.'</b><br />
Ваш браузер: <b>'.$http_user_agent.'</b><br />
Текущее время сервера: <b>'.$time.'</b><br />';
//Если зашел с другого адреса

?>
<!-- Вывод сообщения -->
<h1><i><?=$id?></i> <?=$a[$id]?></h1>
<div><?=$body.$finish?></div>
<?=$_SERVER['SERVER_SIGNATURE']?> 

</body>
</html>































































































































































































































