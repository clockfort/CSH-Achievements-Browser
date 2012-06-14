<?php
    /***
     *  QUERIES FOR ACHIEVEMENTS DB
     ***/
    // Fetches username for given userid
    DEFINE('USR_NAME', "SELECT username FROM users WHERE id=");
    // Query for a user's achievements & achievement progress
    // Requires a user id to be appended at the end.
    DEFINE('USR_ACHIEVE', "SELECT t2.title, t2.progress_max, t1.progress, t2.score, t1.user_id, t2.updated_at FROM achievement_progress AS t1 INNER JOIN achievements AS t2 ON t1.achievement_id=t2.id WHERE t1.progress!=0 AND t1.user_id=" );
    DEFINE('USR_ACHIEVE_NAME', "SELECT t2.title, t2.progress_max, t1.progress, t2.score, t1.user_id, t3.id, t1.updated_at FROM (achievement_progress AS t1 INNER JOIN achievements AS t2 ON t1.achievement_id=t2.id) JOIN users AS t3 ON t1.user_id=t3.id  WHERE t1.progress!=0 AND t3.username=" );
    // Query for All Uses
    DEFINE('ALL_USERS', "SELECT id, username FROM users");
    // Query for showing #users / achievement
    DEFINE('G_PROGRESS', "SELECT t2.title, COUNT(user_id) FROM achievement_progress AS t1 JOIN achievements AS t2 ON t1.achievement_id=t2.id WHERE t1.progress=t2.progress_max GROUP BY t1.achievement_id ORDER BY COUNT(user_id) DESC LIMIT 10");
    // Query for top 10 users with most achivements
    DEFINE('G_TOPUSERS', "SELECT t1.username, COUNT(t2.achievement_id), t2.user_id FROM users AS t1 JOIN achievement_progress AS t2 ON t1.id=t2.user_id WHERE t2.progress=1 GROUP BY t2.user_id ORDER BY COUNT(t2.achievement_id) DESC LIMIT 10");
    // Query for top 10 users by points/score.
    DEFINE('G_SCOREUSERS', "SELECT SUM(t3.score), t1.username FROM (users AS t1 INNER JOIN achievement_progress AS t2 ON t1.id=t2.user_id) INNER JOIN achievements AS t3 ON t2.achievement_id=t3.id WHERE t2.progress=t3.progress_max GROUP BY t1.id ORDER BY SUM(t3.score) DESC, t2.updated_at DESC LIMIT 10"); 
    // All Achievements
    DEFINE('ALL_ACH', "SELECT title, description, score FROM achievements ORDER BY score DESC, id ASC");
    // One achievement
    DEFINE('GET_ACH', "SELECT title, description, score FROM achievements WHERE title=");
    // Recent Achievements
    DEFINE('RECENT', "SELECT t2.title, t3.username FROM (achievement_progress AS t1 INNER JOIN achievements AS t2 ON t1.achievement_id=t2.id ) INNER JOIN users AS t3 ON t1.user_id=t3.id WHERE t1.progress=t2.progress_max ORDER BY t1.updated_at DESC LIMIT 5");
     
    /***
     * QUERIES FOR NETHACK DB
     ***/
    // Retrieve (based on username) : Gender, Alignment, Race, and Role
    // Requires username at the end.
    DEFINE('CHARACTER_DETAIL', "SELECT COUNT(align), gender, align, race, role FROM playlog WHERE name=");
    //Best Game (points) 
    DEFINE('BEST_GAME', "SELECT gender, align, role, race, MAX(points), death, deathdungeon, starttime, endtime, maxlvl FROM playlog WHERE name=");
?>
