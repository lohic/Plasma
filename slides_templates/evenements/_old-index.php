<div id="ombre">

</div>
<div class="colonne">
    <div class="header">
        <h2>
            <span class="edit textfield" max="22" id="A" title="A - Type d’événement"></span><br/>
			<span class="edit textfield" max="30" id="C" title="C - Date-horaire"></span><br/>
			<span class="edit textfield" max="30" id="E" title="E - Information de lieu"></span>, 
			<span class="edit textfield" id="F" title="F - Code bâtiment"></span>
        </h2>
        <p class="edit langue textfield" max="11" id="B" title="B - Langue"></p>
    </div>
    
    <div class="texte">
        <h1>"<span class="edit textarea" id="G" title="G - titre" max="39"></span>"</h1>
    </div>

    <div class="texte2">
        <h1><span class="edit textfield" id="H" title="H - auteur/animateur /organisateur" max="13"></span>,<br/></h1>
		<h2><span class="edit textfield" id="H2" title="H’ - sa qualité" max="28"></span></h2>
    </div>

    <div class="visuel edit image" id="I" title="I - image">
        <img src="" alt="image" class="image-event"/>
    </div>
    
    
    <div class="footer">
        <div class="inscription">
            <p><span class="edit textfield" id="J" title="J - entrée libre ou inscription" max="25"></span></p>
        </div>
        <div class="logo edit checkbox" alt="Logo Sciences Po." id="L" title="L - logo Sciences Po.">
            <img src="<?php echo ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.'evenements/'; ?>logo.png" alt="Sciences Po"/>
        </div>
        <div class="coorganisateur">
            <p class="edit listmenu" alt="Ecole de finance#Ecole 3#Ecole de communication#Ecole 4" id="M" title="M - co-organisateur"></p>
        </div>
    </div>
</div>