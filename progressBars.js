/** 
 * Make basic achievement bar graphs to take over / modify
 * the li to show progress.
 */
function makeListItem( title, max, prog, id, user, app ) {
    var list = document.createElement('li');
    var txt = document.createTextNode("(" + prog + "/" + max + ") - " + title);
    if ( user == "a" ) {
        var link = document.createElement('a');
        link.setAttribute("href", ("game_achievements.php?achievement=" + title + "&app=" + app));
        link.setAttribute("class", "achieve_link");
        link.appendChild(txt);
        list.appendChild(link);
    }
    else if( user != "n"){
        var link = document.createElement('a');
        link.setAttribute("href", ("game_userpage.php?user=" + user + "&app=" + app));
        link.setAttribute("class", "achieve_link");
        link.appendChild(txt);
        list.appendChild(link);
    }
    else{
        list.appendChild(txt);
    }
    
    var bar = document.createElement('div');
    bar.className = "progbar";
    bar.style.width = ( prog / max * 100 )  + "%";
    var con = document.getElementById(id);
    list.appendChild(bar);
    con.appendChild(list);

}

function noDetailList( title, max, prog, id ){
    var list = document.createElement("li");
    var txt = document.createTextNode(title);
    list.appendChild(txt);
    var bar = document.createElement('div');
    bar.className = "progbar";
    bar.style.width = ( prog / max * 100 )  + "%";
    var con = document.getElementById(id);
    list.appendChild(bar);
    con.appendChild(list);
}

/*** 
 * No statistics.
 ***/
function no_stats() {
    var txt = document.createTextNode( "This user has no statistics here. At this time. Keep playing!" );
    document.getElementById( "container" ).appendChild( txt );
    
}

/** 
 * Put the total points earned at top of screen
 */
function placePoints( points ){
    console.log( points );
    if( typeof points=="undefined"  ){
        var p = document.createTextNode('0');
    }
    else {
        var p = document.createTextNode(points);
    } 
    document.getElementById("point_count").appendChild(p);
}

function placeUser( name ){
    var n = document.createTextNode( name );
    document.getElementById("username").appendChild(n);
}
