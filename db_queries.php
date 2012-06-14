<?php
    // Fetches all Apps.
    DEFINE( 'ALL_APP', "SELECT id, name, description 
        FROM apps" );
    // Fetch all achievements for selected App
    DEFINE( 'GET_ACHIEVES', 
        "SELECT id, title, description, score 
        FROM achievements 
        WHERE app_id=" );
    
    // Fetch all achievements for a given user
/*    DEFINE( 'USR_ACHIEVE', 
        "SELECT t2.title, t2.progress_max, t1.progress, t2.score, t1.user_id, t2.app_id 
        FROM (achievement_progress AS t1 INNER JOIN achievements AS t2 on t1.achievement_id=t2.id) 
        JOIN users AS t3 on t1.user_id=t3.id 
        WHERE t1.progress!=0 and t3.username=" );
*/
    // Fetch #users in selected App
    DEFINE( 'NUM_USERS_APP' , 
        "SELECT count(t1.user_id) 
        FROM users_in_apps AS t1 INNER JOIN apps AS t2 ON t1.app_id=t2.id
        WHERE t2.name=");

    /// Fetch #achievements in selected App
    DEFINE( 'NUM_ACHS_APP', 
        "SELECT count(t1.app_id)
        FROM achievements AS t1 INNER JOIN apps AS t2 ON t1.app_id=t2.id
        WHERE t2.name=");
?>
