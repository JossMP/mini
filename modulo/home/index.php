<?php
	require dirname(__FILE__).'/function.php';
	class Home extends Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->fn->redirection( URL."login", $this->session->check_login() );
		}
		function index()
		{
			$this->content->register("sidebar_left_footer","HOLA MUNDO");
			$this->content->put_title("Pagina de Inicio");
			$this->interfaz->title_subtitle("TITULO","Sub-Titulo");
			
			$list = array(
				array("Inicio",URL,"fa fa-dashboard")
			);
			$this->interfaz->breadcrumb($list);
			$this->content->put_body("HOLA MUNDO");
			
			require URI_THEME.'content.php';
		}
	}
