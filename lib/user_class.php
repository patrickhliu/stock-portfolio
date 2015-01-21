<?php

/*
 *      class definition for User, this handles the logic of buying and selling stock 
 * 
*/

class User extends Database {
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $balance;
    private $table_name = "users";
        
//  The constructor sets this objects properties to the info in the database.
    public function __construct($id) {
        parent::__construct();
        $this->prepare("SELECT id, firstName, lastName, email, balance FROM ".$this->table_name." WHERE id=?", [$id]);
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
    
//  Property updater function for this object, need this when we display user's balance, we get that number from the object property, not the database  
    public function updateProperty($property) {
        parent::__construct();
        $this->prepare("SELECT ".$property." FROM ".$this->table_name." WHERE id=?", [$this->id] );
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

// This method is passed the stock object to buy, and quantity
// 1) Find id# & last price of the stock object by returning it's property values (these should match db values when stock object was created) 
// 2) Verify balance is sufficient for purchase
// 3) Purchase Part 1 (start transaction): Deduct cost amount from user's balance in database
// 4) Purchase Part 2: Insert a record of this purchase into the portfolio table.  If duplicae stock is purchased, update the # shares & total value instead.
// 5) Update user's balance property equal to the new balance in database
    public function buyStock($buy_stock, $qty) {            
        $stockID = $buy_stock->getProperty('id');
        $cost    = $buy_stock->getProperty('last_price') * $qty;                
        
        if( $this->checkBalance($cost) ) {
                
            $this->prepare(
            "START TRANSACTION;
            UPDATE users SET balance = balance - ".$cost." WHERE id=?;          
            INSERT INTO portfolio(user_id, symbol_id, shares, total_value) VALUES(?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE shares = shares + " . $qty . ", total_value = total_value + ". $cost ." ;
            COMMIT;" 
            , [  $this->id, $this->id, $stockID, $qty, $cost ]  ); # END prepare statement      
            $this->execute();           
            
            $this->updateProperty('balance');           
            return true;
        }
        else {
            return false;
        }       
    }   

//  This method verifies enough money is left to make a purchase
    public function checkBalance($cost) {
        if( ($this->balance - $cost) > 0 ) {
            return true;
        }   
        return false;       
    }
        
// This is the method for selling stock, most confusing part, the prepare() parameter is a stored SQL function
// checkPort is a stored function, it contains the logic of verifying if a user has enough shares to sell, if so it'll update
// user's balance, # of shares owned, and total value of shares owned.
    public function sellStock($sell_stock, $qty) {
        $stockID = $sell_stock->getProperty('id');
        $cost    = $sell_stock->getProperty('last_price') * $qty;       
                
        $this->prepare("SELECT checkPort(?, ?, ?, ?)", [  $qty, $cost, $this->id, $stockID ]  ); # checkPort is a stored function, definition is in comments below...
        
        try {
            $this->execute() ;                                      // keep getting error SQLSTATE[42000]: Syntax error or access violation: 1172 Result consisted of more than one row.  Fixed, added Limit 1
        }   
        catch(PDOException $e) {
            $this->error = $e->getMessage();
            echo "UH-OH, stored function execution isn't working...: ".$this->error;
        }   
        
        $return_bool = $this->fetchRow();                           // fetchRow() produces...Array ( [checkPort('1', '106.74', '27', '139')] => 1 )
        $return_bool = array_shift( $return_bool );             
                
        if ($return_bool) {
            $this->updateProperty('balance');
            $this->is_share_zero($stockID); 
            return true;
        }
        else {
            return false;           
        }
    }   

// After selling, this method checks if shares remaining is zero, if so delete that record from portolio
    public function is_share_zero($stockID) {
        $this->prepare("SELECT shares FROM portfolio WHERE user_id=? AND symbol_id=?", [  $this->id, $stockID  ]);
        $this->execute();
        
        if( array_shift($this->fetchRow()) == 0 ) {
            $this->prepare("DELETE FROM portfolio WHERE user_id=? AND symbol_id=?", [  $this->id, $stockID  ] );
            $this->execute();
        }   
    }

    
    /* This is the checkPort() stored function that gets called by sellStock() above
        
        DELIMITER $$
        DROP FUNCTION IF EXISTS checkPort;
        CREATE FUNCTION checkPort(qty INT, cost DECIMAL(15,2), user_id INT, symbol_id INT)
        RETURNS BOOL
        BEGIN
            DECLARE numShare INT;
            DECLARE result BOOL DEFAULT FALSE;
            
            SELECT shares INTO numShare
            FROM portfolio 
            WHERE user_id=user_id AND symbol_id=symbol_id;
            
            IF numShare = 0 THEN
                DELETE FROM portfolio WHERE user_id=user_id AND symbol_id=symbol_id;
                SET result = FALSE;
            ELSEIF numShare >= qty THEN 
                UPDATE users SET balance = balance + cost WHERE id=user_id;
                UPDATE portfolio SET shares = shares - qty, total_value = total_value - cost WHERE user_id=user_id AND symbol_id=symbol_id;
                SET result = TRUE;          
            ELSE SET result = FALSE;
            END IF;
            RETURN result;      
        END $$      
        DELIMITER ;
     *      
     */ 

     
//  this method returns result set of all stocks owned by a given user id
    public function getPortfolio() {
        $this->prepare("SELECT stocks.symbol, stocks.last_price, portfolio.shares, portfolio.total_value 
                        FROM portfolio 
                        INNER JOIN stocks ON portfolio.symbol_id = stocks.id 
                        WHERE portfolio.user_id=? ", [$this->id]);
        
        $this->execute();
        return $this->fetchAll();       
    }
}
    
    