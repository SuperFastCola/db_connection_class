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
    $this->connection = mysql_connect($this->sql['db_host'], $this->sql['db_user'], $this->sql['db_pass']);
        
     // Select assigned DB
     if (!mysql_select_db($this->sql['db_name'])) {
       die("Could not connect to DB");
     }
  }
  
  //execute a query 
  public function execute($sqlrequest){
    $this->command = mysql_query("SET NAMES utf8");
    $this->command = mysql_query($sqlrequest,$this->connection);
  }
  
    //returns results
  public function fetchObject($multiple = false){
    unset($this->results);
    
    if($multiple){
    while($this->row = mysql_fetch_object($this->command)){
      $this->results[] = $this->row;
    }
    }
    else{
      $this->results = mysql_fetch_object($this->command);
    }
    
    return $this->results;
  }
  
  
  //returns results
  public function fetchArray(){
    unset($this->results);
    
    while($this->row = mysql_fetch_assoc($this->command)){
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
   return mysql_insert_id($this->connection);
  }
  
  
    //gets number of rows affects
  public function dataRows($affectedRows=false){
    
    $output;
    
     //for modify statements INSERT, UPDATE, REPLACE or DELETE
    if($affectedRows){
      $output = mysql_affected_rows($this->connection);
    }
    //for query statements SELECT or SHOW
    else{
       $output = mysql_num_rows($this->command);
    }
    
    return $output;
  }//end public function

  public function clean($text){
    $cleanerPattern = "/[#?!\\\^=\$%&*+\(\)~\[\];]/";
    $text = addslashes($text);
    return preg_replace($cleanerPattern,"",$text);
  }

}

?>