<?php
	require('../config.php');
	require('../functions.php');
	$con = new DBConnection();
	verificaLogin();
	getNivel();
	if(isset($ac)){
		if($ac=='atu'){
			$senha_crip = md5($senhaInput);
			try 
			{
				$query = $con->query("UPDATE `usuarios` SET `nome`='$nomeInput', senha = '$senha_crip' WHERE id = '$id_usuario'");
			}
			catch(PDOException $e)
			{
			  echo 'Erro: '.$e->getMessage();
			}
			try{
				session_destroy();
				setcookie ('user', ' ', time() - 3600);
				setcookie ('password', ' ', time() - 3600);
			}catch(PDOException $i){
				echo 'Error: ' . $i->getMessage();
			}
			echo "<script>location.href='index.php'</script>";
		}
		exit;
	}
	$stm = $con->prepare("select * from usuarios where id = ?");
	$stm->execute(array($id_usuario_logado));
	while($x = $stm->fetch()){ 
		if($x['id'] == '') { exit; }
	?>
<div class="trocasenha"></div>
<div class="row">
    <div class="col-md-6" style="float:none; margin: 0 auto;">
		<div class="panel panel-default">
			<div class="panel-heading">Dados do Usuário <span class="pull-right btn btn-xs btn-danger disabled"><?php echo strtoupper($acesso_usuario)?></span></div>
			<div class="panel-body">
				<form action="javascript:void(0)" onSubmit="post(this,'troca-senha.php?ac=atu&id_usuario=<?php echo $x['id'] ?>','.trocasenha')">
					<div class="col-md-12">
						<label style="width:100%">Nome:<br/>
							<input type="text" name="nomeInput" value="<?php echo $x['nome'] ?>" class="form-control input-sm" required />
						</label>
					</div>
					<div class="col-md-12">
						<label style="width:100%">Login:<br/>
							<input type="text" name="login" value="<?php echo $x['login'] ?>" class="form-control input-sm" disabled>
						</label>
					</div>
					<div class="col-md-12">
						<label style="width:100%">Nova Senha:<br/>
							<input type="password" name="senhaInput" class="form-control input-sm" autofocus required/>
						</label>
					</div>
					<div class="col-md-12" style="text-align:center">
						<br/>
						<input type="submit" class="btn btn-success btn-sm" style="width:50%" value="Salvar Informações"/>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
	<?php } ?>