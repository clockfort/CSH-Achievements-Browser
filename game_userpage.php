<?php
    require_once("template.php");
    require_once("stats.php");
    echo head("User Achievements");
    echo statScripts();
    echo nav();
    echo "<div id='meta-container'>";
        if( empty( $_SERVER['REMOTE_USER']) && empty($_GET['user'] )) {
            echo "<h1>Users</h1>";
            echo "<div id='container'>";
            echo "<div id='users'>";
            userList(); 
            echo "</div>";
            echo "</div></div>";
        }
        else {
        $user = ( !empty($_GET['user'] ) ? $_GET['user'] : $_SERVER['REMOTE_USER']);
            echo "<h1>User: <span id='username'></span>.</h1>
        <div id='container'>
            <h2> <span id='point_count'></span> Points! </h2>
            <h2> Achievements </h2>
            <ul id='achieve_comp'>
            </ul>
            <h2> In Progress </h2>
            <ul id='achieve_prog'>
            </ul>
        </div>
        </div>";
        $s = new Statistics( $_GET['app'] );    
        $s->getUserAchieve( $user ); 
        $s->db.mysql_close();
        }
    echo foot();
?>
