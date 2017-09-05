<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
			<!-- Sidebar user panel (optional) -->
			<?php echo  $this->content->get("sidebar_user_panel");?>
			<!-- search form (Optional) -->
			<form action="#" method="get" class="sidebar-form">
				<div class="input-group">
					<input type="text" name="q" class="form-control" placeholder="Buscar...">
					<span class="input-group-btn">
						<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
						</button>
					</span>
				</div>
			</form>
			<!-- /.search form -->
			<!-- Sidebar Menu -->
			<?php echo  $this->content->get("sidebar_left_body");?>
			<!-- /.sidebar-menu -->
			<!-- sidebar-footer -->
			<div class="info">
				<hr>
				<?php echo  $this->content->get("sidebar_left_footer");?>
			</div>
			<!-- /.sidebar-footer -->
		</section>
		<!-- /.sidebar -->
	</aside>
