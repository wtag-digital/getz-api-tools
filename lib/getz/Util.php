<?php

	/**
	 * Utilities.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     http://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0, 26 Jul 2016
	 */	 	 
	 
	/**
	 * Enable CORS for external calls.
	 */
	function enableCORS() {
		/* 
		 * Allow from any origin.
		 */
		if (isset($_SERVER["HTTP_ORIGIN"])) {
			header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
			header("Access-Control-Allow-Credentials: true");
			header("Access-Control-Max-Age: 86400"); // cache for 1 day
			header("Content-type: application/json; charset=UTF-8");
		}

		/* 
		 * Access-Control headers are received during OPTIONS requests.
		 */
		if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
			if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"])) {
				header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
			}
			if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"])) {
				header("Access-Control-Allow-Headers: " . 
						$_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]);
			}
			exit(0);
		}

		return true;
	}	 

	/**
	 * @param {String} date
	 * @return {String}
	 */
	function getDay($date) {
		$arr = explode("-", $date);
		$day = explode(" ", $arr[2]);
		
		return $day[0];
	}

	/**
	 * @param {Integer} mounth
	 * @param {Integer} year
	 * @return {Date}
	 */
	function getLastDay($mounth, $year) {
		return date("d", mktime(0, 0, 0, $mounth + 1, 1, $year) - 1);
	}
	
	/**
	 * @param {String} ent
	 */
	function getMounth($ent) {
		$retArr = explode("-", $ent);
		
		$ret = $retArr[1];
		
		if ($ret == "01") $ret = "Jan";
		else if ($ret == "02") $ret = "Fev";
		else if ($ret == "03") $ret = "Mar";
		else if ($ret == "04") $ret = "Abr";
		else if ($ret == "05") $ret = "Mai";
		else if ($ret == "06") $ret = "Jun";
		else if ($ret == "07") $ret = "Jul";
		else if ($ret == "08") $ret = "Ago";
		else if ($ret == "09") $ret = "Set";
		else if ($ret == "10") $ret = "Out";
		else if ($ret == "11") $ret = "Nov";
		else if ($ret == "12") $ret = "Dez";
		
		return $ret;
	}
	
	/**
	 * @param {String} date
	 * @return {String}
	 */
	function getYear($date) {
		$arr = explode("-", $date);
		$year = explode(" ", $arr[0]);
		
		return $year[0];
	}
	
	/**
	 * @param {String} date
	 * @return {String}
	 */
	function getTime($date) {
		$time = explode(" ", $date);
		$timeArr = explode(":", $time[1]);
		
		return $timeArr[0] . ":" . $timeArr[1];
	}
	
	/**
	 * Convert price to double
	 *
	 * @param {String} ent
	 */
	function controllerDouble($ent) {
		$ret = str_replace(".", "", $ent); 
		$ret = str_replace(",", ".", $ret); 
		
		return $ret;
	}

	/**
	 * Convert dd/mm/YYYY to YYYY-mm-dd
	 *
	 * @param {String} ent
	 */
	function controllerDate($ent) {
		$retArr = explode("/", $ent);

		if (sizeof($retArr) > 1)
			$ret = $retArr[2] . "-" . $retArr[1] . "-" . $retArr[0];
		else
			$ret = "";
		
		return $ret;
	}
	
	/**
	 * Convert dd/mm/YYYY 00:00 to YYYY-mm-dd 00:00
	 *
	 * @param {String} ent
	 */
	function controllerDateTime($ent) {
		$dateTime = explode(" ", $ent);
		$date = explode("/", $dateTime[0]);

		if (sizeof($date) > 1)
			$ret = $date[2] . "-" . $date[1] . "-" . $date[0];
		else
			$ret = "";

		return $ret . " " . $dateTime[1];
	}
	
	/**
	 * Convert double to price
	 *
	 * @param {String} ent
	 */
	function modelDouble($ent) {
		$negative = stripos($ent, "-");
		
		if ($negative === 0)
			$ent = str_replace("-", "", $ent); 
		
		$retArr = explode(".", $ent);
		
		$ret = "";
		$number = "";
		$position = 0;
		
		for ($i = (strlen($retArr[0]) - 1); $i >= 0; $i--) {
			if ($position == 3) {
				$position = 0;
				
				$number = substr($retArr[0], $i, 1) . "." . $number;
			} else 
				$number = substr($retArr[0], $i, 1) . $number;

			$position++;
		}
		
		if (sizeof($retArr) > 1) {
			if (strlen($retArr[1]) > 1)
				$ret = $number . "," . substr($retArr[1], 0, 2);
			else
				$ret = $number . "," . substr($retArr[1], 0, 1) . "0";
		} else
			$ret = $number . ",00";
			
		if ($negative === 0)
			$ret = "-" . $ret; 

		return $ret;
	}

	/**
	 * Convert YYYY-mm-dd to dd/mm/YYYY
	 *
	 * @param {String} ent
	 */
	function modelDate($ent) {
		$retArr = explode("-", $ent);

		if (sizeof($retArr) > 1)
			$ret = $retArr[2] . "/" . $retArr[1] . "/" . $retArr[0];
		else
			$ret = "";
		
		return $ret;
	}
	
	/**
	 * Convert YYYY-mm-dd 00:00 to dd/mm/YYYY 00:00
	 *
	 * @param {String} ent
	 */
	function modelDateTime($ent) {
		$dateTime = explode(" ", $ent);
		$date = explode("-", $dateTime[0]);

		if (sizeof($date) > 1)
			$ret = $date[2] . "/" . $date[1] . "/" . $date[0];
		else
			$ret = "";
			
		$time = explode(":", $dateTime[1]);
		
		return $ret . " " . $time[0] . ":" . $time[1];
	}
	
	/**
	 * Convert YYYY-mm-dd 00:00:00 to 00:00
	 *
	 * @param {Date} ent
	 */
	function modelTime($ent) {
		$dateTime = explode(" ", $ent);
		$time = substr($dateTime[1], 0, 5);
		
		return $time;
	}
	
	/**
	 * Spaces to photo name
	 *
	 * @param {string} ent
	 */
	function modelPhoto($ent) {
		return str_replace(" ", "%20", $ent); 
	}
	
	/**
	 * Model Code
	 *
	 * @param {integer} ent
	 */
	function modelCode($ent) {
		$ret = $ent;
		$size = 6 - strlen($ent);
		
		for ($i = 0; $i < $size; $i++) {
			$ret = "0" . $ret;
		}

		return $ret;
	}

	/**
	 * Break line
	 *
	 * @param {string} ent
	 */
	function modelTextArea($ent) {
		return str_replace("<br />", "&#10;", $ent); 
	}
	
	/**
	 * Null
	 *
	 * @param {Object} ent
	 */
	function logicNull($ent) {
		return $ent == "null" ? "" : str_replace("\"", "'", $ent);
	}
	
	/**
	 * Zero
	 *
	 * @param {Object} ent
	 */
	function logicZero($ent) {
		return $ent == "null" ? 0 : $ent;
	}
	
	/**
	 * Add line when blank
	 */
	function addLine($ent) {
		$accent = array("à", "á", "ã", "é", "ê", "í", "ó", "ô", "õ", "ú", "ç");
		$normal = array("a", "a", "a", "e", "e", "i", "o", "o", "o", "u", "c");

		$ent = mb_strtolower($ent);
		$ent = str_replace($accent, $normal, $ent);

		return preg_replace("/ /", "-", $ent);
	}
	
	/**
	 * Remove line
	 *
	 * @param {string} ent
	 */
	function removeLine($ent){
		return preg_replace("/-/", " ", $ent);
	}
	
	/**
	 * Two houses
	 *
	 * @param {double} ent
	 */
	function twoHouses($ent){
		return sprintf("%.2f", $ent);
	}
	
	function save_base64_image($base64_image_string, $output_file_without_extension, 
			$path_with_end_slash = "") {
		$splited = explode(",", substr($base64_image_string, 5), 2);
		$mime = $splited[0];
		$data = $splited[1];
		$mime_split_without_base64 = explode(";", $mime, 2);
		$mime_split = explode("/", $mime_split_without_base64[0], 2);
		if (count($mime_split) == 2) {
			$extension = $mime_split[1];
			if ($extension == "jpeg") {
				$extension = "jpg";
			}
			$output_file_with_extension = $output_file_without_extension . "." . $extension;
		}
		file_put_contents($path_with_end_slash . $output_file_with_extension, 
				base64_decode(str_replace(" ", "+", $data)));
		return $output_file_with_extension;
	}	
	
?>