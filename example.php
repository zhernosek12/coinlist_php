<?php 
	
	include "coinlist.class.php";

	# https://pro.coinlist.co/settings/api -> create api key
	
	$access_key = "0000000-0000-0000-0000-00000000";
	$access_secret = "<access-secret-key>";

	$coinlist = new CoinList($access_key, $access_secret);
	$r = $coinlist->request('GET', '/v1/symbols/summary');
	
	echo "<pre>";
	print_r($r);
	echo "</pre>";

?>