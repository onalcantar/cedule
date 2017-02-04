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

    public function getDaysBetweenDates($datepluspetite, $dateplusgrande){
        $datediff = $dateplusgrande - $datepluspetite;
        return floor($datediff / (60 * 60 * 24));
    }

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
}