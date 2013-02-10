<?php

$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
		echo'<p class="ariane">> <a href="index.php">Forum</a> > Administration</p><br>';
		echo '<h1>Administration</h1>';
		
		/************ Requete ************
		******** Compte des membres ******
		**********************************/
		
		$requete = $bdd->query("SELECT * FROM membres");
		$nbMembres = $requete->rowCount();
		$requete->closeCursor();
		
		if(isset($_SESSION['rang']) and $_SESSION['rang'] <= 1){
			$obj = $bdd->query("SELECT * FROM objets");
			$nbObjets = $obj->rowCount();
			$obj->closeCursor();
			echo'
			<div id="admin">
				<h2>Les objets</h2>
				<hr class="chaud">
				<a class="admin_lien" title="Creer un objet" href="creer_objet.php"><span class="case">Créer un objet</span></a><br>
				<hr>
				<a class="admin_lien" href=""><span class="case">Liste des objets</span></a>
				<hr>
				<a class="admin_lien" href=""><span class="case">Nombre d\'objets : '.$nbObjets.'</span></a>
			</div>
			<div id="admin">
				<h2>Le Forum</h2>
				<hr class="chaud">
				<a class="admin_lien" title="Structure du forum" href="structure.php"><span class="case">Modifier la structure</span></a>
			</div>
			<div id="admin">
				<h2>Membres</h2>
				<hr class="chaud">
				<a class="admin_lien" title="Modifier, supprimer ou donner un avertissement" href="liste_membres.php"><span class="case">Liste des membres</span></a><br>
				<hr>
				<span class="case">Nombre d\'inscrit : '.$nbMembres.'</span>
				<hr>
			</div>
			
			';
		}
	echo '</article>';

echo'</section>';

include("includes/bas.php");
?>

<script src="JS/admin.js"></script>