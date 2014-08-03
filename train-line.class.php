<?php
////////////////////////////////////////////////////
// Class organize train line related info
////////////////////////////////////////////////////
// Author: 	Simon Renert
// Date:		07/31/2014
////////////////////////////////////////////////////
// Change Log
// Date			Initials		Comments
// 08/01/2014	SR				Tested and enabled mysql backend functionality
////////////////////////////////////////////////////
class TrainLine {
	private $line_name;
	private $route;
	private $run_number;
	private $operator_id;

	// Constructor
	function TrainLine() {}

	public function setLineName($line_name) { $this->line_name = $line_name; }
	public function setRoute($route) { $this->route = $route; }
	public function setRunNumber($run_number) { $this->run_number = $run_number; }
	public function setOperatorId($operator_id) { $this->operator_id = $operator_id; }

	public function getLineName() { return $this->line_name; }
	public function getRoute() { return $this->route; }
	public function getRunNumber() { return $this->run_number; }
	public function getOperatorId() { return $this->operator_id; }

	// Prepares sql statement for insert into db
	function PrepareRecordToInsertIntoDB($conn) {
		$sql = "INSERT INTO `TrainLines` (LineName, Route, RunNumber, OperatorId) VALUES (?,?,?,?);";

		if ($stmt = $conn->prepare($sql)) {
			$stmt->bind_param('ssss', $this->getLineName(), $this->getRoute(), $this->getRunNumber(), $this->getOperatorId());

			// success
			return $stmt;
		}

		// failed
		return null;
	}

	// Prepares sql statement to delete from db
	function PrepareRecordToDeleteFromDB($conn) {
		$sql = "DELETE FROM `TrainLines` WHERE RunNumber=?;";

		if ($stmt = $conn->prepare($sql)) {
			$stmt->bind_param('s', $this->getRunNumber());

			// success
			return $stmt;
		}

		// failed
		return null;
	}

	// Reusable function to generate html markup for a table row
	function GenerateTableRowMarkup($editable_ind) {
		if ($editable_ind) {
			$delete_markup="<td><a href=\"manage_train_lines.php?action=delete&run_number={$this->getRunNumber()}\" title=\"Delete\">Delete</a></td>";
		}
		else {
			$delete_markup='';
		}

		$table_row ="<tr>";
		$table_row.="	${delete_markup}";
		$table_row.="	<td>{$this->getLineName()}</td>";
		$table_row.="	<td>{$this->getRoute()}</td>";
		$table_row.="	<td>{$this->getRunNumber()}</td>";
		$table_row.="	<td>{$this->getOperatorId()}</td>";
		$table_row.="</tr>";

		return $table_row;
	}
}
?>
