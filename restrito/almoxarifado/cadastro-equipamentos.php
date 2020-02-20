<?php
	require_once('../../config.php');
	require_once('../../functions.php');
	$con = new DBConnection();
	verificaLogin(); getData();
$con->query("UPDATE notas_equipamentos SET obra = '1' WHERE obra = '0'");

if(@$ac == 'up') {
	try{
		$consulta = $con->query("SELECT COUNT(*) as total FROM notas_equipamentos WHERE patrimonio = '$patrimonio'");
		$cb = $consulta->fetch();
		if($cb['total'] == 0){
			$query = $con->query("INSERT INTO notas_equipamentos (placa, patrimonio, marca, patrimonio2, valor, justificativa, desconto, chassi, ano, empresa, categoria, sub_categoria, situacao, entrada, saida, user_edit, data_edit) VALUES ('$placa', '$patrimonio', '$marca', '$patrimonio2', '$valor', '$seguro', '$desconto', '$chassi', '$ano', '$empresa', '$categoria', '$sub_categoria', '$situacao', '$entrada', '$saida', '$id_usuario_logado', now())");
		}else{
			echo '<div class="alert alert-danger" role="alert">Este BP já existe no sistema, tente um diferente!</div>';
			exit;
		}
	}
	catch( PDOException $e ){
		echo '<div class="alert alert-danger" role="alert">Algo aconteceu de errado!</div>'; 
	}
	echo '<div class="alert alert-success" role="alert">Informações cadastrada com sucesso!</div>';
	exit;
} 
?>
<link rel="stylesheet" href="../style/css/combobox.css"/>
<script>
$(document).ready(function () {
	$("#combobox").combobox();
	$('.sel').multiselect({
		buttonClass: 'btn btn-sm', 
		numberDisplayed: 1,
		maxHeight: 500,
		includeSelectAllOption: true,
		selectAllText: "Selecionar todos",
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		selectAllValue: 'multiselect-all',
		buttonWidth: '100%'
	}); 
});
</script>
<?php
if(@$atu=='ac'){
	echo '<label style="width:100%">Contrato:<br/>
			<select name="obraInput" class="form-control input-sm" style="width:100%" required>';
				$obras = $con->query("SELECT * FROM notas_obras WHERE cidade IN($obra_2) AND id <> 0 ORDER BY descricao ASC");
				while($a = $obras->fetch()) {
					echo '<option value="'.$a['id'].'" selected>'.$a['descricao'].'</option>';
				}
	echo '</select></label>';
	exit;
}
if(@$atu == 'categoria'){
	echo '<label style="width:100%"><small>Sub-Categoria</small><select name="sub_categoria" style="width:100%" class="form-control input-sm">';
	$stms = $con->query("select * from notas_cat_sub where associada in($categoria) order by descricao asc");
	while($l = $stms->fetch()){
		echo '<option value="'.$l['id'].'">'.$l['descricao'].'</option>';
	}
	echo '</select></label>';
}
?>

	<div class="ajax"></div>
	
	<div class="container-fluid" style="padding:0px">
		<form action="javascript:void(0)" onSubmit="post(this,'almoxarifado/cadastro-equipamentos.php?ac=up','.ajax')" enctype="multipart/form-data" class="formulario-info">
			<div class="panel">
				<div class="panel-body" style="width:100%">	
					<div class="col-xs-12 col-sm-4">
						<div class="col-xs-6">
							<label style="width:100%">Nº Motor: <br/> <input type="text" name="placa" class="form-control input-sm"></label>
						</div>
						<div class="col-xs-6">
							<label style="width:100%">BP: <br/><input type="text" name="patrimonio" autocomplete="off" class="form-control input-sm up placa" onfocus="$(this).mask('aaa-*999')" required /></label>
						</div>
	
						<div class="col-xs-6">
							<label style="width:100%">Marca: </br><input type="text" name="marca" class="form-control input-sm up" required></label>
						</div>
						<div class="col-xs-6">
							<label style="width:100%">Nº Apolise: <br><input type="text" name="patrimonio2" class="form-control input-sm"></label>
						</div>
						<div class="col-xs-6">
							<label style="width:100%">Valor: <br><input type="number" step="0.1" name="valor" class="form-control input-sm"  required></label>
						</div>
						<div class="col-xs-6">
							<label style="width:100%">Seguro: <br>
								<select name="seguro" class="form-control input-sm" required>
									<option value="" disabled>Selecione uma opção</option>
									<option value="SIM">SIM</option>
									<option value="NAO">NÃO</option>
								</select>
							</label>
						</div>
						<div class="col-xs-6">
							<label style="width:100%">Nota: <br><input type="number" name="desconto" step="0.01" class="form-control input-sm"  required /></label>
						</div>
						<div class="col-xs-6">
							<label style="width:100%">Ano: <br><input type="text" name="ano" class="form-control input-sm"  required></label>
						</div>
						<div class="col-xs-6">
							<label style="width:100%">Chassi / Nº série: <br><input style="width:100%;"type="text" name="chassi" class="form-control input-sm up"  required></label>
						</div>
					</div>
			
					<div class="col-xs-12 col-sm-4">
						<div class="col-xs-12">
							<label style="width:100%">Empresa: <br>
								<select id="combobox" name="empresa" class="form-control input-sm" required>
									<option value="">SEM EMPRESA</option>
									<?php 
										$empresasql = $con->query("select * from litoralrent_cadastroempresa WHERE tipo_empresa IN(1,2) order by razao_social asc");
										while($l = $empresasql->fetch()) {
											echo '<option value="'.$l['id'].'">'.$l['razao_social'].'</option>';
										}
									?>			
								</select>
							</label>
						</div>
						<div class="col-xs-12">
							<label for "" style="width:100%"> Categoria: 
								<select name="categoria" onChange="$('#itens23').load('../functions/functions-load.php?atu=categoria&control=1&categoria=' + $(this).val() + '');" style="width:100%" class="form-control input-sm" required>
									<option value="0">SELECIONE UMA CATEGORIA</option>
									<?php 
										$stms = $con->query("select * from notas_cat_e where oculto = '0' order by descricao asc");
										while($l = $stms->fetch()){
											echo '<option value="'.$l['id'].'">'.$l['descricao'].'</option>'; 
										}
									?>		
								</select>
							</label>
						</div>
						<div class="col-xs-12">
							<label id="itens23" style="width:100%">Sub-Categoria:
								<label style="width:100%">
									<select name="sub_categoria" style="width:100%" class="form-control input-sm" required>
										<option value="" selected disabled>Selecione uma categoria</option>
									</select>
								</label>
							</label>
						</div>
						<div class="col-xs-12">
							<label style="width:100%">Fornecedor: <br><input type="text" name="obs" class="form-control input-sm" disabled /></label>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4">
						<div class="col-xs-12">
							<label style="width:100%">Obra:<br/>
								<select name="cidade" onChange="$('#itens-obra').load('almoxarifado/cadastro-equipamentos.php?atu=ac&obra_2=' + $(this).val() + '');" class="input-sm form-control" style="width:100%"  required>
									<?php 
										$obras_consulta = $con->query("select * from notas_obras_cidade WHERE id IN($cidade_usuario) AND id <> 0 order by nome asc");
										while($l = $obras_consulta->fetch()) {
											echo '<option value="'.$l['id'].'">'.$l['nome'].'</option>'; 
										}
									?>	
								</select>
							</label>
						</div>
						<div class="col-xs-12">
							<label style="width:100%" id="itens-obra">
								<label style="width:100%">Contrato:<br/>
									<select name="obraInput" class="form-control input-sm" style="width:100%" required>
										<?php 
											$obras_consulta = $con->query("select * from notas_obras WHERE id IN($obra_usuario) AND cidade IN($cidade_usuario) AND id <> 0 order by descricao asc");
											while($l = $obras_consulta->fetch()) {
												echo '<option value="'.$l['id'].'">'.$l['descricao'].'</option>'; 
											}
										?>	
									</select>
								</label>
							</label>
						</div>
						<div class="col-xs-12">
							<label style="width:100%">Tipo: <br>
								<select name="situacao" class="form-control input-sm" required>
									<option value="" selected disabled>00 - SEM TIPO </option>
									<?php 
										$situacaosql = $con->query("select * from notas_eq_situacao where status = '0' AND id <> '0' order by descricao asc");
										while($l = $situacaosql->fetch()) {
											echo '<option value="'.$l['id'].'">'.$l['descricao'].'</option>'; 
										}
									?>			
								</select>
							</label>
						</div>
						<div class="col-xs-6">
							<label>Entrada: <br><input type="date" name="entrada" value="<?php echo $todayTotal ?>" class="form-control input-sm" ></label>
						</div>
						<div class="col-xs-6">
							<label>Saída: <br><input type="date" name="saida" class="form-control input-sm"   ></label><br/>
						</div>
						<div class="col-xs-12" style="text-align:center">
							<label style="width:50%">
								<input type="submit" style="width:100%; height:30px; margin-top:20px" value="Atualizar" class="btn btn-info btn-sm">
							</label>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>