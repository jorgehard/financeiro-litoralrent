<?php
	require_once('../../config.php');
	require_once('../../functions.php');
	$con = new DBConnection();
	verificaLogin(); getData();
?>
<script>
$(document).ready(function(){
	$(".up").keyup(function() {
		$(this).val($(this).val().toUpperCase());
	});
});
</script>
<?php 
if(@$ac=='inserir1') {
	try {
		$query = $con->query("insert into notas_cat_e(descricao, oculto) values ('$descricao','0')");
	}
	catch( PDOException $e ){
		echo '<p class="text-danger">Algo aconteceu de errado!</p>'; 
	}
	echo '<p class="text-success">Informações atualizadas com sucesso!</p>';
	exit;	
} 
if(@$ac == 'inserir2') {
	try {
		$query = $con->query("insert into notas_cat_sub (descricao,associada) values ('$descricao','$associada')");
	}
	catch( PDOException $e ){
		echo '<p class="text-danger">Algo aconteceu de errado!</p>'; 
	}
	echo '<p class="text-success">Informações atualizadas com sucesso!</p>';
	exit;	
} 
?>
<div class="container-fluid">
	<div class="retorno"></div>
	<div class="col-md-6" style="padding:10px">
		<h5><small>CATEGORIA </small></h5>
		<form action="javascript:void(0)" onsubmit='post(this,"gestor/cadastro-categoria-equip.php?ac=inserir1",".retorno")' class="form-horizontal">
			<label style="width:100%">Descrição:
				<input type="text" name="descricao" class="form-control input-sm up" size="100" required/>
			</label>
			<label style="width:100%; text-align:center"><br/>
				<input type="submit" value="Cadastrar"  style="width:50%" class="btn btn-success btn-sm"/>
			</label>
		</form>
	</div>
	<div class="col-md-6" style="border-left:1px solid #E5E5E5; padding:10px;">
		<h5><small>SUB-CATEGORIA</small></h5>
		<form action="javascript:void(0)" onsubmit='post(this,"gestor/cadastro-categoria-equip.php?ac=inserir2",".retorno")' class="form-horizontal">
		<label style="width:100%">Descrição:
			<input type="text" name="descricao" class="form-control input-sm up" required/>
		</label>
		<label style="width:100%">Associada:	
			<select name="associada" style="width:100%" class="form-control input-sm">
				<?php 
					$categorias = $con->query("select * from notas_cat_e WHERE oculto = '0' order by descricao asc");
					while($l = $categorias->fetch()){
						echo '<option value="'.$l['id'].'">'.$l['descricao'].'</option>'; 
					}
				?>		
			</select>
		</label>					
		<label style="width:100%; text-align:center"><br/>
			<input type="submit" value="Cadastrar"  style="width:50%" class="btn btn-success btn-sm"/>
		</label>
		</form>
	</div>
</div>