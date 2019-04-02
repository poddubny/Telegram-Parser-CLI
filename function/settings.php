<?php
	
	// api_id and api_hash (https://my.telegram.org/auth)
	$API_TELEGRAM['api_id']		= "-api_id-";
	$API_TELEGRAM['api_hash'] 	= "-api_hash-";

	// timezona (https://www.php.net/manual/ru/timezones.php)
	date_default_timezone_set('Europe/Kiev');

	// settings madelineproto
	$settings = [
		'app_info'	=> [
			'api_id'	=> $API_TELEGRAM['api_id'],
			'api_hash'	=> $API_TELEGRAM['api_hash']
		],
		'logger'	=> [
			'logger'	=> 0
		]
	];


?>