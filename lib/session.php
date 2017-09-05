<?php
	class session
	{
		function __construct()
		{
			if( !isset($_SESSION) )
			{
				session_start();
			}
		}
		public function put($name, $data)
		{
			$_SESSION[$name] = $data;
		}
		public function set($name, $data)
		{
			$this->put($name, $data);
		}
		public function get($name)
		{
			if(isset($_SESSION[$name]))
			{
				return $_SESSION[$name];
			}
			return false;
		}
		public function remove($name = null)
		{
			if($name!=null)
			{
				unset($_SESSION[$name]);
			}
			else
			{
				$_SESSION = array();
				session_destroy();
			}
		}
		
		/* Register Login */
		public function id_login()
		{
			$id_login = uniqid("login_" );
			$expiry = time()+3600;
			$this->put( "success", true );
			$this->put( "login_id", $id_login );
			$this->put( "expiry", $expiry );
			setcookie( "login_id", $id_login, $expiry );
		}
		public function extend_login()
		{
			if( $this->get("success")!=false && $this->get("login_id")!=false && $this->get("expiry")!=false )
			{
				if( $this->get("expiry") < time() )
				{
					$expiry = time()+3600;
					$this->put( "expiry", $expiry );
					setcookie( "login_id", $this->get("login_id"), $expiry, "/" );
				}
			}
		}
		public function check_login()
		{
			if( $this->get("success")!=false && $this->get("login_id")!=false )
			{
				return true;
			}
			return false;
		}
		public function put_login($data) // crea session login
		{
			if( is_array($data) )
			{
				foreach($data as $i=>$v)
				{
					$this->put($i, $v);
				}
				$this->id_login();
			}
		}
		public function set_login($data) // Modifica session de login
		{
			if( is_array($data) )
			{
				foreach($data as $i=>$v)
				{
					$this->set($i, $v);
				}
				$this->extend_login();
			}
		}
		/* Permisos */
		public function get_permission($modulo,$codigo)
		{
			if( isset($_SESSION["Permiso"][$modulo][$codigo]) )
			{
				return true;
			}
			return false;
		}
		function authorized( $modulo, $codigo, $contenido, $else="" )
		{
			if( $this->get_permission( $modulo, $codigo ) )
			{
				$rtn = $contenido;
			}
			else
			{
				$rtn = $else;
			}
			return $rtn;
		}
	}
?>
