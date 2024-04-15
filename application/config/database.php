<?php
defined('BASEPATH') OR exit('No direct script access allowed');


	$servidor = '.\CDS';
	$banco = 'BDComercialCiaDasRacoes';
	$usuario = 'cdsweb';
	$senha = 'dificil!@#';

	$connectionInfo = array( "Database"=>$banco, "UID"=>$usuario, "PWD"=>$senha);

	$conn = sqlsrv_connect( $servidor, $connectionInfo);

	//

	if( !$conn ) 
	{

	    echo 'Erro ao tentar conectar com o servidor, tente novamente mais tarde.';
	    die();

	}

	sqlsrv_close($conn);

	


$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $servidor,
	'username' => $usuario,
	'password' => $senha,
	'database' => $banco,
	'dbdriver' => 'sqlsrv',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
