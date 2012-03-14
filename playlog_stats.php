<?php
    require_once("template.php");
    echo head( "Playlog" );
    echo statScripts();
    echo nav();
?>
        <div id="meta-container">
            <h1>Playlog</h1>
        <div id="container">
            <?php 
                require_once("nethack_log_connect.php");
                if(!empty($_SERVER['REMOTE_USER']) || !empty($_GET['user'])){
                    echo "<h2>". (!empty($_GET['user']) ? $_GET['user'] : $_SERVER['REMOTE_USER']). "</h>";
                    topCharacters( (!empty($_GET['user']) ? $_GET['user'] : $_SERVER['REMOTE_USER']));
                    bestGame( (!empty($_GET['user']) ? $_GET['user'] : $_SERVER['REMOTE_USER']));
                }
                else {
                    echo "<h2> Please sign in. </h2>";
                }
            ?>
        </div>
        </div>
    </body>
</html>
