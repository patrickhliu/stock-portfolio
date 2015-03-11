<?php
/**************************************************************************************************
stock_class.php
    A class definition for stock symbols.  Stock objects have methods that handle getting current
    stock price from Yahoo
***************************************************************************************************/

class Stock extends Database {
	private $symbol;
	private $last_price;

	// Constructor
	public function __construct($symbol) {
		parent::__construct();
		
		// yahoo url to get stock price
		$url = "http://download.finance.yahoo.com/d/quotes.csv?s=" . $symbol . "&f=sl1d1t1c1ohgv&e=.csv";
		
		// getSymbolPrice parses the .csv file and extracts the stock price
		$arr = $this->getSymbolPrice($symbol, $url);

		// if 'n/a' appears, then Yahoo didn't recognize the stock symbol
		if (in_array('N/A', $arr)) {
			$this->last_price = 'error';	// notify the User view of the error
		}
		else {	// else Yahoo recognized the stock symbol
			$this->symbol     = $arr[0];	// extract symbol
			$this->last_price = $arr[1];	// extract current price
		}
	}
	
	// getSymbolPrice parses the .csv file and extracts the stock price
	public function getSymbolPrice($symbol, $url) {
		$fh = fopen($url, "r");			// file handler will file open the URL and read the .csv
		$last_price = fgetcsv($fh);		// .csv will have one row of data, extract that row
		fclose($fh);					// close handler
		return $last_price;				// return the one row of data
	}
	
	//  Getter method
	public function getProperty($property) {
		if(property_exists($this, $property)) {
			return $this->$property;
		}
		return false;
	}	
}

	
	