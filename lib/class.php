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
				
				$sql="<p>SQL: ".$aux."</p>";
				$error="<p>Error: ".mysql_error($link)."</p>";
				
				
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
							
							$content=array();
							
							$monto= (string) $data[$i]->amount;			
							
							
							$content['time']=$data[$i]->time;
							$content['from']=$data[$i]->sender;
							$content['to']=$data[$i]->recipient;
							$content['amount']=$monto;
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
				
				
				$util->operacionSQL("INSERT INTO Transaction VALUES ('".$clave."', '".$valor['from']."', '".$valor['to']."', '".$fecha."' , '".$valor['block_hash']."' , '".$valor['parent_hash']."' , ".number_format( ($valor['amount']/1000000000000000000)  ,18)."  )");
				
				
				
				
			}
		
					
					
					
			
			
			
			
			
			
		}
		
		
		
		
		
		
		function loadAccount($address)
		{
			$url="https://etherchain.org/api/account/".$address;
			
			
			$handler = curl_init($url);  
			curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);
			$response = curl_exec ($handler);  
			curl_close($handler);  
			$resp=json_decode($response);
			
			
			$data=$resp->data;
			$data=$data[0];
			
			
			
			if ( isset($data->address) )
			{
				$util=new Util;
				
				
				
				$fecha=str_replace("T"," ",$data->firstSeen);
				$fecha=substr($fecha,0,20);	
					
					
				$balance=number_format( ($data->balance/1000000000000000000) , 18 , '.' , '');
				
					
					
			
				$query=$util->operacionSQL("SELECT address FROM Account WHERE address='".$address."'");
				
				
				
				
				//SACANDO MINED
				$handler = curl_init("https://etherchain.org/api/account/".$address."/mined");  
				curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);
				$response = curl_exec ($handler);  
				curl_close($handler);  
				$resp_mined=json_decode($response);				
				
				$data_mined=$resp_mined->data;
				$mined=count($data_mined);
				
				
				
				
				
				//SACANDO NRO DE TRANSACCIONES
				$handler = curl_init("https://etherchain.org/api/account/".$address."/nonce");  
				curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);
				$response = curl_exec ($handler);  
				curl_close($handler);  
				$resp_mined=json_decode($response);				
				
				$data_mined=$resp_mined->data;
				$txs=$data_mined[0]->accountNonce;
					
				
				
				
				
				
				
				if (mysql_num_rows($query)==0)
				{
					$util->operacionSQL("INSERT INTO Account VALUES ('".$address."', '".$data->name."' , ".$balance." , '".$fecha."' ,null,".$mined.",'','NO', NOW() )");
				}
				
				else
				{
					$util->operacionSQL("UPDATE Account SET name='".$data->name."' , balance=".$balance." , mined_blocks=".$mined." , txs=".$txs." , last_update=NOW()");
				}
				
				
				
				
				
				
			}
			
			
			
		}
		
		
		
		
		
		
		
	}
	
	
	
	
	
	class Transaction
	{
		var $hash;
		var $from;
		var $to;
		var $date_time;
		var $block_hash;
		var $parent_hash;	
		var $amount;
		
		
		function Transaction($hash)
		{
			$this->hash=$hash;
			
			$util=new Util;
			
			$query=$util->operacionSQL("SELECT * FROM Transaction WHERE hash='".$hash."'");
			
			if (mysql_num_rows($query)>0)
			{
				$this->from=mysql_result($query,0,1);
				$this->to=mysql_result($query,0,2);
				$this->date_time=mysql_result($query,0,3);
				$this->block_hash=mysql_result($query,0,4);
				$this->parent_hash=mysql_result($query,0,5);
				$this->amount=mysql_result($query,0,6);			
				
				
			}
			else
				return 0;
			
			
			
			
		}
		
		
		

		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	class Account
	{
		var $address;
		var $name;
		var $balance;
		var $firstSeen;
		var $txs;
		var $mined_blocks;	
		var $type;
		var $follow;
		var $last_update;
		
		
		function Account($address)
		{
			$this->address=$address;
			
			$util=new Util;
			
			$query=$util->operacionSQL("SELECT * FROM Account WHERE address='".$address."'");			
			
			
			
			if (mysql_num_rows($query)>0)
			{
				$this->name=mysql_result($query,0,1);
				$this->balance=mysql_result($query,0,2);
				$this->firstSeen=mysql_result($query,0,3);
				$this->txs=mysql_result($query,0,4);
				$this->mined_blocks=mysql_result($query,0,5);
				$this->type=mysql_result($query,0,6);
				$this->follow=mysql_result($query,0,7);
				$this->last_update=mysql_result($query,0,8);			
			}
			else
				return 0;		
		
		
		
		
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	







?>