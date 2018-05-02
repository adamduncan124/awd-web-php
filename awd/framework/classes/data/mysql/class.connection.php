<?php

/*
Project: AWD Framework
Created By Adam Duncan
Date: 04/25/2015
data/class.connection.php
---------------------------------------
Change Log
---------------------------------------
03/08/2017 awd - Added global parameters and moved duplicate code to method
04/16/2018 awd - moved to mysql namespace, added interface, and move items out of construct
*/

namespace AWD\Data\MySQL{
  class Connection implements AWD\Interfaces\Data\iConnection{
	private $dbName = "";
	private $userName = "";
	private $passWord = "";
	private $serverName = "";
	private $results;
    private $params;
	private $dbh;
	
	public function __construct() {
		
	}
	
	private function ConnectToDb(){
		if(!isset($this->dbh))
			$this->dbh=mysql_connect($this->serverName, $this->userName, $this->passWord) or die ('I cannot connect to the database because: ' . mysql_error());
		
		mysql_select_db($this->dbName);
	}
	
	public function SelectDb($dbname=""){
		global $config;
		
		//test if config has more then one database option and find it
		if($db_config = awd_find_dbconfig($dbname))
			\AWD\Exceptions\DataException::ThrowMissingConnection();
		
		$this->dbName = $db_config['dbname'];
		$this->userName = $db_config['username'];
		$this->passWord = $db_config['password'];
		$this->serverName = $db_config['servername'];
			
		//open connection by the name of the passed variable
		//open connection by the name of the passed variable
		$this->ConnectToDb();
	}
    
  private function BuildSql($sql){
    $numParams = func_num_args();
    $params = func_get_args();
    
    if ($numParams > 1) {
			for ($i = 1; $i < $numParams; $i++){
				$params[$i] = mysql_real_escape_string($params[$i]);
			}
		   
			$sql = call_user_func_array('sprintf', $params);
	  }elseif (isset($this->params)){
      $globalParams = $this->params;
      
  		for ($i = 0; $i < count($globalParams); $i++){
			  $globalParams[$i] = mysql_real_escape_string($globalParams[$i]);
		  }
      array_unshift($globalParams, $sql);
      
      $sql = call_user_func_array('sprintf', $globalParams);
    }
    
    /* --AWD IDEA FOR LIKE
    $numParams = func_num_args();
		$params = func_get_args();
		
		if ($numParams > 1) {
			for ($i = 1; $i < $numParams; $i++){
				$params[$i] = mysql_real_escape_string($params[$i]);
			}
			
			if(strripos($params[0]," like ") === false){
				$mySql = call_user_func_array('sprintf', $params);
			}else{ //hack for like
				$mySql = $params[0];
				for($i = 1; $i < $numParams; $i++){
					$mySql = preg_replace('/%s/', $params[$i], $mySql, 1);
				}
			}
		}
    */

    return $mySql;
  }
	
  //add parameters for sql run
  public function AddParameter($value){
    if(!isset($this->params))
      $this->params = array();
    
    //array_push($this->params, $value);
	$this->params[] = $value;
  }
    
	//this returns an array of data
	public function SetResults($sql) {
		$sql = call_user_func_array(array($this, "BuildSql"), func_get_args());

		$this->results = mysql_query($sql,$this->dbh);
		
		if(!$this->results){
			return false;
		}else{
			return true;
		}
	}
	
	//this function returns a fetched array for a row
	public function FetchRow(){
		if($row = mysql_fetch_array($this->results, MYSQL_ASSOC)){
			return $row;
		}else{
			mysql_free_result($this->results);
			return false;
		}
	}
	
	public function LoadTableColumns($table, $show_settings=false){
		$columns = array();
		
		$i = 0;
		$strSql = "SHOW COLUMNS FROM " . $table;
		$my_results = mysql_query($strSql,$this->dbh);

		if($my_results){
		  if (mysql_num_rows($my_results) > 0) {
    		while ($row = mysql_fetch_assoc($my_results)) {				
        		$columns[$i] = $show_settings ? $row : $row['Field'];
				$i++;
    		}
		  }
		}
		
		return $columns;
	}
	
	//just execute the query
	public function ExecuteSql($sql){   
    $sql = call_user_func_array(array($this, "BuildSql"), func_get_args());
    
		$results = mysql_unbuffered_query($sql,$this->dbh);
		if(!$results){
			return false;
		}else{
			return true;
		}
	}
	
	//get the count back by sql count
	public function ExecuteScalar($sql){
		$sql = call_user_func_array(array($this, "BuildSql"), func_get_args());
		
		$results = mysql_query($sql,$this->dbh);
		if(!$results){
			return false;
		}else{
			return mysql_result($results,0);
		}
	}
	
	//get the identity back
	public function ReturnIdentity(){
		return mysql_insert_id();
	}
                   
    public function Reset(){
      $this->params = null;
      $this->results = null;
      $this->dbh = null;
      
      $this->ConnectToDb();
    }
	
	public function Close() {
		//close connection
		mysql_close($this->dbh);
		//echo("Note: Remove This After Testing - DB Dead");
	}
  }
}
?>
