<?php
//command line PHP requires <?php at top of required/included files - otherwise see them as text
class DB 
{
  public $sql;
  public $results = NULL;
  public $row = NULL;
  public $command = NULL;
  public $connection = NULL;

  public function __construct() {
    //this is used when executing from web browser
    //$this->sql = array('host'=>$_SERVER["PARAM1"],'user'=>$_SERVER["PARAM2"],'pass'=>$_SERVER["PARAM3"],'db'=>$_SERVER["PARAM4"]);

    $passed_in_connection_object = func_get_args(); 
    $number_of_args = func_num_args();

    $this->sql = $passed_in_connection_object[0];
  }
    
  public function connect()
  {
     // Connect to MySQL
     $this->connection = new mysqli($this->sql['db_host'], $this->sql['db_user'], $this->sql['db_pass'], $this->sql['db_name'], $this->sql['db_port']);

     // Select assigned DB
     if($this->connection->connect_errno){
       die("Could not connect to DB");
     }

  }
  
  //execute a query 
  public function execute($sqlrequest){
    $this->connection->set_charset("utf8");
    $this->command = $this->connection->query($sqlrequest);

    if(isset($this->connection->error)){
      echo $this->connection->error; 
    }
  }
  
    //returns results
  public function fetchObject($multiple = false){
    unset($this->results);
    
    if($multiple){
    while($this->row = $this->command->fetch_object()){
      $this->results[] = $this->row;
    }
    }
    else{
      $this->results = $this->command->fetch_object();
    }
    
    return $this->results;
  }
  
  
  //returns results
  public function fetchArray(){
    unset($this->results);

    while($this->row = $this->command->fetch_assoc()){
      $this->results[] = $this->row;
    }
    
    if(sizeof($this->results)==1){
      return $this->results[0];
    }
    else{
      return $this->results;  
    }
    
  }
  
  //gets last id of inserted query
  public function lastInsertId(){
   return $this->connection->insert_id;
  }
  
  
    //gets number of rows affects
  public function dataRows($affectedRows=false){
    
    $output;
    
     //for modify statements INSERT, UPDATE, REPLACE or DELETE
    if($affectedRows){
      $output = $this->connection->affected_rows;
    }
    //for query statements SELECT or SHOW
    else{
       $output = $this->command->num_rows;
    }
    
    return $output;
  }//end function

  public function clean($text){
    $cleanerPattern = "/[#?!\\\^=\$%&*+\(\)~\[\];]/";
    $text = addslashes($text);
    return preg_replace($cleanerPattern,"",$text);
  }

}

?>