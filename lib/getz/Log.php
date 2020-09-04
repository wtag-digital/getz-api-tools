<?php

	/**
	 * Log.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     http://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0, 26 Jul 2016
	 */	 
	 
	namespace lib\getz;

	class Log {
	
		public function __construct() { }
		
		public function write($name, $log, $debug) { 
			if ($debug) {
				$fo = fopen("_dev/log/log.txt", "a");
				$fw = fwrite($fo, "LOG [" . $name . "] at " . date("d/m/Y H:i:s") . "
" . $log . "

");
				fclose($fo);
			}
		}
		
	}

?>