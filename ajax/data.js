// JavaScript Document
var screen_list = [ {"key" : "1 - écran 1", "value" : "1 - écran 1"},
                    {"key" : "2 - écran 2", "value" : "2 - écran 2"},
                    {"key" : "3 - écran 3", "value" : "3 - écran 3"}];

var screens = new Array(0,1,2); 

data = [
// liste des écrans + alertes
    {
        "start" : new Date(2012, 0, 1),
        "group" : "1 - écran 1",
        "editable" : true,
		"content":"écran 1",
        "type" : "screen",
        "className" : "screen"
    },
    {
        "start" : new Date(2012, 0, 1),
        "group" : "2 - écran 2",
        "editable" : true,
		"content":"écran 2",
        "type" : "screen",
        "className" : "screen"
    },
    {
        "start" : new Date(2012, 0, 1),
        "group" : "3 - écran 3",
        "editable" : true,
		"content":"écran 3",
        "type" : "screen",
        "className" : "screen"
    },
// liste des slides
    {
        "start" : new Date(2012,10,3,12) ,
        "end" : new Date(2012,10,10),
        "content": "slide-test",
        "className": "evenement-1 unpublished readonly",
        "group":"1 - écran 1",
        "editable": false,
        "type" : "slide",
        "test":"youpi"
    },
    {
        "start" : new Date(2012,4,3,12,30,2) ,
        "end" : new Date(2012,4,5),
        "content": "slide-test2",
        "className": "evenement-1",
        "group":"2 - écran 2",
        "editable": true,
        "type" : "slide",
        "test":"youpi 2"
    },
    {
        "start" : new Date(2013,7,13,12,30,2) ,
        "end" : new Date(2013,7,13,13,00),
        "content": "slide-test3",
        "className": "evenement-1 unpublished",
        "group":"2 - écran 2",
        "editable": true,
        "type" : "slide",
        "test":"youpi 3"
    }
];