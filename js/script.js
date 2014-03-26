/**
 * SCRIPT POUR LE BACKOFFICE PLASMA
 */

// accents
// http://www.pjb.com.au/comp/diacritics.html

var timestamp = new Date().getTime();

var timeline;
var dataTimeline;
var timeline_selected_item;

var seqItemNbr = 0;
var seqItemSelected;

var pixOneSecond=1/60;
var sequencePixOneSecond=1/60;

(function(window,undefined){

    // Establish Variables
    var
        State = History.getState(),
        $log = $('#log');

    // Log Initial State
    History.log('initial:', State.data, State.title, State.url);

    // Bind to State Change
    History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
        // Log the State
        var State = History.getState(); // Note: We are using History.getState() instead of event.state
        //History.log('statechange:', State.data, State.title, State.url);
        //History.log('statechange:', State.url);
        //
        //History.log(getUrlVars());
        if( getUrlVars().id_slide > 0 ){
            $item = $('.edit_slide[data-id-slide="'+getUrlVars().id_slide+'"]');
            edit_slide($item.data('id-slide'), $item.data('template'), $item.data('title'),'slide');
        }else if(getUrlVars().page == 'slides_select'){
            $.fancybox.close();
        }
    });

})(window);

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
    /*$('#add_screen').click(function(e){
        addScreen();
    });*/

    // activation du datepicker jquery
    $(function() {
        $(".date").datepicker({
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            dayNamesMin : ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            monthNames : ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre']
        });
    });
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
            url     :"../ajax/publish-group.php",
            type    : "GET",
            dataType:'json',
            data    : {
                id_groupe : getUrlVars().id_groupe,
                publish  : 'groupe'
            }
        }).done(function ( dataJSON ) {
            console.log(dataJSON);
            $('#last_publication').text(dataJSON.last_publication);
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
        //edit_slide($(this).data('id-slide'), $(this).data('template'), $(this).data('title'),'slide');
        
        History.pushState(null, null, "?page=slides_select&id_slide="+$(this).data('id-slide'));
        e.preventDefault();
    });

    //console.log('edit slide '+getUrlVars().id_slide);
    if( getUrlVars().id_slide > 0 ){
        $item = $('.edit_slide[data-id-slide="'+getUrlVars().id_slide+'"]');
        edit_slide($item.data('id-slide'), $item.data('template'), $item.data('title'),'slide');
    }

    

    slide_preview();


    /**
     * CREATION DE LA TIMELINE
     * on vérifie la présence d'une balise de timeline
     */    
    if($('#dateTimeline').length != 0){
        drawTimeline();
    }


    /**
     * GESTION DES ITEMS SEQUENTIELS
     * on vérifie que la timeline par séquence existe bien
     */
    if($('#sequenceTimeline').length != 0){
        // Pour ajouter un item
        $('#sequenceTimeline').dblclick(function(e){

            var ordre = parseInt($('.sequence-item').length)+1;

            $.ajax({
                url         : "../ajax/data-sequence-item.php",
                type        : "POST",
                data        : {
                    id_groupe:  $id_groupe,
                    ordre:      ordre ,
                    titre :     'Nouveau',
                    action:     'create-item'
                },
                dataType    : 'json'
            }).done(function ( dataJSON ) {
                // duree par default 10secondes
                addSequenceSlide(dataJSON.id,'Nouveau',0,10,'default','unpublished default');
            });
        })
        .click(function(e){
            unselectSequenceItem();
            seqItemSelected = undefined;
        });

        // GGestion du tri
        $( "#sequenceContainer" ).sortable({
            axis:   'x',
            cancel: '.suppr-item',
            update: function( event, ui ) {
                $.ajax({
                    url         : "../ajax/data-sequence-item.php",
                    type        : "POST",
                    data        : {
                        action: 'sort-item',
                        id_tab: JSON.stringify($( "#sequenceContainer" ).sortable( "toArray", { attribute : 'data-id' } )),
                    },
                    dataType    : 'json'
                }).done(function ( dataJSON ) {

                    console.log('RETOUR TRI : ');
                    console.log(dataJSON.message);
                });
            },
            start: function( event, ui ) {
                $('.suppr-item').remove();
            }
        });
        //$('#sequenceContainer li' ).disableSelection();
        $('#sequenceTimeline').disableSelection();
        $('#sequenceContainer').disableSelection();


        // chargement des items sequentiels
        $.ajax({
            url         : "../ajax/data-sequence.php",
            type        : "GET",
            data        : {cache : timestamp, id_groupe: $id_groupe},
            dataType    : 'json'
        }).done(function ( dataJSON ) {
            $.each(dataJSON, function(item) {
                addSequenceSlide( dataJSON[item].id, dataJSON[item].titre, dataJSON[item].id_slide, dataJSON[item].duree, dataJSON[item].template, dataJSON[item].class );
            });
        });
    }

    // gestion du zoom sur les slides séquentiels :
    $('#sequenceTimeline').mousewheel(function(event, delta, deltaX, deltaY){

        //sequencePixOneSecond = pixelRange/timeRange*1000;
        //
        if(Math.abs(deltaY)>Math.abs(deltaX)){
            sequencePixOneSecond = sequencePixOneSecond+deltaY/200;
            if(sequencePixOneSecond<1/30){
                sequencePixOneSecond = 1/30;
            }
            /*console.log(sequencePixOneSecond);*/
            refreshSequenceSlide();

            event.preventDefault();
        }
    })
});


function unselectSequenceItem(){
    seqItemSelected = undefined;
    $('.suppr-item').remove();
    $('.selected').removeClass('selected');
}

/**
 * Fonction servant à ajouter des items séquentiels triés par ordre
 * @param {[int]}       id        id de l'item
 * @param {[string]}    titre     titre du slide à afficher
 * @param {[int]}       id_slide  id du slide à afficher
 * @param {[int]}       duree     durée de l'item en secondes
 * @param {[string]}    template  template du slide à afficher
 * @param {[string]}    className les classes à affecter (published + template)
 */
function addSequenceSlide(id,titre,id_slide,duree,template,className){

    // conteneur du titre
    $content = $("<div/>")
    .addClass('timeline-event-content')
    .text(titre);

    // dic de l'item
    $item = $("<div/>")
    .append($content)
    .addClass('sequence-item '+className)
    .attr('id','sequence-item'+id)
    .attr('data-id', id)
    .data('duree', duree)
    .data('id_slide', id_slide)
    .data('template', template)
    .data('titre', titre)
    .width(pixOneSecond*duree)
    .click(function(e){
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        console.log(this);
        e.cancelBubble = true;
        unselectSequenceItem();
        // on séléctionne l'item
        seqItemSelected = $(this);
        sequenceSlideSupressing();
    })
    .dblclick(function(e){
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        e.cancelBubble = true;
        unselectSequenceItem();
        // on séléctionne l'item
        seqItemSelected = $(this);
        sequenceSlideSupressing();
        edit_item_sequence();
    });

    $('#sequenceContainer').append($item);
    // on rafraichit les dimentions des items
    refreshSequenceSlide();
}

/**
 * [sequenceSlideSupressing description]
 * @return {[type]} [description]
 */
function sequenceSlideSupressing(){
    seqItemSelected.addClass('selected');

    $('<div/>')
    .addClass('suppr-item')
    .click(function(e){
        seqItemSelected.remove();
        $.ajax({
            url         : "../ajax/data-sequence-item.php",
            type        : "POST",
            data        : {
                id:         seqItemSelected.data('id'),
                titre :     seqItemSelected.find('.timeline-event-content').text(),
                action:     'delete-item'
            },
            dataType    : 'json'
        }).done(function ( dataJSON ) {

            //console.log('OK OK sequence : ');
            console.log(dataJSON);
            //seqItemSelected.remove();
            unselectSequenceItem();

            $.ajax({
                url         : "../ajax/data-sequence-item.php",
                type        : "POST",
                data        : {
                    action: 'sort-item',
                    id_tab: JSON.stringify($( "#sequenceContainer" ).sortable( "toArray", { attribute : 'data-id' } )),
                },
                dataType    : 'json'
            }).done(function ( dataJSON ) {

                //console.log('RETOUR TRI : ');
                //console.log(dataJSON.message);
            });
        });
    })
    .insertAfter(seqItemSelected);
}

/**
 * [refreshSequenceSlide description]
 * @return {[type]} [description]
 */
function refreshSequenceSlide(){

    var largeur =0;
    $('.sequence-item').each(function(){
        $(this).width(sequencePixOneSecond*$(this).data('duree'));
        largeur += $(this).outerWidth(true);
    });

    largeur+=100;

    //console.log(largeur);

    $('#sequenceContainer').width(largeur);
}


/**
 * [edit_item_sequence description]
 * @return {[type]} [description]
 */
function edit_item_sequence(){
    //console.log('Edit item From Sequence');

    item_sequence_info = {
        slide_id:  seqItemSelected.data('id_slide'),
        content:   seqItemSelected.text(),
        duree:     seqItemSelected.data('duree'),
        dureeHMS:  second2HMS(seqItemSelected.data('duree')),
        template:  seqItemSelected.data('template')
    };

    item_sequence_editor = ich.item_sequence_editor(item_sequence_info);

    $.fancybox( item_sequence_editor , {
        helpers : {
            title: {
                type: 'inside',
                position: 'top'
            }
        }
    });

    $('#published').attr('checked', seqItemSelected.hasClass("unpublished") ? false : true);

    $( "#duree" )
    .spinner({
        min: 60,
        step: 30,
        spin: function(){
            $('#dureeHMS').text( second2HMS( parseInt($( "#duree" ).val()) ) );
        }
    })
    .change(function() {
        $('#dureeHMS').text( second2HMS( parseInt($( "#duree" ).val()) ) );
    });

    $template = seqItemSelected.data('template');
    
    // ------------------------------------
    slide_preview();
    // ------------------------------------
    slide_selector_refresh = true;
    slide_selector_from = 'sequence';
    slide_selector();

    // sauvegarde d'un item
    $('#save_sequence_item').click(function(e){
        e.preventDefault();

        var titre_slide = $('#template_reference').val() == 'meteo' ? "Météo" : $('#id_slide option:selected').text();
        var template    = $('#template_reference').val();
        var duree       = $('#duree').val();
        var id_slide    = $('#id_slide').val();

        //console.log('SAVE template '+template);

        $('#item_title').text(titre_slide);
        $('.slide_view').data('id-slide',id_slide);
        $('.slide_view>a').attr('href', $('.slide_view').data('absolute-url')+'slideshow/?slide_id='+id_slide+'&template='+template);

        //------------------------
        // on réattribue les classes
        //------------------------        
        var classes = seqItemSelected.attr('class');
        classes = Array('sequence-item');

        if($("#published").is(':checked')){
            // rien
            var published = 1;
        }else{
            classes.push('unpublished');
            var published = 0;
        }
        classes.push(template);
        classes = cleanArray(classes);
        classes = classes.join(' ');

        // on met à jour l'item de la timeline
        seqItemSelected
        .attr('class',classes)
        .data('duree', duree)
        .data('id_slide', id_slide)
        .data('titre', titre_slide)
        .data('template', template)
        .find('div.timeline-event-content')
        .text(titre_slide);

        $.ajax({
            url         : "../ajax/data-sequence-item.php",
            type        : "POST",
            data        : {
                id:         seqItemSelected.data('id'),
                id_slide:   id_slide,
                titre :     titre_slide,
                template :  template,
                duree :     duree,
                published:  published,
                action:     'update-item'
            },
            dataType    : 'json'
        }).done(function ( dataJSON ) {

            //console.log('OK OK sequence : ');
            //console.log(dataJSON);
            refreshSequenceSlide();
        });
    });

    // gestion de l'édition de contenu
    $('#edit_slide_content').click(function(e){

        //console.log(">>> EDIT SLIDE CONTENT : "+ seqItemSelected.data('id') + " ID_SLIDE : "+ seqItemSelected.data('id_slide'));

        edit_slide(seqItemSelected.data('id_slide'), $('#template_reference').val(), seqItemSelected.data('titre'), 'sequence',seqItemSelected.data('id'));     
        
        e.preventDefault();

    });
}



/**
 * ------------------------------------
 * POUR GERER LA TIMELINE
 * ------------------------------------
 */




/**
 * génère l'affichage de la timeline
 * après récupération des données dans ../ajax/data-timeline.php
 * attache les écouteurs nécessaires
 */
function drawTimeline() {

    // créé l'objet timeline en instanciant le DIV correspondant
    timeline = new links.Timeline(document.getElementById('dateTimeline'));
    
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
        'max' : new Date().addMonth(3),     // on peut afficher des évenements 3 mois après la date actuelle
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
                    start   : new Date( dataTimeline[row].start ).addHours(GMTcorrection),
                    end     : new Date( dataTimeline[row].end   ).addHours(GMTcorrection),
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

            $.ajax({
                url     :"../ajax/data-timeline-item.php",
                type    : "POST",
                dataType:'json',
                data    : {
                    id      : dataTimeline[row].id,
                    id_slide: dataTimeline[row].id_slide,
                    id_group: $id_groupe,
                    titre   : dataTimeline[row].content,
                    start   : new Date(dataTimeline[row].start).addHours(GMTcorrection),
                    end     : new Date(dataTimeline[row].end).addHours(GMTcorrection),
                    group   : dataTimeline[row].group,
                    template: dataTimeline[row].template,
                    published : dataTimeline[row].className.indexOf("unpublished") < 0 ? 1 : 0,
                    action  : 'update-item'
                }
            }).done(function ( dataJSON ) {
                //console.log("Save change : "+dataJSON);
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
            //console.log('ONDELETE id : '+ dataTimeline[row].id + ' content : '+ dataTimeline[row].content);
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
            //console.log(' ');
            //console.log("ONSELECT id : " + dataTimeline[row].id + " id_slide : "  + dataTimeline[row].id_slide + " classes : "+dataTimeline[row].className);
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

                timeline_selected_item = row;
                
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
        // permettait d'asservir le zoom de la timeline sequentielle
        /*var pixelRange = $('.timeline-axis').width();
        var timeRange = event.end-event.start;
        pixOneSecond = pixelRange/timeRange*1000;

        refreshSequenceSlide();*/

        //console.log( "1 seconde = "+ pixOneSecond +" pixels");
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
    console.log('Edit item From Timeline');

    var date1 = new Date(dataTimeline[ref].start);
    var date2 = new Date(dataTimeline[ref].end);
    var duree = Math.round((date2-date1)/1000);
    
    // on crée l'objet qui va récupérer les informations d'un item 
    slide_info = {
        slide_id:  dataTimeline[ref].id_slide,

        group:     dataTimeline[ref].group,
        content:   dataTimeline[ref].content,
        start:     dataTimeline[ref].start,
        end:       dataTimeline[ref].end,

        duree:     second2HMS(duree),
        template:  dataTimeline[ref].template,

        startDate : date2mysql(date1),
        endDate : date2mysql(date2),   

        group_selector: screen_list,
        //template_selector:template_list, 

    };

    console.log("ID_SLIDE : "+dataTimeline[ref].id_slide);

    slide_editor = ich.slide_editor(slide_info);

    $.fancybox( slide_editor , {
        //title : '<h1>Éditeur de slide</h1>',
        helpers : {
            title: {
                type: 'inside',
                position: 'top'
            }
        }
    });

    // on selectionne le groupe quand on affiche le formulaire
    $('#screen_reference').val(dataTimeline[ref].group);
    //$('#template_reference').val(dataTimeline[ref].template);
    $('#published').attr('checked', dataTimeline[ref].className.indexOf("unpublished") < 0 ? true : false);

    //alert( dataTimeline[ref].className.indexOf("unpublished") >= 0 ? 1 : 0) ;
    
    // ------------------------------------
    slide_preview();
    // ------------------------------------
    slide_selector_refresh = true;
    slide_selector_from = 'timeline';
    slide_selector();


    // sauvegarde d'un item
    $('#save_item').click(function(e){
        e.preventDefault();

        var group       = $('#screen_reference').val();
        var content     = $('#template_reference').val() == 'meteo' ? "Météo" : dataTimeline[ref].content;
        var template    = $('#template_reference').val();
        var id_slide    = $('#id_slide').val();
        if(template!='meteo'){
            var titre_slide = $('#id_slide option:selected').text();
        }else{
            var titre_slide = "Météo";
        }

        console.log('SAVE template '+template);

        $('#item_title').text(titre_slide);
        $('.slide_view').data('id-slide',id_slide);
        $('.slide_view>a').attr('href', $('.slide_view').data('absolute-url')+'slideshow/?slide_id='+id_slide+'&template='+template);

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
            'template' :    template,
            'id_slide' :    id_slide,
            'content' :     titre_slide
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
                start   : new Date(dataTimeline[ref].start).addHours(GMTcorrection),
                end     : new Date(dataTimeline[ref].end).addHours(GMTcorrection),
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

        //actionAfterClose = 'edit_slide';
        //$.fancybox.close();

        edit_slide(dataTimeline[ref].id_slide, $('#template_reference').val(), dataTimeline[ref].content, 'timeline',ref);     
        e.preventDefault();

    });

    $('#publish_slide').click(function(e){

        console.log("published : "+ ($("#published").is(':checked') ? 1 : 0));
        e.preventDefault();

    });
}

/**
 * [slide_preview description]
 * @return {[type]} [description]
 */
function slide_preview(){

    $(".slide_view>a").mouseenter(function(){
        console.log('ok');

        console.log('preview : '+$(this).attr('href')+"&preview&tiny");

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
function edit_slide(id_slide,template,titre,edit_from,ref_item){
    
    // on vide le formulaire
    $("#myform").empty();
    //console.log("EDIT SLIDE : from "+edit_from+" : "+id_slide+" "+template+" "+titre);

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
            afterClose : function(){
                if( edit_from != 'sequence' && edit_from != 'timeline'){
                    History.pushState(null, null, "?page=slides_select");
                }
            }
        });

        // TITRE DE LA FANCYBOX
        $(".fancybox-title h1").text( 'Éditeur de ' + $('#myform').attr('title') );

        // ON AJOUTE LE TITRE DU SLIDE QU'ON EDITE
        $(".fancybox-inner").prepend("<label>Titre du slide</label><input type='text' id='slide_title' value='"+titre+"'/>");

        // ON AJOUTE LE GESTIONNAIRE D'ÉVÉNEMENTS SI NÉCESSAIRE (template = evenements)
        if(template == 'evenements'){
            event_selector();
        }

        // ON AJOUTE L'ALERTE DE SAUVEGARDE
        $(".fancybox-inner #myform").append("<div class='save_valid'>Sauvegarde effectuée</div>");

        // ON AJOUTE LES BOUTONS
        $(".fancybox-inner #myform").append("<button id='save_slide'>Sauvegarde</button>");

        // ON AFFICHE OU MASQUE LE BOUTON DE RETOUR À L'ITEM
        if(typeof(ref_item) != 'undefined'){
            $(".fancybox-inner #myform").append("<button id='back_to_item'>Retour à l’item</button>");
            $("#back_to_item").click(function(e){
                e.preventDefault();

                if(edit_from=='timeline'){
                    edit_item(ref_item);
                }
                else if(edit_from=='sequence'){
                    edit_item_sequence();
                }
            });
        }

        // POUR DÉSACTIVER LE CLIC EXTÉRIEUR SUR FANCYBOX
        $(".fancybox-overlay").unbind();

        // ON ITNIALISE TINYMCE
        tinymce.init({
            selector: "textarea",
            toolbar: " ",
            menubar : false,
            entity_encoding : 'raw',
            plugins: "paste",
            paste_as_text: true,
        });

        // ACTIVATION DE UPLOADIFIVE
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

                    console.log('uploadifive INIT :');
                    console.log(this);
                    
                    // on affiche une vignette ou un texte si on trouve un média image ou vidéo
                    //$('input[type="file"]').each(function(){
                        if( $(this).data('file') != undefined ) {

                            var temp = $(this).data('file').split('.');
                            var ext = temp[temp.length-1].toLowerCase();
                            
                            $(this).before("<p>"+ $(this).data('file') + "</p>");
                            $(this).parent().before("<div class='preview'></div>")

                            preview_media($(this), $(this).data('file'), ext);
                        }
                    //});

                    var nom = $(this).attr('name');
                    $(this).attr('name', nom + "-old");
                    $(this).data('old-name', nom);
                    $(this).after( "<input type='hidden' name='" + nom + "' value='"+ $(this).data('file') +"'/>" );

                },
                'onUploadComplete'  : function(file,data) {

                    console.log('uploadifive COMPLETE :');
                    console.log(this);

                    info = JSON.parse(data);
                    if(!info.error){
                        $('input[name="'+ $(this).data('old-name') +'"]' ).val( info.file );

                        preview_media($(this),info.file,info.ext);

                        //console.log("upload finished : "+info.file +" / type : "+info.ext);
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

            var duration = parseInt( $('#myform input[name*="duration"]').val() ) + 3;
            console.log("Durée de la vidéo : "+duration);
            
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

                // affichage de la validation de sauvegarde
                $(".save_valid")
                .animate({
                    opacity : 1
                },'slow')
                .delay(300)
                .animate({
                    opacity : 0
                },'slow');


                // SI ON EDITE DEPUIS DE LISTING DE SLIDES
                if(edit_from == 'slide'){
                    console.log('changement de nom : '+$("#slide_title").val());
                    //$('#news_listing>div .liens button[data-id-slide="'+id_slide+'"]').parent().parent().find('.titre a').text( $("#slide_title").val() );
                    $('#titre-slide-'+id_slide).text( $("#slide_title").val() );

                    $('.edit_slide[data-id-slide="'+id_slide+'"]').data('title',$("#slide_title").val() );
                }

                // SI ON EDITE DEPUIS LA TIMELINE
                else if(edit_from == 'timeline' && typeof(ref_item) != 'undefined'){

                    id_slide = dataJSON.id;
                    type_action = 'update-slide';

                    // on modifie les propriétés de l'item
                    timeline.changeItem(ref_item, {
                        'id_slide': dataJSON.id,
                        'content' : $("#slide_title").val(),
                        'template': template,
                        'end'     : duration>0 ? new Date(dataTimeline[ref_item].start).addSeconds(duration) : new Date(dataTimeline[ref_item].end)
                    });

                    // on sauvegarde les modifications en base de donnée
                    $.ajax({
                        url     :"../ajax/data-timeline-item.php",
                        type    : "POST",
                        dataType:'json',
                        data    : {
                            id      : dataTimeline[row].id,
                            id_slide: dataTimeline[row].id_slide,
                            id_group: $id_groupe,
                            titre   : dataTimeline[row].content,
                            start   : new Date(dataTimeline[row].start).addHours(GMTcorrection),
                            end     : new Date(dataTimeline[row].end).addHours(GMTcorrection),
                            group   : dataTimeline[row].group,
                            template: dataTimeline[row].template,
                            published : dataTimeline[row].className.indexOf("unpublished") < 0 ? 1 : 0,
                            action  : 'update-item'
                        }
                    }).done(function ( dataJSON ) {
                        //console.log(dataJSON);
                    });
                }

                // SI ON EDITE DEPUIS LA BARRE SEQUENTIELLE
                else if(edit_from == 'sequence' && typeof(seqItemSelected) != 'undefined'){
                    
                    if(seqItemSelected.hasClass('unpublished')){
                        var published = 0;
                    }else{
                        var published = 1;
                    }

                    seqItemSelected
                    .data('id_slide', dataJSON.id)
                    .data('titre', $("#slide_title").val())
                    .data('template', template)
                    .find('div.timeline-event-content')
                    .text( seqItemSelected.data('titre') );

                    if(typeof(duration)!='undefined' && duration>0){
                        seqItemSelected.data('duree', duration);
                    }

                    $.ajax({
                        url         : "../ajax/data-sequence-item.php",
                        type        : "POST",
                        data        : {
                            id:         seqItemSelected.data('id'),
                            id_slide:   seqItemSelected.data('id_slide'),
                            titre :     seqItemSelected.data('titre'),
                            template :  seqItemSelected.data('template'),
                            duree :     seqItemSelected.data('duree'),
                            published:  published,
                            action:     'update-item'
                        },
                        dataType    : 'json'
                    }).done(function ( dataJSON ) {

                        //console.log('sequence : ');
                        //console.log(dataJSON);
                        refreshSequenceSlide();
                    });
                }
            });
        });
    });
}














/**
 * ------------------------------------
 * POUR GERER L'EDITION DES ÉVÉNEMENTS
 * ------------------------------------
 */

var data_event;
var p_year;
var p_month;
var p_id_organisme;
var p_id_event;
var p_id_session;
var issetParam = false;

/**
 * [event_selector description]
 * @return {[type]} [description]
 */
function event_selector(){
    console.log('SELECTEUR D’ÉVENEMENT');

    var d = new Date();
    var n = d.getFullYear();

    var yearArray = new Array();

    for(i=n; i>= 2014; i--){
        yearArray.push( {"key":i,"value":i} );
    }

    var data_event_selector = {
        "year_event" : yearArray,
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

    if( $('#myform input[name="session_id"]').val() > 0 ){

        console.log('id_session '+$('#myform input[name="session_id"]').val());

        $.ajax({
            url     :"../ajax/api-event.php",
            type    : "GET",
            dataType:'json',
            data    : {
                session        : $('#myform input[name="session_id"]').val()
            }
        }).done(function ( dataJSON ) {
            console.log(" ");
            console.log("retour info event :");
            console.log(dataJSON);
            
            p_year          = dataJSON.session.annee;
            p_month         = parseInt(dataJSON.session.mois);
            p_id_organisme  = parseInt(dataJSON.session.organisme_id);
            p_id_event      = parseInt(dataJSON.session.event_id);
            p_id_session    = parseInt(dataJSON.session.id);
           
            loadEventFromAPI(true);
        });
    }else{
        loadEventFromAPI();
    }

    $("#year_event").change(function(e){
        $("#id_session").empty();
         $("#id_event").empty();
        loadEventFromAPI();
    });
    $("#month_event").change(function(e){
        $("#id_session").empty();
         $("#id_event").empty();
        loadEventFromAPI();
    });
    $("#id_organisme").change(function(e){
        $("#id_session").empty();
         $("#id_event").empty();
        loadEventFromAPI();
    });
    $("#id_event").change(function(e){
        $("#id_session").empty();
        loadEventFromAPI();
    });

    $("#refresh_event").click(function(e){
        refresh_event();
    });

    var d = new Date();
    $("#year_event").val(d.getFullYear());
    $("#month_event").val(d.getMonth()+1);
}


/**
 * [refresh_event description]
 * @return {[type]} [description]
 */
function refresh_event(){
    //console.log(data_event);

    var id_session = $("#id_session option:selected").val();

    var jours = new Array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
    var mois  = new Array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');

    var d = new Date( data_event.sessions[id_session].date_debut );

    var date_event = jours[d.getDay()]+" "+ d.getDate() +" "+mois[d.getMonth()];
    var start_event = data_event.sessions[id_session].horaire_debut;
    var end_event = data_event.sessions[id_session].horaire_fin !='undefined' ? data_event.sessions[id_session].horaire_fin : '';

    start_event = start_event.split(':');
    start_event = start_event[0]+"H"+start_event[1];

    if(end_event != ''){
        end_event = end_event.split(':');
        end_event = " - " + end_event[0]+"H"+end_event[1];
    }

    $("#slide_title").val( addZeroToInt(d.getDate(),2) + '/'+ addZeroToInt((d.getMonth()+1),2) + ' ' + data_event.sessions[id_session].titre );

    //$('#myform input[name="type"]').val(            data_event);
    $('#myform input[name="date_horaire"]').val(    date_event + ", " +start_event + end_event );
    $('#myform input[name="lieu"]').val(            data_event.sessions[id_session].lieu);
    $('#myform input[name="code_batiment"]').val(   data_event.sessions[id_session].code_batiment);
    $('#myform input[name="adresse_nom"]').val(     data_event.sessions[id_session].adresse_nom);
    $('#myform input[name="adresse"]').val(         data_event.sessions[id_session].adresse);
    $('#myform input[name="langue"]').val(          data_event.sessions[id_session].code_langue);
    $('#myform input[name="titre"]').val(           data_event.sessions[id_session].titre);
    $('#myform input[name="session_id"]').val(      data_event.sessions[id_session].id);
    $('#myform input[name="organisateur"]').val(    data_event.organisateur);
    $('#myform input[name="qualite"]').val(         data_event.organisateur_qualite);
    $('#myform input[name="coorganisateur"]').val(  data_event.coorganisateur);
    $('#myform input[name="expire"]').val(          data_event.sessions[id_session].date_fin+' '+data_event.sessions[id_session].horaire_fin);

    //$('#myform input[name="image"]').val();
    
    var date_folder = data_event.sessions[id_session].date_debut.split('-');
    console.log(date_folder[0]+'/'+date_folder[1]);

    downloadEventImage(data_event.url_image, data_event.id, date_folder[1], date_folder[0]);

    $('#myform input[name="inscription"]').val(     data_event.sessions[id_session].type_inscription);

}

/**
 * [downloadEventImage description]
 * @param  {[type]} url   [description]
 * @param  {[type]} id    [description]
 * @param  {[type]} month [description]
 * @param  {[type]} year  [description]
 * @return {[type]}       [description]
 */
function downloadEventImage(url,id,month,year){
    console.log("téléchargement image : "+url);
    $.ajax({
        url     :"../ajax/event-image-download.php",
        type    : "POST",
        dataType:'json',
        data    : {
            url        : url,
            id_event   : id,
            year_event : year,
            month_event: month,
        }
    }).done(function ( dataJSON ) {
        //console.log("retour download : "+dataJSON);

        $('#myform input[name="image"]').val(dataJSON.file);

        preview_media($('#myform input[name="image-old"]'), dataJSON.file, dataJSON.ext);
    });
}

/**
 * [loadEventFromAPI description]
 * @param  {boolean} param sert à définir si on rafraichit en prennant en compte les paramètres initiaux ou les paramètres du formulaire
 * parametres -> formulaire ou
 * formulaire -> parametres
 */
function loadEventFromAPI(form2param){

    issetParam = typeof(form2param) != 'undefined' ? form2param : false;

    if(typeof(p_year)!= 'undefined' && typeof(p_month)!= 'undefined' && typeof(p_id_organisme)!= 'undefined' && typeof(p_id_event)!= 'undefined' && typeof(p_id_session)!= 'id_session' && issetParam == true ){

        var paramObj = {
            year        : p_year,
            month       : p_month,
            id_organisme: p_id_organisme,
            lang        : "fr"
            //id_event    : p_id_event
        }

    }else{

        var paramObj = {
            year        : $("#year_event").val(),
            month       : $("#month_event").val(),
            id_organisme: $("#id_organisme").val(),
            lang        : "fr",
            id_event    : $("#id_event").val()
        }
    }


    $.ajax({
        url     :"../ajax/api-event.php",
        type    : "GET",
        dataType:'json',
        data    : paramObj

    }).done(function ( dataJSON ) {
        console.log('date event reçues');

        //info = JSON.JSONparse(dataJSON);
        //console.log(dataJSON.evenements.organismes);
        //console.log(dataJSON.evenements.evenement);

        // Liste des événements et leur sessions attachées, sur un mois et pour un organisme donné
        if(typeof(dataJSON.evenements) != 'undefined'){

            $("#id_organisme").empty();
            $.each(dataJSON.evenements.organismes, function(item) {
                $("#id_organisme")
                .append($("<option />")
                .val( dataJSON.evenements.organismes[item].id )
                .text(dataJSON.evenements.organismes[item].nom));
                $("#id_session").prop("selectedIndex", 0);
            });

            $("#id_event").empty();
            $.each(dataJSON.evenements.evenement, function(item) {

                var date_debut = dataJSON.evenements.evenement[item].date.split('-');
                var jour = date_debut[2];

                $("#id_event").append($("<option />")
                .val( dataJSON.evenements.evenement[item].id )
                .text(jour + ' ' +dataJSON.evenements.evenement[item].titre));

                // si on se base sur les paramètres sauvegardés alors on sélectionne la session
                if(issetParam){

                    if(dataJSON.evenements.evenement[item].id == p_id_event){

                        $("#id_session").empty();
                        $.each(dataJSON.evenements.evenement[item].sessions, function(itemsession){

                            $("#id_session")
                            .append($("<option />")
                            .val( dataJSON.evenements.evenement[item].sessions[itemsession].id )
                            .text(dataJSON.evenements.evenement[item].sessions[itemsession].titre));
                        });
                        $("#year_event").val( p_year );
                        $("#month_event").val( p_month );
                        $("#id_organisme").val( p_id_organisme );
                        $("#id_event").val(p_id_event);

                        $("#refresh_event").show();

                        $("#id_session").val(p_id_session);

                        loadEventFromAPI();
                    }
                }
            });

            var selectOptions = $("#id_event option");
            var selectedOption = $('#id_event').val();
            selectOptions.sort(function(a, b) {
                if (a.text > b.text) {
                    return 1;
                }
                else if (a.text < b.text) {
                    return -1;
                }
                else {
                    return 0
                }
            });
            $("#id_event").empty().append(selectOptions);
            $('#id_event').val(selectedOption);

            console.log('issetParam ' + issetParam);

            if(!issetParam && form2param != true){
                console.log('form2param :');
                console.log(form2param);
                console.log('pas de paramètres');

                $("#id_event").val($("#id_event option:first").val());

                p_id_event = $('#id_event').val();

                loadEventFromAPI(true);

            }
    
        }

        // détail d'un évéenement et des sessions attachées
        if(typeof(dataJSON.evenement) != 'undefined'){

            $("#id_session").empty();
            $.each(dataJSON.evenement.sessions, function(item) {
                $("#id_session").append($("<option />").val( dataJSON.evenement.sessions[item].id ).text(dataJSON.evenement.sessions[item].titre));
            });

            data_event = dataJSON.evenement;

            $("#refresh_event").show();
        }
    });
}













/**
 * -----------------------------------
 * POUR GERER LA SELECTION DES SLIDES
 * -----------------------------------
 */

var slide_selector_refresh = false;
var slide_selector_from = 'timeline';
/**
 * gestion du formulaire pour sélectionner les slides
 * @return {[type]} [description]
 */
function slide_selector(){

    if(slide_selector_from == 'timeline'){
        id = dataTimeline[timeline_selected_item].id_slide;
        $template = dataTimeline[timeline_selected_item].template;
    }else if(slide_selector_from = 'sequence'){
        id = seqItemSelected.data('id_slide');
        $template = seqItemSelected.data('template');
    }
    //console.log('SELECTEUR DE SLIDES from '+slide_selector_from+': '+id);
    //console.log($("#template_reference").val()+" "+ parseInt($("#mois_slide").val()) +" "+ parseInt($("#annee_slide").val()) + " "+slide_selector_refresh);

    $.ajax({
        url     :"../ajax/data-timeline-slide.php",
        type    : "POST",
        dataType:'json',
        data    : {
            action                  : 'get_select_info',
            id_slide                : id,
            template                : $("#template_reference").val(),
            annee                   : parseInt($("#annee_slide").val()),
            mois                    : parseInt($("#mois_slide").val()),
            slide_selector_refresh  : slide_selector_refresh
        }
    }).done(function ( dataJSON ) {
        //console.log(' ');
        //console.log('Info Slide :');
        //console.log(dataJSON);


        if(slide_selector_refresh == true){

            $('#annee_slide').empty();
            $.each(dataJSON.annees, function(item) {
                $("#annee_slide").append($("<option />").val( dataJSON.annees[item] ).text( dataJSON.annees[item] ));
            });
            $('#mois_slide').empty();
            $.each(dataJSON.mois, function(item) {
                $("#mois_slide").append($("<option />").val( item ).text( dataJSON.mois[item] ));
            });
            $('#template_reference').empty();
            $.each(dataJSON.templates, function(item) {
                $("#template_reference").append($("<option />").val( dataJSON.templates[item] ).text( dataJSON.templates[item] ));         
            });
        }

        $('#id_slide').empty();
        $.each(dataJSON.liste_slides, function(item) {
            $("#id_slide").append($("<option />").val( item ).text( dataJSON.liste_slides[item].nom ));
           
        });

        if(typeof(dataJSON.slide_info) != 'undefined' && slide_selector_refresh == true){
            console.log("SLIDE DEFINI : "+ dataJSON.slide_info.template);
            $("#annee_slide").val(parseInt(dataJSON.slide_info.annee));
            $("#mois_slide").val(parseInt(dataJSON.slide_info.mois));
            $("#template_reference").val(dataJSON.slide_info.template);
            $("#id_slide").val(parseInt(dataJSON.slide_info.id));

            $('.slide_view').data('id-slide',dataJSON.slide_info.id);
            $('.slide_view>a').attr('href', $('.slide_view').data('absolute-url')+'slideshow/?slide_id='+dataJSON.slide_info.id+'&template='+dataJSON.slide_info.template);

        }else if(typeof(dataJSON.slide_info) == 'undefined' && slide_selector_refresh == true){
            console.log("SLIDE INDEFINI : "+$template );

            if($template == 'meteo'){
                $("#template_reference").val($template);
                $('.nometeo').hide();
            }else{
                $("#template_reference").prop("selectedIndex", 0);
                $('.nometeo').show();
            }

            $("#annee_slide").prop("selectedIndex", 0);
            $("#mois_slide").prop("selectedIndex", 0);
            
            $("#id_slide").prop("selectedIndex", 0);

            slide_selector_refresh = false;
            slide_selector(timeline_selected_item);
        }


        $('#annee_slide').change(function(e){
            slide_selector_refresh = false;
            slide_selector(timeline_selected_item);
        });
        $('#mois_slide').change(function(e){
            slide_selector_refresh = false;
            slide_selector(timeline_selected_item);
        });
        $('#template_reference').change(function(e){
            slide_selector_refresh = false;
            slide_selector(timeline_selected_item);

             if($(this).val() == 'meteo'){
                $('.nometeo').hide();
            }else{
                $('.nometeo').show();
            }

            //on mémorise le gabarit sélectionné
            $template = $(this).val();
        });
        $('#id_slide').change(function(e){
            //slide_selector_refresh = false;
            //slide_selector(timeline_selected_item);
            console.log($('#id_slide').val()+" "+$('#id_slide option:selected').text());
        });
    }); 
}














/**
 * -------------------
 * FONCTIONS DIVERSES
 * -------------------
 */


function supprEcran(id, nom){

    if(confirm("Attention vous allez suprimer l'écran « "+nom+" », cette action est irréversible et entrainera la suppression des données associées. Souhaitez vous continuer ?")){

        $DOM_suppr_ecran = $(event.target).parents('.ecran');

        $.ajax({
            url     :"../ajax/ecran-suppr.php",
            type    : "POST",
            dataType:'json',
            data    : {
                id          : id,
                suppr_ecran : 'true'
            }
        }).done(function ( dataJSON ) {

            alert('Écran supprimé!');

            $DOM_suppr_ecran.remove();
        });
    }

    event.preventDefault();
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
 * ajout d'une méthode addMonth à l'objet Date
 * permet d'additionner des mois à une date donnée
 * @param int m le nombre de mois à ajouter (peut être négatif)
 * @return date copiedDate un objet Date
 */
Date.prototype.addMonth= function(m){
    var copiedDate = new Date(this.getTime());
    copiedDate.setMonth(copiedDate.getMonth()+m);
    return copiedDate;
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

/**
 * [date2mysql description]
 * @param  {[type]} date [description]
 * @return {[type]}      [description]
 */
function date2mysql(date){
    annee   = date.getFullYear();
    mois    = date.getMonth() + 1 <10 ? "0"+(date.getMonth() + 1): date.getMonth() + 1;
    jour    = date.getDate() <10      ? "0"+date.getDate()       : date.getDate();
    heure   = date.getHours()<10      ? "0"+date.getHours()      : date.getHours();
    minute  = date.getMinutes()<10    ? "0"+date.getMinutes()    : date.getMinutes();
    seconde = date.getSeconds()<10    ? "0"+date.getSeconds()    : date.getSeconds();

    return annee+"-"+mois+"-"+jour+" "+heure+":"+minute+":"+seconde;
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
 * [addZeroToInt description]
 * @param {[type]} nbr  [description]
 * @param {[type]} rang [description]
 */
function addZeroToInt(nbr, rang){

    nbr = parseInt(nbr);
    var zero = '';
    for(i=0; i<rang; i++){
        zero += '0';
    }
    nbr = zero + nbr;
    return nbr.substr(-rang);
}


//console.log = function() {};