<?
	include_once "clases.php";
	
	function operacionSQL($aux)
	{
		//echo $aux."<br>";
		if (substr_count($_SERVER['HTTP_HOST'],"testmercasist")>0)
			$link=mysql_connect ("localhost","mercasist","merc@s1st") or die ('I cannot connesfgfxxgfct to the database because: ' . mysql_error());
		else
			$link=mysql_connect ("localhost","mercasist","merc@s1st") or die ('I cannot connesfgfxxgfct to the database because: ' . mysql_error());
			//$link=mysql_connect ("vitoquen-cluster.cluster-cnbzlrnduns0.us-east-1.rds.amazonaws.com","root","ylacoloniatovar") or die ('I cannot connesfgfxxgfct to the database because: ' . mysql_error());
		
		
		mysql_select_db ("mercasist"); 
		
		mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");		
		$query=mysql_query($aux,$link);
		
		//mysql_close($link);
		if (!($query))
		{
			$hora="<p>Hora: ".now()."</p>";
			$url="<p>URL: ".$_SERVER['REQUEST_URI']."</p>";
			$ip="<p>IP: ".$_SERVER['REMOTE_ADDR']."</p>";
			$sql="<p>SQL: ".$aux."</p>";
			$error="<p>Error: ".mysql_error($link)."</p>";
			$banned="<p><a target='_blank' href='http://www.mercasist.com/procesos/bannedIp.php?code=80e75254c6bd96f10b7c15eb345f07&ip=".$_SERVER['REMOTE_ADDR']."'>Desterrar al condenado</a></p>";
			
			if (substr_count($_SERVER['HTTP_HOST'],"testmercasist")>0)
			{
				echo $sql.$error;
			}
			else
			{	
				
				Util::emailInformativo("info@mercasist.com","ERROR SQL",$hora.$url.$ip.$sql.$error.$banned);
			}
				
			return 0;
		
		}
		else
		{
			
			$url=$_SERVER['REQUEST_URI'];
			$cuenta=0;
			$cuenta=$cuenta+substr_count($url,"UNION");
			$cuenta=$cuenta+substr_count($url,"union");
			$cuenta=$cuenta+substr_count($url,"CONCAT");
			$cuenta=$cuenta+substr_count($url,"concat");
			
			
			
			if ($cuenta>0)
			{
				mysql_query("INSERT INTO ConfigActividadSospechosa VALUES (null,NOW(),'".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REQUEST_URI']."','".$aux."')",$link);				
				
				$query_aux=mysql_query("SELECT * FROM ConfigBannedIp WHERE ip='".$_SERVER['REMOTE_ADDR']."'",$link);
				if (mysql_num_rows($query_aux)==0)
				{
					$hora="<p>".now()."</p>";
					$url="<p>".$_SERVER['REQUEST_URI']."</p>";
					$ip="<p>".$_SERVER['REMOTE_ADDR']."</p>";					
					$banned="<p><a target='_blank' href='http://www.mercasist.com/procesos/bannedIp.php?code=80e75254c6bd96f10b7c15eb345f07&ip=".$_SERVER['REMOTE_ADDR']."'>Desterrar al condenado</a></p>";
					
					$util=new Util;
					$util->emailInformativo("vmgafrm@gmail.com","¡¡¡URGENTE!!! ACTIVIDAD SOSPECHOSA",$hora.$ip.$url.$banned);
					
					
					/*$url="http://www.mercasist.com/procesos/bannedIp.php?code=80e75254c6bd96f10b7c15eb345f07&ip=".$_SERVER['REMOTE_ADDR'];
					$c = curl_init($url);
					curl_setopt($c, CURLOPT_RETURNTRANSFER,true);
					$page = curl_exec($c);
					curl_close($c);*/
				}
				
			}
			
			return $query;
		}
	
	
	
	}
	
	
	
	function actividadSospechosa($id_usuario)
	{
		$hora="<p>".now()."</p>";
		$usuario="<p>".$id_usuario."</p>";
		$url="<p>".$_SERVER['REQUEST_URI']."</p>";
		$ip="<p>".$_SERVER['REMOTE_ADDR']."</p>";					
		$banned="<p><a target='_blank' href='http://www.mercasist.com/procesos/bannedIp.php?code=80e75254c6bd96f10b7c15eb345f07&ip=".$_SERVER['REMOTE_ADDR']."'>Desterrar al condenado</a></p>";
		
		
		$util=new Util;
		$util->emailInformativo("vmgafrm@gmail.com","¡¡¡URGENTE!!! ACTIVIDAD SOSPECHOSA",$hora.$ip.$usuario.$url.$banned);
	}
	
	
	
	
	function integridadEmail($email)
	{
		return $veri=ereg("^[^@ ]+@[^@ ]+\.[^@ .]+$",$email);
	}
	
	function now()
	{
		$hoy=getdate();
		$fecha=$hoy['year']."-".$hoy['mon']."-".$hoy['mday']." ".$hoy['hours'].":".$hoy['minutes'].":".$hoy['seconds'];	
		
		return $fecha;
	}	
	
	function nowSinFormato()
	{
		$hoy=getdate();
		$fecha=$hoy['year'].$hoy['mon'].$hoy['mday'].$hoy['hours'].$hoy['minutes'].$hoy['seconds'];	
		
		return $fecha;
	}	
	
	
	
	
	function ddmmaaaa_aaaammdd($fecha)
	{
		$ano=substr($fecha,6,4);
		$mes=substr($fecha,3,2);
		$dia=substr($fecha,0,2);
		
		return $ano."-".$mes."-".$dia;;

	}
	
	function aaaammdd_ddmmaaaa($fecha)
	{	
		$ano=substr($fecha,0,4);
		$mes=substr($fecha,5,2);
		$dia=substr($fecha,8,2);
		
		return $dia."-".$mes."-".$ano;
	}
	
	
	function nivelesPath()
	{
		$aux=str_replace($_SERVER['DOCUMENT_ROOT'],"",$_SERVER['SCRIPT_FILENAME']);
		$niveles=substr_count($aux,"/");
		$path="";
		for ($i=0;$i<$niveles;$i++)
			$path.="../";
			
		return $path;
	}
	
	
	function verificarSesion()
	{		
		$aux=str_replace($_SERVER['DOCUMENT_ROOT'],"",$_SERVER['SCRIPT_FILENAME']);
		$niveles=substr_count($aux,"/");
		$path="";
		for ($i=0;$i<$niveles;$i++)
			$path.="../";
		
		
		if (!isset($_SESSION['id_usuario']))
	   	{	
			echo "<SCRIPT LANGUAGE='JavaScript'> 
			
			window.alert('Debe iniciar sesion');
			location.href='".$path."index.php'; 
			
			</SCRIPT>";
			exit;
		}
		else
		{			
			$user=new Usuario($_SESSION['id_usuario']);
			$user->actualizarUltimoAcceso();
			
		}
		
	}
	
	
	
	
	function execURL($url)
	{
		$c = curl_init($url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER,true);
		$page = curl_exec($c);
		curl_close($c);
	}
	
	
	function eliminaSaltos($fuente)
	{
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array("", "", "", "");
		$cadena=str_ireplace($buscar,$reemplazar,$fuente);
		
		$cadena=str_replace('"',"'",$cadena);
		
		
		
		return trim($cadena);
	}
	
	
	
	function convierteSaltosHtml($fuente)
	{
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array("<br />", "<br / >", "<br />", "<br />");
		$cadena=str_ireplace($buscar,$reemplazar,$fuente);
		
		$cadena=str_replace('"',"'",$cadena);
		
		
		
		return trim($cadena);
	}
	
	
	function convierteSaltosEspacio($fuente)
	{
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array(" ", " ", " ", " ");
		$cadena=str_ireplace($buscar,$reemplazar,$fuente);	
		
		return trim($cadena);
	}
	
	
	function diferenciaFechasActualDiasHoras($fecha)
	{
		//DIFERENCIA DE HORAS
		$query_hora=operacionSQL("SELECT TIMEDIFF ( NOW() , ('".$fecha."') )");
		$horas=mysql_result($query_hora,0,0);
		$horas=explode(":",$horas);
		$minutos=$horas[1];
		$horas=$horas[0];
						
						
						
		$dias=floor($horas/24);
		$horas=$horas % 24;
						
		$resul['minutos']=$minutos;
		$resul['horas']=$horas;
		$resul['dias']=$dias;
		
		return $resul;
	}
	
	
	
	
	function tarifas()
	{
		$query=operacionSQL("SELECT * FROM ConfigTarifas");
		
		$resul['comision']=mysql_result($query,0,0);
		$resul['tope_bs']=mysql_result($query,0,1);
		
		return $resul;
		
	}
	
	
	function ivaVigente()
	{
		$query=operacionSQL("SELECT * FROM ConfigTarifas");
		
		return mysql_result($query,0,1);
	}
	
	
	function validarDecimal($valor)
	{
		$primero=preg_match('/^([0-9])*\.?[0-9]?[0-9]?$/',$valor);
		$segundo=preg_match('/^([0-9])*\,?[0-9]?[0-9]?$/',$valor);
		
		
		if (($primero==1)||($segundo==1))
			return true;
		else
			return false;
		
	}
	
	
	

	
?>
