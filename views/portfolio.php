<?php
/* 
 * 		This is the view for a user's portfolio.  We need to setup these variables: user id, user object with that id, and a record
 * which will do the sql query to pull the user's information from the database.  $record will be a multidimensional array where each
 * array is a record of each stock owned by that user. 
 * 		In the HTML we echo out the contents of this $record variable. 
 */
	$id = $sess->getID();
	$user = new User($id);
	$record = $user->getPortfolio();
?>

<header class="user">
	<h1>Portfolio for <?php echo $user->getProperty('firstName')." ".$user->getProperty('lastName')." (".$user->getProperty('email').")";?> </h1>
		<nav>
			<ul>
				<li><a href="?page=user.php">Account</a></li>
				<li><a href="?page=logout.php">Log Out</a></li>
			</ul>
		</nav>
	<hr>	
</header>


	<?php
// Array ( [symbol] => AAPL [last_price] => 106.74 [shares] => 11 [total_value] => 1174.14 ) 
?>
		<table border=1 id="portfolio-table">
			<tr>
				<th>Stock</th><th>Last Trading Price</th><th># of shares you own</th><th>Total Value Per Stock</th>
			</tr>
		<?php
			$total_stock_value = 0;
			foreach($record as $index=>$value ) {
		?>
			<tr>
				<td><?php echo     $record[$index][0]; ?></td>
				<td><?php echo "$".$record[$index][1]; ?></td>
				<td><?php echo     $record[$index][2]; ?></td>
				<td><?php echo "$".$record[$index][3]; ?></td>	
			</tr>
		<?php
			$total_stock_value += $record[$index][3];
			}			
		?>
		</table>
		<div id="user-assets">
			<p>Total Stock Value: <?php echo "$".$total_stock_value; ?></p>
			<p>Cash Balance:      <?php echo "$".$user->getProperty('balance'); ?></p>			
		</div>
		

