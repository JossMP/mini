<?php
	// Lee la configuracion de los modulos
	class modulo
	{
		var $config = null;
		function __construct()
		{
			$this->LoadConfig();
		}
		public function LoadConfig()
		{
			$rtn = false;
			$handle = dir( URI_MOD );
			while ( $directorio = $handle->read() )
			{
				if( is_dir(URI_MOD.$directorio) && $directorio!="." && $directorio!=".." )
				{
					$rtn[$directorio] = $directorio;
				}
			}
			return $this->OpenConfig($rtn);
		}
		public function OpenConfig($dir)
		{
			$rtn = false;
			ksort($dir);
			foreach( $dir as $directorio)
			{
				if( file_exists(URI_MOD.$directorio."/config.json") )
				{
					$json = @file_get_contents( URI_MOD.$directorio."/config.json" );
					if( $json!==FALSE )
					{
						$rtn[] = array( "directorio"=>$directorio,"json"=>$json );
					}
				}
			}
			$this->config = $rtn;
			return $rtn;
		}
		
		public function Permission()
		{
			$rtn = array();
			if( $this->config == null )
			{
				$this->LoadConfig();
			}
			else
			{
				foreach( $this->config as $mod )
				{
					$mod = (object)$mod;
					$json = json_decode( $mod->json );
					foreach( $json->Permiso as $code => $desc )
					{
						$rtn[ $mod->directorio ][ $code ] = $desc;
					}
				}
			}
			return $rtn;
		}
		public function get_config()
		{
			if( $this->config == null )
			{
				$this->LoadConfig();
			}
			return $this->config;
		}
		public function get_permission()
		{
			return $this->Permission();
		}
	}
?>
