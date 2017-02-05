<?php
/**
 * Created by PhpStorm.
 * User: noealcantar
 * Date: 17-02-03
 * Time: 15:34
 */

namespace Ubeo;


class Helpers
{


    /**
     * Helpers constructor.
     */
    public function __construct()
    {
    }

    /**
     * Récupère le nombre de jours entre 2 dates différentes
     */
    public static function getDaysBetweenDates($datepluspetite, $dateplusgrande){
        $datedifference = strtotime($dateplusgrande) - strtotime($datepluspetite);
        return floor($datedifference / (60 * 60 * 24));
    }

    /**
     * Détermine le jour de la semaine selon la date reçue
     * 1 pour lundi , 2 pour mardi et ainsi de suite
     */
    public static function getJourSemaine($date){
        return date("N", strtotime($date));
    }

    public static function getPositionTop($date_debut){
        //print $_SESSION["dateMinimum"];
        //print $date_debut;
        $jours_difference = self::getDaysBetweenDates( $_SESSION["dateMinimum"], $date_debut );
        $jour_semaine = self::getJourSemaine($_SESSION["dateMinimum"]);

        return $position_top = ($jours_difference * 15) + (($jour_semaine-1) * 15);
    }

    /**
     * Récupère la date de la tâche la plus ancienne et que le projet est active
     */
    public static function getDateMinimum($db){
        $query = $db->select( ['date_debut'] )
            ->from('taches')
            ->join('projets', 'taches.id_projet', '=', 'projets.id_projet')
            ->where('active', '=', '1')
            ->limit(1,0)
            ->orderby('date_debut');

        $stmt = $query->execute();
        $dateMinum = $stmt->fetch();

        if ($dateMinum){
            return $dateMinum["date_debut"];
        }else{
            return false;
        }

    }

    /**
     * Récupère la date de la tâche la plus lointane et que le projet est active
     */
    public static function getDateMaximum($db){
        $query = $db->select( ['date_debut'] )
            ->from('taches')
            ->join('projets', 'taches.id_projet', '=', 'projets.id_projet')
            ->where('active', '=', '1')
            ->limit(1,0)
            ->orderby('date_debut', 'DESC');

        $stmt = $query->execute();
        $dateMinum = $stmt->fetch();

        if ($dateMinum){
            return $dateMinum["date_debut"];
        }else{
            return false;
        }

    }

    /**
     * Reécupère tous les projets dans la BD
     */
    public static function getIdProjects($db){
        $query = $db->select( ['id_projet'] )
            ->from('projets')
            ->where('active', '=', '1')
            ->orderby('id_projet');

        $stmt = $query->execute();
        $data = $stmt->fetchAll();
        return $data;
    }


    public static function printSemaines($db){

        $premier_lundi = date('Y-m-d', strtotime("last monday", strtotime($_SESSION["dateMinimum"] . " +1 day")));

        //On additione 4 semaines pour s'assurer que le calendrier s'affiche correctement
        $journees_difference = self::getDaysBetweenDates($premier_lundi, self::getDateMaximum($db)) + (7 * 4);

        $premier_lundi = strtotime($premier_lundi);
        $i = 0;
        while ($i <= ($journees_difference/7)){
            echo '<div class="week">';
            echo        '<div class="week_days">Sem. '.strftime('%d %b', $premier_lundi).'.</div>';
            echo        '<div class="week_ends"></div>';
            echo    '</div>';

            $i++;
            $premier_lundi += (60 * 60 * 24 * 7);
        }

    }
}