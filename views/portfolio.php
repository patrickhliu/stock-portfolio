<?php
/**************************************************************************************************
portfolio.php
    This is the view page for a user's portfolio.
***************************************************************************************************/
    $id = $sess->getID();               // extract user's primary key value from session object's property
    $user = new User($id);              // instantiate a new user
    $record = $user->getPortfolio();    // get SQL result set of user's portfolio/  $record is a multi-dimensional array
                                        // each sub-array has data for each stock owned by the user. 
?>

    <header class="user cf">
        <h1>Portfolio - <?php echo $user->getProperty('firstName')." ".$user->getProperty('lastName')." (".$user->getProperty('email').")";?> </h1>
            <nav>
                <ul>
                    <li><a href="?page=user.php">Account</a></li>
                    <li><a href="?page=logout.php">Log Out</a></li>
                </ul>
            </nav>  
    </header>

<?php
    // Array output:  ( [symbol_id] => AAPL [shares] => 11 [total_value] => 1174.14 ) 
?>
        <table id="portfolio-table">
            <tr>
                <th>Stock</th>
                <th>Shares</th>
                <th>Total Value</th>
            </tr>
        <?php
            $total_stock_value = 0;
            foreach($record as $index=>$value ) {
        ?>
            <tr>
                <td><?php echo     $record[$index][0]; ?></td>
                <td><?php echo     $record[$index][1]; ?></td>
                <td><?php echo '$'.$record[$index][2]; ?></td>
            </tr>
        <?php
            $total_stock_value += $record[$index][2];
            } // end foreach          
        ?>
        </table>
        <div id="user-assets">
            <p>Total Stock Value: <?php echo "$".$total_stock_value; ?></p>
            <p>Cash Balance:      <?php echo "$".$user->getProperty('balance'); ?></p>          
        </div>
        

