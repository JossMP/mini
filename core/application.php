<?php
class Application
{
	private $url_module = null;
	private $url_action = null;
	private $url_params = array();
	private $active_params = true;
	public function __construct($default_module = null) // home
	{
		// Crea un array con la url parcial
		$this->splitUrl();
		// si no existe controller carga pagina de inicio(home)
		//if( !$this->url_module )
		if( $this->url_module == null )
		{
			$this->url_module = $default_module;
			if( !$this->url_module )
			{
				echo "ERROR: Modulo no definido";
				//header('location: ' . URL . 'fail');
			}
			elseif (file_exists(URI_MOD . $this->url_module . '/index.php'))
			{
				define("MODULO", $this->url_module);
				require URI_MOD . $this->url_module . '/index.php';
				$this->url_module = new $this->url_module();
				if ( method_exists($this->url_module, "index") )
				{
					$this->url_module->index();
				}
			}
			else
			{
				echo "ERROR: Modulo no Existente";
				//header('location: ' . URL . 'error');
			}
		}
		elseif ( file_exists(URI_MOD . $this->url_module . '/index.php') )
		{
			require URI_MOD . $this->url_module . '/index.php';
			$this->url_module = new $this->url_module();
			if ( $this->url_action != null )
			{
				if(method_exists($this->url_module, $this->url_action))
				{
					if ( !empty($this->url_params) )
					{
						if( $this->active_params == false )
						{
							header('location: ' . URL . 'fail');
						}
						else
						{
							call_user_func_array(array($this->url_module, $this->url_action), $this->url_params);
						}
					}
					else
					{
						$this->url_module->{$this->url_action}();
					}
				}
				else
				{
					header('location: ' . URL . 'fail');
				}
			}
			else
			{
				if ( method_exists($this->url_module, "index") )
				{
					$this->url_module->index();
				}
				else
				{
					header('location: ' . URL . 'fail');
				}
			}
		}
		else
		{
			header('location: ' . URL . 'fail');
		}
	}
	private function splitUrl()
	{
		if (isset($_GET['url']))
		{
			$url = trim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', $url);

			$this->url_module = isset($url[0]) ? $url[0] : null;
			$this->url_action = isset($url[1]) ? $url[1] : null;
			define("MODULO", $this->url_module);
			unset($url[0],$url[1]);

			$this->url_params = array_values($url);
		}
	}
}
