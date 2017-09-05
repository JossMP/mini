<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo $this->content->get_title();?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="description" content="<?php echo $this->content->get("document_description"); ?>">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="<?php echo URL_THEME;?>bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
		<link rel="stylesheet" href="<?php echo URL_THEME;?>/plugins/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo URL_THEME;?>dist/css/AdminLTE.css">

		<link rel="stylesheet" href="<?php echo URL_THEME;?>dist/css/skins/skin-blue.min.css">
		<?php echo $this->content->get_css();?>
		<?php echo $this->content->get_style();?>
		<link rel="stylesheet" href="<?php echo URL;?>css/global.css">
		<link rel="stylesheet" href="<?php echo URL;?>css/bootstrap-theme2.css">
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
