Last login: Tue Mar 31 16:58:37 on ttys001
LiuYishuos-MacBook-Air:sight-of-cite liuyishuo$ touch test.php
LiuYishuos-MacBook-Air:sight-of-cite liuyishuo$ vim test.php 





















<?php

$obj = new stdClass;
$obj->author = 'a,b,c';

$result  = array($obj);
$result[0]->author = 'def';
print_r($result[0]->author);

$i = 1;
$pub_id = 10;
$sql = ("SELECT * FROM Publication");
if ($i == 0) {
$sql_exec = $sql." GROUP BY pub_id";
}else {
$sql_exec = $sql." WHERE pub_id = $pub_id";
}

echo "\n".$sql_exec."\n"
?>
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
~                                                                               
-- INSERT --
