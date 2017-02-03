<?php
/**
 * Created by PhpStorm.
 * User: noealcantar
 * Date: 17-02-01
 * Time: 14:56
 */

class Projet{

    protected $bd;

    function __construct($db)
    {
        $this->bd = $db;
    }

    public function getDatePlusPetite(){
        $query = $this->bd->select(['datedebut'])
            ->from('Projet')
            ->orderBy('datedebut')
            ->limit(1, 0);

        $stmt = $query->execute();
        $data = $stmt->fetch();

        return $data["datedebut"];
    }

    public function getDatePlusGrande(){
        $query = $this->bd->select(['datefinestimee'])
            ->from('Projet')
            ->orderBy('datefinestimee', 'DESC')
            ->limit(1, 0);

        $stmt = $query->execute();
        $data = $stmt->fetch();

        return $data["datefinestimee"];
    }

    public function getProjetTitles(){
        $query = $this->bd->select()
            ->from('Projet')
            ->orderBy('datedebut');

        $stmt = $query->execute();
        $data = $stmt->fetchAll();

        return $data;
    }

    public function getTacheDetailsByProjet($idprojet){
        $query = $this->bd->select()
            ->from('Taches')
            ->where('idprojet', '=', $idprojet)
            ->orderBy('datedebut');

        $stmt = $query->execute();
        $data = $stmt->fetchAll();

        return $data;
    }
}

$projet = new Projet($pdo);