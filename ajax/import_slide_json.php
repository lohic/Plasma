<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slide.php');
include_once("../classe/classe_fonctions.php");

//echo $data[0]->value;

/*$data = new stdClass();
$data->letexte = 'Super ça fonctionne bien, ça c’est cool';
$data->progression = 20;
$data->pays = "Allemagne";
$data->coorganisateur = "École de communication";*/

$core = new core();

if($core->isAdmin){ 

    //$template = isset($_GET['template']) ? $_GET['template'] : null;
    //$id_slide = isset($_GET['slide_id']) ? $_GET['slide_id'] : null;

    $slide = new Slide();
    //$action = isset($_POST['action']) ? $_POST['action'] : NULL;

    $slide->get_timeline_slide_data( $_GET['id_slide'], $_GET['template'] );

}

/*

if(isset($slide_id)){
    $data = json_decode('{"letexte":"<p>Super ça fonctionne bien, ça c’est cool je fais ma modifcation</p>","username":"","pays":"Allemagne","coorganisateur":"École de communication","password":"","date_slide":"2013-08-23","image":"2013/08/IMG_0001.JPG"}');
}else{
    $data = json_decode('{}');
}

if(isset($template)){

    $json = json_decode( file_get_contents( '../slides_templates/'.$template.'/structure.json' ) );

    foreach($json->html as $line){

        //echo $line->type."<br/>\n";
        //echo isset($line->name) ? $line->name."<br/>\n" : "<br/>\n";
        //echo "---------<br/>\n";
        //$line->value = 'ok';
        //  

        if( isset($line->name) ){
            $name = $line->name;
            if (isset($data->$name) ){
                if($line->type == 'file'){
                    $attr = 'data-file';
                    $line->$attr = $data->$name;
                }else{
                    $line->value = $data->$name;
                }
            }
        }
    }
}else{
    $json = '{}';
}
echo json_encode($json);*/