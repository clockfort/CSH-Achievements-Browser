<?php
    function head($title) {
        $head = "<!DOCTYPE HTML>
            <html>
                <head> 
                <link href='//fonts.googleapis.com/css?family=Aclonica' rel='stylesheet' type='text/css'>
                <link href='//fonts.googleapis.com/css?family=Days+One' rel='stylesheet' type='text/css'>
                <title>"
                . $title 
                ."</title>
                <link rel=stylesheet href='game_style.css' type='text/css'>";
        return $head;
    }
    function statScripts() {
        $scrpt = "<script language='javascript' src='progressBars.js'></script>
            </head>
            <body>";
        return $scrpt;
    }

    function nav() {
        $nav = "<div id='nav'>
                <ul>"; 
        if (!empty($_GET['app'])){
            $ach = "game_achievements.php?app=" . $_GET['app'];
            $usr = "game_userpage.php?app=". $_GET['app'];
            $nav .= "<li><a href=$ach>Achievements</a></li>";
//                    <li><a href=$usr>User Achievements</a></li>";
            if( $_GET['app'] == 1 ){ //Nethack has a playlog, and binges
		$nav .= "<li><a href='binge_highscores.php?app=1'>Binge Highscores</a></li>";
		$nav .= "<li><a href='playlog_stats.php?app=1'>Playlog Stats</a></li>";
            }
	    $nav .= "<li><a href='game_page.php?app=". $_GET['app'] ."'>Game Page</a></li>";
        }
        $nav .= "<li><a href='index.php'>Main</a></li>
            </ul>
        </div>";
        return $nav;
    }

    function foot() {
        $foot = "</body>
            </html>";
        return $foot;

    }
?>
