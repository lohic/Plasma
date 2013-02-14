<?php


/*
LDAP :
emile.boutmy
boutmy

EMAIL :
login
password
*/


include_once('../vars/config.php');
include_once('classe_connexion.php');
include_once('classe_fonctions.php');

class Spuser {


	var $connexion;

	var $isAdmin;
	//var $isSuperAdmin;
	var $info;

	var $id;
	var $login;
	var $password;
	var $type;
	var $nom;
	var $prenom;
	var $email;
	var $groupe;
	var $userLevel;

	function spuser($connexion){

		$this->isAdmin		= false;
		//$this->isSuperAdmin = false;

		$this->id			= NULL;
		$this->login		= NULL;
		$this->password		= NULL;
		$this->type			= NULL;
		$this->nom			= NULL;
		$this->prenom		= NULL;
		$this->email		= NULL;
		$this->groupe		= NULL;
		$this->userLevel	= NULL;

		$this->LDAP			= NULL;

		$this->connexion = $connexion;

		/*
		@ on verifie qu'on ne doit pas se déconnecter 
		*/
		if(isset($_POST['logout']) && $_POST['logout']==true){
			$this->logout();
		}
		
		/*
		@ on verifie qu'un login existe
		@ on recrée un objet user à partir des variables de sessions enregistrées 
		*/
		else if(isset($_SESSION['login'])){

			//echo "SESSION OK ".$_SESSION['login'];
			
			$this->id			= $_SESSION['id'];
			$this->login		= $_SESSION['login'];
			$this->type			= $_SESSION['type'];
			$this->nom			= $_SESSION['nom'];
			$this->prenom		= $_SESSION['prenom'];
			$this->email		= $_SESSION['email'];
			$this->groupe		= $_SESSION['groupe'];
			//$this->isSuperAdmin	= $_SESSION['isSuperAdmin'];
			$this->userLevel	= $_SESSION['userLevel'];
			$this->LDAP			= $_SESSION['LDAP'];
			
			if(!$this->isAuthorised()){
				$this->logout();
			}
			
			if($this->userLevel<=8){
				$this->isAdmin	= true;
			}else{
				$this->isAdmin	= false;
			}

		}
		/*
		@ on verifie qu'un login et un mot de passe existent
		@ on recrée un objet user à partir des variables de sessions enregistrées 
		*/
		else if(isset($_POST['login']) && isset($_POST['password'])){

			//echo "POST LOGIN OK";
			
			// on verifie de quel type de compte il s'agit
			$ldapConnected = $this->isLDAP($_POST['login'],$_POST['password']);
				
			if(!$ldapConnected){			
				//$type = $this->isLDAPorMAIL($_POST['login']); 
				$this->check_login($_POST['login'],$_POST['password']);
			}

		}
		
		/*
		@ sinon rien on vérifie bien que la variable admin est false
		*/
		else{
			//echo "USER RIEN";
			$this->isAdmin		= false;
			//$this->isSuperAdmin	= false;
			$this->userLevel	= false;
		}
		
	}
	
	/*
	@ On vérifie avant tout si il s'agit d'un compte LDAP
	@
	@
	*/
	function isLDAP($login=NULL,$password=NULL){
		
		if(IS_LDAP_SERVER){
			$LDAPinfo = $this->connectLDAP($login,$password);
					
			if(!empty($LDAPinfo->email)){
				
				$this->connexion->connect_db();
			
				$login_query	= sprintf("SELECT * FROM ".TB."user_tb WHERE email=%s", func::GetSQLValueString($LDAPinfo->email, "text")); 
			
				$login_info		= mysql_query($login_query) or die(mysql_error());
				$infoUser		= mysql_fetch_assoc($login_info);
				
				if($infoUser['account_type'] == 'ldap'){
									
					$this->id				= $infoUser['id'];
					$this->login			= $LDAPinfo->login;
					$this->type				= $infoUser['type'];
					$this->nom				= $LDAPinfo->nom;
					$this->prenom			= $LDAPinfo->prenom;
					$this->email			= $infoUser['email'];
					$this->groupe			= $infoUser['groupe'];
					$this->userLevel		= $infoUser['type'];
					
					$this->LDAP				= true;
					
					//$this->isSuperAdmin	= $infoUser['type']=='super_admin'?true:false;
					
					//$this->isAdmin	= true;
					if($this->userLevel<=8){
						$this->isAdmin	= true;
					}else{
						$this->isAdmin	= false;
					}
		
					$_SESSION['id'] 		= $this->id;
					$_SESSION['login']		= $this->login;
					$_SESSION['type']		= $this->type;
					$_SESSION['nom']		= $this->nom;
					$_SESSION['prenom'] 	= $this->prenom;
					$_SESSION['email']		= $this->email;
					$_SESSION['groupe']		= $this->groupe;
					$_SESSION['userLevel']	= $this->userLevel;
					
					$_SESSION['LDAP']		= $this->LDAP;
					
					//$_SESSION['isSuperAdmin']	= $this->isSuperAdmin;
					
					return true;
				}else{
					$this->isAdmin		= false;
					//$this->isSuperAdmin = false;
					$this->userLevel	= false;
					return false;
				}
			}
		}else{
			return false;	
		}
	}
		
	/*
	@ fonction de connexion au LDAP	
	@
	@
	*/
	function connectLDAP($login=NULL,$password=NULL){
		// Eléments d'authentification LDAP
		
		
		$retour->info	= NULL;
		$retour->login	= NULL;
		$retour->prenom	= NULL;
		$retour->nom	= NULL;
		$retour->email	= NULL;
		$retour->type	= NULL;
		//$retour->spID	= NULL;
		//$retour->annee	= NULL;
	
		if(isset($login) && isset($password) && $login!="" && $password!=""){
			$login = strtolower($login);
			
			$ldaprdn  = 'uid='.$login.',ou=Users,o=sciences-po,c=fr';
			$ldappass = $password;
	
			
			// Connexion au serveur LDAP
			$ldapconn = ldap_connect("ldap.sciences-po.fr") or die("Impossible de se connecter au serveur LDAP.");
			
			if ($ldapconn) {
				// Authentification au serveur LDAP
				$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
		
				// Vérification de l'authentification
				if ($ldapbind) {
					$retour->info = "ok";
			
					//recuperation des informations
					$sr=ldap_search($ldapconn,"ou=Users, o=sciences-po, c=fr", "uid=".$login);
					$info = ldap_get_entries($ldapconn, $sr);
					
					
					for ($i=0; $i<$info["count"]; $i++) 
					{
						if ( isset($info[$i]["cn"][0]) ){				$retour->login	= $info[$i]["cn"][0]; }
						if ( isset($info[$i]["givenname"][0]) ){		$retour->prenom = $info[$i]["givenname"][0]; }
						if ( isset($info[$i]["sn"][0]) ){				$retour->nom	= $info[$i]["sn"][0]; }
						if ( isset($info[$i]["mail"][0]) ){				$retour->email	= $info[$i]["mail"][0]; }
						if ( isset($info[$i]["employeetype"][0]) ){		$retour->type	= $info[$i]["employeetype"][0]; }
	
					}
										
					//$retour->raw = $info;
					
					ldap_close($ldapconn);
				} else {
					$retour->info = "login_error";
				}
			
			}else{
				$retour->info = "no_connexion";
			}
		}else{
			$retour->info = "no_login";
		}
		return $retour;
	}
	
	/*
	@ Connecte un compte email
	@
	@
	*/
	function check_login($login=NULL,$password=NULL){
		//$this->connexion->connect_db();
		
		$login__query	= sprintf("SELECT * FROM ".TB."user_tb WHERE login=%s AND password=%s",
																func::GetSQLValueString($login, "text"),
																func::GetSQLValueString($password, "text")); 
	
		$login_info		= mysql_query($login__query) or die(mysql_error());
		$infoUser		= mysql_fetch_assoc($login_info);
		$loginuser		= mysql_num_rows($login_info);

		//echo "INFO USER  : ".$infoUser['login'];	

		if($loginuser){
			
			//echo "INFO USER 2  : ".$infoUser['login'];

			$this->id				= $infoUser['id'];
			$this->login			= $infoUser['login'];
			$this->password			= $infoUser['password'];
			$this->type				= $infoUser['type'];
			$this->nom				= $infoUser['nom'];
			$this->prenom			= $infoUser['prenom'];
			$this->email			= $infoUser['email'];
			$this->groupe			= $infoUser['groupe'];
			$this->userLevel		= $infoUser['type'];
			
			$this->LDAP				= false;
			
			
			//$this->isSuperAdmin	= $infoUser['type']=='super_admin'?true:false;


			$_SESSION['id'] 		= $this->id;
			$_SESSION['login']		= $this->login;
			$_SESSION['type']		= $this->type;
			$_SESSION['nom']		= $this->nom;
			$_SESSION['prenom'] 	= $this->prenom;
			$_SESSION['email']		= $this->email;
			$_SESSION['groupe']		= $this->groupe;
			$_SESSION['userLevel']	= $this->userLevel;
					
			$_SESSION['LDAP']		= $this->LDAP;
			
			//$_SESSION['isSuperAdmin']	= $this->isSuperAdmin;

			//echo "SESSION USER 3  : ".$_SESSION['login'];

			//$this->isAdmin	= true;
			if($this->userLevel<=8){
				$this->isAdmin	= true;
			}else{
				$this->isAdmin	= false;
			}
		}else{
			$this->isAdmin	= false;
		}
	}

	/*
	@ LOGOUT DE L'ADMINISTRATION
	@
	@
	*/
	function logout(){
		$this->id			= NULL;
		$this->login		= NULL;
		$this->type			= NULL;
		$this->nom			= NULL;
		$this->prenom		= NULL;
		$this->email		= NULL;
		$this->groupe		= NULL;
		$this->userLevel	= NULL;
		$this->LDAP			= NULL;

		$this->isAdmin	= false;
		//$this->isSuperAdmin	= false;
		
		$_SESSION = array();
		session_unset();
		session_destroy();
	}

	/*
	@ RECUPERATION DES INFORMATIONS D'UN UTILISATEUR
	@
	@
	*/
	function get_user_info(){
		if($this->isAdmin){
			$retour = NULL;

			// instanciation de l'objet
			$retour = (object)array();

			$retour->id				= $this->id;
			$retour->login			= $this->login;
			$retour->password		= $this->password;
			$retour->type			= $this->type;
			$retour->nom			= $this->nom;
			$retour->prenom			= $this->prenom;
			$retour->email			= $this->email;
			$retour->groupe			= $this->groupe;
			$retour->userLevel		= $this->userLevel;
			$retour->isAdmin		= $this->isAdmin;
			$retour->groups			= $this->get_groups();
			$retour->LDAP			= $this->LDAP;
			//$retour->isSuperAdmin	= $this->isSuperAdmin;
			
			return $retour;
		}else{
			return false;
		}
	}
	
	/*
	@ MISE A JOUR D'UN UTILISATEUR
	@
	@
	*/
	function modify_user($_array_val=NULL){
		if($this->isAdmin){
			
			$this->connexion->connect_db();

			$updateSQL 		= sprintf("UPDATE ".TB."user_tb	SET login=%s,
															password=%s,
															type=%s,
															nom=%s,
															prenom=%s,
															email=%s,
															account_type=%s
															WHERE id=%s",
													func::GetSQLValueString($_array_val['login'], "text"),
									   				func::GetSQLValueString($_array_val['password'], "text"),
									   				func::GetSQLValueString($_array_val['type'], "text"),
									   				func::GetSQLValueString($_array_val['nom'], "text"),
									   				func::GetSQLValueString($_array_val['prenom'], "text"),
									   				func::GetSQLValueString($_array_val['email'], "text"),
									   				func::GetSQLValueString($_array_val['account_type'], "text"),
									   				func::GetSQLValueString($_array_val['id'], "int"));
			$insert_query	= mysql_query($updateSQL) or die(mysql_error());
		
		
			$this->add_groupe_user($_array_val['id'],$_array_val['groupe_user']);
						
		}
	}
	
	/*
	@ CREE D'UN UTILISATEUR
	@
	@
	*/
	function add_user($_array_val=NULL){
		if($this->isAdmin){
					
			$this->connexion->connect_db();
			$updateSQL 		= sprintf("INSERT INTO ".TB."user_tb	(login,password,type,nom,prenom,email,account_type) VALUES (%s,%s,%s,%s,%s,%s,%s)",
													func::GetSQLValueString($_array_val['login'], "text"),
									   				func::GetSQLValueString($_array_val['password'], "text"),
									   				func::GetSQLValueString($_array_val['type'], "text"),
									   				func::GetSQLValueString($_array_val['nom'], "text"),
									   				func::GetSQLValueString($_array_val['prenom'], "text"),
									   				func::GetSQLValueString($_array_val['email'], "text"),
									   				func::GetSQLValueString($_array_val['account_type'], "text"));
			$insert_query	= mysql_query($updateSQL) or die(mysql_error());
			
			$id_user = mysql_insert_id();
		
			$this->add_groupe_user($id_user,$_array_val['groupe_user']);
			
		}
	}
	
	/*
	@ edition de destinataire
	@
	@
	*/
	function add_groupe_user($_id_user=NULL, $_array_groupe=NULL){
		if(!empty($_id_user) && !empty($_array_groupe)){
			$this->clean_groupe($_id_user);
			
			
			$this->connexion->connect_db();
			foreach($_array_groupe as $_id_groupe){
				$insertSQL 		= sprintf("INSERT INTO ".TB."rel_user_groupe_tb (id_user,id_groupe) VALUES (%s,%s)",
														func::GetSQLValueString($_id_user, "int"),
														func::GetSQLValueString($_id_groupe, "int"));
				$insert_query	= mysql_query($insertSQL) or die(mysql_error());
			}
		}
	}
	
	/*
	@ supprime toutes les liaisons entre un utilisateur et un groupe
	@
	@
	*/
	function clean_groupe($_id_user=NULL){	
		if(!empty($_id_user)){
			$this->connexion->connect_db();

			$supprSQL		= sprintf("DELETE FROM ".TB."rel_user_groupe_tb WHERE id_user=%s", func::GetSQLValueString($_id_user,'int'));
			
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			
		}
	}
	
	/*
	@ supprime un compte utilisateur et ses liaisons aux groupes
	@
	@
	*/
	function suppr_user($_id_user=NULL){	
		if(!empty($_id_user)){
			$this->connexion->connect_db();
			
			$this->clean_groupe($_id_user);
			$supprSQL		= sprintf("DELETE FROM ".TB."user_tb WHERE id=%s", func::GetSQLValueString($_id_user,'int'));
			
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			
		}
	}
	
	
	
	/*
	@ TYPE DE VARIABLES MYSQL ATTENDUES
	@
	@
	*/
	function get_post_value($tab){

		$titre_item_attendu = array('password',	'type',	'nom',	'prenom',	'email',	'groupe',	'account_type');
		$type_item_attendu 	= array('text',		'text',	'text',	'text',		'text',		'text',		'text');
		$nbr_colonnes		= count($titre_item_attendu);	

		foreach($tab as $key => $value){
			for($i=0;$i<$nbr_colonnes;$i++){
				if($key==$titre_item_attendu[$i]){
					$tab[$key] = func::GetSQLValueString($value,$type_item_attendu[$i]);
					break;
				}
			}
		}
		
		return $tab;
	}

	/*
	@ RETOURNE LA LISTE DES GROUPES D'UTILISATEUR
	@
	@
	*/
	function get_groups(){		
		$query	= sprintf("SELECT g.id as id, g.libelle AS libelle, g.type AS type, g.id_organisme AS id_organisme
							FROM ".TB."user_groupes_tb g, ".TB."user_tb u, ".TB."rel_user_groupe_tb r
							WHERE r.id_user = u.id
							AND r.id_groupe = g.id
							AND u.id = %s", func::GetSQLValueString($this->id, "int")); 
	
		$result	= mysql_query($query) or die(mysql_error());
		
		$groupOrga	= array();
		$group		= array();			
		//echo 'a : '.$this->userLevel;
			
		while ($info = mysql_fetch_assoc($result)){
			$group[$info['id']] = $info['libelle'];
			$groupOrga[] = $info['id_organisme'];
			//if($info['type'] == 'super_admin') $this->isSuperAdmin = true;
			
			
			//echo $info['type'];
			//if(intval($info['type']) < intval($this->userLevel)){
			//	$this->userLevel = $info['type'];
			//}
		}
		
		//echo ' b : '.$this->userLevel;
		
		/*$queryOrga = 'SELECT type FROM organisme_tb WHERE id IN ('.implode(',',$groupOrga).')';
		$result	= mysql_query($queryOrga) or die(mysql_error());
		
		while ($info = mysql_fetch_assoc($result)){
			//if($info['type'] == 'super_admin') $this->isSuperAdmin = true;
			
			//echo $info['type'];
			if(intval($info['type']) < intval($this->userLevel)){
				$this->userLevel = $info['type'];
			}
		}*/
		
		//echo ' c : '.$this->userLevel;
		
		return $group;
	}
	
	/*
	@ RETOURNE LA LISTE DES UTILISATEURS POUR LES MODIFIER
	@
	@
	*/
	function get_user_list(){
		$sql_liste_user = 'SELECT * FROM '.TB.'user_tb ORDER BY account_type DESC, nom';
		$sql_liste_user_query = mysql_query($sql_liste_user) or die(mysql_error());

		$users = array();
		
		$i = 0;

		while ($user = mysql_fetch_assoc($sql_liste_user_query)){
			
			$class 			= 'listItemRubrique'.($i+1);
			$id				= $user['id'];
			$login			= $user['login'];
			$password		= $user['password'];
			$type			= $user['type'];
			$nom			= $user['nom'];
			$prenom			= $user['prenom'];
			$email			= $user['email'];
			$levelTab		= $this->get_admin_level();
			$account_type	= $user['account_type'];
			$groupes		= $this->get_groupe($id,$id);
			
			global $typeTab;
			global $accountTypeTab;
			
			include('../structure/admin-comptes-list-bloc.php');
			
			$i = ($i+1)%2;
		}
	}
	
	/*
	@ RECUPERATION DES GROUPES DE CONTACT
	@
	@
	*/
	function get_groupe($_id=NULL,$id_user=NULL){
		
		$sql_liste_groupe	= 'SELECT * FROM '.TB.'user_groupes_tb ORDER BY libelle';
		$sql_liste_groupe_query = mysql_query($sql_liste_groupe) or die(mysql_error());
		
	
			
		

		while ($groupe = mysql_fetch_assoc($sql_liste_groupe_query)){

			// instanciation
			$temp = (object)array();
			
			$temp->label	= $groupe['libelle'];
			$temp->select	= '';
			$temp->value	= $groupe['id'];
			$temp->classe	= 'inline';
			
			if(isset($id_user)){
				$sql_liste_user	= "SELECT * FROM ".TB."rel_user_groupe_tb WHERE id_groupe=".$groupe['id'];
				$sql_liste_user_query = mysql_query($sql_liste_user) or die(mysql_error());
				
				while ($user = mysql_fetch_assoc($sql_liste_user_query)){
					if($user['id_user']==$id_user){
						$temp->select	= 'ok';
					}
				}
			}
		
			$groupes[]	= $temp;
			$temp = NULL;
		}

		if($_id){
			$id ='user_'.$_id;
		}else{
			$id = 'user';	
		}
		
		if(isset($groupes))
			return func::createCheckBox($groupes,'groupe_user[]',$id);
	}


	/*
	@ RECUPERATION DU NIVEAU D'ADMINISTRATION
	@
	@
	*/
	function get_admin_level(){
		$sql_liste_level	= 'SELECT * FROM '.TB.'user_level_tb ORDER BY level';
		$sql_liste_level_query = mysql_query($sql_liste_level) or die(mysql_error());
		
		$retour = array();
		
		while ($level = mysql_fetch_assoc($sql_liste_level_query)){
			$retour[ $level['level']]	= $level['libelle'];
		}
		
		return $retour;
	}
	
	
	/*
	@ TESTE SI UTILISATEUR A BIEN LE DROIT D'ETRE SUR LA PLATEFORME 
	@ ET RECUPERE SON NIVEAU D'ADMINISTRATEUR LE CAS ÉCHÉANT
	@
	*/
	function isAuthorised(){
		
		if($this->LDAP){
			$query	= sprintf("SELECT COUNT(email) AS nbr
								FROM ".TB."user_tb
								WHERE email= %s", func::GetSQLValueString($this->email, "text")); 
		}else{
			$query	= sprintf("SELECT COUNT(login) AS nbr
								FROM ".TB."user_tb
								WHERE login= %s", func::GetSQLValueString($this->login, "text")); 	
		}
	
		$result	= mysql_query($query) or die(mysql_error());
		$info	= mysql_fetch_assoc($result);
		
		
		if($info['nbr']>0){		
			return true;
		}else{
			return false;
		}
	}
}

?>