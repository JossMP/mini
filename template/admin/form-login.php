<?php require URI_THEME.'head.php';?>
<body class="hold-transition login-page">
<div class="login-box">
	<div class="login-logo">
		<a href="<?php echo URL;?>"><b>Admin</b>SAE</a>
	</div>
	<!-- /.login-logo -->
	<div id="login-alert">
	</div>
	<div class="login-box-body">
		<p class="login-box-msg">Inicie sesión para acceder a su cuenta</p>

		<form id="Formlogin" name="Formlogin" action="<?php echo URL.MODULO."/in";?>">
			<div class="form-group has-feedback">
				<input type="text" class="form-control" placeholder="Usuario o email" name="login">
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" class="form-control" placeholder="Password"  name="password">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
						<label>
							<input type="checkbox"  name="check"> Recordar inicio de sesion
						</label>
					</div>
				</div>
				<!-- /.col -->
				<div class="col-xs-4">
					<button type="submit" class="SendAjax btn btn-primary btn-block btn-flat" data-destine="<?php echo URL.MODULO."/json/in";?>" data-serialize="Formlogin">Acceder</button>
				</div>
				<!-- /.col -->
			</div>
		</form>

		<div class="social-auth-links text-center">
			<p>- O -</p>
			<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Acceder usando
				Facebook</a>
			<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Acceder usando
				Google+</a>
		</div>
		<!-- /.social-auth-links -->

		<a href="#">Olvidé mi contraseña</a><br>
		<a href="register.html" class="text-center">Registrar una nueva cuenta</a>

	</div>
</div>
<?php
	require URI_THEME.'foot.php';
?>
