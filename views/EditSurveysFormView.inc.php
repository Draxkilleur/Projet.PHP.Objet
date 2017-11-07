<?php
require_once("views/View.inc.php");

class EditSurveysFormView extends View {

    private $survey;
    /**
     * Affiche le formulaire de modification de sondage.
     *
     * @see View::displayBody()
     */
    public function displayBody() {
        require("templates/editsurveys.inc.php");
    }

    public function setSurveys($survey) {
        $this->survey = $survey[0];
    }
}
?>


