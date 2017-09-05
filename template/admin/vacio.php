<?php require URI_THEME.'head.php';?>
<body class="hold-transition skin-blue fixed">
<div class="">
	<?php echo $this->content->get_body(); ?>
	<div class="clearfix"></div>
</div>
<?php echo $this->content->get("ModalPrincipal");?>
<?php
	require URI_THEME.'foot.php';
?>
