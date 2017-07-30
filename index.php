<?
	include "lib/class.php";
	$util=new Util;


	if (isset($_GET['limit']))
		$limit=$_GET['limit'];
	else
		$limit=5;
		
		
		
	
	if (isset($_GET['from']))
		$from=$_GET['from'];
	else
		$from='from';
		



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>





<link href="lib/css/basicos.css" rel="stylesheet" type="text/css"></link>






<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>etherVitor</title>
</head>

<body>



<div style="margin-top:30px; margin-bottom:20px;" align="center" class="Aller15Negro">
<form id="form1" name="form1" method="get" action="">
        
    	  <label for="from"></label>
    	  From - To 
    	  <select name="from" id="from">
    	    <option value="from">From</option>
    	    <option value="to">To</option>
        </select>
/
<label for="limit">Limit</label>
        <input name="limit" type="text" id="limit" size="4" value="<? echo $limit ?>" />
          <input type="submit" name="button" id="button" value="Submit" />
    
    
    
  </form>
</div>    
 
 
 
    <table width="1000" border="1" cellspacing="0" cellpadding="10" align="center">
      <tr class="Aller15Negro" align="center" bgcolor="#D8D8D8">
        <td width="300"><strong>Account</strong></td>
        <td width="200"><strong>Name</strong></td>
        <td width="100"><strong>Balance</strong></td>
        <td width="100"><strong>Mined</strong></td>
        <td width="100"><strong>Txs Total</strong></td>
        <td width="100"><strong>Txs sent</strong></td>
        <td width="100"><strong>Txs recv</strong></td>
      </tr>
      <?
	  
	  
	  $api=new Api;
	  
	  
	  
	$query=$util->operacionSQL("SELECT COUNT(*) as c , A.`".$from."` as fr FROM Transaction A GROUP BY fr ORDER BY c DESC LIMIT ".$limit);


	for ($i=0;$i<mysql_num_rows($query);$i++)
	{
		$address=mysql_result($query,$i,1);
		$cuenta_from=mysql_result($query,$i,0);
		
		
		
		
		$query3=$util->operacionSQL("SELECT address FROM Account WHERE address='".$address."'");
		if (mysql_num_rows($query3)==0)
		{
			$api->loadAccount($address);
		}
		else
		{
			/*$cuenta=new Account($address);
		
			$query_dif=$util->operacionSQL("SELECT TIMESTAMPDIFF(HOUR, '".$cuenta->last_update."' , NOW() ) ");
			$horas=mysql_result($query_dif,0,0);
			
			
			if ($horas>=6)*/
				$api->loadAccount($address);
		}
		
		
		
		
		$account=new Account($address);	
		
		
		
		
		
		$bgcolor='';
		if ($i%2>0)
			$bgcolor='bgcolor="#D8D8D8"';
			
			
		$query2=$util->operacionSQL("SELECT COUNT(*) FROM Transaction A WHERE A.`to`='".$address."'");
		$cuenta_to=mysql_result($query2,0,0);
		
		
		$query2=$util->operacionSQL("SELECT COUNT(*) FROM Transaction A WHERE A.`from`='".$address."'");
		$cuenta_from=mysql_result($query2,0,0);
			
			
			
			
			
			
			
		
			
		echo '<tr class="Aller15Negro" '.$bgcolor.'>
				
				<td>
					<a href="https://etherscan.io/address/'.$address.'" target="_blank" class="LinkFuncionalidad15">'.$address.'</a>
				</td>
				<td>'.$account->name.'</td>
				
				<td>'.number_format($account->balance,2,',','.').'</td>
				<td>'.$account->mined_blocks.'</td>
				
				<td>'.number_format($account->txs,0,',','.').'</td>
				
				
				
				<td>'.number_format($cuenta_from,0,',','.').'</td>
				<td>'.number_format($cuenta_to,0,',','.').'</td>
				
				
			  </tr>';
			
		
		
		
		
		
	}
	
	
	
	




?>
    </table>
  </form>
</div>
</body>
</html>
