<?php
class Membre {
	private $_id;
	private $_pseudo;
	private $_passe;
	private $_description;
	private $_idMaison;
	private $_age;
	private $_sexe;
	private $_email;
	private $_afficheMail;
	private $_dateInscription;
	private $_avatar;
	private $_signature;
	private $_idRang;
	private $_idInventaire;

	//constructeur
	public function __construct(array $donnees){
		
		foreach ($donnees as $key => $value)
		{
			$method = 'set'.ucfirst($key);
		    // Si le setter correspondant existe.
		    if (method_exists($this, $method))
		    {
		      // On appelle le setter.
		      $this->$method($value);
		    }
		}
	}

	//getter
	public function getId(){return $this->_id;}
	public function getPseudo(){return $this->_pseudo;}
	public function getPasse(){return $this->_passe;}
	public function getDescription(){return $this->_description;}
	public function getIdMaison(){return $this->_idMaison;}
	public function getAge(){return $this->_age;}
	public function getSexe(){return $this->_sexe;}
	public function getEmail(){return $this->_email;}
	public function getAfficheMail(){return $this->_afficheMail;}
	public function getDateInscription(){return $this->_dateInscription;}
	public function getAvatar(){return $this->_avatar;}
	public function getSignature(){return $this->_signature;}
	public function getIdRang(){return $this->_idRang;}
	public function getIdInventaire(){return $this->_idInventaire;}

	//setter
	public function setId($id) {
		if(is_int($id))
			$this->_id = $id;
	}
	public function setPseudo($pseudo) {
		if(is_string($pseudo))
			$this->_pseudo = $pseudo;
	}
	public function setPasse($passe) {
		$this->_passe = $passe;
	}
	public function setDescription($des) {
		if(is_string($des))
			$this->_description = $des;
	}
	public function setIdMaison($id){
		if(is_int($id))
			$this->_idMaison = $id;
	}
	public function setAge($age){
		if(is_int($age) && $age > 10)
			$this->_age = $age;
	}
	public function setSexe($sexe){
		$this->_sexe = $sexe;
	}
	public function setMail($mail){
		$this->_email = $mail;
	}
	public function setAfficheMail($bool){
		$this->_afficheMail = $bool;
	}
	public function setDateInscription($date){
		$this->_dateInscription = $date;
	}
	public function setAvatar($avatar){
		$this->_avatar = $avatar;
	}
	public function setSignature($sign){
		$this->_signature = $sign;
	}
	public function setIdInventaire($i){
		if(is_int($i))
			$this->_idInventaire = $i;
	}
	public function setIdRang($rang){
		if(is_int($rang))
			$this->_idRang = $rang;
	}
}
?>