<?php
include_once( 'db_config.php' );
include_once( 'db_connect.php' );
require_once( 'game_queries.php' );

    ini_set("display errors", "on");
    error_reporting(E_ALL);

    // max constants
    connect();
    $query = "SELECT count(id) FROM users";
    $result = mysql_query($query) or die('Query Failed: ' . mysql_error());
    $u = mysql_fetch_array($result, MYSQL_ASSOC);
    $a = $u['count(id)'];
    DEFINE('MAX_USERS', $a);

    $query = "SELECT count(id) FROM achievements";
    $result = mysql_query($query) or die('Query Failed: ' . mysql_error());
    $u = mysql_fetch_array($result, MYSQL_ASSOC);
    $a = $u['count(id)'];
    DEFINE('MAX_ACHIEVE', $a);

    $query = "SELECT SUM(score) FROM achievements";
    $result = mysql_query($query) or die('Query Fialed: ' . mysql_error());
    $u = mysql_fetch_array($result, MYSQL_ASSOC);
    $a = $u['SUM(score)'];
    DEFINE('MAX_POINTS', $a);

/** 
 *  List of achievements and descriptions
 */
function achievements(){
    connect();
    $query = ALL_ACH;
    $result = mysql_query($query) or die('Query Failed: ' .mysql_error());
    echo "<ul>";
    while($a = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        echo "<li>";
        echo "<a href='game_achievements.php?achievement=".$a['title']."'>";
        echo "<div class='achievement' id='".$a['title']."'>";
        echo "<h3>".$a['title']." <span id='point'> ". $a['score']." Points </span></h3>";
        echo "<p>".$a['description']."</p>";
        echo "</div>";
        echo "</a>";
        echo "</li>";
    }
    echo "</ul>";
    mysql_close();
}

/** 
 * Get selected achievement stats.
 */
function getAchievement($achieve) {
    connect();
    $achieve = str_replace("%20", " ", $achieve);
    $query = GET_ACH . "'" . $achieve . "'";
    $result = mysql_query($query) or die('Query Failed: ' .mysql_error());
    while( $a = mysql_fetch_array($result, MYSQL_ASSOC)){
        echo "<div class='achievement'>";
        echo "<h3>".$a['title']."<span id='point'> ". $a['score']." Points </span></h3>";
        echo "<p>".$a['description']."</p>";
        echo "</div>";
    }
    mysql_close();
}

/**
 * Query the database for the top 10 achievement holders
 */
function mostAchievements() {
    connect();
    $query = G_TOPUSERS;
    $result = mysql_query($query) or die('Query Failed: ' .mysql_error());
    while($m = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        $res;
        $it = 0;
        foreach( $m as $a ){
            $res[$it] = $a;
            $it++;
        }
        makeListJS($res[0], MAX_ACHIEVE, $res[1], 'g_toplist', $res[2]);
    }
}

/** 
 * Query for top most-point users 
 */
function topUsers($app_id) {
    connect();
    $query = "SELECT SUM(t3.score), t1.username FROM (users AS t1 INNER JOIN achievement_progress AS t2 ON t1.id=t2.user_id) INNER JOIN achievements AS t3 ON t2.achievement_id=t3.id WHERE t2.progress=t3.progress_max and app_id='" . mysql_real_escape_string($app_id) . "' GROUP BY t1.id ORDER BY SUM(t3.score) DESC, t2.updated_at DESC LIMIT 10";
#    $query = G_SCOREUSERS;
    $result = mysql_query($query) or die('Query Failed: ' . mysql_error());
    while($t = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        makeListJS($t['username'], MAX_POINTS, $t['SUM(t3.score)'], 'g_toplist', $t['username']);
    } 
}

/**
 * Query the database for which achievements have the most holders.
 */ 
function globalAchievements($app_id) {
    connect();
    $query = G_PROGRESS;
    $query = "SELECT t2.title, COUNT(user_id) FROM achievement_progress AS t1 JOIN achievements AS t2 ON t1.achievement_id=t2.id WHERE t1.progress=t2.progress_max and app_id='". mysql_real_escape_string($app_id) ."' GROUP BY t1.achievement_id ORDER BY COUNT(user_id) DESC LIMIT 10";

    $result = mysql_query($query) or die('Query Failed: ' . mysql_error());
    while( $glo = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        $res;
        $it = 0;
        foreach($glo as $g)
        {
            $res[$it] = $g;
            $it++;
        }
        makeListJS($res[0], MAX_USERS, $res[1], 'g_achieve', "a" );
    }
}

/** 
 *  Get User Achievements per user.
 */
function userAchievements( $user ) { 
    connect();
    if( is_numeric($user) ) {
        $query = USR_ACHIEVE . $user;
    }
    else {
        $query = USR_ACHIEVE_NAME . "'" . $user . "'";
    }
    $result = mysql_query($query) or die('Query Failed: ' . mysql_error());
    $iter = 0;
    $myresults;
    while( $per_achieve = mysql_fetch_array($result, MYSQL_ASSOC)) 
    {
        $myresults[$iter]['title'] = $per_achieve['title'];
        $myresults[$iter]['progress_max'] = $per_achieve['progress_max'];
        $myresults[$iter]['progress'] = $per_achieve['progress'];
        $myresults[$iter]['score'] = $per_achieve['score'];  
        $myresults[$iter]['updated_at'] = $per_achieve['updated_at'];
        $myresults[$iter]['id'] = $per_achieve['user_id'];
        if( $myresults[$iter]['progress_max'] == $myresults[$iter]['progress'] ){
            makeListJS( $myresults[$iter]['title'], $myresults[$iter]['progress_max'], $myresults[$iter]['progress'], 'achieve_comp', 'a');
        }
        else{
           makeListJS( $myresults[$iter]['title'], $myresults[$iter]['progress_max'], $myresults[$iter]['progress'], 'achieve_prog', 'a');
        }
        $iter++;
    }
    username($myresults[0]['id']);
    appendPoints($myresults[0]['id']);
    mysql_close();
    return $myresults;
}

/**
 * 5 Recent Achievements 
 */
function recentAchievements($app_id) {
    connect();
    $query = "SELECT t2.title, t3.username FROM (achievement_progress AS t1 INNER JOIN achievements AS t2 ON t1.achievement_id=t2.id ) INNER JOIN users AS t3 ON t1.user_id=t3.id WHERE t1.progress=t2.progress_max and app_id='" . mysql_real_escape_string($app_id) . "' ORDER BY t1.updated_at DESC LIMIT 5";
    $result = mysql_query($query) or die('Query Failed: ' . mysql_error());
    while($rec = mysql_fetch_array($result, MYSQL_ASSOC)){
        echo "<li>";
        echo "<a href='game_achievements.php?app=1&achievement=" . $rec['title'] . "'>".$rec['title']."</a> -- Completed by: <a href='game_userpage.php?app=1&user=".$rec['username'] ."'>" .$rec['username'] . "</a>"; 
        echo "</li>";
    }
}

/** 
 * All Users
 */
function userList() {
    connect();
    $query = ALL_USERS;
    $result = mysql_query($query) or die('Query Failed: ' . mysql_error());
    while( $user = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        echo "<li>";
        echo "<a href='game_userpage.php?app=1&user=".$user['id']."'>".$user['username']."</a>";
        echo "</li>";    
    }
}

function makeListJS( $title, $max, $prog, $id, $uid='n'){
    echo "<script>";
    echo "makeListItem('".$title."', '".$max."', '".$prog."', '".$id."', '".$uid."', 1);";
    echo "</script>";
}

function noDetailsJS( $title, $max, $prog, $id){
    echo "<script>";
    echo "noDetailList('".$title."', '".$max."', '".$prog."', '".$id."');";
    echo "</script>";
}

function appendPoints( $uid ) {
    $query = "SELECT SUM(t1.score) FROM achievements as t1 INNER JOIN achievement_progress AS t2 ON t2.achievement_id=t1.id WHERE t1.progress_max=t2.progress AND t2.user_id=" .$uid;
    $result = mysql_query($query) or die('query failed: ' . mysql_error());
    $u = mysql_fetch_array($result, MYSQL_ASSOC);
    echo "<script> placePoints(". $u['SUM(t1.score)'] .")</script>";
}

function username( $uid ){
    connect();
    $query = USR_NAME . $uid;
    $result = mysql_query($query) or die("Query failed: " . mysql_error());
    $res;
    while( $r = mysql_fetch_array($result, MYSQL_ASSOC) ){
       $res = $r['username'];
    }
    echo "<script> placeUser('".$res."');</script>";
}
?>
