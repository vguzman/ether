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
			$url="https://etherscan.io/address/".$address."/";
			
			
			$handler = curl_init($url);  
			curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);
			$response = curl_exec ($handler);  
			curl_close($handler);  
			
			
			
			//SACANDO NOMNBRE
			$name='';
			$aux=explode("title='NameTag'>",$response);
			if (count($aux)>1)
			{
				$name=$aux[1];
				$aux=explode("<",$name);
				$name=$aux[0];
			}
			
			
			
			
			
			//SACANDO BALANCE
			$aux=explode("<td>ETH Balance:",$response);
			$aux=explode("<td>",$aux[1]);
			$aux=explode("Ether",$aux[1]);
			
			$balance=trim($aux[0]);
			$balance=str_replace("<b>.</b>",".",$balance);
			$balance=str_replace(",","",$balance);
			
			
			
			
			
			//SACANDO BLOQUES MINADOS
			$mined=0;
			if (substr_count($response,"ETH mined")>0)
			{
				$aux=explode("ETH mined",$response);
				$aux=explode(">",$aux[1]);
				$aux=explode(" blocks",$aux[1]);
				
				
				$mined=trim($aux[0]);
			}
			
			
			
			
			
			//SACANDO TXS
			$aux=explode("title='Normal Transactions'",$response);
			$aux=explode(">",$aux[1]);
			$aux=explode(" txn",$aux[1]);
				
				
			$txs=trim($aux[0]);
			
			$util=new Util;
			
			
			
			$query=$util->operacionSQL("SELECT address FROM Account WHERE address='".$address."'");
			
			
			if (mysql_num_rows($query)==0)
			{
				$util->operacionSQL("INSERT INTO Account VALUES ('".$address."', '".$name."' , ".$balance." , ".$mined.",".$mined.",'','NO', NOW() )");
			}
			else
			{
				
				$util->operacionSQL("UPDATE Account SET name='".$name."' , balance=".$balance." , mined_blocks=".$mined." , txs=".$txs." , last_update=NOW() WHERE address='".$address."'");
			}
			
			
		


			/*echo $name;
			echo "<br />";
			echo $balance;
			echo "<br />";
			echo $mined;
			echo "<br />";
			echo $txs;
			echo "<br />";*/			
			
			
			
			
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
				$this->txs=mysql_result($query,0,3);
				$this->mined_blocks=mysql_result($query,0,4);
				$this->type=mysql_result($query,0,5);
				$this->follow=mysql_result($query,0,6);
				$this->last_update=mysql_result($query,0,7);			
			}
			else
				return 0;		
		
		
		
		
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	







?>