<?php
defined('BASEPATH') or exit('No direct script access allowed');

class integracaoDAO extends CI_Model
{




	public function getProdutosSemIdTray()
	{

		$sql = "SELECT top 1 G.idTrayGrupo, (select COALESCE(fotoCaminho2,'') +','+COALESCE(fotoCaminho3,'')+','+ COALESCE(fotoCaminho4,'')+','+COALESCE(fotoCaminho5,'') fotosSku FROM produtoFotos where prodcodigo = codigo) fotosSku,
					* FROM produto 
					LEFT JOIN GRUPO G ON G.GRUPO = PRODUTO.GRUPO
				where (idtray is null or idtray='') and lojavirtual = 1";

		$query = $this->db->query($sql);



		if ($query->num_rows() > 0) {

			return $query->result();
		} else {

			return false;
		}
	}


	public function putProdutosSemIdTray($id, $codigo)
	{

		$sql = "UPDATE produto SET idtray=? where codigo=?";


		$this->db->trans_start();

		$this->db->db_debug = false;

		$query = $this->db->query(
			$sql,
			array(
				$id,
				$codigo
			)
		);



		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->db_debug = true;
			return false;
		} else {
			$this->db->db_debug = true;
			return true;
		}
	}





	public function getEstoque()
	{


		$sql1 = "  IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='comparaEstoqueSinc' AND xtype='U')
						CREATE TABLE comparaEstoqueSinc
						(
							prodCodigo char(14) NOT NULL,
							idTray char(50) NOT NULL,
							idFilial smallint NOT  NULL,
							qtdDisp real NULL,
							name char(120),
							reference char(20),
							brand char(40),
							price float,
							dataEnvio datetime,
							prccompra float
							PRIMARY  KEY(prodCodigo,idFilial)
						); ";
		$query1 = $this->db->query($sql1);

		// verifica se Tabela esta Com informações
		if ($query1) {
			$sql2 = "	SELECT TOP 1 * FROM comparaEstoqueSinc WHERE idFilial=1";
			$query2 = $this->db->query($sql2);

			if ($query2->num_rows() < 1 || $query2->num_rows() == null) {
				$sql3 = "	INSERT INTO comparaEstoqueSinc SELECT prodCodigo,idTray,idFilial, -1,'','','',0 , getdate(),produto.compraPrc  FROM filialEstoque inner join gradeprod on gradeprod.codigograde=filialestoque.prodcodigo inner join produto on produto.codigo=gradeprod.codigobase WHERE idFilial=1 and idtray is not null and idTray<>'' ";
				$query3 = $this->db->query($sql3);
			}
		}
		// verifica se existe novos produtos na tabela de FilialEstoque e insere na tabela de comparaEstoqueSinc
		$sql4 = "	INSERT INTO comparaEstoqueSinc SELECT FE.prodcodigo, produto.idtray,FE.idFilial,-1,'','','',0, getdate(),produto.compraPrc
						from filialEstoque AS FE 
						inner join gradeprod on gradeprod.codigograde=FE.prodcodigo
						inner join produto on produto.codigo=gradeprod.codigobase
						LEFT JOIN comparaEstoqueSinc AS CES
						ON FE.idFilial = CES.idFilial and FE.prodCodigo = CES.prodcodigo
						WHERE FE.idFilial=1
						AND CES.prodcodigo IS NULL and  produto.idtray is not null and produto.idTray<>'' ";
		$query4 = $this->db->query($sql4);



		$sql5 = "	SELECT G.idTrayGrupo, FE.prodCodigo AS produto_codigo , produto.idtray, FE.idFilial,FE.qtdDisp AS quantidade, produto.ncm,
								produto.vendaPrc,produto.codigo,produto.descricao2, produto.DESCRICAO as descricao, produto.codigoBarraProduto , produto.nomeProdutoLojaVirtual,
								produto.material as brand,  produto.vendaPrc as price, produto.compraPrc as compra,
								produto.descricaoLojaVirtual, produto.codigoVideoLojaVirtual, produto.peso,produto.Comprimento, produto.largura,produto.altura, produto.fotoCaminho,
								(select COALESCE(fotoCaminho2,'') +','+COALESCE(fotoCaminho3,'')+','+ COALESCE(fotoCaminho4,'')+','+COALESCE(fotoCaminho5,'') fotosSku FROM produtoFotos where prodcodigo = produto.codigo) fotosSku
						FROM filialEstoque AS FE
						inner join gradeprod on gradeprod.codigograde=FE.prodcodigo
						inner join produto on produto.codigo=gradeprod.codigobase
						LEFT JOIN GRUPO G ON G.GRUPO = PRODUTO.GRUPO
						INNER JOIN  comparaEstoqueSinc AS CES
						ON FE.idFilial = CES.idFilial and FE.prodCodigo = CES.prodCodigo
						WHERE (FE.qtdDisp <> CES.qtdDisp or produto.nomeProdutoLojaVirtual <> coalesce(CES.name,'') 
						or produto.vendaPrc <> coalesce(CES.price,0) or produto.compraPrc <> coalesce(CES.prccompra,0))
						AND FE.idFilial=1
						AND CES.idFilial=1
						and  produto.idtray is not null and  produto.idtray <> '' ";




		$query5 = $this->db->query($sql5);

		if ($query5->num_rows() > 0) {
			return $query5->result();
		} else {
			return false;
		}
	}


	public function ajustarEstoqueEnviado($codigoCad, $quantidade, $name, $price, $brand, $reference, $compra)
	{



		$sql = "		UPDATE comparaEstoqueSinc
							SET qtdDisp=?, dataEnvio=getdate(), name=?, reference=?, brand=?, price=?,prccompra=?
							WHERE prodCodigo=?
							AND idFilial=1 ";
		$query = $this->db->query($sql, array($quantidade, $name, $reference, $brand, $price, $compra, $codigoCad));



		if ($query) {
			return true;
		} else {
			return false;
		}
	}


	public function getConsumerKey()
	{



		$sql = "SELECT valor FROM parametro where parametro='CONSUMERKEYTRAY'";

		$query = $this->db->query($sql);

		if ($query->num_rows() > 0) {

			return $query->row()->valor;
		} else {

			return false;
		}
	}

	public function getConsumerSecret()
	{



		$sql = "SELECT valor FROM parametro where parametro='CONSUMERSECRETTRAY'";

		$query = $this->db->query($sql);

		if ($query->num_rows() > 0) {

			return $query->row()->valor;
		} else {

			return false;
		}
	}

	public function getCode()
	{



		$sql = "SELECT valor FROM parametro where parametro='CODETRAY'";

		$query = $this->db->query($sql);

		if ($query->num_rows() > 0) {

			return $query->row()->valor;
		} else {

			return false;
		}
	}

	public function getUrlLoja()
	{



		$sql = "SELECT valor FROM parametro where parametro='URLLOJATRAY'";

		$query = $this->db->query($sql);

		if ($query->num_rows() > 0) {

			return $query->row()->valor;
		} else {

			return false;
		}
	}


	public function getToken()
	{



		$sql = "SELECT top 1 * FROM tokenTray";

		$query = $this->db->query($sql);

		if ($query->num_rows() > 0) {

			return $query->result();
		} else {

			return false;
		}
	}

	// Consulta de produto

	public function getProduto($prodRef) 
	{
		$sql = "SELECT TOP 1 codigo, descricao, vendaprc, compraprc FROM produto WHERE codigo = ?";
		$query = $this->db->query($sql, array($prodRef));

		if ($query->num_rows() > 0) {
			return $query->row('codigo');
		} else {
			return false;
		}
	}

	// Consulta de pedido

	public function getPedido($idPedido)
	{

		$sql = "SELECT idvenda FROM venda WHERE codPedidoSiteVenda = ?";
		$query = $this->db->query($sql, array($idPedido));

		if ($query->num_rows() > 0) {
			return $query->row('idvenda');
		} else {
			return false;
		}
	}

	// Consulta de cliente

	public function getCliente($cpfCli)
	{

		$sql = "SELECT cpfcnpj FROM cliente WHERE cpfcnpj = ?";
		$query = $this->db->query($sql, array($cpfCli));

		if ($query->num_rows() > 0) {
			// Se o cliente já existe, chama a função putCli para atualizar as informações
			return true;
		} else {
			// Se o cliente não existe, chama a função setCliente para inserir um novo cliente
			return false;
		}
	}

	// UPDATE DE CLIENTE

	public function putCli($cliente)
	{
		$sql = "UPDATE cliente SET nome=?, jurRazaoSocial=?, endLogradouro=?, endNumero=?, endBairro=?, endCidade=?, endCep=?, endComplemento=?, endEstado=?, endFone=?, email=? WHERE cpfcnpj=?";

		$this->db->trans_start();
		$this->db->db_debug = false;

		$query = $this->db->query(
			$sql,
			array(
				$cliente['nome'],
				$cliente['nome'],
				$cliente['Endereco'],
				$cliente['Num'],
				$cliente['Bairro'],
				$cliente['Cidade'],
				$cliente['CEP'],
				$cliente['Compl'],
				$cliente['UF'],
				$cliente['Fone'],
				$cliente['Email'],
				$cliente['CPF']
			)
		);

		$this->db->trans_complete();
	}

	// INSERT CLIENTE

	public function setCliente($cliente)
	{
// print_r($cliente['nome']);
// die;

		$sql = "INSERT INTO cliente (nome,jurRazaoSocial,cpfcnpj,endLogradouro,endNumero,endBairro,
									endCidade,endCep,endComplemento,endEstado,endFone,email,datainclusao) 
		        				VALUES (?,?,?,?,?,?,?,?,?,?,?,?,'2024-04-05')";

		$this->db->trans_start();
		$this->db->db_debug = false;

		$query = $this->db->query($sql,
								array(
									$cliente['nome'],
									$cliente['nome'],
									$cliente['CPF'],
									$cliente['Endereco'],
									$cliente['Num'],
									$cliente['Bairro'],
									$cliente['Cidade'],
									$cliente['CEP'],
									$cliente['Compl'],
									$cliente['UF'],
									$cliente['Fone'],
									$cliente['Email']
			)
		);

		$this->db->trans_complete();
	}


	// MOSTRANDO NA VIEW


	public function obterTodosClientes() 
    {

        $sql = "SELECT nome, cpfcnpj, endLogradouro, endCep, endNumero, endComplemento, endBairro, endCidade, endEstado, endFone, email from cliente where datainclusao>='2024-04-05'";
        $query = $this->db->query($sql);
		
		return $query->result_array();
    }

    // TERMINO




	public function setToken($access_token, $refresh_token, $date_expiration_access_token, $date_expiration_refresh_token, $date_activated, $api_host, $store_id)
	{

		$sql = "delete from tokenTray";

		$query = $this->db->query($sql);


		$sql = "insert into tokenTray(access_token,refresh_token,date_expiration_access_token,date_expiration_refresh_token,date_activated,api_host,store_id) values (?,?,?,?,?,?,?)";


		$this->db->trans_start();

		$this->db->db_debug = false;

		$query = $this->db->query($sql, array($access_token, $refresh_token, $date_expiration_access_token, $date_expiration_refresh_token, $date_activated, $api_host, $store_id));

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->db_debug = true;
			return false;
		} else {
			$this->db->db_debug = true;
			return true;
		}
	}

	public function getGradeProdSemIdTray()
	{

		$sql = "SELECT 	top 1 
		
						produto.idTray 
						,produto.compraPrc 
						,produto.peso 
						,produto.Comprimento 
						,produto.largura 
						,produto.altura 
						,gradeProd.codigoGRADE
						,gradeProd.GRADEVENDAPRC
						,gradeProd.codigoBarra
						,gradeProd.idTrayGrade
						,COR.codigoCor
						,COR.DESCRICAOCOR
						,TAMANHO.codigoTamanho
						,TAMANHO.DESCRICAOTAMANHO
						,'' as mensagemAPI

				FROM produto 
						
					INNER JOIN GRADEPROD 	ON GRADEPROD.CODIGOBASE 	= PRODUTO.CODIGO
					INNER JOIN COR			ON COR.codigoCOR 			= gradeProd.codigoCOR
					INNER JOIN TAMANHO		ON TAMANHO.codigoTAMANHO 	= gradeProd.codigoTAMANHO
						
				where (GRADEPROD.idTrayGrade is null or GRADEPROD.idTrayGrade='') and produto.lojavirtual = 1 and GRADEPROD.gradeativo='1'";

		return $this->db->query($sql)->result();
	}

	public function putGradesProdSemIdTray($grade)
	{

		$sql = "UPDATE GRADEPROD SET idTrayGrade = ? where codigoGrade = ? ";

		$this->db->trans_start();

		$this->db->db_debug = false;


		$query = $this->db->query(
			$sql,
			array(
				$grade->idTrayGrade,
				$grade->codigoGRADE
			)
		);


		$this->db->trans_complete();

		$this->db->db_debug = true;


		return $this->db->trans_status();
	}

	public function getEstoqueGrade()
	{

		$query = $this->db->query("  IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='comparaEstoqueGradeSinc' AND xtype='U')
											CREATE TABLE comparaEstoqueGradeSinc
											(
												prodCodigo char(14) NOT NULL,
												prodCodigoGrade char(14) NOT NULL,
												idTray char(50) NOT NULL,
												idTrayGrade char(50) NOT NULL,
												idFilial smallint NOT  NULL,
												qtdDisp real NULL,
												price float,
												reference char(20),
												dataEnvio datetime,
												prccompra float
												PRIMARY  KEY(prodCodigo,prodCodigoGrade,idFilial)
											); ");

		if ($query) {
			$query = $this->db->query(
				" SELECT TOP 1 * FROM comparaEstoqueGradeSinc WHERE idFilial =2"
			)->row();

			if (empty($query)) {
				$sql = " INSERT INTO comparaEstoqueGradeSinc 
								SELECT 	produto.codigo -- prodCodigo
										,gradeprod.codigoGrade -- prodCodigoGrade
										,produto.idTray -- idTray
										,gradeprod.idTrayGrade -- idTrayGrade
										,FE.idFilial -- idFilial
										,-1 -- qtdDisp
										,0 -- price
										,'' -- reference
										,getdate() -- dataEnvio
										,produto.compraPrc -- prccompra
								FROM filialEstoque AS FE 

									INNER JOIN gradeprod ON gradeprod.codigograde = FE.prodcodigo 
									INNER JOIN produto ON produto.codigo = gradeprod.codigobase 

								WHERE 	FE.idFilial = 2
										AND idTrayGrade IS NOT NULL 
										AND idTrayGrade <> '' ";
				$this->db->query($sql);
			}
		}

		// verifica se existe novos produtos na tabela de FilialEstoque e insere na tabela de comparaEstoqueSinc
		$this->db->query("INSERT INTO comparaEstoqueGradeSinc 
		
								SELECT 	produto.codigo
										,gradeprod.codigoGrade
										,produto.idTray
										,gradeprod.idTrayGrade
										,FE.idFilial
										,-1
										,0
										,''
										,getdate()
										,produto.compraPrc

								FROM filialEstoque AS FE 

									INNER JOIN gradeprod on gradeprod.codigograde=FE.prodcodigo
									INNER JOIN produto on produto.codigo=gradeprod.codigobase
									LEFT JOIN comparaEstoqueGradeSinc AS CES ON FE.idFilial = CES.idFilial and FE.prodCodigo = CES.prodCodigoGrade
								
								WHERE 	FE.idFilial = 2
										AND CES.prodCodigoGrade IS NULL 
										AND gradeprod.idTrayGrade IS NOT NULL 
										AND gradeprod.idTrayGrade <> '' ");

		$sql = "SELECT  gradeprod.idTrayGrade
						,gradeprod.codigoBarra
						,gradeprod.codigoGrade
						,FE.idFilial
						,FE.qtdDisp AS quantidade
						,produto.idTray
						,produto.codigo
						,produto.descricao2
						,gradeProd.GRADEVENDAPRC as price
						,produto.compraPrc as compra
						,produto.codigoVideoLojaVirtual
						,produto.peso
						,produto.comprimento
						,produto.largura
						,produto.altura
						,gradeprod.gradeFotoCaminho
						,'' as mensagemAPI
				
				FROM filialEstoque AS FE
					
					INNER JOIN gradeprod ON gradeprod.codigograde=FE.prodcodigo
					INNER JOIN produto ON produto.codigo=gradeprod.codigobase
					INNER JOIN comparaEstoqueGradeSinc AS CES ON FE.idFilial = CES.idFilial and FE.prodCodigo = CES.prodCodigoGrade
				
				WHERE 	(
							FE.qtdDisp <> CES.qtdDisp 
							OR gradeprod.codigoBarra <> coalesce(CES.reference,'') 
							OR gradeProd.GRADEVENDAPRC <> coalesce(CES.price,0) 
							OR produto.compraPrc <> coalesce(CES.prccompra,0)
						)
						AND FE.idFilial =2
						AND CES.idFilial =2
						AND gradeprod.idTrayGrade IS NOT NULL 
						AND gradeprod.idTrayGrade <> '' ";

		return $this->db->query($sql)->result();
	}

	public function ajustarEstoqueGradeEnviado($dados)
	{

		$sql = "UPDATE comparaEstoqueGradeSinc
						SET qtdDisp=?,
						 	dataEnvio = getdate(), 
							reference=?, 
							price=?,
							prccompra=?
						WHERE 	prodCodigoGrade = ?
								AND idFilial =2 ";

		$this->db->trans_start();

		$this->db->db_debug = false;


		$query = $this->db->query(
			$sql,
			array(
				$dados->quantidade,
				trim($dados->codigoBarra),
				$dados->price,
				$dados->compra,
				trim($dados->codigoGrade)
			)
		);


		$this->db->trans_complete();

		$this->db->db_debug = true;


		return $this->db->trans_status();
	}
}
