<?php

include_once('../vars/config.php');
include_once(REAL_LOCAL_PATH.'classe/classe_connexion.php');
include_once(REAL_LOCAL_PATH.'classe/classe_fonctions.php');


/**
 * 
 */
class Organisme {
	
	var $news_db		= NULL;
	var $id				= NULL;
	
	/**
	 * GESTION DES ORGANISMES
	 * @param  [type] $_id [description]
	 * @return [type]      [description]
	 */
	function organisme($_id=NULL){
		global $connexion_info;
		$this->news_db		= new connexion($connexion_info['server'],$connexion_info['user'],$connexion_info['password'],$connexion_info['db']);
	}
	

	/**
	 * create_organisme creation ou modification d'un organisme
	 * @param type $_array_val 
	 * @param type $_id 
	 * @return type
	 */
	function create_organisme($_array_val,$_id=NULL){
		$this->news_db->connect_db();

		if(isset($_id)){
			//MODIFICATION
			$updateSQL 		= sprintf("UPDATE ".TB."organisme_tb SET nom=%s, type=%s, google_analytics_id=%s WHERE id=%s",
													func::GetSQLValueString($_array_val['nom'],					"text"),
													func::GetSQLValueString($_array_val['type'],				"text"),
													func::GetSQLValueString($_array_val['google_analytics_id'],	"text"),
													func::GetSQLValueString($_id,"int"));
																										
			$update_query	= mysql_query($updateSQL) or die(mysql_error());
			
			
		}else{
			//CREATION
			$insertSQL 		= sprintf("INSERT INTO ".TB."organisme_tb (nom, type, google_analytics_id) VALUES (%s,%s,%s)",
													func::GetSQLValueString($_array_val['nom'],					"text"),
													func::GetSQLValueString($_array_val['type'],					"text"),
													func::GetSQLValueString($_array_val['google_analytics_id'], 	"text"));
			$insert_query	= mysql_query($insertSQL) or die(mysql_error());
			
			$_id = mysql_insert_id();
			

			return $_id;
		}	
	}
	
	/**
	 * creation ou modification d'un groupe utilisateur
	 * @param  [type] $_array_val [description]
	 * @param  [type] $_id        [description]
	 * @return [type]             [description]
	 */
	function create_user_groupe($_array_val,$_id=NULL){
		$this->news_db->connect_db();

		if(isset($_id)){
			//MODIFICATION
			$updateSQL 		= sprintf("UPDATE ".TB."user_groupes_tb SET libelle=%s, type=%s, id_organisme=%s WHERE id=%s",
													func::GetSQLValueString($_array_val['nom'],					"text"),
													func::GetSQLValueString($_array_val['type'],					"text"),
													func::GetSQLValueString($_array_val['id_organisme'],	"text"),
													func::GetSQLValueString($_id,"int"));
																										
			$update_query	= mysql_query($updateSQL) or die(mysql_error());
			
			$this->add_groupe_plasma($_id, $_array_val['groupe_plasma']);
			
		}else{
			//CREATION
			$insertSQL 		= sprintf("INSERT INTO ".TB."user_groupes_tb (libelle, type, id_organisme) VALUES (%s,%s,%s)",
													func::GetSQLValueString($_array_val['nom'],					"text"),
													func::GetSQLValueString($_array_val['type'],					"text"),
													func::GetSQLValueString($_array_val['id_organisme'], 	"text"));
			$insert_query	= mysql_query($insertSQL) or die(mysql_error());
			
			$_id = mysql_insert_id();
			

			return $_id;
		}	
	}
	
	
	/**
	 * RECUPERE LA LISTE DES ORGANISMES
	 * @return [type] [description]
	 */
	function get_organisme_edit_liste(){
		$this->news_db->connect_db();

		$sql_organisme		= sprintf('SELECT * FROM '.TB.'organisme_tb');
		$sql_organisme_query = mysql_query($sql_organisme) or die(mysql_error());
		
		$i = 0;

		while ($organisme_item = mysql_fetch_assoc($sql_organisme_query)){
						
			$class				= 'listItemRubrique'.($i+1);
			$id					= $organisme_item['id'];
			$nom				= $organisme_item['nom'];
			$type				= $organisme_item['type'];
			$google_analytics_id= $organisme_item['google_analytics_id'];
			$user_level			= $this->get_admin_level();
	
			global $typeTab;
			
			include(REAL_LOCAL_PATH.'structure/admin-organisme-list-bloc.php');
			
			$i = ($i+1)%2;
			
		}
	}
	

	/**
	 * [get_organisme_liste description]
	 * @return [type] [description]
	 */
	function get_organisme_liste(){
		$this->news_db->connect_db();

		$sql_organisme		= sprintf('SELECT * FROM '.TB.'organisme_tb');
		$sql_organisme_query = mysql_query($sql_organisme) or die(mysql_error());
	
		
		$liste = array();

		while ($organisme_item = mysql_fetch_assoc($sql_organisme_query)){
						
			$id					= $organisme_item['id'];
			$nom				= $organisme_item['nom'];
	
			$liste[$id] = $nom;
			
		}
		
		return $liste;
	}
	
	
	/**
	 * RECUPERE LA LISTE DES GROUPES D'UTILISATEURS
	 * @return [type] [description]
	 */
	function get_user_groupe_edit_liste(){
		$this->news_db->connect_db();

		$sql_user_groupe		= sprintf('SELECT * FROM '.TB.'user_groupes_tb');
		$sql_user_groupe_query = mysql_query($sql_user_groupe) or die(mysql_error());
		
		$i = 0;

		while ($user_groupe_item = mysql_fetch_assoc($sql_user_groupe_query)){
						
			$class				= 'listItemRubrique'.($i+1);
			$id					= $user_groupe_item['id'];
			$nom				= $user_groupe_item['libelle'];
			$type				= $user_groupe_item['type'];
			$id_organisme		= $user_groupe_item['id_organisme'];
			$organismes			= $this->get_organisme_liste();
			$user_level			= $this->get_admin_level();

			$groupe_plasma		= $this->get_groupe_ecrans($user_groupe_item['id'],$user_groupe_item['id']);
			
			global $typeTab;
			
			include(REAL_LOCAL_PATH.'structure/admin-user_groupe-list-bloc.php');
			
			$i = ($i+1)%2;
			
		}
	}
	
	/**
	 * SUPPRIME UN ORGANISME
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function suppr_organisme($id=NULL){
		if(isset($id)){
			$this->news_db->connect_db();

			$supprSQL				= sprintf("DELETE FROM ".TB."organisme_tb WHERE id=%s", func::GetSQLValueString($id,'int'));
			$suppr_query			= mysql_query($supprSQL) or die(mysql_error());
			
			$sql_user_groupe		= sprintf("SELECT * FROM ".TB."user_groupes_tb WHERE id_organisme=%s", func::GetSQLValueString($id,'int'));
			$sql_user_groupe_query  = mysql_query($sql_user_groupe) or die(mysql_error());

			while ($user_groupe_item = mysql_fetch_assoc($sql_user_groupe_query)){
							
				$id	 = $user_groupe_item['id'];
				$this->suppr_user_groupe($id);

			}
		}
	}
	
	/**
	 * SUPPRIME UN USER_GROUPE
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function suppr_user_groupe($id=NULL){
		if(isset($id)){
			$this->news_db->connect_db();

			$supprSQL		= sprintf("DELETE FROM ".TB."user_groupes_tb WHERE id=%s", func::GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			$supprSQL		= sprintf("DELETE FROM ".TB."rel_user_groupe_tb WHERE id_groupe=%s", func::GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			$supprSQL		= sprintf("DELETE FROM ".TB."rel_user_groupe_groupe_tb WHERE id_groupe=%s", func::GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			$supprSQL		= sprintf("DELETE FROM ".TB."rel_template_groupe_tb WHERE id_groupe=%s", func::GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			$supprSQL		= sprintf("DELETE FROM ".TB."rel_cat_actu_groupe_tb WHERE id_groupe=%s", func::GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
		}
	}
	
	
	/**
	 * RECUPERATION DU NIVEAU D'ADMINISTRATION
	 * @return [type] [description]
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

	/**	
	 * RECUPERATION DES GROUPES DE CONTACT
	 * @param  [type] $id_user_groupe [description]
	 * @return [type]          [description]
	 */
	function get_groupe_ecrans($_id=NULL,$id_user_groupe=NULL){
		
		$sql_liste_groupe	=  'SELECT G.id AS id, G.nom AS nom, E.ville AS ville
								FROM '.TB.'ecrans_groupes_tb AS G, '.TB.'etablissements_tb AS E
								WHERE G.id_etablissement = E.id
								ORDER BY E.ville, G.nom';
		$sql_liste_groupe_query = mysql_query($sql_liste_groupe) or die(mysql_error());
		
		$groupes = array();
	
		while ($groupe = mysql_fetch_assoc($sql_liste_groupe_query)){

			// instanciation
			$temp = (object)array();
			
			$temp->label	= '<strong>'.$groupe['ville'].':</strong> '.$groupe['nom'];
			$temp->select	= '';
			$temp->value	= $groupe['id'];
			$temp->classe	= 'inline';
			

			if(isset($id_user_groupe)){
				$sql_liste_plasma	= "SELECT * FROM ".TB."rel_ecrans_groupe_user_groupe_tb WHERE id_user_groupe=".$id_user_groupe;
				$sql_liste_plasma_query = mysql_query($sql_liste_plasma) or die(mysql_error());
				
				while ($plasma = mysql_fetch_assoc($sql_liste_plasma_query)){
					if($plasma['id_ecrans_groupe']==$groupe['id']){
						$temp->select	= 'ok';
					}
				}
			}
		
			$groupes[]	= $temp;
			$temp 		= NULL;
		}

		if($_id){
			$id ='user_groupe_'.$_id;
		}else{
			$id = 'user_groupe';	
		}
		
		if(isset($groupes))
			return func::createCheckBox($groupes,'groupe_plasma[]',$id);
	}

	/**
	 * ajout des groupes d'écrans
	 * @param [type] $_id_user      [description]
	 * @param [type] $_array_groupe [description]
	 */
	function add_groupe_plasma($_id_groupe=NULL, $_array_plasma=NULL){

		if(!empty($_id_groupe) && !empty($_array_plasma)){

			$this->clean_groupe_plasma($_id_groupe);
			
			foreach($_array_plasma as $_id_groupe_plasma){
				$insertSQL 		= sprintf("INSERT INTO ".TB."rel_ecrans_groupe_user_groupe_tb (id_user_groupe,id_ecrans_groupe) VALUES (%s,%s)",
														func::GetSQLValueString($_id_groupe, "int"),
														func::GetSQLValueString($_id_groupe_plasma, "int"));
				$insert_query	= mysql_query($insertSQL) or die(mysql_error());
			}
		}
	}
	
	/**
	 * supprime toutes les liaisons entre un groupe d'utilisateur et des groupes d'écrans
	 * @param  [type] $_id_user [description]
	 * @return [type]           [description]
	 */
	function clean_groupe_plasma($_id_groupe=NULL){	

		if(!empty($_id_groupe)){

			$supprSQL		= sprintf("DELETE FROM ".TB."rel_ecrans_groupe_user_groupe_tb WHERE id_user_groupe=%s", func::GetSQLValueString($_id_groupe,'int'));
			
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
		}
	}

}