<?php 
session_start();
require_once('config.php');
$con = new DBConnection();

function verificaLogin(){
	global $con, $nome_usuario, $obra_usuario, $cidade_usuario, $login_usuario, $id_usuario_logado, $acesso_usuario, $status, $nivel_acesso, $editarss_usuario, $tipo_home, $tipo_login, $ip_usuario;
	if(isset($_COOKIE['user']) && isset($_COOKIE['password'])){
		$stat = $con->prepare("SELECT * FROM usuarios WHERE login = ? AND senha = ?");
		$stat->execute(array($_COOKIE['user'],$_COOKIE['password']));
		$count = $stat->rowCount();
		if ($count == "1"){
			$rowa = $stat->fetch(PDO::FETCH_ASSOC);
			$nome_usuario = $rowa['nome'];
			$login_usuario = $rowa['login'];
			$obra_usuario = $rowa['obra'];
			$cidade_usuario = $rowa['cidade'];
			$nivel_acesso = $rowa['nivel_acesso'];
			$acesso_usuario = $rowa['acesso_login'];
			$id_usuario_logado = $rowa['id'];
			$editarss_usuario = $rowa['editarss'];
			$tipo_home = $rowa['tipo_home'];
			$tipo_login = $rowa['tipo_login'];
			$ip_usuario = $_SERVER['REMOTE_ADDR'];	
			$atu = $con->prepare("UPDATE usuarios SET ultimo_login = now() WHERE id = ? ");
			$atu->execute(array($rowa['id']));
			$status = true;
		}
	}else{
		if(isset($_SESSION['login_usuario']) && isset($_SESSION['senha_usuario'])){
			$stat = $con->prepare("SELECT * FROM usuarios WHERE login = ? AND senha = ?");
			$stat->execute(array($_SESSION['login_usuario'],$_SESSION['senha_usuario']));
			$count = $stat->rowCount();
			if ($count == 1){
				$rowa = $stat->fetch(PDO::FETCH_ASSOC);
				$nome_usuario = $rowa['nome'];
				$login_usuario = $rowa['login'];
				$obra_usuario = $rowa['obra'];
				$cidade_usuario = $rowa['cidade'];
				$nivel_acesso = $rowa['nivel_acesso'];
				$acesso_usuario = $rowa['acesso_login'];
				$id_usuario_logado = $rowa['id'];
				$editarss_usuario = $rowa['editarss'];
				$tipo_home = $rowa['tipo_home'];
				$tipo_login = $rowa['tipo_login'];
				$ip_usuario = $_SERVER['REMOTE_ADDR'];	
				$atu = $con->prepare("UPDATE usuarios SET ultimo_login = now() WHERE id = ? ");
				$atu->execute(array($rowa['id']));
				$status = true;
			}else{
				session_destroy();
				$status = false;
				echo "<script>window.location='../index.php';</script>";	
			}
		}else{
			$status = false;
			echo "<script>window.location='../index.php';</script>";			
		}	
	}
	
}
	// FUNÇÃO DATA PARA INPUT
	function getData(){
		global $today, $todayTotal, $inicioMes, $mes_nome, $meses_numero, $hora_view, $data_view, $ano_view, $dia_view;
		$today = getdate(); 
		if($today['mon'] < 10) { 
			$today['mon'] = '0'.$today['mon'];
		} else { 
			$today['mon'] = $today['mon'];
		} 
		if($today['mday'] < 10){ 
			$today['mday'] = '0'.$today['mday']; 
		}else{ 
			$today['mday'] = $today['mday']; 
		}  
		$todayTotal = $today['year'].'-'.$today['mon'].'-'.$today['mday'];
		$inicioMes = $today['year'].'-'.$today['mon'].'-01';
		$data_view = date("d/m/Y", mktime(gmdate("d"), gmdate("m"), gmdate("Y")));
		$hora_view = $today['hours'].':'.$today['minutes'].':'.$today['seconds'].'';
		$ano_view = $today['year'];
		$dia_view = $today['mday'];
		
		$meses_numero = array (1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
		
		switch($today['mon']){
			case '01':
				$mes_nome = 'JANEIRO';
			break;
			case '02':
				$mes_nome = 'FEVEREIRO';
			break;
			case '03':
				$mes_nome = 'MARÇO';
			break;
			case '04':
				$mes_nome = 'ABRIL';
			break;
			case '05':
				$mes_nome = 'MAIO';
			break;
			case '06':
				$mes_nome = 'JUNHO';
			break;
			case '07':
				$mes_nome = 'JULHO';
			break;
			case '08':
				$mes_nome = 'AGOSTO';
			break;
			case '09':
				$mes_nome = 'SETEMBRO';
			break;
			case '10':
				$mes_nome = 'OUTUBRO';
			break;
			case '11':
				$mes_nome = 'NOVEMBRO';
			break;
			case '12':
				$mes_nome = 'DEZEMBRO';
			break;
		}
	}
	function getNivel(){
		global $id_usuario_logado, $nivel_acesso, $nivel_acesso_array, $financeiro_array, $logistica_array, $equipamento_array, $compras_array, $consulta_array, $gestor_array;
		$nivel_acesso_array = explode(",", $nivel_acesso);
		foreach ($nivel_acesso_array as $key_acesso) {
			switch ($key_acesso) {
				case 1:
					$financeiro_array = $id_usuario_logado;
					break ;
				case 2:
					$logistica_array = $id_usuario_logado;
					break ;
				case 3:
					$equipamento_array = $id_usuario_logado;
					break ;
				case 5:
					$compras_array = $id_usuario_logado;
					break ;
				case 8:
					$consulta_array = $id_usuario_logado;
					break ;
				case 9:
					$gestor_array = $id_usuario_logado;
					break ;
			 }
		}
	}
?>