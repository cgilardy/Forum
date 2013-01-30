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

function rang($num){
	switch($num)
	{
		case 1 :
			$texte = "Directeur";
		break;
		case 3 :
			$texte = "Coll�gien";
		break;
	}
	
	return $texte;
}

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
	$requete->execute(array('id'=>$id));
	$requete->closeCursor();
	echo '<p class="bon">la réponse a bien été supprimer !</p>';
}
?>