<?php
class GoogleURL
{
	private $apiURL = 'https://www.googleapis.com/urlshortener/v1/url';

	function __construct( $apiKey = false )
	{
		//creamos la url de solicitud con nuestra key pública
		$apiKey = ($apiKey==false) ? "AIzaSyBHaNO1VVstA5YkB-Oy7vWJIAiUoiwq0Tc" : $apiKey;
		$this->apiURL = $this->apiURL . '?key=' . $apiKey;
	}

	//convierte una url larga en una corta
	public function encode($url)
	{
		$data = $this->cURL($url, true);
		//var_dump($data);
		return isset($data->id) ? $data->id : '' ;
	}

	//convierte una url corta en la real (larga)
	public function decode($url)
	{
		$data = $this->cURL($url, false);
		//var_dump($data);
		return isset($data->longUrl) ? $data->longUrl : '' ;
	}

	//enviamos y recogemos los datos del api de google
	private function cURL($url, $post = true)
	{
		$ch = curl_init();
		if ($post)
		{
			curl_setopt( $ch, CURLOPT_URL, $this->apiURL );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode(array('longUrl' => $url)) );
		}
		else
		{
			curl_setopt( $ch, CURLOPT_URL, $this->apiURL . '&shortUrl=' . $url );
		}
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		$json = curl_exec($ch);
		curl_close($ch);
		return (object) json_decode($json);
	}
}
?>
