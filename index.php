<!DOCTYPE HTML>
<html>

<?php
    include( "db_connect.php" );
?>
    <head>
        <title>CSH Achievements</title>
        <link rel="stylesheet" href="style.css" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,500' rel='stylesheet' type='text/css'>
    </head>

    <body>
        <header>
            <h1>Computer Science House Achievements</h1>
        </header>
        <section id="games">
            <h2>CSH Games:</h2>
            <?php retrieve_apps(); ?>
        </section> 

    </body>
</html>
