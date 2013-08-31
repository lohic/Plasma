<?php
include(REAL_LOCAL_PATH."/classe/classe_meteo.php");

$meteo = new meteo(); // normalement on doit injecter un zipcode dans meteo()... j'ai prévu Paris par défaut.

//$json_injector = $meteo->get_meteo(); ?>

<!--<script language="javascript" type="text/javascript">
json_data = <?php //echo $json_injector; ?>;
main_zip = "<?php //echo $meteo->zipcode; ?>";
</script>-->

<div id="meteo1" class="meteo1">
	<div id="ombre"></div>
	<div class="colonne_gauche">
		<div class="header">
			<h1>Lundi 5 septembre 2011</h1>
			<div class="ville"><h2>Météo<br/><span>...</span></h2></div>
			<div class="picto_principal"></div>
		</div>
		
		<div class="detail texte">
			<div class="thermometre">

			</div>
			<div class="temperature">
				<p><span>16</span>°</p>	
			</div>
			<div class="temperature_min">
				<p>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p>max:<span>18</span>°</p>	
			</div>
			<div class="vent">

			</div>
		</div>
	</div>

	<div class="footer">
		<div class="autre_jour forecast1">
			<div class="jour">
				<p>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_jour forecast2">
			<div class="jour">
				<p>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_jour forecast3">
			<div class="jour">
				<p>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_jour forecast4">
			<div class="jour">
				<p>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_jour forecast5">
			<div class="jour">
				<p>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_jour forecast6">
			<div class="jour">
				<p>Mardi 6</p>	
			</div>
			<div class="temperature_min">
				<p>min:<span>8</span>°</p>	
			</div>
			<div class="temperature_max">
				<p>max:<span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

	</div>
	
</div>







<div id="meteo2" class="meteo2">
	<div id="ombre2"></div>
	<div class="colonne_gauche">
		<div class="header">
			<h1>Dans les autres campus</h1>
			<div class="ville"><h2>Météo</h2></div>
		</div>
	</div>

	<div class="footer">
		<div class="autre_campus">
			<div class="nom_ville">
				<p>Dijon</p>	
			</div>
			<div class="temperature">
				<p><span>20</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_campus">
			<div class="nom_ville">
				<p>Le Havre</p>	
			</div>
			<div class="temperature">
				<p><span>22</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_campus">
			<div class="nom_ville">
				<p>Menton</p>	
			</div>
			<div class="temperature">
				<p><span>25</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_campus">
			<div class="nom_ville">
				<p>Nancy</p>	
			</div>
			<div class="temperature">
				<p><span>18</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_campus">
			<div class="nom_ville">
				<p>Poitiers</p>	
			</div>
			<div class="temperature">
				<p><span>22</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>

		<div class="autre_campus">
			<div class="nom_ville">
				<p>Reims</p>	
			</div>
			<div class="temperature">
				<p><span>23</span>°</p>	
			</div>
			<div class="temps">

			</div>	
		</div>
	</div>
	
</div>