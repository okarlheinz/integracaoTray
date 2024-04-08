<?php

defined('BASEPATH') OR exit('No direct script access allowed');


	if(!function_exists('executarScriptG'))
	{
		function executarScriptG()
		{
			
			$CI =& get_instance();
			$retorno = $CI->scriptGDAO->executarScriptG();

			habilitaEmissaoMigrate();
			return $retorno;

		}	
	}

	if(!function_exists('getEmpresa'))
	{
		
		function getEmpresa()
		{
			$CI =& get_instance();
			return $CI->geraldao->getEmpresa();		
		}
		
	}

	if(!function_exists('getCliente'))
	{

		function getCliente($cpf_cnpj)
		{

			$CI =& get_instance();
			return $CI->geraldao->getCliente($cpf_cnpj);

		}

	}

	if(!function_exists('getFornecedor'))
	{

		function getFornecedor($nomefantasia)
		{

			$CI =& get_instance();
			return $CI->geraldao->getCliente($nomefantasia);

		}

	}

	if(!function_exists('getComissaoVendasDevolucao'))
	{
		
		function getComissaoVendasDevolucao($data)
		{
			$CI =& get_instance();
			return $CI->vendasdao->getComissaoVendasDevolucao($data);		
		}
		
	}

	if(!function_exists('getAuxFuncionarioComissao'))
	{
		
		function getAuxFuncionarioComissao($funcionario)
		{
			$CI =& get_instance();
			return $CI->geraldao->getAuxFuncionarioComissao($funcionario);		
		}
		
	}

	if(!function_exists('getProduto'))
	{
		
		function getProduto($codigo)
		{
			$CI =& get_instance();
			return $CI->produtodao->getProduto($codigo);		
		}
		
	}


	if(!function_exists('gravarAcesso'))
	{
		
		function gravarAcesso()
		{
			$CI =& get_instance();
			return $CI->geraldao->gravarAcesso();		
		}
		
	}


	if(!function_exists('getConfiguracao'))
	{
		
		function getConfiguracao()
		{
			$CI =& get_instance();
			return $CI->geraldao->getConfiguracao();		
		}
		
	}

	if(!function_exists('getFuncionarioCaixa'))
	{
		
		function getFuncionarioCaixa()
		{
			$CI =& get_instance();
			return  $CI->geraldao->getFuncionarioCaixa();
		}
		
	}

	if(!function_exists('permissaoAcesso'))
	{
		
		function permissaoAcesso()
		{
			
			$CI =& get_instance();
			$valor = $CI->session->userdata('MODALIDADE');
			if($valor > 1)
			{
				
				redirect(base_url()."acesso-negado-403");
				
			}
			
			
		}
		
	}

	if(!function_exists('permissaoAcessoPreVenda'))
	{
		
		function permissaoAcessoPreVenda()
		{
			
			$valor = getParametro('USAPREVENDA');

			if($valor->valor != 1)
			{
				
				redirect(base_url()."acesso-negado-403");
				
			}
			
			
		}
		
	}	



	if(!function_exists('formatarHoraPicoVenda'))
	{
		
		function formatarHoraPicoVenda($hora)
		{

			$tamanho = strlen($hora);

			if($tamanho == 1)
			{
				$hora = "0".$hora.':00';

				$novaHora = (intval($hora + 1));

				$novoTamanho = strlen($novaHora);
				
				if($novoTamanho == 1)
				{

					$novaHora = "0".$novaHora.":00";

				}
				else
				{

					$novaHora = $novaHora.":00";

				}
				
			}
			else
			{
				$hora = $hora.':00';

				$novaHora = (intval($hora + 1));

				$novoTamanho = strlen($novaHora);
				
				if($novoTamanho == 1)
				{

					$novaHora = "0".$novaHora.":00";

				}
				else
				{

					$novaHora = $novaHora.":00";

				}
				
			}

			return $hora.' às '.$novaHora;
			
		}
		
	}			


	if(!function_exists('ConverterDataBrparaSQL'))
	{

		function ConverterDataBrparaSQL($data)
		{

			if($data != "")
			{
				$Retorna = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2)." ".substr($data,11,8);
			}
			else
			{
				$Retorna = $data;
			}
			return $Retorna;
			
		}

	}

	if(!function_exists('ConverterDataSQLparaBr'))
	{

		function ConverterDataSQLparaBr($data)
		{

			if($data != "")
			{
				$Retorna = substr($data,8,2)."/".substr($data,5,2)."/".substr($data,0,4);
			}
			else
			{
				$Retorna = $data;
			}
			return $Retorna;
			
		}

	}
	

	if(!function_exists('ConverterDataHoraSQLparaBr'))
	{

		function ConverterDataHoraSQLparaBr($data)
		{

			if($data != "")
			{
				$Retorna = substr($data,8,2)."/".substr($data,5,2)."/".substr($data,0,4)." ".substr($data,11,2).":".substr($data,14,2).":".substr($data,17,2);
			}
			else
			{
				$Retorna = $data;
			}
			return $Retorna;
			
		}

	}

	if(!function_exists('validateDate'))
	{

		function validateDate($date, $format = 'Y-m-d H:i:s')
		{
			
			$d = DateTime::createFromFormat($format, $date);
			
			return $d && $d->format($format) == $date;
			
		}	
		
	}

	if(!function_exists('converterMoedaparaBanco'))
	{

		function converterMoedaparaBanco($get_valor) 
		{
			
			$source = array('.', ',');
			
			$replace = array('', '.');
			
			$valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
			
			return $valor; //retorna o valor formatado para gravar no banco
			
		}	

	}

	function criptografar($valor, $key = 'cds1234')
	{

		$valor = $valor.$key;

		$valor = base64_encode(base64_encode(base64_encode(base64_encode($valor))));

		return $valor;

	}

	function descriptografar($valor)
	{

		$valor = base64_decode(base64_decode(base64_decode(base64_decode($valor))));

		return substr($valor, 0, -7);

	}	

	if(!function_exists('object_to_array'))
	{
		function object_to_array($data)
		{
		    if (is_array($data) || is_object($data))
		    {
		        $result = array();
		        foreach ($data as $key => $value)
		        {
		            $result[$key] = object_to_array($value);
		        }
		        return $result;
		    }
		    return $data;
		}
	}
	
	function getChequeFinanceiroOutros($data, $tipo)
	{
		
		$CI =& get_instance();
		
		$retorno = $CI->vendasdao->getChequeFinanceiroOutros($data);
		
		foreach($retorno as $row)
		{
			
			$categoria = $row->categoria;
			/*
			if($categoria == 'Cartão              ' && $tipo == 'Cartão              ')
			{
				return $row->totalValor;
				
			}		
			*/
			switch($categoria)
			{
				
				case ($categoria == 'Cheque              ' && $tipo == 'Cheque              '):
					return $row->totalValor;
					break;
					
				case ($categoria == 'Cartão              ' && $tipo == 'Cartão              '):
					return $row->totalValor;
					break;				
					
				case ($categoria == 'Financeiro          ' && $tipo == 'Financeiro          '):
					return $row->totalValor;
					break;

				case ($categoria == 'Outros              ' && $tipo == 'Outros              '):
					return $row->totalValor;
					break;			
					
			}		
			
		}
		
	}

	function atualizarIBPT($get_estado)
	{
		
		$CI =& get_instance();
		return $CI->ibptdao->atualizarIBPT();		
		//select idEmpresa FROM empresa
		
	}

	function getFilial()
	{
		
		$CI =& get_instance();
		return $CI->geraldao->getFilial();		
		//select idEmpresa FROM empresa
		
	}

	function getInfoFilial($idFilial)
	{
		
		$CI =& get_instance();
		return $CI->geraldao->getInfoFilial($idFilial);		
		
	}	
	
	function getNomeFilial($idFilial)
	{

		$CI =& get_instance();
		return $CI->vendasdao->getNomeFilial($idFilial);
		
	}

	function getNomeFiliais($idFilial)
	{
		
		$CI =& get_instance();
		return $CI->geraldao->getNomeFiliais($idFilial);
		
	}

	function getNomeFornecedor($idFornecedor)
	{
		
		$CI =& get_instance();
		return $CI->geraldao->getNomeFornecedor($idFornecedor);
		
	}

	function getDadosFilial($data, $mes)
	{
		
		$CI =& get_instance();
		return $CI->vendasdao->getDadosVendas($data, $mes);
		
	}

	function getParametro($string)
	{
		
		$CI =& get_instance();
		return $CI->geraldao->getParametro($string);
		
	}

	function getFiliaisPendentes()
	{
		
		$CI =& get_instance();
		return $CI->geraldao->getFiliaisPendentes();
		
	}

	function getFiliaisPendentes2()
	{
		
		$CI =& get_instance();
		return $CI->geraldao->getFiliaisPendentes2();
		
	}


    function storedProcedure_SP_MANIPULA_ID_VENDA()
    {
		
		$CI =& get_instance();
		
		$tsql = "{call SP_MANIPULA_ID_VENDA(?)}";

		$id = 1;

		$params = array( array($id, SQLSRV_PARAM_INOUT) );


		$query = sqlsrv_query($CI->db->conn_id, $tsql, $params);

		sqlsrv_next_result($query);

		return $id;
            
    }
	
	function storedProcedure_SP_MANIPULA_ID_PRE_VENDA()
	{

		$CI =& get_instance();
		
		$tsql = "{call SP_MANIPULA_ID_PRE_VENDA(?)}";

		$id = 1;

		$params = array( array($id, SQLSRV_PARAM_INOUT) );


		$query = sqlsrv_query($CI->db->conn_id, $tsql, $params);

		sqlsrv_next_result($query);

		return $id;	
		
	}

    function storedProcedure_SP_MANIPULA_ID_DEV_VENDA()
    {
		
		$CI =& get_instance();
		
		$tsql = "{call SP_MANIPULA_ID_DEV_VENDA(?)}";

		$id = 1;

		$params = array( array($id, SQLSRV_PARAM_INOUT) );

		$query = sqlsrv_query($CI->db->conn_id, $tsql, $params);

		sqlsrv_next_result($query);

		return $id;
            
    }
	function limparSessionRelatorios()
	{

		/* DESTROI SESSÃO DOS DADOS DO RELATÓRIO */

		$sessaoRelatorios = array(
                   'DESEMPENHO_VENDA'       => '',
                   'DESEMPENHO_DEVOLUCAO'   => '',
                   'VENDAS_POR_MES'         => '',
				   'VENDAS_POR_MES_FILIAL'  => '',
				   'VENDAS_POR_DIA'         => '',
				   'VENDAS_PICO_DE_VENDAS'  => ''
					);
				
		$CI =& get_instance();

		$CI->session->set_userdata($sessaoRelatorios);
				
		$CI->session->unset_userdata($sessaoRelatorios);

	}

	// Função para criptografar a url

 	function encode_url($string, $key="cds95@124_", $url_safe=TRUE)
	{

	    $CI =& get_instance();
	    $ret = $CI->encrypt->encode($string, $key);

	    if ($url_safe)
	    {
	        $ret = strtr(
	                $ret,
	                array(
	                    '+' => '.',
	                    '=' => '-',
	                    '/' => '~'
	                )
	            );
	    }

	    return $ret;

	}

  	function decode_url($string, $key = "cds95@124_")
	{

	    $CI =& get_instance();
	    $string = strtr(
	            $string,
	            array(
	                '.' => '+',
	                '-' => '=',
	                '~' => '/'
	            )
	        );

	    return $CI->encrypt->decode($string, $key);

	}	

function removeAcentos($value) 
{

    $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
    $to = "aaaaeeiooouucAAAAEEIOOOUUC";
             
    $caracteres = array("'",'<','>','!','@','#','$','¨','&','*','(',')','_','=','{','}','[',']','?',';',':','|','*','"','~','^','´','`','¨','æ','Æ','ø','£','Ø','ƒ','ª','¿','®','½','¼','ß','µ','þ','ý','Ý','£','§','¹','²','³','£','¢','¬');

    $keys = array();
    $values = array();
    preg_match_all('/./u', $from, $keys);
    preg_match_all('/./u', $to, $values);
    $mapping = array_combine($keys[0], $values[0]);
    $value = strtr($value, $mapping);
             
    $value = str_replace($caracteres,"",$value);
    
    return $value;
    
}

function removeAcentosOBS($value) 
{

    $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
    $to = "aaaaeeiooouucAAAAEEIOOOUUC";
             
    $caracteres = array("'",'<','>','!','@','#','$','¨','&','*','(',')','_','=','{','}','[',']','?',';','|','*','"','~','^','´','`','¨','æ','Æ','ø','£','Ø','ƒ','ª','¿','®','½','¼','ß','µ','þ','ý','Ý','£','§','¹','²','³','£','¢','¬');

    $keys = array();
    $values = array();
    preg_match_all('/./u', $from, $keys);
    preg_match_all('/./u', $to, $values);
    $mapping = array_combine($keys[0], $values[0]);
    $value = strtr($value, $mapping);
             
    $value = str_replace($caracteres,"",$value);
    
    return $value;
    
}

function removerCaracterCodigo($codigo)
{

    $codigo = str_replace(" ", "§", $codigo);
    $codigo = str_replace(".", "₩", $codigo);
    $codigo = str_replace("'", "¤", $codigo);
    $codigo = str_replace("/", "☆", $codigo);
    
    return $codigo;

}

function apagarArquivosPasta($dir) 
{ 	
	//Apaga somente os arquivos da pasta e subpastas, não apaga as subpastas.

	 $files = array_diff(scandir($dir), array('.','..')); 
      foreach ($files as $file) { 
        (is_dir("$dir/$file")) ? apagarArquivosPasta("$dir/$file") : unlink("$dir/$file"); 
      } 

} 

function diasemana($data) 
{

	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);

	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

	switch($diasemana) 
	{

		case "0": $diasemana = "Domingo";       break;
		case "1": $diasemana = "Segunda-Feira"; break;
		case "2": $diasemana = "Terça-Feira";   break;
		case "3": $diasemana = "Quarta-Feira";  break;
		case "4": $diasemana = "Quinta-Feira";  break;
		case "5": $diasemana = "Sexta-Feira";   break;
		case "6": $diasemana = "Sábado";        break;

	}

	return "$diasemana";

}

function tratarData($isMobile, $dataInicial, $dataFinal) 
{

	$CI =& get_instance();

	// Converte a Data para formato BR se for via mobile
	if($isMobile)
	{

		$dataInicial = ConverterDataSQLparaBr($dataInicial);

		$dataFinal   = ConverterDataSQLparaBr($dataFinal);

	}

	// Verifica se está vazio as datas
	if(empty($dataInicial) || empty($dataFinal))
	{
		
		$CI->session->set_flashdata("msg","<div style='border:0px solid red;background:darkred;color:white;padding:5px;margin-top:10px;margin-bottom:10px;float:left;font-size:12px;margin-left:30px;'>Selecione a data inicial e final</div>");
		
		return false;
		
	}			

	// Converte as datas para formato sql para validação abaixo
	$format_data_inicio = ConverterDataBrparaSQL($dataInicial);
	
	$format_data_fim    = ConverterDataBrparaSQL($dataFinal);

	$format_data_inicio = trim($format_data_inicio);
	
	$format_data_fim    = trim($format_data_fim);
	
	// Verificar se é valido
	if((!validateDate($format_data_inicio, "Y-m-d")) || (!validateDate($format_data_fim, "Y-m-d"))) 
	{
		
		$CI->session->set_flashdata("msg","<div style='border:0px solid red;background:darkred;color:white;padding:5px;margin-top:10px;margin-bottom:10px;float:left;font-size:12px;margin-left:30px;'>Formato da data inválido</div>");
		
		return false;
		
	}	
					
	//  Verifica se a Data Inicial é maior que a Data Final 
	if($format_data_inicio > $format_data_fim)
	{
		
		$CI->session->set_flashdata("msg","<div style='border:0px solid red;background:darkred;color:white;padding:5px;margin-top:10px;margin-bottom:10px;float:left;font-size:12px;margin-left:30px;'>Data inicial não pode ser maior que a data final</div>");
		
		return false;
		
	}	

	return true;

}

if(!function_exists('inserirPedidoMagento'))
	{
		
	function inserirPedidoMagento($idPedido,$proxy,$sessionId,$cliente)
	{

		$CI =& get_instance();
		  return $CI->integracaodao->inserirPedido2($idPedido,$proxy,$sessionId,$cliente);		
	}
		
}

if(!function_exists('truncateValor'))
{
	function truncateValor($val, $f="0")
	{
	    if(($p = strpos($val, '.')) !== false) {
	        $val = floatval(substr($val, 0, $p + 1 + $f));
	    }
	    return $val;
	}	
}

if(!function_exists('formatarData'))
{

	function formatarData($data)
	{

		$dia = substr($data, 0, 2);
		$mes = substr($data, 3, 2);
		$ano = substr($data, 8, 2);

		$data = $dia."/".$mes."/".$ano;

		return$data;

	}

}

if(!function_exists('mask'))
{

	function mask($val, $mask)
	{
		
		$maskared = '';
		
		$k = 0;
		
		for($i = 0; $i<=strlen($mask)-1; $i++)
		{

			if($mask[$i] == '#')
			{
				if(isset($val[$k]))
				$maskared .= $val[$k++];
			}
			else
			{
				if(isset($mask[$i]))
				$maskared .= $mask[$i];
			}

		}
		
		return $maskared;

	}


}


function numberToColumnName($number)
{
    $abc = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $abc_len = strlen($abc);

    $result_len = 1; // how much characters the column's name will have
    $pow = 0;
    while( ( $pow += pow($abc_len, $result_len) ) < $number )
    {
        $result_len++;
    }

    $result = "";
    $next = false;
    // add each character to the result...
    for($i = 1; $i<=$result_len; $i++)
    {
        $index = ($number % $abc_len) - 1; // calculate the module

        // sometimes the index should be decreased by 1
        if( $next || $next = false )
        {
            $index--;
        }

        // this is the point that will be calculated in the next iteration
        $number = floor($number / strlen($abc));

        // if the index is negative, convert it to positive
        if( $next = ($index < 0) ) 
        {
            $index = $abc_len + $index;
        }

        $result = $abc[$index].$result; // concatenate the letter
    }
    return $result;
}


function getNomeTamanho($key)
{
	$CI =& get_instance();
	return $CI->geraldao->getNomeTamanho($key);
}




if(!function_exists('criaTabelasAgendamentoVisita'))
{
	function criaTabelasAgendamentoVisita()
	{
		
		$CI =& get_instance();
		
		$retorno = $CI->agendaDAO->criaTabelas();

		return $retorno;

	}	
}

if(!function_exists('converteCaracterEspecialParaComum'))
{

	function converteCaracterEspecialParaComum($get_valor) 
	{
		
		$source = array('₩' ,'☆', '¤', '§');
			
		$replace = array('.', '/', "'",' ');
			
		$valor = str_replace($source, $replace, $get_valor); 
		
		return $valor; //retorna o valor formatado para gravar no banco
		
	}	

}

if(!function_exists('converteCaracterComumParaEspecial'))
{

	function converteCaracterComumParaEspecial($get_valor) 
	{
		
		$source = array('.', '/', "'",' ');
			
		$replace = array('₩' ,'☆', '¤', '§');
			
		$valor = str_replace($source, $replace, $get_valor); 
		
		return $valor; //retorna o valor formatado para gravar no banco
		
	}	

}



if(!function_exists('formatarDataAnoDoisNumeros'))
{

	function formatarDataAnoDoisNumeros($data)
	{


		$CI =& get_instance();

		$isMobile  = $CI->agent->is_mobile();
		if($isMobile)
		{

			$dia = substr($data, 8, 2);
			$mes = substr($data, 5, 2);
			$ano = substr($data, 2, 2);
			
			$data = $dia."/".$mes."/".$ano;


		}
		else
		{

			$dia = substr($data, 0, 2);
			$mes = substr($data, 3, 2);
			$ano = substr($data, 8, 2);

			$data = $dia."/".$mes."/".$ano;

		}	

		// a data retornada sai assim DD/MM/AA
		return $data;

	}

}



if(!function_exists('formatarDataAnosDoisNumerosParaQuatro'))
{

	function formatarDataAnosDoisNumerosParaQuatro($data)
	{


		$CI =& get_instance();

		$isMobile  = $CI->agent->is_mobile();
		
		if($isMobile)
		{

			$ano = substr($data, 6, 2);
			$mes = substr($data, 3, 2);
			$dia = substr($data, 0, 2);
			
			$data = $dia."/".$mes."/"."20".$ano;


		}
		else
		{

			$dia = substr($data, 0, 2);
			$mes = substr($data, 3, 2);
			$ano = substr($data, 6, 2);

			$data = $dia."/".$mes."/"."20".$ano;

		}	

		// a data retornada sai assim DD/MM/AAAA
		return $data;

	}

}

if(!function_exists('validaCPFCNPJ'))
{

	function validaCPFCNPJ($cpfCnpj)
	{


		$CI =& get_instance();
		$USARNFCEHOMOLOGACAO 	= getParametro('USARNFCEHOMOLOGACAO')->valor;

		// se estiver em ambiente de homologação e o CNPJ ultilizado foi esse não ira retornar como CNPJ invalido
		if(((trim($cpfCnpj) == "99.999.999/0001-91") || (trim($cpfCnpj) == "99999999000191") ) &&  $USARNFCEHOMOLOGACAO == 1)
		{
			return true;
		}

		$cpfCnpj = preg_replace("/[^0-9]/", "", $cpfCnpj);

        //se for CPF
        if(strlen($cpfCnpj) == 11) 
        {
			// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
		    if (strlen($cpfCnpj) != 11    || $cpfCnpj == '00000000000' || $cpfCnpj == '11111111111' || $cpfCnpj == '22222222222' || 
		    	$cpfCnpj == '33333333333' || $cpfCnpj == '44444444444' || $cpfCnpj == '55555555555' || $cpfCnpj == '66666666666' || 
		    	$cpfCnpj == '77777777777' || $cpfCnpj == '88888888888' || $cpfCnpj == '99999999999')
			{
				return false;
		    }
			else
			{   // Calcula os números para verificar se o cpfCnpj é verdadeiro
		        for ($t = 9; $t < 11; $t++) {
		            for ($d = 0, $c = 0; $c < $t; $c++) {
		                $d += $cpfCnpj{$c} * (($t + 1) - $c);
		            }
		 
		            $d = ((10 * $d) % 11) % 10;
		 
		            if ($cpfCnpj{$c} != $d) {
		                return false;
		            }
		        }
		        return true;
		    }
        }

	    // se for CNPJ
	    if(strlen($cpfCnpj) == 14) 
	    {
	       
		 	$cpfCnpj = preg_replace('/[^0-9]/', '', (string) $cpfCnpj);
			// Valida tamanho
			if (strlen($cpfCnpj) != 14)
			{
				return false;
			}

			// Valida primeiro dígito verificador
			for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
			{
				$soma += $cpfCnpj{$i} * $j;
				$j = ($j == 2) ? 9 : $j - 1;
			}

			$resto = $soma % 11;

			if ($cpfCnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
				
				return false;
			
			// Valida segundo dígito verificador
			for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
			{
				$soma += $cpfCnpj{$i} * $j;
				$j = ($j == 2) ? 9 : $j - 1;
			}
			
			$resto = $soma % 11;

			if($cpfCnpj{13} == ($resto < 2 ? 0 : 11 - $resto))
			{
				return true;
			}
			else
			{
				return false;
			}	
	    }
        else
        {
        	return false;
        }	
	}

}


if(!function_exists('removeAcentosXML'))
{
	function removeAcentosXML($value) 
	{

	    $from 	= "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
	    $to 	= "aaaaeeiooouucAAAAEEIOOOUUC";

	             
	    $caracteres = array("'",'<','>','!','@','#','$','¨','*','(',')','_','=','{','}','[',']','?',';','|','*','"','~','^','´','`','¨','æ','Æ','ø','£','Ø','ƒ','ª','¿','®','½','¼','ß','µ','þ','ý','Ý','£','§','¹','²','³','£','¢','¬');

	    $keys = array();
	    $values = array();
	    preg_match_all('/./u', $from, $keys);
	    preg_match_all('/./u', $to, $values);

	    $mapping = array_combine($keys[0], $values[0]);
	    $value = strtr($value, $mapping);
	             
	    $value = str_replace($caracteres,"",$value);
	    $value = str_replace("&", 'E', $value);
	    $value = str_replace("º", '.', $value);

	    return $value;
	}
	    
	
}


function getValeCliente($cpfcnpj)
{
	
	$CI =& get_instance();
	return $CI->geraldao->getValeCliente($cpfcnpj);		

	
}	


function getFiliaisPendentesEspecifica($idFilial)
{
	
	$CI =& get_instance();
	return $CI->geraldao->getFiliaisPendentesEspecifica($idFilial);
	
}
function getFiliaisPendentesEspecifica2($idFilial)
{
	
	$CI =& get_instance();
	return $CI->geraldao->getFiliaisPendentesEspecifica2($idFilial);
	
}



if(!function_exists('calculaDiasSistemaDemo'))
{
	function calculaDiasSistemaDemo($dataCriacao) 
	{

		$dataFinal = date('Ymd');
		$dataCriacao = substr($dataCriacao, 0, 10);
		$dataCriacao = explode("-", $dataCriacao);
		$dataCriacao = $dataCriacao[0].'-'.$dataCriacao[1].'-'.$dataCriacao[2];
		
		$d1 = strtotime($dataCriacao); 
		$d2 = strtotime($dataFinal);
		
		$dataFinal = ($d2 - $d1) /86400;
		if($dataFinal < 0)
		{
			$dataFinal = $dataFinal * -1;
		}


		return $dataFinal;
	}
}



if(!function_exists('habilitaEmissaoMigrate'))
{
	function habilitaEmissaoMigrate() 
	{
		$USANFCEMIGRATE 	= (getParametro('USANFCEMIGRATE') != NULL)? getParametro('USANFCEMIGRATE')->valor : 0 ;
		$USANFEMIGRATE 		= (getParametro('USANFEMIGRATE') != NULL)? getParametro('USANFEMIGRATE')->valor : 0 ;
		$USANFSEMIGRATE 	= (getParametro('USANFSEMIGRATE') != NULL)? getParametro('USANFSEMIGRATE')->valor : 0 ;
		
		$CI =& get_instance();

		if($USANFCEMIGRATE == 1 || $USANFEMIGRATE == 1 || $USANFSEMIGRATE == 1)
		{

			$retorno = $CI->scriptGDAO->habilitaEmissaoMigrate();

			return $retorno;
		}
		else
		{
			return false;
		}

	}
}

if(!function_exists('agruparPorCampo'))
{

	function agruparPorCampo($array, $campoAgrupar) 
	{
	    $resultado = array();
	    foreach($array as $valor) {
			
	        $resultado[trim($valor[$campoAgrupar])][] = $valor;
	    }

	    return $resultado;
	}
}



