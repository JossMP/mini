<?php
	define("BEFORE", 	false);
	define("AFTER", 	true);
	class content
	{
		private $code_secret = array();
		private $code = array();
		//private $services = array();
		function __construct()
		{
			
		}
		// Registro de Contenido
		public function register( $name, $code )
		{
			$this->code[$name] = $code;
		}
		public function put( $name, $code )
		{
			$this->register( $name, $code );
		}
		public function add( $name, $code, $position = AFTER )
		{
			if( isset($this->code[$name]) )
			{
				if($position == AFTER)
					$this->register( $name, $this->code[$name].$code );
				else
					$this->register( $name, $code.$this->code[$name] );
			}
			else
			{
				$this->register( $name, $code );
			}
		}
		public function remove( $name )
		{
			unset($this->code[$name]);
		}
		public function get($name, $filter = null)
		{
			if( isset($this->code[$name]) )
			{
				if(!$filter)
				{
					return $this->code[$name];
				}
				else if( is_callable($filter) )
				{
					return call_user_func($filter, $this->code[$name]);
				}
			}
			return "";
		}
		
		// Code privados
		public function get_secret($name, $filter = null)
		{
			if( isset($this->code_secret[$name]) )
			{
				if(!$filter)
				{
					return $this->code_secret[$name];
				}
				else if( is_callable($filter) )
				{
					return call_user_func($filter, $this->code_secret[$name]);
				}
			}
			return "";
		}
		// css | style
		public function get_style()
		{
			return ( isset($this->code_secret["style"]) ) ? "<style>".$this->get_secret("style")."</style>\n":"";
		}
		public function add_style($str)
		{
			if( isset( $this->code_secret["style"] ) )
			{
				$this->code_secret["style"].=$str."\n";
			}
			else
			{
				$this->code_secret["style"]=$str."\n";
			}
		}
		// js | script
		public function get_script()
		{
			return ( isset($this->code_secret["script"]) ) ? "<script>".$this->get_secret("script")."</script>\n":"";
		}
		public function add_script($str)
		{
			if( isset( $this->code_secret["script"] ) )
			{
				$this->code_secret["script"].=$str."\n";
			}
			else
			{
				$this->code_secret["script"]=$str."\n";
			}
		}
		// url css | style
		public function get_css()
		{
			return ( isset($this->code_secret["url_style"]) ) ? $this->get_secret("url_style"):"";
		}
		public function add_css( $url )
		{
			if( isset( $this->code_secret["url_style"] ) )
			{
				$this->code_secret["url_style"].='<link href="'.$url.'" rel="stylesheet">'."\n";
			}
			else
			{
				$this->code_secret["url_style"]='<link href="'.$url.'" rel="stylesheet">'."\n";
			}
		}
		// url js | script 
		public function get_js()
		{
			return ( isset($this->code_secret["url_script"]) ) ? $this->get_secret("url_script"):"";
		}
		public function add_js( $url )
		{
			if( isset( $this->code_secret["url_script"] ) )
			{
				$this->code_secret["url_script"].='<script src="'.$url.'"></script>'."\n";
			}
			else
			{
				$this->code_secret["url_script"]='<script src="'.$url.'"></script>'."\n";
			}
		}
		// title
		public function put_title( $str )
		{
			$this->code_secret["title"] = $str;
		}
		public function get_title()
		{
			return ( isset($this->code_secret["title"]) )? $this->code_secret["title"] : TITLE;
		}
		// Content Body
		public function get_body()
		{
			return ( isset($this->code_secret["body"]) ) ? $this->get_secret("body"):"";
		}
		public function put_body( $str )
		{
			$this->code_secret["body"] = $str;
		}
		public function add_body( $str, $position = AFTER  )
		{
			if( isset( $this->code_secret["body"] ) )
			{
				if($position == AFTER)
					$this->code_secret["body"] = $this->code_secret["body"] . $str;
				else
					$this->code_secret["body"] = $str . $this->code_secret["body"];
			}
			else
			{
				$this->code_secret["body"] = $str;
			}
		}
		// Body Title
		public function get_body_title()
		{
			return ( isset($this->code_secret["body_title"]) ) ? $this->get_secret("body_title"):"";
		}
		public function put_body_title( $str )
		{
			$this->code_secret["body_title"] = $str;
		}
	}
?>
