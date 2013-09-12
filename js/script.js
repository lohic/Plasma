/**
 * SCRIPT POUR LE BACKOFFICE PLASMA
 */

// accents
// http://www.pjb.com.au/comp/diacritics.html

var timeline;
var dataTimeline;
var data_event;

$('document').ready(function(){

    // GESTION DU MENU PRINCIPAL
    $('ul#menuDown > li').mouseover(function(){ $(this).children('a').addClass('menuDown-hover').siblings('ul').show(); });
    $('ul#menuDown > li').mouseout(function(){ $(this).children('a').removeClass('menuDown-hover').siblings('ul').hide(); });

    $("#globalnav>ul>li>a").each( function () { 
        $(this).attr("class","");
    } ) ;

    $("#globalnav>ul>li>a").click( function () {
        $("#globalnav>ul>li>a").each( function () {
            $(this).attr("class","");

        } ) ;

        $(this).attr("class","select"); 
    } ) ;

    // quand on clique sur un icone poubelle
    $("li span.trash").each( function () {  
        $(this).click( function () {

            $(this).parent().remove();
            var order = '';

            $('.news_list').each( function () {
                order += $(this).attr('id') +':'+ $(this).sortable('toArray')+'|';
            });

            //alert(order);

            var valeur = document.getElementById("save_value");
            valeur.value = order;

            $('#return_refresh').text('état : Sauvegarde en cours !');
            $('#refresh_form').submit();
        } ) ;
    } ) ;
    
    // fonction pour ajouter un écran 
    $('#add_screen').click(function(e){
        addScreen();
    });

    // activation du datepicker jquery
    $(function() {
        $(".date").datepicker({
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            dayNamesMin : ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            monthNames : ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre']
        });
    });
    
    // on vérifie la présence d'une balise de timeline
    if($('#mytimeline').length != 0){
        drawTimeline();
    }

    /**
     * ---------------------------------
     * POUR GERER LES BOUTONS DE LA PAGE
     * ---------------------------------
     */

    $('#group_publish').click(function(e){
        e.preventDefault();

        console.log(' ');
        console.log('PUBLICATION DU GROUPE');
        console.log('id_groupe : '+getUrlVars().id_groupe);
        
        $.ajax({
            url     :"../ajax/publish-screen.php",
            type    : "GET",
            dataType:'json',
            data    : {
                id_groupe : getUrlVars().id_groupe,
                publish  : 'groupe'
            }
        }).done(function ( dataJSON ) {
            console.log(dataJSON);
        });
    });

    /**
     * Pour afficher ou masque le bloc de preview
     * quand on va sur l'icone de l'oeil d'un écran
     */
    $(".child-screen .ecran").each(function(){

        $(this).find('img[alt="voir"]').mouseenter(function(){
            console.log('enter');

            $('#preview_screen').attr('src', $(this).parent().attr('href')+"&preview&tiny");
            $('#preview_screen').show();

            $( "#preview_screen" ).position({
                of: $(this).parent().parent().parent(),
                my: "center bottom",
                at: "center top-10",
                collision: "none flip"
            });
        });

    });

    $(".child-screen .ecran").mouseleave(function(){
        $('#preview_screen').hide();
        $('#preview_screen').attr('src', "");
    });

    $(".edit_slide").click(function(e){
        //alert($(this).data('id'));
        edit_slide($(this).data('id-slide'), $(this).data('template'), $(this).data('title'),'slide');
        e.preventDefault();
    });

    slide_preview();

});




/**
 * génère l'affichage de la timeline
 * après récupération des données dans ../ajax/data-timeline.php
 * attache les écouteurs nécessaires
 */
function drawTimeline() {

    // créé l'objet timeline en instanciant le DIV correspondant
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
        'zoomMin' : 1000*60*60/5,
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
        data        : {cache : timestamp, id_groupe: $id_groupe},
        dataType    : 'script'
    }).done(function ( dataJSON ) {
        //console.log(dataJSON);
        
        // on crée les objets SCREEN et SLIDE
        timeline.addItemType('screen', links.Timeline.ItemBox);
        timeline.addItemType('slide', links.Timeline.ItemRange);

        // on scanne les données contenues dans data-timeline.php
        eval(dataJSON);
        //AFFICHE LA TIMELINE
        timeline.draw(dataTimeline ,options);

        console.log("Timeline ready");

        // on intitialise la position des étiquettes de groupes
        /*for(var i = 0; i < screens.length ; i ++){
            timeline.changeItem(screens[i], {'start' : todayM3 });
        }

        timeline.setSelection();*/
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
            timeline.changeItem(row, {
                'className': 'unpublished default',
                'type':'slide',
                'template':'default',
                'id_slide':0
            });

            $.ajax({
                url     :"../ajax/data-timeline-item.php",
                type    : "POST",
                dataType:'json',
                data    : {
                    id_slide: dataTimeline[row].id_slide,
                    id_group: $id_groupe,
                    titre   : dataTimeline[row].content,
                    start   : new Date( dataTimeline[row].start ).addHours(2),
                    end     : new Date( dataTimeline[row].end   ).addHours(2),
                    group   : dataTimeline[row].group,
                    template: dataTimeline[row].template,
                    action  : 'create-item'
                }
            }).done(function ( dataJSON ) {
                console.log('ID créé : '+dataJSON +' ');
                console.log(dataTimeline[row]); 

                timeline.changeItem(row, {
                    'id': dataJSON
                });

                console.log(' ');
                console.log('ONADD ref : ' + row + ' id : ' + dataTimeline[row].id + ' id_slide : ' + dataTimeline[row].id_slide);
                console.log('content  : ' + dataTimeline[row].content );
                console.log('start  : ' + dataTimeline[row].start + ' end : ' + dataTimeline[row].end);
                console.log('groupe : ' + dataTimeline[row].group);
                console.log('class  : ' + dataTimeline[row].className);
            });
            
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

            var debut = new Date(dataTimeline[row].start);

            console.log(' ');
            console.log("ONCHANGE id : " + dataTimeline[row].id + " id_slide : "  + dataTimeline[row].id_slide);
            console.log('start : '+dataTimeline[row].start + ' end : ' + dataTimeline[row].end);
            console.log('groupe : ' + dataTimeline[row].group);
            //ok
            $.ajax({
                url     :"../ajax/data-timeline-item.php",
                type    : "POST",
                dataType:'json',
                data    : {
                    id      : dataTimeline[row].id,
                    id_slide: dataTimeline[row].id_slide,
                    id_group: $id_groupe,
                    titre   : dataTimeline[row].content,
                    start   : new Date(dataTimeline[row].start).addHours(2),
                    end     : new Date(dataTimeline[row].end).addHours(2),
                    group   : dataTimeline[row].group,
                    template: dataTimeline[row].template,
                    published : dataTimeline[row].className.indexOf("unpublished") < 0 ? 1 : 0,
                    action  : 'update-item'
                }
            }).done(function ( dataJSON ) {
                console.log("Save change : "+dataJSON);
                /*timeline.changeItem(row, {
                    'id': dataJSON.id
                });*/
            });
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
                url     :"../ajax/data-timeline-item.php",
                type    : "POST",
                dataType:'json',
                data    : {
                    id      : dataTimeline[row].id,
                    titre   : dataTimeline[row].content,
                    action  : 'delete-item'
                }
            }).done(function ( dataJSON ) {
                alert(dataJSON.message);
                //console.log(dataJSON);
            });

            console.log(' ');
            console.log('ONDELETE id : '+ dataTimeline[row].id + ' content : '+ dataTimeline[row].content);
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
            console.log(' ');
            console.log("ONSELECT id : " + dataTimeline[row].id + " id_slide : "  + dataTimeline[row].id_slide + " classes : "+dataTimeline[row].className);
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
            
            if(dataTimeline[row].type == 'slide'){
                
                edit_item(row);
                
            }else if(dataTimeline[row].type == 'screen'){
                screen_info = {
                    group:     dataTimeline[row].group,
                    screen_title:   dataTimeline[row].content,
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
        /*for(var i = 0; i < screens.length ; i ++){
            timeline.changeItem(screens[i], {'start' : new Date(event.start) });
            //console.log("start : "+new Date(event.start));
        }
        timeline.setSelection();*/
    }
    
    // ON ATTACHE LES DIFFERENTS ECOUTEURS DE LA TIMELINE
    links.events.addListener(timeline, 'add', onadd);
    links.events.addListener(timeline, 'change', onchange);
    links.events.addListener(timeline, 'delete', ondelete);
    links.events.addListener(timeline, 'select', onselect);
    links.events.addListener(timeline, 'edit',   onedit);
    //links.events.addListener(timeline, 'rangechange', onrangechange);
}



/*
 * fonction pour éditer les informations d'un item de la timeline
 * normalise les données dans un objet
 * affiche les données dans un formulaire généré avec iCanHaz
 * affiche ce formulaire dans une fenêtre fancybox
 * @param {ref} la référence de l'item timeline sélecctionné
 */
function edit_item(ref){
    console.log('Edit slide');

    var date1 = new Date(dataTimeline[ref].start);
    var date2 = new Date(dataTimeline[ref].end);
    var duree = Math.round((date2-date1)/1000);

    var actionAfterClose;
    
    // on crée l'objet qui va récupérer les informations d'un item 
    slide_info = {
        slide_id:  dataTimeline[ref].id_slide,

        group:     dataTimeline[ref].group,
        content:   dataTimeline[ref].content,
        start:     dataTimeline[ref].start,
        end:       dataTimeline[ref].end,

        duree:     second2HMS(duree),
        template:  dataTimeline[ref].template,

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

        group_selector: screen_list,
        template_selector:template_list, 

    };

    console.log("ID_SLIDE : "+dataTimeline[ref].id_slide);

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
            //console.log("fancybox OK");
        },
        afterClose : function(){
            console.log('edit item close / action : '+actionAfterClose);

            if(actionAfterClose == "edit_slide"){
                //alert('ok');
                edit_slide(dataTimeline[ref].id_slide, dataTimeline[ref].template, dataTimeline[ref].content, 'timeline',ref);
            }
        }
    });

    // on selectionne le groupe quand on affiche le formulaire
    $('#screen_reference').val(dataTimeline[ref].group);
    $('#template_reference').val(dataTimeline[ref].template);
    $('#published').attr('checked', dataTimeline[ref].className.indexOf("unpublished") < 0 ? true : false);
    if($('#template_reference').val() == 'meteo'){
            $('#edit_slide_content').hide();
    }
    $template = $('#template_reference').val();
    //alert( dataTimeline[ref].className.indexOf("unpublished") >= 0 ? 1 : 0) ;
    
    // ------------------------------------
    slide_preview();
    // ------------------------------------


    $('#template_reference').change(function(){
        if($(this).val() == 'meteo'){
            $('#edit_slide_content').hide();
        }else{
            $('#edit_slide_content').show();
        }

        //on mémorise le gabarit sélectionné
        $template = $(this).val();
    });

    // sauvegarde d'un item
    $('#save_item').click(function(e){
        e.preventDefault();

        var group       = $('#screen_reference').val();
        var content     = $('#template_reference').val() == 'meteo' ? "Météo" : dataTimeline[ref].content;
        var template    = $('#template_reference').val();

        $('#item_title').val(content);

        console.log($("#published").is(':checked') ? 'publié' : 'non publié');


        //------------------------
        // on réattribue les classes
        //------------------------        
        var classes = dataTimeline[ref].className;

        //console.log("class :"+classes);

        classes = classes.split(' ');

        if($("#published").is(':checked')){
            for(var i=0; i<classes.length; i++){
                if(classes[i] == 'unpublished'){
                    classes[i] = '';
                }
            }
        }else{
            var isUnpublished = false;
            for(var i=0; i<classes.length; i++){
                if(classes[i] == 'unpublished'){
                    isUnpublished = true;
                }
            }
            if(!isUnpublished){
                classes.push('unpublished');
            }
        }
        classes.push(template);
        classes = cleanArray(classes);
        classes = classes.join(' ');
        //console.log("class :"+classes);

        console.log("template : "+template);

        timeline.changeItem(ref, {
            'group':        group,
            'className':    classes,
            'content' :     content,
            'template' :    template
        });

        //ok 2
        $.ajax({
            url     :"../ajax/data-timeline-item.php",
            type    : "POST",
            dataType:'json',
            data    : {
                id      : dataTimeline[ref].id,
                id_slide: dataTimeline[ref].id_slide,
                id_group: $id_groupe,
                titre   : dataTimeline[ref].content,
                start   : new Date(dataTimeline[ref].start).addHours(2),
                end     : new Date(dataTimeline[ref].end).addHours(2),
                group   : dataTimeline[ref].group,
                template: dataTimeline[ref].template,
                published : dataTimeline[ref].className.indexOf("unpublished") < 0 ? 1 : 0,
                action  : 'update-item'
            }
        }).done(function ( dataJSON ) {
            console.log(dataJSON);
            /*timeline.changeItem(ref, {
                'id': dataJSON
            });*/
        });
    });

    // gestion de l'édition de contenu

    $('#edit_slide_content').click(function(e){

        //console.log(dataTimeline);
        console.log(">>> EDIT SLIDE CONTENT : "+ ref + " ID_SLIDE : "+ dataTimeline[ref].id_slide);

        actionAfterClose = 'edit_slide';
        $.fancybox.close();

        //edit_slide(dataTimeline[ref].id_slide, $('#template_reference').val(), dataTimeline[ref].content, 'timeline',ref);     
        e.preventDefault();

    });

    $('#publish_slide').click(function(e){

        console.log("published : "+ ($("#published").is(':checked') ? 1 : 0));
        e.preventDefault();

    });
}


function slide_preview(){

    $(".slide_view>a").mouseenter(function(){
        console.log('ok');

        console.log('preview : '+$(this).attr('href')+"&template="+$(this).data('id-slide')+"&preview&tiny");

        $('#preview_screen').attr('src', $(this).attr('href')+"&preview&tiny");
        $('#preview_screen').show();

        $( "#preview_screen" ).position({
            of: $(this),
            my: "center bottom",
            at: "center top-10",
            collision: "none flip"
        });
    });


    $(".slide_view>a").mouseleave(function(){
        $('#preview_screen').hide();
        $('#preview_screen').attr('src', "");
    });
}


/**
 * Conversion des secondes en hh : mm : ss
 * @param {duree} un entier qui indique une durée en secondes
 * @return {retour} une chaîne formatée : '00h:00m:00s'
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

    // screens.push(lastItemID);
    // console.log("longueur : " + lastItemID);
    // console.log(screens.join(', '));
}


/**
 * --------------------------------
 * POUR GERER L'EDITION DES SLIDES
 * --------------------------------
 */




/**
 * pour gérer l'édition d'un slide
 * génère un formulaire avec le plugin jquery dform
 * affiche le formulaire dans une fancybox
 * si un champ FILE est trouvé utilise uploadifive
 */
function edit_slide(id_slide,template,titre,edit_from,ref_timeline){
    
    // on vide le formulaire
    $("#myform").empty();
    console.log("EDIT SLIDE : from "+edit_from+" : "+id_slide+" "+template+" "+titre);

    // on vérifie si on crée un slide ou si on le modifie
    var type_action = id_slide==0 ? 'create-slide' : 'update-slide';

    // on génère un formulaire à partir d'une structure JSON
    // la structure est la fusion du fichier structure.json correspondant au template
    // et des données sauvegardées au format JSON en bas de donnée
    $("#myform").dform('../ajax/import_slide_json.php?template='+ template +'&id_slide=' + id_slide +"&cache=" + new Date() ,function(data){

        // quand le formulaire est généré, on ouvre une fenêtre fancybox
        $.fancybox( this , {
            title : '<h1>Éditeur de slide</h1>',
            autoSize:false,
            width:500,
            scrollOutside:false,
            autoHeight:true,
            helpers : {
                title: {
                    type: 'inside', 
                    position: 'top'
                }
            },
            afterShow: function(){
        
            },
            afterLoad: function(current, previous) {
                console.log(current);
                //$.fancybox.reposition();
                //$.fancybox.update();
            }
        });
        //*
        $(".fancybox-title h1").text( 'Éditeur de ' + $('#myform').attr('title') );
                $(".fancybox-inner").prepend("<label>Titre du slide</label><input type='text' id='slide_title' value='"+titre+"'/>");
                if(template == 'evenements'){
                    event_selector();
                }
                $(".fancybox-inner #myform").append("<button id='back_to_item'>Retour à l’item</button>");
                $(".fancybox-inner #myform").append("<button id='save_slide'>Sauvegarde</button>");

                $(".fancybox-title h1").click(function(e){
                    $.fancybox.update();
                    e.preventDefault();
                });

                if(typeof(ref_timeline) != 'undefined'){
                    $("#back_to_item").click(function(e){
                        e.preventDefault();
                        edit_item(ref_timeline);
                    });
                }else{
                    $("#back_to_item").hide();
                }

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

                                    preview_media($(this), $(this).data('file'), ext);
                                }
                            });

                            var attr = $(this).attr('name');
                            $(this).attr('name', attr + "-old");
                            $(this).data('old-name', attr);
                            $(this).after( "<input type='hidden' name='" + attr + "' value='"+ $(this).data('file') +"'/>" );

                        },
                        'onUploadComplete'  : function(file,data) {

                            info = JSON.parse(data);
                            if(!info.error){
                                $('input[name="'+ $(this).data('old-name') +'"]' ).val( info.file );

                                preview_media($(this),info.file,info.ext);

                                console.log("upload finished : "+info.file +" / type : "+info.ext);
                                //$.fancybox.update();
                                //$.fancybox.reposition();
                            }else{
                                alert( info.message );
                            }
                        }
                    });

                });
        

        // SAUVEGARDE DU SLIDE
        $("#save_slide").click(function(e){
            e.preventDefault();

            // on attribue bien le contenu de tinyMCE aux champs d'origine
            tinyMCE.triggerSave();
            dform_value = formToJSON( $("#myform").serializeArray() );

            var duree = parseInt( $('#myform input[name*="duration"]').val() ) + 3;
            console.log("Durée de la vidéo : "+duree);
            
            // on sauvegarde les valeurs en base de donnée
            $.ajax({
                url     :"../ajax/data-timeline-slide.php",
                type    : "POST",
                dataType:'json',
                data    : {
                    id_slide: id_slide,
                    nom     : $("#slide_title").val(),
                    template: template,
                    json    : JSON.stringify( dform_value ),
                    action  : type_action
                }
            }).done(function ( dataJSON ) {
                console.log("slide sauvegardé");


                // SI ON EDITE DEPUIS DE LISTING DE SLIDES
                if(edit_from == 'slide'){
                    console.log('changement de nom : '+$("#slide_title").val());
                    //$('#news_listing>div .liens button[data-id-slide="'+id_slide+'"]').parent().parent().find('.titre a').text( $("#slide_title").val() );
                    $('#titre-slide-'+id_slide).text( $("#slide_title").val() );

                    $('.edit_slide[data-id-slide="'+id_slide+'"]').data('title',$("#slide_title").val() );
                }

                // SI ON EDITE DEPUIS LA TIMELINE
                else if(edit_from == 'timeline' && typeof(ref_timeline) != 'undefined'){
                    // on sauvegarde les propriétés de l'item
                    timeline.changeItem(ref_timeline, {
                        'id_slide': dataJSON.id,
                        'content' : $("#slide_title").val(),
                        'template': template,
                        'end'     : duree>0 ? new Date(dataTimeline[ref_timeline].start).addSeconds(duree) : new Date(dataTimeline[ref_timeline].end)
                    });
                    // ok 3
                    $.ajax({
                        url     :"../ajax/data-timeline-item.php",
                        type    : "POST",
                        dataType:'json',
                        data    : {
                            id      : dataTimeline[row].id,
                            id_slide: dataTimeline[row].id_slide,
                            id_group: $id_groupe,
                            titre   : dataTimeline[row].content,
                            start   : new Date(dataTimeline[row].start).addHours(2),
                            end     : new Date(dataTimeline[row].end).addHours(2),
                            group   : dataTimeline[row].group,
                            template: dataTimeline[row].template,
                            published : dataTimeline[row].className.indexOf("unpublished") < 0 ? 1 : 0,
                            action  : 'update-item'
                        }
                    }).done(function ( dataJSON ) {
                        console.log(dataJSON);
                    });
                }
            });
        });
        //*/
    });
}

function event_selector(){
    console.log('SELECTEUR D’ÉVENEMENT');

    var data_event_selector = {
        "year_event" : [
        {"key":2013,"value":2013},
        {"key":2012,"value":2012},
        {"key":2011,"value":2011}],
        "month_event" : [
        {"key":1,"value":"janvier"},
        {"key":2,"value":"février"},
        {"key":3,"value":"mars"},
        {"key":4,"value":"avril"},
        {"key":5,"value":"mai"},
        {"key":6,"value":"juin"},
        {"key":7,"value":"juillet"},
        {"key":8,"value":"août"},
        {"key":9,"value":"septembre"},
        {"key":10,"value":"octobre"},
        {"key":11,"value":"novembre"},
        {"key":12,"value":"décembre"}]
    }

    var event_selector = ich.event_selector(data_event_selector);
    $(".fancybox-inner").prepend(event_selector);

    loadEventFromAPI();

    $("#year_event").change(function(e){
        $("#id_session").empty();
         $("#id_event").empty();
        loadEventFromAPI('event');
    });
    $("#month_event").change(function(e){
        $("#id_session").empty();
         $("#id_event").empty();
        loadEventFromAPI('event');
    });
    $("#id_organisme").change(function(e){
        $("#id_session").empty();
         $("#id_event").empty();
        loadEventFromAPI('event');
    });
    $("#id_event").change(function(e){
        $("#id_session").empty();
        loadEventFromAPI('session');
    });

    $("#refresh_event").click(function(e){
        refresh_event();
    });

    var d = new Date();

    $("#year_event").val(d.getFullYear());
    $("#month_event").val(d.getMonth()+1);
}

function refresh_event(){
    console.log(data_event);

    var id_session = $("#id_session option:selected").index();

    console.log("id_session : "+id_session);

    $("#slide_title").val(                          data_event.sessions[id_session].titre);

    //$('#myform input[name="type"]').val(            data_event);
    $('#myform input[name="date_horaire"]').val(    data_event.sessions[id_session].date_debut + " " +data_event.sessions[id_session].horaire_debut);
    $('#myform input[name="lieu"]').val(            data_event.sessions[id_session].lieu);
    $('#myform input[name="code_batiment"]').val(   data_event.sessions[id_session].code_batiment);
    $('#myform input[name="langue"]').val(          data_event.sessions[id_session].code_langue);
    $('#myform input[name="titre"]').val(           data_event.sessions[id_session].titre);
    $('#myform input[name="organisateur"]').val(    data_event.organisateur);
    $('#myform input[name="qualite"]').val(         data_event.organisateur_qualite);
    $('#myform input[name="coorganisateur"]').val(  data_event.coorganisateur);
    //$('#myform input[name="image"]').val();
    //
    
    downloadEventImage(data_event.url_image, data_event.id);

    $('#myform input[name="inscription"]').val(     data_event.sessions[id_session].type_inscription);



}

function downloadEventImage(url,id){
    console.log("téléchargement image : "+url);
    $.ajax({
        url     :"../ajax/event-image-download.php",
        type    : "POST",
        dataType:'json',
        data    : {
            url      : url,
            id_event : id,
        }
    }).done(function ( dataJSON ) {
        console.log("retour download : "+dataJSON);
        //timeline.changeItem(ref_timeline, {
        //    'id': dataJSON.id
        //});
        $('#myform input[name="image"]').val(dataJSON.file);

        preview_media($('#myform input[name="image-old"]'), dataJSON.file, dataJSON.ext);
    });
}

function loadEventFromAPI(info_to_refresh){

    $.ajax({
        url     :"../ajax/api-event.php",
        type    : "GET",
        dataType:'json',
        data    : {
            year        : $("#year_event").val(),
            month       : $("#month_event").val(),
            id_organisme: $("#id_organisme").val(),
            lang        : "fr",
            id_event       : $("#id_event").val()
        }

    }).done(function ( dataJSON ) {
        console.log('date event reçues');

        //info = JSON.JSONparse(dataJSON);

        //console.log(dataJSON.evenements.organismes);

        //console.log(dataJSON.evenements.evenement);

        if(typeof(dataJSON.evenements) != 'undefined'){
            var event_selector = $("#id_event");
            var organisme_selector = $("#id_organisme");
            //don't forget error handling!
            event_selector.empty();
            $.each(dataJSON.evenements.evenement, function(item) {
                event_selector.append($("<option />").val( dataJSON.evenements.evenement[item].id ).text(dataJSON.evenements.evenement[item].titre));
            });

            $.each(dataJSON.evenements.organismes, function(item) {
                organisme_selector.append($("<option />").val( dataJSON.evenements.organismes[item].id ).text(dataJSON.evenements.organismes[item].nom));
            });

            event_selector.prop("selectedIndex", 0);
            organisme_selector.prop("selectedIndex", 0);

            $("#refresh_event").hide();
        }

        if(typeof(dataJSON.evenement) != 'undefined'){
            var session_selector = $("#id_session");
            //don't forget error handling!
            session_selector.empty();
            $.each(dataJSON.evenement.sessions, function(item) {
                session_selector.append($("<option />").val( dataJSON.evenement.sessions[item].id ).text(dataJSON.evenement.sessions[item].titre));
            });

            session_selector.prop("selectedIndex", 0);

            data_event = dataJSON.evenement;

            $("#refresh_event").show();
        }

        //timeline.changeItem(ref_timeline, {
        //    'id': dataJSON.id
        //});
    });
}

function slide_selector(){
    console.log('SELECTEUR DE SLIDES');   
}



/**
 * fonction pour créer la preview d'un média (image ou vidéo)
 * au chargement ou au rafraichissement
 * si le media est une vidéo, mets à jour la durée
 * @param {ref}
 * @param {file}
 * @param {ext}
 */
function preview_media(ref, file, ext){
    console.log('PREVIEW');
    delete(ref.parent().prev().find('video'));
    ref.parent().prev().html("");            
    ref.parent().prev().empty();
    $('input[name="'+ref.data('old-name')+'-duration"]').remove();

    //console.log(ref.parent().prev());

    if($.inArray(ext, $videoExt) != -1){
        console.log('video');
        if(file!='undefined'){
            ref.parent().prev().html("<video style='max-width:100%;height:auto;' onClick='play();' onLoad='dureeVideo();' src='../slides_images/"+ file +"'></video>");
            dureeVideo(ref);
        }
    }else{
        console.log('image');
        if(file!='undefined'){
            ref.parent().prev().html("<img src='../slides_images/"+ file +"' class='vignette'/>");
        }
    }
    //$.fancybox.update();
}


/**
 * FONCTION POUR CALCULER LA DUREE D'UNE VIDEO
 * @param {ref}
 */
function dureeVideo(ref){
    //console.log( "durée : " + $(this).parent().prev().find('video').duration );
    
    ref.parent().prev().find('video')
    .bind("loadedmetadata", function(e){
        e.preventDefault();

        $(this).after('<p>durée : '+ (Math.round(this.duration*100)/100) +' sec</p>');

        ref.parent().before( '<input type="hidden" name="'+ref.data('old-name')+'-duration" value="'+this.duration+'">' );

        console.log("vidéo chargée : " + this.duration);
    });
}

/**
 * fonction de conversion des éléments d'un formuaire en un objet JSON du type
 * { nom : valeur , nom2 : valeur2 }
 * @param {data} : un tableau d'objets issus d'un formulaire normalisées avec .serializeArray()
 * @return {retour} un objet javascript { nom : valeur , nom2 : valeur2 }
 */
function formToJSON( data ) {
    // on crée l'objet retour
    var retour = {};

    $.each(data, function(i,ligne){

        retour[ligne.name] = ligne.value;
    });
    return retour;
}


/**
 * ajout d'une méthode addHours à l'objet Date
 * permet d'additionner des heures à une date donnée
 * notamment pour corriger les Date avec des décalages horaire
 * @param int h le nombre d'heures à ajouter (peut être négatif)
 * @return date copiedDate un objet Date
 */
Date.prototype.addHours= function(h){
    var copiedDate = new Date(this.getTime());
    copiedDate.setHours(copiedDate.getHours()+h);
    return copiedDate;
}

/**
 * ajout d'une méthode addSeconds à l'objet Date
 * permet d'additionner des secondes à une date donnée
 * notamment pour mettre à jour les vidéos
 * @param int s le nombre d'heures à ajouter (peut être négatif)
 * @return date copiedDate un objet Date
 */
Date.prototype.addSeconds= function(s){
    var copiedDate = new Date(this.getTime());
    copiedDate.setSeconds(copiedDate.getSeconds()+s);
    return copiedDate;
}


/**
 * sert à récupérer les variables GET en javascript
 * @return {[type]} [description]
 */
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}


/**
 * cleanArray removes all duplicated elements
 * @param  {[type]} array [description]
 * @return {[type]}       [description]
 */
function cleanArray(array) {
  var i, j, len = array.length, out = [], obj = {};
  for (i = 0; i < len; i++) {
    obj[array[i]] = 0;
  }
  for (j in obj) {
    out.push(j);
  }
  return out;
}


//console.log = function() {};