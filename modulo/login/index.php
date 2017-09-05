<?php
	require URI_MOD.MODULO.'/function.php';
	class Login extends Controller
	{
		function index()
		{
			$this->content->put_title("Iniciar Sesion");
			$this->content->add_css(URL_THEME."/plugins/iCheck/square/blue.css");
			$this->content->add_js(URL_THEME."/plugins/iCheck/icheck.min.js");
			$js="$(function () {
				$('input').iCheck({
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
			});";
			$this->content->add_script($js);
			require URI_THEME.'form-login.php';
		}
		function Json($modo)
		{
			header('Content-Type: application/json');
			$this->fn = new fnLogin( $this );
			switch($modo)
			{
				case "in":
					echo $this->fn->Login($_REQUEST['login'],$_REQUEST['password']);
				break;
				case "out":
					echo $this->fn->Logout();
				break;
				case "fb_in":
					echo $thi->fn->LoginFacebook();
				break;
				case "fb_out":
					echo $thi->fn->LoginFacebook();
				break;
			}
		}
		function facebook()
		{
			
		}
	}
