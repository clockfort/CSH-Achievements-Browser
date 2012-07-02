<?php
    require_once("template.php");
    echo head( "Binge Scoreboard" );
    echo statScripts();
    echo nav();
?>
        <div id="meta-container">
            <h1>Binge Scoreboard</h1> 
        <div id="container">
	    <h2>(July 3rd 2PM - July 4th 2AM EST 2012)</h2>
            <?php 
                require_once("nethack_log_connect.php");
                //topCharacters( (!empty($_GET['user']) ? $_GET['user'] : $_SERVER['REMOTE_USER']));
                //bestGame( (!empty($_GET['user']) ? $_GET['user'] : $_SERVER['REMOTE_USER']));
            ?>
        </div>
        </div>
    </body>
</html>
