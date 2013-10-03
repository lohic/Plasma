<div id="ombre"></div>

<div class="colonne">
	<div class="header">
		<h2>{{type}}</h2>
	</div>
	
	<div class="texte">
		<h1>« {{titre}} »</h1>
	</div>

	<div class="texte2">
		<h2>
			{{intervenant}},<br/>
			<span class="small" title="Qualité de l'intervenant">{{qualite}}</span>
		</h2>
	</div>

	<video id="video" autoplay="true" src="<?php echo ABSOLUTE_URL.IMG_SLIDES; ?>{{fichier_video}}">
		<p>{{description}}</p>
	</video>
		
	<div class="footer">
		<div class="logo">
			<img src="<?php echo ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.'video/'; ?>logo.png" alt="Sciences Po"/>
		</div>
	</div>
</div>