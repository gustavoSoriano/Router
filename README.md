# Router
Classe php para trabalhar REST API

## Carregador de template

```
	Router::view("home.html");
```


## Alteração do conteúdo do template com base nas flags (Template View)

```
	Router::view("home.html", ["#{titulo}#" => "Bem vindo ao site"] );
```


## JWT | Cria Jwt

```
	Router::Jwt();
```


## JWT | Valida o Jwt
#### Enviar o jwt no Authorization, header da requisição.
Se o jwt for inválido irá cortar o fluxo da aplicação e gerar um erro

```
	Router::validateJwt();
```


## HTTP GET

```
	Router::get('/', function(){
		Router::view("home.html", ["#{titulo}#" => "Bem vindo ao site"] );
	});
```


## HTTP GET Com parâmetros dinâmicos no endpoint | {parametro}

```
	Router::get('/idUsuario/{cpf}', function($params){
		echo $params->cpf;
	});
```


## HTTP POST | x-www-form-urlencoded

```
	Router::post('/dados', function(){
		print_r($_POST);
	});
```

## Recuperando Json no corpo da requisição

```
	Router::getJson();
	Obrigatório Stringify
	### Ex:
	
	Router::post('/recebejson', function(){
		$dados = Router::getJson();
		echo $dados->nome;
	});
```



## HTTP POST | JSON

```
	Router::post('/dados', function(){
		var_dump( Router::getJson() );
	});
```

## HTTP PUT | JSON

```
	Router::put('/dados', function(){
		var_dump( Router::getJson() );
	});
```


## HTTP DELETE

```
	Router::delete('/dados/{cod}', function($params){
		echo $params->cod;
	});
```



## Router::json | Resposta em json
```
	Router::get('/dados', function(){
		Router::json( array("usuario" => "soriano") );
	});
```



## Configuração Dev | error_reporting, display_errors 

```
	Router::dev();
```



## Página não encontrada | Deixar na última linha do arquivo

```
	Router::notFound("notFound.html");
```



