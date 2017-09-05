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
		<section class="content-header">
			<?php echo $this->content->get("body_header"); ?>
		</section>
		<section class="content-header">
			<?php echo $this->content->get("body_title"); ?>
			<?php echo $this->content->get("breadcrumb"); ?>
		</section>
		<!-- Main content -->
		<section class="content">
			<?php echo $this->content->get_body(); ?>
			<div class="clearfix"></div>
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
	<!-- Modal Principal -->
	<?php echo $this->content->get("ModalPrincipal");?>
	<!-- ./Modal Principal -->
	<?php
		require URI_THEME.'footer.php';
		require URI_THEME.'sidebar-right.php';
	?>
</div>
<?php
	require URI_THEME.'foot.php';
?>
