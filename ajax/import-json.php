<?php

header('Content-Type: text/html; charset=utf-8');


//echo $data[0]->value;

/*$data = new stdClass();
$data->letexte = 'Super ça fonctionne bien, ça c’est cool';
$data->progression = 20;
$data->pays = "Allemagne";
$data->coorganisateur = "École de communication";*/

$template = isset($_GET['template']) ? $_GET['template'] : null;
$slide_id = isset($_GET['slide_id']) ? $_GET['slide_id'] : null;

if(isset($slide_id)){
    $data = json_decode('{"letexte":"<p>Super ça fonctionne bien, ça c’est cool je fais ma modifcation</p>","username":"","pays":"Allemagne","coorganisateur":"École de communication","password":"","date_slide":"2013-08-23","image":"2013/08/frankfurter-dauphin-magali.jpg"}');
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
echo json_encode($json);