<?php

	class Helper
	{
		public static function text($text, $color = false)
		{
			if (strlen($text))
			{
				if (!$color)
					$color = 29;
				echo ("\33[01;".$color."m".$text."\33[m");
			}
		}

		public static function echo_header()
		{
			system('clear');
			Helper::text("\nTelegram Soft v0.1\n", 42);
			Helper::text("\nAction: Parser\n", 44);
			Helper::text("\nNOWADAYS: ".date("Y-m-d H:i:s")."\n", 46);
			Helper::text("\n-------------------------\n\n", 30);
		}
	}

?>