<?php
// PHP file defining the APIs for the course project 'Sight of Cite' by Zhang Jingning and Liu Yishuo
// started Mar. 14, 2015

//exit ( json_encode ( $_POST["data"] ) );

$SERVERNAME = "localhost";
$PORT = '8889';
$USERNAME = "root";
$PASSWORD = "root";
$DBNAME = "sightofc_db";

$JSONSQLDIC = array();	// Dictionary for traslating JSON & SQL terms

if ($_SERVER ['REQUEST_METHOD'] == 'GET') {		// QUERY
	if (isset ( $_GET ["action"] )) {
		$value = 'An error occured.';
		// call function 'get_all' or 'get_entry_by_id' according to the input
		switch ($_GET ["action"]) :
			case "get_all_paper" : // get the all the content
				$value = get_all_paper ();
				break;
			case "search_by_id" :
				$value = search_paper_by_pub_id ( $_GET ["id"] );
				break;
			case "search_by_title" :
				$value = search_paper_by_title ($_GET ["title"]);		
				break;
			default :
				$value = 'Error input.';
		endswitch;

		exit ( json_encode ( $value ) );
	}
}
else if($_SERVER ['REQUEST_METHOD'] == 'POST') {	// INSERT
	if (isset ( $_POST ["action"] )) {
		$value = 'An error occurd.';
		// calll function 'insert_entry' or 'update_entry' according to the input
		switch ($_POST ["action"]) :
		case "add_paper" :
			$value = add_paper( $_POST ["data"]);	// call function
			break;
		case "update_paper" :
			$value = update_paper( $_POST ["data"]);
			break;
		case "add_note_by_paper_ids":
			break;
		case "update_note_by_paper_ids":
			break;
		case "add_tag_by_paper_id":
			break;
		default :
// 			$value = 'Error input.';
			$value = $_POST;
		endswitch;
		
		exit ( json_encode ( $value ) );
	}

}
else if ($_SERVER ['REQUEST_METHOD'] == 'PUT') {	// UPDATE
	parse_str(file_get_contents("php://input"), $post_vars);
	$value = update_paper( $post_vars);
}
else if ($_SERVER ['REQUEST_METHOD'] == 'DELETE') {	// DELETE
	$value = delete_paper( $_POST ["data"]);
}




function get_all_paper() {	
	
	global $SEVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$sql = "SELECT * FROM Publication";
		$stmt = $conn->prepare ( $sql );
		$stmt->execute ();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );
		return $result;
	} catch ( PDOException $e ) {
		echo $sql . "<br>" . $e->getMessage ();
	}
	
	$conn = null;
}

function search_paper_by_pub_id($pub_id) {
	
	if (isset ( $pub_id )) {	//input validation
		
	global $SEVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
// 		$sql = "SELECT * FROM Publication WHERE pub_id=$pub_id";
		$stmt = $conn->prepare('SELECT * FROM Publication WHERE pub_id = :pub_id');
		$stmt->bindParam(":pub_id", $pub_id, PDO::PARAM_INT);
// 		$stmt = $conn->prepare ( $sql );
		$stmt->execute ();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );
		return $result;
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
	
	} else {
		return 'Missing argument.';
	}
}

function search_paper_by_title($title_piece) {
	
	if (isset ( $title_piece)) {
	
	global $SEVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$sql = "SELECT * FROM Publication WHERE title LIKE '%$title_piece%'";
		$stmt = $conn->prepare ( $sql );
		$stmt->execute ();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );
		return $result;
	} catch ( PDOException $e ) {
		echo $sql . "<br>" . $e->getMessage ();
	}

	$conn = null;
	
	} else {
		return 'Missing argument';
	}
}

function get_max_pub_id(){
	global $SEVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$sql = "SELECT max(pub_id) AS max_id FROM Publication";
		$stmt = $conn->prepare ( $sql );
		$stmt->execute ();
		$result = $stmt->fetch( PDO::FETCH_ASSOC );		
		return $result['max_id'];
	} catch ( PDOException $e ) {
		echo $sql . "<br>" . $e->getMessage ();
	}

	$conn = null;
}

// $_POST REQUESTs
function add_paper($paperObj) {
	
	global $SEVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;      
	
	$NEW_PUB_ID = get_max_pub_id() + 1;	

	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		
		$sql = ("INSERT INTO Publication 
				SET
				pub_id = :pub_id, 
				title = :title, 
				pub_year = :pub_year, 
				cite_count = :cite_count, 
				ISBN = :ISBN 
				");

		$stmt = $conn->prepare ( $sql );
		$stmt->bindParam(":pub_id", $NEW_PUB_ID, PDO::PARAM_INT);		
		$stmt->bindParam(":title", $paperObj['title'], PDO::PARAM_STR );
		$stmt->bindParam(":pub_year", $paperObj['pub_year'], PDO::PARAM_INT);
		$stmt->bindParam(":cite_count", $paperObj['cite_count'], PDO::PARAM_INT);
		$stmt->bindParam(":ISBN", $paperObj['ISBN'], PDO::PARAM_STR );
		$stmt->execute();

// 		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );
		return $NEW_PUB_ID;
		//return $sql;
	} catch ( PDOException $e ) {
		echo $sql . "<br>" . $e->getMessage ();
	}
	
	$conn = null;
}

function update_paper($paperObj) {
	
	global $SEVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
	
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->exec("SET CHARACTER SET utf8");
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		
		$sql = "UPDATE Publication
				SET 
					title = :title,
					pub_year = :pub_year,
					cite_count = :cite_count,
					ISBN = :ISBN
				WHERE pub_id = :pub_id 
				";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(":pub_id", $paperObj['pub_id'], PDO::PARAM_INT);
		$stmt->bindParam(":title", $paperObj['title'], PDO::PARAM_STR );
		$stmt->bindParam(":pub_year", $paperObj['pub_year'], PDO::PARAM_INT);
		$stmt->bindParam(":cite_count", $paperObj['cite_count'], PDO::PARAM_INT);
		$stmt->bindParam(":ISBN", $paperObj['ISBN'], PDO::PARAM_STR );
		$stmt->execute ();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );
		return $result;
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}
	
	$conn = null;
}

function delete_paper($paperObj) {
	
	global $SEVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
	
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
// 		$sql = ("DELETE FROM Publication
// 				WHERE (pub_id = :pub_id, title = :title, pub_year = :pub_year, cite_count = :cite_count, ISBN = :ISBN)");
// 		$stmt = $conn->prepare ( $sql );
		$stmt = $conn->prepare('DELETE FROM Publication
				WHERE pub_id = :pub_id 
				AND title = :title 
				AND pub_year = : pub_year 
				AND cite_count = :cite_count 
				AND ISBN = :ISBN
				');
		$stmt->bindParam(":pub_id", $paperObj['pub_id'], PDO::PARAM_INT);
		$stmt->bindParam(":title", $paperObj['title'], PDO::PARAM_STR );
		$stmt->bindParam(":pub_year", $paperObj['pub_year'], PDO::PARAM_INT);
		$stmt->bindParam(":cite_count", $paperObj['cite_count'], PDO::PARAM_INT);
		$stmt->bindParam(":ISBN", $paperObj['ISBN'], PDO::PARAM_STR );
		$stmt->execute ();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );
		return $result;
	} catch ( PDOException $e ) {
// 		echo $sql . "<br>" . $e->getMessage ();
	}
	
	$conn = null;
}
?>