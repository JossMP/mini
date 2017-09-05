<?php require URI_THEME.'head.php';?>
<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">
	<?php
		require URI_THEME.'header.php';
		require URI_THEME.'sidebar-left.php';
	?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<?php echo $this->content->get("body_header"); ?>
		<section class="content-header">
			<?php echo $this->content->get("body_title"); ?>
			<?php echo $this->content->get("breadcrumb"); ?>
		</section>
		<!-- Main content -->
		<section class="content">
			<div class="error-page">
				<h2 class="headline text-yellow">404</h2>
				<div class="error-content">
					<h3><i class="fa fa-warning text-yellow"></i> Oops! Pagina no encontrada.</h3>
					<p>
						No pudimos encontrar la página que buscabas,
						<br>Prueba <a href="<?php echo URL;?>">Regresar al Inicio</a>
					</p>
				</div>
				<!-- /.error-content -->
			</div>
			<!-- /.error-page -->
			<div class="clearfix"></div>
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
	<?php
		require URI_THEME.'footer.php';
		require URI_THEME.'sidebar-right.php';
	?>
</div>
<?php
	require URI_THEME.'foot.php';
?>
