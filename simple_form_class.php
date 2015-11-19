<?php

class simple_form{	

private $db;
private $my_table;
private $my_values_array;
private $my_columns_array;

	public function __construct ($table,$values_array,$columns_array)
	{
		$this->my_table = $table;
		$this->my_values_array = $values_array;
		$this->my_columns_array = $columns_array;
	}

	public function insert(){			
		if(empty($db)){
			$dbConnection = new dbConnection();
			$db = new MySQL($dbConnection->host,$dbConnection->userdb,$dbConnection->pass,$dbConnection->dbname);	
		}

		$values = '';

		foreach($this->my_values_array as $value){	
			$value = strip_tags(mysqli_real_escape_string($db->dbConn,$value));
			$values .= "'".$value."',";			
		}

		$columns = '';

		foreach($this->my_columns_array as $column){
			$columns .= $column.",";
		}	

		$values = trim($values, ",");
		$columns = trim($columns, ",");

		$sql = "INSERT INTO ".$this->my_table." ($columns) VALUES ($values)";
		
		$results = $db->query($sql);

		return $results->insertID();

	}

	public function update($record_id){			
		if(empty($db)){
			$dbConnection = new dbConnection();
			$db = new MySQL($dbConnection->host,$dbConnection->userdb,$dbConnection->pass,$dbConnection->dbname);	
		}

		$column_value_array = array_combine($this->my_columns_array,$this->my_values_array);

		$update_string = '';	

		foreach($column_value_array as $column=>$value){
			$value = strip_tags(mysqli_real_escape_string($db->dbConn,$value));
			$update_string .= $column."='".$value."',";	
		}

		$update_string = trim($update_string, ",");

		$sql = "UPDATE ".$this->my_table." SET $update_string WHERE ".$this->my_table."_id = $record_id";
		$results = $db->query($sql);

		return TRUE;

	}
	
	public function __destruct ()
	{
		
	}	
	
}

?>
