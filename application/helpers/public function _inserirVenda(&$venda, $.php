	public function _inserirVenda(&$venda, $empresa, $UF)
	{

		$prIdVenda  = storedProcedure_SP_MANIPULA_ID_VENDA();
	
		$prIdFilial = getFilial();
		
		$venda->cds_idPedidoVenda = $prIdVenda;
		
		$sql_venda = "INSERT INTO venda (idVenda,
										 idFilial,
										 codPedidoSiteVenda,
										 funcionario,
										 funcionario1,
										 idDevolucao,
										 numSerieECF,
										 cupomNumero,
										 ccfNumero,
										 numeroDav,
										 numeroPV,
										 idCotacao,
										 cpfCnpjCliente,
										 nomeCliente,
										 tabela,
										 dataVenda,
										 dataHora,
										 subtotal,
										 total,
										 descVendaPerc,
										 descVendaValor,
										 notaFiscalNumero,
										 finalizado,
										 enviado,
										 enviadoInvalidado,
										 invalidado,
										 entregue,
										 PVEfetivada,
										 conferido,
										 idFilialEntrega,
										 encomenda,
										 entregaDeposito,
										 cupom,
										 obs,
										 idConsignacao)
										 VALUES 
										 (
											?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
										 )";


		$sqlProd = "INSERT INTO vendaProd (idVendaProd,
											idVenda,
											idFilial,
											prodCodigo,
											prodDescricao,
											prodImpNumSerie,
											prodCOO,
											prodCCF,
											valorTotal,
											prodCodigoUsaGE,
											numSerieUsaGE,
											qtd,
											qtdCanc,
											qtdEncom,
											qtdEncomPend,
											qtdVendaConf,
											prcUnit,
											descPerc,
											descValor,
											descontoAutorizacao,
											prodEntregue,
											unidadeValor,
											percComissaoProd,
											numeroItemECF,
											codTotECF,
											prodUnidade,
											invalidadoItem,
											baseCalculoReduzida,
											acrescimo,
											prcUnitOrig,
											prodNCM,
											valAproxTributos,
											numPedCompraNfe,
											itemPedCompraNfe,
											cst,
											csosn,
											prodCfop,
											tabelaprecoProd,
											icms,
											prodMetragem,
											baseCalculoIcmsSubst,
											icmsSubst) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

													


		$this->db->trans_start();

		$this->db->db_debug = false;

		
		//INSERIR VENDA 
		$this->db->query($sql_venda, array($prIdVenda,
											$prIdFilial,
											$venda->id,
											'COMERCIAL',
											'',
											0,
											'',
											'',
											'',
											'',
											NULL, 
											NULL,
											$venda->cds_cpfCnpj,
											$venda->cds_nomeCliente,
											'CONSUMIDOR',
											date('Y-m-d'),
											date('Y-m-d H:i:s'),
											$venda->cds_subtotal, 
											$venda->cds_subtotal,
											0,
											0,
											'',
											'0',
											'0',
											'0',
											'0',
											'0',
											'0',
											'0',
											$prIdFilial, 
											'0',
											0,
											0,
											$venda->id,
											''));

		$i = 1;
		
		$qty_orderedTot = 0;

		foreach($venda->line_items as $_produto)
		{

			$sql_existe_prod = "SELECT codigoGrade, DESCRICAOGRADE, UNIDADE, ncm, cst, csosn, 0 qtd, 0 price
									FROM GRADEPROD 
								INNER JOIN PRODUTO P ON P.codigo = GRADEPROD.CODIGOBASE
									WHERE idWooGrade = ? AND gradeAtivo = '1'";
									
			$prod = $this->db->query($sql_existe_prod, array(trim($_produto->product_id)));
			

			$produto = $prod->row();
			$produto->qtd   = $_produto->quantity;
			$produto->price = $_produto->price;

			$qty_orderedTot += $_produto->quantity;
			
			$produto = $this->obterValores($produto, $empresa, $venda->billing->state);

			$produto->price = ($_produto->subtotal / $produto->qtd);

			$this->db->query($sqlProd, array($i,
										$prIdVenda,
										$prIdFilial,
										$produto->codigoGrade,
										$produto->DESCRICAOGRADE,
										'',
										'',
										'',
										$produto->qtd * $produto->price,
										'',
										'',
										$produto->qtd,
										0,
										0,
										0,
										0,
										$produto->price,
										0,
										0,
										'',
										1,
										1,
										0,
										0,
										'',
										$produto->UNIDADE, 
										0 ,
										$produto->baseCalculo, 
										0,
										$produto->price,
										$produto->ncm,
										0,
										'',
										'',
										$produto->cst,
										$produto->csosn,
										$produto->cfop,
										'CONSUMIDOR',
										$produto->icms,
										NULL,
										0,
										0));

			$i++;

		}



		$this->db->trans_complete();

		if ($this->db->trans_status() === TRUE)
		{
			
			$this->db->trans_start();

			$this->db->db_debug = false;

			$existe_venda = "SELECT idVenda FROM venda WHERE idVenda = ? AND idFilial = ?";

			$result_existe_venda = $this->db->query($existe_venda, array($prIdVenda, $prIdFilial));	
			
			if($result_existe_venda->num_rows() > 0)
			{

				$formaPGTO = 'PENDENCIA';

				$_forma = $this->getInfoFormaPagamento($formaPGTO);
				
				$dados['post']['dinheiro']   = 0;
				
				$dados['post']['totalValorFormaPagamentoPrazo'] = number_format($venda->cds_total, 3, ',', '.');

				$_forma->intervaloPrest = (empty($_forma->intervaloPrest)) ? 1 : $_forma->intervaloPrest;
		
				if($_forma->entrada == 1 && $_forma->parcelaEntrada != 1)
				{
					
					$dt = date('d/m/Y', strtotime('+'.$_forma->entradaIntervaloPrest.' days'));

				}
				else if($_forma->entrada == 1 && $_forma->parcelaEntrada == 1)
				{
					
					$dt = date('d/m/Y');

				}
				else
				{

					if($_forma->dataIgual != 1)
					{
						
						$dt = date('d/m/Y', strtotime('+'.$_forma->intervaloPrest.' days'));

					}
					else
					{
						$dt = date('d/m/Y', strtotime('+1 month'));
					}

				}

				$dados['post']['dataVencto']         = array($dt);
				$dados['post']['gridValor']          = array(number_format($venda->cds_total, 3, ',', '.'));
				$dados['post']['formaNumPrestacoes'] = array(1);
				$dados['post']['formaPrestacoes']    = array(1);
				$dados['post']['formaPGTO']          = array($formaPGTO);
				$dados['post']['formaBanco']         = array('');
				$dados['post']['formaAgencia']       = array('');
				$dados['post']['formaAberto']        = array('');
				$dados['post']['formaNum']           = array('');
				$dados['post']['formaLote']          = array('');
				$dados['post']['inadimplencia']      = array($_forma->inadiplencia);

				
				$dados['post']['ValorTroco'] = 0;
				$dados['post']['credito'] = 0;
				$dados['post']['descontoTotalPerce'] = number_format($venda->cds_descPerc, 3, ',', '.'); 
				$dados['post']['descontoTotalValor'] = number_format($venda->cds_descValor, 3, ',', '.'); 
				$dados['post']['descontoAutorizacao'] = '';
				$dados['post']['cpfNaNota_h'] = '';
				$dados['post']['cupomBonus'] = '';
				$dados['post']['VLRADICIONALMONTAGEM'] = 0;
				$dados['post']['VERSAOOTICA'] = 0;
				
				$dados['post']['cpfClientePadrao'] = getConfiguracao()->cpfClientePadrao;
				$dados['post']['idVenda'] = $prIdVenda;
				$dados['post']['cliente'] = $venda->cds_cpfCnpj;
				$dados['post']['nomeCliente'] = $venda->cds_nomeCliente;
				$dados['post']['totalFinal'] = number_format($venda->cds_total, 3, ',', '.');
				$dados['post']['acrescimo']  = number_format($venda->cds_acrescimo, 3, ',', '.');

			
				$lancamentoContasReceberFinalizarCaixa = $this->lancamentoContasReceberFinalizarCaixa($dados);
		
			
				$this->db->trans_complete();
				
				if($lancamentoContasReceberFinalizarCaixa)
				{
					
					if( getParametro('USANFEMIGRATE')->valor == 1 )
					{

						$data['nfe']['observacao']        = '';
						$data['nfe']['nomeTransportador'] = '';
						$data['nfe']['hiddenIdVenda']     = $prIdVenda;
						$data['nfe']['hiddenCpfCnpj']     = $venda->cds_cpfCnpj;
						$data['nfe']['pesoBruto']         = 0;
						$data['nfe']['pesoLiquido']       = 0;
						$data['nfe']['hiddenTipoNf']      = 'NE';
						$data['nfe']['freteTipo']         = 0;
						$data['nfe']['quantidade']        = number_format($qty_orderedTot, 3, ',', '.');
						$data['nfe']['frete']             = number_format($venda->cds_acrescimo, 3, ',', '.');
						$data['nfe']['serieNf']           = $this->getSerieNf()->valor;
						$data['nfe']['cfop']              = (trim($UF) == trim($empresa->estado)) ? "5.102" : "6.102";
						$data['nfe']['natureza']          = $this->getNaturezaCfopProdutoVenda($prIdVenda); 
	
						$retorno = $this->inserirNfeVenda($data);
	
						if($retorno)
						{
	
							$_retorno = $this->emitirNfevendaIntegracao($venda->cds_cpfCnpj, $prIdVenda, $retorno);
	
							$venda->cds_mensagem = ('Venda de Nº '.$prIdVenda.' finalizada com sucesso! '. $_retorno['mensagem']);
							$venda->cds_finalizado = 'SIM';
	
	
						}
						else
						{
							
							$venda->cds_mensagem = ('Venda de Nº '.$prIdVenda.' finalizada com sucesso! Erro ao inserir a NF-e!');
							$venda->cds_finalizado = 'SIM';
	
						}


					}
					else
					{

						$venda->cds_mensagem = ('Venda de Nº '.$prIdVenda.' finalizada com sucesso!');
						$venda->cds_finalizado = 'SIM';
	
					}

				}
				else
				{

					$venda->cds_mensagem = 'Erro ao Finalizar Venda.';

				}

				
			}
			else
			{
				
				$this->db->trans_complete();
				
				$venda->cds_mensagem = ('Venda de Nº '.$prIdVenda.' não encontrada.');

				
			}

		}		
		else
		{	

			$this->db->db_debug = true;

			$venda->cds_mensagem = 'Venda inserida com sucesso!';

		}

	}

