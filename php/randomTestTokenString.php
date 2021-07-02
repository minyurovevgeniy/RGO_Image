<?php
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
	$length=20;

	$randomString = '';
	
	//for ($j=0;$j<100;$j++)
//	{
		
		for ($i = 0; $i < $length; $i++) 
		{
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		//echo $randomString.'<br>';
	//}
?>