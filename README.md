# Router
Classe php para trabalhar REST API

## CARREGADOR DE TEMPLATE

```
	Router::view("home.html");
```


## ALTERAÇÃO DO CONTEÚDO DO TEMPLATE COM BASE NAS FLAGS (TEMPLATE VIEW)

```
	Router::view("home.html", ["#{titulo}#" => "Bem vindo ao site"] );
```


## HTTP GET

```
	Router::get('/', function(){
		Router::view("home.html", ["#{titulo}#" => "Bem vindo ao site"] );
	});
```


## HTTP GET COM PARÂMETROS DINÂMICOS NO ENDPOINT | {paramDinamic}

```
	Router::get('/idUsuario/{cpf}', function($params){
		echo $params->cpf;
	});
```


## HTTP POST | JSON

```
	Router::post('/recebejson', function(){
		$dados = Router::getJson();
		echo $dados->nome;
	});
```


## HTTP PUT | x-www-form-urlencoded

```
	Router::put('/recebeDados', function($dados){
		print_r($dados);
	});
```


## RECUPERANDO JSON

```
	Router::getJson();
	
	### EXEMPLO DE USO:
	
	Router::post('/recebejson', function(){
		$dados = Router::getJson();
		echo $dados->nome;
	});
```


## CONFIGURAÇÃO DEV | error_reporting, display_errors 

```
	Router::dev();
```



## PÁGINA NÃO ENCONTRADA | DEVERÁ FICA NA ÚLTIMA LINHA DO ARQUIVO

```
	Router::notFound("notFound.html");
```



