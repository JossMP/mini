<?php
	require URI_MOD.MODULO.'/function.php';
	class licencia extends Controller
	{
		function index()
		{
			$fn = new fnLicencia();
			
			$this->content->put_title("Licencia");
			
			$list = array(
				array("Inicio",URL),
				array("Licencia","")
			);
			//$this->content->put_body_title("LICENCIA");
			//$this->mod->breadcrumb($list);
			$this->content->put_body( $fn->VerLicencia() );
			require URI_THEME.'head.php';
			require URI_THEME.'content.php';
		}
	}
?>
