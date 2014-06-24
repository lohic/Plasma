<!-- SLIDE EVENEMENT -->
<div id="ombre" class="ombre">

</div>
<div class="colonne {{type}}" data-type="{{type}}" data-couleur="{{couleur}}">
    <div class="header">
        <h2><span id="type_event">{{rubrique}}</span><br/>
			{{date_horaire}}<br/>
			{{lieu}}{{#lieu}}{{#code_batiment}}, {{/code_batiment}}{{/lieu}}{{code_batiment}}
        </h2>
        <p class="langue">{{langue}}</p>
    </div>
    
    <div class="texte">
        <h1>« {{titre}} »</h1>
    </div>

    <div class="texte2">
        <h2>{{organisateur}}{{#qualite}},{{/qualite}}<br/><span>{{qualite}}</span></h2>
    </div>

    <div class="visuel">
        <img src="<?php echo ABSOLUTE_URL.IMG_SLIDES; ?>{{image}}" alt="image" class="image-event"/>
    </div>
    
    
    <div class="footer">
        <div class="inscription">
            <p>{{inscription}}</p>
        </div>
        {{^coorganisateur}}
        <div class="logo" alt="Logo Sciences Po.">
            <img src="<?php echo ABSOLUTE_URL.SLIDE_TEMPLATE_FOLDER.'evenements/'; ?>logo.png" alt="Sciences Po"/>
        </div>
        {{/coorganisateur}}
        <div class="coorganisateur">
            {{#coorganisateur}}
            <p>{{coorganisateur}}</p>
            {{/coorganisateur}}
        </div>
    </div>
</div>