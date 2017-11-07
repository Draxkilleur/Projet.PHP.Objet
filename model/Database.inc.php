<?php
require_once("model/Survey.inc.php");
require_once("model/Response.inc.php");

class Database {

	private $connection;

	/**
	 * Ouvre la base de données. Si la base n'existe pas elle
	 * est créée à l'aide de la méthode createDataBase().
	 */
	public function __construct() {
		$dbHost = "localhost";
		$dbBd = "sondages";
		$dbPass = "";
		$dbLogin = "root";
		$url = 'mysql:host='.$dbHost.';dbname='.$dbBd;
		//$url = 'sqlite:database.sqlite';
		$this->connection = new PDO($url, $dbLogin, $dbPass);
		if (!$this->connection) die("impossible d'ouvrir la base de données");
		$this->createDataBase();
	}


	/**
	 * Initialise la base de données ouverte dans la variable $connection.
	 * Cette méthode crée, si elles n'existent pas, les trois tables :
	 * - une table users(nickname char(20), password char(50));
	 * - une table surveys(id integer primary key autoincrement,
	 *						owner char(20), question char(255));
	 * - une table responses(id integer primary key autoincrement,
	 *		id_survey integer,
	 *		title char(255),
	 *		count integer);
	 */
	private function createDataBase() {
		/* TODO START */
		$this->connection->exec("
		CREATE DATABASE IF NOT EXISTS sondages CHARACTER SET 'utf8';

		USE sondages;

		CREATE TABLE `responses` (
			`id` int(5) NOT NULL AUTO_INCREMENT,
			`id_survey` int(3) NOT NULL,
			`title` varchar(255) NOT NULL,
			`count` int(5) NOT NULL,
			PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE `surveys` (
			`id` int(5) NOT NULL AUTO_INCREMENT,
			`owner` varchar(20) NOT NULL,
			`question` varchar(255) NOT NULL,
			PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		CREATE TABLE `users` (
			`nickname` varchar(20) NOT NULL,
			`password` varchar(50) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		");
		/* TODO END */
	}

	/**
	 * Vérifie si un pseudonyme est valide, c'est-à-dire,
	 * s'il contient entre 3 et 10 caractères et uniquement des lettres.
	 *
	 * @param string $nickname Pseudonyme à vérifier.
	 * @return boolean True si le pseudonyme est valide, false sinon.
	 */
	private function checkNicknameValidity($nickname) {
		/* TODO START */

		if(strlen($nickname) <= 10 and strlen($nickname) >= 3 ){
			 return true;
		}
		 return false;
		/* TODO END */
	}

	/**
	 * Vérifie si un mot de passe est valide, c'est-à-dire,
	 * s'il contient entre 3 et 10 caractères.
	 *
	 * @param string $password Mot de passe à vérifier.
	 * @return boolean True si le mot de passe est valide, false sinon.
	 */
	private function checkPasswordValidity($password) {
		/* TODO START */
		if(strlen($password) <= 10 and strlen($password) >= 3 ){
			return true;
		}
		return false;
		/* TODO END */
	}

	/**
	 * Vérifie la disponibilité d'un pseudonyme.
	 *
	 * @param string $nickname Pseudonyme à vérifier.
	 * @return boolean True si le pseudonyme est disponible, false sinon.
	 */
	private function checkNicknameAvailability($nickname) {
		/* TODO START */
		$req = $this->connection->query("SELECT nickname FROM users WHERE nickname='".$nickname."'");
		$req=$req->fetchAll();
		if (count($req)!=0){
			return false;
		}
		else{
            return true;
		}


		/* TODO END */
	}

	/**
	 * Vérifie qu'un couple (pseudonyme, mot de passe) est correct.
	 *
	 * @param string $nickname Pseudonyme.
	 * @param string $password Mot de passe.
	 * @return boolean True si le couple est correct, false sinon.
	 */
	public function checkPassword($nickname, $password) {
		/* TODO START */
		$req = $this->connection->query("SELECT password FROM users WHERE nickname='".$nickname."'");
		$mdp = $req->fetchAll();
		if($mdp[0]['password'] == md5($password)){
			return true;
		}
		return false;
		/* TODO END */
	}

	/**
	 * Ajoute un nouveau compte utilisateur si le pseudonyme est valide et disponible et
	 * si le mot de passe est valide. La méthode peut retourner un des messages d'erreur qui suivent :
	 * - "Le pseudo doit contenir entre 3 et 10 lettres.";
	 * - "Le mot de passe doit contenir entre 3 et 10 caractères.";
	 * - "Le pseudo existe déjà.".
	 *
	 * @param string $nickname Pseudonyme.
	 * @param string $password Mot de passe.
	 * @return boolean|string True si le couple a été ajouté avec succès, un message d'erreur sinon.
	 */
	public function addUser($nickname, $password) {
	  /* TODO START */
		if($this->checkNicknameValidity($nickname) == false){
			return "Le pseudo doit contenir entre 3 et 10 lettres.";
		}
		elseif($this->checkNicknameAvailability($nickname) == false){
			return "Le pseudo existe déjà.";
		}
		elseif($this->checkPasswordValidity($password) == false){
			return "Le mot de passe doit contenir entre 3 et 10 caractères.";
		}
		else{
			$this->connection->exec("INSERT INTO users VALUES ('$nickname', '".md5($password)."')");

		}
	  /* TODO END */
	  return true;
	}

	/**
	 * Change le mot de passe d'un utilisateur.
	 * La fonction vérifie si le mot de passe est valide. S'il ne l'est pas,
	 * la fonction retourne le texte 'Le mot de passe doit contenir entre 3 et 10 caractères.'.
	 * Sinon, le mot de passe est modifié en base de données et la fonction retourne true.
	 *
	 * @param string $nickname Pseudonyme de l'utilisateur.
	 * @param string $password Nouveau mot de passe.
	 * @return boolean|string True si le mot de passe a été modifié, un message d'erreur sinon.
	 */
	public function updateUser($nickname, $password) {
		/* TODO START */
		if ($this->checkPasswordValidity($password) == false){
			return "Le mot de passe doit contenir entre 3 et 10 caractères.";
		}
		else{
			$this->connection->exec("UPDATE users SET password ='".md5($password)."' WHERE nickname='".$nickname."'");
		}
		/* TODO END */
	  return true;
	}

	/**
	 * Sauvegarde un sondage dans la base de donnée et met à jour les indentifiants
	 * du sondage et des réponses.
	 *
	 * @param Survey $survey Sondage à sauvegarder.
	 * @return boolean True si la sauvegarde a été réalisée avec succès, false sinon.
	 */
	public function saveSurvey($survey) {
		/* TODO START */

		$Question = $survey->getQuestion();

		$Owner = $survey->getOwner();

		$this->connection->exec("INSERT INTO surveys (owner, question) VALUES ('$Owner', '$Question')");
		$req = $this->connection->query("Select id from surveys where owner = '$Owner' and question = '$Question'");
		$idSurvey = $req->fetch();
		$survey->setId($idSurvey['id']);
		$this->saveResponse($survey);
		/* TODO END */
		return true;
	}

	/**
	 * Sauvegarde une réponse dans la base de donnée et met à jour son indentifiant.
	 *
	 * @param Response $response Réponse à sauvegarder.
	 * @return boolean True si la sauvegarde a été réalisée avec succès, false sinon.
	 */
	private function saveResponse($response) {
		/* TODO START */
		$idSurvey = $response->getId();
		foreach ($response->getResponses() as $key => $value) {
			if($value != ""){
				$this->connection->exec("INSERT INTO responses (id_survey, title, count) VALUES ('$idSurvey', '$value', 0)");
			}

		}
		/* TODO END */
		return true;
	}

	/**
	 * Charge l'ensemble des sondages créés par un utilisateur.
	 *
	 * @param string $owner Pseudonyme de l'utilisateur.
	 * @return array(Survey)|boolean Sondages trouvés par la fonction ou false si une erreur s'est produite.
	 */
	public function loadSurveysByOwner($owner) {
		/* TODO START */
		$req = $this->connection->query("SELECT * FROM surveys WHERE owner='".$owner."'");
		return $this->loadSurveys($req);
        /* TODO END */
	}
    /**
     * Charge l'ensemble des sondages
	 *
     * @return array(Survey)|boolean Sondages trouvés par la fonction ou false si une erreur s'est produite.
     */
    public function loadSurveysForAll() {
        /* TODO START */
        $req = $this->connection->query("SELECT * FROM surveys");
        return $this->loadSurveys($req);
        /* TODO END */
    }

	/**
	 * Charge l'ensemble des sondages dont la question contient un mot clé.
	 *
	 * @param string $keyword Mot clé à chercher.
	 * @return array(Survey)|boolean Sondages trouvés par la fonction ou false si une erreur s'est produite.
	 */
	public function loadSurveysByKeyword($keyword) {
		/* TODO START */
		$req=$this->connection->query("SELECT * FROM surveys WHERE question LIKE '%".$keyword."%'");
		return $this->loadSurveys($req);
		/* TODO END */
	}


	/**
	 * Enregistre le vote d'un utilisateur pour la réponse d'identifiant $id.
	 *
	 * @param int $id Identifiant de la réponse.
	 * @return boolean True si le vote a été enregistré, false sinon.
	 */
	public function vote($id) {
		/* TODO START */
		$this->connection->exec("UPDATE responses SET count = count +1 WHERE id='".$id."'");
		/* TODO END */
	}

	/**
	 * Construit un tableau de sondages à partir d'un tableau de ligne de la table 'surveys'.
	 * Ce tableau a été obtenu à l'aide de la méthode fetchAll() de PDO.
	 *
	 * @param array $arraySurveys Tableau de lignes.
	 * @return array(Survey)|boolean Le tableau de sondages ou false si une erreur s'est produite.
	 */
	private function loadSurveys($arraySurveys) {
		$surveys = array();
		/* TODO START */
		$result=$arraySurveys->fetchAll();
		foreach ($result as $key => $values){
			$survey=new Survey($values['owner'], $values['question']);
			$survey->setId($values['id']);
			$req=$this->connection->query("SELECT * FROM responses WHERE id_survey=".$values['id']."");
			$survey->setResponses($this->loadResponses($survey, $req));
			$surveys[]=$survey;
		}
		/* TODO END */
		return $surveys;
	}

	/**
	 * Construit un tableau de réponses à partir d'un tableau de ligne de la table 'responses'.
	 * Ce tableau a été obtenu à l'aide de la méthode fetchAll() de PDO.
	 *
	 * @param Survey $survey Le sondage.
	 * @param array $arraySurveys Tableau de lignes.
	 * @return array(Response)|boolean Le tableau de réponses ou false si une erreur s'est produite.
	 */
	private function loadResponses($survey, $arrayResponses) {
		$responses = array();
		/* TODO START */
        $result=$arrayResponses->fetchAll();
        foreach ($result as $key => $values){
            $response=new Response($survey, $values['title'], $values['count']);
            $response->setId($values['id']);
            $responses[] = $response;
        }
		/* TODO END */
		return $responses;
	}

    /**
	 * Fonction de suppression de sondage par ID de sondage
     * @param $id
     * @return bool
     */
	public function deleteSurvey($id){
		$req = "DELETE FROM responses where id_survey=".$id.";";
        $req .= "DELETE FROM surveys where id=".$id.";";
        echo $req;
		$r = $this->connection->prepare($req);
        if($r->execute())
        	return true;
        else
        	return false;
	}

    /**
	 * Fonction de chargement de sondage par ID
     * @param $id
     * @return array
     */
	public function loadSurveysById($id){
        $req = $this->connection->query("SELECT * FROM surveys WHERE id=".$id);
        return $this->loadSurveys($req);
	}

	public function pushEdit($id){
		$req2="";
		$req="UPDATE surveys SET question='".$_POST['questionSurvey']."' WHERE id=".$id.";";
		$req.="DELETE title FROM responses WHERE id_surveys=".$id.";";
		for ($i=0; $i<=4; $i++){
            $req2.="INSERT INTO responses (title) VALUES ('".$_POST['responseSurvey']."');";
		}
		echo $req.$req2;
		$this->connection->exec($req.$req2);
	}
}
?>
