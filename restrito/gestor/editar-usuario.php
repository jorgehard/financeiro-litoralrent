<?php
	require_once('../../config.php');
	require_once('../../functions.php');
	$con = new DBConnection();
	verificaLogin(); getData();
?>
<script>
$(function () {
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
<style>
.nivel_acesso_class input[type="checkbox"] {
    display:none;
}
.nivel_acesso_class input[type="checkbox"] + label {
    color:#333;
	margin:10px;
}
.nivel_acesso_class input[type="checkbox"] + label span {
    display:inline-block;
    width:29px;
    height:19px;
    margin:-2px 10px 0 0;
    vertical-align:middle;
    background:#f3f3f3;
	border:1px solid #ccc;
    cursor:pointer;
}

.nivel_acesso_class input[type="checkbox"]:checked + label span {
    background:#5CB85C;
	border:1px solid #ccc;
}
</style>
<?php
if(@$atu=='ac'){
	echo '<label style="width:100%">CONTRATO:<br/>
			<select name="ob[]" class="sel" style="width:100%" multiple="multiple" required>';
				$obras = $con->query("SELECT * FROM notas_obras WHERE cidade IN($obra_2) AND id <> 0 ORDER BY descricao ASC");
				while($a = $obras->fetch()) {
					echo '<option value="'.$a['id'].'" selected>'.$a['descricao'].'</option>';
				}
	echo '</select></label>';
	exit;
}
if(@$ac == 'update') {
	foreach($ob as $obs) { @$obu .= $obs.','; } $obu = substr($obu,0,-1);
	foreach($ci as $cis) { @$ciu .= $cis.','; } $ciu = substr($ciu,0,-1);
	
	foreach($nivel_acesso2 as $niv2) { @$nivel_acesso3 .= $niv2.','; } $nivel_acesso3 = substr($nivel_acesso3,0,-1);

	$query = $con->query("UPDATE `usuarios` SET `nome`='$nome', `rg`='$rgInput', `cargo`='$cargo', `telefone`='$telefone', `login`='$loginInput', `status`='$status22', `obra` = '$obu', `cidade` = '$ciu', `editarss` = '$editarss', `nivel_acesso` = '$nivel_acesso3', `acesso_login` = '$acesso_usuarioInput' WHERE id = '$id'");
	if($query) {
		echo '<div class="alert alert-success" role="alert">Informações atualizadas com sucesso!</div>';
	}else{ 
		echo '<div class="alert alert-danger" role="alert">'.mysql_error().'</div>';
	}
	exit;	
} 

$stm = $con->query("select * from usuarios where id = '$id'"); 
while($b = $stm->fetch()) { extract($b); } 
?>

<div class="ajax" style="width:100%; text-align:center;"></div>
<div class="panel panel-default" style="border:none">
<div class="panel-body">
<form action="javascript:void(0)" onSubmit="post(this,'gestor/editar-usuario.php?ac=update&id=<?php echo $id ?>','.ajax');" class="small">

		<div class="col-md-12">
		
			<div class="col-xs-6">
				<label style="width:100%">Login:<input type="text" name="loginInput" value="<?php echo $login ?>" class="form-control input-sm" size="10"></label>
			</div>
			<div class="col-xs-6">
				<label style="width:100%">Status:
					<select name="status22" class="form-control input-sm">
						<?php if($status == '0') { ?>
						<option value="0" selected>ATIVO</option>
						<option value="1">INATIVO</option>
						<?php }else{ ?>
						<option value="0">ATIVO</option>
						<option value="1" selected>INATIVO</option>
						<?php } ?>
					</select>
				</label>
			</div>
			<div class="col-xs-12">
				<label style="width:100%">Nome:<input type="text" name="nome" value="<?= $nome ?>" class="form-control input-sm up" required /></label><br>
			</div>
			<div class="col-xs-4">
				<label style="width:100%">R.G.:<input type="text" name="rgInput" value="<?= $rg ?>" class="form-control input-sm up" required /></label><br>
			</div>
			<div class="col-xs-4">
				<label style="width:100%">Cargo:<input type="text" name="cargo" value="<?= $cargo ?>" class="form-control input-sm up" required /></label><br>
			</div>
			<div class="col-xs-4">
				<label style="width:100%">Telefone:<input type="text" name="telefone" value="<?= $telefone ?>" class="form-control input-sm up" required /></label><br>
			</div>
			<div class="col-xs-6">
				<label style="width:100%">Obra:<br/>
					<select name="ci[]" onChange="$('#itens').load('gestor/editar-usuario.php?atu=ac&obra_2=' + $(this).val() + '');" class="sel" style="width:100%" multiple="multiple" required>
						<?php 
							$obras_consulta = $con->query("select * from notas_obras_cidade WHERE id IN($cidade) AND id <> 0 order by nome asc");
							while($l = $obras_consulta->fetch()) {
								echo '<option value="'.$l['id'].'" selected>'.$l['nome'].'</option>'; 
							}
							
							$obras_consulta = $con->query("select * from notas_obras_cidade WHERE id NOT IN($cidade) AND id <> 0 order by nome asc");
							while($l = $obras_consulta->fetch()) {
								echo '<option value="'.$l['id'].'">'.$l['nome'].'</option>';  
							}
						?>	
					</select>
				</label>
			</div>
			<div class="col-xs-6">
				<label style="width:100%" id="itens">
					<label style="width:100%">Contrato:<br/>
						<select name="ob[]" class="sel" style="width:100%" multiple="multiple" required>
							<?php 
								$obras_consulta = $con->query("select * from notas_obras WHERE id IN($obra) AND cidade IN($cidade) AND id <> 0 order by descricao asc");
								while($l = $obras_consulta->fetch()) {
									echo '<option value="'.$l['id'].'" selected>'.$l['descricao'].'</option>'; 
								}

								$obras_consulta = $con->query("select * from notas_obras WHERE id NOT IN($obra) and cidade IN($cidade) AND id <> 0 order by descricao asc");
								while($l = $obras_consulta->fetch()) {
									echo '<option value="'.$l['id'].'">'.$l['descricao'].'</option>';
								}
							?>	
						</select>
					</label>
				</label>
			</div>
			
			<div class="col-xs-6">
				<label style="width:100%">Editar Informações:
					<select name="editarss" class="form-control input-sm">
						<?php if($editarss == 0) { ?>
						<option value="0" selected>NÃO</option>
						<option value="1">SIM</option>
						<?php }else{ ?>
						<option value="0">NÃO</option>
						<option value="1" selected>SIM</option>
						<?php } ?>
					</select>
				</label>
			</div>
			
			<div class="col-xs-6">
				<label style="width:100%">Tipo:
					<select name="acesso_usuarioInput" class="form-control input-sm" required>
						<option value="">Selecione um tipo</option>
						<?php
						$acesso = $con->query("select * from acesso_usuario WHERE tipo = '1' order by controle asc");
						while($l = $acesso->fetch()) {
							if($acesso_login == $l['descricao']){
								echo '<option value="'.$l['descricao'].'" selected>'.$l['descricao'].'</option>';
							}else{
								echo '<option value="'.$l['descricao'].'">'.$l['descricao'].'</option>';
							}
						}
						?>
					</select>
				</label>
			</div>
			<div class="col-xs-12 nivel_acesso_class">
				<?php								
				echo '<br/>Selecionar: <br/>';
				$acesso_usuario = $con->query("select * from acesso_usuario WHERE tipo = '0' and controle NOT IN($nivel_acesso) order by controle asc");
				while($l = $acesso_usuario->fetch()) {
					echo '	<input type="checkbox" id="nivel'.$l['controle'].'" name="nivel_acesso2[]" value="'.$l['controle'].'" />
					<label for="nivel'.$l['controle'].'"><span></span>'.$l['descricao'].'</label>';
				}		
				echo '<br/>Selecionados: <br/>';
				$acesso_usuario = $con->query("select * from acesso_usuario WHERE tipo = '0' and controle IN($nivel_acesso) order by controle asc");
				while($l = $acesso_usuario->fetch()) {
					echo '	<input type="checkbox" id="nivel'.$l['controle'].'" name="nivel_acesso2[]" value="'.$l['controle'].'" checked />
					<label for="nivel'.$l['controle'].'"><span></span>'.$l['descricao'].'</label>';
				}
				?>
				
			</div>
			<div class="col-xs-12" style="text-align:center; margin-top:30px;">
				<input type="submit" class="btn btn-success btn-sm"  style="width:50%" value="Salvar"/>
			</div>
		</div>
	</form>
	</div>
	</div>
</table>
