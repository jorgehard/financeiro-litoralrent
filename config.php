<?php
header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_MONETARY,"pt_BR", "ptb");

extract($_POST); 
extract($_GET);

class DBConnection extends PDO
{
    public function __construct()
    {
		global $titulo_geral;
		try{
			/*$titulo_geral = "LitoralRent";
			$DBhost = "polemicalitoral.com.br";
			$DBname = "polemica_freat2";
			$DBuser = "polemica_freat2";
			$DBpass = "5IqryeWlDCJP";*/
			$titulo_geral = "LitoralRent";
			$DBhost = "localhost";
			$DBname = "polemica_freat2";
			$DBuser = "root";
			$DBpass = "";

			parent::__construct("mysql:host=".$DBhost.";dbname=".$DBname.";charset=utf8;",$DBuser, $DBpass);
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $i)
		{
			echo 'Error: ' . $i->getMessage();
		}
	}
}

