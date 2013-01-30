<?php
include("includes/config.php");

$pseudo = $_POST['pseudo'];
$passe = md5($_POST['passe']);

$requete = $bdd->prepare("SELECT * FROM membres WHERE pseudo = :pseudo and passe = :passe");
$requete->execute(array('pseudo'=>$pseudo,'passe'=>$passe));
$nbRep = $requete->rowCount();
$reponse = $requete->fetch();

if($nbRep == 1){
	$_SESSION['session'] = true;
	$_SESSION['id'] = $reponse['id_membre'];
	$_SESSION['pseudo'] = $reponse['pseudo'];
	$_SESSION['rang'] = $reponse['rang'];
	$_SESSION['avatar'] = $reponse['avatar'];
	$_SESSION['inventaire'] = $reponse['id_inventaire'];
	$_SESSION['afficher'] = $reponse['afficherEmail'];
	header("Location: index.php?connexion=ok");
}
else
	header("Location: index.php?connexion=no");
?>