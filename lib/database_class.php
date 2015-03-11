<?php
/**************************************************************************************************
database_class.php
    A class definition for database connections.  All other classes extend this class.
***************************************************************************************************/
require_once('config.php');

class Database {
    private $dbc;                       // connection variable
    private $query_statement;           // query statemetn variable
    
    public function __construct() {
        $this->connect();               // connect() will attempt to connect to the database
    }   
    
    public function connect() {         // connect to database using PDO
        try {
            $this->dbc = new PDO("mysql:host=".DB_SERVER."; dbname=".DB_NAME, DB_USER, DB_PASS);
            $this->dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo "Sorry dude, Databse Connection Error: ".$this->error;
        }
    }
    
    // prepare() takes a query statement & array of arguments
    // it will bind the arguments to the query statement
    public function prepare($q, $arr=[]) {
        $this->query_statement = $this->dbc->prepare($q);
                
        foreach($arr as $index => &$value) {        // bind by reference
            $this->query_statement->bindParam( ($index+1), $value);
        }
    }
    
    // execute the query statement
    public function execute() {
        $this->query_statement->execute();
    }
    
    // fetch result set row by row
    public function fetchRow() {    
        return $this->query_statement->fetch(PDO::FETCH_ASSOC);
    }   
    
    // fetch entire result set
    public function fetchAll() {    
        return $this->query_statement->fetchAll();
    }   
    
    // return # of affected rows by query statement
    public function rowCount() {
        return $this->query_statement->rowCount();
    }   
}
