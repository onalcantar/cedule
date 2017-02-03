<?php

class Tache{

    protected $bd;
    private $idtache;
    private $nom;
    private $datedebut;
    private $maitre;

    function __construct($db)
    {
        $this->bd = $db;
    }

    public function getTacheStyle($tache, $numeroprojet){

        //Défini l'id de l'element ou la date débute
        $idtacheparent = $tache["datedebut"]."-".$numeroprojet;

        //Récupère le positions top et left de l'élément ou la date débute
        $positions = $this->setTachePosition($idtacheparent);
        //print_r($positions);
        print $positions;

        $height = "height:".(20 * $tache["duree"]) * 7 ."px;";
        $position = "position:absolute;";
        $zindex = "z-index:10000;";
        $backgroud = "background-color:";
        $backgroud .= $tache["maitre"] == 1 ? "#F6F3F3;" : "#FFFFFF;";

        $style = $height . $position . $zindex . $positions . $backgroud;

        return $style;
    }

    public function setTachePosition($date){

        $positions = '';
        ?>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"
                integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
                crossorigin="anonymous">
        </script>
        <script type="text/javascript">

            function getOffset( el ) {
                var _x = 0;
                var _y = 0;
                while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
                    _x += el.offsetLeft - el.scrollLeft;
                    _y += el.offsetTop - el.scrollTop;
                    el = el.offsetParent;
                }
                return { top: _y, left: _x };
            }

            var positions = getOffset( document.getElementById('<?=$date?>') );
            console.log(positions);

        </script>

        <?php
        $top =  (string)"<script>document.writeln(positions.top);</script>";
        $left =  (string)"<script>document.writeln(positions.left);</script>";

        $positions.= "top:".$top."px; left:".$left."px;";

        return $positions;
    }
}

$tacheobjet = new Tache($pdo);