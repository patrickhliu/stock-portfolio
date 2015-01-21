<?php

/*  This is the view for the user's account page.  
 *  Here they can get a stock quote, buy stock, sell stock or view portfolio. 
 *  For the HTML portion, it has 3 sections: Get Quote, Buy Stock, Sell Stock, View Portfolio.
 *  Any error/success messages set by the PHP code are echo'd out in the HTML code.
 */ 

        $id = $sess->getID();                                                                       //  First get the ID of the person logged in 
        $user = new User($id);                                                                      //  Create a new user object based on this ID
        //$account_bal = $user->getProperty('balance');
                
        if(  isset($_POST['submit-get-quote']) AND !empty($_POST['symbol-get-quote'])  ) {          //  Check if user wants to get stock quote
            $symbol = urlencode($_POST['symbol-get-quote']);                                        //  Escape their stock symbol string
            $quote_stock = new Stock($symbol);                                                      //  Create a new object of type Stock, it'll get the latest price from Yahoo            
        }
        else if(  isset($_POST['submit-get-quote']) AND empty($_POST['symbol-get-quote'])  ) {      //  If user input is invalid (in this case blank), then set an error message variable, it'll display in the HTML code
            $quote_stock_msg = "Quote Error: Please Enter a stock symbol";
        }
        
        
        
        if(  isset($_POST['submit-purchase']) AND strlen($_POST['symbol-purchase'])>0 AND strlen($_POST['qty-purchase'])>0) {   // Check if user wants to buy stocks, for validation the symbol and quantity must be strings greater than length 0
            $symbol   = urlencode($_POST['symbol-purchase']);                                       //  Escape the user's stock symbol string
            $qty      = htmlspecialchars($_POST['qty-purchase']);                                   //  Escape the user's quantity string
            $buy_stock    = new Stock($symbol);                                                     //  Create a new stock object, again this will automatically query Yahoo
            
            if( preg_match( "/^[1-9]+\d*$/", $qty )) {                                              //  Validate qty string, must be start with 1-9 and any number of digits can follow
                if( $user->buyStock($buy_stock, $qty) ){                                            //  Call buyStock() method, it verifies user has enough money for purchase then does the actual purchase transaction.  buyStock() returns true or false
                    $buy_stock_msg = "You've purchased ".$qty." shares of ".$symbol;                //  If buyStock() was true, set a successful purchase message                                   
                }
                else {
                    $buy_stock_msg = "Purchase Error: There are insufficient funds for purchase..."; // Else buyStock() was false, set a purchase error message
                }           
            }
            else {
                    $buy_stock_msg = "Purchase Error: Quantity must be greater than zero...";       //  This else branch sets a message to alert user that quantity entered was invalid.  If you enter 0 or some word, those are invalid quantities.
            }           
        }
        else if(  isset($_POST['submit-purchase']) AND ( empty($_POST['symbol-purchase']) OR empty($_POST['qty-purchase']))) {  //  This is field validation for the buy stock section, basically if either field is blank set an error message.
            $buy_stock_msg = "Purchase Error: One or more fields were blank.  Please try again.";
        }
        
        
        
        if(  isset($_POST['submit-sell']) AND strlen($_POST['symbol-sell'])>0 AND strlen($_POST['qty-sell'])>0  ) {     //  This is all very similar in logic to buyStock except this is for selling stock.
            $symbol   = urlencode($_POST['symbol-sell']);
            $qty      = htmlspecialchars($_POST['qty-sell']);
            $sell_stock = new Stock($symbol);
            
            if( preg_match( "/^[1-9]\d*$/", $qty )) {
                if( $user->sellStock($sell_stock, $qty) ) {                 
                    $sell_stock_msg = "You've sold ".$qty." shares of ".$symbol;                                    
                }
                else {
                    $sell_stock_msg = "Sell Error: You don't have enough shares to sell...";
                }           
            }
            else {
                    $sell_stock_msg = "Sell Error: Quantity must be greater than zero...";
            }           
        }
        else if(  isset($_POST['submit-sell']) AND ( empty($_POST['symbol-sell']) OR empty($_POST['qty-sell']))) {
                    $sell_stock_msg = "Sell Error: One or more fields were blank.  Please try again.";
        }
?>
<header class="user">
    <h1>Welcome !</h1>
        <nav>
            <ul>
                <li><a href="?page=portfolio.php">Check Portfolio</a></li>
                <li><a href="?page=logout.php">Log Out</a></li>
            </ul>
        </nav>
    <hr>    
</header>
<section id="user-details">
    <div id="user-header">
        <h1><?php echo ucfirst($user->getProperty('firstName'))." ".ucfirst($user->getProperty('lastName'))." (".$user->getProperty('email').")"; ?></h1>
        <h2>Current Balance: <?php echo "$".  number_format(  $user->getProperty('balance'), 2, '.', ',' ) ; ?></h2>
    </div>
    
    <?php //print_r($row); ?>
    
    <form action="?page=user.php" method="POST">
            <div id="get-quote">
                <p>Get Stock Quote:</p>
                <input type="text"   name="symbol-get-quote" placeholder="Enter Stock Symbol...">
                <input type="submit" name="submit-get-quote" value="Latest Quote">
                <?php 
                    if(isset($quote_stock)) {
                ?>
                <p>
                <?php
                        echo $quote_stock->getProperty('symbol').": $".$quote_stock->getProperty('last_price');
                ?>
                </p>
                <?php                
                    } 
                    else if(isset($quote_stock_msg)) {
                ?>
                <p>
                <?php
                        echo $quote_stock_msg;
                ?>
                </p>
                <?php } ?>
            </div> <!-- end get-quote -->
            <div id="stock-purchase">
                <p>Buy Stock:</p>
                <input type="text"   name="symbol-purchase"  placeholder="Enter Stock Symbol...">
                <input type="text"   name="qty-purchase"     placeholder="Enter # to purchase...">
                <input type="submit" name="submit-purchase"  value="Purchase">
                <?php 
                    if( isset($buy_stock_msg) ) {
                ?>
                <p>
                <?php
                        echo $buy_stock_msg;
                ?>
                </p>
                <?php 
                    } 
                ?>
            </div>
            <div id="stock-sell">
                <p>Sell Stock:</p>
                <input type="text"   name="symbol-sell"  placeholder="Enter Stock Symbol...">
                <input type="text"   name="qty-sell"     placeholder="Enter # to sell...">
                <input type="submit" name="submit-sell"  value="SELL">
                <?php 
                    if( isset($sell_stock_msg) ) {
                ?>
                <p>
                <?php
                        echo $sell_stock_msg;
                ?>
                </p>
                <?php 
                    } 
                ?>
            </div>
    </form>     
</section>
