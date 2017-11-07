<?php

require_once("actions/Action.inc.php");

class DeleteSurveysAction extends Action {

    /**
     * Lance la fonction de suppression de sondages
     *

     *
     * @see Action::run()
     */
    public function run() {
        if($this->database->deleteSurvey($_REQUEST['id']))
        {
            $this->setView(getViewByName("Surveys"));
            $this->getView()->setSurveys($this->database->loadSurveysForAll());
        }

        /* TODO END */
    }

}


?>
