<?php
// PHP file defining the APIs for the course project 'Sight of Cite' by Zhang Jingning and Liu Yishuo
// started Mar. 14, 2015

// GLOBAL SETTINGS for cPanel
// $SERVERNAME = "engr-cpanel-mysql.engr.illinois.edu";
// $PORT = '3306';
// $USERNAME = "sightofc_root";
// $PASSWORD = "sightofc_root";
// $DBNAME = "sightofc_db";

// GLOBAL SETTINGS for MAMP
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
				// $value = getAllPaper ();
				$value = searchPaperByPub_id ("getAll");
				break;
			case "search_by_id" :
				$value = searchPaperByPub_id ( $_GET ["id"] );
				break;
			case "search_by_title" :
				$value = searchPaperByTitle ($_GET ["title"]);		
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
			$value = addPaper( $_POST ["data"]);	// call function
			break;
		case "update_paper" :
			$value = updatePaper( $_POST ["data"]);
			break;
		case "add_note_by_paper_ids":
			break;
		case "update_note_by_paper_ids":
			break;
		case "add_tag_by_paper_id":
			break;
		case "delete_paper" :
			$value = deletePaper( $_POST['data']);
			break;
		default :
			$value = 'Error input.';
			// $value = $_POST;
		endswitch;
		
		exit ( json_encode ( $value ) );
	}

}
else if ($_SERVER ['REQUEST_METHOD'] == 'PUT') {	// UPDATE
	parse_str(file_get_contents("php://input"), $post_vars);
	$value = updatePaper( $post_vars['data']);
	exit ( json_encode( $value) );
}
else if ($_SERVER ['REQUEST_METHOD'] == 'DELETE') {	// DELETE
	parse_str(file_get_contents("php://input"), $post_vars);
	$value = deletePaper( $post_vars['data']);
	exit ( json_encode ( $value ) );
}




function getAllPaper() {	
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		// $sql = "SELECT Publication.pub_id as pub_id, Publication.title as title, Publication.pub_year as pub_year, Publication.cite_count as cite_count, Publication.ISBN as ISBN, Author.name as author, Location.Name as location
		// 		FROM Author JOIN (Publication JOIN Location ON Publication.loc_id = Location.loc_id) ON Author.auth_id = Publication.auth_id";
		
		$sql = ("SELECT Publication.pub_id AS pub_id, 
						Publication.pub_title AS title, 
						GROUP_CONCAT(Author.auth_name SEPARATOR ',') AS authorNames, 
						GROUP_CONCAT(Author.auth_id SEPARATOR ',') AS authorIds, 
						Location.loc_name AS loc
				FROM Author NATURAL JOIN Author_of NATURAL JOIN Publication NATURAL JOIN Location
				GROUP BY Publication.pub_id");

		$stmt = $conn->prepare ( $sql );
		$stmt->execute ();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );

		// re-formating the result into associative array

		for ($resultCount = 0; $resultCount < sizeof($result); $resultCount++) {
			
			
					$result[$resultCount]->authorNames = explode(',', $result[$resultCount]->authorNames);
					$result[$resultCount]->authorIds = explode(',', $result[$resultCount]->authorIds);

					for ($i = 0; $i < sizeof($result[$resultCount]->authorNames); $i++) {
						$authorObj = new stdClass;
						$authorObj->name = $result[$resultCount]->authorNames[$i];
						$authorObj->id = $result[$resultCount]->authorIds[$i];
						$result[$resultCount]->author[$i] = $authorObj;
					}
					
					unset( $result[$resultCount]->authorNames);
					unset( $result[$resultCount]->authorIds);

		}

		return $result;
	} catch ( PDOException $e ) {
		echo $sql . "<br>" . $e->getMessage ();
	}
	
	$conn = null;
}

function searchPaperByPub_id($pub_id) {
	
	if (isset ( $pub_id )) {	//input validation
		
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$sql = ("SELECT Publication.pub_id AS pub_id, 
						Publication.pub_title AS title, 
						Publication.pub_year AS pub_year,
						Publication.pub_ISBN AS ISBN,
						Publication.pub_cite_count AS cite_count,
						GROUP_CONCAT(Author.auth_name SEPARATOR ',') AS authorNames, 
						GROUP_CONCAT(Author.auth_id SEPARATOR ',') AS authorIds, 
						Location.loc_name AS location
				FROM Author NATURAL JOIN Author_of NATURAL JOIN Publication NATURAL JOIN Location");

		if ( $pub_id == "getAll") {	// get all paper
			$sql = $sql." GROUP BY Publication.pub_id";
			// echo "\n".$sql."\n";
		} else {
			$sql = $sql." WHERE Publication.pub_id = :pub_id";
		}

		$stmt = $conn->prepare ( $sql );
		$stmt->bindParam(":pub_id", $pub_id, PDO::PARAM_INT);
		$stmt->execute ();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );

		// re-formating the result into associative array

		for ($resultCount = 0; $resultCount < sizeof($result); $resultCount++) {
			
			
					$result[$resultCount]->authorNames = explode(',', $result[$resultCount]->authorNames);
					$result[$resultCount]->authorIds = explode(',', $result[$resultCount]->authorIds);

					for ($i = 0; $i < sizeof($result[$resultCount]->authorNames); $i++) {
						$authorObj = new stdClass;
						$authorObj->name = $result[$resultCount]->authorNames[$i];
						$authorObj->id = $result[$resultCount]->authorIds[$i];
						$result[$resultCount]->author[$i] = $authorObj;
					}
					
					unset( $result[$resultCount]->authorNames);
					unset( $result[$resultCount]->authorIds);

		}

		return $result;
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
	
	} else {
		return 'Missing argument.';
	}
}

function searchPaperByTitle($query) {	
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$sql = ("SELECT Publication.pub_id AS pub_id, 
						Publication.pub_title AS title, 
						Publication.pub_year AS pub_year,
						Publication.pub_ISBN AS ISBN,
						Publication.pub_cite_count AS cite_count,
						GROUP_CONCAT(Author.auth_name SEPARATOR ',') AS authorNames, 
						GROUP_CONCAT(Author.auth_id SEPARATOR ',') AS authorIds, 
						Location.loc_name AS location
				FROM Author NATURAL JOIN Author_of NATURAL JOIN Publication NATURAL JOIN Location
				WHERE Publication.pub_title LIKE :query
				GROUP BY Publication.pub_id"
				);		

		$stmt = $conn->prepare ( $sql );		
		$query='%'.$query.'%';
		$stmt->bindParam(":query", $query, PDO::PARAM_STR);

		$stmt->execute ();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );

		// re-formating the result into associative array

		for ($resultCount = 0; $resultCount < sizeof($result); $resultCount++) {
			
			
					$result[$resultCount]->authorNames = explode(',', $result[$resultCount]->authorNames);
					$result[$resultCount]->authorIds = explode(',', $result[$resultCount]->authorIds);

					for ($i = 0; $i < sizeof($result[$resultCount]->authorNames); $i++) {
						$authorObj = new stdClass;
						$authorObj->name = $result[$resultCount]->authorNames[$i];
						$authorObj->id = $result[$resultCount]->authorIds[$i];
						$result[$resultCount]->author[$i] = $authorObj;
					}
					
					unset( $result[$resultCount]->authorNames);
					unset( $result[$resultCount]->authorIds);

		}

		// print_r($result);
		return $result;
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
	
}

// function searchPaperByTitle($title_piece) {
	
// 	if (isset ( $title_piece)) {
	
// 	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
// 	try {
// 		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
// 		// set the PDO error mode to exception
// 		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
// 		$sql = "SELECT * FROM Publication WHERE pub_title LIKE '%$title_piece%'";
// 		$stmt = $conn->prepare ( $sql );
// 		$stmt->execute ();
// 		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );
// 		return $result;
// 	} catch ( PDOException $e ) {
// 		echo $sql . "<br>" . $e->getMessage ();
// 	}

// 	$conn = null;
	
// 	} else {
// 		return 'Missing argument';
// 	}
// }

function getMaxId($id_name, $table_name){
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ("SELECT max($id_name) AS max_id FROM $table_name");
		$stmt = $conn->prepare ( $sql );
		$stmt->execute ();
		$result = $stmt->fetch( PDO::FETCH_ASSOC );	

		return $result['max_id'];

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

function insertAuthorOf($pub_id, $auth_id){
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;      
	
	$NEW_PUB_ID = getMaxId("pub_id", "Publication") + 1;	

	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql_author_of = ("INSERT INTO Author_of(pub_id, auth_id)
				VALUES (:pub_id,:auth_id)");

		$stmtAuthA_of = $conn->prepare ( $sql_author_of );
		$stmtAuthA_of->bindParam(":pub_id", $pub_id, PDO::PARAM_INT);		
		$stmtAuthA_of->bindParam(":auth_id", $auth_id, PDO::PARAM_INT);			
		$stmtAuthA_of->execute();
	
		return;
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}
	
	$conn = null;	
}

// $_POST REQUESTs
function addPaper($paperObj) {
	
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;      
	
	$NEW_PUB_ID = getMaxId("pub_id", "Publication") + 1;	

	insertAuthorOf($NEW_PUB_ID,1);

	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sqlPub = ("INSERT INTO Publication (pub_id, pub_title, pub_year, pub_cite_count, pub_ISBN)
				VALUES (:pub_id, :title, :pub_year, :cite_count, :ISBN)");

		$stmtPub = $conn->prepare ( $sqlPub );
		$stmtPub->bindParam(":pub_id", $NEW_PUB_ID, PDO::PARAM_INT);		
		$stmtPub->bindParam(":title", $paperObj['title'], PDO::PARAM_STR );
		$stmtPub->bindParam(":pub_year", $paperObj['pub_year'], PDO::PARAM_INT);
		$stmtPub->bindParam(":cite_count", $paperObj['cite_count'], PDO::PARAM_INT);
		$stmtPub->bindParam(":ISBN", $paperObj['ISBN'], PDO::PARAM_STR );
		$stmtPub->execute();

		foreach ($paperObj['author'] as $number => $pair) {
			foreach ($pair as $key => $auth_name) {
				$sqlAuthFind = ("SELECT auth_id FROM Author WHERE auth_name = :auth_name");
				$stmtAuthFind = $conn->prepare( $sqlAuthFind );
				$stmtAuthFind->bindParam(":auth_name", $auth_name, PDO::PARAM_STR);
				$stmtAuthFind->execute();
				$resultAuthFind = $stmtAuthFind->fetch ( PDO::FETCH_ASSOC );

				if ( empty($resultAuthFind) ) {	// find new author -> add to Author
					
					$NEW_AUTH_ID = getMaxId( "auth_id", "Author" ) + 1;
					
					$sqlAuthAdd = ("INSERT INTO Author (auth_id, auth_name) VALUES (:auth_id, :auth_name)");
					$stmtAuthAdd = $conn->prepare ( $sqlAuthAdd );
					$stmtAuthAdd->bindParam(":auth_id", $NEW_AUTH_ID, PDO::PARAM_INT);
					$stmtAuthAdd->bindParam(":auth_name", $auth_name, PDO::PARAM_STR);
					$stmtAuthAdd->execute();

					insertAuthorOf($NEW_PUB_ID, $NEW_AUTH_ID);
				} else {
					insertAuthorOf($NEW_PUB_ID, $resultAuthFind[auth_id]);
				}
			}
		}

		print_r($paperObj);
		echo "\n";

		return array("pub_id" => $NEW_PUB_ID);
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}
	
	$conn = null;
}

function updatePaper($paperObj) {
	
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
	
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->exec("SET CHARACTER SET utf8");
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		
		$sql = "UPDATE Publication
				SET 
					pub_title = :title,
					pub_year = :pub_year,
					pub_cite_count = :cite_count,
					pub_ISBN = :ISBN
				WHERE pub_id = :pub_id 
				";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(":pub_id", $paperObj['pub_id'], PDO::PARAM_INT);
		$stmt->bindParam(":title", $paperObj['title'], PDO::PARAM_STR );
		$stmt->bindParam(":pub_year", $paperObj['pub_year'], PDO::PARAM_INT);
		$stmt->bindParam(":cite_count", $paperObj['cite_count'], PDO::PARAM_INT);
		$stmt->bindParam(":ISBN", $paperObj['ISBN'], PDO::PARAM_STR );
		$stmt->execute ();
		return 1;
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}
	
	$conn = null;
}

function deletePaper($paperObj) {

	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
	
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ("DELETE FROM Publication 
				WHERE pub_id = :pub_id"
				);

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":pub_id", $paperObj['pub_id'], PDO::PARAM_INT);
		$stmt->execute ();
		return 1;

	} catch ( PDOException $e ) {
		echo $sql . "<br>" . $e->getMessage ();
	}
	
	$conn = null;
}
?>