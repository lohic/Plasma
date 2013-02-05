<?php

include_once('../vars/config.php');
include_once('classe_connexion.php');
include_once('classe_fonctions.php');
//include_once('fonctions.php');
//include_once('connexion_vars.php');


class Etablissement {
	
	var $news_db		= NULL;
	var $id				= NULL;
	
	/**
	* GESTION DES ORGANISMES
	*
	*
	*/
	function etablissement($_id=NULL){
		global $connexion_info;
		$this->news_db		= new connexion($connexion_info['server'],$connexion_info['user'],$connexion_info['password'],$connexion_info['db']);
		
		$this->updater();
		
	}
	
	/**
	* create_etablissement creation ou modification d'un organisme
	* @param $_array_val
	* @param $_id
	*/
	function create_etablissement($_array_val,$_id=NULL){
		$this->news_db->connect_db();

		if(isset($_id)){
			//MODIFICATION
			$updateSQL 		= sprintf("UPDATE ".TB."etablissements_tb SET nom=%s, ville=%s, code_meteo=%s, code_postal=%s WHERE id=%s",
																		func::GetSQLValueString($_array_val['nom'],			"text"),
																		func::GetSQLValueString($_array_val['ville'],		"text"),
																		func::GetSQLValueString($_array_val['code_meteo'],	"text"),
																		func::GetSQLValueString($_array_val['code_postal'],	"int"),
																		func::GetSQLValueString($_id,"int"));
																										
			$update_query	= mysql_query($updateSQL) or die(mysql_error());
			
			
		}else{
			//CREATION
			$insertSQL 		= sprintf("INSERT INTO ".TB."etablissements_tb (nom, ville, code_meteo, code_postal) VALUES (%s,%s,%s,%s)",
																		func::GetSQLValueString($_array_val['nom'],			"text"),
																		func::GetSQLValueString($_array_val['ville'],		"text"),
																		func::GetSQLValueString($_array_val['code_meteo'],	"text"),
																		func::GetSQLValueString($_array_val['code_postal'],	"int"));
			$insert_query	= mysql_query($insertSQL) or die(mysql_error());
			
			$_id = mysql_insert_id();
			

			return $_id;
		}	
	}
	
	/**
	* execution des fonctions de mise à jour
	*/
	private function updater(){
		
		if(isset($_POST['suppr_etablissement']) && $_POST['suppr_etablissement'] == 'ok'){
			
			$this->suppr_etablissement($_POST['id_suppr_etablissement']);	
		}
		
		
		if(isset($_POST['create_etablissement']) && $_POST['create_etablissement'] == 'ok'){
			$val['nom']			= $_POST['nom'];
			$val['ville']		= $_POST['ville'];
			$val['code_meteo']	= $_POST['code_meteo'];
			$val['code_postal']	= $_POST['code_postal'];
			
		
			$this->create_etablissement($val);	
		}
		
		
		if(isset($_POST['modif_etablissement']) && $_POST['modif_etablissement'] == 'ok'){
			$val['id']			= $_POST['id'];
			$val['nom']			= $_POST['nom'];
			$val['ville']		= $_POST['ville'];
			$val['code_meteo']	= $_POST['code_meteo'];
			$val['code_postal']	= $_POST['code_postal'];
			
		
			$this->create_etablissement($val,$val['id']);	
		}
	}

	
	
	/*
	@ RECUPERE LA LISTE DES ORGANISMES
	@
	@
	*/
	function get_etablissement_edit_liste(){
		$this->news_db->connect_db();

		$sql_etablissement		= sprintf('SELECT * FROM '.TB.'etablissements_tb');
		$sql_etablissement_query = mysql_query($sql_etablissement) or die(mysql_error());
		
		$i = 0;

		while ($etablissement_item = mysql_fetch_assoc($sql_etablissement_query)){
						
			$class			= 'listItemRubrique'.($i+1);
			$id				= $etablissement_item['id'];
			$nom			= $etablissement_item['nom'];
			$ville			= $etablissement_item['ville'];
			$code_meteo		= $etablissement_item['code_meteo'];
			$code_postal	= $etablissement_item['code_postal'];
				
			include('../structure/etablissement-list-bloc.php');
			
			$i = ($i+1)%2;
			
		}
	}
	
	function get_etablissement_liste(){
		$this->news_db->connect_db();

		$sql_etablissement		= sprintf('SELECT * FROM '.TB.'etablissements_tb');
		$sql_etablissement_query = mysql_query($sql_etablissement) or die(mysql_error());
	
		
		$liste = array();

		while ($etablissement_item = mysql_fetch_assoc($sql_etablissement_query)){
						
			$id					= $etablissement_item['id'];
			$nom				= $etablissement_item['nom'];
	
			$liste[$id] = $nom;
			
		}
		
		return $liste;
	}
	
	
	
	
	/**
	* SUPPRIME UN ETABLISSEMENT
	* @param $id
	*/
	function suppr_etablissement($id=NULL){
		if(isset($id)){
			$this->news_db->connect_db();

			$supprSQL		= sprintf("DELETE FROM ".TB."etablissements_tb WHERE id=%s", func::GetSQLValueString($id,'int'));
			$suppr_query	= mysql_query($supprSQL) or die(mysql_error());
			
		}
	}

}
	
	

?>