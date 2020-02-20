<?php
	require_once('../../config.php');
	require_once('../../functions.php');
	$con = new DBConnection();
	verificaLogin(); getData();
?>
<script>
$(document).ready(function() {
});
</script>
	<div style="clear: both; margin-bottom:5px;">
		<h3 style="font-family: 'Oswald', sans-serif;letter-spacing:5px;"> 
			<p style="position:relative; top:10px; left:10px;">CONSULTA <small> CATEGORIAS & SUB-CATEGORIAS</small>
			<div class="buttons-top-page pull-right">
				<a href="#" style="padding:3px 15px;" title="Cadastrar Novo" class="btn btn-success btn-sm" onclick='$(".modal-body").load("gestor/cadastro-categoria-equip.php")' data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle" aria-hidden="true"></i> Cadastrar</a>				
				<a href="#" style="padding:3px 15px; margin:0px 10px;" title="Atualizar Pagina" class="btn btn-warning btn-sm" onclick="ldy('gestor/consulta-categoria-equip.php','.conteudo')"><i class="fa fa-refresh" aria-hidden="true"></i> Atualizar</a>
			</div>
			</p>
		</h3>
	</div>
	<div style="clear: both;">
		<hr></hr>
	</div>
	<div class="col-md-12">
		<h4><small>LISTA DE CATEGORIA</small></h4>
		<table class="table table-striped table-condensed small">
			<thead>
			<tr>
				<th colspan="2"> <span class="glyphicon glyphicon-eject" aria-hidden="true"></span> </th>
				<th>Nome</th>
				<th><center> <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> </center></th>
				<th><center> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> </center></th>
				<th><center> <span class="glyphicon glyphicon-flag" aria-hidden="true"></span> </center></th>
				
			</tr>
			</thead>
		<tbody>
        <?php
		$se = 0;
		$stm = $con->query("select * FROM notas_cat_e WHERE id <> '0'");
		while($b = $stm->fetch()){ extract($b);
			$se += 1;
			echo '<tr class="info" id="cupom'.$id.'">';
				echo '<td width="3%">'.$se.'</td>';
				echo '<td colspan="2">'.$descricao.'</td>';
				echo '<td width="10%"><center>';
				if($oculto == '0'){
					echo '<span class="btn btn-xs small btn-success" style="font-size:8px">ATIVO</span>';
				}else{
					echo '<span class="btn btn-xs small btn-danger" style="font-size:8px">INATIVO</span>';
				}
				echo '</center>
				</td>';
				echo '<td width="40px" style="text-align:center"><a href="#" Onclick=\'$(".modal-body").load("gestor/editar-categoria-eqp.php?id='.$id.'")\' data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-xs" style="margin:0px; font-weight:bold; font-size:12px"><i class="fas fa-edit"></i></a></td>';
								
				echo '<td width="40px" style="text-align:center"><a href="#" onclick=\'$(".modal-body").load("gestor/del/ex-cat-e.php?&id='.$id.'")\' data-toggle="modal" data-target="#myModal2"  class="btn btn-danger btn-xs" style="margin:0px; font-weight:bold; font-size:12px"><span class="glyphicon glyphicon-trash"></span></a></td>';
			echo '</tr>';
			$stm2 = $con->query("select * FROM notas_cat_sub WHERE associada = '$id' order by descricao asc");
			$se2 = 0;
			while($c = $stm2->fetch()){
				$se2 += 1;
				echo '<tr class="small" id="cupom'.$c['id'].'">';
				echo '<td></td>';
				echo '<td width="3%">'.$se2.'</td>';
				echo '<td>'.$c['descricao'].'</td>';
				echo '<td width="10%"><center>';
				if($c['oculto'] == '0'){
					echo '<span class="btn btn-xs small btn-success" style="font-size:8px">ATIVO</span>';
				}else{
					echo '<span class="btn btn-xs small btn-danger" style="font-size:8px">INATIVO</span>';
				}
				echo '</center></td>';
				echo '<td width="40px" style="text-align:center"><a href="#" Onclick=\'$(".modal-body").load("gestor/editar-sub-categoria-eqp.php?id='.$c['id'].'")\' data-toggle="modal" data-target="#myModal" class="btn btn-info btn-xs" style="margin:0px; font-weight:bold; font-size:10px;"><span class="glyphicon glyphicon-pencil"></span></a></td>';
								
				echo '<td width="40px" style="text-align:center"><a href="#" onclick=\'$(".modal-body").load("gestor/del/ex-cat-sub.php?&id='.$c['id'].'")\' data-toggle="modal" data-target="#myModal2"  class="btn btn-danger btn-xs" style="margin:0px; font-weight:bold; font-size:10px; background:#ff8484"><i class="fas fa-trash"></i></a></td>';
				echo '</tr>'; 
			}

			
		}

        ?>
		</tbody>
		</table>
	</div>

	<div class="retorno"></div>
	
	<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:auto;">
		<div class="modal-dialog">
			<div class="modal-content"> 
				<div class="modal-header box box-info" style="margin:0px;">
					<button type="button" class="close" onclick="$('.modal').modal('hide'); $('.modal-body').empty()" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Painel Equipe</h4>
				</div>
				<div class="modal-body">
					Aguarde um momento &nbsp;&nbsp; <img src="../style/img/loading.gif" alt="Carregando" width="20px"/>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width:auto;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" onclick="$('.modal').modal('hide')" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Excluir Usuario</h4>
				</div>
				<div class="modal-body">
					Aguarde um momento &nbsp;&nbsp; <img src="../../imagens/loading.gif" alt="Carregando" width="20px"/>
				</div>
			</div>
		</div>
	</div>