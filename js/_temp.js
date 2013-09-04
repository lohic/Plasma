
/*function update_item(){

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
            published : dataTimeline[row].className.indexOf("unpublished") < 0 ? 1 : 0,
            action  : 'update-item'
        }
    }).done(function ( dataJSON ) {
        console.log("Save change : "+dataJSON);
    });

    // ---------------
    
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
            published : dataTimeline[ref].className.indexOf("unpublished") < 0 ? 1 : 0,
            action  : 'update-item'
        }
    }).done(function ( dataJSON ) {
        console.log(dataJSON);
    });

    // ---------------

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
            published : dataTimeline[row].className.indexOf("unpublished") < 0 ? 1 : 0,
            action  : 'update-item'
        }
    }).done(function ( dataJSON ) {
        console.log(dataJSON);
    });
}*/
