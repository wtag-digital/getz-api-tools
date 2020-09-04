<?php

	/**
	 * Boot
	 * 
	 * @author Mario Sakamoto <mskamot@gmail.com>
	 * @see https://wtag.com.br/getz
	 */
	 
	$settings = simplexml_load_file("settings.xml");
	
	/*
	 * Settings.xml
	 */
	if (!file_exists("../../settings.xml")) {
		$fo = fopen("../../settings.xml", "w");
		
		$fw = fwrite($fo, "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
<settings>
	<package>" . $settings->project . "</package>
	<debug>true</debug>	
	<connection>
		<is_official>false</is_official>
	</connection>
</settings>");	

		fclose($fo);
	}

	/*
	 * Connection.php
	 */
	if (!file_exists("../../src/logic/Connection.php")) {
		$fo = fopen("../../src/logic/Connection.php", "w");
		
		$fw = fwrite($fo, "<?php

	/**
	 * Connection
	 *
	 * @author Mário Sakamoto <mskamot@gmail.com>
	 * @see http://mariosakamoto.com/getz 
	 */
	 
	namespace src\\logic;

	class Connection {

		private \$server;
		private \$userName;
		private \$password;
		private \$dataBase;
	
		public function __construct(\$_DAO_FACTORY_IS_OFFICIAL) {
			if (\$_DAO_FACTORY_IS_OFFICIAL == \"true\") {
				\$this->setServer(\"" . $settings->official->server . "\");
				\$this->setUserName(\"" . $settings->official->username . "\");
				\$this->setPassword(\"" . $settings->official->password . "\");
				\$this->setDatabase(\"" . $settings->official->database . "\");
			} else {
				\$this->setServer(\"" . $settings->localhost->server . "\");
				\$this->setUsername(\"" . $settings->localhost->username . "\");
				\$this->setPassword(\"" . $settings->localhost->password . "\");
				\$this->setDatabase(\"" . $settings->localhost->database . "\");
			}
		}
		
		/**
		 * @param {String} server
		 */
		private function setServer(\$server) {
			\$this->server = \$server;
		}
		
		/**
		 * @return {String}
		 */
		public function getServer() {
			return \$this->server;
		}
		
		/**
		 * @param {String} userName
		 */
		private function setUsername(\$username) {
			\$this->username = \$username;
		}
		
		/**
		 * @return {String}
		 */
		public function getUsername() {
			return \$this->username;
		}
		
		/**
		 * @param {String} password
		 */
		private function setPassword(\$password) {
			\$this->password = \$password;
		}
		
		/**
		 * @return {String}
		 */
		public function getPassword() {
			return \$this->password;
		}
		
		/**
		 * @param {String} dataBase
		 */ 
		private function setDatabase(\$database) {
			\$this->database = \$database;
		}
		
		/**
		 * @return {String}
		 */
		public function getDatabase() {
			return \$this->database;
		}

	}

?>");	

		fclose($fo);
	}	
	
	/*
	 * Reveng.bat
	 */
	if (!file_exists("reveng.bat")) {
		$fo = fopen("reveng.bat", "w");
		
		$fw = fwrite($fo, "@ECHO OFF
SET BIN_TARGET=%~dp0/../../lib/getz/reveng.php
php \"%BIN_TARGET%\" %*");	

		fclose($fo);
	}
	
	if (!file_exists("../../.htaccess")) {
		$ret = "Options -Indexes

<FilesMatch \"\\.(xml|ini)\$\">
	Deny from all
</FilesMatch>

<FilesMatch \"\\.(jpg|jpeg|png|gif|ico|css|js)\$\">
	Header set Cache-Control \"max-age=14400, public\"
</FilesMatch>

<FilesMatch \"\\.php\$\">
	Header set Cache-Control \"max-age=0, private, no-store, no-cache, must-revalidate\"
	Header set Pragma \"no-cache\"
</FilesMatch>

<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE application/javascript
  AddOutputFilterByType DEFLATE application/rss+xml
  AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
  AddOutputFilterByType DEFLATE application/x-font
  AddOutputFilterByType DEFLATE application/x-font-opentype
  AddOutputFilterByType DEFLATE application/x-font-otf
  AddOutputFilterByType DEFLATE application/x-font-truetype
  AddOutputFilterByType DEFLATE application/x-font-ttf
  AddOutputFilterByType DEFLATE application/x-javascript
  AddOutputFilterByType DEFLATE application/xhtml+xml
  AddOutputFilterByType DEFLATE application/xml
  AddOutputFilterByType DEFLATE font/opentype
  AddOutputFilterByType DEFLATE font/otf
  AddOutputFilterByType DEFLATE font/ttf
  AddOutputFilterByType DEFLATE image/svg+xml
  AddOutputFilterByType DEFLATE image/x-icon
  AddOutputFilterByType DEFLATE text/css
  AddOutputFilterByType DEFLATE text/html
  AddOutputFilterByType DEFLATE text/javascript
  AddOutputFilterByType DEFLATE text/plain
  AddOutputFilterByType DEFLATE text/xml
</IfModule>

RewriteEngine On
RewriteBase /" . $settings->project . "/

#RewriteCond %{HTTPS} off
#RewriteCond %{HTTP_HOST} (www\\.)?" . $settings->project . ".com.br
#RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
#Header always set Content-Security-Policy: upgrade-insecure-requests

# API urls
RewriteRule ^api/([^/]*)/filter-column/([^/]*)/filter-condition/([^/]*)/filter-value/([^/]*)/order-column/([^/]*)/order-value/([^/]*)/page/([0-9]*)/([0-9]*)/?$ Autoload.php?class=$1&state=&filterColumn=$2&filterCondition=$3&filterValue=$4&orderColumn=$5&orderValue=$6&page=$7&pageSize=$8&resource=&friendly= [NC,L]
RewriteRule ^api/([^/]*)/filter-column/([^/]*)/filter-condition/([^/]*)/filter-value/([^/]*)/order-column/([^/]*)/order-value/([^/]*)/page/([0-9]*)/?$ Autoload.php?class=$1&state=&filterColumn=$2&filterCondition=$3&filterValue=$4&orderColumn=$5&orderValue=$6&page=$6&pageSize=&resource=&friendly= [NC,L]
RewriteRule ^api/([^/]*)/filter-column/([^/]*)/filter-condition/([^/]*)/filter-value/([^/]*)/order-column/([^/]*)/order-value/([^/]*)/?$ Autoload.php?class=$1&state=&filterColumn=$2&filterCondition=$3&filterValue=$4&orderColumn=$5&orderValue=$6&page=&pageSize=&resource=&friendly= [NC,L]
RewriteRule ^api/([^/]*)/filter-column/([^/]*)/filter-condition/([^/]*)/filter-value/([^/]*)/?$ Autoload.php?class=$1&state=&filterColumn=$2&filterCondition=$3&filterValue=$4&orderColumn=&orderValue=&page=&pageSize=&resource=&friendly= [NC,L]
RewriteRule ^api/([^/]*)/order-column/([^/]*)/order-value/([^/]*)/page/([0-9]*)/([0-9]*)/?$ Autoload.php?class=$1&state=&filterColumn=&filterCondition=&filterValue=&orderColumn=$2&orderValue=$3&page=$4&pageSize=$5&resource=&friendly= [NC,L]
RewriteRule ^api/([^/]*)/order-column/([^/]*)/order-value/([^/]*)/page/([0-9]*)/?$ Autoload.php?class=$1&state=&filterColumn=&filterCondition=&filterValue=&orderColumn=$2&orderValue=$3&page=$4&pageSize=&resource=&friendly= [NC,L]
RewriteRule ^api/([^/]*)/order-column/([^/]*)/order-value/([^/]*)/?$ Autoload.php?class=$1&state=&filterColumn=&filterCondition=&filterValue=&orderColumn=$2&orderValue=$3&page=&pageSize=&resource=&friendly= [NC,L]
RewriteRule ^api/([^/]*)/resource/([0-9]*)/?$ Autoload.php?class=$1&state=&filterColumn=&filterCondition=&filterValue=&orderColumn=&orderValue=&page=&pageSize=&resource=$2&friendly= [NC,L]
RewriteRule ^api/([^/]*)/?$ Autoload.php?class=$1&state=&filterColumn=&filterCondition=&filterValue=&orderColumn=&orderValue=&page=&pageSize=&resource=&friendly= [NC,L]";
		
		$fo = fopen("../../.htaccess", "w");
		$fw = fwrite($fo, $ret);
		
		fclose($fo);
	}
	
	/*
	 * Robot.xml
	 */
	if (!file_exists("../../robots.txt")) {
		$fo = fopen("../../robots.txt", "w");
		
		$fw = fwrite($fo, "User-agent: *
Disallow: /lib/
Disallow: /res/
Disallow: /src/");	

		fclose($fo);
	}	
	
?>