<?php
/**************************************************************************************************
user.php
    This is the view for the main account page. User gets to this page via the login page (home).
    This page allows users to get a stock quote, buy stocks, sell stocks or view portfolio.
***************************************************************************************************/
    $id = $sess->getID();   // After login, the login object creates a new session, session ID is primary key of user
    $user = new User($id);  // Create a new user object, it'll set it's own properties using the primary key value passed in
    
    // GET STOCK QUOTE //             
    // if user pushed "GET QUOTE" button & the field isn't empty...
    if(  isset($_POST['submit-get-quote']) AND !empty($_POST['symbol-get-quote'])  ) {
        $symbol = strtoupper(urlencode($_POST['symbol-get-quote']));    // Escape their stock symbol string, it'll be part of a URL
        $quote_stock = new Stock($symbol);                              // Create a new stock object, it'll get the latest price from Yahoo

        // On instantiation, the stock constructor will get the price from Yahoo.  If a wrong symbol was entered, 
        // Yahoo returns a 'n/a' in the CSV file and the stock object will set last_price to error.
        if ($quote_stock->getProperty('last_price') === 'error') {      // Check if an incorrect stock symbol was enetered
            $quote_stock_msg = "Quote Error: <br/> Incorrect Stock Symbol";   // if so set error message
        }
        // else Yahoo was able to give correct info for the stock symbol...
        // create a msg to show of the stock symbol and the current price received from Yahoo
        else {
            $quote_stock_msg = $quote_stock->getProperty('symbol').": $".$quote_stock->getProperty('last_price');
        }
    }
    // if user pushed "get quote" button, but the field is empty...
    // create an error msg to show
    else if(  isset($_POST['submit-get-quote']) AND empty($_POST['symbol-get-quote'])  ) {      //  Check if field was left blank, if so set error message
        $quote_stock_msg = "Quote Error: <br/> Please Enter a stock symbol";
    }

    // BUY STOCKS //        
    // if user pushes "buy" button and the stock symbol & qty fields are not empty...
    if(  isset($_POST['submit-purchase']) AND strlen($_POST['symbol-purchase'])>0 AND strlen($_POST['qty-purchase'])>0) {
        $symbol       = strtoupper(urlencode($_POST['symbol-purchase']));   // Escape their stock symbol string, it'll be part of a URL
        $qty          = htmlspecialchars($_POST['qty-purchase']);           // Escape any HTML input into string form
        $buy_stock    = new Stock($symbol);                                 // Create a new stock object, it'll get the latest price from Yahoo
        $buyTotalCost = $qty * $buy_stock->getProperty('last_price');       // Calculate total cost of the purchase

        if ($buy_stock->getProperty('last_price') === 'error') {            // Check if an incorrect stock symbol was enetered
                $buy_stock_msg = "Purchase Error: <br/> Incorrect Stock Symbol";  // if so set error message   
        }            
        else if(preg_match( "/^[1-9]+\d*$/", $qty )) {  //  Validate $qty variable, must start with 1-9 and any number of digits can follow
            if( $user->buyStock($buy_stock, $qty) ) {   //  buyStock() method will verify if user has enough money for purchase.  If so, it'll buy the stock.
                $buy_stock_msg = "Purchased: ".$buy_stock->getProperty('symbol').'<br/>'.       // Set a 'receipt' message with purchase details.
                                 "# of Shares: ".$qty.'<br/>'.
                                 "Cost/Share: $".$buy_stock->getProperty('last_price').'<br/>'.
                                 "Total: $".$buyTotalCost;
            }
            else {  // else means the user doesn't have enough money to make purchase...
                $buy_stock_msg = "Purchase Error: Insufficient balance for purchase. <br/>".    // Set a purchase error message showing how much is needed to buy
                                 "Total Required: <br/> $".$buyTotalCost;                              
            }           
        }
        else {  // else means something is wrong with qty input, like a user entered letters or characters
            $buy_stock_msg = "Purchase Error: <br/> Quantity must be a number greater than zero...";  // Set a purchase error message
        }           
    }
    // if user pushes "buy" button, but one of the fields is empty...
    else if(  isset($_POST['submit-purchase']) AND ( empty($_POST['symbol-purchase']) OR empty($_POST['qty-purchase']))) {
        $buy_stock_msg = "Purchase Error: <br/> One or more fields were blank.  Please try again.";   // Set a purchase error message
    }
    
    // SELL STOCKS //        
    // if user pushes "sell" button and the stock symbol & qty fields are not empty...
    if(  isset($_POST['submit-sell']) AND strlen($_POST['symbol-sell'])>0 AND strlen($_POST['qty-sell'])>0  ) {     
        $symbol        = strtoupper(urlencode($_POST['symbol-sell']));   // Escape their stock symbol string, it'll be part of a URL
        $qty           = htmlspecialchars($_POST['qty-sell']);           // Escape any HTML input into string form
        $sell_stock    = new Stock($symbol);                             // Create a new stock object, it'll get the latest price from Yahoo
        $sellTotalCost = $qty * $sell_stock->getProperty('last_price');  // Calculate total of the sale
            
        if ($sell_stock->getProperty('last_price') === 'error') {        // Check if an incorrect stock symbol was entered
            $sell_stock_msg = "Selling Error: <br/> Incorrect Stock Symbol";   // if so, set error message
        }            
        else if( preg_match( "/^[1-9]\d*$/", $qty )) {      //  Validate $qty variable, must start with 1-9 and any number of digits can follow
            if( $user->sellStock($sell_stock, $qty) ) {     //  sellStock() method will verify if user has enough shares to sell. If so, it'll sell the stock.
                $sell_stock_msg = "Sold: ".$sell_stock->getProperty('symbol').'<br/>'.              // Set a 'receipt' message with sale details.
                                  "# of Shares: ".$qty.'<br/>'.
                                  "Cost/Share: $".$sell_stock->getProperty('last_price').'<br/>'.
                                  "Total: $".$sellTotalCost;                                
            }
            else {  // else means the user doesn't have enough shares to sell....
                $sell_stock_msg = "Sell Error: <br/> You don't have enough shares to sell...";    // set error message
            }           
        }
        else {  // else means something is wrong with qty input, like a user entered letters or characters
            $sell_stock_msg = "Sell Error: <br/> Quantity must be a number greater than zero...";
        }           
    }
    // if user pushes "buy" button, but one of the fields is empty...
    else if(  isset($_POST['submit-sell']) AND ( empty($_POST['symbol-sell']) OR empty($_POST['qty-sell']))) {
        $sell_stock_msg = "Sell Error: <br/> One or more fields were blank.  Please try again.";  // Set a purchase error message
    }
?>

<!-- HTML CODE for user account main page -->
<header class="user cf">
    <h1>Welcome! <?php echo $user->getProperty('firstName')." ".$user->getProperty('lastName')." (".$user->getProperty('email').")";?> </h1>
        <nav>
            <ul>
                <li><a href="?page=portfolio.php">Check Portfolio</a></li>
                <li><a href="?page=logout.php">Log Out</a></li>
            </ul>
        </nav>
</header>
<section id="user-details">
    <div id="user-header">
        <h1>Current Balance: <?php echo "$".  number_format(  $user->getProperty('balance'), 2, '.', ',' ) ; ?></h1>
    </div>    
        
    <form action="?page=user.php" method="POST" name='get-quote-form'>
        <div id="get-quote">
            <p>Get Stock Quote:</p>
            <input type="text"   name="symbol-get-quote" placeholder="Enter Stock Symbol...">
            <input type="submit" name="submit-get-quote" value="Get Quote">
            <p class='get-quote-error'>     
            <?php 
                if(isset($quote_stock_msg)) {
                    echo $quote_stock_msg;     // display the message for getting stock quotes
                }
            ?>
            </p>
        </div>
    </form>

    <form action="?page=user.php" method="POST" name='buy-stock-form'>
        <div id="stock-purchase">
            <p>Buy Stock:</p>
            <input type="text"   name="symbol-purchase"  placeholder="Enter Stock Symbol...">
            <input type="text"   name="qty-purchase"     placeholder="Enter # to purchase...">
            <input type="submit" name="submit-purchase"  value="Buy">
            <p class='stock-purchase-error'>     
            <?php 
                if(isset($buy_stock_msg)) {
                    echo $buy_stock_msg;       // display the message for buying stocks
                }
            ?>
            </p>
        </div>
    </form>

    <form action="?page=user.php" method="POST" name='sell-stock-form'>
        <div id="stock-sell">
            <p>Sell Stock:</p>
            <input type="text"   name="symbol-sell"  placeholder="Enter Stock Symbol...">
            <input type="text"   name="qty-sell"     placeholder="Enter # to sell...">
            <input type="submit" name="submit-sell"  value="Sell">
            <p class='stock-sell-error'>     
            <?php 
                if(isset($sell_stock_msg)) {
                    echo $sell_stock_msg;      // display the message for selling stocks
                }
            ?>
            </p>
        </div>
    </form>     
</section>
