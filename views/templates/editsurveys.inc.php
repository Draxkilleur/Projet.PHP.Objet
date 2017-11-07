<?php

function generateInputForResponse($n, $value) {
    ?>
    <div class="control-group">
        <label class="control-label" for="responseSurvey<?php echo $n; ?>">Réponse <?php echo $n; ?></label>
        <div class="controls">
            <input class="span3" type="text" name="responseSurvey<?php echo $n; ?>" value="<?php echo $value->getTitle() ?>">
        </div>
    </div>
    <?php
}
?>

<form method="post" action="index.php?action=EditSurveys&id=<?php echo $this->survey->getId() ?>" class="modal">
    <div class="modal-header">
        <h3>Modification du sondage</h3>
    </div>
    <div class="form-horizontal modal-body">
        <?php	if ($this->message!=="")
            echo '<div class="alert '.$this->style.'">'.$this->message.'</div>';
        ?>
        <div class="control-group">
            <label class="control-label" for="questionSurvey">Question</label>
            <div class="controls">
                <input class="span3" type="text" name="questionSurvey" value="<?php echo $this->survey->getQuestion(); ?>">
            </div>
        </div>
        <br>
        <?php
        $i=0;
        $responses = $this->survey->getResponses();
        foreach ($responses as $key => $values){
            $i = $key++;
            generateInputForResponse($i, $values);
        }

        ?>
    </div>
    <div class="modal-footer">
        <input class="btn btn-danger" type="submit"	value="Modifier le sondage" />
    </div>
</form>



