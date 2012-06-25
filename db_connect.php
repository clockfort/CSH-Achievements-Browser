<?php
    include_once( 'db_config.php' );
    include_once( 'db_queries.php' );
    include_once( 'game_queries.php' );

    ini_set( "display errors", "on" );
    error_reporting( E_ALL );

    //connect();
    
    /****
     *  Function to connect to the datbase, and select the
     *  achievements database.
     ****/
    function connect() {
        $db = mysql_connect( DB_HOST, DB_USER, DB_PASS );
        if( !$db ){
            die( 'could not connect: ' . mysql_error() );
        }

        mysql_select_db( 'achievements' );
    }

/**********************************
      GET ALL APPS/GAMES
**********************************/
    /****
     *  Retrieve all "apps" that have achievements
     ****/
    function retrieve_apps() {
        connect();
        $query = ALL_APP;
        $result = mysql_query( $query ) or die( 'Query Failed: ' .mysql_error() );
        
        echo "<ul>";

        while( $a = mysql_fetch_array( $result, MYSQL_ASSOC )){
            echo "<li>";
            $title_str;
            /*
            if( !strcmp( $a['name'], "Nethack" )){
                $title_str = "<a href='../nethack'>Nethack</a>";
            }
            else {*/
                $title_str = "<a href='game_page?app=" . $a['id'] . "'>". $a['name'] . "</a>";
           // }
            echo "<div class='game_title'><h2>".$title_str."</h2>";
            app_user_count( $a['name'] );
            app_ach_count( $a['name'] );
            echo "</div>";
            /*echo "<div class='game_achieve'>";
                retrieve_achieves( $a['id'] );
                echo "</div>";
             */    
            echo "</li>";
        }

        echo "</ul>";
    }

    function appID_to_name ( $app_id ){
    	connect();
	$query = sprintf ("SELECT `name` FROM `apps` WHERE `id` = '%s'", mysql_real_escape_string($app_id));
	$result = mysql_query( $query ) or die( 'Query Failed: ' .mysql_error() );
	$row = mysql_fetch_array( $result, MYSQL_ASSOC );
	return $row['name'];
    }

/**********************************
      GET ALL ACHIEVEMENTS
**********************************/

    /****
     *  Retrieve all achievements from the selected "app" 
     ****/
    function retrieve_achieves( $app_id ){
        connect();
        $query = GET_ACHIEVES . mysql_real_escape_string($app_id) . " LIMIT 25";
        $result = mysql_query( $query ) or die( "Query Failed: " . mysql_error() );
        if( !$result ) {
            return;
        }
        echo "<ul>";

        while( $a = mysql_fetch_array( $result, MYSQL_ASSOC )){
            echo "<li>";
            echo $a['title'];
            echo "</li>";
        }

        echo "</ul>";
    }

    /****
     *  Retrieve all achievements for a given user.
     ****/
    function retrieve_user_achieves( $user ){
        connect();
        $query = USR_ACHIEVE ."'". mysql_real_escape_string($user) . "'";
        $result = mysql_query( $query ) or die( "Query failed:" . mysql_error() );
        if( !$result ){
            return;
        }

        echo "<ul>";

        while( $a = mysql_fetch_array( $result, MYSQL_ASSOC )){
            echo "<li>";
            echo $a['title'];
            echo " ---- " . $a['app_id'];
            echo "</li>";
        }

        echo "</ul>";
    }

/**********************************
      GET APP STATISTICS
**********************************/

    /****
     *  Get App User Count
     ****/
    function app_user_count( $game ){
       connect();
       $query = NUM_USERS_APP ."'". mysql_real_escape_string($game) . "'";
       $result = mysql_query( $query ) or die( "Query failed:" . mysql_error() );
        if( !$result ){
            echo "No result.";
            return;
        }
        while( $a = mysql_fetch_array( $result, MYSQL_ASSOC )){
            echo "<div class='app_count'>";
            echo $a['count(t1.user_id)'] . "u";
            echo "</div>";
        }
    }

    /****
     * Get App Achievement Count
     ****/
    function app_ach_count( $game ){
        connect();
        $query = NUM_ACHS_APP . "'" . mysql_real_escape_string($game) . "'";
        $result = mysql_query( $query ) or die( "Query failed: " . mysql_error() );
        if( !$result ){
            echo "No result.";
            return;
        }
        while( $a = mysql_fetch_array( $result, MYSQL_ASSOC )){
            echo "<div class='app_count'>";
            echo $a['count(t1.app_id)'] . "a";
            echo "</div>";
        }
    }


/**********************************
     MISC MYSQL STUFF 
**********************************/
    /****
     *  Close all mysql connections
     ****/
    function end_queries(){
        mysql_close();
    }
?>
