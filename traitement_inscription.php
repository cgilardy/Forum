<?php
include("includes/config.php");
	if(strlen($_POST['passe']) >= 6 && $_POST['passe'] == $_POST['confirme'] && strlen($_POST['pseudo']) >=4){
		
		//On vérifie si le pseudo est déjâ possédé
		$requete = $bdd->prepare("SELECT pseudo FROM membres WHERE pseudo=:pseudo");
		$requete->execute(array('pseudo'=>$_POST['pseudo']));
		$nbPseudo = $requete->rowCount();
		
		if($nbPseudo > 0){
			header("Location: index.php?pseudo=no");
			exit();
		}
		$requete->closeCursor();
		//On vérifie si l'email est déjâ possédé	
		$requete = $bdd->prepare("SELECT email FROM membres WHERE email=:mail");
		$requete->execute(array('mail'=>$_POST['email']));
		$nbEmail = $requete->rowCount();
		
		if($nbEmail > 0){
			header("Location: index.php?email=no");
			exit();
		}
		$requete->closeCursor();
	//On continue le traitement
	$passe = md5($_POST['passe']);
	$pseudo = $_POST['pseudo'];
	$age = $_POST['age'];
	$sexe = $_POST['sexe'];
	//Traitement de l'image de l'animal
	$maxsize = 500000;
	$maxwidth = 600;
	$maxheight = 600;
	
	if ($_FILES['photo']['error'] > 0)
		echo '<p class="erreur">Attention : erreur lors du transfert !<br></p>';
	if ($_FILES['photo']['size'] > $maxsize)
		echo '<p class="erreur">Le fichier est trop gros !<br></p>';
	$extensions_valides = array('jpg', 'jpeg', 'gif','png');
	//1. strrchr renvoie l'extension avec le point (« . »).
	//2. substr(chaine,1) ignore le premier caractère de chaine.
	//3. strtolower met l'extension en lettres minuscules.
	$extension_upload = strtolower(  substr(  strrchr($_FILES['photo']['name'], '.')  ,1)  );
	if ( in_array($extension_upload,$extensions_valides) )
		echo '<p class="bon">Extension valide de l\'image<br></p>';
	$image_sizes = getimagesize($_FILES['photo']['tmp_name']);
	if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight)
		echo '<p class="erreur">Le format de l\'image est trop grand !<br></p>';
	$nom1 = md5(uniqid(rand(), true)).'_'.rand();
	$nom = "avatars/{$nom1}.{$extension_upload}";
	$resultat = move_uploaded_file($_FILES['photo']['tmp_name'],$nom);
	
	$description = $_POST['description'];
	$email = $_POST['email'];
	$maison = rand(1,4);
	
	/*calcule de l'id du membres pour l'inventaire*/
	$id = $bdd->query("SELECT id_membre as id FROM membres WHERE id_membre = (SELECT MAX(id_membre) FROM membres)");
	$reponse = $id->fetch();
	$identifiant = 1+$reponse['id'];
	$requete = $bdd->prepare("INSERT INTO inventaires (id_membre) VALUES(:id)");
	$requete->execute(array('id'=>$identifiant));
	$requete->closeCursor();
	
	/*calcule de l'id de l'inventaire*/
	$id_in = $bdd->query("SELECT id_inventaire as id FROM inventaires WHERE id_inventaire = (SELECT MAX(id_inventaire) FROM inventaires)");
	$reponse1 = $id_in->fetch();
	
	$requete = $bdd->prepare("INSERT INTO membres (pseudo,passe,description,id_maison,age,sexe,email,afficherEmail,date_inscription,avatar,rang,id_inventaire) VALUES(:pseudo,:passe,:desc,:maison,:age,:sexe,:email,:affi,CURDATE(),:avatar, :rang,:inventaire)");
	$requete->execute(array('pseudo'=>$pseudo,'passe'=>$passe, 'desc'=>$description, 'maison'=>$maison, 'age'=>$age, 'sexe'=>$sexe, 'email'=>$email, 'affi'=>0, 'avatar'=>$nom,'rang'=>3,'inventaire'=>$reponse1['id']))or die(print_r($requete->errorInfo()));
	$requete->closeCursor();
	header("Location: index.php?inscription=".$pseudo."");
	}
	else
		header("Location: index.php?inscription=no");
	
?>