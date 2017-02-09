<?php
/**
 * Created by PhpStorm.
 * User: noealcantar
 * Date: 17-02-08
 * Time: 21:35
 */

namespace Ubeo;

include_once "Helper.php";

class TachesHelper extends Helper
{

    protected static $dateMinimum;
    protected static $db;

    /**
     * Helpers constructor.
     */
    public function __construct()
    {
        parent::__construct();
        self::$db = self::$db_connection;
        self::$dateMinimum = $this->getDateMinimum();
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

        $jours_difference = self::getDaysBetweenDates( self::$dateMinimum, $date_debut );
        $jour_semaine = self::getJourSemaine(self::$dateMinimum);

        return $position_top = ($jours_difference * 15) + (($jour_semaine-1) * 15);
    }

    public static function getPositionTopRetard($date_debut, $duree){

        $date_debut_retard = date('Y-m-d', strtotime($date_debut." +". ($duree*7) ."days"));
        $jours_difference = self::getDaysBetweenDates( self::$dateMinimum, $date_debut_retard );
        $jour_semaine = self::getJourSemaine(self::$dateMinimum);

        return $position_top = ($jours_difference * 15) + (($jour_semaine-1) * 15);
    }

    /**
     * Récupère la date de la tâche la plus ancienne et que le projet est active
     */
    public static function getDateMinimum(){
        $query = self::$db->select( ['date_debut'] )
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
        $query = self::$db->select( ['date_debut'] )
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
    public static function getIdProjects(){
        $query = self::$db->select( ['id_projet'] )
            ->from('projets')
            ->where('active', '=', '1')
            ->orderby('id_projet');

        $stmt = $query->execute();
        $data = $stmt->fetchAll();
        return $data;
    }

    /**
     * Génere une date formatée en français
     *
     * @param Date en format timestamp
     */
    public static function getDateFrancais($date){
        return strftime('%e %b %Y', $date);
    }


    /**
     * Fait l'impression d'un tableau avec toutes les semaines pour les tâches currentes.
     * Affiche le lundi de chaque semaine.
     * Rajoute 4 semaines à la fin pour le délai de duration de la denière tâche (côté esthétique)
     */
    public static function printSemaines(){

        $premier_lundi = date('Y-m-d', strtotime("last monday", strtotime(self::$dateMinimum . " +1 day")));

        //On additione 28 jours (4 semaines)  pour s'assurer que le calendrier s'affiche correctement
        $journees_difference = self::getDaysBetweenDates($premier_lundi, self::getDateMaximum(self::$db)) + (7 * 4);

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

    /**
     * Détermine la classe de la tache selon se durée
     * Par défaut 1 semaine est égal a vide
     * Sinon la classe c'est le mot week suivi du numéro de semaines selon la durée
     *
     */
    public static function getClasseDurationSemaine($duree){
        $week = "";
        if ($duree > 1){
            $week = "week".$duree;
        }
        return $week;
    }

    public static function countNumberOfTachesParProjet( $id_projet ){
        $query = self::$db->select()
            ->count('id_tache', 'taches')
            ->from('taches')
            ->where('id_projet', '=', $id_projet);

        $stmt = $query->execute();
        $number_of_taches = $stmt->fetch();

        if ($number_of_taches){
            return $number_of_taches["taches"];
        }else{
            return false;
        }
    }
}