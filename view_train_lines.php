<?php
////////////////////////////////////////////////////
// Read MySQL DB and preset user with all train lines entered thus far
////////////////////////////////////////////////////
// Author: 	Simon Renert
// Date:		07/31/2014
////////////////////////////////////////////////////
require_once $_SERVER['DOCUMENT_ROOT'] . "/train-line.class.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/datalayer.class.php";

try {
	/* ---------------------------------
	// NOTES: I don't have access to MySQL database to actually run this code
	// ---------------------------------
	// Connect to MySQL
	$datalayer = new DataLayer();
	*/
	echo "I don't have access to MySQL database to actually run this code.<br />";
	echo "See view_train_lines.php for code.";
	echo "<a href=\"upload_train_lines.html\">Upload another</a> ";
	exit;
}
catch (Exception $e) {
	echo "Unable to connect to MySQL db";
	exit;
}

try {
	// Select statement (TABLE_NAME is a placeholder)
	$sql_statement = "SELECT LineName, Route, RunNumber, OperatorId FROM TABLE_NAME ORDER BY RunNumber;";
	$result = $datalayer->ReadDBGetResultSet($sql_statement);

	// Display non-empty result set
	if (!is_null($result) && $result->num_rows) {
		$output ="<tr>";
		$output.="	<th class=\"table_header\">Train Line</th>";
		$output.="	<th class=\"table_header\">Route</th>";
		$output.="	<th class=\"table_header\">Run Number</th>";
		$output.="	<th class=\"table_header\">Operator ID</th>";
		$output.="</tr>";

		while ($row = $result->fetch_assoc()) {
			// New TrainLine object
			$train_line_obj = new TrainLine();

			$train_line_obj->setLineName($row['LineName']);
			$train_line_obj->setRoute($row['Route']);
			$train_line_obj->setRunNumber($row['RunNumber']);
			$train_line_obj->setOperatorId($row['OperatorId']);

			// Create output
			$output.= $train_line_obj->GenerateTableRowMarkup();
		}

		echo "<table>$output</table>";
	}
	else { echo "No records to display.<br />"; }

	echo "<a href=\"upload_train_lines.html\">Upload another</a> ";
}
catch (Exception $e) {
	echo "The following error has occurred: <br />{$e->getMessage()}<br />";
}

/* ---------------------------------
// NOTES: I don't have access to MySQL database to actually run this code
// ---------------------------------
// Close connection
$datalayer->CloseConnection();
*/
?>