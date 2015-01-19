<?php
/*
 * 		class definition for Stock, this has the logic for a user getting a stock quote, buying stock & selling stock * 
 */


class Stock extends Database {
	private $id;
	private $symbol;
	private $last_price;
	private $table_name = "stocks";	

// The constructor will...
// 1) Call database parent to open connection to database.
// 2) Call getSymboolPrice(), that method will query Yahoo for the current stock price.
// 3) From the yahoo query, set this objects symbol and last_price properties. 
// 4) The insertPrice() method is called to place the objects properties into the database.
// 5) The updateProperty() method is called to update this objects 'id' property to the primary key value in the database
	public function __construct($symbol) {
		parent::__construct();
		$url = "http://download.finance.yahoo.com/d/quotes.csv?s=" . $symbol . "&f=sl1d1t1c1ohgv&e=.csv";
		$arr = $this->getSymbolPrice($symbol, $url);
		$this->symbol = $arr[0];
		$this->last_price = $arr[1];		
		$this->insertPrice($this->symbol, $this->last_price);		
		$this->updateProperty('id', 'symbol', $this->symbol);
	}
	
//  This is the function that gets the price from Yahoo
	public function getSymbolPrice($symbol, $url) {
		$fh = fopen($url, "r");
		$last_price = fgetcsv($fh);
		fclose($fh);
		return $last_price;			
	}
	
//  This method will place the price from Yahoo  into the database	
	public function insertPrice($symbol, $price) {
		$this->prepare("INSERT INTO ". $this->table_name  ." (symbol, last_price) VALUES(?,?) 
			ON DUPLICATE KEY UPDATE last_price = ".$price, [$symbol, $price]);
		$this->execute();
	}

//  Generic getter for the properties of a stock object
	public function getProperty($property) {
		if(property_exists($this, $property)) {
			return $this->$property;
		}
		return false;
	}	

//  Updater:  this function grabs data from the stock table and updates this objects properties.  It's only called from the constructor to update 'id' property.
	public function updateProperty($property, $search_prop, $search_prop_value ) {
		parent::__construct();
		$this->prepare("SELECT ".$property." FROM ".$this->table_name." WHERE ".$search_prop."=?", [ $search_prop_value ] );
		$this->execute();
		$arr = $this->fetchRow();
		
		foreach($arr as $index => $value) {
			if(property_exists($this, $index)) {
				$this->$index = $value;
			}			
		}				
	}
}

	
	