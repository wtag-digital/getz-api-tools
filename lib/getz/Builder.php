<?php

	/**
	 * Builder.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     http://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0, 26 Jul 2016
	 */
	 
	namespace lib\getz;

	class Builder {
	
		public function __construct() { }

		public function entity($table, $fields, $fk) {
			$ret = "<?php 
			
	/**
	 * Generated by Getz Framework.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     https://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0	
	 */
	 
	namespace src\model; 

	class " . ucfirst($table) . " {
			";
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					$ret .= "
		private $" . $field . ";";
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {		
					$ret .= "
		private $" . $foreignTable . ";";
				}
			}
			
			$ret .= "
			
		public function __construct() { }
			";
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					$ret .= "
		public function get" . ucfirst($field) . "() {
			return \$this->" . $field . ";
		}
		
		public function set" . ucfirst($field) . "(\$" . $field . ") {
			\$this->" . $field . " = \$" . $field . ";
		}		
					";
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {		
					$ret .= "
		public function get" . ucfirst($foreignTable) . "() {
			return \$this->" . $foreignTable . ";
		}
		
		public function set" . ucfirst($foreignTable) . "(\$" . $foreignTable . ") {
			\$this->" . $foreignTable . " = \$" . $foreignTable . ";
		}		
					";
				}
			}
			
			$ret .= "
	}
	
?>";
		
			if (!file_exists("../../src/model/" . ucfirst($table) . ".php")) {
				$fo = fopen("../../src/model/" . ucfirst($table) . ".php", "w");
				$fw = fwrite($fo, $ret);
				
				fclose($fo);
			}
		}
		
		public function input($table, $fields, $fk) {
			$ret = "<?php 
			
	/**
	 * Generated by Getz Framework.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     https://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0	
	 */
	 
	namespace src\model; 
	use src\model;

	class " . ucfirst($table) . "Input {
			";
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					$ret .= "
		private $" . $field . ";";
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {		
					$ret .= "
		private $" . $foreignTable . "Input;";
				}
			}
			
			$ret .= "
			
		private \$error;	
			
		public function __construct(\$request) { ";
		
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					$ret .= "
			if (isset(\$request[" . strtoupper($field) . "])) {
				\$this->" . $field . " = \$request[" . strtoupper($field) . "];	
			}";
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {		
					$ret .= "
			if (isset(\$request[" . strtoupper($foreignTable) . "_INPUT]) && isset(\$request[" . strtoupper($foreignTable) . "_INPUT][ID])) {
				\$" . $foreignTable . "Input = new model\\" . ucfirst($foreignTable) . "Input(\$request[" . strtoupper($foreignTable) . "_INPUT]);
				\$this->" . $foreignTable . "Input = \$" . $foreignTable . "Input;	
			}";
				}
			}
			
		$ret .= "
		}";
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					$ret .= "
					
		public function get" . ucfirst($field) . "() {
			return \$this->" . $field . ";
		}
		
		public function set" . ucfirst($field) . "(\$" . $field . ") {
			\$this->" . $field . " = \$" . $field . ";
		}";
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {		
					$ret .= "
					
		public function get" . ucfirst($foreignTable) . "Input() {
			return \$this->" . $foreignTable . "Input;
		}
		
		public function set" . ucfirst($foreignTable) . "Input(\$" . $foreignTable . "Input) {
			\$this->" . $foreignTable . "Input = \$" . $foreignTable . "Input;
		}";
				}
			}
			
			$ret .= "	
			
		public function getError() {
			return \$this->error;
		}
		
		private function setError(\$error) {
			\$this->error = \$error;
		}	

		public function isValid(\$method) {		
			if (\$method == POST) {";
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					if ($field != "id" && $field != "cadastrado" && $field != "modificado") {
						$ret .= "
				if (\$this->" . $field . " == null || \$this->" . $field . " == STRING_EMPTY) {
					\$this->setError(THE_ATTRIBUTE . " . strtoupper($field) . " . IS_REQUIRED);
					return false;					
				} ";					
					}
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {		
					$ret .= "else if (\$this->" . $foreignTable . "Input == null || \$this->" . $foreignTable . "Input->getId() == null || \$this->" . $foreignTable . "Input->getId() == STRING_EMPTY) {
					\$this->setError(THE_ATTRIBUTE . " . strtoupper($foreignTable) . "_INPUT . DOT . ID . IS_REQUIRED);
					return false;					
				} ";					
				}
			}	

			$ret .= "else {
					return true;
				}
			} else if (\$method == PUT) {
				if (\$this->id == null || \$this->id == STRING_EMPTY) {
					\$this->setError(THE_ATTRIBUTE . ID . IS_REQUIRED);
					return false;					
				} else {
					return true;
				}
			}
		}
		
		public function getEntity() {
			\$" . $table . " = new model\\" . ucfirst($table) . "();
			if (\$this->id != null) {
				\$" . $table . "->setId(\$this->id);
			}";
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					if ($field != "id" && $field != "cadastrado" && $field != "modificado") {
						$ret .= "
			if (\$this->" . $field . " != null) {
				\$" . $table . "->set" . ucfirst($field) . "(\$this->" . $field . ");	
			}";					
					}
				}
			}			
			
			$ret .= " 
			\$" . $table . "->setCadastrado(date(YMD_HIS, (time() - ONE_HOUR_IN_SECONDS * BRAZILIAN_TIME_ZONE)));
			\$" . $table . "->setModificado(date(YMD_HIS, (time() - ONE_HOUR_IN_SECONDS * BRAZILIAN_TIME_ZONE)));";

			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {
					$ret .= "
			if (\$this->" . $foreignTable . "Input != null) {
				\$" . $foreignTable . " = \$this->" . $foreignTable . "Input->getEntity();
				\$" . $table . "->set" . ucfirst($foreignTable) . "(\$" . $foreignTable . ");
			}";					
				}
			}
			$ret .= "
			return \$" . $table . ";
		}		
		
	}
	
?>";
		
			if (!file_exists("../../src/model/" . ucfirst($table) . "Input.php")) {
				$fo = fopen("../../src/model/" . ucfirst($table) . "Input.php", "w");
				$fw = fwrite($fo, $ret);
				
				fclose($fo);
			}
		}		

		public function output($table, $fields, $fk) {
			$ret = "<?php 
			
	/**
	 * Generated by Getz Framework.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     https://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0	
	 */
	 
	namespace src\model; 
	use src\model;	

	class " . ucfirst($table) . "Output implements \JsonSerializable {
			";
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					$ret .= "
		private $" . $field . ";";
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {		
					$ret .= "
		private $" . $foreignTable . "Output;";
				}
			}
			
			$ret .= "
			
		public function __construct() { }";
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					$ret .= "
					
		public function get" . ucfirst($field) . "() {
			return \$this->" . $field . ";
		}
		
		public function set" . ucfirst($field) . "(\$" . $field . ") {
			\$this->" . $field . " = \$" . $field . ";
		}";
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {		
					$ret .= "
					
		public function get" . ucfirst($foreignTable) . "Output() {
			return \$this->" . $foreignTable . "Output;
		}
		
		public function set" . ucfirst($foreignTable) . "Output(\$" . $foreignTable . "Output) {
			\$this->" . $foreignTable . "Output = \$" . $foreignTable . "Output;
		}";
				}
			}
			
			$ret .= "

		public function getOutput(\$" . $table . ") {
			if (\$" . $table . " != null) {
				\$" . $table . "Output = new model\\" . ucfirst($table) . "Output();
				\$" . $table . "Output->setId(\$" . $table . "->getId());";
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					if ($field != "id" && $field != "cadastrado" && $field != "modificado") {
						$ret .= "
				\$" . $table . "Output->set" . ucfirst($field) . "(\$" . $table . "->get" . ucfirst($field) . "());";
					}
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {		
					$ret .= "
				\$" . $foreignTable . "Output = new model\\" . ucfirst($foreignTable) . "Output();
				\$" . $table . "Output->set" . ucfirst($foreignTable) . "Output(\$" . $foreignTable . "Output->getOutput(\$" . $table . "->get" . ucfirst($foreignTable) . "()));";
				}
			}			
			
			$ret .= "
				return \$" . $table . "Output;
			} else {
				return null;
			}
		}
			
		public function getOutputList(\$" . $table . "List) {
			\$" . $table . "OutputList = array();
			\$count = NUMBER_ZERO;
			for (\$x = NUMBER_ZERO; \$x < sizeof(\$" . $table . "List); \$x++) {
				\$" . $table . "OutputList[\$count] = \$this->getOutput(\$" . $table . "List[\$x]);
				\$count++;
			}
			return \$" . $table . "OutputList;
		}
		
		public function jsonSerialize() {
			\$objectVars = get_object_vars(\$this);
			return array_filter(\$objectVars, function (\$value) { 
				return \$value != null;
			});
		}		
		
	}
	
?>";
		
			if (!file_exists("../../src/model/" . ucfirst($table) . "Output.php")) {
				$fo = fopen("../../src/model/" . ucfirst($table) . "Output.php", "w");
				$fw = fwrite($fo, $ret);
				
				fclose($fo);
			}
		}

		/**
		 * @param {String} table
		 * @param {Array} fields
		 * @param {Array} fk
		 */
		public function dao($table, $fields, $fk) {
			$ret = "<?php
			
	/**
	 * Generated by Getz Framework.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     https://wtag.com.br/getz 
	 * @since   1.0.0
	 * @version 1.0.0	 
	 */
	 
	namespace src\model; 
	use src\model;
	
	class " . ucfirst($table) . "Dao {
		
		private \$connection;
		private \$size;
		private \$log;
		private \$columns = "; 
		
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {	
					if ($field == "id") {
						$ret .= strtoupper($table) . " . DOT. ID . WHITE_SPACE . STRING_AS . WHITE_SPACE . DOUBLE_QUOTES . " . strtoupper($table) . " . DOT . ID . DOUBLE_QUOTES";
					} else {
						$ret .= " . COMMA . WHITE_SPACE . " . strtoupper($table) . " . DOT. " . strtoupper($field) . " . WHITE_SPACE . STRING_AS . WHITE_SPACE . DOUBLE_QUOTES . " . strtoupper($table) . " . DOT . " . strtoupper($field) . " . DOUBLE_QUOTES";
					}
				}
			}

		$ret .= ";
		
		public function __construct(\$connection) {
			\$this->connection = \$connection;
		}
		
		public function getInsertId() {
			return \$this->connection->getInsertId();
		}

		public function getSize() {
			return \$this->size;
		}

		private function setLog(\$log) {
			\$this->log = \$log;
		}
		
		public function getLog() {
			return \$this->log;
		}
		
		public function getColumns() {
			return \$this->columns;
		}
		
		public function create(\$" . $table . ") {
			\$query = INSERT . WHITE_SPACE . INTO . WHITE_SPACE . " . strtoupper($table) . " . WHITE_SPACE . LEFT_PARENTHESES . ";
			
			$y = 0;
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {	
					if ($field != "id") {
						if ($y == 0) {
							$ret .= strtoupper($field);
							$y++;
						} else {
							$ret .= " . COMMA . WHITE_SPACE . " . strtoupper($field);	
						}
					}
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {	
					$ret .= " . COMMA . WHITE_SPACE . " . strtoupper($foreignKey);	
				}
			}			
			
			$ret .= " . RIGHT_PARENTHESES . WHITE_SPACE . VALUES . WHITE_SPACE . LEFT_PARENTHESES"; 
					
			$y = 0;

			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {	
					if ($field != "id") {
						if ($y == 0) {
							$ret .= " . DOUBLE_QUOTES . \$" . $table . "->get" . ucfirst($field) . "() . DOUBLE_QUOTES";
							$y++;
						} else {
							$ret .= " . COMMA . WHITE_SPACE . DOUBLE_QUOTES . \$" . $table . "->get" . ucfirst($field) . "() . DOUBLE_QUOTES";
						}
					}
				}
			}		

			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {	
					$ret .= " . COMMA . WHITE_SPACE . DOUBLE_QUOTES . \$" . $table . "->get" . ucfirst($foreignTable) . "()->getId() . DOUBLE_QUOTES";
				}
			}	
					
			$ret .= " . RIGHT_PARENTHESES;
			\$this->setLog(\$query);
			return \$this->connection->execute(\$query);
		}

		public function read(\$where, \$order, \$hasPagination) {
			\$count = NUMBER_ZERO;";
			
			if (count($fk) > 0) {
				$ret .= "
			if (\$where != STRING_EMPTY) {
				\$where = WHERE . WHITE_SPACE . \$where";		
				foreach ($fk as $foreignTable => $foreignKey) {	
					$ret .= " . WHITE_SPACE . STRING_AND . WHITE_SPACE . " . strtoupper($table) . " . DOT . " . strtoupper($foreignKey) . " . WHITE_SPACE . EQUALS . WHITE_SPACE. " . strtoupper($foreignTable) . " . DOT . ID";	
				}
				$ret .= ";
			} else {
				\$where = WHERE";
				
				$y = 0;
				
				foreach ($fk as $foreignTable => $foreignKey) {	
					if ($y == 0) {
						$ret .= " . WHITE_SPACE . " . strtoupper($table) . " . DOT . " . strtoupper($foreignKey) . " . WHITE_SPACE . EQUALS . WHITE_SPACE. " . strtoupper($foreignTable) . " . DOT . ID";
						$y++;
					} else {
						$ret .= " . WHITE_SPACE . STRING_AND . WHITE_SPACE . " . strtoupper($table) . " . DOT . " . strtoupper($foreignKey) . " . WHITE_SPACE . EQUALS . WHITE_SPACE. " . strtoupper($foreignTable) . " . DOT . ID";
					}
				}
				
				$ret .= ";
			}";
			} else {
				$ret .= "
			if (\$where != STRING_EMPTY) {
				\$where = WHERE . WHITE_SPACE . \$where;
			}";
			}			
			
			$ret .= "
			if (\$order != STRING_EMPTY) {
				\$order = ORDER_BY . WHITE_SPACE . \$order;
			}";

			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {	
					$ret .= "
			\$" . $foreignTable . "Dao = new model\\" . ucfirst($foreignTable) . "Dao(\$this->connection);";
				}
			}
			
			$ret .= "
			\$query = SELECT . WHITE_SPACE . \$this->columns"; 
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {	
					$ret .= " . COMMA . WHITE_SPACE . \$" . $foreignTable . "Dao->getColumns()";
				}
			}			
			
			$ret .= " . WHITE_SPACE . FROM . WHITE_SPACE . " . strtoupper($table) . " . WHITE_SPACE . " . strtoupper($table); 
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {	
					$ret .= " . COMMA . WHITE_SPACE . " . strtoupper($foreignTable) . " . WHITE_SPACE . " . strtoupper($foreignTable); 
				}
			}	
			
			$ret .= " . WHITE_SPACE . \$where;
			\$this->setLog(\$query . WHITE_SPACE . \$order);
			\$result = \$this->connection->execute(\$query . WHITE_SPACE . \$order);
			\$" . $table . "List = array();
			while (\$row = \$result->fetch_assoc()) {
				\$" . $table . " = new model\\" . ucfirst($table) . "();
				\$" . $table . "->setId(\$row[" . strtoupper($table) . " . POINT . ID]);";
			
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {	
					if ($field != "id") {
						$ret .= "
				\$" . $table . "->set" . ucfirst($field) . "(\$row[" . strtoupper($table) . " . POINT . " . strtoupper($field) . "]);";
					}
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {	
					$ret .= "
				\$" . $foreignTable . " = new model\\" . ucfirst($foreignTable) . "();
				\$" . $foreignTable . "->setId(\$row[" . strtoupper($foreignTable) . " . DOT . ID]);
				\$" . $foreignTable . "->set" . ucfirst($foreignKey) . "(\$row[" . strtoupper($foreignTable) . " . DOT . " . strtoupper($foreignKey) . "]);
				\$" . $table . "->set" . ucfirst($foreignTable) . "(\$" . $foreignTable . ");";
				}
			}
			
			$ret .= "
				\$" . $table . "List[\$count] = \$" . $table . ";
				\$count++;
			}
			\$this->connection->free(\$result);
			if (\$hasPagination && \$count > NUMBER_ZERO) {
				\$result = \$this->connection->execute(\$query);
				\$size = NUMBER_ZERO;
				while (\$row = \$result->fetch_assoc()) {
					\$size++;
				}
				\$this->connection->free(\$result);				
				\$this->size = \$size;
			}
			return \$" . $table . "List;
		}

		public function update(\$" . $table . ") {
			\$query = UPDATE . WHITE_SPACE . " . strtoupper($table) . " . WHITE_SPACE . SET . WHITE_SPACE . ID . WHITE_SPACE . EQUALS . 
					WHITE_SPACE . DOUBLE_QUOTES . \$" . $table . "->getId() . DOUBLE_QUOTES;";
					
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {	
					if ($field != "id" && $field != "cadastrado" && $field != "modificado") {
						$ret .= "
			if (\$" . $table . "->get" . ucfirst($field) . "() != null && \$" . $table . "->get" . ucfirst($field) . "() != STRING_EMPTY) {
				\$query .= COMMA . WHITE_SPACE . " . strtoupper($field) . " . WHITE_SPACE . EQUALS . WHITE_SPACE . DOUBLE_QUOTES . 
						\$" . $table . "->get" . ucfirst($field) . "() . DOUBLE_QUOTES;
			}";
					}
				}
			}
			
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {	
						$ret .= "
			if (\$" . $table . "->get" . ucfirst($foreignTable) . "() != null && \$" . $table . "->get" . ucfirst($foreignTable) . "()->getId() != null &&  
					\$" . $table . "->get" . ucfirst($foreignTable) . "()->getId() != STRING_EMPTY) {
				\$query .= COMMA . WHITE_SPACE . " . strtoupper($foreignKey) . " . WHITE_SPACE . EQUALS . WHITE_SPACE . DOUBLE_QUOTES . 
						\$" . $table . "->get" . ucfirst($foreignTable) . "()->getId() . DOUBLE_QUOTES;
			}";
				}
			}				
			
			$ret .= "
			\$query .= COMMA . WHITE_SPACE . MODIFICADO . WHITE_SPACE . EQUALS . WHITE_SPACE . DOUBLE_QUOTES . 
					\$" . $table . "->getModificado() . DOUBLE_QUOTES . WHERE . WHITE_SPACE . ID . EQUALS . 
					\$" . $table . "->getId();
			\$this->setLog(\$query);
			return \$this->connection->execute(\$query);
		}

		public function delete(\$" . $table . ") {
			\$query = DELETE . WHITE_SPACE . FROM . WHITE_SPACE . " . strtoupper($table) . " . WHITE_SPACE . WHERE . WHITE_SPACE . ID . 
					WHITE_SPACE . EQUALS . WHITE_SPACE . \$" . $table . "->getId();
			\$this->setLog(\$query);
			return \$this->connection->execute(\$query);
		}

	}

?>";
		
			if (!file_exists("../../src/model/" . ucfirst($table) . "Dao.php")) {
				$fo = fopen("../../src/model/" . ucfirst($table) . "Dao.php", "w");
				$fw = fwrite($fo, $ret);
				
				fclose($fo);
			}
		} 	
	
	    /*
		 * @param {String} table
		 * @param {Array} fields
		 * @param {Array} fk
		 * @param {String} answer
		 */
		public function controller($table, $fields, $fk, $answer) {
			$ret = "<?php

	/**
	 * Generated by Getz Framework.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     http://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	 
	namespace src\controller;
	use lib\getz;
	use src\model;
	
	class " . ucfirst($table) . " extends getz\Activator {
		
		public function __construct() { }
		
		public function init() {
			enableCORS();
			if (\$_SERVER[REQUEST_METHOD] == strtoupper(POST)) {
				\$" . $table . "Input = new model\\" . ucfirst($table) . "Input(\$this->request);
				if (\$" . $table . "Input->isValid(POST)) {
					\$this->daoFactory->beginTransaction();
					\$" . $table . "Dao = \$this->daoFactory->get" . ucfirst($table) . "Dao();
					\$result = \$" . $table . "Dao->create(\$" . $table . "Input->getEntity());
					\$this->log->write(POST, \$" . $table . "Dao->getLog(), \$this->debug);
					\$insertId = \$" . $table . "Dao->getInsertId();
					if (\$result) {		
						\$" . $table . "List = \$" . $table . "Dao->read(" . strtoupper($table) . " . DOT . ID . WHITE_SPACE . EQUALS . 
								WHITE_SPACE . \$insertId, STRING_EMPTY, false);
						\$this->log->write(GET, \$" . $table . "Dao->getLog(), \$this->debug);
						\$" . $table . "Output = new model\\" . ucfirst($table) . "Output();
						\$this->response[RESPONSE][" . strtoupper($table) . "][VALUE] = \$" . $table . "Output->getOutputList(
								\$" . $table . "List);									
						\$this->response[RESPONSE][" . strtoupper($table) . "][SIZE] = sizeOf(
								\$this->response[RESPONSE][" . strtoupper($table) . "][VALUE]);							
						\$this->daoFactory->commit();
						\$this->response[RESPONSE][STATUS] = NUMBER_TWO_HUNDRED;
						\$this->response[RESPONSE][MESSAGE] = SUCCESS;								
					} else {												
						\$this->daoFactory->rollback();								
						\$this->response[RESPONSE][STATUS] = NUMBER_FIVE_HUNDRED;
						\$this->response[RESPONSE][MESSAGE] = INTERNAL_SERVER_ERROR;
					}
					\$this->daoFactory->close();					
				} else {
					\$this->response[RESPONSE][STATUS] = NUMBER_FOUR_HUNDRED;
					\$this->response[RESPONSE][MESSAGE] = \$" . $table . "Input->getError();
				}
			} else if (\$_SERVER[REQUEST_METHOD] == strtoupper(GET)) {
				if (\$this->resource != STRING_EMPTY) {
					\$this->where = " . strtoupper($table) . " . DOT . ID . WHITE_SPACE . EQUALS . WHITE_SPACE . \$this->resource;	
				}
				if (\$this->order == STRING_EMPTY) {
					\$this->order = " . strtoupper($table) . " . DOT . ID . WHITE_SPACE . DESC;
				}
				\$this->daoFactory->beginTransaction();
				\$" . $table . "Dao = \$this->daoFactory->get" . ucfirst($table) . "Dao();
				\$" . $table . "List = \$" . $table . "Dao->read(\$this->where, \$this->order, \$this->hasPagination);	
				\$this->log->write(GET, \$" . $table . "Dao->getLog(), \$this->debug);
				\$" . $table . "Output = new model\\" . ucfirst($table) . "Output();
				\$this->response[RESPONSE][" . strtoupper($table) . "][VALUE] = \$" . $table . "Output->getOutputList(\$" . $table . "List);													
				\$this->daoFactory->close();				
				if (\$this->hasPagination) {
					\$this->response[RESPONSE][" . strtoupper($table) . "][SIZE] = \$" . $table . "Dao->getSize();
					if (\$this->response[RESPONSE][" . strtoupper($table) . "][SIZE] == NUMBER_ZERO) {
						\$this->response[RESPONSE] = null;
						\$this->response[RESPONSE][MESSAGE] = DATA_NOT_FOUND;
					}
				} else {
					\$this->response[RESPONSE][" . strtoupper($table) . "][SIZE] = sizeOf(
							\$this->response[RESPONSE][" . strtoupper($table) . "][VALUE]);	
					if (\$this->response[RESPONSE][" . strtoupper($table) . "][SIZE] == NUMBER_ZERO) {
						\$this->response[RESPONSE] = null;
						\$this->response[RESPONSE][MESSAGE] = DATA_NOT_FOUND;
					}
				}
				\$this->response[RESPONSE][STATUS] = NUMBER_TWO_HUNDRED;
			} else if (\$_SERVER[REQUEST_METHOD] == strtoupper(PUT)) {		
				if (\$this->resource != STRING_EMPTY && !empty(\$this->request) && \$this->request[ID] == 
						\$this->resource) {
					\$" . $table . "Input = new model\\" . ucfirst($table) . "Input(\$this->request);
					if (\$" . $table . "Input->isValid(PUT)) {
						\$this->daoFactory->beginTransaction();
						\$" . $table . "Dao = \$this->daoFactory->get" . ucfirst($table) . "Dao();
						\$" . $table . "List = \$" . $table . "Dao->read(" . strtoupper($table) . " . DOT . ID . WHITE_SPACE . EQUALS . 
								WHITE_SPACE . \$this->resource, STRING_EMPTY, false);
						\$this->log->write(GET, \$" . $table . "Dao->getLog(), \$this->debug);
						if (\$" . $table . "List != null && sizeOf(\$" . $table . "List) > NUMBER_ZERO) {
							\$result = \$" . $table . "Dao->update(\$" . $table . "Input->getEntity());
							\$this->log->write(PUT, \$" . $table . "Dao->getLog(), \$this->debug);	
							if (\$result) {	
								\$" . $table . "List = \$" . $table . "Dao->read(" . strtoupper($table) . " . DOT . ID . WHITE_SPACE . EQUALS . 
										WHITE_SPACE . \$this->resource, STRING_EMPTY, false);
								\$this->log->write(GET, \$" . $table . "Dao->getLog(), \$this->debug);
								\$" . $table . "Output = new model\\" . ucfirst($table) . "Output();
								\$this->response[RESPONSE][" . strtoupper($table) . "][VALUE] = \$" . $table . "Output->getOutputList(
										\$" . $table . "List);									
								\$this->response[RESPONSE][" . strtoupper($table) . "][SIZE] = sizeOf(
										\$this->response[RESPONSE][" . strtoupper($table) . "][VALUE]);							
								\$this->daoFactory->commit();
								\$this->response[RESPONSE][STATUS] = NUMBER_TWO_HUNDRED;
								\$this->response[RESPONSE][MESSAGE] = SUCCESS;									
							} else {							
								\$this->daoFactory->rollback();
								\$this->response[RESPONSE][STATUS] = NUMBER_FIVE_HUNDRED;
								\$this->response[RESPONSE][MESSAGE] = INTERNAL_SERVER_ERROR;
							}
						} else {
							\$this->response[RESPONSE][STATUS] = NUMBER_TWO_HUNDRED;
							\$this->response[RESPONSE][MESSAGE] = DATA_NOT_FOUND;	
						}
						\$this->daoFactory->close();
					} else {
						\$this->response[RESPONSE][STATUS] = NUMBER_FOUR_HUNDRED;
						\$this->response[RESPONSE][MESSAGE] = \$" . $table . "Input->getError();
					}
				} else {
					\$this->response[RESPONSE][STATUS] = NUMBER_FOUR_HUNDRED;
					\$this->response[RESPONSE][MESSAGE] = BAD_REQUEST;	
				}
			} else if (\$_SERVER[REQUEST_METHOD] == strtoupper(DELETE)) {
				if (\$this->resource != STRING_EMPTY) {
					\$this->daoFactory->beginTransaction();
					\$" . $table . "Dao = \$this->daoFactory->get" . ucfirst($table) . "Dao();
					\$" . $table . "List = \$" . $table . "Dao->read(" . strtoupper($table) . " . DOT . ID . WHITE_SPACE . EQUALS . WHITE_SPACE . 
							\$this->resource, STRING_EMPTY, false);
					\$this->log->write(GET, \$" . $table . "Dao->getLog(), \$this->debug);
					if (\$" . $table . "List != null && sizeOf(\$" . $table . "List) > NUMBER_ZERO) {
						\$result = \$" . $table . "Dao->delete(\$" . $table . "List[NUMBER_ZERO]);
						\$this->log->write(DELETE, \$" . $table . "Dao->getLog(), \$this->debug);	
						if (\$result) {
							\$this->daoFactory->commit();
							\$this->response[RESPONSE][STATUS] = NUMBER_TWO_HUNDRED;
							\$this->response[RESPONSE][MESSAGE] = SUCCESS;							
						} else {
							\$this->daoFactory->rollback();
							\$this->response[RESPONSE][STATUS] = NUMBER_FIVE_HUNDRED;
							\$this->response[RESPONSE][MESSAGE] = INTERNAL_SERVER_ERROR;	
						}
						\$this->daoFactory->close();	
					} else {
						\$this->response[RESPONSE][STATUS] = NUMBER_TWO_HUNDRED;
						\$this->response[RESPONSE][MESSAGE] = DATA_NOT_FOUND;	
					}
				} else {
					\$this->response[RESPONSE][STATUS] = NUMBER_FOUR_HUNDRED;
					\$this->response[RESPONSE][MESSAGE] = BAD_REQUEST;	
				}				
			} else {
				\$this->response[RESPONSE][STATUS] = NUMBER_FOUR_HUNDRED;
				\$this->response[RESPONSE][MESSAGE] = BAD_REQUEST;	
			}
			echo json_encode(\$this->response, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
		}
		
	}

?>";

			if (!file_exists("../../src/controller/" . ucfirst($table) . ".php")) {
				$fo = fopen("../../src/controller/" . ucfirst($table) . ".php", "w");
				$fw = fwrite($fo, $ret);

				fclose($fo);
			}
		}

		/**
		 * @param {String} table
		 * @param {Array} fields
		 * @param {Array} fk
		 */
		public function daoFactory($table, $fields, $fk) {
			$buffer = file_get_contents("../../src/logic/DaoFactory.php");
			$find = "get" . ucfirst($table) . "Dao";
			$pos = strpos($buffer, $find);
			
			if ($pos === false) {
				$arr = file("../../src/logic/DaoFactory.php");
			
				array_pop($arr);
				array_pop($arr);
				array_pop($arr);
				array_pop($arr);

				file_put_contents('../../src/logic/DaoFactory.php', $arr);
	
				$fo = fopen("../../src/logic/DaoFactory.php", "a");
				$fw = fwrite($fo, "		
		public function get" . ucfirst($table) . "Dao() {
			return new model\\" . ucfirst($table) . "Dao(\$this->getConnection());
		}
		
	}
	
?>");

				fclose($fo);
			}
		}

		/**
		 * @param {String} table
		 * @param {Array} fields
		 * @param {Array} fk
		 */
		public function constants($table, $fields, $fk) {
			$buffer = file_get_contents("../../Constants.php");
			$find = "\"" . strtoupper($table) . "\"";
			$pos = strpos($buffer, $find);
			if ($pos === false) {
				$arr = file("../../Constants.php");
				array_pop($arr);
				array_pop($arr);
				file_put_contents("../../Constants.php", $arr);
				$fo = fopen("../../Constants.php", "a");
				$fw = fwrite($fo, "	define(\"" . strtoupper($table) . "\", \"" . $table . "\");
	
?>");
				fclose($fo);
			}
			$buffer = file_get_contents("../../Constants.php");
			$find = "\"" . strtoupper($table) . "_INPUT" . "\"";
			$pos = strpos($buffer, $find);
			if ($pos === false) {
				$arr = file("../../Constants.php");
				array_pop($arr);
				array_pop($arr);
				file_put_contents("../../Constants.php", $arr);
				$fo = fopen("../../Constants.php", "a");
				$fw = fwrite($fo, "	define(\"" . strtoupper($table) . "_INPUT\", \"" . $table . "Input\");
	
?>");
				fclose($fo);
			}
			if (count($fields) > 0) {
				foreach ($fields as $field => $type) {		
					$buffer = file_get_contents("../../Constants.php");
					$find = "\"" . strtoupper($field) . "\"";
					$pos = strpos($buffer, $find);
					if ($pos === false) {
						$arr = file("../../Constants.php");
						array_pop($arr);
						array_pop($arr);
						file_put_contents("../../Constants.php", $arr);
						$fo = fopen("../../Constants.php", "a");
						$fw = fwrite($fo, "	define(\"" . strtoupper($field) . "\", \"" . $field . "\");
			
		?>");
						fclose($fo);
					}
				}
			}
			if (count($fk) > 0) {
				foreach ($fk as $foreignTable => $foreignKey) {	
					$buffer = file_get_contents("../../Constants.php");
					$find = "\"" . strtoupper($foreignKey) . "\"";
					$pos = strpos($buffer, $find);
					if ($pos === false) {
						$arr = file("../../Constants.php");
						array_pop($arr);
						array_pop($arr);
						file_put_contents("../../Constants.php", $arr);
						$fo = fopen("../../Constants.php", "a");
						$fw = fwrite($fo, "	define(\"" . strtoupper($foreignKey) . "\", \"" . $foreignKey . "\");
			
		?>");
						fclose($fo);
					}
				}
			}
		}		

	}

?>