<?php

$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
		echo'<p class="ariane">> <a href="index.php">Forum</a> > Liste des membres</p><br>';
		echo '<h1>Liste des membres</h1>';
		echo '
			<table id="liste_membres">
				<tr>
					<th>Nom</th>
					<th>Age</th>
					<th>Rang</th>
					<th>Action</th>
				</tr>';
				$requete = $bdd->query("SELECT * FROM membres JOIN maisons ON maisons.id_maison = membres.id_maison JOIN rang R ON R.id_rang = membres.id_rang ORDER BY id_membre DESC");
				while($donnees = $requete->fetch()){
				if($donnees['place_rang'] == 0)
					$titre = htmlspecialchars(rang($donnees['place_rang'],$donnees['sexe']));
				else
					$titre = htmlspecialchars(rang($donnees['place_rang'],$donnees['sexe'])).' de '.maison($donnees['id_maison'], $donnees['nom']);
					echo'<tr>
						<td><a href="profil.php?id_pseudo='.$donnees['id_membre'].'">'.htmlspecialchars($donnees['pseudo']).'</a></td>
						<td class="age_liste">'.htmlspecialchars($donnees['age']).'</td>
						<td>'.$titre.'</td>
						<td class="action_liste"></td>
					</tr>';
				}
			echo'</table>
		';
		
		
	echo '</article>';

echo'</section>';

include("includes/bas.php");
?>

<script src="JS/admin.js"></script>