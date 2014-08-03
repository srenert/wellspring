<?php
////////////////////////////////////////////////////
// Manages data in db based on selected user action
////////////////////////////////////////////////////
// Author: 	Simon Renert
// Date:	08/01/2014
////////////////////////////////////////////////////
require_once $_SERVER['DOCUMENT_ROOT'] . "/train-line.class.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/datalayer.class.php";

try {
	// Connect to MySQL
	$datalayer = new DataLayer();
}
catch (Exception $e) {
	echo "Unable to connect to MySQL db! {$e->getMessage()}";
	exit;
}


try {
	// Determine which action needs to be performed
	if (isset($_GET['action'])) {
		$action = trim($_GET['action']);

		switch($action) {
			case "delete":
				if (!isset($_GET['run_number']) || trim($_GET['run_number']) == '') {
					throw new Exception("Delete action is missing run number.");
				}

				$train_line_obj = new TrainLine();
				$train_line_obj->setRunNumber(trim($_GET['run_number']));

				// Prepare sql statement to delete
				$mysqli_statement = $train_line_obj->PrepareRecordToDeleteFromDB($datalayer->getConn());

				if (!is_null($mysqli_statement)) {
					if (!$datalayer->WriteRecordToDB($mysqli_statement)) {
						throw new Exception("Unable to delete record for run number {$train_line_obj->getRunNumber()}.");
					}
					header ("Location: http://{$_SERVER['HTTP_HOST']}/view_train_lines.php");
					exit;
				}
				break;
			default:
				throw new Exception("Unexpected user action was taken - $action.");
				break;
		}
	}
	else {
		throw new Exception("Missing action.");
	}

	echo "<a href=\"view_train_lines.php\">See All Train Lines</a>";
}
catch (Exception $e) {
	echo "The following error has occurred: <br />{$e->getMessage()}<br />";
}

// Close connection
$datalayer->CloseConnection();
?>