<?php

	/**
	 * Autoload.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     http://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0, 26 Jul 2016
	 */
	 
	use lib\getz;
	use src\logic;
	
	header("Content-Type: application/json; charset=utf-8");		
	
	/*
	 * SPL autoload register.
	 */
	spl_autoload_register(function ($autoload) {
		if (!@include(__DIR__ . BAR .  str_replace(BACKSLASH, BAR, $autoload) . DOT . PHP)) { 
			$applicationSettings = simplexml_load_file(APPLICATION_SETTINGS);
			$root = STRING_EMPTY;
			if ($applicationSettings->package != STRING_EMPTY) {
				$root = BAR . $applicationSettings->package;		
			}		
			header(LOCATION . $root . BAR . NOT_FOUND);
		}
	});
	
	define("CONSTANTS", "Constants.php");
	define("UTIL_PACKAGE", "lib/getz/Util.php");	
	
	require_once(CONSTANTS);
	require_once(UTIL_PACKAGE);	

	/*
	 * Application settings.
     */	 
	$applicationSettings = simplexml_load_file(APPLICATION_SETTINGS);
	$root = STRING_EMPTY;
	if ($applicationSettings->package != STRING_EMPTY) {
		$root = BAR . $applicationSettings->package;		
	}
	$debug = false;	
	if ($applicationSettings->debug != STRING_EMPTY && $applicationSettings->debug == STRING_TRUE) {
		$debug = true;
	}
	$documentRoot = $_SERVER[DOCUMENT_ROOT] . $root;
	$daoFactoryIsOfficial = $applicationSettings->connection->is_official;	
	$class = $_GET[STRING_CLASS];	 

	$daoFactory = new logic\DaoFactory($daoFactoryIsOfficial);
	$log = new getz\Log();
	$request = array();

	/*
	 * PUT rules.
	 */
	if ($_SERVER[REQUEST_METHOD] == strtoupper(PUT)) {
		$putFo = fopen(PHP_INPUT, R);
		$putData = STRING_EMPTY;
		while ($data = fread($putFo, NUMBER_ONE_THOUSAND_TWENTY_FOUR * NUMBER_ONE_THOUSAND_TWENTY_FOUR)) {
			$putData .= $data;
		}
		fclose($putFo);
		$request = json_decode(str_replace(REQUEST . EQUALS, STRING_EMPTY, $putData), true);
	} else if (isset($_POST[REQUEST])) {
		$request = json_decode($_POST[REQUEST], true);
	}
	
	$response = array();
	$resource = $daoFactory->prepare($_GET[RESOURCE]);
	
	/*
	 * Where.
	 */
	$filterColumn = $daoFactory->prepare($_GET[FILTER_COLUMN]);
	$filterCondition = $daoFactory->prepare($_GET[FILTER_CONDITION]);
	$filterValue = $daoFactory->prepare($_GET[FILTER_VALUE]);
	$where = STRING_EMPTY;
	if ($filterColumn != STRING_EMPTY && $filterCondition != STRING_EMPTY && $filterValue != STRING_EMPTY) {
		if (strtoupper($filterCondition) == strtoupper(STRING_EQUALS)) {
			$where = $filterColumn . WHITE_SPACE . EQUALS . WHITE_SPACE . SINGLE_QUOTATION_MARKS . $filterValue . 
					SINGLE_QUOTATION_MARKS;		
		} else if (strtoupper($filterCondition) == strtoupper(STRING_NOT_EQUALS)) {
			$where = $filterColumn . WHITE_SPACE . NOT_EQUALS . WHITE_SPACE . SINGLE_QUOTATION_MARKS . $filterValue . 
					SINGLE_QUOTATION_MARKS;					
		} else if (strtoupper($filterCondition) == strtoupper(LIKE)) {
			$where = $filterColumn . WHITE_SPACE . LIKE . WHITE_SPACE . SINGLE_QUOTATION_MARKS . PERCENTAGE . 
					$filterValue . PERCENTAGE . SINGLE_QUOTATION_MARKS;				
		} else if (strtoupper($filterCondition) == strtoupper(STRING_BETWEEN)) {
			$where = $filterColumn . WHITE_SPACE . BETWEEN . WHITE_SPACE . $filterValue;	
		} else if (strtoupper($filterCondition) == strtoupper(STRING_LESS_THAN)) {
			$where = $filterColumn . WHITE_SPACE . LESS_THAN . WHITE_SPACE . $filterValue;		
		} else if (strtoupper($filterCondition) == strtoupper(STRING_LESS_EQUALS)) {
			$where = $filterColumn . WHITE_SPACE . LESS_EQUALS . WHITE_SPACE . $filterValue;		
		} else if (strtoupper($filterCondition) == strtoupper(STRING_MORE_THAN)) {
			$where = $filterColumn . WHITE_SPACE . MORE_THAN . WHITE_SPACE . $filterValue;		
		} else if (strtoupper($filterCondition) == strtoupper(STRING_MORE_EQUALS)) {
			$where = $filterColumn . WHITE_SPACE . MORE_EQUALS . WHITE_SPACE . $filterValue;
		}
	}

	/*
	 * Order.
	 */
	$orderColumn = $daoFactory->prepare($_GET[ORDER_COLUMN]);
	$orderValue = $daoFactory->prepare($_GET[ORDER_VALUE]);	 
	$order = STRING_EMPTY;
	if ($orderColumn != STRING_EMPTY) {
		if ($orderValue == STRING_EMPTY) {
			$orderValue = ASC;
		}
		$order = $orderColumn . WHITE_SPACE . $orderValue;
	}	
	
	/*
	 * Limits.
	 */
	$page = $daoFactory->prepare($_GET[PAGE]);
	$pageSize = $daoFactory->prepare($_GET[PAGE_SIZE]); 
	$hasPagination = false;
	if ($pageSize == STRING_EMPTY) {
		$pageSize = NUMBER_TEN;
	}
	if ($page > NUMBER_ZERO) {
		$hasPagination = true;
		if ($order != STRING_EMPTY) {
			$order .= WHITE_SPACE . LIMIT . WHITE_SPACE . (($page * $pageSize) - $pageSize) . COMMA . 
					$pageSize;
		} else {
			$order = LIMIT . WHITE_SPACE . (($page * $pageSize) - $pageSize) . COMMA . $pageSize;
		}
	}

	/*
	 * The controller.
	 */
	$controller = SRC_CONTROLLER . ucfirst($class);
	$instance = new $controller;
	$instance->setClass($class);		
	$instance->setDaoFactory($daoFactory);		
	$instance->setDebug($debug);
	$instance->setDocumentRoot($documentRoot);
	$instance->setHasPagination($hasPagination);	
	$instance->setLog($log);		
	$instance->setRequest($request);
	$instance->setResponse($response);
	$instance->setWhere($where);
	$instance->setOrder($order);
	$instance->setResource($resource);
	$instance->init();

?>