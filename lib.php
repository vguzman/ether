<?


	class Util
	{
		
		function operacionSQL($aux)
		{
			$link=mysql_connect ("localhost","ether","123456") or die ('I cannot connesfgfxxgfct to the database because: ' . mysql_error());		
			mysql_select_db ("ether");			
			
			
			mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");		
			$query=mysql_query($aux,$link);
			
			
			if (!($query))
			{
				echo $sql.$error;
				return false;
			}
			else
				return $query;	
		
		
		}
	
	
	
	
	
	
	
	
	
	
	}












?>