<?php

include_once('classe_connexion.php');
include_once('fonctions.php');
include_once('connexion_vars.php');


class Organisme {
	
	var $news_db		= NULL;
	var $id				= NULL;
	
	/**
	* GESTION DES ORGANISMES
	*
	*
	*/
	function organisme($_id=NULL){
		global $plasma_cInfo;
		$this->news_db		= new connexion($plasma_cInfo['server'],$plasma_cInfo['user'],$plasma_cInfo['password'],$plasma_cInfo['db']);
	}
	
	/**
	* create_organisme creation ou modification d'un organisme
	* @param $_array_val
	* @param $_id
	*/
	function create_organisme($_array_val,$_id=NULL){
		$this->news_db->connect_db();

		if(isset($_id)){
			//MODIFICATION
			$updateSQL 		= sprintf("UPDATE ".TB."organisme_tb SET nom=%s, type=%s, google_analytics_id=%s WHERE id=%s",
													GetSQLValueString($_array_val['nom'],					"text"),
													GetSQLValueString($_array_val['type'],					"text"),
													GetSQLValueString($_array_val['google_analytics_id'],	"text"),
													GetSQLValueString($_id,"int"));
																										
			$update_query	= mysql_query($updateSQL) or die(mysql_error());
			
			
		}else{
			//CREATION
			$insertSQL 		= sprintf("INSERT INTO ".TB."organisme_tb (nom, type, google_analytics_id) VALUES (%s,%s,%s)",
													GetSQLValueString($_array_val['nom'],					"text"),
													GetSQLValueString($_array_val['type'],					"text"),
													GetSQLValueString($_array_val['google_analytics_id'], 	"text"));
			$insert_query	= mysql_query($insertSQL) or die(mysql_error());
			
			$_id = mysql_insert_id();
			

			return $_id;
		}	
	}
	
	/*
	@ creation ou modification d'un groupe utilisateur
	@
	@
	*/
	function create_user_groupe($_array_val,$_id=NULL){
		$this->news_db->connect_db();

		if(isset($_id)){
			//MODIFICATION
			$updateSQL 		= sprintf("UPDATE ".TB."user_groupes_tb SET libelle=%s, type=%s, id_organisme=%s WHERE id=%s",
													GetSQLValueString($_array_val['nom'],					"text"),
													GetSQLValueString($_array_val['type'],					"text"),
													GetSQLValueString($_array_val['id_organisme'],	"text"),
													GetSQLValueString($_id,"int"));
																										
			$update_query	= mysql_query($updateSQL) or die(mysql_error());
			
			
		}else{
			//CREATION
			$insertSQL 		= sprintf("INSERT INTO ".TB."user_groupes_tb (libelle, type, id_organisme) VALUES (%s,%s,%s)",
													GetSQLValueString($_array_val['nom'],					"text"),
													GetSQLValueString($_array_val['type'],					"text"),
													GetSQLValueString($_array_val['id_organisme'], 	"text"));
			$insert_query	= mysql_query($insertSQL) or die(mysql_error());
			
			$_id = mysql_insert_id();
			

			return $_id;
		}	
	}
	
	
	/*
	@ RECUPERE LA LISTE DES ORGANISMES
	@
	@
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
			
			include('../structure/admin-organisme-list-bloc.php');
			
			$i = ($i+1)%2;
			
		}
	}
	
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
	
	
	/*
	@ RECUPERE LA LISTE DES GROUPES D'UTILISATEURS
	@
	@
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
			
			global $typeTab;
			
			include('../structure/admin-user_groupe-list-bloc.php');
			
			$i = ($i+1)%2;
			
		}
	}
	
	/*
	@ SUPPRIME UN ORGANISME
	@
	@
	*/
	function suppr_organisme($id=NULL){
		if(isset($id)){
			$this->news_db->connect_db();

			$supprSQL		= sprintf("DELETE FROM ".TB."organisme_tb WHERE id=%s", GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			
			$sql_user_groupe		= sprintf("SELECT * FROM ".TB."user_groupes_tb WHERE id_organisme=%s", GetSQLValueString($id,'int'));
			$sql_user_groupe_query = mysql_query($sql_user_groupe) or die(mysql_error());

	
			while ($user_groupe_item = mysql_fetch_assoc($sql_user_groupe_query)){
							
				$id					= $user_groupe_item['id'];
				
				$this->suppr_user_groupe($id);
			}
		}
	}
	
	/*
	@ SUPPRIME UN USER_GROUPE
	@
	@
	*/
	function suppr_user_groupe($id=NULL){
		if(isset($id)){
			$this->news_db->connect_db();

			$supprSQL		= sprintf("DELETE FROM ".TB."user_groupes_tb WHERE id=%s", GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			$supprSQL		= sprintf("DELETE FROM ".TB."rel_user_groupe_tb WHERE id_groupe=%s", GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			$supprSQL		= sprintf("DELETE FROM ".TB."rel_user_groupe_groupe_tb WHERE id_groupe=%s", GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			$supprSQL		= sprintf("DELETE FROM ".TB."rel_template_groupe_tb WHERE id_groupe=%s", GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
			$supprSQL		= sprintf("DELETE FROM ".TB."rel_cat_actu_groupe_tb WHERE id_groupe=%s", GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
		}
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

}
	
	

?>