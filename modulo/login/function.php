<?php
	class fnLogin
	{
		var $parents;
		function __construct(&$parents)
		{
			$this->parents = $parents;
		}
		function Login($usuario, $clave)
		{
			$query = "SELECT idUsuario, Estado, Usuario, Estado, Nombres, Paterno, Materno, DNI, FotoPerfil FROM Usuario WHERE Usuario like '".$usuario."' AND Clave = md5('".$clave."');";
			if( $this->parents->sql->consulta($query) )
			{
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					if( $obj->Estado == "Activo" )
					{
						$login_session= array(
							"idUsuario" 	=> $obj->idUsuario,
							"login" 		=> $obj->Usuario,
							"Nombre" 		=> $obj->Nombres." ".$obj->Paterno." ".$obj->Materno,
							"Foto" 			=> $obj->FotoPerfil,
							"DNI" 			=> $obj->DNI,
							"Permiso" 		=> $this->PermisoUsuario($obj->idUsuario)
						);
						$this->parents->session->put_login( $login_session );
						
						$rtn = array(
							"success"=>true,
							"redirection"=>URL,
							"update"=>array(
								array(
									"id"		=> "login-alert",
									"value"		=> "Accediendo al Sistema",
									"action"	=> "html"
								)
							)
						);
						return json_encode($rtn, JSON_PRETTY_PRINT);
					}
					else
					{
						$rtn = array(
							"success"=>false,
							"notification"=>"Su Usuario es bloqueado..."
						);
						return json_encode($rtn, JSON_PRETTY_PRINT);
					}
				}
				$rtn = array(
					"success"=>false,
					"notification"=>"Usuario o clave incorrecto..."
				);
				return json_encode($rtn, JSON_PRETTY_PRINT);
			}
		}
		function PermisoUsuario($idUsuario)
		{
			$Permiso=array();
			$query="SELECT Codigo, Modulo, idUsuario FROM Permiso WHERE idUsuario=".$idUsuario." ORDER BY Modulo DESC;";
			if( $this->parents->sql->consulta($query) )
			{
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					$Permiso[$obj->Modulo][$obj->Codigo]=true;
				}
			}
			return $Permiso;
		}
		function Logout()
		{
			$this->parents->session->remove();
			$rtn = array(
				"success" 		=> true,
				"redirection" 	=> URL."login"
			);
			return json_encode($rtn, JSON_PRETTY_PRINT);
		}
		/* Login FB */
		function LoginFacebook()
		{
			
		}
	}
?>
