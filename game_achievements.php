<?php 
    require_once( "template.php" );
    include_once( "db_connect.php" );
    echo head( "Achievements" );
    echo statScripts();
    echo nav();
?>
        <div id="meta-container">
        <h1><?php echo appID_to_name($_GET['app']); ?></h1>
        <div id="container">
        <script> console.log("Loaded."); </script>
            <?php 
                require_once( "stats.php" );
                if( !empty($_GET['app']) ){
                    $stat = new Statistics( $_GET['app'] );
                        //echo "<h2> List of Achievements </h2>";
                        //$stat->game_achievements( 1 );
                    if ( empty($_GET['achievement']) ) {
                        echo "<h2> List of Achievements </h2>";
                        $stat->game_achievements($_GET['app']);
                    }
                    else {
                        $stat->getAchievement( $_GET['achievement'] );
                    }
                }
                else {
                    echo "<p><i>No application selected for achievement.</i></p>";
                }
            ?>
        </div>
        </div>
    </body>
</html>
