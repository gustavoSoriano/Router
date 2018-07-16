<?php
	header('Access-Control-Allow-Origin: *'); 
	header("Access-Control-Allow-Credentials: false");
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
	header('Access-Control-Allow-Headers: Authorization, content-type');
    header("Content-type:text/html; charset=utf-8");

    spl_autoload_register(function($className){
        $class = str_replace("\\", "/", $className);
        $class = $class . '.php';
        if(file_exists($class))
        {
            require_once $class;
            return;
        }
        echo "Erro: arquivo da classe <strong>{$class}</strong> não encontrado";
    });