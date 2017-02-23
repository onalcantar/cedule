<?php
/**
 * Created by PhpStorm.
 * User: noealcantar
 * Date: 17-02-03
 * Time: 15:34
 */

namespace Ubeo;

class Helper
{


    public static $min_date;
    public static $_instance;

    public static function getInstance()
    {
        if ( ! isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Helpers constructor.
     */
    public function __construct()
    {
        global $db;
        self::$min_date = $this->getMinDate( $db );
    }

    /**
     * Récupère le nombre de jours entre 2 dates différentes
     */
    public static function getDaysBetweenDates( $older_date, $recent_date ){

        $date1 = new \DateTime($older_date);
        $date2 = new \DateTime($recent_date);

        return $difference_days = $date2->diff($date1)->format("%a");
    }

    /**
     * Détermine le jour de la semaine selon la date reçue
     * 1 pour lundi , 2 pour mardi et ainsi de suite
     */
    public static function getWeekDay( $date ){
        return date( "N", strtotime( $date ) );
    }

    /**
     * Récupère la date de la tâche la plus ancienne et que le projet est active
     */
    public static function getMinDate( $db ){
        $query = $db->select( ['start_date'] )
            ->from( 'tasks' )
            ->join( 'projects', 'tasks.id_project', '=', 'projects.id_project' )
            ->where( 'active', '=', '1' )
            ->limit( 1,0 )
            ->orderby( 'start_date' );

        $stmt = $query->execute();
        $min_date = $stmt->fetch();

        if ($min_date){
            return $min_date["start_date"];
        }else{
            return false;
        }

    }

    /**
     * Récupère la date de la tâche la plus lointane et que le projet est active
     */
    public static function getMaxDate($db){
        $query = $db->select( ['start_date'] )
            ->from( 'tasks' )
            ->join( 'projects', 'tasks.id_project', '=', 'projects.id_project' )
            ->where( 'active', '=', '1' )
            ->limit( 1,0 )
            ->orderby( 'start_date', 'DESC' );

        $stmt = $query->execute();
        $max_date = $stmt->fetch();

        if ( $max_date ){
            return $max_date["start_date"];
        }else{
            return false;
        }

    }

    /**
     * Reécupère tous les projets dans la BD
     */
    public static function getIdsProjects( $db ){
        $query = $db->select( ['id_project'] )
            ->from( 'projects' )
            ->where( 'active', '=', '1' )
            ->orderby( 'id_project' );

        $stmt = $query->execute();
        $data = $stmt->fetchAll();

        return $data;
    }

	/**
	 * Reécupère tous les projets dans la BD
	 */
	public static function getProjects( $db ){
		$query = $db->select( ['id_project', 'project_name'] )
		            ->from( 'projects' )
		            ->where( 'active', '=', '1' )
		            ->orderby( 'id_project' );

		$stmt = $query->execute();
		$data = $stmt->fetchAll();

		return $data;
	}

    /**
     * Génere une date formatée en français
     *
     * @param Date en format timestamp
     */
    public static function getFrenchFormatDate( $date ){

        return strftime('%e %b %Y', $date);
    }

    /**
     * Fait l'impression d'un tableau avec toutes les semaines pour les tâches currentes.
     * Affiche le lundi de chaque semaine.
     * Rajoute 4 semaines à la fin pour le délai de duration de la denière tâche (côté esthétique)
     */
    public static function printSemaines( $db ){

        $premier_lundi = date('Y-m-d', strtotime("last monday", strtotime(self::$min_date . " +1 day")));

        //On additione 28 jours (4 semaines)  pour s'assurer que le calendrier s'affiche correctement
        $journees_difference = self::getDaysBetweenDates( $premier_lundi, self::getMaxDate($db) ) + (7 * 4);

        $premier_lundi = strtotime( $premier_lundi );

        $i = 0;
        while ( $i <= ( $journees_difference / 7) ){

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
    public static function getDurationWeekCssClass( $duration ){

        $week = "";
        if ( $duration > 1 ){
            $week = "week".$duration;
        }
        return $week;
    }

}

global $helper;
$helper = Helper::getInstance();