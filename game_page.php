<?php 
    require_once( "template.php" );
    require_once("db_game_connect.php"); 
    echo head("Nethack Page");
    echo statScripts();
    echo nav(); 
?>

    <div id="meta-container">
        <h1> Achievements </h1>
    <div id="container">
        <div id="gAchieve">
            <h2> Top Achievements </h2>
            <ul id='g_achieve'>
            </ul>
        </div>
        <div id="gTopUser">
            <h2> Top Ten Users </h2>
            <ul id='g_toplist'>
            </ul>
        </div>
        <h2> Recent Achievements</h2>
        <ul><?php recentAchievements(); ?></ul>
    </div>
    </div>
    </body>
    <?php 
        globalAchievements();
        topUsers();
    ?>
</html>
