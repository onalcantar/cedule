<?php

namespace Ubeo;


if ( isset( $_POST["action"] ) && !empty( $_POST["action"] ) ){

    include_once '../db/config.inc';

    switch ( $_POST["action"] ){

        case "close_task" :

            if ( isset($_POST["id_task"]) && !empty($_POST["id_task"]) ){
                $id_task = $_POST["id_task"];
            }
            $task = new Task( $id_task );

            if ( $task ){
	            $task->setCompleted( 1 );

                $task_is_overdue = $task->isTaskOverdue();

                if ( $task->save() ){

                    if ( $task_is_overdue ){
                        //Mettre à jour les dates des Tasks qui n'ont pas de retard
                        $task->updateDatesWhenTaskIsOverdue();
                    }
                    echo '<meta http-equiv="refresh" content="0; url=http://cedule.dev.dev-ubeo.com/">';

                }
            }

            break;

        case "modify_notes" :

            if ( isset($_POST["id_task"]) && !empty($_POST["id_task"]) ){
                $id_task = $_POST["id_task"];
            }
            $task = new Task( $id_task );

            if ( $task ){

                if ( isset($_POST["notes"]) && !empty($_POST["notes"]) ) {
	                $task->setNotes( $_POST["notes"] );

                }elseif ( isset($_POST["overdue_notes"]) && !empty($_POST["overdue_notes"]) ){    //Si c'Est des notes de retard
	                $task->setOverdueNotes( $_POST["overdue_notes"] );

                }

                if ( $task->save() ){
                    echo '<meta http-equiv="refresh" content="0; url=http://cedule.dev.dev-ubeo.com/">';
                }

            }else{
                echo "error";
            }

            break;

        case "modify_start_date" :
            if ( isset($_POST["id_task"]) && !empty($_POST["id_task"]) ){
	            $id_task = $_POST["id_task"];
            }
            $task = new Task( $id_task );

            if ( $task ){

                if ( isset($_POST["start_date"]) && !empty($_POST["start_date"]) ){
                    $new_date = $_POST["start_date"];

                    if ( strtotime($new_date) < strtotime($task->getStartDate()) ){

                        $task->updateStartDatesWhenTaskIsOverdue( $new_date );
                        echo '<meta http-equiv="refresh" content="0; url=http://cedule.dev.dev-ubeo.com/">';
                    }
                    else{
                        echo "La date ne doit être plus récente";
                    }
                }
            }

            break;
    }


}


class Task{

    protected $db;

	private $task_name;
	private $start_date;
	private $duration;
	private $notes;
	private $completed;
	private $overdue_days;
	private $overdue_notes;
	private $id_task;
	private $id_project;

	private $current;

	protected static $_current_task = 0;
	protected static $_current_project = 0;
	//protected static $_derniere_tache = 0;

    function __construct( $task )
    {

        global $db;
        $this->db = $db;

        if( is_array($task) ) {
            // Création
            $this->insertTask( $task );
        }
        elseif ( is_numeric($task) ) {
            // Chargement
            $this->loadTask( $task );
        }
    }

	/**
	 * @return mixed
	 */
	public function getTaskName() {
		return $this->task_name;
	}

	/**
	 * @param mixed $task_name
	 */
	public function setTaskName( $task_name ) {
		$this->task_name = $task_name;
	}

	/**
	 * @return mixed
	 */
	public function getStartDate() {
		return $this->start_date;
	}

	/**
	 * @param mixed $start_date
	 */
	public function setStartDate( $start_date ) {
		$this->start_date = $start_date;
	}

	/**
	 * @return mixed
	 */
	public function getDuration() {
		return $this->duration;
	}

	/**
	 * @param mixed $duration
	 */
	public function setDuration( $duration ) {
		$this->duration = $duration;
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
	public function getCompleted() {
		return $this->completed;
	}

	/**
	 * @param mixed $completed
	 */
	public function setCompleted( $completed ) {
		$this->completed = $completed;
	}

	/**
	 * @return mixed
	 */
	public function getOverdueDays() {
		return $this->overdue_days;
	}

	/**
	 * @param mixed $overdue_days
	 */
	public function setOverdueDays( $overdue_days ) {
		$this->overdue_days = $overdue_days;
	}

	/**
	 * @return mixed
	 */
	public function getOverdueNotes() {
		return $this->overdue_notes;
	}

	/**
	 * @param mixed $overdue_notes
	 */
	public function setOverdueNotes( $overdue_notes ) {
		$this->overdue_notes = $overdue_notes;
	}

	/**
	 * @return mixed
	 */
	public function getIdTask() {
		return $this->id_task;
	}

	/**
	 * @param mixed $id_task
	 */
	public function setIdTask( $id_task ) {
		$this->id_task = $id_task;
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
	public function getCurrent() {
		return $this->current;
	}

	/**
	 * @param mixed $current
	 */
	public function setCurrent( $current ) {
		$this->current = $current;
	}

    public function updateStatusTask( $id_task){
        $query = $this->bd->update( ["completed" => 1] )
            ->table( 'tasks' )
            ->where( "id_task", "=", $id_task );

        $affectedRows = $query->execute( true );

        if( $affectedRows ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sauvegarde une nouvelle tâche dans la BD
     */
    private function insertTask( $task ) {
	    $query = $this->bd->insert( array_keys( $task )  )
	                               ->into( 'tasks' )
	                               ->values( array_values( $task ) );

	    $id_task = $query->execute( true );

	    if( $id_task ) {
		    return $this->loadTask( $id_task );
	    } else {
	    	return false;
	    }
    }

    /**
     * Récupère toutes les données d'une tâche selon son id
     * Et encapsules ses données
     */
    private function loadTask( $id_task ) {

        $query = $this->db->select()
            ->from( 'tasks' )
            ->where( 'id_task', '=', $id_task )
            ->orderby( 'id_task' );

        $stmt = $query->execute();
        $task = $stmt->fetch();

	    if( $task ) {
	        $this->setIdTask( $task['id_task'] );
		    $this->setTaskName( $task['task_name'] );
	        $this->setStartDate( $task['start_date'] );
	        $this->setDuration( $task['duration'] );
	        $this->setNotes( $task['notes'] );
	        $this->setOverdueDays( $task['overdue_days'] );
	        $this->setCompleted( $task['completed'] );
		    $this->setOverdueNotes( $task["overdue_notes"] );
	        $this->setIdProject( $task['id_project'] );

            $this->isCurrentTask();
		    return true;
	    } else {
	    	return false;
	    }
    }

    /**
     * Détermine la Task currente pour chaque projet
     *  Si la Task n'est pas terminée et que la Task currente n'a pas été définie
     *  et le projet current c'est le 0  juste pour la première fois.
     *  Ou si la tâche n'est pas terminée et que l'id du projet est différent à celui-là qui a déjà une tâche currente
     */
    private function isCurrentTask(){

        if ( ($this->getCompleted() == 0 && self::$_current_task == 0  && self::$_current_project == 0)
            || ( $this->getCompleted() == 0 && $this->getIdProject() != self::$_current_project) ){
            self::$_current_task = 1;  //Task currente est active
            self::$_current_project = $this->getIdProject();   //Projet current égal à l'id du projet
            $this->setCurrent(1);  //Task currente
        }
        else{
            self::$_current_task = 0;
            $this->setCurrent(0);
        }

    }

    private function getTaskStatus(){
        if ( $this->getCompleted() == 1 || self::$_current_task == 1){
            return "validate";
        }
    }

    /**
     * Récupère la date de fin d'une tâche selon sa date de début et sa durée en semaines
     */
    public function getEndDateTask(){

        $days_to_add = " +".( ($this->getDuration() * 7) - 1)." days";
        $end_date = date( 'Y-m-d', strtotime( $this->getStartDate() . $days_to_add ) );

        return strtotime( $end_date );
    }

    /**
     * Vérifié le status de la tâche pour pouvoir imprimer ses attributs
     * Si la tâche est terminée on ne peut plus la modifier, mais on peut voir ses notes
     * Si la tâche est la tâche currente on active le radiobutton pour la fermer lorsque la tâche sera finie
     */
    private function printTaskActions(){
        
        $active = "";
        
        if ( $this->getCompleted() == 1 ){
            $active .= '<a class="state_open state_close" href="javascript:;">
                            <i class="fa fa-circle-o" aria-hidden="true"></i>
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                        </a>';
        }
        elseif ( $this->getCurrent() == 1 ){
            
            $active .= '<a class="state_open" onclick="document.getElementById(\'form-id-'.$this->getIdTask().'\').submit();" >
                                <i class="fa fa-circle-o" aria-hidden="true"></i>
                                <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            </a>';
        }

        //Si la tâche as des notes associées à elle
        if ($this->getNotes()){
            $active .= '<a class="infos" ondblclick="showFormulaireAjouterNotes( this, '.$this->getIdTask().')" >
                            <i class="fa fa-info-circle tooltip" aria-hidden="true">
                                <span class="tooltiptext">'.$this->getNotes().'</span>
                            </i>
                        </a>';
        }else{  //Sinon on lance un formulaire pour ajouter des tâches
            $active .= '<a class="infos" href="javascript:;" onclick="showFormulaireAjouterNotes( this, '.$this->getIdTask().')" >
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        </a>';
        }

        return $active;
    }

    /**
     * Imprime una tâche et la place selon l'ordre dans la calendrier par rapport
     * à sa date de début
     *
     * Utilise l'objet global Helper
     */
    public function printTask( $project_order ){
        global $helper;

        $position_top = $this->getPositionTop(); //Position de la tâche en pixels
        $week = $helper::getDurationWeekCssClass( $this->getDuration() );
        $start_date = $helper::getFrenchFormatDate( strtotime( $this->getStartDate()) );
        $end_date = $helper::getFrenchFormatDate( $this->getEndDateTask() );

	    $task = '<div class="box projet'.$project_order.' '.$week.' '.$this->getTaskStatus().'" style="top:'.$position_top.'px;">';
	    $task .= '<h6 ondblclick="showDatePicker( this, '.$this->getIdTask().', '."'".$this->getStartDate()."'".' )" >'.$start_date.'</h6>';
        //$Task .= $this->getDateDebutAvecFormulaire();
	    $task .= '<form id="form-id-'.$this->getIdTask().'" action="classes/Task.php" method="post" >';
	    $task .= '<input type="hidden" name="id_task" value="'.$this->getIdTask().'" />';
	    $task .= '<input type="hidden" name="action" value="close_task" />';
	    $task .= '<p>'.$this->getTaskName().'<br>';
	    $task .= $this->printTaskActions();
	    $task .= '</p>';
	    $task .= '</form>';
	    $task .= '<h6>'.$end_date.'</h6>';
	    $task .= '</div>';

        echo $task;

	    //Si la tâche as un retard
        if ( $this->getOverdueDays() > 0 ){
            $this->printOverdueBlock( $project_order );
        }

    }

    /*public function getDateDebutAvecFormulaire(){

        $date_debut =  '<form method="post" action="classes/Task.php">';
        $date_debut .= '<input type="hidden" name="action" value="modifier_date_debut" />';
        $date_debut .= '<input ondblclick=" showDatePicker( this ); " name="date_debut" type="date" value="'.$this->getDateDebut().'" />';
        $date_debut .= '</form>';

        return $date_debut;
    }*/

    /**
     *  Imprime un retard selon la durée en semaines
     */
    public function printOverdueBlock( $project_order ){

        $position_top = $this->getPositionTopOverdueBlock();  //Position en pixel du retard
        $size = $this->getOverdueDays() * 15;

        $overdue_block = '<div class="box projet'.$project_order.'  warning" style="top:'.$position_top.'px; ">';
	    $overdue_block .= '<p style="padding-top: 0px; height: '.$size.'px;">';

        if ($this->getOverdueNotes()){
	        $overdue_block .= '<a class="infos" href="javascript:;">
                            <i class="fa fa-info-circle tooltip" aria-hidden="true">
                                <span class="tooltiptext">'.$this->getOverdueNotes().'</span>
                            </i>
                        </a>';
        }else{
	        $overdue_block .= '<a class="infos" href="javascript:;" onclick="showFormulaireAjouterNotesRetard( this, '.$this->getIdTask().')">
                            <i class="fa fa-plus-circle tooltip" aria-hidden="true"></i>
                        </a>';
        }

	    $overdue_block .= '</p>';
	    $overdue_block .= '</div>';

        echo $overdue_block;
    }

    /**
     * Récupère la position de la tâche en pixeles selon sa date de début
     * @return float
     */
    public function getPositionTop(){

        global $helper;

	    $difference_days = $helper::getDaysBetweenDates( $helper::$min_date, $this->getStartDate() );
        $week_day = $helper::getWeekDay( $helper::$min_date );

        return $position_top = ( $difference_days * 15) + ( ($week_day - 1) * 15 );
    }

    /**
     * Récupère la position du retard en pixeles selon sa date de début
     * @return float
     */
    public function getPositionTopOverdueBlock(){

        global $helper;

        $overdue_start_date = date( 'Y-m-d', strtotime( $this->getStartDate()." +". ($this->getDuration() * 7) ."days" ) );
        $difference_days = $helper::getDaysBetweenDates( $helper::$min_date, $overdue_start_date );
	    $week_day = $helper::getWeekDay( $helper::$min_date );

        return $position_top = ( $difference_days * 15) + ( ($week_day - 1) * 15 );
    }

    public function isTaskOverdue(){
        global $helper;

        $today = date("Y-m-d");
        if (  strtotime($today) > $this->getEndDateTask() ){
            $difference_days = $helper::getDaysBetweenDates( date( 'Y-m-d', $this->getEndDateTask() ), $today );
            $this->setOverdueDays($difference_days);

            return true;
        }
        else{
            return false;
        }

    }

    /**
     * Effectue les modifications dans la BD de la tâche currente selon ses attributs
     */
    public function save(){

        $task = [ "id_task" => $this->getIdTask(),
                    "task_name" => $this->getTaskName(),
                    "start_date" => $this->getStartDate(),
                    "duration" => $this->getDuration(),
                    "notes" => $this->getNotes(),
                    "overdue_notes" => $this->getOverdueNotes(),
                    "completed" => $this->getCompleted(),
                    "overdue_days" => $this->getOverdueDays(),
                    "id_project" => $this->getIdProject()
        ];

        $query = $this->db->update( $task )
            ->table( 'tasks' )
            ->where( "id_task", "=", $task["id_task"] );

        $affectedRows = $query->execute( true );

        if( $affectedRows ) {

            return true;
        } else {
            return false;
        }
    }

    /**
     * Met à jour toutes les dates qui n'ont pas de retard en ajoutant la quantité de jours de retard de la
     * tâche qui vient de avoir un retard en jours
     * @return bool
     */
    public function updateDatesWhenTaskIsOverdue( ){

        $query = $this->db->prepare( "UPDATE tasks
                                    SET start_date = DATE_ADD(start_date, INTERVAL ".$this->getOverdueDays()." DAY) 
                                    WHERE id_project = ". $this->getIdProject()." 
                                    AND overdue_days = 0 AND completed = 0" );

        $affectedRows = $query->execute();

        if ($affectedRows){
            return true;
        }else{
            return false;
        }
    }

    /*
     * Mets à jour tous les dates de debut des tâches après le dernier retard
     * oû la date de debut a été modifié selon les jours de difference
     */
    public function updateStartDatesWhenTaskIsOverdue( $new_date ){

        global $helper;

        $previous_date = $this->getStartDate();
        //$this->setDateDebut( $nouvelle_date );
        $difference_days = $helper::getDaysBetweenDates( $new_date, $previous_date );

        $query = $this->db->prepare( "UPDATE tasks
                                    SET start_date = DATE_ADD(start_date, INTERVAL ".($difference_days * -1)." DAY)
                                        WHERE id_project = ". $this->getIdProject()."
                                        AND id_task >= ". $this->getIdTask() );

        $affectedRows = $query->execute();

        if ( $affectedRows ){
            //Mettre à jour la quantité de journées de retard selon la difference de jours
            if ( $this->subtractOverdueDaysToPreviousDate( $difference_days ) ){
                $this->setOverdueDays( $difference_days * -1 );

            }
        }

    }

    /*
     * Mets à jour la quantité de jours de retard de la Task antérieure à celle qui vient d'être modifié
     *
     */
    public function subtractOverdueDaysToPreviousDate( $difference_days ){
        $query = $this->db->prepare( "UPDATE tasks ta
                                      INNER JOIN
                                      (
                                          SELECT MAX(id_task) as id_task
                                          FROM tasks
                                          WHERE id_task < ".$this->getIdTask()."
                                          AND completed = 1
                                      ) tac ON ta.id_task = tac.id_task
                                        SET ta.overdue_days = ta.overdue_days - $difference_days
                                        WHERE ta.id_task = tac.id_task" );

        $id_Task = $query->execute();

        if ( $id_Task ){

            return $id_Task;
        }
        else{
            return false;
        }
    }

}