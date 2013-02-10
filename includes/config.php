<?php 
session_start(); // cette fonction est obligatoire dans toute les pages avant le code html

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=RPG', 'root', ''); //connexion en pdo
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

/************** Fonction ******************
********* Affichage du rang ! *************
*******************************************/

function rang($num,$sexe){
	switch($num)
	{
		case 0 :
			$texte = 'Directeur de l\'école';
		break;
		case 1 :
			if($sexe == 'Homme')
				$texte = "Directeur";
			else
				$texte = "Directrice";
		break;
		case 3 :
			if($sexe == 'Homme')
				$texte = "Collégien";
			else
				$texte = "Collégienne";
		break;
	}
	return $texte;
}

/************** Fonction ******************
******** Affichage de la maison ! *********
*******************************************/

function maison($nb,$text){

	switch($nb)
	{
		case 1 :
			$texte = '<span style="color: red">'.$text.'</span>';
		break;
		case 2 :
			$texte = '<span style="color: green">'.$text.'</span>';
		break;
		case 3 :
			$texte = '<span style="color: blue">'.$text.'</span>';
		break;
		case 4 :
			$texte = '<span style="color: yellow">'.$text.'</span>';
		break;
		default :
			$texte = "Pas de maison";
		break;
	}
	
	return $texte;
}

/************** Fonction ******************
********* Affichage du blason ! ***********
*******************************************/

function blason($id_membre){
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=RPG', 'root', ''); //connexion en pdo
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	$requete = $bdd->prepare("SELECT * FROM membres ME JOIN maisons MA ON ME.id_maison=MA.id_maison WHERE id_membre=:id");
	$requete->execute(array('id'=>$id_membre));
	$blason = $requete->fetch();
	$image = $blason['blason'];
	return $image;
}

/************** Fonction ******************
****** supression d'une reponse ***********
*******************************************/

function supReponse($id){
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=RPG', 'root', ''); //connexion en pdo
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	
	$requete = $bdd->prepare("DELETE FROM forum_reponses WHERE id_reponse = :id");
	$requete->execute(array('id'=>intval($id)));
	$requete->closeCursor();
	echo '<p class="bon">la réponse a bien été supprimer !</p>';
}


/************** Fonction ******************
********* suppression d'un sujet ! ********
*******************************************/

function supPetitSujet($id_sujet){
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=RPG', 'root', ''); //connexion en pdo
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	//suppression des luts
	$lus = $bdd->prepare("DELETE FROM lus_petitsuj WHERE id_petitsuj=:id");
	$lus->execute(array('id'=>$id_sujet));
	$lus->closeCursor();
	//suppression des réponses
	$requete = $bdd->prepare("DELETE FROM forum_petitrep WHERE id_petitsuj=:id");
	$requete->execute(array('id'=>intval($id_sujet)));
	$requete->closeCursor();
	
	//suppression du sujet
	$requete = $bdd->prepare("DELETE FROM forum_petitsujet WHERE id_petitsuj =:id");
	$requete->execute(array('id'=>intval($id_sujet)));
	$requete->closeCursor();
}



/************** Fonction ******************
********* suppression d'un sujet ! ********
*******************************************/

function supSujet($id_sujet){
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=RPG', 'root', ''); //connexion en pdo
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	//suppression des luts
	$lus = $bdd->prepare("DELETE FROM lus_sujets WHERE id_sujet=:id");
	$lus->execute(array('id'=>$id_sujet));
	$lus->closeCursor();
	//suppression des réponses
	$requete = $bdd->prepare("DELETE FROM forum_reponses WHERE id_sujet=:id");
	$requete->execute(array('id'=>intval($id_sujet)));
	$requete->closeCursor();
	
	//suppression du sujet
	$requete = $bdd->prepare("DELETE FROM forum_sujets WHERE id_sujet =:id");
	$requete->execute(array('id'=>intval($id_sujet)));
	$requete->closeCursor();
}

/************** Fonction ******************
********* Affichage de la date ! **********
*******************************************/

function temps($time){
	$texte = null;
	$tempsReel = time()+3600;
	$resultat = $tempsReel-$time;
	if($resultat < 60)
		$texte = 'il y a '.$resultat.' secondes';
	else if($resultat > 60 and $resultat < 3600){
		$resultat = intval($resultat/60);
		if($resultat == 1)
			$texte= 'il y a '.$resultat.' minute';
		else
			$texte= 'il y a '.$resultat.' minutes';
	}
	else if($resultat > 3600 and $resultat < 86400){
		$resultat = intval($resultat/3600);
		if($resultat == 1)
			$texte = 'il y a '.$resultat.' heure';
		else
			$texte = 'il y a '.$resultat.' heures';
	}
	else{
		$texte = 'le '.date('j/m/Y à G:i:s',$time);
	}
	return $texte;
}
/************** Fonction ******************
********* Affichage du bbcode ! ***********
*******************************************/
function code($texte){
    $texte = htmlentities($texte);
	
    //gras
    $texte = preg_replace("/&lt;gras&gt;(.*)&lt;\/gras&gt;/siU", "<span class='gras_code'>$1</span>", $texte);
	//italique
    $texte = preg_replace("/&lt;italic&gt;(.*)&lt;\/italic&gt;/siU", "<span class='italic_code'>$1</span>", $texte);
	//lien
    $texte = preg_replace("/&lt;lien=&quot;(.*)&quot;&gt;(.*)&lt;\/lien&gt;/siU", "<a href='$1'>$2</a>", $texte);
	//image
    $texte = preg_replace("/&lt;image=&quot;(.*)&quot;\/&gt;/siU", "<img width='200' src='$1'>", $texte);
	//citation
    $texte = preg_replace("/&lt;citation=&quot;(.*)&quot;&gt;(.*)&lt;\/citation&gt;/siU", "<p class='citation_code'><span class='souligne_code' >Citation de $1 :</span><br><span class='italic_code'>$2</span></p>", $texte);
	//souligner
    $texte = preg_replace("/&lt;souligne&gt;(.*)&lt;\/souligne&gt;/siU", "<span class='souligne_code'>$1</span>", $texte);
	//couleur
    $texte = preg_replace("#&lt;color=&quot;(red|green|purple|blue|yellow)&quot;&gt;(.*)&lt;/color&gt;#siU", "<span style=\"color:$1\">$2</span>", $texte);
	//taille
    $texte = preg_replace("#&lt;taille valeur=&quot;(8px|10px|12px|18px|22px|26px)&quot;&gt;(.*)&lt;/taille&gt;#siU", "<span style=\"font-size:$1\">$2</span>", $texte);
    
    return($texte);
}
?>