<?php
	class Router
	{
		protected static $params   = array();
		protected static $folder;
		protected static $notFound = true;
		protected static $input;

		private static function compareParams($URI, $endPoint)
		{
			//valida os parametros não dinamicos
			foreach( $endPoint as $key => $u )
			{
				//se o respectivo parâmetro não é dinamico, vou testa-lo com outro array no mesmo índice
				if( !strstr($u,'{') )
				{
					//se a string for diferente estando na mesma posição, não é o endpoint correto
					if( $URI[$key] !== $endPoint[$key] ) return false;
				}
			}
			//se chegar aqui, quer dizer que os parametros não dinâmicos são iguais nas mesmas posições dos dois arrays
			return true;
		}

		//percorre os parametros dinamicos e coloca o valor passado, dentro de self:params
		private static function mountParamsDinamic($URI, $endPoint)
		{
			foreach( $endPoint as $key => $u )
			{
				if( strstr($u,'{') )
				{
					self::$params[substr( $u, 1, -1 )] = urldecode( $URI[$key] );
				}
			}
		} 

		private static function testURI($endPoint, $_callback)
		{			
			//recupera o nome do dirroot, pois esta na uri. Mas é necessário remover \app\server
			self::$folder = __DIR__;
			self::$folder = str_replace("\app\server\controllers", "", self::$folder);
			self::$folder = "/".basename(self::$folder);

			//removo a folder root da uri
			$URI = str_replace(self::$folder, "", $_SERVER ['REQUEST_URI']);

			//criando os array's
			$arrURI      = explode("/", $URI);
			$arrEndPoint = explode("/", $endPoint);

			//preciso medir o tamanho da uri atual e do endpoint desejado
			$sizeURI      = count( $arrURI );
			$sizeEndPoint = count( $arrEndPoint );

			//são do mesmo tamanho e os parametros não dinamicos são iguais?
			if( $sizeURI == $sizeEndPoint and self::compareParams($arrURI, $arrEndPoint) )
			{
				self::$input = file_get_contents("php://input");
				
				//se chegar aqui, quer dizer que o endpoint é válido
				//basta devolver os parametros dinamicos e chamar o callback
				self::mountParamsDinamic($arrURI, $arrEndPoint);
				self::$notFound = false;

				//se o metodo for put, add o fluxo de entrada no array de parâmetros da classe
				if($_SERVER['REQUEST_METHOD'] == "PUT") 
				{
					$arrayPut = array();
					parse_str(self::$input, $arrayPut);
					foreach($arrayPut as $key => $value)
					{
						self::$params[$key] = $value;
					}
				}
				//converte o array de parâmetro para objeto
				self::$params   = (object) self::$params;
				call_user_func($_callback, self::$params);
				exit;
			}
		}

 		public static function get($_url, $_callback)
		{
			if( $_SERVER['REQUEST_METHOD'] == "GET" )self::testURI($_url, $_callback);
		}

		public static function post($_url, $_callback)
		{
			if( $_SERVER['REQUEST_METHOD'] == "POST" )self::testURI($_url, $_callback);
		}

		public static function put($_url, $_callback)
		{
			if( $_SERVER['REQUEST_METHOD'] == "PUT" )self::testURI($_url, $_callback);
		}

		public static function delete($_url, $_callback)
		{
			if( $_SERVER['REQUEST_METHOD'] == "DELETE" )self::testURI($_url, $_callback);
		}
		

		/**
		 * Recebe um json enviado no corpo da requisição
		 * Deverá ser stringify
		 */
		public static function getJson()
		{
			return json_decode( self::$input );
		}

		public static function notFound( $template )
		{
			if( self::$notFound === true ) self::View($template);
		}

	    public static function View( $_tpl, $_flags=null )
	    {
			if($_flags == null)
				echo file_get_contents($_tpl);
			else
				echo str_replace(array_keys($_flags), array_values($_flags), file_get_contents($_tpl) );
		}	
		

		public static function dev()
		{
			error_reporting(E_ALL);
			ini_set("display_errors", 1);
		}


		/**
		 * Tratativas de erro e finalização do fluxo
		 */
		public static function Err( $message )
		{
			echo json_encode( array("error" => $message ) );
			exit;
		}

		/***
		 * 
		 * Gerador de JWT
		 * Cria Jwt baseado na data atual do servidor
		 * O token torna-se inválido quando a data é alterada
		 * 
		 */
		public static function Jwt()
		{
			$vencimento= array("vencimento"=>Date('d:m:Y'));
			$key       = 'soriano.dev';
			$header    = array('typ'  => 'JWT','alg'  => 'HS256');
			$header    = json_encode($header);
			$header    = base64_encode($header);
			$dados     = json_encode($vencimento);
			$dados     = base64_encode($dados);
			$signature = hash_hmac('sha256', "$header.$dados", $key, true);
			$signature = base64_encode($signature);
			$token     = "$header.$dados.$signature";
			$token     = str_replace("/", "-xx-", $token);
			$token     = str_replace("+", "-ww-", $token);
			return json_encode( array( 'token' => $token ) );
		}


		/**
		 * Valida o jwt que vem no header da requisição
		 * O jwt deverá ser enviado no Authorization
		 */
		public static function validateJwt()
		{
			if( isset( apache_request_headers()["Authorization"] ) ) 
			{
				$token=apache_request_headers()["Authorization"];

				if( ($token === json_decode( self::jwt() )->token) == false ) 
					self::Err("Invalid-jwt");
			}
			else
				self::Err("No-Authorization");
			
		}

		public static function Json( $data )
		{
			header("Content-Type: application/json; charset=utf-8");
			if( gettype($data) !== "array" ) $data = array("response"=>$data);
			$headers = array(
				"Content_type"=>"application/json",
				"time_stamp"=>date("d-m-Y")." ".date("h:m:s"),
				"data"=> $data
			);
			print_r(json_encode($headers));
		}
		
	}
?>
