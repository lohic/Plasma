<?php
header('Content-type: text/html; charset=UTF-8');

include_once('../vars/config.php');
include_once('../vars/statics_vars.php');
include_once('../classe/classe_core.php');
include_once('../classe/classe_slide.php');
include_once("../classe/classe_fonctions.php");


$core = new core();

if($core->isAdmin){ 

    $slide = new Slide();
?>// JavaScript Document
var screen_list = [ {"key" : "1 - écran 1", "value" : "1 - écran 1"},
                    {"key" : "2 - écran 2", "value" : "2 - écran 2"},
                    {"key" : "3 - écran 3", "value" : "3 - écran 3"}];

var screens = new Array(0,1,2); 

data = [
// liste des écrans + alertes
    {
        "start" : new Date(2012, 0, 1),
        "group" : "1 - écran 1",
        "editable" : false,
		"content":"écran 1",
        "type" : "screen",
        "className" : "screen"
    },
    {
        "start" : new Date(2012, 0, 1),
        "group" : "2 - écran 2",
        "editable" : false,
		"content":"écran 2",
        "type" : "screen",
        "className" : "screen"
    },
    {
        "start" : new Date(2012, 0, 1),
        "group" : "3 - écran 3",
        "editable" : false,
		"content":"écran 3",
        "type" : "screen",
        "className" : "screen"
    },
// liste des slides
    <?php echo $slide->get_timeline_items() ?>
];
<?php
}
?>