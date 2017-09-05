<?php
	class extra
	{
		var $parents = null;
		function __construct( &$parents )
		{
			$this->parents = $parents;
		}
		function SelectOptions($table,$index,$description,$condition=array(),$default=false)
		{
			$where = "";
			if( count($condition)>0 )
			{
				foreach($condition as $i=>$v)
				{
					$cnd[] = $i."='".$v."'";
				}
				if( isset($cnd) && count($cnd) >0 )
					$where = " WHERE ".implode(" AND ",$cnd);
			}
			$opt="";
			$query="SELECT ".$index." id, ".$description." val FROM ".$table.$where.";";
			if( $this->parents->sql->consulta($query) )
			{
				$resultado = $this->parents->sql->resultado;
				foreach( $resultado as $obj )
				{
					$selected = ( $obj->id == $default ) ? " selected" : "" ;
					$opt .= '<option data-change="'.$obj->id.'" value="'.$obj->id.'"'.$selected.'>'.$obj->val.'</option>';
				}
			}
			return $opt;
		}
		function evaluate($condition,$true,$false)
		{
			if($condition)
			{
				return $true;
			}
			return $false;
		}
		function get_transcurrido($time) // int format
		{
			$periodos = array("seg", "min", "hora", "día", "sem", "mes", "año", "década");
			$duraciones = array("60","60","24","7","4.35","12","10");
			$now = time();
			$diferencia = $now - $time;

			for($j = 0; $diferencia >= $duraciones[$j] && $j < count($duraciones)-1; $j++) {
				$diferencia /= $duraciones[$j];
			}
			$diferencia = round($diferencia);

			if($diferencia != 1) {
				if($j != 5){
					$periodos[$j].= "s";
				}else{
					$periodos[$j].= "es";
				}
			}

			return $diferencia." ".$periodos[$j];
		}
		public function redirection( $url, $no = false )
		{
			if( !$no )
			{
				header( "Location: ".$url );
				exit();
			}
		}
		function attribute( $attr = array() )
		{
			$rtn="";
			if( is_array($attr) )
			{
				foreach($attr as $i=>$v)
				{
					$rtn.=$i.'="'.htmlspecialchars($v).'" ';
				}
			}
			return $rtn;
		}
		function validate( $data, $options=null )
		{
			if( is_array($data) && is_array($options) )
			{
				foreach( $data as $i=>$v )
				{
					if( isset( $options[$i] ) && is_string($options[$i]) )
					{
						$rtn[$i] = validation_case( $v, $options[$i] );
					}
					else
					{
						$rtn[$i] = validation_case( $v, "requiere" );
					}
				}
			}
		}
		function validation_case( $valor, $filter )
		{
			switch( $filter )
			{
				case "requiere":
					if(trim($valor) != '')
					{
						return true;
					}
				break;
				case "boolean":
					if( filter_var($valor, FILTER_VALIDATE_BOOLEAN) === FALSE )
					{
						return true;
					}
				break;
				case "email":
					if( filter_var($valor, FILTER_VALIDATE_EMAIL) === FALSE )
					{
						return true;
					}
				break;
				case "int":
					if( filter_var($valor, FILTER_VALIDATE_INT) === FALSE )
					{
						return true;
					}
				break;
				case "float":
					if( filter_var($valor, FILTER_VALIDATE_FLOAT) === FALSE )
					{
						return true;
					}
				break;
				case "number":
					if( filter_var($valor, FILTER_VALIDATE_INT) === FALSE OR filter_var($valor, FILTER_VALIDATE_FLOAT) === FALSE )
					{
						return true;
					}
				break;
				case "ip":
					if( filter_var($valor, FILTER_VALIDATE_IP) === FALSE )
					{
						return true;
					}
				break;
				case "mac":
					if( filter_var($valor, FILTER_VALIDATE_MAC) === FALSE )
					{
						return true;
					}
				break;
				case "url":
					if( filter_var($valor, FILTER_VALIDATE_URL) === FALSE )
					{
						return true;
					}
				break;
			}
			return false;
		}
		function num2letras($num,$fem=false,$dec=false)
		{
			$matuni[2]  = "dos";
			$matuni[3]  = "tres";
			$matuni[4]  = "cuatro";
			$matuni[5]  = "cinco";
			$matuni[6]  = "seis";
			$matuni[7]  = "siete";
			$matuni[8]  = "ocho";
			$matuni[9]  = "nueve";
			$matuni[10] = "diez";
			$matuni[11] = "once";
			$matuni[12] = "doce";
			$matuni[13] = "trece";
			$matuni[14] = "catorce";
			$matuni[15] = "quince";
			$matuni[16] = "dieciseis";
			$matuni[17] = "diecisiete";
			$matuni[18] = "dieciocho";
			$matuni[19] = "diecinueve";
			$matuni[20] = "veinte";
			$matunisub[2] = "dos";
			$matunisub[3] = "tres";
			$matunisub[4] = "cuatro";
			$matunisub[5] = "quin";
			$matunisub[6] = "seis";
			$matunisub[7] = "sete";
			$matunisub[8] = "ocho";
			$matunisub[9] = "nove";
			$matdec[2] = "veint";
			$matdec[3] = "treinta";
			$matdec[4] = "cuarenta";
			$matdec[5] = "cincuenta";
			$matdec[6] = "sesenta";
			$matdec[7] = "setenta";
			$matdec[8] = "ochenta";
			$matdec[9] = "noventa";
			$matsub[3]  = 'mill';
			$matsub[5]  = 'bill';
			$matsub[7]  = 'mill';
			$matsub[9]  = 'trill';
			$matsub[11] = 'mill';
			$matsub[13] = 'bill';
			$matsub[15] = 'mill';
			$matmil[4]  = 'millones';
			$matmil[6]  = 'billones';
			$matmil[7]  = 'de billones';
			$matmil[8]  = 'millones de billones';
			$matmil[10] = 'trillones';
			$matmil[11] = 'de trillones';
			$matmil[12] = 'millones de trillones';
			$matmil[13] = 'de trillones';
			$matmil[14] = 'billones de trillones';
			$matmil[15] = 'de billones de trillones';
			$matmil[16] = 'millones de billones de trillones';

			$num = trim((string)@$num);

			if ($num[0] == '-')
			{
				$neg = 'menos ';
				$num = substr($num, 1);
			}
			else
			{
				$neg = '';
			}
			while ($num[0] == '0') $num = substr($num, 1);
			if ($num[0] < '1' or $num[0] > 9)
			{
				$num = '0' . $num;
			}
			$zeros = true;
			$punt = false;
			$ent = '';
			$fra = '';
			for ($c = 0; $c < strlen($num); $c++)
			{
				$n = $num[$c];
				if (! (strpos(".,'''", $n) === false))
				{
				   if ($punt) break;
				   else{
					  $punt = true;
					  continue;
				   }
				}
				elseif (! (strpos('0123456789', $n) === false))
				{
				   if ($punt)
				   {
					  if ($n != '0') $zeros = false;
					  $fra .= $n;
				   }
				   else
				   {
					  $ent .= $n;
				   }
				}
				else
				{
				   break;
				}
			}

			$ent = '     ' . $ent;
			/*
			*/
			if ($dec and $fra and ! $zeros)
			{
				$fin = ' Coma';
				for ($n = 0; $n < strlen($fra); $n++)
				{
				   if (($s = $fra[$n]) == '0')
				   {
					  $fin .= ' cero';
				   }
				   elseif ($s == '1')
				   {
					  $fin .= $fem ? ' una' : ' un';
				   }
				   else
				   {
					  $fin .= ' ' . $matuni[$s];
				   }
				}
			}
			else
			{
				$fin = '';
			}
			if ((int)$ent === 0) return 'Cero ' . $fin;
			$tex = '';
			$sub = 0;
			$mils = 0;
			$neutro = false;

			while ( ($num = substr($ent, -3)) != '   ') {

				$ent = substr($ent, 0, -3);
				if (++$sub < 3 and $fem) {
				   $matuni[1] = 'una';
				   $subcent = 'as';
				}else{
				   $matuni[1] = $neutro ? 'un' : 'uno';
				   $subcent = 'os';
				}
				$t = '';
				$n2 = substr($num, 1);
				if ($n2 == '00') {
				}elseif ($n2 < 21)
				   $t = ' ' . $matuni[(int)$n2];
				elseif ($n2 < 30) {
				   $n3 = $num[2];
				   if ($n3 != 0) $t = 'i' . $matuni[$n3];
				   $n2 = $num[1];
				   $t = ' ' . $matdec[$n2] . $t;
				}else{
				   $n3 = $num[2];
				   if ($n3 != 0) $t = ' y ' . $matuni[$n3];
				   $n2 = $num[1];
				   $t = ' ' . $matdec[$n2] . $t;
				}

				$n = $num[0];
				if ($n == 1) {
				   $t = ' ciento' . $t;
				}elseif ($n == 5){
				   $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
				}elseif ($n != 0){
				   $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
				}

				if ($sub == 1) {
				}elseif (! isset($matsub[$sub])) {
				   if ($num == 1) {
					  $t = ' mil';
				   }elseif ($num > 1){
					  $t .= ' mil';
				   }
				}elseif ($num == 1) {
				   $t .= ' ' . $matsub[$sub] . 'ón';
				}elseif ($num > 1){
				   $t .= ' ' . $matsub[$sub] . 'ones';
				}
				if ($num == '000') $mils ++;
				elseif ($mils != 0) {
				   if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
				   $mils = 0;
				}
				$neutro = true;
				$tex = $t . $tex;
			}
			$tex = $neg . substr($tex, 1) . $fin;
			//return ucwords($tex);
			return ucfirst($tex);
		}
		function getDecimal($num,$digit=2)
		{
			$pow = pow(10,$digit);
			return intval($num*$pow - intval($num)*$pow);
		}
	}
?>
