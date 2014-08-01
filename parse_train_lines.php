<?php
////////////////////////////////////////////////////
// Upload a user provided .csv file, parse it and present
// it in a user-friendly format.
// Rules:
// - Output cannot overlap. All entries displayed must be unique.
// - Sort the output detailed above in alphabetical order by Run Number.
////////////////////////////////////////////////////
// Author: 	Simon Renert
// Date:		07/31/2014
////////////////////////////////////////////////////
require_once $_SERVER['DOCUMENT_ROOT'] . "/train-line.class.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/datalayer.class.php";

// Variables
$upload_dir = "{$_SERVER['DOCUMENT_ROOT']}\\upload";

try {
	/* ---------------------------------
	// NOTES: I don't have access to MySQL database to actually run this code
	// ---------------------------------
	// Connect to MySQL
	$datalayer = new DataLayer();
	*/
}
catch (Exception $e) {
	echo "Unable to connect to MySQL db";
	exit;
}

try {
	// Make sure that form was submitted properly and user didn't access page directly
	if ($_FILES["file"]["error"] > 0) {
	  throw new Exception($_FILES["file"]["error"]);
	}
	else {
		$file_name = $_FILES["file"]["name"];
		$tmp_location = $_FILES["file"]["tmp_name"];

		$uploaded_file_path = "${upload_dir}\\${file_name}";

		// Restrict only to .csv files
		if (!preg_match("/(.*)\.csv$/",$file_name)) {
			throw new Exception("Invalid file format was uploaded.");
		}

		// Make sure unique file is submitted (optional)
		//if (file_exists("${uploaded_file_path}")) {
		//	throw new Exception("Duplicate file has already been uploaded.");
		//}

		// Save file on the server
		move_uploaded_file($tmp_location, $uploaded_file_path) or die("Unable to move file from $tmp_location to ${uploaded_file_path}");

		// Read file contents into an array of lines
		$lines_array = file($uploaded_file_path);

		// Loop through each line and if valid data, store it
		$line_number=0;
		$train_lines_array = array();

		foreach($lines_array as $line) {
			// Skip the first line (header row)
			if (!$line_number) {
				$line_number++;
				continue;
			}

			$line_number++;
			$line = str_replace("\r\n",'',$line); // remove carriage return
			$line_array = explode(',',$line); // split line into fields

			// Ensure correct number of fields per line
			if (sizeof($line_array) <> 4) {
				throw new Exception("Invalid or corrupt data on line ${line_number}");
			}

			// New TrainLine object
			$train_line_obj = new TrainLine();

			$train_line_obj->setLineName($line_array[0]);
			$train_line_obj->setRoute($line_array[1]);
			$train_line_obj->setRunNumber($line_array[2]);
			$train_line_obj->setOperatorId($line_array[3]);

			// Key is run_number to prevent dups
			$train_lines_array[$train_line_obj->getRunNumber()] = $train_line_obj;
		}

		// Make sure at least one train line was provided
		if (!sizeof($train_lines_array)) {
			throw new Exception("Empty file was submitted");
		}

		// Sort array by run number
		ksort($train_lines_array);

		$output ="<tr>";
		$output.="	<th class=\"table_header\">Train Line</th>";
		$output.="	<th class=\"table_header\">Route</th>";
		$output.="	<th class=\"table_header\">Run Number</th>";
		$output.="	<th class=\"table_header\">Operator ID</th>";
		$output.="</tr>";

		// Display results to user
		foreach($train_lines_array as $run_number => $train_line_obj){
			/* ---------------------------------
			// NOTES: I don't have access to MySQL database to actually run this code
			// ---------------------------------
			// Record data in db
			$mysqli_statement = $train_line_obj->PrepareRecordToInsertIntoDB($datalayer->getConn());

			if (!is_null($mysqli_statement)) {
				$datalayer->WriteRecordToDB($mysqli_statement);
			}
			*/

			// Create output
			$output.= $train_line_obj->GenerateTableRowMarkup();
		}

		echo "<table>$output</table>";
		echo "<a href=\"upload_train_lines.html\">Upload another</a> ";
		echo "<a href=\"view_train_lines.php\">See All Train Lines</a>";
	}
}
catch (Exception $e) {
	echo "The following error has occurred: <br />{$e->getMessage()}<br />";
	echo "<a href=\"upload_train_lines.html\">Upload another</a>";
}

/* ---------------------------------
// NOTES: I don't have access to MySQL database to actually run this code
// ---------------------------------
// Close connection
$datalayer->CloseConnection();
*/
?>