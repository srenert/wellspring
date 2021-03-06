<?php
////////////////////////////////////////////////////
// Class to commonalize database functionality
////////////////////////////////////////////////////
// Author: 	Simon Renert
// Date:		07/31/2014
////////////////////////////////////////////////////
// Change Log
// Date			Initials		Comments
// 08/01/2014	SR				Tested and enabled mysql backend functionality
////////////////////////////////////////////////////
class DataLayer {
	private $conn;

	function DataLayer() {
		$this->ConnectToDB('p3nlmysql113plsk.secureserver.net', 'src_user', 'PaQ8oZO7jt', 'srcreations_db', 3306);
	}

	public function setConn($conn) { $this->conn = $conn; }
	public function getConn() { return $this->conn; }

	// Connects to DB and sets connection obj
	public function ConnectToDB($somehost, $username, $password, $dbname, $port) {
		// Connection values
		$this->setConn(new mysqli($somehost, $username, $password, $dbname, $port));
		if ($this->getConn()->connect_errno) {
			$this->LogError("Failed to connect to MySQL: (" . $this->getConn()->connect_errno . ") " . $this->getConn()->connect_error);
		}
	}

	// Inserts a record into db
	public function WriteRecordToDB($stmt) {
		// Execute sql statement
		$stmt->execute();

		if ($stmt->error) {
			$this->LogError("Failed to insert record - {$stmt->error}");

			// failed
			return 0;
		}

		// success
		return 1;
	}

	// Query DB and return result set
	public function ReadDBGetResultSet($sql_statement) {
		if ($result = $this->getConn()->query($sql_statement)) {
			return $result;
		}

		return null;
	}

	// Close connection
	public function CloseConnection() {
		$this->getConn()->close();
	}

	public function LogError($error) {
		// some method of logging error, i.e email to webmaster, insert into error log, insert into error table, etc..
		echo $error;
	}
}
?>
