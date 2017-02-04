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
	private $maitre;
	private $id_tache;
	private $id_projet;

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
	public function getMaitre() {
		return $this->maitre;
	}

	/**
	 * @param mixed $maitre
	 */
	public function setMaitre( $maitre ) {
		$this->maitre = $maitre;
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

    private function loadTache($id_tache) {
	    $query = $this->bd->select()->from('taches')->where('id_tache', '=', $id_tache);
	    $stmt = $query->execute();
	    $data = $stmt->fetch();

	    if($data) {
	        $this->setIdTache($data['id_tache']);
		    $this->setNom($data['nom']);
	        $this->setDateDebut($data['date_debut']);
	        $this->setDuree($data['duree']);
	        $this->setMaitre($data['maitre']);
	        $this->setNotes($data['notes']);
	        $this->setRetard($data['retard']);
	        $this->setTermine($data['termine']);
	        $this->setIdProjet($data['id_projet']);
		    return true;
	    } else {
	    	return false;
	    }
    }

    public function printTache($ordre_projet){
        echo $GLOBALS["dateMinimum"];

	    echo '<div class="box projet'.$ordre_projet.' validate" style="top:15px;">';

	    echo '</div>';
    }
}