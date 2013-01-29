<?php


class Connexion {

	var $info	= NULL;

	var $serveur	= NULL;
	var $login		= NULL;
	var $password	= NULL;
	var $db			= NULL;
	var $connect	= NULL;


	function connexion($_serveur=NULL,$_login=NULL,$_password=NULL,$_db=NULL){
		$this->serveur 		= $_serveur;
		$this->login		= $_login;
		$this->password		= $_password;
		$this->db			= $_db;
		
		$this->info = $this->connect_db();

	}

	
	function connect_db() {
		//if(!isset($this->connect)){
			// on se connecte à MySQL 
			$this->connect = mysql_connect($this->serveur, $this->login, $this->password); 
			
			// on séléctionne la base 
			mysql_select_db($this->db,$this->connect);
			
			
			$var['msg'] = 'ON EST CONNECTE';
			$var['db'] 	= $this->db;

			//return "ON EST CONNECTE";
			
			$this->info = $var;
			
			//var_dump($var);

			return $var;
		//}
	}
	
	function close_db(){
		mysql_close($this->connect);
		$this->connect = NULL;
	}
	
}

?>