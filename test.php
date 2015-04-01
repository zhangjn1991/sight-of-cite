<?php

$obj = new stdClass;
$obj->author = 'a,b,c';

$result  = array($obj);
$result[0]->author = 'def';
print_r($result[0]->author);
?>
