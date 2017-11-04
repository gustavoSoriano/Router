<?php
	header('Access-Control-Allow-Origin: *');
	require_once('./Router.php');
	Router::dev();
	

//TEMPLATES
	Router::get('/', function(){
		Router::view("home.html", ["#{titulo}#" => "Bem vindo ao site"] );
	});

	//endpoint com parâmetro dinâmico
	Router::get('/soriano/{cpf}', function($params){
		echo $params->cpf;
	});

	//recebe dados por json raw
	Router::post('/recebejson', function(){
		$dados = Router::getJson();
		echo $dados->nome;
	});

	//recebe dados por json raw
	Router::put('/recebejson', function(){
		$dados = Router::getJson();
		echo $dados->nome;
	});

	//recebe post por x-www-form-urlencoded
	Router::put('/recebeDados', function($dados){
		print_r($dados);
	});

	//recebe post por x-www-form-urlencoded
	Router::post('/recebeDados', function($dados){
		print_r($dados);
	});

//ERROR 404 - Manter sempre em último
	Router::notFound("notFound.html");
