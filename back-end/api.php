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
				$value = getPaperByPubId ("getAll");
				break;
			case "search_by_id" :
				$value = getPaperByPubId ( $_GET ["id"] );
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
			$value = deletePaperByPaperId( $_POST['data']);
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
	$value = deletePaperByPaperId( $post_vars['data']);
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

		return $result;
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
	
}

////////////////////////////////
//      API Functions        //
//////////////////////////////

// API 01
function addPaper($paperObj) {
	
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;      
	
	$NEW_PUB_ID = getMaxId("pub_id", "Publication") + 1;

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

		foreach ($paperObj['author'] as $number => $pair) {	// Check & Update Author, Author_of
			foreach ($pair as $key => $auth_name) {
				$findAuthId = checkAuthor( $auth_name );
				if ( empty($findAuthId) ) {	// new author found -> add to Author					
					$NEW_AUTH_ID = insertAuthor( $auth_name );
					insertAuthorOf($NEW_PUB_ID, $NEW_AUTH_ID);	// add to Author_of
				} else {						// author in record -> add to Author_of directly
					insertAuthorOf($NEW_PUB_ID, $findAuthId);
				}
			}
		}

		foreach ($paperObj['location'] as $number => $pair) {
			foreach ($pair as $key => $loc_name) {
				
			}
		}

		return array("pub_id" => $NEW_PUB_ID);

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}
	
	$conn = null;
}

// API 02
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

// API 03
function deletePaperByPaperId($paperObj) {

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

// API 04
function searchPaperByAttrs($paperObj) {

}

// API 05
function addNoteByPaperIds($paperObj) {

}
// API 06
function updateNoteByPaperIds($paperObj) {

}

// API 07
function deleteNoteByPaperIds($paperObj) {

}

// API 08
function getNoteByPaperIds($paperObj) {

}

// API 09
function getPaperByPubId($pub_id) {
	
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

		if ( $pub_id == "getAll") {	// SPECIAL CASE: get all paper
			$sql = $sql." GROUP BY Publication.pub_id";
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

// API 10
function addTagByPaperId($paperObj) {

}

// API 11
function deleteTagByPaperId($paperObj) {

}

// API 12
function deleteTagByTagId($tagId) {

}

////////////////////////////////
//   Peripheral Functions    //
//////////////////////////////
function getMaxId($idName, $tableName) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ("SELECT max($idName) AS maxId FROM $tableName");
		$stmt = $conn->prepare ( $sql );
		$stmt->execute ();
		$result = $stmt->fetch( PDO::FETCH_ASSOC );	

		return $result['maxId'];

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

function checkAuthor( $auth_name ) {	// Check the passed auth_name, if new->empty, existed->auth_id
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sqlAuthFind = ("SELECT auth_id FROM Author WHERE auth_name = :auth_name");

		$stmtAuthFind = $conn->prepare( $sqlAuthFind );
		$stmtAuthFind->bindParam(":auth_name", $auth_name, PDO::PARAM_STR);
		$stmtAuthFind->execute();
		$resultAuthFind = $stmtAuthFind->fetch ( PDO::FETCH_ASSOC );

		if ( empty($resultAuthFind) ) {	// it's new
			return;
		} else {	// it's existed
			return $resultAuthFind[auth_id];
		}

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

function insertAuthor( $auth_name ) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;

	$NEW_AUTH_ID = getMaxId( "auth_id", "Author" ) + 1;
	
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sqlAddAuthor = ("INSERT INTO Author (auth_id, auth_name) VALUES (:auth_id, :auth_name)");

		$stmtAddAuthor = $conn->prepare ( $sqlAddAuthor );
		$stmtAddAuthor->bindParam(":auth_id", $NEW_AUTH_ID, PDO::PARAM_INT);
		$stmtAddAuthor->bindParam(":auth_name", $auth_name, PDO::PARAM_STR);
		$stmtAddAuthor->execute();

		return $NEW_AUTH_ID;

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

function insertAuthorOf( $pub_id, $auth_id ) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;      
	
	$NEW_PUB_ID = getMaxId("pub_id", "Publication") + 1;	

	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sqlAddAuthorOf = ("INSERT INTO Author_of(pub_id, auth_id) VALUES (:pub_id,:auth_id)");

		$stmtAddAuthorOf = $conn->prepare ( $sqlAddAuthorOf );
		$stmtAddAuthorOf->bindParam(":pub_id", $pub_id, PDO::PARAM_INT);		
		$stmtAddAuthorOf->bindParam(":auth_id", $auth_id, PDO::PARAM_INT);			
		$stmtAddAuthorOf->execute();
	
		return;
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}
	
	$conn = null;	
}

?>