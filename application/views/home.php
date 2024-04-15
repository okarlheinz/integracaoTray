<?php
defined('BASEPATH') or exit('No direct script access allowed');
// echo '<PRE>';
// foreach ($token as $row) 
// {
//	var_dump($token);

// 	}

// var_dump($notasEmitidas);
// var_dump($produtosSemEstoque);
// var_dump($produtosInexistentes);
// die();
// echo '<PRE>';
// var_dump($Client);

// foreach ($Client as $row){

// 	var_dump($row);
// }


// die();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<title>CDS Sistemas - Sincronização Tray</title>
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap/bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/datatable/dataTables.bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/datatable/responsive.dataTables.css">
	<link rel="shortcut icon" href="<?php echo base_url() ?>assets/img/logo30x30.ico">
	<style type="text/css">
		::selection {
			background-color: #E13300;
			color: white;
		}

		::-moz-selection {
			background-color: #E13300;
			color: white;
		}

		body {
			background-color: #fff;
			margin: 40px;
			font: 13px/20px normal Helvetica, Arial, sans-serif;
			color: #4F5155;
		}

		a {
			color: #003399;
			background-color: transparent;
			font-weight: normal;
		}

		h1 {
			color: #444;
			background-color: transparent;
			color: #1E90FF;
			border-bottom: 1px solid #D0D0D0;
			font-size: 19px;
			font-weight: normal;
			margin: 0 0 14px 0;
			padding: 14px 15px 5px 15px;
		}

		code {
			font-family: Consolas, Monaco, Courier New, Courier, monospace;
			font-size: 12px;
			background-color: #f9f9f9;
			border: 1px solid #D0D0D0;
			color: #002166;
			display: block;
			margin: 14px 0 14px 0;
			padding: 12px 10px 12px 10px;
		}

		#body {
			margin: 0 15px 0 15px;
		}

		p.footer {
			text-align: right;
			font-size: 11px;
			border-top: 1px solid #D0D0D0;
			line-height: 32px;
			padding: 0 10px 0 10px;
			margin: 20px 0 0 0;
		}

		#container {
			margin: -20px;
			border: 1px solid #D0D0D0;
			box-shadow: 0 0 8px #D0D0D0;
		}

		.contagem {
			/*width:300px;*/
			/*height:70px;*/
			background-color: #ffffff;
			border-radius: 130px;
			width: 150px;
			height: 150px;
			border-width: 6px;
			border-color: #39a02b;
			border-style: solid;
			display: inline-block;
			color: #1E90FF;

		}

		.numero {
			min-width: 20px;
			max-width: 55px;
			/*background-color: #efefef;*/
			color: #1E90FF;
			font-size: 42px;
			font-weight: bold;
			/*margin: 5px;*/
			margin: 50px 0 0 40px;
			text-align: center;

			border-radius: 5px;
			padding: 5px;
		}

		.row {
			margin-left: -10px;
			margin-right: -10px;
		}

		button[type="button"] {
			outline: none;
			border: none;
		}

		*:focus {
			outline: 0 !important;
			border: none;
		}

		button:focus {
			border: none;
			outline: none;
		}

		#grades_produtos_wrapper>div:nth-of-type(1),
		#dados_loja_wrapper>div:nth-of-type(1),
		#produtos_inexistentes_wrapper>div:nth-of-type(1),
		#Clientes_wrapper>div:nth-of-type(1),
		#Pedidos_wrapper>div:nth-of-type(1),
		#notas_Emitidas_wrapper>div:nth-of-type(1),
		#atualiza_grades_produtos_wrapper>div:nth-of-type(1) {
			background: #f5f5f5;
			padding: 5px;
			margin: -6px 5px 0px 5px;
			border: 1px solid #CCCCCC;
		}

		#grades_produtos_wrapper>div:nth-of-type(3),
		#dados_loja_wrapper>div:nth-of-type(3),
		#produtos_inexistentes_wrapper>div:nth-of-type(3),
		#Clientes_wrapper>div:nth-of-type(3),
		#Pedidos_wrapper>div:nth-of-type(3),
		#notas_Emitidas_wrapper>div:nth-of-type(3),
		#atualiza_grades_produtos_wrapper>div:nth-of-type(3) {
			background: #f5f5f5;
			padding: 5px;
			margin: 0px 5px 0px 5px;
			border: 1px solid #CCCCCC;
		}
	</style>
</head>

<body>

	<div id="container">
		<h1 style="text-align: center; background-color: #1C1C1C;color: #fff;">Sincronização Tray</h1>
		<br>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-8"></div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" style="float:right;text-align: center;margin-right: 20px;">
				<label style="margin-right: 0;font-size: 18pt;" for="">Próxima Sincronização</label>
				<br><br>
				<input type="hidden" id="controlaTempo" value="1">
				<div class="form-group">
					<button type="button" class="" style="background-color: transparent;" id="btn_start_stop">
						<img id="img_tempo" style="width:80px;margin-right: 45px; margin-top: -70px;" src="<?php echo  base_url() ?>assets/img/pause_tempo.png">
					</button>

					<div class="contagem">
						<div class="numero segundo" id="segundo">30</div>
						<p style="margin: 10px 0 0 0px;font-size:20px; ">Segundos</p>
					</div>
				</div>
			</div>
		</div>

		<br>
		<br>


		<div id="body">



			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
					<h1>Dados Loja</h1>
					<table id="dados_loja" name="dados_loja" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
						<thead style="background-color: #00BFFF; color:#FFFFFF">
							<tr>
								<th style="white-space:nowrap;text-align:center;">Url Loja</th>
								<th style="white-space:nowrap;text-align:center;">Consumer Key</th>
								<th style="white-space:nowrap;text-align:center;">Consumer Secret</th>
								<th style="white-space:nowrap;text-align:center;">Code</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($dadosLoja)) {
								foreach ($dadosLoja as $row) { ?>
									<tr>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['base_url_loja']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['consumer_key']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['consumer_secret']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['code']) ?></td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

					<h1>Dados Token</h1>
					<table id="produtos_inexistentes" name="produtos_inexistentes" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
						<thead style="background-color: #FF0000; color:#FFFFFF">
							<tr>
								<th style="white-space:nowrap;text-align:center;">Acccess Token</th>
								<th style="white-space:nowrap;text-align:center;">Refresh Token</th>
								<th style="white-space:nowrap;text-align:center;">Expiration Acccess Token</th>
								<th style="white-space:nowrap;text-align:center;">Expiration Refresh Token</th>
								<th style="white-space:nowrap;text-align:center;">Date Activated</th>
								<th style="white-space:nowrap;text-align:center;">Api Host</th>
								<th style="white-space:nowrap;text-align:center;">Store Id</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($token)) {
								foreach ($token as $row) { ?>
									<tr>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row->access_token) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row->refresh_token) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row->date_expiration_access_token) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row->date_expiration_refresh_token) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row->date_activated) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row->api_host) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row->store_id) ?></td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
			</div>
			<br>
			<br>


			<!-- Clientes -->

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<h1>Clientes Tray</h1>
					<table id="Clientes" name="Clientes" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
						<thead style="background-color: #04bf04; color:#FFFFFF">
							<tr>
								<th style="white-space:nowrap;text-align:center;">Nome</th>
								<th style="white-space:nowrap;text-align:center;">CPF</th>
								<th style="white-space:nowrap;text-align:center;">Endereco</th>
								<th style="white-space:nowrap;text-align:center;">CEP</th>
								<th style="white-space:nowrap;text-align:center;">Num</th>
								<th style="white-space:nowrap;text-align:center;">Compl</th>
								<th style="white-space:nowrap;text-align:center;">Bairro</th>
								<th style="white-space:nowrap;text-align:center;">Cidade</th>
								<th style="white-space:nowrap;text-align:center;">UF</th>
								<th style="white-space:nowrap;text-align:center;">Fone</th>
								<th style="white-space:nowrap;text-align:center;">Email</th>
								<th style="white-space:nowrap;text-align:center;">Retorno</th>	
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($clientes)) {
								foreach ($clientes as $row) { ?>
									<tr>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['nome']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['CPF']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['Endereco']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['CEP']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['Num']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['Compl']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['Bairro']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['Cidade']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['UF']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['Fone']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['Email']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['Situacao']) ?></td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Fim Clientes -->


			
			<!-- Pedidos -->

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<h1>Pedidos</h1>
					<table id="Pedidos" name="Pedidos" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
						<thead style="background-color: brown; color:#FFFFFF">
							<tr>
								<th style="white-space:nowrap;text-align:center;">Id Pedido Site</th>
								<th style="white-space:nowrap;text-align:center;">Id Venda CDS</th>
								<th style="white-space:nowrap;text-align:center;">Retorno</th>								
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($pedidos)) {
								foreach ($pedidos as $row) { ?>
									<tr>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['PedidoTray']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['IdVendaCDS']) ?></td>
										<td style='vertical-align:middle;white-space:nowrap;text-align:center;'><?php echo trim($row['Situacao']) ?></td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Fim Pedidos -->

			<br>

		</div>

	</div>

</body>


<footer>

	<script>
		var base_url = '<?php echo base_url() ?>';
	</script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/js/bootstrap/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/js/datatable/dataTables.bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/js/datatable/dataTables.responsive.js"></script>
	<?php echo '<script type="text/javascript" src="' . base_url() . 'assets/js/processos.js?' . time() . '"></script>'; ?>





</footer>

</html>