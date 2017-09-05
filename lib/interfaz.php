<?php
	class Interfaz
	{
		function __construct(&$parents)
		{
			$this->parents = $parents;
			$this->init();
		}
		private function init()
		{
			$this->get_navlogo();
			$this->get_navUserbar();
			$this->get_modal("ModalPrincipal");
			if( $this->parents->session->check_login() )
			{
				$idUsuario = $this->parents->session->get("idUsuario");
				$this->get_navMessages($idUsuario);
				$this->get_navNotification($idUsuario);
				$this->get_navTask($idUsuario);
				$this->sidebar_left_menu(); // only user
			}
		}
		public function get_modal($id, $title="", $body="", $button="")
		{
			$rtn = '
			<div class="modal fade" id="'.$id.'">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="ModalTitle">'.$title.'</h4>
							</div>
						<div class="modal-body">
							<div class="row" id="ModalBody">
								'.$body.'
							</div>
						</div>
						<div class="modal-footer">
							<span id="addButton">'.$button.'</span>
							<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cerrar</button>
						</div>
					</div>
				</div>
			</div>';
			$this->parents->content->register($id, $rtn);
		}
		public function get_navlogo($mini='<b>S</b>AE',$long='<b>Admin</b>SAE')
		{
			$this->parents->content->register("mini",$mini);
			$this->parents->content->register("long",$long);
		}
		public function get_navMessages($idUsuario)
		{
			$rtn="";
			$query = "SELECT Paterno,Materno,Nombres,FotoPerfil,Mensaje.idMensaje,Mensaje.idUsuario idOrigen,Visto,DestinoMensaje.idUsuario idDestino, Mensaje,FechaRegistro FROM DestinoMensaje INNER JOIN Mensaje ON Mensaje.idMensaje = DestinoMensaje.idMensaje INNER JOIN Usuario ON Usuario.idUsuario=Mensaje.idUsuario WHERE DestinoMensaje.idUsuario=".$idUsuario." AND Visto = 'No' ORDER BY FechaRegistro DESC LIMIT 10;";
			if( $this->parents->sql->consulta($query) )
			{
				$msj = '';
				$cont = 0;
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					$cont++;
					$msj .= '<li class="nav-item">
								<a href="#" class="nav-link">
									<div class="pull-left">
										<img src="'.URL.'images/avatar/'.$obj->FotoPerfil.'" class="img-circle" alt="User">
									</div>
									<h4>
										'.$obj->Nombres.' '.$obj->Paterno.' '.$obj->Materno.'
										<small><i class="fa fa-clock-o"></i> '.$this->parents->fn->get_transcurrido(strtotime( $obj->FechaRegistro )).'</small>
									</h4>
									<p>'.$obj->Mensaje.'</p>
								</a>
							</li>';
				}
				$total = ( $cont==0 ) ? "" : $cont;
				$span = ( $cont==0 ) ? "" : '<span class="label label-success">'.$total.'</span>';
				$icon = '<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-envelope-o"></i>
					'.$span.'
				</a>';

				$title = '<li class="header">Tienes '.$cont.' Mensaje(s)</li>';
				$footer = '<li class="footer"><a href="#">Ver todo los mensajes</a></li>';
				$msj = '<li><ul class="menu">'.$msj.'</ul></li>';
				$rtn = '<li class="dropdown messages-menu">
					'.$icon.'
					<ul class="dropdown-menu">
						'.$title.'
						'.$msj.'
						'.$footer.'
					</ul>
				</li>';
			}
			$this->parents->content->register("nav_message", $rtn);
		}
		public function get_navNotification($idUsuario)
		{
			$rtn="";
			$query = "SELECT idNotificacion,Mensaje,icon,FechaRegistro,click,Visto,idUsuario FROM Notificacion WHERE idUsuario=".$idUsuario." AND Visto = 'No' ORDER BY FechaRegistro DESC LIMIT 15;";
			if( $this->parents->sql->consulta($query) )
			{
				$msj = '';
				$cont = 0;
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					$cont++;
					$msj .= '<li>
								<a href="#">
									<i class="fa fa-'.$obj->icon.' text-red"></i> '.$obj->Mensaje.'
								</a>
							</li>';
				}
				$total = ( $cont==0 ) ? "" : $cont;
				$span = ( $cont==0 ) ? "" : '<span class="label label-warning">'.$total.'</span>';
				$icon = '<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-bell-o"></i>
					'.$span.'
				</a>';

				$title = '<li class="header">Tienes '.$cont.' notificacion(es)</li>';
				$footer = '<li class="footer"><a href="#">Ver todas las notificaciones</a></li>';
				$msj = '<li><ul class="menu">'.$msj.'</ul></li>';
				$rtn = '<li class="dropdown notifications-menu">
					'.$icon.'
					<ul class="dropdown-menu">
						'.$title.'
						'.$msj.'
						'.$footer.'
					</ul>
				</li>';
			}
			$this->parents->content->register("nav_notification", $rtn);
		}
		public function get_navTask($idUsuario)
		{
			$rtn="";
			$query = "SELECT idTarea,Tarea,Avance,FechaRegistro,idUsuario,click FROM Tarea WHERE idUsuario=".$idUsuario." AND Avance < 100 ORDER BY FechaRegistro DESC LIMIT 15;";
			if( $this->parents->sql->consulta($query) )
			{
				$msj = "";
				$cont = 0;
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					$cont++;
					$msj .= '<li>
								<a href="#">
									<h3>
										'.$obj->Tarea.'
										<small class="pull-right">'.$obj->Avance.'%</small>
									</h3>
									<div class="progress xs">
										<div class="progress-bar progress-bar-aqua" style="width: '.$obj->Avance.'%" role="progressbar" aria-valuenow="'.$obj->Avance.'" aria-valuemin="0" aria-valuemax="100">
											<span class="sr-only">'.$obj->Avance.'% Completado</span>
										</div>
									</div>
								</a>
							</li>';
				}

				$total = ( $cont==0 ) ? "" : $cont;
				$span = ( $cont==0 ) ? "" : '<span class="label label-danger">'.$total.'</span>';
				$icon = '<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-flag-o"></i>
					'.$span.'
				</a>';

				$title = '<li class="header">Tienes '.$cont.' tarea(s) pendiente(s)</li>';
				$footer = '<li class="footer"><a href="#">Ver todas las tareas</a></li>';
				$msj = '<li><ul class="menu">'.$msj.'</ul></li>';
				$rtn = '<li class="dropdown tasks-menu">
					'.$icon.'
					<ul class="dropdown-menu">
						'.$title.'
						'.$msj.'
						'.$footer.'
					</ul>
				</li>';
			}
			$this->parents->content->register("nav_task", $rtn);
		}
		public function get_navUserbar()
		{
			if( $this->parents->session->check_login() )
			{
				$idUsuario = $this->parents->session->get("idUsuario");
				$query = "SELECT idUsuario, Nombres, Paterno, Materno, Usuario, FotoPerfil FROM Usuario WHERE Usuario.idUsuario=".$idUsuario.";";
				if( $this->parents->sql->consulta($query) )
				{
					$resultado = $this->parents->sql->resultado;
					foreach( $resultado as $obj )
					{
						$this->sidebar_user_panel($obj->Nombres.' '.$obj->Paterno.' '.$obj->Materno, URL.'images/avatar/'.$obj->FotoPerfil);
						$rtn='<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="'.URL.'images/avatar/'.$obj->FotoPerfil.'" class="user-image" alt="'.$obj->Usuario.'">
								<span class="hidden-xs">'.$obj->Nombres.' '.$obj->Paterno.' '.$obj->Materno.'</span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-header">
									<img src="'.URL.'images/avatar/'.$obj->FotoPerfil.'" class="img-circle" alt="'.$obj->Usuario.'">
								<p>
									'.$obj->Nombres.' '.$obj->Paterno.' '.$obj->Materno.'
									<small>('.$obj->Usuario.')</small>
								</p>
								</li>
								<!-- Menu Body -->
								<li class="user-body">
									'.$this->parents->content->get("panel_user_body").'
								</li>
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
										<a href="#" class="btn btn-primary btn-flat"><i class="fa fa-user"></i> Perfil</a>
									</div>
									<div class="pull-right">
										<button class="btn bg-navy btn-flat SendAjax" data-destine="'.URL.'login/json/out" ><i class="fa fa-power-off"></i> Salir</button>
									</div>
								</li>
							</ul>
						</li>';
					}
				}
			}
			else
			{
				$rtn='<li class="dropdown user user-menu">
					<a href="'.URL.'login" class="btn btn-primary">
						<i class="fa fa-user"></i> Iniciar sesion
					</a>
				</li>';
			}
			$this->parents->content->register("nav_userbar", $rtn);
		}
		public function breadcrumb( $list = array() )
		{
			$item='';
			$cont = count( $list );
			foreach( $list as $v )
			{
				if( is_array($v) && count($v)==3 )
				{
					$item .= '<li><a href="'.$v[1].'">
						<i class="'.$v[2].'"></i> '.$v[0].'
					</a></li>';
				}
				else if( is_array($v) && count($v)==2 )
				{
					$item .= '<li><a href="'.$v[1].'">
						'.$v[0].'
					</a></li>';
				}
				else if( is_array($v) && count($v)==2 )
				{
					$item .= '<li><a href="'.$v[1].'">
						'.$v[0].'
					</a></li>';
				}
			}
			$rtn = '<ol class="breadcrumb">'.$item.'</ol>';
			$this->parents->content->register( "breadcrumb", $rtn );
		}
		public function title_subtitle($title, $subtitle="")
		{
			if($title!="")
			{
				$rtn='<h1>'.$title.'<small>'.$subtitle.'</small></h1>';
				$this->parents->content->register( "body_title", $rtn );
			}
		}
		public function sidebar_left_menu()
		{
			$config = $this->parents->mod->get_config();
			if( $config != null )
			{
				$Menu="";
				foreach($config as $i => $v)
				{
					$json = json_decode( $v['json'] );
					if( isset($json->UserMenu) && $json->UserMenu=="true" )
					{
						if( isset($json->Menu) )
						{
							foreach($json->Menu as $m)
							{
								if( $this->parents->session->get_permission( $v["directorio"],$v["directorio"] ) )
								{
									$SubMenu='';
									$issm='';
									$isism='';
									if( isset($m->SubMenu) && count($m->SubMenu)>0 )
									{
										foreach( $m->SubMenu as $sm )
										{
											if( isset($sm->Permiso) && $sm->Permiso!="" )
											{
												if( $this->parents->session->get_permission( $v['directorio'], $sm->Permiso ) )
												{
													$attr = ( isset($sm->Attribute) ) ? $sm->Attribute : array();
													$SubMenu.='<li class="">
														<a href="'.URL.$v['directorio'].$sm->URL.'/" '.$this->parents->fn->attribute($attr).'>
															<i class="'.$sm->Icon.'"></i><span>'.$sm->Name.'</span>
														</a>
													</li>';
													$issm = 'treeview';
													$isism = '<span class="pull-right-container">
														<i class="fa fa-angle-left pull-right"></i>
													</span>';
												}
											}
										}
										$SubMenu = ( $SubMenu!='' ) ? '<ul class="treeview-menu">'.$SubMenu.'</ul>':'';
									}
									$active = (MODULO == $v['directorio'])?' active':'';
									$attr = ( isset($m->Attribute) ) ? $m->Attribute : array();
									$Menu.='
									<li class="'.$issm.$active.'">
										<a href="'.URL.$v['directorio'].$m->URL.'/" '.$this->parents->fn->attribute($attr).'>
											<i class="'.$m->Icon.'"></i><span>'.$m->Name.'</span>
											'.$isism.'
										</a>
										'.$SubMenu.'
									</li>';
								}
							}
						}
					}
				}
				$rtn = '<ul class="sidebar-menu" id="sidebar_left_menu">
					<!--<li class="header">MENU</li>-->
					'.$Menu.'
				</ul>';
				$this->parents->content->register( "sidebar_left_body", $rtn );
			}
		}
		function sidebar_user_panel($Name,$image)
		{
			$rtn = '<div class="user-panel">
				<div class="pull-left image">
					<img src="'.$image.'" class="img-circle" alt="User">
				</div>
				<div class="pull-left info">
					<p>'.$Name.'</p>
					<!-- Status -->
					<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
				</div>
			</div>';
			$this->parents->content->register("sidebar_user_panel",$rtn);
		}
	}
?>
