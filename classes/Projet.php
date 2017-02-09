<?php
/**
 * Created by PhpStorm.
 * User: noealcantar
 * Date: 17-02-01
 * Time: 14:56
 */


namespace Ubeo;

include_once "Model.php";

class Projet extends Model {

    protected $db;
    private $id_projet;
    private $nom;
    private $active;
    private $date_livraison;
    private $montant_soumission;

    public static $ordre_currente = 1;
    private $ordre;

    function __construct($projet = null)
    {
        $this->setOrdre(self::$ordre_currente);
        self::$ordre_currente++;

        parent::__construct();
        $this->db = parent::$db_connection;

        if (is_array($projet)){
            $this->insertProject($projet);
        }
        elseif (is_numeric($projet)){
            $this->getProjectById($projet);
        }
    }

    /**
     * @return mixed
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param mixed $ordre
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    }

    /**
     * @return mixed
     */
    public function getIdProjet()
    {
        return $this->id_projet;
    }

    /**
     * @param mixed $id_projet
     */
    public function setIdProjet($id_projet)
    {
        $this->id_projet = $id_projet;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getDateLivraison()
    {
        return $this->date_livraison;
    }

    /**
     * @param mixed $date_livraison
     */
    public function setDateLivraison($date_livraison)
    {
        $this->date_livraison = $date_livraison;
    }

    /**
     * @return mixed
     */
    public function getMontantSoumission()
    {
        return $this->montant_soumission;
    }

    /**
     * @param mixed $montant_soumission
     */
    public function setMontantSoumission($montant_soumission)
    {
        $this->montant_soumission = $montant_soumission;
    }

    /**
     * Récupère les données de tous les projets
     */
    public function getProjetData(){
        $query = $this->db->select()
            ->from('Projet')
            ->orderBy('datedebut');

        $stmt = $query->execute();
        $data = $stmt->fetchAll();

        return $data;
    }

    /**
     * Récupère un projet par son ID
     */
    private function getProjectById( $projet_id ){

        $projet = parent::getDataById($projet_id, 'projets', 'id_projet');

        if($projet) {
            $this->setIdProjet($projet["id_projet"]);
            $this->setNom($projet["nom"]);
            $this->setActive($projet["active"]);
            $this->setDateLivraison($projet["date_livraison"]);
            $this->setMontantSoumission($projet["montant_soumission"]);
            return true;
        }else{
            return false;
        }

    }

    public function getTachesIdsFromProjet(){
        $query = $this->db->select( ['id_tache'])
            ->from('taches')
            ->where('id_projet', '=', $this->getIdProjet())
            ->orderby('date_debut');

        $stmt = $query->execute();
        $taches_id = $stmt->fetchAll();

        return $taches_id;
    }

    public function createTaches($number_of_taches){
        $id_taches = $this->getTachesIdsFromProjet();
        if ($id_taches) {
            $i = 1;
            foreach ($id_taches as $id_tache){
                $tache_final = $i == $number_of_taches ? true : false;
                $tache = new Tache($id_tache["id_tache"]);
                $tache->printTache($this->getOrdre(), $tache_final);
                $i++;
            }
        }
    }

    public function printTitle(){
        echo '<a href="javascript:;" class="title projet'.$this->getOrdre().'" id="'.$this->getIdProjet().'">'.$this->getNom().'</a>';
    }

    /**
     * Sauvegarde un nouveau projet dans la db
     */
    private function insertProject($projet){

        $insert_id = parent::insert($projet, 'projets');
        return $insert_id;
    }

    /**
     * Modifie les donneés d'un projet dans la db
     */
    private function updateProject($projet){
        $query = $this->db->update( [
            'nom' => $projet["nom"],
            'active' => $projet["active"],
            'montant_soumission' => $projet["montant_soumission"],
            'date_livraison' => $projet["date_livraison"]
        ] )
            ->table('projets')
            ->where( 'id_projet', '=', $projet["id_projet"]);

        return $affectedRows = $query->execute(false);
    }

    /**
     * supprime les donneés d'un projet dans la db
     */
    private function deleteProject($projet_id){
        $query = $this->db->delete()
            ->from('projets')
            ->where( 'id_projet', '=', $projet_id);

        return $affectedRows = $query->execute(false);
    }


}