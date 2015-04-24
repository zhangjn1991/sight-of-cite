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


if ($_SERVER ['REQUEST_METHOD'] == 'GET') {		// QUERY
	if (isset ( $_GET ["action"] )) {
		$value = 'An error occurred.';
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
			case "search_paper_by_attrs": 	// API 04
				$value = searchPaperByAttrs ( $_GET["data"] );
				break;
			case "get_note_by_paper_ids":	// API 08
				$value = getNoteByPaperIds( $_GET["pub_id_1"], $_GET["pub_id_2"] );
				break;
			case "get_paper_by_ids": 	// API 09
				$value = getPaperByPubIds( $_GET["ids"] );
				break;
			default :
				$value = 'Error input.';
		endswitch;

		exit ( json_encode ( $value ) );
	}
}
else if($_SERVER ['REQUEST_METHOD'] == 'POST') {	// INSERT
	if (isset ( $_POST ["action"] )) {

		$value = 'An error occurred.';

		switch ($_POST ["action"]) :
		case "add_paper" :	// API 01
			$value = addPaper( $_POST ["data"] );
			break;
		case "update_paper" :	// API 02
			$value = updatePaper( $_POST ["data"]);
			break;
		case "delete_paper" :	// API 03
			$value = deletePaperByPaperId( $_POST['data']['pub_id']);
			break;
		case "add_note_by_paper_ids":	// API 05
			$value = addNoteByPaperIds( $_POST['data']['pub_id_1'], $_POST['data']['pub_id_2'], $_POST['data']['note_content'] );
			break;
		case "update_note_by_paper_ids":	// API 06
			$value = updateNoteByPaperIds( $_POST['data']['pub_id_1'], $_POST['data']['pub_id_2'], $_POST['data']['note_content'], $_POST['data']['rating'], $_POST['data']['date']);
			break;
		case "delete_note_by_paper_ids": 	// API 07
			$value = deleteNoteByPaperIds( $_POST['data']['pub_id_1'], $_POST['data']['pub_id_2'] );
			break;
		case "add_tag_by_paper_id":	// API 10
			$value = addTagByPaperId( $_POST['data']['pub_id'], $_POST['data']['tag_content'] );
			break;
		case "delete_tag_by_paper_id" :	// API 11
			$value = deleteTagByPaperId( $_POST['data']['pub_id'], $_POST['data']['tag_id'] );
			break;
		case "delete_tab_by_tag_id" :	// API 12
			$value = deleteTagByTagId( $_POST['tag_id'] );
			break;

		case "test" :
			$value = checkTagOf( $_POST['tag_id'] );
			break;
		default :
			$value = 'Error input.';
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
	$value = deletePaperByPaperId( $post_vars['data']['pub_id']);
	exit ( json_encode ( $value ) );
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

		foreach ($paperObj['location'] as $number => $pair) {
			foreach ($pair as $key => $loc_name) {
				$findLocId = checkLocation( $loc_name );
				if ( empty($findLocId) ) {
					$NEW_LOC_ID = insertLocation( $loc_name );
					$loc_id = $NEW_LOC_ID;
				} else {
					$loc_id = $findLocId;
				}
			}
		}

		$sql = ("INSERT INTO Publication (pub_id, pub_title, pub_year, pub_cite_count, pub_ISBN, loc_id, pub_MSid, pub_abstract)
				VALUES (:pub_id, :title, :pub_year, :cite_count, :ISBN, :loc_id, :pub_MSid, :pub_abstract)");

		$stmt = $conn->prepare ( $sql );
		$stmt->bindParam(":pub_id", $NEW_PUB_ID, PDO::PARAM_INT);
		$stmt->bindParam(":title", $paperObj['title'], PDO::PARAM_STR );
		$stmt->bindParam(":pub_year", $paperObj['pub_year'], PDO::PARAM_INT);
		$stmt->bindParam(":cite_count", $paperObj['cite_count'], PDO::PARAM_INT);
		$stmt->bindParam(":ISBN", $paperObj['ISBN'], PDO::PARAM_STR );
		$stmt->bindParam(":pub_abstract", $paperObj['abstract'], PDO::PARAM_STR);
		$stmt->bindParam(":pub_MSid", $paperObj['MSid'], PDO::PARAM_INT);
		$stmt->bindParam(":loc_id", $loc_id, PDO::PARAM_INT);
		$stmt->execute();

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
					pub_ISBN = :ISBN,
					pub_abstract = :pub_abstract
				WHERE pub_id = :pub_id 
				";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(":pub_id", $paperObj['pub_id'], PDO::PARAM_INT);
		$stmt->bindParam(":title", $paperObj['title'], PDO::PARAM_STR );
		$stmt->bindParam(":pub_year", $paperObj['pub_year'], PDO::PARAM_INT);
		$stmt->bindParam(":cite_count", $paperObj['cite_count'], PDO::PARAM_INT);
		$stmt->bindParam(":ISBN", $paperObj['ISBN'], PDO::PARAM_STR );
		$stmt->bindParam(":pub_abstract", $paperObj['abstract'], PDO::PARAM_STR);
		$stmt->execute ();
		return true;
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}
	
	$conn = null;
}

// API 03
function deletePaperByPaperId( $pubId ) {

	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
	
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ("DELETE FROM Publication 
				WHERE pub_id = :pub_id"
				);

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":pub_id", $pubId, PDO::PARAM_INT);
		$stmt->execute ();
		return 1;

	} catch ( PDOException $e ) {
		echo $sql . "<br>" . $e->getMessage ();
	}
	
	$conn = null;
}

// API 04
function searchPaperByAttrs( $attrObj ) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		print_r( $attrObj );
		echo "\n";

		foreach ($attrObj as $key => $value) {
			// echo "attr: ".$key."\n";
			$relation = translateRelation( substr($value, 0, 2) );
			// echo "relation_trans: ".translateRelation( $relation )."\n";
			$value = substr($value, 2);
			// echo "value: ".substr($value, 2)."\n";


			$sql = ("SELECT * FROM Publication WHERE pub_id ".$relation." :pub_id");
			// $sql = ("SELECT * FROM Publication WHERE pub_id :relation :pub_id");
			echo "sql: ".$sql."\n";
			$stmt = $conn->prepare( $sql );
			// $stmt->bindParam(":relation", $relation, PDO::PARAM_INT);
			$stmt->bindParam(":pub_id", $value, PDO::PARAM_INT);
			$stmt->execute();

			// echo "sql: ".$sql."\n";
			$result = $stmt->fetchAll( PDO::FETCH_CLASS );
		}


	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

// API 05
function addNoteByPaperIds( $citerId, $citeeId, $noteContent ) {	// return note_id if succeed, else null
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$noteId = checkCite( $citerId, $citeeId );
		
		if ($noteId) {	// no record -> insert entry into Cite, Note
			$noteId = getMaxId('note_id', 'Note') + 1;

			$sql = ( "INSERT INTO Cite (citer_id, citee_id, note_id) 
						VALUES (:citer_id, :citee_id, :note_id); 
						INSERT INTO Note (note_id, note_content)
						VALUES (:note_id, :note_content)" );

			$stmt = $conn->prepare( $sql );
			$stmt->bindParam(":citer_id", $citerId, PDO::PARAM_INT);
			$stmt->bindParam(":citee_id", $citeeId, PDO::PARAM_INT);
			$stmt->bindParam(":note_id", $noteId, PDO::PARAM_INT);
			$stmt->bindParam(":note_content", $noteContent, PDO::PARAM_STR);
			$stmt->execute();

			return $noteId;
		} else {	// has record -> return the current note_id
			return $noteId;
		}

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}
// API 06
function updateNoteByPaperIds( $citerId, $citeeId, $noteContent, $noteRating, $noteDate ) {	// return updated note_id, if note exist return null
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$noteId = checkCite( $citerId, $citeeId );

		if ( $noteId ) {	// the aimed note doesn't exist
			return null;
		}

		$sql = ( "UPDATE Note
					SET 
						note_content = :note_content,
						note_rating = :note_rating,
						note_date = :note_date
					WHERE note_id = :note_id" );

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":note_id", $noteId, PDO::PARAM_INT);
		$stmt->bindParam(":note_content", $noteContent, PDO::PARAM_STR);
		$stmt->bindParam(":note_rating", $noteRating, PDO::PARAM_INT);
		$stmt->bindParam(":note_date", $noteDate, PDO::PARAM_STR);
		$stmt->execute();

		return $noteId;

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

// API 07
function deleteNoteByPaperIds( $citerId, $citeeId ) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$noteId = checkCite( $citerId, $citeeId );

		$sql = ( "DELETE FROM Cite WHERE citer_id = :citer_id AND citee_id = :citee_id;
					DELETE FROM Note WHERE note_id = :note_id;" );

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":citer_id", $citerId, PDO::PARAM_INT);
		$stmt->bindParam(":citee_id", $citeeId, PDO::PARAM_INT);
		$stmt->bindParam(":note_id", $noteId, PDO::PARAM_INT);
		$stmt->execute();

		return true;

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

// API 08
function getNoteByPaperIds( $citerId, $citeeId ) {	// return the Note object
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$noteId = checkCite( $citerId, $citeeId );

		$sql = ("SELECT * FROM Note WHERE note_id = :note_id");

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":note_id", $noteId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );

		return $result;

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

// API 09
function getPaperByPubId($pub_id) {
	
	if (isset ( $pub_id ) && !empty( $pub_id ) ) {	//input validation
		
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
	
	} else if( empty( $pub_id )) {
		return "Error: Empty pub_id. ";
	} else {
		return "Error: Missing argument. ";
	}
}
function getPaperByPubIds( $ids ) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;

	$resultCount = 0;
		
	for ($i = 0; $i < strlen($ids); $i+=2) {

		$pubId = $ids[$i];
		
		$result[ $resultCount ] = getPaperByPubId($pubId);
	}

	return $result;
}

// API 10
function addTagByPaperId( $pubId, $tagContent ) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$tagId = checkTag( $tagContent );

		if( $tagId ) {	// if it's a new tag
			$tagId = getMaxId('tag_id', 'Tag') + 1;
			insertTag($tagId, $tagContent);
		}

		$sql = ("INSERT INTO Tag_of (pub_id, tag_id) VALUES (:pub_id, :tag_id)");

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":pub_id", $pubId, PDO::PARAM_INT);
		$stmt->bindParam(":tag_id", $tagId, PDO::PARAM_INT);
		$stmt->execute();

		return true;

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

// API 11
function deleteTagByPaperId($pubId, $tagId) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ( "DELETE FROM Tag_of WHERE pub_id = :pub_id AND tag_id = :tag_id" );

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":pub_id", $pubId, PDO::PARAM_INT);
		$stmt->bindParam(":tag_id", $tagId, PDO::PARAM_INT);
		$stmt->execute();

		if (checkTagOf($tagId)) {	// no records of this tag-> delete it from Tag table
			deleteTag($tagId);
		}

		return true;

		} catch ( PDOException $e ) {
			echo $e->getMessage ();
		}

		$conn = null;
}

// API 12
function deleteTagByTagId($tagId) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;

	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );


		$corresPaper = checkTagOf( $tagId );

		if (!$corresPaper) {	// has records in Tag_of -> clean up

			for ($i = 0; $i < sizeof($corresPaper); $i++) {
				$sql = ("DELETE FROM Tag_of WHERE tag_id = :tag_id AND pub_id = :pub_id");

				$stmt = $conn->prepare ( $sql );
				$stmt->bindParam(":tag_id", $tag_id, PDO::PARAM_INT);
				$stmt->bindParam(":pub_id", $corresPaper[$i]->pub_id, PDO::PARAM_INT);
				$stmt->execute();
			}
		}
		
		deleteTag( $tagId );
		return true;

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
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

		$sql = ("SELECT auth_id FROM Author WHERE auth_name = :auth_name");

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":auth_name", $auth_name, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch ( PDO::FETCH_ASSOC );

		if ( empty($result) ) {	// it's new
			return;
		} else {	// it's existed
			return $result['auth_id'];
		}

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

function checkLocation( $loc_name ) {	// Check the passed loc_name, if new->empty, existed->loc_id
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ("SELECT loc_id FROM Location WHERE loc_name = :loc_name");

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":loc_name", $loc_name, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch ( PDO::FETCH_ASSOC );

		if ( empty($result) ) {	// it's new
			return;
		} else {	// it's existed
			return $result['loc_id'];
		}

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

function insertLocation( $loc_name ) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;

	$NEW_LOC_ID = getMaxId( "loc_id", "Location" ) + 1;
	
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ("INSERT INTO Location (loc_id, loc_name) VALUES (:loc_id, :loc_name)");

		$stmt = $conn->prepare ( $sql );
		$stmt->bindParam(":loc_id", $NEW_LOC_ID, PDO::PARAM_INT);
		$stmt->bindParam(":loc_name", $loc_name, PDO::PARAM_STR);
		$stmt->execute();

		return $NEW_LOC_ID;

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

		$sql = ("INSERT INTO Author (auth_id, auth_name) VALUES (:auth_id, :auth_name)");

		$stmt = $conn->prepare ( $sql );
		$stmt->bindParam(":auth_id", $NEW_AUTH_ID, PDO::PARAM_INT);
		$stmt->bindParam(":auth_name", $auth_name, PDO::PARAM_STR);
		$stmt->execute();

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

		$sql = ("INSERT INTO Author_of(pub_id, auth_id) VALUES (:pub_id,:auth_id)");

		$stmt = $conn->prepare ( $sql );
		$stmt->bindParam(":pub_id", $pub_id, PDO::PARAM_INT);		
		$stmt->bindParam(":auth_id", $auth_id, PDO::PARAM_INT);			
		$stmt->execute();
	
		return;
	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}
	
	$conn = null;	
}

function translateRelation( $relation ) {

	$result = 'an error occurred.';

	switch ($relation) {
		case 'lt':
			$result = "<";
			break;
		case 'eq':
			$result = "=";
			break;
		case 'gt':
			$result = ">";
			break;
		case 'hs':
			$result = "HAS";
			break;
		default:
			$result = "error: invalid relation";
			break;
	}

	return $result;
}

function checkCite( $citerId, $citeeId ) {		// Check the passed citee & citer ids, new->true, existed->note_id
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ("SELECT note_id FROM Cite WHERE citee_id = :citee_id AND citer_id = :citer_id");

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":citee_id", $citeeId, PDO::PARAM_INT);
		$stmt->bindParam(":citer_id", $citer_id, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch ( PDO::FETCH_ASSOC );

		if ( empty($result) ) {	// no records in Tag_of
			return true;
		} else {	// this tag still has records in Tag_of
			return $result;
		}

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

function checkTag( $tagContent ) {		// Check the passed STRING tag_content, if new->true, existed->tag_id
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ("SELECT tag_if FROM Tag WHERE tag_content = :tag_content");

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":tag_content", $tagContent, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );

		if ( empty($result) ) {	// no records in Tag_of
			return true;
		} else {	// this tag still has records in Tag_of
			return $result;
		}

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

function checkTagOf( $tagId ) {		// Check the passed tag_id, if new->true, existed->paper_id(s)
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ("SELECT pub_id FROM Tag_of WHERE tag_id = :tag_id");

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":tag_id", $tagId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetchAll ( PDO::FETCH_CLASS );

		if ( empty($result) ) {	// no records in Tag_of
			return true;
		} else {	// this tag still has records in Tag_of
			return $result;
		}

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

function deleteTag( $tagId ) {		// delete this tag from Tag table
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ("DELETE FROM Tag WHERE tag_id = :tag_id");

		$stmt = $conn->prepare( $sql );
		$stmt->bindParam(":tag_id", $tagId, PDO::PARAM_INT);
		$stmt->execute();

		return true;

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}

function getTagIdByPubId ($pubId, $tagId) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ( "SELECT tag_id FROM Tag_of WHERE pub_id = :pub_id AND tag_id = :tag_id" );
		
		$stmt = $conn->prepare($sql);
		$stmt->bindParam("pub_id", $pubId, PDO::PARAM_INT);
		$stmt->bindParam("tag_id", $tagId, PDO::PARAM_INT);
		$stmt->execute();
		$result = $stmt->fetch ( PDO::FETCH_ASSOC );

		return $result;

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;	
}

function insertTag( $tagId, $tagContent ) {
	global $SERVERNAME, $PORT, $DBNAME, $USERNAME, $PASSWORD;
		
	try {
		$conn = new PDO ( "mysql:host=$SERVERNAME;port=$PORT; dbname=$DBNAME", $USERNAME, $PASSWORD);
		// set the PDO error mode to exception
		$conn->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		$sql = ( "INSERT INTO Tag (tag_id, tag_content) VALUES (:tag_id, :tag_content)" );
		
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(":tag_id", $tagId, PDO::PARAM_INT);
		$stmt->bindParam("tag_content", $tagContent, PDO::PARAM_STR);
		$stmt->execute();

		return true;

	} catch ( PDOException $e ) {
		echo $e->getMessage ();
	}

	$conn = null;
}
?>