<?php
include(REAL_LOCAL_PATH."/classe/classe_meteo.php");

$meteo = new meteo(); // normalement on doit injecter un zipcode dans meteo()... j'ai prévu Paris par défaut.

//$json_injector = $meteo->get_meteo(); ?>


<div id="meteo1" class="meteo1">
	<div id="ombre" class="ombre"></div>
	<div class="colonne_gauche">
		<div class="header">
			<h1 style='display:none'>Lundi 5 septembre 2011</h1>
			<div class="ville"><h2 style='display:none'>Météo<br/><span>...</span></h2></div>
			<div class="picto_principal"></div>
		</div>
		
		<div class="detail texte">
			<div class="thermometre">

			</div>
			<div class="temperature">
				<p style='display:none'><span>16</span>°</p>	
			</div>
			<div class="temperature_min">
				<p style='display:none'>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p style='display:none'>max:<span>18</span>°</p>	
			</div>
			<div class="vent"></div>
		</div>
	</div>

	<div class="footer">
		<div class="autre_jour forecast1">
			<div class="jour">
				<p style='display:none'>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p style='display:none'>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p style='display:none'>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_jour forecast2">
			<div class="jour">
				<p style='display:none'>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p style='display:none'>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p style='display:none'>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_jour forecast3">
			<div class="jour">
				<p style='display:none'>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p style='display:none'>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p style='display:none'>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_jour forecast4">
			<div class="jour">
				<p style='display:none'>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p style='display:none'>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p style='display:none'>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_jour forecast5">
			<div class="jour">
				<p style='display:none'>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p style='display:none'>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p style='display:none'>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_jour forecast6">
			<div class="jour">
				<p style='display:none'>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p style='display:none'>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p style='display:none'>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

	</div>
	
</div>



<div id="meteo2" class="meteo2">
	<div id="ombre2" class="ombre"></div>
	<div class="colonne_gauche">
		<div class="header">
			<h1 style='display:none'>Dans les autres campus</h1>
			<div class="ville"><h2 style='display:none'>Météo</h2></div>
		</div>
	</div>

	<div class="footer">
		<div class="autre_campus">
			<div class="nom_ville">
				<p style='display:none'>Dijon</p>	
			</div>
			<div class="temperature">
				<p style='display:none'><span>20</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_campus">
			<div class="nom_ville">
				<p style='display:none'>Le Havre</p>	
			</div>
			<div class="temperature">
				<p style='display:none'><span>22</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_campus">
			<div class="nom_ville">
				<p style='display:none'>Menton</p>	
			</div>
			<div class="temperature">
				<p style='display:none'><span>25</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_campus">
			<div class="nom_ville">
				<p style='display:none'>Nancy</p>	
			</div>
			<div class="temperature">
				<p style='display:none'><span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_campus">
			<div class="nom_ville">
				<p style='display:none'>Poitiers</p>	
			</div>
			<div class="temperature">
				<p style='display:none'><span>22</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_campus">
			<div class="nom_ville">
				<p style='display:none'>Reims</p>	
			</div>
			<div class="temperature">
				<p style='display:none'><span>23</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>
	</div>
	
</div>