<?php


require_once (__DIR__.'\..\db\db_connect.php');
	
	
class Warning{
	
  
    // database connection and table name
    private $db;
    private $table_name = "warnings";
  
    // object properties
    public $warning_id;
    public $patient_number;
    public $sensor;
	public $warning_date;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
		// read warning's data
	function readAll($params){
  
    // select all query
	  $query = "SELECT
                *
            FROM
                " . $this->table_name . " w
            WHERE
                w.patient_number = :patient_number

            ";
	   
	// prepare query statement
	$stmt = $this->conn->prepare($query);
	
	$stmt->bindParam(":patient_number", $params['patient_number']);
  
        // execute query
    $stmt->execute();
	
    return $stmt;
	}
	
		// create reading
	function create(){
		
		// query to insert reading
		$query = "INSERT INTO " . $this->table_name . " (sensor,patient_number,warning_date) VALUES (:sensor, :patient_number, :warning_date)";
	  
	 
	  // prepare query
      $stmt = $this->conn->prepare($query);


		// bind values
		$stmt->bindParam(":sensor", $this->sensor);
		$stmt->bindParam(":patient_number", $this->patient_number);
		$stmt->bindParam(":warning_date", $this->warning_date);
	

		// execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
		  
}

function searchByStartEndDate($data){
  

	if($data['start_date']!=$data['end_date'])
	{
    $query = "SELECT
                *
            FROM
                " . $this->table_name . "
            WHERE 
			
			patient_number = :patient_number AND
			
			warning_date 
			
			BETWEEN
			
                :start_date
			AND
				:end_date
	";
	
	$startEndDifferent=true;
	
	
	}
	else{
		
		$query = "SELECT
                *
            FROM
                " . $this->table_name . " w
            WHERE 
			
			DATE(w.warning_date)= :start_date
			
			AND
			
			patient_number=:patient_number
	";
	$startEndDifferent=false;
		
	}
	 $stmt = $this->conn->prepare( $query );
	 
	if($startEndDifferent)
		$stmt->bindParam(":end_date", $data['end_date']);
	
	$stmt->bindParam(":start_date", $data['start_date']);
	$stmt->bindParam(":patient_number", $data['patient_number']);
  
    // prepare query statement
   
	
    // execute query
    if($stmt->execute()){
	
		return $stmt;
	}
	
}
}
