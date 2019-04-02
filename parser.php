<?php

	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	define('/', DIRECTORY_SEPARATOR);
	define('DIR', dirname(__FILE__));
	define('LIB', DIR.'/library');
	define('FUNC', DIR.'/function');

	require_once (FUNC.'/settings.php');
	require_once (FUNC.'/Helper.php');

	system('clear');

	Helper::text("\n\nPLEASE WAIT ...\n\n", 42);
	if (!file_exists(LIB.'/MadelineProto/madeline.php'))
		copy('https://phar.madelineproto.xyz/madeline.php', LIB.'/MadelineProto/madeline.php');
	require_once (LIB.'/MadelineProto/madeline.php');

	system('clear');

	// <header
	Helper::echo_header();
	// header/>

	Helper::text("Session check, please wait ...\n");

	$MP = new \danog\MadelineProto\API(DIR.'/sessions/account.madeline', $settings);
	$get_self = $MP->get_self();

	if (!($get_self))
	{
		Helper::text("Session is empty\n", 31);
		Helper::text("\n-------------------------\n\n", 30);
		$MP->phone_login(readline('Enter your phone number: '));
		$authorization = $MP->complete_phone_login(readline('Enter the phone code: '));
		if ($authorization['_'] === 'account.password') {
		    $authorization = $MP->complete_2fa_login(readline('Please enter your password (hint '.$authorization['hint'].'): '));
		}
		if ($authorization['_'] === 'account.needSignup') {
		    $authorization = $MP->complete_signup(readline('Please enter your first name: '), readline('Please enter your last name (can be empty): '));
		}
		$get_self = $MP->get_self();
	}

	// <header
	Helper::echo_header();
	// header/>

	Helper::text("Session: ".$get_self['phone']."\n", 42);
	Helper::text("\n-------------------------\n\n", 30);

	$chat = readline("Enter chatname (ex: yarik): ");
	if (!(strlen($chat)))
		exit("\nError: (chat can't be empty)\n");

	$check_admin = readline("Parsing admin (Y/N): ");
	if (strlen($check_admin) != 1)
		exit("\nError: (check_admin != 1)\n");
	if (mb_strtolower($check_admin) != 'y' && mb_strtolower($check_admin) != 'n')
		exit("\nError: (check_admin != (Y/N))\n");

	$check_hidden = readline("Parsing hidden status (Y/N):");
	if (strlen($check_hidden) != 1)
		exit("\nError: (check_hidden != 1)\n");
	if (mb_strtolower($check_hidden) != 'y' && mb_strtolower($check_hidden) != 'n')
		exit("\nError: (check_hidden != (Y/N))\n");

	$end_time = readline("Date of last activity (ex: ".date("Y-m-d H:i:s")."): ");

	if (!(strlen($end_time)))
		exit("\nError: (end_time can't be empty)\n");

	Helper::text("\n-------------------------\n", 30);
	Helper::text("\n\nPLEASE WAIT ...\n\n", 42);
	$result = $MP->get_pwr_chat($chat);
	Helper::text("\nParse chat: ".$result['title']."\n", 45);

	$array_username = [];
	foreach ($result['participants'] as $key => $value)
	{
		if (isset($value['user']))
		{
			if (isset($value['user']['username']))
			{
				if ($value['user']['type'] == 'bot')
					continue;
				if (mb_strtolower($check_admin) == 'n')
				{
					if ($value['role'] != 'user')
						continue;
				}
				if (mb_strtolower($check_hidden) == 'n')
				{
					if (isset($value['user']['status']))
						if (isset($value['user']['status']['_']))
							if ($value['user']['status']['_'] == 'userStatusRecently')
								continue;
				}

				if (isset($value['user']['status']))
					if (isset($value['user']['status']['_']))
						if ($value['user']['status']['_'] == 'userStatusOffline')
							if (isset($value['user']['status']['was_online']))
								if (strtotime($end_time) > $value['user']['status']['was_online'])
									continue;
				array_push($array_username, $value['user']['username']);
			}
		}
	}
	if (!file_exists(DIR.'/username.txt'))
		exit("\nError: (username.txt not found)\n");
	file_put_contents(DIR.'/username.txt', '');
	file_put_contents(DIR.'/username.txt', implode("\n", $array_username));
	Helper::text("\nIn username.txt saved ".count($array_username)." user\n", 42);
	Helper::text("\n-------------------------\n\n", 30);
?>