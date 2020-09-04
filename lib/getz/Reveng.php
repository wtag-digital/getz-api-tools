<?php

	/**
	 * Reverse engineering.
	 * 
	 * @author  Mario Sakamoto <mskamot@gmail.com>
	 * @see     http://wtag.com.br/getz
	 * @since   1.0.0
	 * @version 1.0.0, 26 Jul 2016
	 */	 	 
	 
	namespace lib\getz;
	
	/*
	 * SPL
	 */	 	
	spl_autoload_register(function ($class) {
		require_once("../../" . $class . ".php");
	});	 	
	
	use lib\getz;
	use src\logic;
	
	/*
	 * Start the configured connection
	 */
	$dao = new getz\Dao(new logic\Connection(false));
	$dao->beginTransaction();
	
	/*
	 * Operation builder
	 */
	$reveng = new getz\Reveng($dao->getConnection());
	$reveng->build();
	
	$dao->close();

	/**
	 * Reveng
	 */
	class Reveng {
	
		private $connection;
		
		/**
		 * @param {Object} connection
		 */
		public function __construct($connection) {
			$this->connection = $connection;
		}

		public function build() {
			$sql = "
				SELECT 
					table_name AS 'table' 
				FROM 
					information_schema.tables
				WHERE 
					table_schema = DATABASE()";
					
			$result = $this->connection->execute($sql);
			
			$builder = "@ECHO OFF";
			
			while ($row = $result->fetch_assoc()) {			
				$builder .= "
SET BIN_TARGET=%~dp0/map/" . $row["table"] . ".php
php \"%BIN_TARGET%\" %*";
				
				$filename = "map/" . $row["table"] . ".php";
				
				if (!file_exists($filename)) {
					$script = '<?php 

	/**
	 * Generated by Getz Framework 
	 *
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz
	 */
	 
	/*
	 * SPL
	 */	 	
	spl_autoload_register(function ($class) {
		require_once("../../" . $class . ".php");
	});	 
			
	use lib\\getz;';
				
					$script .= "
			
	\$table = \"" . $row["table"] . "\";";
				
					$script .= 
	"
			
	/*
	 * \$fields = array('field' => 'type');
	 *
	 * types: string16, string32, string64,
	 * integer, double,
	 * date, datetime, time, new, now,
	 * cpf, cnpj, cep, phone, celphone,
	 * photo, photoWithPosition, position, upload
	 */ ";
				
					$script .= $this->getColum($row["table"]);
				
					$script .= "
				
	/*
	 *\$fk = array(\"table\" => \"field\");
	 */";
				 
					$script .= $this->getFk($row["table"]);
				
					$script .= "
				
	// Set the table if this screen call another
	\$call = \"\";
	
	// Set the column for answer after the call
	\$answer = \"\";";
	
					$script .=  
	"

	/*
	 * Builder
	 */
	\$builder = new getz\\Builder();
	\$builder->entity(\$table, \$fields, \$fk);
	\$builder->input(\$table, \$fields, \$fk);
	\$builder->output(\$table, \$fields, \$fk);
	\$builder->dao(\$table, \$fields, \$fk);
	\$builder->daoFactory(\$table, \$fields, \$fk);
	\$builder->controller(\$table, \$fields, \$fk, \$answer);
	\$builder->constants(\$table, \$fields, \$fk);";

					$script .= '
				
?>';

				}

				if (!file_exists($filename)) {
					$fo = fopen($filename, "w");
					$fw = fwrite($fo, $script);	
					fclose($fo);
				}
			}
			
			$filename = "builder.bat";
			
			if (!file_exists($filename)) {
				$fo = fopen($filename, "w");
				$fw = fwrite($fo, $builder . "
SET BIN_TARGET=%~dp0/builder.bat
del \"%BIN_TARGET%\" %*");	

				fclose($fo);
			}

			$this->connection->free($result);
		}
		
		/**
		 * @param {String} table
		 * return {String}
		 */
		public function getFk($table) {
			$fks = '
	$fk = array(';
			
			$cont = 0;
			
			$sql = 
					"SELECT 
						TABLE_NAME AS 'table_name',
						COLUMN_NAME AS 'column_name',
						REFERENCED_COLUMN_NAME AS 'referenced_column_name',
						REFERENCED_TABLE_NAME AS 'referenced_table_name'
					FROM 
						information_schema.KEY_COLUMN_USAGE
					WHERE 
						TABLE_SCHEMA = DATABASE() AND
						REFERENCED_TABLE_SCHEMA = DATABASE() AND
						TABLE_NAME = '" . $table . "'";
								   
			$result = $this->connection->execute($sql);

			while ($row = $result->fetch_assoc()) {
				if ($cont == 0)
					$fks .= "'" . $row['referenced_table_name'] . "' => '" . $row['column_name'] . "'";
				else {
					$fks .= ",
			'" . $row['referenced_table_name'] . "' => '" . $row['column_name'] . "'";
				}
				
				$cont++;
			}
			
			$fks .= "
	);";
			
			return $fks;					  
		}
		
		/**
		 * @param table String
		 * @return Array
		 */
		public function getColum($table) {
			$sql = "SHOW COLUMNS FROM " . $table;	   
			$result = $this->connection->execute($sql);

			$arrayColl = '
	$fields = array(';		
			
			$cont = 0;

			while ($row = $result->fetch_assoc()) {
				$control = false;
				
				$sqlFk = 
						"SELECT 
							TABLE_NAME AS 'table_name',
							COLUMN_NAME AS 'column_name',
							REFERENCED_COLUMN_NAME AS 'referenced_column_name',
							REFERENCED_TABLE_NAME AS 'referenced_table_name'
						FROM 
							information_schema.KEY_COLUMN_USAGE
						WHERE 
							TABLE_SCHEMA = DATABASE() AND
							REFERENCED_TABLE_SCHEMA = DATABASE() AND
							TABLE_NAME = '" . $table . "'";
							
				$resultFk = $this->connection->execute($sqlFk);
				
				while ($rowFk = $resultFk->fetch_assoc()) {
					if ($rowFk['column_name'] == $row['Field'])
						$control = true;	
				}
				
				if($control == false) {			
					if($cont == 0)
						$arrayColl .= "'".$row['Field']."' => '".$this->getTypes($row['Field'],$row['Type'])."'";
					else
						$arrayColl .= ",
			'" . $row['Field'] . "' => '" . $this->getTypes($row['Field'], $row['Type']) . "'";
					
					$cont++;
				}
			}
			
			$arrayColl .="
	);";	

			return $arrayColl;
		}
		
		/**
		 * @param column String
		 * @param type String
		 * @return String
		 */
		public function getTypes($column, $type) { 
			if (strtoupper($column) == "ID")
				return "integer";
			else if (strtoupper($column) == "CADASTRADO")
				return "new";
			else if (strtoupper($column) == "MODIFICADO")
				return "now";
			else if (strtoupper($column) == "REGISTERED")
				return "new";
			else if (strtoupper($column) == "MODIFIED")
				return "now";
			else if (strtoupper($column) == "CPF")
				return "cpf";
			else if (strtoupper($column) == "CNPJ")
				return "cnpj";
			else if (strtoupper($column) == "CEP")
				return "cep";
			else if (strtoupper($column) == "TELEFONE")
				return "phone";
			else if (strtoupper($column) == "PHONE")
				return "phone";
			else if (strtoupper($column) == "CELULAR")
				return "cellphone";
			else if (strtoupper($column) == "CELLPHONE")
				return "cellphone";
			else if (strpos(strtoupper($type), "INT") !== false)
				return "integer";
			else if (strpos(strtoupper($type), "DOUBLE") !== false)
				return "double";
			else if (strpos(strtoupper($type), "DATETIME") !== false)
				return "datetime";
			else if (strpos(strtoupper($type), "DATE") !== false)
				return "date";
			else if (strpos(strtoupper($type), "TIME") !== false)
				return "time";
			else if (strtoupper($column) == "FOTO")
				return "photo";
			else if (strtoupper($column) == "PHOTO")
				return "photo";	
			else if (strtoupper($column) == "UPLOAD")
				return "upload";	
			else if (strpos(strtoupper($type), "VARCHAR") !== false) {
				$temp = strtoupper($type);		
				$temp = str_replace("VARCHAR(", "", $temp); 		
				$temp = str_replace(")", "", $temp);	
				
				if (intval($temp) < 21 )
					return "string16";
				else
					return "string32";	
			} else if (strpos(strtoupper($type), "TEXT") !== false)
				return "string64";
				
			return "?";
		}	

	}

?>