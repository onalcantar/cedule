<?php
/**
 * Created by PhpStorm.
 * User: noealcantar
 * Date: 17-02-08
 * Time: 22:07
 */

namespace Ubeo;
include_once 'Controller.php';

class ProjetController extends Controller
{

    private $projet_model;
    /**
     * ProjetController constructor.
     */
    public function __construct()
    {
        //$projet_model = new Projet();
    }

    public function printAllTaches(){
        global $taches_helper;

        $projects = $taches_helper::getIdProjects();

        if (isset($projects)){
            foreach ($projects as $id_projet){

                $number_of_taches = $taches_helper::countNumberOfTachesParProjet($id_projet["id_projet"]);
                $project = new Projet($id_projet["id_projet"]);
                $project->printTitle();
                $project->createTaches($number_of_taches);
            }
        }
    }

    /**
     * Reécupère tous les projets dans la BD
     */

}