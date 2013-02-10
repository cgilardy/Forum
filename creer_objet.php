<?php

$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
	
		if(isset($_SESSION['session']) and $_SESSION['rang'] <= 1){
			
			echo'<p class="ariane">> <a href="index.php">Forum</a> > <a href="administration.php">Administration</a> > Créer un objet</p><br>';
			echo '<h1>Créer un objet</h1><br>';
			
			if(isset($_GET['ajout'])){
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
				$image = "objets/{$nom1}.{$extension_upload}";
				$resultat = move_uploaded_file($_FILES['photo']['tmp_name'],$image);
				
				$requete = $bdd->prepare("INSERT INTO objets (type_objet,nom_objet,image_objet,caracteristique_objet,force,agilite,intel,pouvoir) VALUES(:type,:nom,:image,:cara,:force,:agilite,:intel,:pouvoir)");
				$requete->execute(array('type'=>$_POST['type'], 'nom'=>$_POST['nom'], 'image'=>$image, 'cara'=>$_POST['description'], 'force'=>$_POST['force'], 'agilite'=>$_POST['agi'], 'intel'=>$_POST['intel'], 'pouvoir'=>$_POST['power'])) or die(print_r($requete->errorInfo()));	
				$requete->closeCursor();
			}
			
			echo '
				<form id="creerObjet" method="post" action="creer_objet.php?ajout=ok" enctype="multipart/form-data">
				<fieldset id="objet" style="float:left;">
				<legend>Objet</legend>
					<table>
						<tr>
							<td>Nom : </td>
							<td><input id="nom" name="nom" type="text" size="30" autofocus required placeholder="Nom de l\'objet" /></td>
						</tr>
						<tr>
							<td>Type :</td>
							<td><input id="type" name="type" type="text" required></td>
						</tr>
						<tr>
							<td>Image : </td>
							<td><input name="photo" type="file" required/></td>
						</tr>
						<tr>
							<td>Description: </td>
							<td><textarea id="description" name="description" cols="50" rows="5" placeholder="Caractéristique de l\'objet"></textarea></td>
						</tr>
					</table>
				</fieldset>
				<fieldset id="caraObjet">
				<legend>Caractéristique</legend>
					<table>
						<tr>
							<td>Force : </td>
							<td><input id="force" name="force" type="number" value="0" /></td>
						</tr>
						<tr>
							<td>Agilité : </td>
							<td><input id="agi" name="agi" type="number" value="0"/></td>
						</tr>
						<tr>
							<td>Intelligence : </td>
							<td><input id="intel" name="intel" type="number" value="0"/></td>
						</tr>
						<tr>
							<td>Pouvoir : </td>
							<td><input id="power" name="power" type="number" value="0"/></td>
						</tr>
					</table>
				</fieldset>
					<center>
						<input name="Submit" type="submit" id="creer" value="Créer l\'objet"/>
					</center>
				</form>
						';
		}
	echo '</article>';

echo'</section>';

include("includes/bas.php");
?>

<script src="JS/creer_objet.js"></script>