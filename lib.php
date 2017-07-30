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




	class Api
	{
		
		
		function loadTx()
		{
			
			$util=new Util;
	
	
	
	
			$e=1;$flag_out=false;$resul=array();
			while (true)
			{
				if (($flag_out==true)||($e>2100))
					break;
				
				
				
				
			
				echo "<strong>*******************************".$e."**************************************</strong>";
				echo "<br>";
				echo $url="https://etherchain.org/api/txs/".$e."/100";
				echo "<br>";echo "<br>";echo "<br>";
				
				
				
				
				$handler = curl_init($url);  
				curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);
				$response = curl_exec ($handler);  
				curl_close($handler);  
				$resp=json_decode($response);
				
				
				
				if (isset($resp->status))
					if ($resp->status=="1")
					{
			
						$data=$resp->data;									
									
						
						for ($i=0;$i<count($data);$i++)
						{
							
							
							$hash=$data[$i]->hash;						
							
							
							$content['time']=$data[$i]->time;
							$content['from']=$data[$i]->sender;
							$content['to']=$data[$i]->recipient;
							$content['amount']=$data[$i]->amount;
							$content['block_hash']=$data[$i]->blockHash;
							$content['parent_hash']=$data[$i]->parentHash;
							
							
							if (($data[$i]->type=="tx")&&($data[$i]->amount>0))
								if (isset($resul[$hash])==false)
								{
									
									echo $hash;
									echo "<br>";
										
									echo $data[$i]->time;
									echo "<br>";
									
									
									$query=$util->operacionSQL("SELECT hash FROM Transaction WHERE hash='".$hash."'");
									if (mysql_num_rows($query)==0)	
									{						
										$resul[$hash]=$content;	
									}
									else
									{
										echo "<strong>############ Registrado en BD</strong><br />";							
										
										
										$flag_out=true;						
										break;
									}
								
								
								}
								else
								{
									echo "<strong>############hash repetido</strong><br />";
									
									
									
									$flag_out=true;						
									break;
									
								}
							
							
							
							echo "<br>";
							echo "<br>";
							
							
						}
					}
					else
						"ERRORRRRRRRRRRRRRRRRRR<br />";
						
						
						
					$e=$e+100;
		
			}
		
		
			
			
			
			foreach ($resul as $clave => $valor)
			{
				
				
				$fecha=str_replace("T"," ",$valor['time']);
				$fecha=substr($fecha,0,20);
				
				
				$util->operacionSQL("INSERT INTO Transaction VALUES ('".$clave."', '".$valor['from']."', '".$valor['to']."', ".$valor['amount']." ,  '".$fecha."' , '".$valor['block_hash']."' , '".$valor['parent_hash']."' , ".number_format( ($valor['amount']/1000000000000000000) , 18 )."  )");
				
				
				
				
			}
		
					
					
					
			
			
			
			
			
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}







?>