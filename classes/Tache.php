<?php

namespace Ubeo;
include_once "Model.php";

class Tache extends Model {

    protected $bd;
	private $nom;
	private $date_debut;
	private $duree;
	private $notes;
	private $termine;
	private $retard;
	private $currente;
	private $id_tache;
	private $id_projet;
    private $tache_status;

	protected static $tache_currente = 0;
	protected static $projet_current = 0;
	protected static $derniere_tache = 0;

    function __construct($tache = null)
    {
        parent::__construct();
        $this->bd = parent::$db_connection;

        if(is_array($tache)) {
            // Création
            $this->insertTache($tache);
        }
        elseif (is_numeric($tache)) {
            // Chargement
            $this->loadTache($tache);
        }
    }

    /**
     * @return mixed
     */
    public function getTacheStatus()
    {
        return $this->tache_status;
    }

    /**
     * @param mixed $tache_status
     */
    public function setTacheStatus($tache_status)
    {
        $this->tache_status = $tache_status;
    }

	/**
	 * @return mixed
	 */
	public function getIdProjet() {
		return $this->id_projet;
	}

	/**
	 * @param mixed $id_projet
	 */
	public function setIdProjet( $id_projet ) {
		$this->id_projet = $id_projet;
	}

	/**
	 * @return mixed
	 */
	public function getNom() {
		return $this->nom;
	}

	/**
	 * @param mixed $nom
	 */
	public function setNom( $nom ) {
		$this->nom = $nom;
	}

	/**
	 * @return mixed
	 */
	public function getDateDebut() {
		return $this->date_debut;
	}

	/**
	 * @param mixed $date_debut
	 */
	public function setDateDebut( $date_debut ) {
		$this->date_debut = $date_debut;
	}

	/**
	 * @return mixed
	 */
	public function getDuree() {
		return $this->duree;
	}

	/**
	 * @param mixed $duree
	 */
	public function setDuree( $duree ) {
		$this->duree = $duree;
	}

	/**
	 * @return mixed
	 */
	public function getNotes() {
		return $this->notes;
	}

	/**
	 * @param mixed $notes
	 */
	public function setNotes( $notes ) {
		$this->notes = $notes;
	}

	/**
	 * @return mixed
	 */
	public function getTermine() {
		return $this->termine;
	}

	/**
	 * @param mixed $termine
	 */
	public function setTermine( $termine ) {
		$this->termine = $termine;
	}

	/**
	 * @return mixed
	 */
	public function getRetard() {
		return $this->retard;
	}

	/**
	 * @param mixed $retard
	 */
	public function setRetard( $retard ) {
		$this->retard = $retard;
	}

    /**
     * @return mixed
     */
    public function getCurrente()
    {
        return $this->currente;
    }

    /**
     * @param mixed $currente
     */
    public function setCurrente($currente)
    {
        $this->currente = $currente;
    }

	/**
	 * @return mixed
	 */
	public function getIdTache() {
		return $this->id_tache;
	}

	/**
	 * @param mixed $id_tache
	 */
	public function setIdTache( $id_tache ) {
		$this->id_tache = $id_tache;
	}

    public function updateStatusTache($id_tache){
        $query = $this->bd->update(["termine" => 1])
            ->table('taches')
            ->where("id_tache", "=", $id_tache);

        $affectedRows = $query->execute(true);

        if($affectedRows) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sauvegarde une nouvelle tâche dans la BD
     */
    private function insertTache($tache) {
        $insert_id = parent::insert($tache, 'taches');
        return $insert_id;
    }

    /**
     * Récupère toutes les données d'une tâche selon son id
     * Et encapsules ses données
     */
    private function loadTache($id_tache) {

	    $tache = parent::getDataById($id_tache, 'taches', 'id_tache');

	    if($tache) {
	        $this->setIdTache($tache['id_tache']);
		    $this->setNom($tache['nom']);
	        $this->setDateDebut($tache['date_debut']);
	        $this->setDuree($tache['duree']);
	        $this->setNotes($tache['notes']);
	        $this->setRetard($tache['retard']);
	        $this->setTermine($tache['termine']);
	        $this->setIdProjet($tache['id_projet']);
            $this->isTacheCurrente();
		    return true;
	    } else {
	    	return false;
	    }
    }

    /**
     * Détermine la tache currente pour chaque projet
     *  Si la tache n'est pas terminée et que la tache currente n'a pas été définie
     *  et le projet current c'est le 0  juste pour la première fois.
     *  Ou si la tâche n'est pas terminée et que l'id du projet est différent à celui-là qui a déjà une tâche currente
     */
    private function isTacheCurrente(){
        if ( ($this->getTermine() == 0 && self::$tache_currente == 0  && self::$projet_current == 0)
            || ( $this->getTermine() == 0 && $this->getIdProjet() != self::$projet_current) ){
            self::$tache_currente = 1;  //Tache currente est active
            self::$projet_current = $this->getIdProjet();   //Projet current égal à l'id du projet
            $this->setCurrente(1);  //Tache currente
            $this->setTacheStatus("validate");
        }
        elseif ($this->getTermine() == 1){
            $this->setTacheStatus("validate");
        }
        else{
            self::$tache_currente = 0;
            $this->setCurrente(0);
        }
    }

    /**
     * Récupère la date de fin d'une tâche selon sa date de début et sa durée en semaines
     */
    public function getDateFinTache(){
        $jours_ajouter = " +".(($this->getDuree() * 7) - 1)." days";

        $date_fin_tache = date('Y-m-d', strtotime( $this->getDateDebut() . $jours_ajouter));
        return strtotime($date_fin_tache);
    }

    /**
     * Vérifié le status de la tâche pour pouvoir imprimer ses attributs
     * Si la tâche est terminée on ne peut plus la modifier, mais on peut voir ses notes
     * Si la tâche est la tâche currente on active le radiobutton pour la fermer lorsque la tâche sera finie
     */
    private function printStatusTache(){
        $active = "";
        if ($this->getTermine() == 1 ){
            $active .= '<a class="state_open state_close" href="javascript:;"><i class="fa fa-circle-o" aria-hidden="true"></i><i class="fa fa-dot-circle-o" aria-hidden="true"></i></a>';
            $active .= '<a class="infos" href="javascript:;"><i class="fa fa-info-circle" aria-hidden="true"></i></a>';
        }
        elseif ($this->getCurrente() == 1 ){
            $active .= '<a class="state_open" onclick="onTacheTerminee('.$this->getIdTache().');"><i class="fa fa-circle-o" aria-hidden="true"></i><i class="fa fa-dot-circle-o" aria-hidden="true"></i></a>';
            $active .= '<a class="infos" href="javascript:;"><i class="fa fa-info-circle" aria-hidden="true"></i></a>';
        }

        echo $active;
    }

    /**
     * Imprime una tâche et la place selon l'ordre dans la calendrier par rapport
     * à sa date de début
     *
     * Utilise l'objet global Helper
     */
    public function printTache($ordre_projet, $tache_final){
        global $taches_helper;

        //Recuperer la position top de la tâche et le numero de semaines selon la duree du projet
        $position_top = $taches_helper::getPositionTop($this->getDateDebut());
        $week = $taches_helper::getClasseDurationSemaine($this->getDuree());
        $date_debut_francais = $taches_helper::getDateFrancais( strtotime( $this->getDateDebut() ) );
        $date_fin_francais = $taches_helper::getDateFrancais( $this->getDateFinTache() );

	    echo '<div class="box projet'.$ordre_projet.' '.$week.' '.$this->getTacheStatus().'" style="top:'.$position_top.'px;">';
        echo '<h6>'.$date_debut_francais.'</h6>';
        echo '<p>'.$this->getNom().'<br>';
        $this->printStatusTache();
		echo '</p>';
		echo '<h6>'.$date_fin_francais.'</h6>';
	    echo '</div>';

	    //Si la tâche as un restard
        if ($this->getRetard() == 1){
            $this->printRetard($ordre_projet);
        }

        if ($tache_final){
            $position_top_fin = $position_top + (($this->getDuree() * 7) * 15);
            $this->printFinProjet($position_top_fin, $ordre_projet, $date_fin_francais);
        }


    }

    /*
     *  Imprime un retard selon la durée en semaines
     */
    public function printRetard($ordre_projet){
        global $taches_helper;

        //Recuperer la position top de la tâche et le numero de semaines selon la duree du projet
        $position_top = $taches_helper::getPositionTopRetard($this->getDateDebut(), $this->getDuree());
        $week = $taches_helper::getClasseDurationSemaine($this->getRetard());
        $date_debut_francais = $taches_helper::getDateFrancais( strtotime( $this->getDateDebut() ) );
        $date_fin_francais = $taches_helper::getDateFrancais( $this->getDateFinTache() );

        echo '<div class="box projet'.$ordre_projet.' '.$week.' warning" style="top:'.$position_top.'px;">';
        echo '<h6>'.$date_debut_francais.'</h6>';
        echo '<p><RETARD></RETARD><br>';
        echo '<a class="infos" href="javascript:;"><i class="fa fa-info-circle" aria-hidden="true"></i></a>';
        echo '</p>';
        echo '<h6>'.$date_fin_francais.'</h6>';
        echo '</div>';


    }

    /*
     *  Imprime la fin du projet
     */
    public function printFinProjet($position_top, $ordre_projet, $date_fin_francais){
        echo '<div class="box end projet'.$ordre_projet.'" style="top:'.$position_top.'px;">';
        echo    '<h6>'.$date_fin_francais.'</h6>';
        echo '</div>';
    }
}