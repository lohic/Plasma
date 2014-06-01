<?php

// CONFIG SAMPLE


/**
* variables de connexion
*/
$connexion_info['server'] 		= 'localhost';
$connexion_info['user'] 		= 'root';
$connexion_info['password'] 	= 'z6po';
$connexion_info['db']		 	= 'sciences_po_plasma_db';


/**
* variables du système de fichier
*/
define ('SUB_FOLDER',			'Site_SCIENCESPO_PLASMA/');
define ('ABSOLUTE_URL',			'http://localhost:8888/'.SUB_FOLDER);
define ('LOCAL_PATH',			getcwd().'/../');
define ('REAL_LOCAL_PATH',		realpath( dirname(__FILE__).'/../').'/' );

define ('IS_LDAP_SERVER',		false);
define ('IS_MAIL_LOGIN',		false);

define ('SLIDE_TEMPLATE_FOLDER','slides_templates/');
define ('ACTU_MEDIA_FOLDER',	'actu_medias/');
define ('SLIDESHOW_FOLDER',		'slideshow/');
define ('IMG_SLIDES',			'slides_images/');

define ('TB',					'sp_plasma_');

define ('EVENEMENT_DATA_URL',	'http://www.sciencespo.fr/evenements/api/');

define ('METEO_DATA_URL',		'vars/meteo_json.txt');

define ('GMTcorrection',		0);

define ('DEBUG',				false);