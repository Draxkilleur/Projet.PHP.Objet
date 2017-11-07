<?php

require_once("actions/Action.inc.php");

class EditSurveysFormAction extends Action {

    /**
     * Lance la fonction de suppression de sondages
     *

     *
     * @see Action::run()
     */
    public function run() {
        if($this->database->loadSurveysById($_REQUEST['id']))
        {
            $this->setView(getViewByName("EditSurveysForm"));
            $this->getView()->setSurveys($this->database->loadSurveysById($_REQUEST['id']));
        }

        /* TODO END */
    }

}


?>
