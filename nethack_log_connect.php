<?php
    /***
     * Performs queries for the playlog stats.
     ***/
    require_once( 'game_queries.php' );
    require_once( 'db_config.php' );

    function connect() {
        $db = mysql_connect(NH_HOST, NH_USER, NH_PASS);
        if(!$db) {
            die('could not connect: ' . mysql_error() );
        }
        mysql_select_db('nethack');
    }
    // Acquire Top 5 Most Played Characters
    function topCharacters( $user ){
        connect();
        $query = CHARACTER_DETAIL . "'". $user . "' GROUP BY race, role, align, gender ORDER BY COUNT(align) DESC LIMIT 5";
        echo "<h2>Top 5 Played Characters</h2>";
        echo "<ul>";
        $result = mysql_query($query) or die('Query Failed: ' . mysql_error());
        while($c = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            echo "<li>";
            echo $c['role'] . " " . $c['race'] . " " . $c['gender'] . " - Played " . $c['COUNT(align)'] . " Times"; 
            echo "</li>";
        }
        echo "</ul>";
    }

    function bestGame( $user ){
        connect();
        $query = BEST_GAME . "'" . $user ."'";
        $result = mysql_query($query) or die("Query Failed: " . mysql_error());
        echo "<h2> Best Game: </h2>";
        while( $c = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            echo "<h3>".$c['role'] . " " . $c['race'] . " " . $c['gender'] . " - With a score of: " . $c['MAX(points)'] . " Points (LVL: ". $c['maxlvl'] . ")</h3>";
            echo "<p>" . $c['death'] . " in  " . $c['deathdungeon'] ."</p>";
            echo "<p>". $c['starttime'] . " - " . $c['endtime'] . "</p>";
        }

    }
?>
