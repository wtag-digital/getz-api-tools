<?php

	/**
	 * Data access object.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     http://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0, 26 Jul 2016
	 */
	 
	namespace lib\getz;

	use lib\getz;
	
	class Dao extends getz\Sqli {

		private $sqli;
		private $server;
		private $username;
		private $password;
		private $database;
		
		/**
		 * {Object} connection
		 */
		public function __construct($connection) { 
			$this->server = $connection->getServer();
			$this->username = $connection->getUsername();
			$this->password = $connection->getPassword();
			$this->database = $connection->getDatabase();
		}
		
		/**
		 * @param {String} parameter
		 * @return {String}
		 */
		public function prepare($parameter) {
			$statement = "";
			$statement = str_replace("\\", "", $parameter);
			$statement = str_replace("'", "\'", $statement);
			$statement = str_replace("\"", "\'", $statement);
			return $statement;
		}
		
		/**
		 * @return {Object}
		 */
		public function getConnection() {
			return $this->sqli;
		}
		
		public function beginTransaction() {
			$this->sqli = new getz\Sqli($this->server, $this->username, $this->password, 
					$this->database);
			$this->sqli->beginTransaction();
		}

		public function commit() {
			$this->sqli->commit();
		}

		public function rollBack() {
			$this->sqli->rollBack();
		}

		public function close() {
			$this->sqli->close();
		}

	}

?>