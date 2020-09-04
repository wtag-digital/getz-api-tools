<?php

	/**
	 * Activator.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     http://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0, 26 Jul 2016
	 */
	 
	namespace lib\getz;
	
	class Activator {
		
		public $class;
		public $daoFactory;
		public $debug;
		public $documentRoot;
		public $hasPagination;
		public $log;
		public $request;
		public $response;
		public $where;
		public $order;
		public $resource;
		
		public function __construct() { }
		
		public function setClass($class) {
			$this->class = $class;
		}			
		
		public function setDaoFactory($daoFactory) {
			$this->daoFactory = $daoFactory;
		}	

		public function setDebug($debug) {
			$this->debug = $debug;
		}	
		
		public function setDocumentRoot($documentRoot) {
			$this->documentRoot = $documentRoot;
		}	
		
		public function setHasPagination($hasPagination) {
			$this->hasPagination = $hasPagination;
		}
		
		public function setLog($log) {
			$this->log = $log;
		}	

		public function setRequest($request) {
			$this->request = $request;
		}
		
		public function setResponse($response) {
			$this->response = $response;
		}		
		
		public function setWhere($where) {
			$this->where = $where;
		}
		
		public function setOrder($order) {
			$this->order = $order;
		}	

		public function setResource($resource) {
			$this->resource = $resource;
		}		
		
	}

?>