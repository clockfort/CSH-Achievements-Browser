<?php
require_once( "db_config.php" );
require_once( "db_queries.php" );
require_once( "game_queries.php" );

DEFINE("ACHIEVEMENT", "game_achievements.php?achievement=");
/***  
 * Collects statistics from the achievements database 
 * on db.csh.rit.edu
 ***/
class Statistics {
    /*** 
     * Construct a new Statistics object.
     * Connects to database.
     ***/
    function __construct( $app ) {
        if(!$app ){
            $app = 1;
        }
        $this->app = $app;
        $this->db = mysql_connect( DB_HOST, DB_USER, DB_PASS);
        if( !$this->db )
        {
            die( "Could not connect: " . mysql_error() );
        }        
        mysql_select_db("achievements");
        //$this->DB = new mysqli("server", DB_HOST, DB_USER, DB_PASS, "achievements");
    }

    /*** 
     * Get All Achievements for this application.
     ***/
    function game_achievements ( $app_id ) {
        $query = GET_ACHIEVES . $app_id;
//        $stmt = $mysqli->prepare( GET_ACHIEVES );
  //      $stmt->bind_param("s"
        $result = mysql_query ( $query ) or die( "Query Failed " . mysql_error() );
        
        if( !$result ){
            echo "<p><i>No achievements... yet</i></p>";
            return;
        }
        // Print out achievements 
        echo "<ul>";
        while( $a = mysql_fetch_array( $result, MYSQL_ASSOC )){
            echo "<li>";
            echo "<a href='" . ACHIEVEMENT . $a['title'] . "&app=". $this->app . "'>";
            echo "<div class='achievement' id='" . $a['title'] . "'>";
            echo "<h3>" . $a['title'] . " ";
            echo  "<span id='point'>" . $a['score'] . " Points</span></h3>";
            echo "<p>" . $a['description'] . "</p>";
            echo "</div>";
            echo "</a>";
            echo "</li>";
        }

        echo "</ul>";
        return;
    }

    /*** 
     * Get specific achievement
     ***/
    function getAchievement( $achieve ){
        $achieve = str_replace( "%20", " ", $achieve );
        $query = GET_ACH . "'" . $achieve . "'";
        $result = mysql_query( $query ) or die( "Query Failed: " . mysql_error());

        // Error, no result.
        if( !$result ){
            echo "<p><i>No achievement here....</i></p>";
            return;
        }

        // Print out achievement information
        while( $a = mysql_fetch_array( $result, MYSQL_ASSOC )){
            echo "<div class='achievement'>";
            echo "<h3>" . $a['title'] ." ";
            echo "<span id='point'>" . $a['score'] . " Points</psan>";
            echo "</h3>";
            echo "<p>" . $a['description'] . "</p>";
            echo "</div>";
        }
        return;
    }

    /*** 
     * Get User's Achievements
     ***/
    function getUserAchieve( $user ){
        if( is_numeric($user) ) {
            $query = USR_ACHIEVE . $user ." and t2.app_id=" . $this->app; 
        }
        else {
            $query = USR_ACHIEVE_NAME . "'" . $user ."'";
        }

        $result = mysql_query( $query ) or die( "Query Failed: " . mysql_error());

        //Error, no result.
        if( !$result || count( $result ) < 1 ){
            echo "<p><i>No achievements for this user... KEEP PLAYING!</p></i>";
            echo "<script>placePoints( 0 ); placeUser( $user );</script>";
            return;
        }
        $iter = 0;
        $myresults;
        while( $p = mysql_fetch_assoc($result)) 
        {
            require_once( "achievement.php" );
            $a = new User_Achievement( $p['title'], $p['progress_max'], $p['progress'], 
                                        $p['score'], $p['updated_at'], $p['id'] );
            $myresults[$iter] = $a;
            if( $a->prog_max == $a->prog ){
                $this->makeListJS( $a->title, $a->prog_max, $a->prog, 'achieve_comp', 'a');
            }
            else{
               $this->makeListJS( $a->title, $a->prog_max, $a->prog, 'achieve_prog', 'a');
            }
            $iter++;
        }
        if( !$myresults ){
            echo "<script>placePoints( 0 ); placeUser('$user' );</script>";
            echo "<script>no_stats()</script>";
            return;
        }
        else {
            $this->username($myresults[0]->id);
            $this->appendPoints($myresults[0]->id);
            return $myresults;
        }
    }


    /**************
     *  VIEW - FUNCTIONS.
     **************/

    /***
     * Calls list item JS function.
     ***/
    private function makeListJS( $title, $max, $prog, $id, $uid='n'){
        echo "<script>";
        echo "makeListItem('".$title."', '".$max."', '".$prog."', '".$id."', '".$uid."', $this->app );";
        echo "</script>";
    }

    /*** 
     * Creates a list without any details from JS function.
     ***/
    private function noDetailsJS( $title, $max, $prog, $id){
        echo "<script>";
        echo "noDetailList('".$title."', '".$max."', '".$prog."', '".$id."');";
        echo "</script>";
    }

    /*** 
     *  Add points to achievement.
     *  Makes a call to JS placePoints function
     ***/
    private function appendPoints( $uid ) {
        $query = "SELECT SUM(t1.score) FROM achievements as t1 INNER JOIN achievement_progress AS t2 ON t2.achievement_id=t1.id WHERE t1.progress_max=t2.progress AND t2.user_id=" .$uid;
        $result = mysql_query($query) or die('query failed: ' . mysql_error());
        $u = mysql_fetch_array($result, MYSQL_ASSOC);
        echo "<script> placePoints(". $u['SUM(t1.score)'] .")</script>";
    }

    /***
     * Places a username into the span at the top
     * Uses a JS call to placeUser
     ***/
    private function username( $uid ){
        
        $query = USR_NAME . $uid;
        $result = mysql_query($query) or die("Query failed: " . mysql_error());
        $res;
        while( $r = mysql_fetch_array($result, MYSQL_ASSOC) ){
           $res = $r['username'];
        }
        echo "<script> placeUser('".$res."');</script>";
    }
}
?>
