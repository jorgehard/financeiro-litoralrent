<?php
	require_once('../../config.php');
	require_once('../../functions.php');
	$con = new DBConnection();
	verificaLogin(); getData();
?>
<link rel="stylesheet" href="../style/css/combobox.css?v1"/>
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
		//
		$("#combobox").combobox();
		//
		$.fn.dataTable.ext.errMode = 'none';
		$('#resultadoConsulta').DataTable({
			"paging": false,
			"lengthChange": false,
			"searching": true,
			"ordering": true,
			"info": false,
			"bAutoWidth": false
		});
	});
</script>
<?php
	if(isset($ac)){
		//DETALHADO
		if(@$relatorio == '2'){
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
				<h4 class="hidden-xs visible-print" style="font-family: \'Oswald\', sans-serif; letter-spacing:4px; text-align:center; margin-bottom:20px;">
					<p><small>'.strtoupper($con->query("SELECT razao_social FROM litoralrent_cadastroempresa WHERE id = '".$empresaInput."' ")->fetchColumn()).'</small></p>
					<small> PERIODO '.implode("/",array_reverse(explode("-",$data_inicial))).' À '.implode("/",array_reverse(explode("-",$data_final))).'</small>
				</h4>
				</center>';
			echo '<div class="box box-widget">
			<table id="resultadoConsulta" class="table table-striped table-condensed small">
				<thead>
					<tr class="small">
						<th>Nº</th>
						<th>Contrato</th>
						<th class="hidden-print">Empresa:</th>
						<th>BP:</th>
						<th>BP:</th>
						<th class="hidden-print">Chassi:</th>
						<th>Sub-Categoria:</th>
						<th style="text-align:center">Tipo:</th>
						<th style="text-align:center">Data:</th>
						<th style="text-align:center">Valor:</th>';
						if($acesso_usuario == 'MASTER'){
							echo '<th class="hidden-print">Editar:</th>';
						}
						echo '
					</tr>
				</thead> 
			<tbody>';
			$stm = $con->prepare("SELECT contrato_itens.adendo, SUM(contrato_itens.vlr) as valor_total, contrato_dados.id, contrato_dados.empresa, litoralrent_cadastroempresa.razao_social, litoralrent_cadastroempresa.cnpj, contrato_itens.equipamento, contrato_itens.data_retirada, contrato_itens.vlr, contrato_itens.tipo, notas_equipamentos.patrimonio, notas_equipamentos.chassi, notas_equipamentos.sub_categoria FROM contrato_dados INNER JOIN contrato_itens ON contrato_dados.id = contrato_itens.id_contrato INNER JOIN litoralrent_cadastroempresa ON contrato_dados.empresa = litoralrent_cadastroempresa.id INNER JOIN notas_equipamentos ON notas_equipamentos.id = contrato_itens.equipamento WHERE notas_equipamentos.categoria IN($catg) AND notas_equipamentos.sub_categoria IN($subg) AND notas_equipamentos.status IN($sta) AND notas_equipamentos.situacao IN($sit) AND notas_equipamentos.controle IN($ctt) AND (contrato_itens.data_retirada BETWEEN ? and ?) AND (contrato_dados.empresa = '$empresaInput') GROUP BY contrato_itens.id");
			//SELECT contrato_dados.id as id_contrato, contrato_itens.id as id_item, contrato_adendo.obra, SUM(contrato_itens.vlr) as valor_total, contrato_dados.id, contrato_dados.empresa, contrato_itens.equipamento, contrato_itens.data_retirada, contrato_itens.vlr, contrato_itens.tipo FROM contrato_dados INNER JOIN contrato_adendo ON contrato_dados.id = contrato_adendo.id_contrato INNER JOIN contrato_itens ON contrato_adendo.id = contrato_itens.adendo WHERE contrato_dados.empresa = '$empresaInput' GROUP BY contrato_itens.id
			$stm->execute(array("$data_inicial", "$data_final"));
			$total_vlr_g = 0; $se = 0;
			while($all = $stm->fetch())
			{
				$se += 1;
				echo '<tr id="thisTr'.$all['id'].'">';
				echo '<td width="5%">'.$se.'</td>';
				echo '<td width="5%">'.$all['id'].'</td>';
				echo '<td width="40%" class="hidden-print">'.$all['razao_social'].'</td>';
				echo '<td>'.$all['patrimonio'].'</td>';
				echo '<td>'.$con->query("SELECT obra FROM contrato_adendo WHERE id = '".$all['adendo']."' ")->fetchColumn().'</td>';
				echo '<td class="hidden-print" width="5%">'.$all['chassi'].'</td>';
				echo '<td>'.$con->query("SELECT descricao FROM notas_cat_sub WHERE id = '".$all['sub_categoria']."' ")->fetchColumn().'</td>';
				if($all['tipo'] == '0'){
					echo '<td  width="5%" align="center" style="font-size:11px;"><span class="label label-success">LOCADO</span></td>';
				}else if($all['tipo'] == '1'){
					echo '<td  width="5%" align="center" style="font-size:11px;"><span class="label label-danger">DEVOLVIDO</span></td>';
				}
				echo '<td width="5%">'.implode("/",array_reverse(explode("-",$all['data_retirada']))).'</td>';
				echo '<td  width="5%" data-sort="'.$all['valor_total'].'" align="center">R$&nbsp;'.number_format($all['valor_total'],2,",",".").'</td>';
				$total_vlr_g += $all['valor_total'];
				if($acesso_usuario == 'MASTER'){
					echo '
					<td class="hidden-print" id="thisTd'.$all['id'].'" align="center">
						<a href="#" onclick=\'$(".retorno").load("financeiro/editar-contrato.php?id_contrato='.$all['id'].'")\' class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-plus"></span> Editar</a>
					</td>';
				}
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '<h2 class="pull-right" style="font-family: \'Oswald\', sans-serif; letter-spacing:5px;">Total: <small> R$ '.number_format($total_vlr_g,2,",",".").'</small></h2>';
			echo '</div>';
			exit;
		}
		//MEDIÇÃO
		if(@$relatorio == '33'){
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
					<p>'.strtoupper($con->query("SELECT razao_social FROM litoralrent_cadastroempresa WHERE id = '".$empresaInput."' ")->fetchColumn()).'</p>
					<small> PERIODO '.implode("/",array_reverse(explode("-",$data_inicial))).' À '.implode("/",array_reverse(explode("-",$data_final))).'</small>
				</h5>
				</center>';
			echo '<div class="box box-widget">
					<table id="resultadoConsulta" class="box box-widget table  table-striped table-min small" style="font-size:10px">
					<thead>
						<tr>
							<th style="text-align:center"><i class="fa fa-list-alt" aria-hidden="true"></i></th>
							<th style="text-align:center">Categoria:</th>
							<th style="text-align:center">Patrimônio:</th>
							<th  class="hidden-print" style="text-align:center">Chassi:</th>
							<th style="text-align:center">Data Locação:</th>
							<th style="text-align:center">Dias</th>
							<th style="text-align:center">Vlr:</th>
							<th style="text-align:center">Vlr (Ref):</th>
							<th style="text-align:center">Status:</th>';
							echo '
						</tr>
					</thead> 
				<tbody>';
				
				$stm = $con->query("SELECT * FROM notas_equipamentos WHERE notas_equipamentos.categoria IN($catg) AND notas_equipamentos.sub_categoria IN($subg) AND notas_equipamentos.situacao IN($sit) AND controle = '1'");
				$c = 0; $total_equipamentos = 0;
				while($b = $stm->fetch()){
					$stm34 = $con->query("SELECT contrato_itens.*, (SELECT empresa FROM contrato_dados WHERE contrato_dados.id = contrato_itens.id_contrato) AS empresa_id, contrato_itens.vlr as vlr_contrato, contrato_itens.data_retirada FROM contrato_itens WHERE contrato_itens.equipamento = '".$b['id']."'  AND contrato_itens.tipo = 0 ORDER BY contrato_itens.data_retirada DESC LIMIT 1");
					$rowxx = $stm34->fetch();
					if($rowxx != ''){
					$c += 1;
					echo '<tr id="thisTr'.$b['id'].'">';
					echo '<td style="text-align:center">'.$c.'</td>';
					echo '<td>'.$con->query("SELECT descricao FROM notas_cat_sub WHERE id = '".$b['sub_categoria']."' ")->fetchColumn().'</td>';
					echo '<td> '.$b['patrimonio'].'</td>';
					echo '<td class="hidden-print">'.$b['chassi'].'</td>';
					$data_controle1 = explode("-", $data_final);
					$data_controle2 = explode("-", $rowxx['data_retirada']);
					if($data_controle1[1] == $data_controle2[1]){
						$datetime1 = date_create($data_final);
						$datetime2 = date_create($rowxx['data_retirada']);
						
						$interval = date_diff($datetime1, $datetime2);
						$totalDias = ($interval->days) + 1;
					}else{
						$datetime1 = date_create($data_final);
						$datetime2 = date_create($data_inicial);
						
						$interval = date_diff($datetime1, $datetime2);
						$totalDias = ($interval->days) + 1;
					}
					$controle1 = date_create($data_final);
					$controle2 = date_create($data_inicial);
					$valorControle = date_diff($controle1, $controle2);
					$diasMes = ($valorControle->days) + 1;
					echo '<td style="text-align:center">'.implode("/",array_reverse(explode("-",$rowxx['data_retirada']))).'</td>';
					echo '<td class="text-danger">'.$totalDias.' dias</td>';
					echo '<td style="text-align:center">R$ '.number_format($rowxx['vlr_contrato'],2,",",".").'</td>';
					$total_certo = ($rowxx['vlr_contrato'] / $diasMes) * $totalDias;
					echo '<td style="text-align:center">R$ '.number_format($total_certo,2,",",".").'</td>';
					$total_equipamentos += $total_certo;
					echo '<td style="text-align:center">';
					if($b['controle'] == '1'){ 
						echo '<span class="label label-success">LOCADO</label>'; 
					}
					echo '</td>';
					echo '</tr>';
					}
				}
				
				echo '</tbody> </table>';
				
				echo '<h2 class="pull-right" style="font-family: \'Oswald\', sans-serif; letter-spacing:5px;">Total: <small> R$ '.number_format($total_equipamentos,2,",",".").'</small></h2>';
				
				echo '</div>';
			exit;
		}
		//itens
		if(@$relatorio == '3'){
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
				<h4 class="hidden-xs visible-print" style="font-family: \'Oswald\', sans-serif; letter-spacing:4px; text-align:center; margin-bottom:20px;">
					<p><small>'.strtoupper($con->query("SELECT razao_social FROM litoralrent_cadastroempresa WHERE id = '".$empresaInput."' ")->fetchColumn()).'</small></p>
					<small> PERIODO '.implode("/",array_reverse(explode("-",$data_inicial))).' À '.implode("/",array_reverse(explode("-",$data_final))).'</small>
				</h4>
				</center>';
				echo $empresaInput;
			echo '<div class="box box-widget">
			<table id="resultadoConsulta" class="table table-striped table-condensed small">
				<thead>
					<tr class="small">
						<th>Nº</th>
						<th>Contrato:</th>
						<th class="hidden-print">Empresa:</th>
						<th>Item:</th>
						<th>Obs:</th>
						<th style="text-align:center">Data:</th>
						<th style="text-align:center">Qtd:</th>
						<th style="text-align:center">Vlr:</th>
						<th style="text-align:center">Total:</th>
					</tr>
				</thead> 
			<tbody>';
			if($empresaInput == '0'){
				$stm = $con->prepare("SELECT contrato_venda.*, SUM(contrato_venda.qtd * contrato_venda.vlr) as vlr_total, contrato_dados.id AS id_contrato, (SELECT razao_social FROM litoralrent_cadastroempresa WHERE id = contrato_dados.empresa) AS razao_social FROM contrato_dados INNER JOIN contrato_venda ON contrato_dados.id = contrato_venda.id_contrato WHERE (contrato_venda.data_venda BETWEEN ? AND ?) GROUP BY contrato_venda.id ");
				$stm->execute(array("$data_inicial", "$data_final"));
			}else{
				$stm = $con->prepare("SELECT contrato_venda.*, SUM(contrato_venda.qtd * contrato_venda.vlr) as vlr_total, contrato_dados.id AS id_contrato, (SELECT razao_social FROM litoralrent_cadastroempresa WHERE id = contrato_dados.empresa) AS razao_social FROM contrato_dados INNER JOIN contrato_venda ON contrato_dados.id = contrato_venda.id_contrato WHERE contrato_dados.empresa = '$empresaInput' AND (contrato_venda.data_venda BETWEEN ? AND ?) GROUP BY contrato_venda.id ");
				$stm->execute(array("$data_inicial", "$data_final"));
			}
			
			$total_vlr_g = 0; $se = 0;
			while($all = $stm->fetch())
			{
				$se += 1;
				echo '<tr id="thisTr'.$all['id'].'">';
				echo '<td width="5%">'.$se.'</td>';
				echo '<td width="5%">'.$all['id_contrato'].'</td>';
				echo '<td width="20%" class="hidden-print">'.$all['razao_social'].'</td>';
				echo '<td width="10%">'.$con->query("SELECT descricao FROM notas_itens WHERE id = '".$all['item']."' ")->fetchColumn().'</td>';

				echo '<td width="10%">'.$all['obs'].'</td>';
				
				echo '<td width="5%">'.implode("/",array_reverse(explode("-",$all['data_venda']))).'</td>';
				echo '<td width="5%">'.$all['qtd'].'</td>';
				echo '<td  width="5%" data-sort="'.$all['vlr'].'" align="center">R$&nbsp;'.number_format($all['vlr'],2,",",".").'</td>';
				echo '<td  width="5%" data-sort="'.$all['vlr_total'].'" align="center">R$&nbsp;'.number_format($all['vlr_total'],2,",",".").'</td>';
				
				$total_vlr_g += $all['vlr_total'];
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '<h2 class="pull-right" style="font-family: \'Oswald\', sans-serif; letter-spacing:5px;">Total: <small> R$ '.number_format($total_vlr_g,2,",",".").'</small></h2>';
			echo '</div>';
			exit;
		}
	}
?>
	<div class="buttons-top-page hidden-print">
		<?php if($acesso_usuario == 'MASTER') { ?>
		<a href="#" style="padding:3px 15px;" title="Cadastrar Novo" class="btn btn-success btn-sm" onclick="ldy('financeiro/cadastro-contrato.php','.conteudo')"><i class="fa fa-plus-circle" aria-hidden="true"></i> Cadastrar</a>
		<?php } ?>
		<a href="#" style="padding:3px 15px; margin:0px 10px;" title="Atualizar Pagina" class="btn btn-warning btn-sm" onclick="ldy('financeiro/consulta-contrato.php','.conteudo')"><i class="fa fa-refresh" aria-hidden="true"></i> Atualizar</a>
		
		<a href="javascript:window.print()" style="padding:3px 15px; margin:0px 10px;"  class="hidden-xs hidden-print pull-right btn btn-warning btn-sm"> <span class="glyphicon glyphicon-print" aria-hidden="true"></span>&nbsp;Imprimir</a>
	</div>
	<div style="clear: both;" class="hidden-print">
		<hr></hr>
	</div>
<section class="content-header hidden-print">
	<h1>Relatorio Contratos<small></small></h1>
</section>
<section class="content">
	<form action="javascript:void(0)" id="form1" class="hidden-print">
		<div class="well well-sm" style="padding:10px 10px 5px 10px;">
			<div class="container-fluid">
				<div class="col-xs-12 col-md-3" style="padding:2px">
					<label for="" style="width:100%;"><small>Empresa / CNPJ:</small> <br/>
						<select id="combobox" name="empresaInput" class="form-control input-sm" required>
								<?php 
								$empresasql = $con->query("select * from litoralrent_cadastroempresa order by razao_social asc");
								while($l = $empresasql->fetch()) {
									echo '<option value="'.$l['id'].'">'.$l['razao_social'].'</option>'; 
								}
								?>			
						</select>
					</label>
				</div>
				<div class="col-xs-12 col-md-3" style="padding:2px">
					<label for="" style="width:100%"><small>Relatorio:</small> <br/>
						<select name="relatorio" class="form-control input-sm" style="width:100%">
							<!--<option value="1">SIMPLES</option>-->
							<option value="2">DETALHADO</option>
							<option value="33">MEDIÇÃO</option>
							<option value="3">ITENS</option>
						</select>
					</label>
				</div>
				<div class="col-xs-12 col-md-2" style="padding:2px">
					<label style="width:100%"><small>Data: </small>
						<input type="date" name="data_inicial" value="<?php echo $inicioMes; ?>" class="form-control input-sm" style="width:100%"/>
					</label>
				</div>
				<div class="col-xs-12 col-md-2" style="padding:2px">
					<label style="width:100%"><small><br/></small>
						<input type="date" name="data_final" value="<?php echo $todayTotal; ?>" class="form-control input-sm" style="width:100%"/>
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
						<input type="submit" value="Pesquisar" style="width:150px; margin-left:10px;" onClick="post('#form1','financeiro/relatorio-contrato.php?ac=consulta','.retorno')" class="btn btn-success btn-sm">
					</label>
				</div>
			</div>
		</div>
	</form>
	<div class="retorno"></div>
</section>