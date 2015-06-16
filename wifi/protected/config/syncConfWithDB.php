<?php
	// Dados da conexão
	$dbname = "wifi";
	$dbport = "5432";
	$dbuser = "postgres";
	$dbhost = "127.0.0.1";
	$dbpassword = "postgres";

	// Preferências
	$tableName = "configuracao";

	$dbstring = "host={$dbhost} port={$dbport} dbname={$dbname} user={$dbuser} password={$dbpassword}";

	$conexao = pg_connect($dbstring);

	$config = include("config.php");

	$config_pt_br = include("config_pt_br.php");
	$config_en_us = include("config_en_us.php");

	foreach($config as $key => $value)
	{
		pg_insert($conexao, $tableName, prepareData($key, $value));

	}

	foreach($config_pt_br as $key => $value)
	{
		pg_insert($conexao, $tableName, prepareData($key, $value, "pt_br"));

	}

	foreach($config_en_us as $key => $value)
	{
		pg_insert($conexao, $tableName, prepareData($key, $value, "en_us"));

	}

	function prepareData($key, $value, $lang = null)
	{
		return array
		(
			"nome" => $key,
			"valor" => $value,
			"idioma" => $lang

		);

	}

?>
