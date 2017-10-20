<?php

require_once("actions/Action.inc.php");

class LoginAction extends Action {

	/**
	 * Traite les données envoyées par le visiteur via le formulaire de connexion
	 * (variables $_POST['nickname'] et $_POST['password']).
	 * Le mot de passe est vérifié en utilisant les méthodes de la classe Database.
	 * Si le mot de passe n'est pas correct, on affiche le message "Pseudo ou mot de passe incorrect."
	 * Si la vérification est réussie, le pseudo est affecté à la variable de session.
	 *
	 * @see Action::run()
	 */
	public function run() {
  	/* TODO START */
		if($this->database->checkPassword( $_POST['nickname'], $_POST['password']) == true){
			$this->setSessionLogin($_POST['nickname']);
			$this->setView(getViewByName("Message"));
			$this->getView()->setMessage("Vous êtes désormais connecté");
		}
		else{
			$this->setView(getViewByName("Message"));
			$this->getView()->setMessage("Pseudonyme ou Mot de passe incorrect");
		}
  	/* TODO END */
	}

}

?>