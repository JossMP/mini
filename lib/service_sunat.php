<?php
	class WsseAuthHeader extends SoapHeader
	{
		private $wss_ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
		function __construct($user, $pass, $ns = null)
		{
			if ($ns)
			{
				$this->wss_ns = $ns;
			}

			$auth = new stdClass();
			$auth->Username = new SoapVar($user, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);
			$auth->Password = new SoapVar($pass, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);
			$username_token = new stdClass();
			$username_token->UsernameToken = new SoapVar($auth, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns);
			$security_sv = new SoapVar( new SoapVar($username_token, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns), SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'Security', $this->wss_ns );
			parent::__construct($this->wss_ns, 'Security', $security_sv, true);
		}
	}
	function soapClientWSSecurityHeader($user, $password)
	{
		// Creating date using yyyy-mm-ddThh:mm:ssZ format
		$tm_created = gmdate('Y-m-d\TH:i:s\Z');
		$tm_expires = gmdate('Y-m-d\TH:i:s\Z', gmdate('U') + 180); //only necessary if using the timestamp element

		// Generating and encoding a random number
		$simple_nonce = mt_rand();
		$encoded_nonce = base64_encode($simple_nonce);

		// Compiling WSS string
		$passdigest = base64_encode(sha1($simple_nonce . $tm_created . $password, true));

		// Initializing namespaces
		$ns_wsse = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
		$ns_wsu = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
		$password_type = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest';
		$encoding_type = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary';

		// Creating WSS identification header using SimpleXML
		$root = new SimpleXMLElement('<root/>');

		$security = $root->addChild('wsse:Security', null, $ns_wsse);

		//the timestamp element is not required by all servers
		$timestamp = $security->addChild('wsu:Timestamp', null, $ns_wsu);
		$timestamp->addAttribute('wsu:Id', 'Timestamp-28');
		$timestamp->addChild('wsu:Created', $tm_created, $ns_wsu);
		$timestamp->addChild('wsu:Expires', $tm_expires, $ns_wsu);

		$usernameToken = $security->addChild('wsse:UsernameToken', null, $ns_wsse);
		$usernameToken->addChild('wsse:Username', $user, $ns_wsse);
		$usernameToken->addChild('wsse:Password', $passdigest, $ns_wsse)->addAttribute('Type', $password_type);
		$usernameToken->addChild('wsse:Nonce', $encoded_nonce, $ns_wsse)->addAttribute('EncodingType', $encoding_type);
		$usernameToken->addChild('wsu:Created', $tm_created, $ns_wsu);

		// Recovering XML value from that object
		$root->registerXPathNamespace('wsse', $ns_wsse);
		$full = $root->xpath('/root/wsse:Security');
		$auth = $full[0]->asXML();

		return new SoapHeader($ns_wsse, 'Security', new SoapVar($auth, XSD_ANYXML), true);
	}
	class ServiceSunat
	{
		var $error = "";
		var $access = null;
		var $service = null;
		var $client = null;
		var $DefautService=null;
		function __construct( string $username, string $password, $security = false )
		{
			if($security==true)
				$this->access = soapClientWSSecurityHeader($username,$password);
			else
				$this->access = array(new WsseAuthHeader($username,$password));

			$this->initDefautService();
		}
		function setError($msg="")
		{
			$this->error = $msg;
		}
		function getError()
		{
			return $this->error;
		}
		function initClient($index)
		{
			$this->setService($index);
			try{
				$this->client = new SoapClient($this->service, array("encoding"=>"ISO-8859-1", "trace" => 1, "exceptions" => 1, "cache_wsdl" => 1)  );

				$this->client->__setSoapHeaders( $this->access );
				return true;
			}
			catch(exception $e)
			{
				return false;
			}
			return false;
		}
		function initDefautService()
		{
			$this->DefautService=array(
				0 	=> "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl", //facturacion
				1 	=> "https://e-guiaremision.sunat.gob.pe/ol-ti-itemision-guia-gem/billService?wsdl", //Guia Remision
				2 	=> "https://www.sunat.gob.pe/ol-ti-itemision-otroscpe-gem/billService?wsdl", // Retencion y percepcion
				3 	=> "https://www.sunat.gob.pe/ol-it-wsconsvalidcpe/billValidService?wsdl", // Consulta validez y verificacion de FE
				4 	=> "https://www.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl", // Consulta CDR

				5 	=> "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl", // BETA: factura
				6 	=> "https://e-beta.sunat.gob.pe/ol-ti-itemision-guia-gem-beta/billService?wsdl", // BETA: guia
				7 	=> "https://e-beta.sunat.gob.pe/ol-ti-itemision-otroscpe-gem-beta/billService?wsdl" // BETA: Retenciones
			);
		}
		function setService($index)
		{
			$this->service = $this->DefautService[$index];
		}

		function sendXML( string $path, string $name="" )
		{
			$this->initClient(5);//beta
			if( $name=="" )
			{
				$name = basename($path);
			}
			$zip = file_get_contents( $path );
			$request = array(
				'fileName' => $name,
				'contentFile' => $zip
			);
			try{
				$resultado = $this->client->sendBill($request);
				return $resultado->applicationResponse;
			}
			catch (SoapFault $exception)
			{
				//echo $exception->faultcode." : ".$exception->faultstring;
				$this->setError( $exception->faultstring );
				return false;
			}
			return false;
		}
		function sendCheckCDR( string $ruc, string $tipo, string $serie, string $nro )
		{
			if(!$this->initClient(4))
			{
				$this->setError( "Cliente SOAP no Inicializado..." );
				return false;
			}
			$request = array(
				'rucComprobante' => $ruc,
				'tipoComprobante' => $tipo,
				'serieComprobante' => $serie,
				'numeroComprobante' => $nro
			);
			try{
				$resultado = $this->client->getStatusCdr($request);
				return $resultado->applicationResponse;
			}
			catch (SoapFault $exception)
			{
				echo $this->client->__getLastRequest()."\n";
				echo $this->client->__getLastResponse()."\n";
				//echo $exception->faultcode." : ".$exception->faultstring;
				$this->setError( $exception->faultstring );
				return false;
			}
		}
	}
	//$test = new ServiceSunat("20601898447MODDATOS","moddatos");
	$test = new ServiceSunat(strtoupper("10449884944lblindl1"),"BLINDpa12hh12");
	//$a = $test->sendXML("../upload/custodia/20601898447/zip/20601898447-01-F001-00000001.zip");
	$a = $test->sendCheckCDR("10425563543","01","F001","00001");
	if($a!=false)
	{
		echo $a;
	}
	else
	{
		echo $test->getError();
	}
?>
