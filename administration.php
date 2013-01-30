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
		
		if(isset($_SESSION['rang']) and $_SESSION['rang'] == 1){
			echo'
			<div id="admin">
				<h2>Le Forum</h2>
				<hr class="chaud">
				<a class="admin_lien" title="Structure du forum" href="structure.php"><span class="case">Modifier la structure</span></a>
			</div>
			<div id="admin">
				<h2>Membres</h2>
				<hr class="chaud">
				<a class="admin_lien" title="Modifier, supprimer ou donner un avertissement" href=""><span class="case">Liste des membres</span></a><br>
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