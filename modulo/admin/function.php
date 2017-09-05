<?php
	class fnAdmin
	{
		var $parents;
		var $Max = 50;
		var $idUsuario;
		function __construct( &$parents )
		{
			$this->parents = $parents;
			$this->idUsuario = $this->parents->session->get("idUsuario");
		}
		function Usuarios()
		{
			$add = '<button class="btn btn-xs btn-success OpenModal" data-target="#ModalPrincipal" data-destine="'.URL.MODULO.'/json/formnuevousuario"><i class="fa fa-plus"></i></button>';
			$add = $this->parents->session->authorized( MODULO, "add", $add );
			$search ='<form id="SearchUser">
				<div class="input-group input-group-sm" style="width: 250px;">
					<input type="text" name="search" class="form-control pull-right" placeholder="Buscar">
					<div class="input-group-btn">
						<button type="submit" class="btn btn-default AjaxSearch" data-destine="'.URL.MODULO.'/json/search_user" data-serialize="SearchUser"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</form>';
			
			$search = $this->parents->session->authorized( MODULO, "search", $search );
						
			$rtn='<div class="row">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">
								Lista de Usuarios
								'.$add.'
							</h3>
							<div class="box-tools">
								'.$search.'
							</div>
						</div>
						<div class="box-body table-responsive no-padding">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>ID</th>
										<th>Nombre Completo</th>
										<th>E-mail</th>
										<th>Estado</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="ListaUsuario">
										'.$this->ListaUsuario().'
								</tbody>
							</table>
						</div>
						
						<div class="box-footer clearfix" id="Paginacion">
							'.$this->Pagination().'
						</div>
					</div>
				</div>
			</div>';
			return $rtn;
		}
		function ListaUsuario($Pag=1, $idUsuario=false)
		{
			$rtn="";
			$desde = ($Pag<=0)?0:($Pag-1)*$this->Max;
			
			$query = "SELECT idUsuario, Usuario, Estado, Nombres, Paterno, Materno, email FROM Usuario WHERE Registrador=".$this->idUsuario." OR idUsuario=".$this->idUsuario." LIMIT ".$desde.",".$this->Max.";";
			
			if( $idUsuario!=false )
			{
				$query = "SELECT idUsuario, Usuario, Estado, Nombres, Paterno, Materno, email FROM Usuario WHERE Registrador=".$this->idUsuario." AND idUsuario=".$idUsuario.";";
			}
			
			if($this->parents->sql->consulta($query))
			{
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					$rtn .= '
						<tr id="u'.$obj->idUsuario.'">
							<td>'.$obj->idUsuario.'</td>
							<td>'.$obj->Nombres." ".$obj->Paterno." ".$obj->Materno.'</td>
							<td>'.$obj->email.'</td>
							<td>'.$obj->Estado.'</td>
							<td>'.$this->Botonera($obj->idUsuario).'</td>
						</tr>';
				}
			}
			return $rtn;
		}
		function Pagination( $Pag=1, $Total = false )
		{
			if( $Total===false )
			{
				$query = "SELECT COUNT(*)Total FROM Usuario WHERE Registrador=".$this->idUsuario.";";
				if($this->parents->sql->consulta($query))
				{
					$resultado = $this->parents->sql->resultado;
					foreach( $resultado as $obj )
					{
						$TotalPag = ($obj->Total/$this->Max);
						$TotalPag = (($TotalPag-(int)$TotalPag)>0)? (int)$TotalPag+1:(int)$TotalPag;
						$TotalPag = ($TotalPag<=0)?1:$TotalPag;
					}
				}
			}
			else
			{
				$TotalPag = ($Total/$this->Max);
				$TotalPag = (($TotalPag-(int)$TotalPag)>0)? (int)$TotalPag+1:(int)$TotalPag;
				$TotalPag = ($TotalPag<=0)?1:$TotalPag;
			}
			$min=$Pag-2;
			$max=$Pag+2;
			
			$min = ($max>$TotalPag)? $min+($TotalPag-$max) : $min;
			$max = ($min<=0)? $max+((-1*$min)+1): $max;

			$min = ($min<=0)? 1 : $min;
			$max = ($max>$TotalPag)? $TotalPag : $max;

			$next=($max >= $TotalPag)?'':'<li><a href="'.URL.MODULO.'/json/mas" class="SendAjax" data-data="'.htmlentities( json_encode( array("Pag"=>($max+1)) ) ).'">&raquo;</a></li>';
			
			$back=($min <= 1)?'':'<li><a href="'.URL.MODULO.'/json/mas" class="SendAjax" data-data="'.htmlentities( json_encode( array("Pag"=>($min)) ) ).'">&laquo;</a></li>';
			
			$rtn="";
			for($i=$min;$i<=$max;$i++)
			{
				$active=($i==$Pag)?" class='active' disabled='disabled'":"";
				$rtn.='<li'.$active.'><a href="'.URL.MODULO.'/json/mas" class="SendAjax" data-data="'.htmlentities(json_encode(array("Pag"=>$i))).'">'.$i.'</a></li>';
			}
			return '<ul class="pagination pagination-sm no-margin pull-right">
				'.$back.'
				'.$rtn.'
				'.$next.'
			</ul>';
		}
		function CargaMas($Pag=1)
		{
			$Form = $this->ListaUsuario($Pag);
			$Pagination = $this->Pagination($Pag);

			$rtn = array(
				"success"=>"true",
				"update"=>array(
					array(
						"id"		=> "ListaUsuario",
						"value"		=> $Form,
						"action"	=> "html"
					),
					array(
						"id"		=> "Paginacion",
						"value"		=> $Pagination,
						"action"	=> "html"
					)
				),
			);
			return json_encode($rtn,JSON_PRETTY_PRINT);
		}
		function Botonera( $idUsuario=false )
		{
			$data = htmlspecialchars( json_encode( array( "idUsuario"=>$idUsuario ) ) );
			
			$edit = '<button type="button" class="btn btn-success btn-xs OpenModal" data-destine="'.URL.MODULO.'/json/formeditausuario" data-target="#ModalPrincipal" data-data="'.$data.'"><i class="fa fa-edit"></i></button> ';
			
			$level = '<button type="button" class="btn btn-primary btn-xs OpenModal" data-destine="'.URL.MODULO.'/json/FormEditPermisos" data-target="#ModalPrincipal" data-target-type="" data-data="'.$data.'"><i class="fa fa-id-card"></i></button> ';
			
			$remove = '<button type="button" class="btn btn-danger btn-xs SendAjax" data-destine="'.URL.MODULO.'/json/borrausuario" data-confirm="Seguro que desea borrar los datos" data-data="'.$data.'"><i class="fa fa-remove"></i></button> ';

			$edit = $this->parents->session->authorized(MODULO,"edit",$edit);
			$level = $this->parents->session->authorized(MODULO,"level",$level);
			$remove = $this->parents->session->authorized(MODULO,"remove",$remove);
			
			return $edit.$level.$remove;
		}
		function SearchUser( $search )
		{
			$Form="";
			$query = "SELECT idUsuario, Usuario, Estado, Nombres, Paterno, Materno, email FROM Usuario WHERE (idUsuario=".$this->idUsuario." OR Registrador=".$this->idUsuario.") AND (Nombres like '%".$search."%' OR Paterno like '%".$search."%' OR Materno like '%".$search."%');";
			
			if($this->parents->sql->consulta($query))
			{
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					$Form .= '
						<tr id="u'.$obj->idUsuario.'">
							<td>'.$obj->idUsuario.'</td>
							<td>'.$obj->Nombres." ".$obj->Paterno." ".$obj->Materno.'</td>
							<td>'.$obj->email.'</td>
							<td>'.$obj->Estado.'</td>
							<td>'.$this->Botonera($obj->idUsuario).'</td>
						</tr>';
				}
			}
			if( $this->parents->sql->cant == 0 )
			{
				$rtn = array(
					"success" 		=> "false",
					"notification" 	=> "No se Encontraron Resultados"
				);
				return json_encode($rtn,JSON_PRETTY_PRINT);
			}
			$Pagination = $this->Pagination(1,$this->parents->sql->cant);

			$rtn = array(
				"success"=>"true",
				"update"=>array(
					array(
						"id"		=> "ListaUsuario",
						"value"		=> $Form,
						"action"	=> "html"
					),
					array(
						"id"		=> "Paginacion",
						"value"		=> $Pagination,
						"action"	=> "html"
					)
				)
			);
			return json_encode($rtn,JSON_PRETTY_PRINT);
		}
		function BorrarUsuario($idUsuario=false)
		{
			$condicion = array( "idUsuario"=>$idUsuario );
			if( $this->parents->sql->eliminar("Usuario",$condicion) )
			{
				$rtn=array(
					"success"	=> true,
					//"notification"=>"Guardado",
					"update"	=> array(
						array(
							"id"		=> "u".$idUsuario,
							"action"	=> "remove"
						)
					)
				);
				return json_encode( $rtn, JSON_PRETTY_PRINT );
			}
			$rtn=array(
				"success"		=> false,
				"notification"	=> "ERROR: No se pudo borrar."
			);
			return json_encode( $rtn, JSON_PRETTY_PRINT );
		}
		function FormNuevoUsuario()
		{
			$form='<form class="form-horizontal col-xs-12" id="FormNuevoUsuario">
				<div class="form-group">
					<label for="Nombres" class="col-sm-3 control-label">Nombres: </label>
					<div class="col-sm-9">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
							<input type="text" class="form-control" id="Nombres" name="Nombres" placeholder="Nombres">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="Paterno" class="col-sm-3 control-label">Ap. Paterno: </label>
					<div class="col-sm-9">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
							<input type="text" class="form-control" id="Paterno" name="Paterno" placeholder="Apellido Paterno">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="Materno" class="col-sm-3 control-label">Ap. Materno: </label>
					<div class="col-sm-9">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
							<input type="text" class="form-control" id="Materno" name="Materno" placeholder="Apellido Materno">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="Usuario" class="col-sm-3 control-label">Usuario: </label>
					<div class="col-sm-9">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
							<input type="text" class="form-control" id="Usuario" name="Usuario" placeholder="Nombre de Usuario">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="Clave" class="col-sm-3 control-label">Clave: </label>
					<div class="col-sm-9">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-key"></i></span>
							<input type="password" class="form-control" id="Clave" name="Clave" placeholder="Clave de acceso">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-sm-3 control-label">E-Mail: </label>
					<div class="col-sm-9">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
							<input type="text" class="form-control" id="email" name="email" placeholder="Correo Electronico">
						</div>
					</div>
				</div>
			</form>';
			
			$btn='<button type="submit" class="btn btn-success SendAjax" data-destine="'.URL.MODULO.'/json/guardausuario" data-serialize="FormNuevoUsuario"><i class="fa fa-check"></i> Guardar</button>';
			$rtn = array(
				"success"=>true,
				"update"=>array(
					array(
						"id"		=> "ModalTitle",
						"action"	=> "html",
						"value"		=> "NUEVO USUARIO"
					),
					array(
						"id"		=> "ModalBody",
						"action"	=> "html",
						"value"		=> $form
					),
					array(
						"id"		=> "addButton",
						"action"	=> "html",
						"value"		=> $btn
					)
				)
			);
			return json_encode( $rtn, JSON_PRETTY_PRINT );
		}
		function FormEditaUsuario( $idUsuario )
		{
			$form="";
			$query = "SELECT idUsuario,Usuario,Clave,Estado,Paterno,Materno,Nombres,DNI,FotoPerfil,email,movil FROM Usuario WHERE idUsuario=".$idUsuario.";";
			
			if($this->parents->sql->consulta($query))
			{
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					$form='<form class="form-horizontal col-xs-12" id="FormEditaUsuario">
						<div class="form-group">
							<label for="Nombres" class="col-sm-3 control-label">Nombres: </label>
							<div class="col-sm-9">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
									<input type="text" class="form-control" id="Nombres" name="Nombres" placeholder="Nombres" value="'.$obj->Nombres.'">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="Paterno" class="col-sm-3 control-label">Ap. Paterno: </label>
							<div class="col-sm-9">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
									<input type="text" class="form-control" id="Paterno" name="Paterno" placeholder="Apellido Paterno" value="'.$obj->Paterno.'">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="Materno" class="col-sm-3 control-label">Ap. Materno: </label>
							<div class="col-sm-9">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
									<input type="text" class="form-control" id="Materno" name="Materno" placeholder="Apellido Materno" value="'.$obj->Materno.'">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="Usuario" class="col-sm-3 control-label">Usuario: </label>
							<div class="col-sm-9">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input type="text" class="form-control" value="'.$obj->Usuario.'" disabled>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="Clave" class="col-sm-3 control-label">Clave: </label>
							<div class="col-sm-9">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-key"></i></span>
									<input type="password" class="form-control" id="Clave" name="Clave" placeholder="Clave de acceso" value="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label">E-Mail: </label>
							<div class="col-sm-9">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input type="text" class="form-control" id="email" name="email" placeholder="Correo Electronico" value="'.$obj->email.'">
								</div>
							</div>
						</div>
					</form>';
					$json = htmlspecialchars(json_encode(array("idUsuario"=>$obj->idUsuario)));
					$btn = '<button type="submit" class="btn btn-success CloseModal" data-destine="'.URL.MODULO.'/json/editausuario" data-serialize="FormEditaUsuario" data-target="#ModalPrincipal" data-data="'.$json.'"><i class="fa fa-check"></i> Guardar</button>';
					$rtn = array(
						"success"=>true,
						"update"=>array(
							array(
								"id"		=> "ModalTitle",
								"action"	=> "html",
								"value"		=> "EDITA USUARIO"
							),
							array(
								"id"		=> "ModalBody",
								"action"	=> "html",
								"value"		=> $form
							),
							array(
								"id"		=> "addButton",
								"action"	=> "html",
								"value"		=> $btn
							)
						)
					);
					return json_encode( $rtn, JSON_PRETTY_PRINT );
				}
			}
			$rtn = array(
				"success"=>true,
				"notification"=>"ERROR: registro no encontrado."
			);
			return json_encode( $rtn, JSON_PRETTY_PRINT );
		}
		function GuardaUsuario( $datos )
		{
			$datos["Clave"] = md5($datos["Clave"]);
			$datos["Registrador"] = $this->idUsuario;
			
			if( !isset($datos["Usuario"]) || !isset($datos["Clave"]) || !isset($datos["Nombres"]) || !isset($datos["email"]) )
			{
				$rtn = array(
					"success"		=> false,
					"notification"	=> "Todo los Datos son requeridos"
				);
				return json_encode($rtn, JSON_PRETTY_PRINT);
			}
			
			if( $this->ExisteUsuario( $datos["Usuario"] ) )
			{
				$rtn = array(
					"success"		=> false,
					"notification"	=> "El Usuario ya Existe!",
				);
				return json_encode($rtn, JSON_PRETTY_PRINT);
			}
			if( $this->parents->sql->insertar("Usuario",$datos) )
			{
				$NewUser = $this->ListaUsuario( false, $this->parents->sql->LAST_INSERT_ID() );
				$rtn = array(
					"success"=>true,
					"notification"=>"Creado correctamente...",
					"update"=>array(
						array(
							"id"		=> "ListaUsuario",
							"action"	=> "prepend",
							"value"		=> $NewUser
						)
					)
				);
				return json_encode( $rtn, JSON_PRETTY_PRINT );	
			}
			
			$rtn = array(
				"success"		=> false,
				"notification"	=> "ERROR: se ha producido un error al crear el usuario."
			);
			return json_encode($rtn, JSON_PRETTY_PRINT);
		}
		
		function EditaUsuario( $datos )
		{
			if( isset($datos["Clave"]) && $datos["Clave"]!="" )
			{
				$datos["Clave"] = md5($datos["Clave"]);
			}
			else
			{
				unset( $datos["Clave"] );
			}
			$datos["Registrador"] = $this->idUsuario;
			
			if( !isset($datos["Nombres"]) || !isset($datos["email"]) )
			{
				$rtn = array(
					"success"		=> false,
					"notification"	=> "Complete los datos."
				);
				return json_encode($rtn, JSON_PRETTY_PRINT);
			}
			if( $this->ValidaUsuario( $datos["idUsuario"], $this->idUsuario ) )
			{
				$condicion = array(
					"idUsuario" 	=> $datos["idUsuario"]
				);
				if( $this->parents->sql->modificar( "Usuario", $datos, $condicion, "AND" ) )
				{
					$user = $this->ListaUsuario( false, $datos["idUsuario"] );
					$rtn = array(
						"success"=>true,
						"update"=>array(
							array(
								"id"		=> "u1",
								"action"	=> "replaceWith",
								"value"		=> $user
							)
						)
					);
					return json_encode( $rtn, JSON_PRETTY_PRINT );	
				}
				$rtn = array(
					"success"		=> false,
					"notification"	=> "ERROR: No se ha podido modificar el registro.",
				);
				return json_encode($rtn, JSON_PRETTY_PRINT);
			}
			else
			{
				$rtn = array(
					"success"		=> false,
					"notification"	=> "No tiene permiso para modificar este usuario.",
				);
				return json_encode($rtn, JSON_PRETTY_PRINT);
			}
		}
		function ValidaUsuario( $idUsuario, $Registrador )
		{
			$query = "SELECT COUNT(*)Cant FROM Usuario WHERE idUsuario=".$idUsuario." AND Registrador=".$Registrador.";";
			
			if($idUsuario == $Registrador)
				$query = "SELECT COUNT(*)Cant FROM Usuario WHERE idUsuario=".$idUsuario.";";
				
			if( $this->parents->sql->consulta($query) )
			{
				$resultado = $this->parents->sql->resultado;
				foreach($resultado as $obj)
				{
					if( $obj->Cant > 0 )
					{
						return true;
					}
				}
			}
			return false;
		}
		function ExisteUsuario( $Usuario = "")
		{
			$query = "SELECT COUNT(*)Cant FROM Usuario WHERE Usuario like '".$Usuario."';";
			if( $this->parents->sql->consulta($query) )
			{
				$resultado = $this->parents->sql->resultado;
				foreach($resultado as $obj)
				{
					if( $obj->Cant > 0 )
					{
						return true;
					}
				}
			}
			return false;
		}
		
		
		// Funciones para asignar permisos a cada usuario
		public function FormPermisos($idUsuario)
		{
			$permisos = $this->parents->mod->get_permission(); // Lista de Permisos Asignables
			
			$pu = $this->PermisosUsuario( $idUsuario ); // Lista de Los permisos Asignados a un Usuario
			$form = '<form id="FormPermisosUsuario" class="col-xs-12">'.$this->fichaUsuario($idUsuario);
			foreach( $permisos as $mod => $cd )
			{
				$check_mod = ( isset( $pu[$mod][$mod] ) ) ? "checked":"";
				$form.='<div class="col-xs-12">
				<div class="box box-primary box-solid collapsed-box">
					<div class="box-header with-border">
						<h3 class="box-title"><input type="checkbox" '.$check_mod.' name="permiso['.$mod.']['.$mod.']"> <span class="text-bold">Módulo:</span> '.strtoupper($mod).'</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">';
				foreach( $cd as $code => $desc )
				{
					$check_per = ( isset( $pu[$mod][$code] ) ) ? "checked":"";
					$form .= '<div class="checkbox">
								<label class="col-xs-6">
									<input type="checkbox" '.$check_per.' name="permiso['.$mod.']['.$code.']"> '.$desc.'
								</label>
							</div>';
				}
				$form.='</div></div></div></div>';
			}
			$form.='</form>';
			return $form;
		}
		function PermisosUsuario($idUsuario)
		{
			$rtn = array();
			$query="SELECT Modulo,Codigo FROM Permiso WHERE idUsuario=".$idUsuario." ORDER BY Modulo ASC;";
			if( $this->parents->sql->consulta( $query ) )
			{
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					$rtn[$obj->Modulo][$obj->Codigo] = true;
				}
			}
			return $rtn;
		}
		function fichaUsuario($idUsuario)
		{
			$form="";
			$query="SELECT idUsuario, Usuario, Estado, Paterno, Materno, Nombres, FotoPerfil FROM Usuario WHERE idUsuario=".$idUsuario.";";
			if( $this->parents->sql->consulta( $query ) )
			{
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					$isCheck = ( $obj->Estado=="Activo" )?'<i class="pull-right fa fa-check-circle"></i>':'<i class="pull-right fa fa-ban"></i>';
					$form .= '<div class="col-xs-3 nospace-right text-center">
						<img src="'.URL.'images/avatar/'.$obj->FotoPerfil.'" class="img-thumbnail nospace" width="100%" alt="'.$obj->Usuario.'">
						'.strtoupper($obj->Usuario).'
					</div>
					<div class="col-xs-9">
						<div class="callout callout-success lead">
							<h4 class="text-center">'.strtoupper($obj->Nombres." ".$obj->Paterno." ".$obj->Materno).' '.$isCheck.'</h4>
							<p>Informacion de usuario no Disponible</p>
						</div>
					</div>
					<div class="clearfix"></div>
					<hr>';
				}
			}
			return $form;
		}
		public function ModalPermisos($idUsuario)
		{
			$form= $this->FormPermisos($idUsuario);
			$data= htmlspecialchars(json_encode(array("idUsuario"=>$idUsuario)));
			$btn = '<button class="btn btn-success CloseModal" data-target="#ModalPrincipal" data-serialize="FormPermisosUsuario" data-destine="'.URL.MODULO.'/json/EditPermisos" data-data="'.$data.'"><i class="fa fa-save"></i> Guardar</button>';
			$rtn = array(
				"success"=>true,
				"update"=>array(
					array(
						"id"		=> "ModalTitle",
						"action"	=> "html",
						"value"		=> "ADD PERMISOS"
					),
					array(
						"id"		=> "ModalBody",
						"action"	=> "html",
						"value"		=> $form
					),
					array(
						"id"		=> "addButton",
						"action"	=> "html",
						"value"		=> $btn
					)
				)
			);
			return json_encode( $rtn, JSON_PRETTY_PRINT );
		}
		function EditPermisos( $datos )
		{
			$idUsuario = $datos["idUsuario"];
			$Permisos = ( isset($datos["permiso"]) ) ? $datos["permiso"] : array();
			$query="DELETE FROM Permiso WHERE  idUsuario=".$idUsuario.";";
			if( $this->parents->sql->consulta($query) )
			{
				if( is_array($Permisos) )
				{
					$datos = array();
					foreach( $Permisos as $mod => $cd )
					{
						if( is_array($cd) )
						{
							foreach( $cd as $code=>$v )
							{
								$datos[] = array(
									"Modulo" 		=> $mod,
									"Codigo" 		=> $code,
									"idUsuario" 	=> $idUsuario
								);
							}
						}
					}
					$this->parents->sql->insertarAll( "Permiso" , $datos );
				}
			}
			$rtn = array(
				"success"		=> true,
				"notification"	=> "Se ha modificado los permisos."
			);
			return json_encode($rtn, JSON_PRETTY_PRINT);
		}
	}
?>












