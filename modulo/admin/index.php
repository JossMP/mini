<?php
	require dirname(__FILE__)."/function.php";
	class Admin extends Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->fn->redirection( URL."login", $this->session->check_login() );
		}
		function index()
		{
			$this->content->put_title("Lista de Usuario");
			$this->interfaz->title_subtitle("Usuarios", "Lista de Usuario");
			$list = array(
				array("inicio",URL),
				array("Admin",URL.MODULO)
			);
			$this->interfaz->breadcrumb( $list );
			
			$style='.permiso{
    height: 80px;
    overflow-y: scroll;
    padding: 0px;
    margin-left: 0px;
    margin-top: -10px;
    margin-right: -10px;
    margin-bottom: -10px;
}';
			
			$this->content->add_style( $style );
			
			$fn = new fnAdmin( $this );
			$this->content->put_body( $fn->Usuarios() );
			require URI_THEME.'content.php';
		}
		function Json( $modo=false )
		{
			header('Content-Type: application/json');
			$fn = new fnAdmin($this);
			switch($modo)
			{
				case "mas":
					echo $fn->CargaMas( $_REQUEST["Pag"] );
					exit();
				break;
				case "search_user":
					echo $fn->SearchUser( $_REQUEST["search"] );
					exit();
				break;
				case "borrausuario":
					echo $fn->BorrarUsuario( $_REQUEST["idUsuario"] );
					exit();
				break;
				case "formnuevousuario":
					echo $fn->FormNuevoUsuario();
					exit();
				break;
				case "guardausuario":
					echo $fn->GuardaUsuario($_REQUEST);
					exit();
				break;
				case "FormEditPermisos":
					echo $fn->ModalPermisos( $_REQUEST["idUsuario"] );
					exit();
				break;
				case "EditPermisos":
					echo $fn->EditPermisos( $_REQUEST );
					exit();
				break;
				case "formeditausuario":
					echo $fn->FormEditaUsuario( $_REQUEST["idUsuario"] );
					exit();
				break;
				case "editausuario":
					echo $fn->EditaUsuario( $_REQUEST );
					exit();
				break;
				default:
					echo json_encode(array("success"=>false,"notification"=>"Accion no definida..."),JSON_PRETTY_PRINT);
					exit();
				break;
			}
		}
	}

