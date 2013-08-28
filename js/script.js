/*
* SCRIPT POUR LE BACKOFFICE PLASMA
*/

// accents
// http://www.pjb.com.au/comp/diacritics.html

var timeline;


$('document').ready(function(){

    $('#add_screen').click(function(e){
        addScreen();
    });

    /*$.getJSON('../ajax/slide-data.js', function(data) {
        console.log("C : "+data.C);
    });*/

    // TEST D'INCLUSION DE TEMPLATE
    /*var timestamp = new Date().getTime();
    $.ajax({
        url: "../slides_templates/compte_a_rebours/structure.js?cache="+timestamp,
    }).done(function ( data ) {
        //console.log(dataJSON);
        eval(data);      
        console.log("OK template ready");
        console.log(structure.date);
        ich.addTemplate('compte_a_rebour',template);
    });*/
    
    drawVisualization();
});

// Called when the Visualization API is loaded.
function drawVisualization() {

    // AFFICHE LA TIMELINE
    timeline = new links.Timeline(document.getElementById('mytimeline'));
    
    var todayM3 = new Date();
    todayM3.setDate(todayM3.getDate() - 3);

    var todayP4 = new Date();
    todayP4.setDate(todayP4.getDate() + 4)

    // options pour la timeline
    var options = {
        'width':  '100%',
        'height': 'auto',
        'snapEvents' : true,
        'cluster': true,
        'axisOnTop': true,
        'zoomMin' : 1000*60*60,
        'min' : new Date(2013, 7, 1),
        'max' : new Date(2013, 11, 31),
        'start' : todayM3,
        'end' : todayP4,
        'editable': true,
        'groupsChangeable':false,
        'showNavigation': false,
        'showButtonNew': false,
        'locale' : 'fr',
        'style' : 'box',
        'box' : {'align':'left'},
    };

    // charge en AJAX le contenu du fichier data-timeline.php
    var timestamp = new Date().getTime();
    $.ajax({
        url         : "../ajax/data-timeline.php",
        type        : "GET",
        data        : {cache : timestamp},
        dataType    : 'script'
    }).done(function ( dataJSON ) {
        //console.log(dataJSON);
        
        timeline.addItemType('screen', links.Timeline.ItemBox);
        timeline.addItemType('slide', links.Timeline.ItemRange);

        // on scanne les données contenues dans data-taimline.php
        eval(dataJSON);
        timeline.draw(data ,options);

        console.log("Timeline ready");

        // on intitialise la position des étiquettes de groupes
        for(var i = 0; i < screens.length ; i ++){
            timeline.changeItem(screens[i], {'start' : todayM3 });
        }

        timeline.setSelection();
    });

    // AJOUT D'UN SLIDE
    var onadd = function(event){

        var row = undefined;
        var sel = timeline.getSelection();
        if (sel.length) {
            if (sel[0].row != undefined) {
                var row = sel[0].row;
            }
        }

        if (row != undefined) {
            data[row].test = 'test : '+Math.random()*20;
            timeline.changeItem(row, {
                'className': 'unpublished',
                'type':'slide'
            });

            $.ajax({
                url     :"../ajax/data-timeline-slide.php",
                type    : "POST",
                dataType:'json',
                data    : {
                    titre   : data[row].content,
                    start   : new Date(data[row].start).addHours(2),
                    end     : new Date(data[row].end).addHours(2),
                    group   : data[row].group,
                    action  : 'create-item'
                }
            }).done(function ( dataJSON ) {
                console.log('ID créé : '+dataJSON +' ');
                console.log(data[row]); 

                timeline.changeItem(row, {
                    'id': dataJSON.id
                });
            });

            console.log('Ajout : ' + data[row].content + '\nstart : '+ data[row].start + '\nend   : ' + data[row].end + '\n[groupe : ' + data[row].group + ']'+ '\nclass : ' + data[row].className +'\nid : ' + data[row].id);
        }
    }
    

    // CHANGEMENT (via un drag) D'UN SLIDE
    var onchange = function(event){

        var row = undefined;
        var sel = timeline.getSelection();
        if (sel.length) {
            if (sel[0].row != undefined) {
                var row = sel[0].row;
            }
        }

        if (row != undefined) {

            var debut = new Date(data[row].start);

            $.ajax({
                url     :"../ajax/data-timeline-slide.php",
                type    : "POST",
                dataType:'json',
                data    : {
                    id      : data[row].id,
                    titre   : data[row].content,
                    start   : new Date(data[row].start).addHours(2),
                    end     : new Date(data[row].end).addHours(2),
                    group   : data[row].group,
                    action  : 'update-item'
                }
            }).done(function ( dataJSON ) {
                console.log(dataJSON);
                timeline.changeItem(row, {
                    'id': dataJSON.id
                });
            });

            console.log("onChange :\n" + data[row].start + ' >> ' + data[row].end + '\n[groupe : ' + data[row].group + ']');
        }
    }

    // SUPPRESSION D'UN SLIDE
    var ondelete = function(event){

        var row = undefined;
        var sel = timeline.getSelection();
        if (sel.length) {
            if (sel[0].row != undefined) {
                var row = sel[0].row;
            }
        }

        if (row != undefined) {
            $.ajax({
                url     :"../ajax/data-timeline-slide.php",
                type    : "POST",
                dataType:'json',
                data    : {
                    id      : data[row].id,
                    titre   : data[row].content,
                    action  : 'delete-item'
                }
            }).done(function ( dataJSON ) {
                alert(dataJSON.message);
                console.log(dataJSON);
            });
            console.log('DELETE : ' + data[row].content);
        }
    }
    
    
    // SÉLECTION D'UN SLIDE
    var onselect = function (event) {

        var row = undefined;
        var sel = timeline.getSelection();
        if (sel.length) {
            if (sel[0].row != undefined) {
                var row = sel[0].row;
            }
        }

        if (row != undefined) {
            console.log("SELECT");
            console.log("id : "  + data[row].id );
            console.log("start : "  + data[row].start );
            console.log("end : "    + data[row].end );
            console.log("content : "+ data[row].content );
            console.log("group : "  + data[row].group );
        }  
    };


    // ÉDITION D'UN SLIDE
    var onedit = function(event){
        var row = undefined;
        var sel = timeline.getSelection();
        if (sel.length) {
            if (sel[0].row != undefined) {
                var row = sel[0].row;
            }
        }

        if (row != undefined) {
            

            if(data[row].type == 'slide'){
                
                edit_item(row);
                
            }else if(data[row].type == 'screen'){
                screen_info = {
                    group:     data[row].group,
                    screen_title:   data[row].content,
                    selector: screen_list
                };

                screen_editor = ich.screen_editor(screen_info);

                $.fancybox( screen_editor , {
                    title : '<h1>Éditeur d’écran</h1>',
                    helpers : {
                        title: {
                            type: 'inside',
                            position: 'top'
                        }
                    },
                });
            }
        }
    }


    // MISE A JOUR LORS D'UN SCROLL OU UN ZOOM
    var onrangechange = function(event){           
        for(var i = 0; i < screens.length ; i ++){
            timeline.changeItem(screens[i], {'start' : new Date(event.start) });
            //console.log("start : "+new Date(event.start));
        }
        timeline.setSelection();
    }
    

    links.events.addListener(timeline, 'add', onadd);
    links.events.addListener(timeline, 'change', onchange);
    links.events.addListener(timeline, 'delete', ondelete);
    links.events.addListener(timeline, 'select', onselect);
    links.events.addListener(timeline, 'edit',   onedit);
    links.events.addListener(timeline, 'rangechange', onrangechange);
}

function edit_item(ref){
    console.log('edit slide');

    var date1 = new Date(data[ref].start);
    var date2 = new Date(data[ref].end);
    var duree = Math.round((date2-date1)/1000);
    
    slide_info = {
        group:     data[ref].group,
        content:   data[ref].content,
        start:     data[ref].start,
        end:       data[ref].end,

        duree:     second2HMS(duree),

        annee1:    date1.getFullYear(),
        mois1:     date1.getMonth() + 1 <10 ? "0"+(date1.getMonth() + 1): date1.getMonth() + 1,
        jour1:     date1.getDate() <10      ? "0"+date1.getDate()       : date1.getDate(),
        heure1:    date1.getHours()<10      ? "0"+date1.getHours()      : date1.getHours(),
        minute1:   date1.getMinutes()<10    ? "0"+date1.getMinutes()    : date1.getMinutes(),
        seconde1:  date1.getSeconds()<10    ? "0"+date1.getSeconds()    : date1.getSeconds(),

        annee2:    date2.getFullYear(),
        mois2:     date2.getMonth() + 1 <10 ? "0"+(date2.getMonth() + 1): date2.getMonth() + 1,
        jour2:     date2.getDate() <10      ? "0"+date2.getDate()       : date2.getDate(),
        heure2:    date2.getHours()<10      ? "0"+date2.getHours()      : date2.getHours(),
        minute2:   date2.getMinutes()<10    ? "0"+date2.getMinutes()    : date2.getMinutes(),
        seconde2:  date2.getSeconds()<10    ? "0"+date2.getSeconds()    : date2.getSeconds(),

        selector: screen_list,

    };

    slide_editor = ich.slide_editor(slide_info);

    $.fancybox( slide_editor , {
        title : '<h1>Éditeur de slide</h1>',
        helpers : {
            title: {
                type: 'inside',
                position: 'top'
            }
        },
        afterLoad : function(){
            console.log("fancybox OK");
        }
    });

    // on selectionne le groupe quand on affiche le formulaire
    $('#screen_reference').val(data[ref].group);

    $('#save_slide').click(function(e){

        console.log('ok');

        var group = $('#screen_reference').val();
        console.log('changement de groupe : '+group);
        timeline.changeItem(ref, {
            'group': group
        });

        //$.fancybox.close();
        e.preventDefault();
    });

    // gestion de l'édition de contenu
    editSlideContent(ref);
}

/*
* fonction pour gérer l'édition de contenu d'un slide
*/
function editSlideContent(ref){

    $('#edit_slide_content').click(function(e){

        console.log(">>> EDIT SLIDE CONTENT : "+ ref);

        /*var slide_content = {'id':ref};
        slide_content_editor = ich.slide_content_editor(slide_content);

        $.fancybox( slide_content_editor , {
            title : '<h1>Modifier un slide</h1>',
            helpers : {
                title: {
                    type: 'inside',
                    position: 'top'
                }
            },
        });*/

        edit_slide();

        e.preventDefault();

    });
}

/*
* Conversion des secondes en hh : mm : ss
*/ 
function second2HMS(duree){
    // heure
    retour = (duree-duree%3600)/3600<10 ? "0"+ (duree-duree%3600)/3600+"h"  : (duree-duree%3600)/3600 + 'h';
    duree = duree%3600;
    // minute
    retour += (duree-duree%60)/60 <10   ? "0"+(duree-duree%60)/60+"m"    : (duree-duree%60)/60 + 'm';
    duree = duree%60;
    // secondes
    retour += duree < 10                ? "0"+duree+"s"                      : duree+'s';

    return retour;
}

/**
 * ajouter un écran
 */
function addScreen(){
    screen_list.push ({"key" : $('#group').val(), "value" : $('#group').val()})

    timeline.addItem({
        'start': new Date(2012, 0, 1),
        "group" : $('#group').val(),
        "editable" : true,
        'content': $('#group').val(),
        "type" : "screen",
        "className" : "screen"
    });


    var sel = timeline.getData();
    var lastItemID = sel.length-1;

    screens.push(lastItemID);
    //console.log("longueur : " + lastItemID);
    //console.log(screens.join(', '));
}

Date.prototype.addHours= function(h){
    var copiedDate = new Date(this.getTime());
    copiedDate.setHours(copiedDate.getHours()+h);
    return copiedDate;
}


/*
* --------------------------------
* POUR GERER L'EDITION DES SLIDES
* --------------------------------
*/

function edit_slide(){
    console.log('edit_slide appelé DFORM');
    // on vide le formulaire
    $("#myform").empty();

    // on génère un formulaire à partir d'une structure JSON
    // la structure est la fusion du fichier structure.json correspondant au template
    // et des données sauvegardées au format JSON en bas de donnée
    $("#myform").dform('../ajax/import-json.php?template='+ $template +'&slide_id=' + $slide_id,function(data){

        // quand le formulaire est généré, on ouvre une fenêtre fancybox
        $.fancybox( this , {
            title : '<h1>Éditeur de slide</h1>',
            width : 500,
            helpers : {
                title: {
                    type: 'inside',
                    position: 'top'
                }
            }
        });

        // pour désactiver le clic extérieur sur fancybox
        $(".fancybox-overlay").unbind();

        // on initialise tinyMCE
        tinymce.init({
            selector: "textarea",
            toolbar: " ",
            menubar : false,
            entity_encoding : 'raw',    
        });

        // activation de uplodifive
       
        $(function() {
            $('input[type="file"]').uploadifive({
                'checkScript'       : '../js/uploadifive-v1.1.2-standard/check-exists.php',
                'multi'             : false,
                'formData'          : {
                                       'timestamp' : $timestamp,
                                       'token'     : $token
                                      },
                'queueSizeLimit'    : 1,
                //'queueID'          : 'queue',
                'removeCompleted'   : true,
                'uploadScript'      : '../ajax/uploadifive.php',
                'onInit'            : function(){
                    
                    // on affiche une vignette ou un texte si on trouve un média image ou vidéo
                    $('input[type="file"]').each(function(){
                        if( $(this).data('file') != undefined ) {

                            var temp = $(this).data('file').split('.');
                            var ext = temp[temp.length-1].toLowerCase();
                            

                            $(this).before("<p>"+ $(this).data('file') + "</p>");
                            $(this).parent().before("<div class='preview'></div>")

                            preview($(this), $(this).data('file'), ext);
                        }
                    });

                    var attr = $(this).attr('name');
                    $(this).attr('name', attr + "-old");
                    $(this).data('old-name', attr);
                    $(this).after( "<input type='hidden' name='" + attr + "'/>" );

                },
                'onUploadComplete'  : function(file,data) {

                    info = JSON.parse(data);
                    if(!info.error){
                        $('input[name="'+ $(this).data('old-name') +'"]' ).val( info.file );

                        preview($(this),info.file,info.ext);

                        console.log("upload finished : "+info.file +" / type : "+info.ext);
                    }else{
                        alert( info.message );
                    }
                }
            });

        });

        // quand on valide
        $("#validation").click(function(e){
            // on attribue bien le contenu de tinyMCE aux champs d'origine
            tinyMCE.triggerSave();
            
            console.log( JSON.stringify( formToJSON( $("#myform").serializeArray() ) ) );

            e.preventDefault();
        });
    });
}


/*
* fonction pour créer la preview (image ou vidéo)
* au chargement ou au rafraichissement
* si le media est une vidéo, mets à jour la durée
*/
function preview(ref, file, ext){
    console.log('PREVIEW');
    delete(ref.parent().prev().find('video'));
    ref.parent().prev().html("");            
    ref.parent().prev().empty();
    $('input[name="'+ref.data('old-name')+'-duration"]').remove();


    //console.log(ref.parent().prev());

    if($.inArray(ext, $videoExt) != -1){
        console.log('video');
        ref.parent().prev().html("<video style='max-width:100%;height:auto;' onClick='play();' onLoad='dureeVideo();' src='../slides_images/"+ file +"'></video>");
        dureeVideo(ref);
    }else{
        console.log('image');
        ref.parent().prev().html("<img src='../slides_images/"+ file +"' class='vignette'/>");
    }
}


/*
* FONCTION POUR CALCULER LA DUREE D'UNE VIDEO
*/
function dureeVideo(ref){
    //console.log( "durée : " + $(this).parent().prev().find('video').duration );
    //
    ref.parent().prev().find('video')
    .bind("loadedmetadata", function(e){
        e.preventDefault();

        $(this).after('<p>durée : '+ (Math.round(this.duration*100)/100) +' sec</p>');

        ref.parent().before( '<input type="hidden" name="'+ref.data('old-name')+'-duration" value="'+this.duration+'">' );

        console.log("vidéo chargée : " + this.duration);
    });
}

/*
* fonction de conversion des éléments d'un formuaire en un objet JSON du type
* { nom : valeur , nom2 : valeur2 }
* @param data : un tableau d'objets issus d'un formulaire normalisées avec .serializeArray()
* @return un objet javascript { nom : valeur , nom2 : valeur2 }
*/
function formToJSON( data ) {
    // on crée l'objet retour
    var retour = {};

    $.each(data, function(i,ligne){

        retour[ligne.name] = ligne.value;
    });
    return retour;
}

//console.log = function() {};