<?php

class MembreManager {
	private $_bdd;

	//constructeur
	public function __construct(PDO $bdd){
		$this->setBdd($bdd); 
	}
	//setter
	public function setBdd(PDO $bdd){
		$this->_bdd = $bdd;
	}
	//methode
	public function add(Membre $m){
		
		$q = $this->_bdd->prepare("INSERT INTO membres (pseudo,passe,description,id_maison,age,sexe,email,afficherEmail,date_inscription,avatar,signature,id_rang,id_inventaire) VALUES(:pseudo,:passe,:descr,:maison,:age,:sexe,:email,:affi,:datei,:avatar,:signature, :rang,:inventaire)");
		$q->execute(array('pseudo'=>$m->getPseudo(),'passe'=>$m->getPasse(), 'descr'=>$m->getDescription(), 'maison'=>$m->getIdMaison(), 'age'=>$m->getAge(), 'sexe'=>$m->getSexe(), 'email'=>$m->getEmail(), 'affi'=>$m->getAfficheMail(),'datei'=>$m->getDateInscription(), 'avatar'=>$m->getAvatar(),'signature'=>$m->getSignature(),'rang'=>$m->getIdRang(),'inventaire'=>$m->getIdInventaire()))or die(print_r($q->errorInfo()));
		header("Location: index.php?inscription=".$m->getPseudo()."");
	}

	public function delete(Membre $m){
		$q = $this->_bdd->prepare("DELETE FROM membres WHERE id_membre=:id");
		$q->execute(array('id'=>$m->getId()));
	}

	public function get($id){
		$q = $this->_bdd->prepare("SELECT * FROM membres WHERE id_membre=:id");
		$q->execute(array('id'=>$id))
		$donnees = $q->fetch(PDO::FETCH_ASSOC);
		return new Membre($donnees);
	}

	public function getList(){
		$membre = array();
		$q->$this->_bdd->query("SELECT * FROM membres");
		while($donnees = $q->fetch(PDO::FETCH_ASSOC)){
			$membre[] = new Membre($donnees);
		}
		return $membre;
	}

}

?>