<?php
	require_once('../../config.php');
	require_once('../../functions.php');
	$con = new DBConnection();
	verificaLogin(); getData();
?>
<style>
@media print {

}
</style>
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
    $('#resultadoConsulta').DataTable({
		"paging": false,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": false,
		"bAutoWidth": false,
		"columnDefs": [
		    { "orderable": false, "targets": [ -1 ] }
		]
    });
	});
</script>
	<?php
	if(isset($ac)){
		if(@$relatorio == '1'){
			if(empty($cat) || empty($sub) || empty($to) || empty($si) || empty($ct)){ 
				echo '<span class="text-danger">Selecione todos os campos obrigatorios</span>'; 
				exit; 
			}else{
				$catg = ''; $subg = ''; $sta = ''; $sit = ''; $ctt = '';
				foreach($cat as $cats){ $catg .= $cats.','; } $catg = substr($catg,0,-1); 
				foreach($sub as $subs)  { $subg .= $subs.',';  } $subg = substr($subg,0,-1);
				foreach($to as $tos)  { $sta .= $tos.',';   } $sta = substr($sta,0,-1);
				foreach($si as $sis)  { $sit .= $sis.',';   } $sit = substr($sit,0,-1); 
				foreach($ct as $cts)  { $ctt .= $cts.',';   } $ctt = substr($ctt,0,-1); 
			}
			echo '<div class="container-fluid hidden-xs visible-print" style="border-bottom:1px solid #CCC; padding-bottom:20px; margin:15px;">
				<div class="col-xs-2" style="padding:0px">
					<img src="../style/img/litoralrent-logo.png" class="img-responsive" width="100px" />
				</div>
				<div class="col-xs-10" style="text-align:right; font-size:8px">
					<b><small>LITORAL RENT LOCADORA E CONSTRUÇÕES LTDA.</small></b><br/>
					Av Antônio Emmerick, 723, Jardim Guassu, São Vicente/SP - CEP 11370-001<br/>
					Telefone: (13) 3043-4211 &nbsp;&nbsp;&nbsp; Email: contato@litoralrent.com.br
					<br/>
				</div>
			</div>
			';
			echo '<center>
				<h5 class="hidden-xs visible-print" style="font-family: \'Oswald\', sans-serif; letter-spacing:4px; text-align:center; margin-bottom:20px;">
					<p><small>RELATORIO DE PATRIMONIO</small> <br/> <small>NOTA: '.$busca.'</small></p>
				</h5>
				</center>';
			echo '<div class="box box-widget">
					<table id="resultadoConsulta" class="box box-widget table table-striped table-min small" style="font-size:10px">
					<thead>
						<tr>
							<th style="text-align:center"><i class="fa fa-list-alt" aria-hidden="true"></i></th>
							<th style="text-align:center">BP:</th>
							<th style="text-align:center">Fornecedor:</th>
							<th style="text-align:center">Motor:</th>
							<th style="text-align:center">Chassi:</th>
							<th style="text-align:center">Nota:</th>
							<th style="text-align:center">Sub-Categoria:</th>
							<th style="text-align:center">Valor:</th>
							<th style="text-align:center">Status:</th>';
							if($acesso_usuario == 'MASTER'){
								echo '<th class="hidden-print" style="text-align:center">Editar:</th>';
							}
							echo '
						</tr>
					</thead> 
				<tbody>';
				
				$stm = $con->query("SELECT * FROM notas_equipamentos WHERE desconto LIKE '%$busca%' AND categoria IN($catg) AND sub_categoria IN($subg) AND status IN($sta) AND situacao IN($sit) AND controle IN($ctt)");
				$c = 0; $total_equipamentos = 0;
				while($b = $stm->fetch()){
					$c += 1;
					echo '<tr id="thisTr'.$b['id'].'">';
					echo '<td style="text-align:center">'.$c.'</td>';
					echo '<td>'.$b['patrimonio'].'</td>';
					echo '<td>'.$b['obs'].'</td>';
					echo '<td>'.$b['placa'].'</td>';
					echo '<td>'.$b['chassi'].'</td>';
					echo '<td>'.$b['desconto'].'</td>';
					echo '<td>'.$con->query("SELECT descricao FROM notas_cat_sub WHERE id = '".$b['sub_categoria']."' ")->fetchColumn().'</td>';
					echo '<td data-sort="'.$b['valor'].'">R$&nbsp;'.number_format($b['valor'],2,",",".").'</td>';
					
					echo '<td style="text-align:center">';
						if($b['status'] == '0'){
							echo '<span class="label label-success">ATIVO</span>'; 
						}else if($b['status'] == '1'){ 
							echo '<span class="label label-danger">INATIVO</span>'; 
						}else if($b['status'] == '2') {
							echo '<span class="label label-warning">ROUBADO</span>'; 
						}
					echo '</td>';
					//echo '<td>'.implode("/",array_reverse(explode("-",$b['data_retorno']))).'</td>';
					if($acesso_usuario == 'MASTER'){
						echo '<td class="hidden-print" style="text-align:center" width="5px"><a href="#" Onclick=\'$(".modal-body").load("almoxarifado/editar-equipamento-master.php?id='.$b['id'].'")\' data-toggle="modal" data-target="#myModal" class="btn btn-success btn-xs hidden-print" style="margin:0px; font-weight:bold;"><span class="glyphicon glyphicon-pencil"></span></a></td>';
					}
					
					echo '</tr>';
				}
				echo '</tbody> </table> </div>';
				
			exit;
		}
		if(@$relatorio == '2'){
			if(empty($cat) || empty($sub) || empty($to) || empty($si)){ 
				echo '<span class="text-danger">Selecione todos os campos obrigatorios</span>'; 
				exit; 
			}else{
				$catg = ''; $subg = ''; $sta = ''; $sit = '';
				foreach($cat as $cats){ $catg .= $cats.','; } $catg = substr($catg,0,-1); 
				foreach($sub as $subs)  { $subg .= $subs.',';  } $subg = substr($subg,0,-1);
				foreach($to as $tos)  { $sta .= $tos.',';   } $sta = substr($sta,0,-1);
				foreach($si as $sis)  { $sit .= $sis.',';   } $sit = substr($sit,0,-1); 
			}
			echo '<div class="container-fluid hidden-xs visible-print" style="border-bottom:1px solid #CCC; padding-bottom:20px; margin:15px;">
				<div class="col-xs-2" style="padding:0px">
					<img src="../style/img/litoralrent-logo.png" class="img-responsive" width="100px" />
				</div>
				<div class="col-xs-10" style="text-align:right; font-size:8px">
					<b><small>LITORAL RENT LOCADORA E CONSTRUÇÕES LTDA.</small></b><br/>
					Av Antônio Emmerick, 723, Jardim Guassu, São Vicente/SP - CEP 11370-001<br/>
					Telefone: (13) 3043-4211 &nbsp;&nbsp;&nbsp; Email: contato@litoralrent.com.br
					<br/>
				</div>
			</div>
			';
			echo '<center>
				<h5 class="hidden-xs visible-print" style="font-family: \'Oswald\', sans-serif; letter-spacing:4px; text-align:center; margin-bottom:20px;">
					<p><small>RELATORIO DE PATRIMONIO</small> <br/> <small>NOTA: '.$busca.'</small></p>
				</h5>
				</center>';
				$stm = $con->query("SELECT * FROM notas_equipamentos WHERE desconto LIKE '%$busca%' AND categoria IN($catg) AND sub_categoria IN($subg) AND status IN($sta) AND situacao IN($sit)");
				$c = 0;
				while($b = $stm->fetch()){ extract($b);
					$c += 1;
					echo '<div class="box box-widget">
					<table class="box box-widget table table-striped table-condensed" style="font-size:12px; border:1px solid #ccc;">';
				echo '
				<thead>
					<tr>
						<th style="text-align:center">'.$con->query("select razao_social from litoralrent_cadastroempresa where id = '$empresa'")->fetchColumn().'</th>
						
						<th style="text-align:center"><small>Categoria: &nbsp;</small> '.$con->query("SELECT descricao FROM notas_cat_e WHERE id = '$categoria'")->fetchColumn().'</th>
						
						<th style="text-align:center"><small>Sub-Categoria: &nbsp; </small>'.$con->query("SELECT descricao FROM notas_cat_sub WHERE id = '$sub_categoria'")->fetchColumn().'
						</th>
						
					</tr>
				</thead><tbody>';
				echo '
					<tr>
						<td width="30%"><small><b>Nº Motor:</b></small> '.$placa.'</td>
						<td width="30%"><small><b>Marca:</b></small> '.$marca.'</td>
						<td width="30%"><small><b>BP:</b></small> '.$patrimonio.'</td>
					</tr>';
				echo '
					<tr>
						<td><small><b>Chassi: </b></small>'.$chassi.'</td>
						<td><small><b>Valor: </b></small> R$ '.number_format($valor,2,",",".").'</td>
						<td><small><b>Tipo: </b></small>'.$con->query("SELECT descricao FROM notas_eq_situacao WHERE id = '$situacao' ")->fetchColumn().'</td>
					</tr>';
				if($status == '0'){ $status_print = 'ATIVO'; }else{ $status_print = 'INATIVO'; }
				echo '
					<tr>
						<td><small><b>Nº Apolise: </b></small>'.$patrimonio2.'</td>
						<td><small><b>Tipo: </b></small>'.$status_print.'</td>
						<td><small><b>Entrada: </b></small>'.implode("/",array_reverse(explode("-",$entrada))).' - <small><b>Saida: </b></small>'.implode("/",array_reverse(explode("-",$saida))).'</td>
					</tr>';
				echo '
					<tr>
						<td><small><b>Chassi / Nº série: </b></small>'.$chassi.'</td>
						<td><small><b>Ano: </b></small>'.$ano.'</td>
						<td><small><b>Nota: </b></small>'.$desconto.'';
							if($acesso_usuario == 'MASTER'){
								echo '<a href="#" Onclick=\'$(".modal-body").load("almoxarifado/editar-equipamento-master.php?id='.$b['id'].'")\' data-toggle="modal" data-target="#myModal" class="pull-right btn btn-warning btn-xs hidden-print" style="margin:0px; font-weight:bold;"><span class="glyphicon glyphicon-pencil"></span></a>';
							}
						echo '</td>
					</tr>';
					echo '</tbody> </table> </div>';
				}
			exit;
		}
	
	}
	?>

<section class="content">
	<div class="buttons-top-page hidden-print">
		<?php if($acesso_usuario == 'MASTER') { ?>
		<a href="#" style="padding:3px 15px;" title="Cadastrar Novo" class="btn btn-success btn-sm" onclick='$(".modal-body").load("almoxarifado/cadastro-equipamentos.php")' data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle" aria-hidden="true"></i> Cadastrar</a>	
		<?php } ?>
		<a href="#" style="padding:3px 15px; margin:0px 10px;" title="Atualizar Pagina" class="btn btn-warning btn-sm" onclick="ldy('almoxarifado/consulta-equipamentos-2.php','.conteudo')"><i class="fa fa-refresh" aria-hidden="true"></i> Atualizar</a>
		
		<a href="javascript:window.print()" style="padding:3px 15px; margin:0px 10px;"  class="hidden-xs hidden-print pull-right btn btn-warning btn-sm"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span>&nbsp;Imprimir</a>
	</div>
	<div class="hidden-print" style="clear: both;">
		<hr></hr>
	</div>
	<form action="javascript:void(0)" id="form1" class="hidden-print">
		<div class="well well-sm" style="padding:10px 10px 5px 10px;">
			<div class="container-fluid">
				<div class="col-xs-12 col-md-3" style="padding:0px 5px">
					<label for="" style="width:100%"><small>Nota:</small> <br/>
						<input type="text" name="busca" placeholder="Nota fiscal" class="form-control input-sm" />
					</label>
				</div>
				<div class="col-xs-12 col-md-2" style="padding:2px">
					<label for="" style="width:100%"><small>Relatorio:</small> <br/>
						<select name="relatorio" class="form-control input-sm" style="width:100%">
							<option value="1">SIMPLES</option>
							<option value="2">DETALHADA</option>
						</select>
					</label>
				</div>
				<div class="col-xs-12 col-md-2" style="padding:2px">
					<label style="width:100%"><small>Categoria:</small>
						<select name="cat[]" onChange="$('#itens_categoria').load('../functions/functions-load.php?atu=categoria&control=2&categoria=' + $(this).val() + '');" class="sel" multiple="multiple" required> 
							<?php 
								$stms = $con->query("select * from notas_cat_e where oculto = '0' order by descricao asc");
								while($l = $stms->fetch()){
									echo '<option value="'.$l['id'].'" selected>'.$l['descricao'].'</option>'; 
								}
							?>		
						</select>
					</label>
				</div>
				<div class="col-xs-12 col-md-2" style="padding:2px;">
					<div id="itens_categoria">
						<label style="width:100%"><small>Sub-Categoria:</small><br/>
							<select name="sub[]"  class="sel" multiple="multiple" required>
								<?php 
								$stms = $con->query("select * from notas_cat_sub order by descricao asc");
								while($l = $stms->fetch()){
									echo '<option value="'.$l['id'].'" selected>'.$l['descricao'].'</option>'; 
								}
								?>		
							</select>
						</label>
					</div>
				</div>
				<div class="col-xs-12 col-md-2" style="padding:2px;">
						<label style="width:100%"><small>Tipo: </small>
							<select name="si[]" class="sel" multiple="multiple">
								<?php
								$stms = $con->query("select * from notas_eq_situacao where status = '0' order by descricao asc");
								while($l = $stms->fetch()){
									echo '<option value="'.$l['id'].'" selected>'.$l['descricao'].'</option>';
								}
								?>
							</select>
						</label>
				</div>
				<div class="col-xs-12 col-md-2" style="padding:2px;">
						<label style="width:100%"><small>Situação: </small>
							<select name="to[]" class="sel" multiple="multiple">
								<option value="0" selected>ATIVO</option>
								<option value="1" selected>INATIVO</option>
								<option value="2" selected>ROUBADO</option>
							</select>
						</label>
				</div>
				<div class="col-xs-12 col-md-2" style="padding:2px;">
						<label style="width:100%"><small>Status: </small>
							<select name="ct[]" class="sel" multiple="multiple">
								<option value="0" selected>DISPONIVEL</option>
								<option value="1" selected>LOCADO</option>
							</select>
						</label>
				</div>
				<div class="col-xs-12 col-md-2" style="padding:2px;">
					<label><br/>
						<input type="submit" value="Pesquisar" style="width:150px; margin-left:10px;" onClick="post('#form1','almoxarifado/consulta-equipamentos-2.php?ac=consulta','.retorno')" class="btn btn-success btn-sm">
					</label>
				</div>
			</div>
		</div>
	</form>
	<div class="retorno"></div>
</section>
	<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog"  style="width:90%;">
			<div class="modal-content"> 
				<div class="modal-header box box-info" style="margin:0px;">
					<button type="button" class="close" onclick="$('.modal').modal('hide'); $('.modal-body').empty()" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Painel Administrativo</h4>
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