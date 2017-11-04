<?php
require_once("views/View.inc.php");

class GetSurveysFormView extends View {

    /**
     * Affiche les sondages.
     *
     * @see View::displayBody()
     */
    public function displayBody() {
        require("templates/surveys.inc.php");
    }

}
?>


