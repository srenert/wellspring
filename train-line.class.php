<?php
	// Class organize train line related info
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
			$sql = "INSERT INTO TABLE_NAME (LineName, Route, RunNumber, OperatorId) VALUES (?,?,?,?);";

			$stmt = $conn->stmt_init();
			if ($stmt->prepare($sql)) {
				$stmt->bindParam(1, $this->getLineName());
				$stmt->bindParam(2, $this->getRoute());
				$stmt->bindParam(3, $this->getRunNumber());
				$stmt->bindParam(4, $this->getOperatorId());

				// success
				return $stmt;
			}

			// failed
			return null;
		}

	// Reusable function to generate html markup for a table row
	function GenerateTableRowMarkup() {
		$table_row ="<tr>";
		$table_row.="	<td>{$this->getLineName()}</td>";
		$table_row.="	<td>{$this->getRoute()}</td>";
		$table_row.="	<td>{$this->getRunNumber()}</td>";
		$table_row.="	<td>{$this->getOperatorId()}</td>";
		$table_row.="</tr>";

		return $table_row;
	}
  }
?>
