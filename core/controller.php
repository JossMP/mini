<?php
	class Controller
	{
		function __construct()
		{
			$this->load_basic_php();
			$this->load_interfaz();
		}
		public function register_class( $class_alias, $class_name, $param = null )
		{
			try{
				if( is_string($class_alias) && is_string($class_name) )
				{
					$this->$class_alias = new $class_name();
				}
				else
				{
					throw new ISException('El nombre de la clase debe de ser una cadena.');
				}
			}
			catch(Exception $exception)
			{
				echo $exception;
			}
		}
		public function load_interfaz()
		{
			include(URI."lib/interfaz.php");
			$this->interfaz = new Interfaz($this);
		}
		public function load_basic_php()
		{
			include(URI."lib/sql.php");
			$this->register_class("sql","sql");

			include(URI."lib/session.php");
			$this->register_class("session","session");

			include(URI."lib/modulo.php");
			$this->register_class("mod","modulo");

			include(URI."lib/content.php");
			$this->register_class("content","content");

			include(URI."lib/extra.php");
			//$this->register_class("fn","extra");
			$this->fn = new extra($this);
			
			include(URI."lib/upload.php");
			$this->register_class("upload","upload");
		}
	}
?>
