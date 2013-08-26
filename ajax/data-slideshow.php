<?php

header('Content-Type: text/html; charset=utf-8');

$action         = isset( $_POST['action'] ) ? $_POST['action'] : null;
$publish_date   = isset( $_POST['actual_data_date'] ) ? $_POST['actual_data_date'] : null;
$plasma_id      = isset( $_POST['plasma_id'] ) ? $_POST['plasma_id'] : 0;


if($publish_date>"2013-08-25 18:58:00" || $publish_date == 'undefined'){

    echo '{
        "screen_id" : '. $plasma_id .',
        "publish_date" : "2013-08-25 18:58:00",
        "update" : true,
        "slides_date" : [
            {
                "id" : 1,
                "id_slide" : 10,
                "start" : "2013-08-25 18:55:00",
                "end" : "2013-08-25 19:00:00",
                "alerte" : false
            },
            {
                "id" : 2,
                "id_slide" : 20,
                "start" : "2013-08-25 19:00:00",
                "end" : "2013-08-25 19:05:00",
                "alerte" : false
            },
            {
                "id" : 3,
                "id_slide" : 10,
                "start" : "2013-08-25 19:05:00",
                "end" : "2013-08-25 19:10:00",
                "alerte" : false
            }
        ],
        "slides_ordered" : [
            {
                "id" : 1,
                "id_slide" : 100,
                "duree" : "00:01:00"
            },
            {
                "id" : 2,
                "id_slide" : 200,
                "duree" : "00:01:00"
            },
            {
                "id" : 3,
                "id_slide" : 300,
                "duree" : "00:01:00"
            }
        ]
    }';

}else{

    echo '{
        "screen_id" : '. $plasma_id .',
        "publish_date" : "2013-08-25 18:58:00",
        "update" : false
    }';

}