<?php
require_once('phpmailer/PHPMailer_v5.1/class.phpmailer.php');


class FreakMailer extends PHPMailer 
{
    	var $priority = 3;
    	var $to_name = 'Стелсиб';
    	var $to_email = 'zakaz@stelsib.ru';
    	var $From = 'support';
    	var $FromName = 'stelsib.ru';
    	var $Sender = null;
  
    	function FreakMailer()
    	{
			// Берем из файла config.php массив $site
            global $site;
            if (isset($_SESSION["region"])) {
                $region = $_SESSION["region"];
            }
      		if($site['smtp_mode'] == 'enabled')
     		{
        		$this->Host = $site['smtp_host'];
        		$this->Port = $site['smtp_port'];
        		if($site['smtp_username'] != '')
        		{
         			$this->SMTPAuth  = true;
        			$this->Username  = $site['smtp_username'];
        			$this->Password  =  $site['smtp_password'];
        		}
        		$this->Mailer = "smtp";
      		}
            $this->FromName = $this->FromName.' ['.$region.']';
      		if(!$this->From)
      		{
        		$this->From = $site['from_email'];
      		}
      		if(!$this->FromName)
      		{
        		$this-> FromName = $site['from_name'].'['.$region.']';
      		}
      		if(!$this->Sender)
      		{
        		$this->Sender = $site['from_email'];
      		}
      		$this->Priority = $this->priority;
    	}
}
