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

    $.getJSON('../ajax/slide-data.js', function(data) {
        console.log("C : "+data.C);
    });

    // TEST D'INCLUSION DE TEMPLATE
    var timestamp = new Date().getTime();
    $.ajax({
        url: "../slides_templates/compte_a_rebours/structure.js?cache="+timestamp,
    }).done(function ( data ) {
        //console.log(dataJSON);
        eval(data);      
        console.log("OK template ready");
        console.log(structure.date);
        ich.addTemplate('compte_a_rebour',template);
    });
    
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
        //'start': new Date(2012, 0, 1),
        //'end': new Date(2012, 11, 31),
        'cluster': true,
        'axisOnTop': true,
        'zoomMin' : 1000*60*60,
        'min' : new Date(2012, 0, 1),
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

    // charge en AJAX le contenu du fichier data.js
    var timestamp = new Date().getTime();
    $.ajax({
        url: "../ajax/data.js?cache="+timestamp,
    }).done(function ( dataJSON ) {
        //console.log(dataJSON);
        eval(dataJSON);
        timeline.draw(data ,options);

        console.log("OK timeline ready");

        for(var i = 0; i < screens.length ; i ++){
            timeline.changeItem(screens[i], {'start' : todayM3 });
        }
        timeline.setSelection();

    });
    
    
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
            console.log("SELECT - id : " + row);
            console.log("start : "  + data[row].start );
            console.log("end : "    + data[row].end );
            console.log("content : "+ data[row].content );
            console.log("group : "  + data[row].group );

        }

        
    };
    links.events.addListener(timeline, 'select', onselect);

    // CHANGEMENT (via un drag) D'UN SLIDE
    var onchange= function(event){

        var row = undefined;
        var sel = timeline.getSelection();
        if (sel.length) {
            if (sel[0].row != undefined) {
                var row = sel[0].row;
            }
        }

        if (row != undefined) {

            var debut = new Date(data[row].start);

            $(".start_date").text(  debut.getDate() + " " + (debut.getMonth()+1) + " " + debut.getFullYear());
            $(".end_date").text( data[row].end );
            $(".contenu").text( data[row].content );
            $(".groupe").text( data[row].group );

            console.log("onChange :\n" + data[row].start + ' >> ' + data[row].end + '\n[groupe : ' + data[row].group + ']');
        }
    }
    links.events.addListener(timeline, 'change', onchange);

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
                
                editSlide(row);
                
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
    links.events.addListener(timeline, 'edit',   onedit);


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
            console.log('Delete : ' + data[row].content + '\n' +  data[row].test);
        }
    }
    links.events.addListener(timeline, 'delete', ondelete);


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
            console.log('Ajout : ' + data[row].content + '\nstart : '+ data[row].start + '\nend   : ' + data[row].end + '\n[groupe : ' + data[row].group + ']'+ '\nclass : ' + data[row].className );
        }
    }
    links.events.addListener(timeline, 'add', onadd);

    // MISE A JOUR LORS D'UN SCROLL OU UN ZOOM
    var onrangechange = function(event){           

        for(var i = 0; i < screens.length ; i ++){
            timeline.changeItem(screens[i], {'start' : new Date(event.start) });
            //console.log("start : "+new Date(event.start));
        }

        timeline.setSelection();

        //start
    }
    links.events.addListener(timeline, 'rangechange', onrangechange)

    
}

function editSlide(ref){
    console.log('edit slide');

    var date1 = new Date(data[ref].start);
    var date2 = new Date(data[ref].end);
    var duree = Math.round((date2-date1)/1000);
    
    slide_info = {
        group:     data[ref].group,
        content:   data[ref].content,
        start:     data[ref].start,
        end:       data[ref].end,
        test:      data[ref].test,

        duree:     second2HMS(duree),

        annee1:    date1.getFullYear(),
        mois1:     date1.getMonth() + 1,
        jour1:     date1.getDate(),
        heure1:    date1.getHours(),
        minute1:   date1.getMinutes(),
        seconde1:  date1.getSeconds(),

        annee2:    date2.getFullYear(),
        mois2:     date2.getMonth() + 1,
        jour2:     date2.getDate(),
        heure2:    date2.getHours(),
        minute2:   date2.getMinutes(),
        seconde2:  date2.getSeconds(),

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
    });

    // on selectionne le groupe quand on affiche le formulaire
    $('#screen_reference').val(data[ref].group);

    $('#save_slide').click(function(e){

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

        var slide_content = {'id':ref};
        slide_content_editor = ich.slide_content_editor(slide_content);

        $.fancybox( slide_content_editor , {
            title : '<h1>Modifier un slide</h1>',
            helpers : {
                title: {
                    type: 'inside',
                    position: 'top'
                }
            },
        });

    });
}

/*
* Conversion des secondes en hh : mm : ss
*/ 
function second2HMS(duree){
    // heure
    retour = (duree-duree%3600)/3600 + 'h : ';
    duree = duree%3600;
    // minute
    retour += (duree-duree%60)/60 + 'm : ';
    duree = duree%60;
    // secondes
    retour += duree +'s';

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



//console.log = function() {};