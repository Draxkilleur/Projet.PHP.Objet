
<li class="media well">
	<div class="media-body">
		<h4 class="media-heading"><?php echo $survey->getQuestion() ?></h4>
		<br>
	  <?php
	  foreach ($survey->getResponses() as $response) {
	  /* TODO START */
          echo '<div class="fluid-row">
			<div class="span2">'.$response->getTitle().'</div>
			<div class="span2 progress progress-striped active">
				<div class="bar" style="width: '.$response->computePercentage($percent).'%"></div>
			</div>
			<span class="span1">('.$response->computePercentage($percent).'%)</span>
			<form class="span1" method="post" action="?action=Vote">
				<input type="hidden" name="responseId" value="'.$response->getId().'">
				<input type="submit" style="margin-left:5px" class="span1 btn btn-small btn-danger" value="Voter">
			</form>
		</div>';
		/* TODO END */
		} 
		?>
	</div>
</li>



