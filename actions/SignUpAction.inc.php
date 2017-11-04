<?php

require_once("actions/Action.inc.php");

class SignUpAction extends Action {
	/**
	 * Traite les données envoyées par le formulaire d'inscription
	 * ($_POST['signUpLogin'], $_POST['signUpPassword'], $_POST['signUpPassword2']).
	 *
	 * Le compte est crée à l'aide de la méthode 'addUser' de la classe Database.
	 *
	 *
     * Si la fonction 'addUser' retourne une erreur ou si le mot de passe et sa confirmation
     * sont différents, on envoie l'utilisateur vers la vue 'SignUpForm' contenant
     * le message retourné par 'addUser' ou la chaîne "Le mot de passe et sa confirmation
     * sont différents.";
	 * Si l'inscription est validée, le visiteur est envoyé vers la vue 'MessageView' avec
	 * un message confirmant son inscription.
	 *
	 * @see Action::run()
	 */
	public function run() {
		/* TODO START */

		if( $_POST['signUpPassword'] != $_POST['signUpPassword2']){	// On vérifie que le mot de passe et sa confirmation sont identiques
            $this->setSignUpFormView("Les mots de passe ne correspondent pas.",'alert-error');
		}
		elseif ($this->database->addUser($_POST['signUpLogin'], $_POST['signUpPassword'])!= 1){
            $this->setView(getViewByName("SignUpForm"));
            $this->getView()->setMessage($this->database->addUser($_POST['signUpLogin'], $_POST['signUpPassword'], 'alert-error'));
		}
		else{
			$this->setSignUpFormView("Vous êtes à présent enregistré, veuillez vous connecter");
		}

		 	//On fait appel a AddUser pour ajouter a la base de données l'utilisateur
		/* TODO END */
	}

	private function setSignUpFormView($message) {
		$this->setView(getViewByName("SignUpForm"));
		$this->getView()->setMessage($message, 'alert-error');
	}

}


?>
