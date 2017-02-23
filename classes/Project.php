<?php
/**
 * Created by PhpStorm.
 * User: noealcantar
 * Date: 17-02-01
 * Time: 14:56
 */


namespace Ubeo;

use Ubeo\Task;


if ( isset( $_POST["action"] ) && !empty( $_POST["action"] ) ){


    include_once '../db/config.inc';

    switch ( $_POST["action"] ){

        case "add_project" :

            if ( ( isset($_POST["project_name"]) && !empty($_POST["project_name"]) ) ){

                $project = [ "project_name" => htmlspecialchars( $_POST["project_name"] ) ];

	            if ( isset($_POST['delivery_date']) && !empty($_POST['delivery_date']) ){
		            $project["delivery_date"] = htmlspecialchars($_POST['delivery_date']) ;
	            }

                if ( isset($_POST['date_confirmed']) && !empty($_POST['date_confirmed']) ){
                	if ($_POST['date_confirmed'] == "confirmed"){
		                $project["delivery_date"] = null;
	                }
                }

                $new_project = new Project( $project );

                if ( $new_project ){
	                $_SESSION["message"] = "Success";
                    echo '<meta http-equiv="refresh" content="0; url=http://cedule.dev.dev-ubeo.com/formulaires/addProject.php">';
                }else{
	                $_SESSION["message"] = "Error";
	                echo '<meta http-equiv="refresh" content="0; url=http://cedule.dev.dev-ubeo.com/formulaires/addProject.php">';
                }
            }

            break;

        case "modify_project":

            if ( ( isset($_POST["id_project"]) && !empty($_POST["id_project"]) ) ){
                $project = new Project($_POST["id_project"]);

                if ( $project ){
	                $project->setProjectName( htmlspecialchars( $_POST["project_name"] ) );

                    if ( isset($_POST['delivery_date']) && !empty($_POST['delivery_date']) ){
	                    $project->setDeliveryDate( htmlspecialchars($_POST['delivery_date']) );
                    }

	                if ( isset($_POST['date_confirmed']) && !empty($_POST['date_confirmed']) ){
		                if ($_POST['date_confirmed'] == "confirmed"){
			                $project->setDeliveryDate(null);
		                }
	                }

                    if ( $project->save() ){
	                    $_SESSION["message"] = "Success";
                        echo '<meta http-equiv="refresh" content="0; url=http://cedule.dev.dev-ubeo.com/formulaires/modifyProject.php">';
                    }else{
	                    $_SESSION["message"] = "Error";
	                    echo '<meta http-equiv="refresh" content="0; url=http://cedule.dev.dev-ubeo.com/formulaires/modifyProject.php">';
                    }
                }
            }

            break;

    }


}

class Project{

    protected $db;
    private $id_project;
    private $project_name;
    private $active;
    private $delivery_date;
    private $submission_amount;

    public static $_current_order = 1;
    private $_order;

    function __construct( $project = null )
    {
        $this->setOrder( self::$_current_order );
        self::$_current_order++;

        //Récupère l'instance global de la clase Database
        global $db;
        $this->db = $db;

        if ( is_array( $project ) ){
            $this->insertProject( $project );
        }
        elseif ( is_numeric( $project ) ){
            $this->getProjetById($project);
        }
    }

	/**
	 * @return mixed
	 */
	public function getIdProject() {
		return $this->id_project;
	}

	/**
	 * @param mixed $id_project
	 */
	public function setIdProject( $id_project ) {
		$this->id_project = $id_project;
	}

	/**
	 * @return mixed
	 */
	public function getProjectName() {
		return $this->project_name;
	}

	/**
	 * @param mixed $project_name
	 */
	public function setProjectName( $project_name ) {
		$this->project_name = $project_name;
	}

	/**
	 * @return mixed
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * @param mixed $active
	 */
	public function setActive( $active ) {
		$this->active = $active;
	}

	/**
	 * @return mixed
	 */
	public function getDeliveryDate() {
		return $this->delivery_date;
	}

	/**
	 * @param mixed $delivery_date
	 */
	public function setDeliveryDate( $delivery_date ) {
		$this->delivery_date = $delivery_date;
	}

	/**
	 * @return mixed
	 */
	public function getSubmissionAmount() {
		return $this->submission_amount;
	}

	/**
	 * @param mixed $submission_amount
	 */
	public function setSubmissionAmount( $submission_amount ) {
		$this->submission_amount = $submission_amount;
	}

	/**
	 * @return mixed
	 */
	public function getOrder() {
		return $this->_order;
	}

	/**
	 * @param mixed $order
	 */
	public function setOrder( $order ) {
		$this->_order = $order;
	}
	
    /**
     * Récupère les données de tous les projets
     */
    /*public function getProjetData(){
        $query = $this->db->select()
            ->from('Projet')
            ->orderBy('datedebut');

        $stmt = $query->execute();
        $data = $stmt->fetchAll();

        return $data;
    }*/

    /**
     * Récupère un projet par son ID
     */
    private function getProjetById( $project_id ){

        $query = $this->db->select()
            ->from('projects')
            ->where('id_project', '=', $project_id)
            ->orderby('id_project');

        $stmt = $query->execute();
	    $project = $stmt->fetch();

        if( $project ) {
            $this->setIdProject($project["id_project"]);
            $this->setProjectName($project["project_name"]);
            $this->setActive($project["active"]);
            $this->setDeliveryDate($project["delivery_date"]);
            $this->setSubmissionAmount($project["submission_amount"]);
            return true;
        }else{
            return false;
        }

    }

    /**
     * Récupère toutes les ids des tâches qui appartient au projet
     * @return array
     */
    public function getTasksIdsFromProject(){
        $query = $this->db->select( ['id_task'])
            ->from('tasks')
            ->where('id_project', '=', $this->getIdProject())
            ->orderby('start_date');

        $stmt = $query->execute();
        $tasks_ids = $stmt->fetchAll();

        if ( $tasks_ids ){
        	return $tasks_ids;
        }else{
        	return false;
        }

    }

    /**
     * Imprime le tableau des tâches en format HTML et les positionne selon son ordre et date de début
     */
    public function printTasks(){
        $tasks_ids = $this->getTasksIdsFromProject();
        if ($tasks_ids) {

            foreach ($tasks_ids as $task_id){

                $tache = new Task( $task_id["id_task"], $this->getOrder() );
                $tache->printTask( $this->getOrder() );
            }
        }

        if ( $this->getDeliveryDate() ){
            $this->printProjectEnd();
        }

    }

    /**
     * Imprime le titre du projet
     */
    public function printTitle(){
        //$date_livraison = $this->getDateLivraison() != null ? $this->getDateLivraison() : "";
        echo '<a data-id="'.$this->getIdProject().'" 
                data-name="'.$this->getProjectName().'" 
                data-date="'.$this->getDeliveryDate().'"
                data-fancybox-type="iframe"
                href="formulaires/modifyProject.php" 
                class="title projet'.$this->getOrder().' various">'
                    .$this->getProjectName().
             '</a>';
    }

    /**
     * Sauvegarde un nouveau projet dans la db
     */
    private function insertProject( $project ){

        $query = $this->db->insert( array_keys( $project ) )
            ->into( 'projects' )
            ->values( array_values( $project ) );

        $id_project = $query->execute(false);

        if ($id_project){
            return $id_project;
        }else{
            return false;
        }
    }

    /*
     *  Imprime la fin du projet
     */
    public function printProjectEnd(){

        global $helper;

        $difference_days = $helper::getDaysBetweenDates( $helper::$min_date, $this->getDeliveryDate() );
        $week_day = $helper::getWeekDay( $helper::$min_date );
        $position_top = ( $difference_days * 15) + ( ($week_day - 1) * 15 );   //La position du div en pixeles

        $end_date = $helper::getFrenchFormatDate( strtotime( $this->getDeliveryDate() ) );

        echo '<div class="box end projet'.$this->getOrder().'" style="top:'.$position_top.'px;">';
        echo    '<h6>'.$end_date.'</h6>';
        echo '</div>';
    }


	public function countProjectTasks(){

		$query = $this->db->select()
		            ->count( $column = 'id_task', $as = 'tasks_number', $distinct = false )
		            ->from( 'tasks' )
		            ->where( 'id_project', '=', $this->getIdProject() );

		$stmt = $query->execute();
		$tasks_number = $stmt->fetch();

		if ( $tasks_number ){
			return $tasks_number["tasks_number"];
		}
	}



    /**
     * Effectue les modifications dans la BD de la tâche currente selon ses attributs
     */
    public function save(){

	    $project = [ "id_project" => $this->getIdProject(),
            "project_name" => $this->getProjectName(),
            "active" => $this->getActive(),
            "submission_amount" => $this->getSubmissionAmount(),
            "delivery_date" => $this->getDeliveryDate()
        ];

        $query = $this->db->update( $project )
            ->table( 'projects' )
            ->where( "id_project", "=", $project["id_project"] );

        $affectedRows = $query->execute( true );

        if( $affectedRows ) {

            return true;
        } else {
            return false;
        }
    }

}