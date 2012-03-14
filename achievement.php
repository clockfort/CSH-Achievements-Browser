<?php
class User_Achievement{
    /***
     * Construct a User_Achievement
     ***/
    function __construct( $title, $prog_max, $prog, $score, $update, $id ){
        $this->title = $title;
        $this->prog_max = $prog_max;
        $this->prog = $prog;
        $this->score = $score;
        $this->update = $update;
        $this->id = $id;
    }

    function format(){
        $out = "( $this->prog / $this->prog_max ) -- $this->title";
        return $out;
    }
}

class Achievement{
    function __construct( $title, $point, $desc ){
        $this->title = $title;
        $this->point = $point;
        $this->desc = $desc;
    } 
}
?>
