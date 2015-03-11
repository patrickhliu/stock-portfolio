<?php
/**************************************************************************************************
header.php
    The header for all pages
***************************************************************************************************/
 ?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8">      
        <meta name="viewport" content="width=device-width">
        <title><?php echo htmlspecialchars($title); ?></title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700,300italic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="../css/normalize.css">
        <link rel="stylesheet" href="../css/style.css">
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="../script/script.js"></script>
    </head>
    <body>
        <header class='proj-title'>
            <h1>CS75 Project 1 - Stock Portfolio</h1>
        </header>
        
        <div id="wrapper">
