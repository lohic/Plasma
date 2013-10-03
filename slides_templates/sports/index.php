	<div id="ombre">

	</div>
    <div class="colonne_gauche"> 
		<div class="header">
			<h1>Sports</h1>
		</div>
		
		<div class="sport">
			<h2>{{type_sport}}</h2>
			{{#icone_sport}}
			<div class="le_sport" style="background-image:url(<?php echo ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.'sports/pictos/'; ?>{{icone_sport}})"></div>
			{{/icone_sport}}
		</div>

		<div class="texte">
			<h3>{{titre}}</h3>
		</div>

		<div class="texte2">
			<h3>{{participant}}</h3>
		</div>

		<div class="endbloc"></div>
	</div>


	<div class="colonne_droite">
		<div class="classement">
			<h3>{{type_resultat}}</h3>
		</div>

		<div class="detail_classement">
			{{{resultat}}}
		</div>
	</div>