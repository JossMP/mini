<?php
	class sql
	{
		var $resultado;
		var $db;
		var $id=0;
		var $cant=0;

		public function __construct()
		{
			$this->Conectar();
		}

		private function Conectar()
		{
			try{
				$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ);
				$this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
			}
			catch(Exception $e)
			{
				echo "SERVIDOR DE BASE DE DATOS NO DISPONIBLE";
				exit();
			}
		}

		function insertar($tabla,$datos)//$datos es de Tipo Array array(idUnidad=>1,nombre=>Josue)
		{
			$this->cant=0;
			$keystr = array();
			$valstr = array();
			$sql="";

			$query = $this->db->prepare("DESCRIBE $tabla;");
			if($query->execute())
			{
				foreach($query->fetchAll() as $obj)
				{
					if(isset($datos[$obj->Field]))
					{
						$keystr[]=$obj->Field;
						$valstr[]="'".$datos[$obj->Field]."'";
					}
				}
			}
			$sql="INSERT INTO $tabla (" . implode(",", $keystr) . ") VALUES (" . implode(",", $valstr) . ");";
			$query = $this->db->prepare($sql);
			if( $query->execute() )
			{
				$this->id = $this->db->lastInsertId();
				return true;
			}
			return false;
		}

		function insertarAll( $tabla, $datos )//$datos es de Tipo Array array( array(idUnidad=>1,nombre=>Josue),array(....))
		{
			$this->cant=0;
			$keystr = array();
			$valstr = array();
			$values = array();
			$sql="";

			$query = $this->db->prepare("DESCRIBE $tabla;");
			if($query->execute())
			{
				foreach($query->fetchAll() as $obj)
				{
					$keystr[]=$obj->Field;
					foreach( $datos as $i => $dato )
					{
						if( isset($dato[$obj->Field]) )
						{
							$valstr[$i][]="'".$dato[$obj->Field]."'";
						}
						else
						{
							$valstr[$i][]="null";
						}
					}
				}
				foreach( $valstr as $v )
				{
					$values[] = implode(",", $v);
				}
			}
			if( count($values) <= 0 )
			{
				return false;
			}
			$keystr = implode( ",", $keystr );
			$valstr = implode( "),(", $values );

			$sql="INSERT INTO $tabla ($keystr) VALUES ($valstr);";
			$query = $this->db->prepare($sql);
			if( $query->execute() )
			{
				$this->cant = $query->rowCount();
				return true;
			}
			return false;
		}

		function modificar($tabla,$datos,$condicion,$op='AND')
		{
			$this->cant=0;
			$sql="UPDATE $tabla SET ";
			$where = array();

			$query = $this->db->prepare("DESCRIBE $tabla;");
			if($query->execute())
			{
				$i=0;
				foreach($query->fetchAll() as $obj)
				{
					if(isset($datos[$obj->Field]))
					{
						$valstr[$obj->Field] = $obj->Field."='".$datos[$obj->Field]."'";
					}
					if(isset($condicion[$obj->Field]))
					{
						$where[$obj->Field] = $obj->Field."='".$condicion[$obj->Field]."'";
					}
				}
			}
			$sql = $sql.implode(",",$valstr)." WHERE ".implode(" ".$op." ", $where).";";
			$query = $this->db->prepare($sql);
			if($query->execute())
			{
				$this->cant = $query->rowCount();
				return true;
			}
			return false;
		}

		function eliminar( $tabla, $condicion, $op='OR' )
		{
			$this->cant=0;
			$sql="DELETE FROM $tabla WHERE ";
			$where = array();

			$query = $this->db->prepare("DESCRIBE $tabla;");
			if($query->execute())
			{
				foreach($query->fetchAll() as $obj)
				{
					if(isset($condicion[$obj->Field]))
					{
						$where[$obj->Field] = $obj->Field."='".$condicion[$obj->Field]."'";
					}
				}
			}
			$sql = $sql.implode(" ".$op." ", $where).";";
			$query = $this->db->prepare($sql);
			if($query->execute())
			{
				$this->cant = $query->rowCount();
				return true;
			}
			return false;
		}

		function consulta($sql)
		{
			$this->cant = 0;
			$query = $this->db->prepare($sql);
			if($query->execute())
			{
				$this->resultado = $query->fetchAll();
				$this->cant = $query->rowCount();
				return true;
			}
			return false;
		}

		function LAST_INSERT_ID()
		{
			return  $this->id;
		}
	}
