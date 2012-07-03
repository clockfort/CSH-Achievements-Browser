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
        $result = mysql_query($query) or die('Query Failed: ' . mysql_error());
        if( !$result || count($result) < 1 ){
            echo "You don't play this game, do you? No stats available.";
            return;
        }
        echo "<ul>";
        while($c = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            if( !$c['role'] ){
                echo "<scirpt>no_stats()</script>";
            }
            echo "<li>";
            echo $c['role'] . " " . $c['race'] . " " . $c['gender'] . " - Played " . $c['COUNT(align)'] . " Times"; 
            echo "</li>";
        }
        echo "</ul>";
    }

    function bestGame( $user ){
        connect();
        $user = mysql_real_escape_string($user);
        $query = "SELECT gender, align, role, race, points, death, deathdungeon, starttime, endtime, maxlvl FROM playlog WHERE name=" . "'" . $user ."'" . " and points=(SELECT MAX(points) FROM playlog where name=". "'" . $user . "'" . ")";
        $result = mysql_query($query) or die("Query Failed: " . mysql_error());
        echo "<h2> Best Game: </h2>";
        while( $c = mysql_fetch_array($result, MYSQL_ASSOC))
        {
            if( !$c['role'] ){
                echo "<script>no_stats();</script>";
                return;
            }
            echo "<h3>".$c['role'] . " " . $c['race'] . " " . $c['gender'] . " - With a score of: " . $c['points'] . " Points (LVL: ". $c['maxlvl'] . ")</h3>";
            echo "<p>" . $c['death'] . " in  " . $c['deathdungeon'] ."</p>";
            echo "<p>". $c['starttime'] . " - " . $c['endtime'] . "</p>";
        }

    }


    function bingeScores( $startTime, $endTime ){
        connect();
        $query = "SELECT name, gender, align, role, race, points, death, deathdungeon, starttime, endtime, maxlvl FROM playlog WHERE starttime >= '2012-07-03 14:00:00' and endtime <= '2012-07-04 02:00:00'   ORDER BY `points` DESC";
        $result = mysql_query($query) or die("Query Failed: " . mysql_error());
        echo "<h2>High Scores (Updated on death):</h2>";
	if ( mysql_num_rows($result) != 0){
	        while( $c = mysql_fetch_array($result, MYSQL_ASSOC))
	        {
	            if( !$c['role'] ){
	                echo "<script>no_stats();</script>";
	                return;
	            }
	            echo "<h3><a href=\"playlog_stats.php?app=1&user=".$c['name']."\">".$c['name']."</a>: ".$c['role'] . " " . $c['race'] . " " . $c['gender'] . " - With a score of: " . $c['points'] . " Points (LVL: ". $c['maxlvl'] . ")</h3>";
	            echo "<p>" . $c['death'] . " in  " . $c['deathdungeon'] ."</p>";
	#            echo "<p>". $c['starttime'] . " - " . $c['endtime'] . "</p>";
	        }
	}
	else{
		echo "<h3> No deaths yet this binge - Go Team Ant!</h3>";
	}
    }

?>
