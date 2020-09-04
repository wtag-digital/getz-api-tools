<?php 

	/**
	 * Sqli.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     http://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0, 26 Jul 2016
	 */	 
	 
	namespace lib\getz;
	
	use Mysqli;

	class Sqli {

		private $sqli;

		/**
		 * @param {String} server
		 * @param {String} username
		 * @param {String} password
		 * @param {String} database
		 */
		public function __construct($server, $username, $password, $database) {
			$this->sqli = new Mysqli($server, $username, $password, $database);
			
			if ($this->sqli->connect_errno > 0)
				die("Unable to connect to database.");

			$this->sqli->set_charset("utf8");
		}

		public function beginTransaction() {
			$this->sqli->autocommit(FALSE);
		}

		/**
		 * @param {String} query
		 * @return {Object}
		 */
		public function execute($query) {
			return $this->sqli->query($query);
		}

		public function commit() {
			$this->sqli->commit();
		}

		public function rollBack() {
			$this->sqli->rollback();
		}

		public function close() {
			$this->sqli->close();
		}

		/**
		 * @param {Object} result
		 */	
		public function free($result) {
			$result->free();
		}
		
		/**
		 * @return {Integer}
		 */
		public function getInsertId() {
			return $this->sqli->insert_id;
		}

	}

?>