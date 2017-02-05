<?php

namespace Ubeo;

class Tache{

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

	protected static $tache_currente = 0;

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

    function __construct($bd, $tache)
    {
        $this->bd = $bd;

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
     * Sauvegarde une nouvelle tâche dans la BD
     */
    private function insertTache($tache) {
	    $query = $this->bd->insert(array_keys($tache))
	                               ->into('taches')
	                               ->values(array_values($tache));

	    $id_tache = $query->execute(true);

	    if($id_tache) {
		    return $this->loadTache($id_tache);
	    } else {
	    	return false;
	    }
    }

    /**
     * Récupère toutes les données d'une tâche selon son id
     * Et encapsules ses données
     */
    private function loadTache($id_tache) {
	    $query = $this->bd->select()->from('taches')->where('id_tache', '=', $id_tache);
	    $stmt = $query->execute();
	    $data = $stmt->fetch();

	    if($data) {
	        $this->setIdTache($data['id_tache']);
		    $this->setNom($data['nom']);
	        $this->setDateDebut($data['date_debut']);
	        $this->setDuree($data['duree']);
	        $this->setCurrente($data['currente']);
	        $this->setNotes($data['notes']);
	        $this->setRetard($data['retard']);
	        $this->setTermine($data['termine']);
	        $this->setIdProjet($data['id_projet']);
		    return true;
	    } else {
	    	return false;
	    }
    }


    private function getClasseDurationSemaine(){
        $week = "";
        if ($this->getDuree() > 1){
            $week = "week".$this->getDuree();
        }
        return $week;
    }

    /**
     * Récupère la date de fin d'une tâche selon sa date de début et sa durée en semaines
     */
    public function getDateFinTache(){
        $jours_ajouter = " +".(($this->getDuree() * 7) - 1)." days";

        $date_fin_tache = date('Y-m-d', strtotime( $this->getDateDebut() . $jours_ajouter));
        return strtotime($date_fin_tache);
    }


    private function isTacheActive(){
        $active = "";
        if ($this->getTermine() == 1){
            $active .= '<a class="state_open state_close" href="javascript:;"><i class="fa fa-circle-o" aria-hidden="true"></i><i class="fa fa-dot-circle-o" aria-hidden="true"></i></a>';
            $active .= '<a class="infos" href="javascript:;"><i class="fa fa-info-circle" aria-hidden="true"></i></a>';
        }
        elseif ($this->getCurrente() == 1){
            $active .= '<a class="state_open" href="javascript:;"><i class="fa fa-circle-o" aria-hidden="true"></i><i class="fa fa-dot-circle-o" aria-hidden="true"></i></a>';
            $active .= '<a class="infos" href="javascript:;"><i class="fa fa-info-circle" aria-hidden="true"></i></a>';
        }

        return $active;
    }

    /**
     * Imprime una tâche et la place selon l'ordre dans la calendrier par rapport
     * à sa date de début
     *
     * Utilise l'objet global Helper
     */
    public function printTache($ordre_projet){
        global $helper;
        //print $helper::getJourSemaine(strtotime($helper::getDateMinimum($this->bd)));
        $position_top = $helper::getPositionTop($this->getDateDebut());
        $week = $this->getClasseDurationSemaine();
        $validate  = "";

	    echo '<div class="box projet'.$ordre_projet.' '.$week.' validate" style="top:'.$position_top.'px;">';
        echo '<h6>'.strftime('%e %b %Y', strtotime($this->getDateDebut())).'</h6>';
        echo '<p>'.$this->getNom().'<br>';
        echo $this->isTacheActive();
        //echo self::$tache_currente;
		echo '</p>';
		echo '<h6>'.strftime('%e %b %Y', $this->getDateFinTache() ).'</h6>';
	    echo '</div>';
    }
}