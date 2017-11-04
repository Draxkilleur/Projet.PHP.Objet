<?php

require_once("actions/Action.inc.php");

class GetSurveysFormAction extends Action {

    /**
     * Traite les données envoyées par la requète.
     *

     *
     * @see Action::run()
     */
    public function run() {
        if ($this->getSessionLogin()===null){
            $this->setView(getViewByName("Surveys"));
            $this->getView()->setSurveys($this->database->loadSurveysForAll());
        }
        /* TODO END */
    }

}


?>
