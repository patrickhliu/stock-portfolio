<?php
/**************************************************************************************************
user_class.php
    A class definition for users.  User objects have methods that handle buying/selling stocks
***************************************************************************************************/

class User extends Database {           
    // all of the object properties copied from SQL table & are displayed on the account main page
    private $pri;                       // user's primary key value
    private $firstName;                 // user's first name
    private $lastName;                  // user's last name
    private $email;                     // user's email
    private $balance;                   // user's remaining balance
    private $table_name = "users";      // SQL table
        
    // Constructor
    public function __construct($pri) {
        parent::__construct();

        // User's info is looked up in SQL table, and copied to the object properties
        $this->prepare("SELECT pri, firstName, lastName, email, balance FROM ".$this->table_name.
                       " WHERE pri=?", [$pri]);
        $this->execute();
        $arr = $this->fetchRow();
        
        foreach($arr as $index => $value) {
            if(property_exists($this, $index)) {
                $this->$index = $value;
            }           
        }       
    }

    //  Generic getter method
    public function getProperty($property) {
        if(property_exists($this, $property)) {
            return $this->$property;
        }
        return false;
    }
    
    //  Property updater will take a value from SQL table and copy it to the corresponding object property.
    //  Needed when showing user's balance.  That number is first updated in SQL table, then copied to
    //  the object property via this function.  And this property is what is displayed on the page.
    public function updateProperty($property) {
        parent::__construct();
        $this->prepare("SELECT ".$property." FROM ".$this->table_name." WHERE pri=?", [$this->pri] );
        $this->execute();
        $arr = $this->fetchRow();
        
        foreach($arr as $index => $value) {
            if(property_exists($this, $index)) {
                $this->$index = $value;
            }
            else {
                null;
            }
        }               
    }
    
    // buyStock() handles the buying of stock
    public function buyStock($buy_stock, $qty) {                    // arguments are a stock object & qty variable
        $stockID = $buy_stock->getProperty('symbol');               // extract the stock symbol
        $cost    = $buy_stock->getProperty('last_price') * $qty;    // calculate the total cost of this transaction
        
        // if user has enough money to make purchase...
        if( $this->balance > $cost ) {                      
            // first statement (UPDATE) updates the user's balance in the SQL table
            // second statement (INSERT) updates the user's portfolio in the SQL table
            $this->prepare(
            "START TRANSACTION;            
            UPDATE users SET balance = balance - ".$cost." WHERE pri=?;                      
            INSERT INTO portfolio(user_id, symbol_id, shares, total_value) VALUES(?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE shares = shares + " . $qty . ", total_value = total_value + ". $cost ." ;
            COMMIT;"             
            , [  $this->pri, $this->pri, strtoupper($stockID), $qty, $cost ]  );
            
            $this->execute();            

            // update the object 'balance' property, it'll be displayed on the main account page
            $this->updateProperty('balance');   

            // return true to indicate to the view that purchase was successful
            return true;
        }
        else {              // else means user not have enough money
            return false;   // return false to indicate to view that purchase can't be made
        }       
    }   
       
    // sellStock handles the selling of stock
    public function sellStock($sell_stock, $qty) {                  // arguments are a stock object & qty variable
        $stockID = $sell_stock->getProperty('symbol');              // extract the stock symbol
        $cost    = $sell_stock->getProperty('last_price') * $qty;   // calculate the total of this transaction
                
        // checkPort is a stored SQL function.
        // arguments are: quantity to sell, transaction cost, user primary key, & stock symbol
        // checkPort will look at the portfolio SQL table & confirm that a user has enough shares of a certain stock to sell
        // if there are enough shares, checkPort returns true.  If not, checkPort returns false.
        // checkPort code is in the comment block below
        $this->prepare("SELECT checkPort(?, ?, ?, ?)", [  $qty, $cost, $this->pri, $stockID ]  ); 
        
        // try/catch for checkPort stored function
        try {   // keep getting error SQLSTATE[42000]: Syntax error or access violation: 1172 Result consisted of more than one row.  Fixed, added Limit 1
            $this->execute() ;                                      
        }   
        catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo "UH-OH, stored function execution isn't working...: ".$this->error;
        }   

        // fetchRow() produces:   
        // [checkPort('3', '128.55', '12', 'MSFT')] => 1      if there are enough shares to sell
        // [checkPort('3', '128.55', '12', 'MSFT')] => 0      if not enough shares to sell
        // array elements are [# of stocks to sell, cost of sale, customer ID, stock to sell]
        $return_bool = $this->fetchRow();                              
        $return_bool = array_shift( $return_bool );     // return the first element, it'll either be 1 or 0
                
        if ($return_bool) {                             // if (1)
            $this->updateProperty('balance');           // copy SQL balance value into this object's balance property
            $this->is_share_zero($stockID);             // is_share_zero() checks if there are 0 shares remaining of the sold stock 
            return true;                                // return true to indicate to view that sale was successful
        }
        else {
            return false;                               // return false to indicate to view that sale failed
        }
    }   

    // is_share_zero() checks if there are 0 shares remaining of the sold stock 
    public function is_share_zero($stockID) {
        // query SQL portfolio table for how many shares a user has left of a stock
        $this->prepare("SELECT shares FROM portfolio WHERE user_id=? AND symbol_id=?", [  $this->pri, $stockID  ]);
        $this->execute();
        
        // if 0 shares are left, delete that row from the portfolio
        if( array_shift($this->fetchRow()) == 0 ) {
            $this->prepare("DELETE FROM portfolio WHERE user_id=? AND symbol_id=?", [  $this->pri, $stockID  ] );
            $this->execute();
        }   
    }

    /* This is the checkPort() stored function
        CREATE DEFINER=`root`@`localhost` FUNCTION `checkPort`(`qty` INT, `cost` DECIMAL(15,2), `customer_id` INT, `sym_id` VARCHAR(30)) RETURNS tinyint(1)
        BEGIN
              DECLARE numShare INT DEFAULT 0;
              DECLARE result BOOL DEFAULT FALSE;
              
              SELECT shares INTO numShare
              FROM portfolio
              WHERE symbol_id = sym_id AND user_id = customer_id
              LIMIT 1;
              
              IF numShare = 0 THEN
                DELETE FROM portfolio WHERE user_id = customer_id AND symbol_id = sym_id;
                SET result = FALSE;
              ELSEIF numShare >= qty THEN 
                UPDATE users SET balance = balance + cost WHERE pri = customer_id;
                UPDATE portfolio SET shares = shares - qty, total_value = total_value - cost WHERE user_id = customer_id AND symbol_id = sym_id;
                SET result = TRUE;      
              ELSEIF numShare IS NULL THEN SET result = FALSE;
              ELSE SET result = FALSE;
              END IF;
              RETURN result;
            
            END$$

        DELIMITER ;
     */     
     
//  getPortfolio() returns a dataset of the user's portfolio to display
    public function getPortfolio() {
        $this->prepare("SELECT symbol_id, shares, total_value FROM portfolio WHERE user_id=? ", [$this->pri]);        
        $this->execute();
        return $this->fetchAll();       
    }
}
    
    