	<!-- Main Header -->
	<header class="main-header">
		<!-- Logo -->
		<a href="<?php echo URL;?>" class="logo">
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"><?php echo $this->content->get("mini");?></span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><?php echo $this->content->get("long");?></span>
		</a>

		<!-- Header Navbar -->
		<nav class="navbar navbar-static-top" role="navigation">
			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<!-- Navbar Right Menu -->
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<!-- Messages Menu -->
					<?php echo $this->content->get("nav_message");?>
					<!-- /.messages-menu -->

					<!-- Notifications Menu -->
					<?php echo $this->content->get("nav_notification");?>
					<!-- /.Notifications Menu -->

					<!-- Tasks Menu -->
					<?php echo $this->content->get("nav_task");?>
					<!-- /.Tasks Menu -->

					<!-- UserBar Menu -->
					<?php echo $this->content->get("nav_userbar");?>
					<!-- /.UserBar Menu -->

					<!-- right SideBar Menu -->
					<li><a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a></li>
					<!-- /.right SideBar Menu -->
				</ul>
			</div>
		</nav>
	</header>
