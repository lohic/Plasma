<div id="ombre">

	</div>
<div class="colonne">
	<div class="header">
		<h2>
			<span class="edit textfield" max="22" id="A" title="A - Type de vidéo">{{type}}</span>
		</h2>
	</div>
	
	<div class="texte">
		<h1>"
			<span class="edit textfield" max="34" id="B" title="B - Titre/thème">{{titre}}</span>
		"</h1>
	</div>

	<div class="texte2">
		<h2>
			<span class="edit textfield" max="34" id="C" title="C - Nom de l'intervenant">{{intervenant}}</span>,<br/>
			<span class="small edit textfield" max="39" id="D" title="D - Qualité de l'intervenant">{{qualite}}</span>
		</h2>
	</div>

	<span class="edit video" id="E" title="E - Vidéo">
		<video id="video" autoplay="true" src="<?php echo ABSOLUTE_URL.IMG_SLIDES; ?>{{fichier_video}}">
			<p>{{description}}</p>
		</video>
	</span>
	<div class="footer">
		<div class="logo">
			<img src="<?php echo ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.'video/'; ?>logo.png" alt="Sciences Po"/>
		</div>
	</div>
</div>