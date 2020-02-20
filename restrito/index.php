<?php
	require('../config.php');
	require('../functions.php');
	$con = new DBConnection();
	verificaLogin();
	getNivel();
	getData();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<title> <?php echo $titulo_geral; ?> - Sistema Administrativo</title>
	<link rel="icon" href="../style/img/icone-litoralrent.ico" type="image/x-icon"/>
	<link rel="shortcut icon" href="../style/img/imagens/icone-litoralrent.ico" type="image/x-icon"/>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
	<link rel="stylesheet" href="../style/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="../style/css/bootstrap-combobox.css" />
	<link rel="stylesheet" href="../style/css/dashboard.css?v04"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"/>
	<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed|Ubuntu|Oswald:300' rel='stylesheet' type='text/css'/>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="../style/css/skins/skin-blue.min.css"/>
	<link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css"/>
	<link rel="stylesheet" href="../plugins/iCheck/all.css"/>
	<link rel="stylesheet" href="../plugins/autocomplete/jquery-ui.css"/>
	<link rel="stylesheet" href="../style/css/uploadfile.min.css"/>
	<link rel="stylesheet" href="../style/css/restrito-dashboard.css"/>
	<link rel="stylesheet" href="../style/css/multiple-select.css"/>
	<link rel="stylesheet" href="../style/css/multiselect.filter.css"/>
	
	<link href='../plugins/core/main.css' rel='stylesheet' />
    <link href='../plugins/daygrid/main.css' rel='stylesheet' />

    <script src='../plugins/core/main.js'></script>
    <script src='../plugins/daygrid/main.js'></script>
	
	<script src='../plugins/core/locales/pt-br.js'></script>
	<script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
		  locale: 'pt-br',
		  plugins: ['dayGrid'],
		  height: 'parent',
		  header: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
		  },
		  defaultView: 'dayGridMonth',
		  defaultDate: '<?= $todayTotal ?>',
		  navLinks: true, 
		  editable: true,
		  eventLimit: true, 
		  events: [
			{
				id: '1',
				title: 'Lançar nota fiscal',
				start: '2019-04-09',
				end: '2019-04-09',
				color: '#F8BB3C'
			},
			{
				id: '2',
				title: 'Teste Calendario',
				start: '2019-04-09',
				end: '2019-04-09',
				color: '#EB5900'
			},
		  ]
		});
		calendar.setOption('locale', 'pt-br');
		calendar.render();
      });

    </script>
</head>
<body class="inicio-painel">
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php" style="font-family: 'Lobster';"> <img src="../style/img/litoralrent-logo.png" alt="" width="35%" style="margin-top:-5px; margin-right:10px; float:left;"/></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
			<li><a href="#" onClick="ldy('troca-senha.php','.conteudo')"><i class="fas fa-user-cog"></i> <?= $login_usuario ?></a></li>
            <li><a href="../logout.php?acao=true"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
          </ul>
        </div>
		
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div id="navBarLeft" class="col-sm-3 col-md-2 sidebar">
				<ul class="nav nav-sidebar">
				<!-- FINANCEIRO -->
					<?php if ($financeiro_array == $id_usuario_logado) { ?>
					<li class="header button-header" onClick="clickFunction1();"><i class="fas fa-dollar-sign"></i> Financeiro<i class="fa fa-caret-down pull-right" aria-hidden="true"></i></li>
						<li class="header sub-header list-block list-finac"> Cadastro</li>
							<li class="list-block list-finac buttonClass"><a href="#" onClick="ldy('financeiro/cadastro-contrato.php','.conteudo')"><i class="fas fa-file-contract"></i> <span>Contrato</span></a></li>
						<li class="header sub-header list-block list-finac"> Consulta</li>
							<li class="list-block list-finac buttonClass"><a href="#" onClick="ldy('financeiro/consulta-contrato.php','.conteudo')"><i class="fas fa-search-dollar"></i> <span>Contrato (Locação)</span></a></li>
							<li class="list-block list-finac buttonClass"><a href="#" onClick="ldy('financeiro/consulta-contrato-devolucao.php','.conteudo')"><i class="fas fa-search-dollar"></i> <span>Contrato (Devolução)</span></a></li>
						<li class="header sub-header list-block list-finac"> Relatório</li>
							<li class="list-block list-finac buttonClass"><a href="#" onClick="ldy('almoxarifado/consulta-equipamentos-2.php','.conteudo')"><i class="fas fa-truck-pickup"></i> <span>Equipamentos</span></a></li>
							<li class="list-block list-finac buttonClass"><a href="#" onClick="ldy('financeiro/relatorio-contrato.php','.conteudo')"><i class="fas fa-newspaper"></i> <span>Relatorio Contrato</span></a></li>
					<?php } ?>
				<!-- COMPRAS -->
					<?php if($compras_array == $id_usuario_logado) { ?>
					<li class="header button-header" onClick="clickFunction2();"><i class="fas fa-hand-holding-usd"></i> Comercial <i class="fa fa-caret-down pull-right" aria-hidden="true"></i></li>
					<li class="header sub-header list-block list-comp"> Cadastro</li>
						<li class="list-block list-comp buttonClass"><a href="#" onClick="ldy('financeiro/cadastro-empresa.php','.conteudo')"><i class="fas fa-briefcase"></i> <span>Cadastro Empresas</span></a></li>
						<li class="list-block list-comp buttonClass"><a href="#" onClick="ldy('financeiro/cadastro-orcamento.php','.conteudo')"><i class="far fa-money-bill-alt"></i> <span>Cadastro Orçamento</span></a></li>
					<li class="header sub-header list-block list-comp"> Consulta</li>
						<li class="list-block list-comp buttonClass"><a href="#" onClick="ldy('financeiro/consulta-empresas.php','.conteudo')"><i class="fas fa-search"></i> <span>Consulta Empresas</span></a></li>
						<li class="list-block list-comp buttonClass"><a href="#" onClick="ldy('financeiro/consulta-orcamento.php','.conteudo')"><i class="fas fa-briefcase"></i> <span>Orçamento</span></a></li>
						<li class="list-block list-comp buttonClass"><a href="#" onClick="ldy('financeiro/consulta-itens-2.php','.conteudo')"> <i class="fas fa-folder-open"></i> <span>Itens</span></a></li>
					<?php } ?>
				<!-- CONSULTA -->
					<li class="header button-header" onClick="clickFunction3();"><span class="glyphicon glyphicon-search"></span> Consulta <i class="fa fa-caret-down pull-right" aria-hidden="true"></i></li>
					<!--<li class="header sub-header list-logistica"> Consulta</li>	-->
						<li class="header sub-header list-block list-consulta"> Relatório</li>
						<li class="list-block list-consulta buttonClass"><a href="#" onClick="ldy('financeiro/relatorio-contrato.php','.conteudo')"><i class="fas fa-newspaper"></i> <span>Relatorio Contrato</span></a></li>
						<li class="list-block list-consulta buttonClass"><a href="#" onClick="ldy('almoxarifado/consulta-equipamentos.php','.conteudo')"><i class="fas fa-truck-pickup"></i> <span>Equipamentos</span></a></li>
						
				<!-- GESTOR -->
					<?php if ($gestor_array == $id_usuario_logado) { ?>
					<li class="header button-header" onClick="clickFunction4();"><i class="fa fa-user-secret" aria-hidden="true"></i>&nbsp;&nbsp;Gestor <i class="fa fa-caret-down pull-right" aria-hidden="true"></i></li>
					<li class="header sub-header list-block list-gestor">RH</li>
						<li class="list-block list-gestor buttonClass"><a href="#" onclick="ldy('gestor/consulta-usuarios.php','.conteudo')"><i class="fa fa-users" aria-hidden="true"></i> <span>Usuários</span></a></li>
					<li class="header sub-header list-block list-gestor">Consulta</li>
						<li class="list-block list-gestor buttonClass"><a href="#" onclick="ldy('gestor/consulta-categoria-equip.php','.conteudo')"><i class="fas fa-car"></i> <span>Categoria Equipamentos</span></a></li>
						<li class="list-block list-gestor buttonClass"><a href="#" onclick="ldy('gestor/consulta-situacao-equip.php','.conteudo')"><i class="fas fa-clipboard-check"></i> <span>Situação Equipamentos</span></a></li>
						<li class="list-block list-gestor buttonClass"><a href="#" onClick="ldy('financeiro/consulta-itens.php','.conteudo')"><i class="fas fa-boxes"></i> <span>Itens</span></a></li>
						
						<li class="list-block list-gestor buttonClass"><a href="#" onClick="ldy('calendario/calendario.php','.conteudo')"><i class="icon-calendar"></i> <span>teste</span></a></li>
					<?php } ?>
				</ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<div class="content-wrapper conteudo" style="margin:10px">
				<!--<div class="container-fluid">
					<div class="col-xs-12 col-md-3" style="padding:20px">
						<div class="btn-dash">
							<div class="col-xs-4 dash-head">
								<i class="fas fa-bell"></i>
							</div>
							<div class="col-xs-8 dash-body">
								teste
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-3" style="padding:20px">
						<div class="btn-dash">
						-
						</div>
					</div>
					<div class="col-xs-12 col-md-3" style="padding:20px">
						<div class="btn-dash">
						-
						</div>
					</div>
					<div class="col-xs-12 col-md-3" style="padding:20px">
						<div class="btn-dash">
						-
						</div>
					</div>
				</div>-->
				<div class="container-fluid">
					<script>
						(() => {
    console.log(1);
    setTimeout(function(){console.log(2)}, 1000);
    setTimeout(function(){console.log(3)}, 0);
    console.log(4);
})();
					</script>
					<div class="col-xs-12 col-md-8">
						<section style="margin:0px; padding:5px; border-bottom:1px solid #ccc; margin-bottom:20px;">
							<h4 style="font-family: 'Oswald', sans-serif; letter-spacing:1.5px;">Calendário<small> </small></h4>
						</section>
						<div id='calendar-container'>
							<div id='calendar'></div>
						</div>
					</div>
					<div class="col-xs-12 col-md-4">
						<section style="margin:0px; padding:5px; border-bottom:1px solid #ccc; margin-bottom:20px;">
							<h4 style="font-family: 'Oswald', sans-serif; letter-spacing:1.5px;">Equipamentos Disponiveis<small> </small></h4>
						</section>
						<?php
							echo '<div class="table-dash-box">
								<table id="resultadoConsulta" class="box box-widget table table-condensed table-dash" style="font-size:12px">
								<thead>
									<tr>
										<th style="text-align:center"><i class="fa fa-list-alt" aria-hidden="true"></i></th>
										<th style="text-align:center">Descrição:</th>
										<th style="text-align:center">Total</th>
									</tr>
								</thead> 
							<tbody>';
						$stm2 = $con->query("select *, (SELECT COUNT(*) FROM notas_equipamentos WHERE sub_categoria = notas_cat_sub.id AND status IN(0) AND situacao IN(2,1) AND controle IN(0)) AS total_equipamentos FROM notas_cat_sub WHERE oculto = '0' order by descricao asc");
						$se2 = 0;
						$total_equipamentos_g = 0;
						while($c = $stm2->fetch()){
							if($c['total_equipamentos'] <> 0){
								$se2 += 1;
								echo '<tr>';
								echo '<td style="text-align:center">'.$se2.'</td>';
								echo '<td>'.$c['descricao'].'</td>';
								echo '<td style="text-align:center">'.$c['total_equipamentos'].'</td>';
								echo '</tr>';
								$total_equipamentos_g += $c['total_equipamentos'];
							}
						}
						echo '<tfoot>';
						echo '<tr class="active"><td colspan="2"><b>Total</b></td><td style="text-align:center"><b>'.$total_equipamentos_g.'</b></td></tr>';
						echo '</tfoot></table></div>';
					?>
					</div>
				</div>
			</div>
			<!---------------------------------------------------------------------------------->

			<div id="loading" class="hidden-print" style="width:100%; height:100%; display:none; position:fixed; top:0; left:0; background: rgba(255, 255, 255, 0.5); z-index:9999;">
				<div style="position:relative; top: 40%; text-align:center;">
					<img src="../style/img/loading.svg"  alt="" width="40px" />
					<h4 style="font-family: 'Lobster', sans-serif; font-size:15px; color: rgba(0, 0, 0, 0.5);">Carregando...</h4>
				</div>
			</div>
			<div id="loadingstart" style="position: absolute; height: 100%; width: 100%; top:0; left: 0; background: #FFF; z-index:9999; 
			font-size: 30px; text-align: center; padding-top: 10px; color: #666;">
				<img src="../style/img/loading.gif" alt="" width="120px"/>
				<h4 style="font-family: 'Lobster', sans-serif; font-size:15px; color: rgba(0, 0, 0, 0.5);">Carregando...</h4>
			</div>
			<footer class="main-footer hidden-print" style="padding:20px 10px; background:#F5F5F5">
				<div>
					<strong>Copyright &copy; 2018</strong> Todos direitos reservados. <br/> <small>Desenvolvido por: AtlasWare | Soluções tecnologicas</small>
				</div>
				
			</footer>
		</div>
		</div>
    </div>
	
	<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="../style/js/bootstrap.min.js"></script>
	<script src="../style/js/app.min.js"></script>
	<script src="../style/js/bootstrap-combobox.js"></script>
	<script src="../plugins/datatables/jquery.dataTables.js?v3"></script>
	<script src="../plugins/datatables/dataTables.bootstrap.js"></script>
	<script src="../plugins/iCheck/icheck.min.js"></script>
	<script src="../plugins/autocomplete/jquery-ui.min.js"></script>
	<script src="../plugins/autocomplete/jquery.select-to-autocomplete.js"></script>
	<script src="../plugins/input-mask/jquery.maskedinput.js"></script>
   
	<script src="../plugins/jquery.uploadfile.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js"></script>
	<script src="../plugins/jquery.slimscroll.min.js"></script>
	
	<script src="../plugins/jquery.multiple.js"></script>
	<script src="../plugins/bootstrap-select.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script src="../plugins/jquery.printElement.js"></script>
	<!-- -->
	<script src="../style/js/all.js"></script>
	<script src="../style/js/combobox.js"></script>
	<script>
	function clickFunction1(){
		$(".list-block").not(".list-finac").slideUp();
		$(".list-finac").slideToggle('slow');
	}
	function clickFunction2(){
		$(".list-block").not(".list-comp").slideUp();
		$(".list-comp").slideToggle('slow');
	}
	function clickFunction3(){
		$(".list-block").not(".list-consulta").slideUp();
		$(".list-consulta").slideToggle('slow');
	}
	function clickFunction4(){
		$(".list-block").not(".list-gestor").slideUp();
		$(".list-gestor").slideToggle('slow');
	}
	
	$('.buttonClass').click(function(){
		$('li').removeClass("active");
		$(this).addClass("active");
	});
    $(window).load(function() {
        $('#loadingstart').fadeOut(200);
		$('.list-finac').slideToggle('slow');
		$('.list-comp').slideToggle('slow');
		$('.list-consulta').slideToggle('slow');
		$('.list-gestor').slideToggle('slow');
    });
	</script>
</body>
</html>
