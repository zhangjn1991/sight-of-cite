<?php

// $obj = new stdClass;
// $obj->author = 'a,b,c';

// $result  = array($obj);
// $result[0]->author = 'def';
// print_r($result[0]->author);

// $i = 1;
// $pub_id = 10;
// $sql = ("SELECT * FROM Publication");
// if ($i == 0) {
// $sql_exec = $sql." GROUP BY pub_id";
// }else {
// $sql_exec = $sql." WHERE pub_id = $pub_id";
// }

// echo "\n".$sql_exec."\n"

include 'test_copy.php';
echo addTwo(3);
echo "\n";

// var_dump($argv);

// echo $argv[1]."\n";

// for ($i = 1; $i <= 3; $i++) {
// 	echo "\n".$argv[$i];
// 	echo "    ".addTwo($argv[$i]);
// }
// echo "\n";
$val = returnEmpty( $argv[1] );

echo "val = ".$val."\n";

if (empty( $val ) ) {
	echo "Empty !\n";
} else {
	echo "Not Empty !\n";
}


function returnEmpty($input) {
	if ($input) {
		return 1;
	} else {
		return ;
	}
}

?>