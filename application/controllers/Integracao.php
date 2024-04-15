<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Integracao extends CI_Controller
{


	public function index()
	{


		$base_url_loja = trim($this->integracaodao->getUrlLoja());
		if (empty($base_url_loja)) {
			$data['dadosLoja'][0]['base_url_loja'] = "Endereço URL sem valor";
		} else {
			$data['dadosLoja'][0]['base_url_loja'] = $base_url_loja;
		}


		$consumer_key = trim($this->integracaodao->getConsumerKey());
		if (empty($consumer_key)) {
			$data['dadosLoja'][0]['consumer_key'] = "Consumer Key sem valor";
		} else {
			$data['dadosLoja'][0]['consumer_key'] = $consumer_key;
		}

		$consumer_secret = trim($this->integracaodao->getConsumerSecret());

		if (empty($consumer_secret)) {
			$data['dadosLoja'][0]['consumer_secret'] = "Consumer Secret sem valor";
		} else {
			$data['dadosLoja'][0]['consumer_secret'] = $consumer_secret;
		}


		$code = trim($this->integracaodao->getCode());

		if (empty($code)) {
			$data['dadosLoja'][0]['code'] = "Code sem valor";
		} else {
			$data['dadosLoja'][0]['code'] = $code;
		}

		$token = $this->integracaodao->getToken();

		if (isset($token[0]->date_expiration_refresh_token)) {
			$date_expiration_refresh_token = $token[0]->date_expiration_refresh_token;
		} else {
			$date_expiration_refresh_token =  '01/01/2000';
		}

		if ((empty($token)) || (getdate() >  $date_expiration_refresh_token)) {

			$curl = curl_init();

			$params["consumer_key"] = $consumer_key;
			$params["consumer_secret"] = $consumer_secret;
			$params["code"] = $code;

			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL => $base_url_loja . "auth/",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => false,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => $params,
				)
			);

			$response = curl_exec($curl);
			$err = curl_error($curl);
			$resposta = json_decode($response);
			$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			curl_close($curl);

			if ($code == "200" || $code == "201") {

				$params["access_token"] = $resposta->{'access_token'};

				//inserir token tabela
				$this->integracaodao->setToken($resposta->{'access_token'}, $resposta->{'refresh_token'}, $resposta->{'date_expiration_access_token'}, $resposta->{'date_expiration_refresh_token'}, $resposta->{'date_activated'}, $resposta->{'api_host'}, $resposta->{'store_id'});
			} else {
				print_r("Erro: " . $code . "<br>");
				print_r('Name:       ' . $resposta->{'name'});
				var_dump($resposta);
				die();
			}
		} else {

			if (getdate() <  $token[0]->date_expiration_access_token) {

				$params["access_token"] = $token[0]->access_token;
			} else {
				$curl = curl_init();

				$params["refresh_token"] =  $token[0]->refresh_token;


				curl_setopt_array(
					$curl,
					array(
						CURLOPT_URL => $base_url_loja . "auth/?" . http_build_query($params),
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => false,
						CURLOPT_SSL_VERIFYPEER => false,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "GET",
					)
				);

				$response = curl_exec($curl);
				$err = curl_error($curl);
				$resposta = json_decode($response);
				$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

				curl_close($curl);

				if ($code == "200" || $code == "201") {

					$params["access_token"] = $resposta->{'access_token'};

					//inserir token tabela
					$this->integracaodao->setToken($resposta->{'access_token'}, $resposta->{'refresh_token'}, $resposta->{'date_expiration_access_token'}, $resposta->{'date_expiration_refresh_token'}, $resposta->{'date_activated'}, $resposta->{'api_host'}, $resposta->{'store_id'});
				} else {
					print_r("Erro: " . $code . "<br>");
					print_r('Name:       ' . $resposta->{'name'});
					var_dump($resposta);
					die();
				}
			}
		}

		$token = $this->integracaodao->getToken();
		$data['token'] = $token;






		//listar vendas [KARL]

		//Chamar a lista de todas as vendas enviadas para pegar o id


		$curl = curl_init();

		$params["refresh_token"] =  $token[0]->refresh_token;

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $base_url_loja . "orders?" . http_build_query($params) . '&status=ENVIADO',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
			)
		);


		$response = curl_exec($curl);
		$err = curl_error($curl);
		$resposta = json_decode($response);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);






		foreach ($resposta->Orders as $key => $pedido) 
		{
			$curl = curl_init();
			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL => $base_url_loja . "orders/" . $pedido->Order->id . "/complete?" . http_build_query($params),
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => false,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
				)
			);

			$response = curl_exec($curl);
			$err = curl_error($curl);
			$resposta = json_decode($response);
			$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);


			$status = $resposta->Order->status;
			$id = $resposta->Order->id;
			$date = $resposta->Order->date;
			$total = $resposta->Order->total;
			$point_sale	 = $resposta->Order->point_sale;

			// Dados cliente

			$nomeCli = strtoupper($resposta->Order->Customer->name);
			$cpfCli = $resposta->Order->Customer->cpf;
			$logradouro = strtoupper($resposta->Order->Customer->address);
			$cep = $resposta->Order->Customer->zip_code;
			$numero = $resposta->Order->Customer->number;
			$complemento = $resposta->Order->Customer->complement;
			$bairro = $resposta->Order->Customer->neighborhood;
			$cidade = $resposta->Order->Customer->city;
			$uf = $resposta->Order->Customer->state;
			$telefone = $resposta->Order->Customer->phone;
			$email = $resposta->Order->Customer->email;

			    // Dados Produto venda
				foreach ($resposta->Order->ProductsSold as $key => $produto) {
					$data[] = array(
						'prodId' => $produto->ProductsSold->id,
						'prodRef' => $produto->ProductsSold->reference,
						'prodDescricao' => $produto->ProductsSold->original_name,
						'vendaprc' => $produto->ProductsSold->price,
						'compraprc' => $produto->ProductsSold->cost_price,
						'idvendaprod' => $produto->ProductsSold->order_id
					);
				}
			
				// Agora você tem todos os produtos desta venda armazenados no array $data
			
				// Imprimir os detalhes de todos os produtos desta venda
				foreach ($data as $produto) {
					echo '<pre>';
					print_r('************************ ID Venda: ' . $id . ' | SKU: ' . $produto['prodId'] . ' | Codigo produto: ' . $produto['prodRef'] . ' ************************');
				}
			
				// Se desejar, pode adicionar uma linha em branco após cada pedido
				echo '<br>';
			
				// Limpar o array de produtos para a próxima iteração do loop de pedido
				$data = array();


			$cpfCliFormat = substr($cpfCli, 0, 3) . '.' . substr($cpfCli, 3, 3) . '.' . substr($cpfCli, 6, 3) . '-' . substr($cpfCli, 9, 2);


			$data['ClientAPI'] = array(
				'nome' => $nomeCli,
				'CPF' => $cpfCliFormat,
				'Endereco' => $logradouro,
				'CEP' => $cep,
				'Num' => $numero,
				'Compl' => $complemento,
				'Bairro' => $bairro,
				'Cidade' => $cidade,
				'UF' => $uf,
				'Fone' => $telefone,
				'Email' => $email,
			);


			$data['clientes'][$key] = array(
				'nome' => $nomeCli,
				'CPF' => $cpfCliFormat,
				'Endereco' => $logradouro,
				'CEP' => $cep,
				'Num' => $numero,
				'Compl' => $complemento,
				'Bairro' => $bairro,
				'Cidade' => $cidade,
				'UF' => $uf,
				'Fone' => $telefone,
				'Email' => $email,
				'Situacao' => '',
			);


			$data['pedidos'][$key] = array(
				 'PedidoTray' => $pedido->Order->id,
				 'IdVendaCDS' => '',
				 'Situacao' => '',
			);

		// 	$data['produtos'] = array(
		// 		'ID' => $prodId,
		// 		'codigo' => $prodRef,
		// 		'descricao' => $prodDescricao,
		// 		'venda' => $vendaprc,
		// 		'compra' => $compraprc,
		// 		'idVendaProd' => $idvendaprod,
		//    );
			
			$retornoGetCli = $this->integracaodao->getCliente($cpfCliFormat);

			if ($retornoGetCli) {
				
				$this->integracaodao->putCli($data['ClientAPI']);
				$data['clientes'][$key]['Situacao'] = 'cliente Atualizado';
			}
			else{
				
				$this->integracaodao->setCliente($data['ClientAPI']);
				$data['clientes'][$key]['Situacao'] = 'Cliente Novo';
			}	
			//fim cliente

			//pedido
			$retornoGetPed = $this->integracaodao->getPedido($pedido->Order->id);
			// $retornoGetProd = $this->integracaodao->getProduto($produto->ProductsSold->reference);


			

			
			// if ($retornoGetProd) {
				if (!$retornoGetPed){
					$data['pedidos'][$key]['Situacao'] = 'Pedido inserido com Sucesso | Produtos: ' . $retornoGetPed;
	
					$data['pedidos'][$key]['IdVendaCDS'] = $retornoGetPed;
					//validar produtos se tem cadastrado 
					//se todos produtos ok continua
					//inserir venda
					//inserir vendaprod
					//ate aqui é uma pre venda- so ate aqui para me mostrar
	
	
					//depois falo o proximo
					//situacao erro no pedido produto x nao cadastrado
	
	
	
				}else{			
				  $data['pedidos'][$key]['Situacao'] = 'Pedido ja Consta na cds '. $retornoGetPed;
				  $data['pedidos'][$key]['IdVendaCDS'] = $retornoGetPed;
				}
				
			// } else {
			// 	$data['pedidos'][$key]['Situacao'] = 'Produto não cadastrado';
			// 	$data['pedidos'][$key]['IdVendaCDS'] = $retornoGetProd;
			// }


			//fim pedido

			
			curl_close($curl);

		}
// echo '<pre>';
// print_r($data['clientes']);

// print_r($data['pedidos']);

// die;
	
		//curl_close($curl);

       

        // Pegando os clientes do BD e adicionando a variavel $data
       // $data['clientes'] = $this->integracaodao->obterTodosClientes();

        // $this->load->view('home', $data);


	}
}
