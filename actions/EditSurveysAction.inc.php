<?php

require_once("actions/Action.inc.php");

class EditSurveysAction extends Action {

    /**
     * Lance la fonction de suppression de sondages
     *

     *
     * @see Action::run()
     */
    public function run() {
        echo $this->database->pushEdit($_REQUEST['id']);
        if($this->database->pushEdit($_REQUEST['id']))
        {
            $this->setView(getViewByName("Surveys"));
            $this->getView()->setSurveys($this->database->loadSurveysForAll());
        }

        /* TODO END */
    }

}


?>
