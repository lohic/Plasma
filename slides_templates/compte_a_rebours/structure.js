
var structure = {
    'name' : 'compte à rebour',
    'date' : '13/08/2013',
    'structure' : [
        {
            'type' : 'image',
            'value' : ''
        },
        {
            'type' : 'date',
            'value' : '0'
        }
    ]
}

var template = ''+
'<div id="ombre"></div>'+
'<div class="colonne">'+
'<div class="edit textfield date invisible" id="A" title="A - Date de l’événement">{{date}}</div>'+
'<div class="header">'+
'<h1></h1>'+
'</div>'+
'<div class="visuel edit image" id="I" title="I - image">'+
'<img src="" width="768" alt="image" class="image-event" />'+
'</div>'+
'<div class="footer">'+
'<div class="logo">'+
'<img src="slides_templates/compte_a_rebours/logo.png" alt="Sciences Po" />'+
'</div>'+
'</div>'+
'</div>';