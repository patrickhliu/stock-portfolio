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

        if ( $this->verify_url($url) ) {                    // verify that Yahoo! can be reached...
            $arr = $this->getSymbolPrice($symbol, $url);    // getSymbolPrice parses the .csv file and extracts the stock price  

            // if 'n/a' appears, then Yahoo didn't recognize the stock symbol
            if (in_array('N/A', $arr)) {
                $this->last_price = 'error';    // notify the User view of the error
            }
            else {  // else Yahoo recognized the stock symbol
                $this->symbol     = $arr[0];    // extract symbol
                $this->last_price = $arr[1];    // extract current price
            }
        }
        else {
            $this->last_price = 'noConnect';    // notify the User view of the error
        }                
    }
    
    // getSymbolPrice parses the .csv file and extracts the stock price
    public function getSymbolPrice($symbol, $url) {
        $fh = fopen($url, "r");         // file handler will file open the URL and read the .csv
        $last_price = fgetcsv($fh);     // .csv will have one row of data, extract that row
        fclose($fh);                    // close handler
        return $last_price;             // return the one row of data
    }
    
    //  Getter method
    public function getProperty($property) {
        if(property_exists($this, $property)) {
            return $this->$property;
        }
        return false;
    }

    // method to verify the site can connect to Yahoo! to get real-time stock price
    public function verify_url($url) {
        $ch = curl_init($url);                          // initialize curl session 
        curl_setopt($ch, CURLOPT_NOBODY, true);         // exclude body
        curl_exec($ch);                                 // execute curl session
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);  // extract HTTP code
        curl_close($ch);                                // close session

        if ( $code === 200 ) {                          // if 200, then csv file exists.
            return true;
        }
        return false;
    }
}
?>   