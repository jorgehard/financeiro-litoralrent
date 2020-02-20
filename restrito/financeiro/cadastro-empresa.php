<?php
	require_once('../../config.php');
	require_once('../../functions.php');
	$con = new DBConnection();
	verificaLogin(); getData();
	if(@$up=='busca-rgi') {
		$stm = $con->prepare("SELECT * FROM litoralrent_cadastroempresa WHERE cnpj = ?");
		$stm->execute(array($busca));
		while($b = $stm->fetch()) {
			echo '<script>alert("Empresa ja se encontra cadastrada em nosso sistema!!!")</script>'; 
			echo '<script>$("#razao_social").val("'.$b['razao_social'].'")</script>'; 
			echo '<script>$("#telefone").val("'.$b['telefone'].'")</script>'; 
			echo '<script>$("#celular").val("'.$b['celular'].'")</script>'; 
			echo '<script>$("#contato").val("'.$b['contato'].'")</script>'; 
			echo '<script>$("#email").val("'.$b['email'].'")</script>'; 
			echo '<script>$("#endereco").val("'.$b['endereco'].'")</script>'; 
			echo '<script>$("#seguimento").val("'.$b['seguimento'].'")</script>'; 
			echo '<script>$("#data_retorno").val("'.$b['data_retorno'].'")</script>'; 
			echo '<script>$("#visita").val("'.$b['visita'].'")</script>'; 
		}		
		exit;
	}
	if(isset($ac)){
		if($data_retorno == ''){ $data_retorno = '0001-01-01'; }
		$stm = $con->prepare("SELECT COUNT(*) FROM litoralrent_cadastroempresa WHERE cnpj = ?");
		$stm->execute(array($cnpj));
		$count = $stm->fetchColumn();
		if($count != 0){
			echo '
			<div class="alert alert-danger">
				<h4>Empresa já cadastrada!</h4>
				<p>O CPF/CNPJ: '.$cnpj.' ja está cadastrada no sistema, favor consultar as empresas ja cadastradas e tentar novamente!!!</p>
			</div>';
		}else{
			$query = $con->prepare("INSERT INTO litoralrent_cadastroempresa (tipo_empresa, cnpj, razao_social, telefone, celular, contato, email, endereco, seguimento, data_retorno, obs, visita, data_cadastro) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$query->execute(array($tipo_empresa, $cnpj, $razao_social, $telefone, $celular, $contato, $email, $endereco, $seguimento, $data_retorno, $obs, $visita, $todayTotal));
			if($query){
				echo '
					<div class="alert alert-success">
						<h4>Empresa Cadastrada!</h4>

						<p>O CPF/CNPJ: '.$cnpj.' de nome '.$razao_social.' foi cadastrada com sucesso no sistema!!!</p>
					</div>';
			}
		}
		echo "<script> $('html, body').animate({ scrollTop: $('#alert1').offset().top }, 'slow'); </script>";
		exit;
	}
	
	?>
<section class="content-header" id="alert1">
	<h1>Cadastro Empresa <small> </small></h1>
</section>
<section class="content">
	<div class="resultadoCadastro"></div>
	<div class="box box-primary" style="padding-top:10px">
		<form action="javascript:void(0)" onSubmit="post(this,'financeiro/cadastro-empresa.php?ac=ins','.resultadoCadastro')">
			<div class="box-body">
				<div class="form-group">
					<label>Tipo Empresa:</label>
					<select name="tipo_empresa" onChange="$('#itens_empresa').load('../functions/functions-load.php?atu=cadastroEmpresa&tipo_empresa=' + $(this).val() + '');" class="form-control input-sm" required>
						<option value="0">CLIENTE</option>
						<option value="1">FORNECEDOR</option>
						<option value="2">CLIENTE / FORNECEDOR</option>
						<option value="3">PESSOA FISICA</option>
					</select>
				</div>
				<div id="itens_empresa">
					<div class="form-group">
						<label>CNPJ:</label>
						<input type="text" name="cnpj" onblur="$('#autoco').load('financeiro/cadastro-empresa.php?up=busca-rgi&busca=' + $(this).val() + '');" onfocus="$(this).mask('99.999.999/9999-99')" placeholder="__.___.___/____-__" class="juridica form-control input-sm"  required />
						<div id="autoco"></div>
					</div>
					<div class="form-group">
						<label>Razão Social:</label>
						<input type="text" name="razao_social" id="razao_social" placeholder="Nome da Empresa" size="80" class="todosInput form-control input-sm" required />
					</div>
				</div>
				<div class="form-group">
					<label>Telefone:</label>
					<input type="text" name="telefone" id="telefone" onfocus="$(this).mask('(99) 9999-9999')" placeholder="(__) ____-____" size="80" class="todosInput form-control input-sm" >
				</div>
				<div class="form-group">
					<label>Celular:</label>
					<input type="text" name="celular" id="celular" onfocus="$(this).mask('(99) 99999999?9')" placeholder="(__) _________" size="80" class="todosInput form-control input-sm" >
				</div>
				<div class="form-group">
					<label>Representante:</label>
					<input type="text" name="contato" id="contato" placeholder="Responsável Legal" size="80" class="todosInput form-control input-sm" >
				</div>
				<div class="form-group">
					<label>E-mail</label>
					<input type="email" name="email" id="email" placeholder="E-mail para contato" size="80" class="todosInput form-control input-sm" >
				</div>
				<div class="form-group">
					<label>Endereço:</label>
					<input type="text" name="endereco" id="endereco" placeholder="Endereço completo" size="80" class="todosInput form-control input-sm" required />
				</div>
				<div class="form-group">
					<label>Seguimento:</label>
					<input type="text" name="seguimento" id="seguimento" placeholder="Seguimento" size="80" class="todosInput form-control input-sm" >
				</div>
				<div class="form-group">
					<label>Data Retorno:</label>
					<input type="date" name="data_retorno" id="data_retorno" class="todosInput form-control input-sm" />
				</div>
				<div class="form-group">
					<label>Visita:</label>
					<input type="text" name="visita" value="<?= $todayTotal ?>" id="visita" class="todosInput form-control input-sm" >
				</div>
				<div class="form-group">
					<label>Observações:</label>
					<textarea name="obs" class="todosInput form-control input-sm"></textarea>
				</div>
				<div class="box-footer" style="text-align:center">
					<input type="submit" style="width:50%" class="btn btn-success btn-sm submit-empresa" value="Salvar">
				</div>
			</div>
		</form>
	</div>
</section>