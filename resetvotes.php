<?php
// header("Access-Control-Allow-Origin: *");

require_once('db.php');


// drop table
try {

	// prepare sql statement to add form data
	$sql = 'DROP TABLE IF EXISTS votes';

	// prepare PDO
	$statement = $conn->prepare($sql);

	// execute statement
	$statement->execute();

	// close connection
	$statement->closeCursor();
}

catch (Exception $error) {
	echo "There was a problem with the drop sql statement.";
	$statement->getMessage();
}



// recreate table
try {

    // prepare sql statement to add form data

	$sql = 'CREATE TABLE votes (i INT(11) AUTO_INCREMENT PRIMARY KEY, vote VARCHAR(1) NOT NULL)';

	// prepare PDO
	$statement = $conn->prepare($sql);

	// execute statement
	$statement->execute();

	// close connection
	$statement->closeCursor();

   // echo out a statement
   echo "['msg': 'reset']";
}

catch (Exception $error) {
	echo "There was a problem with the create sql statement.";
	$statement->getMessage();
}
