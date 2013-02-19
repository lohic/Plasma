<?php

$json->countusers = rand(0,40);

$retour = json_encode($json);

echo $retour;