<?php
$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
		echo'<p class="ariane">> <a href="index.php">Forum</a> > profil</p><br>';
		if(isset($_GET['id_pseudo']) and $_GET['id_pseudo'] == $_SESSION['id']){
			
			/************** Requete ***************
			* affichage des information du membre *
			***************************************/
			
			$requete = $bdd->prepare("SELECT * FROM membres JOIN maisons ON maisons.id_maison = membres.id_maison JOIN rang R ON r.id_rang = membres.id_rang WHERE id_membre=:id");
			$requete->execute(array('id'=>$_SESSION['id']));
			$profil = $requete->fetch();
			
			echo '<img class="profil_image" height="100" src="'.blason($profil['id_membre']).'" alt="maison">
				  <h1 class="titre_profil">'.$profil['pseudo'].'</h1><br>'; // titre
		
			$prenom = strstr($profil['pseudo'],' ',true);
			$nom = strstr($profil['pseudo'], ' ');
			if($nom == ''){
				$prenom = $profil['pseudo'];
				$nom = null;
			}
			if($profil['place_rang'] == 0)
				$titre = rang($profil['place_rang'],$profil['sexe']);
			else
				$titre = rang($profil['place_rang'],$profil['sexe']).' de '.maison($profil['id_maison'], $profil['nom']);
				
			echo '<div id="profil_info">
				<h2>Personnage</h2>
				<hr>
				<table>
				
					<tr>
						<td><img width="100" src="'.$profil['avatar'].'" alt="avatar"></td>
						<td>';
							if($nom != null){
								echo'<span class="case_profil_info">'.$prenom.' '.$nom.'</span><br>';
							}
							else
								echo'<span class="case_profil_info">'.$prenom.'</span><br>';
							echo'<span class="case_profil_info">'.$titre.'</span><br>';
							if(trim($profil['description']) != '')
								echo'<span class="case_profil_info">Description : <br>'.$profil['description'].'</span><br>';
							echo'<a href=""><span class="case_profil_info">'.code($profil['signature']).'</span></a><br>	
						</td>
					</tr>
				</table>
			</div>';	
			
			echo '<div id="profil">
				<h2>Informations</h2>
				<hr>';
					echo'<span class="case_profil">'.$profil['email'].'</span>
				<hr>';
				if(trim($profil['signature']) != '')
					echo'<span class="case_profil">Signature : <br>'.$profil['signature'].'</span><hr>';
			echo'</div>';	
			echo '<div id="profil">
				<h2>Inventaire</h2>
				<hr>
			</div>';	
		
		}
		//si ce n'est pas mon compte !
		else if(isset($_GET['id_pseudo']) and $_GET['id_pseudo'] != $_SESSION['id']){
			/************** Requete ***************
			* affichage des information du membre *
			***************************************/
			
			$requete = $bdd->prepare("SELECT * FROM membres JOIN maisons ON maisons.id_maison = membres.id_maison JOIN rang R ON R.id_rang = membres.id_rang WHERE id_membre=:id");
			$requete->execute(array('id'=>$_GET['id_pseudo']));
			$profil = $requete->fetch();
			
			echo '<img class="profil_image" height="100" src="'.blason($profil['id_membre']).'" alt="maison">
				  <h1 class="titre_profil">'.$profil['pseudo'].'</h1><br>'; // titre
		
			$prenom = strstr($profil['pseudo'],' ',true);
			$nom = strstr($profil['pseudo'], ' ');
			if($nom == ''){
				$prenom = $profil['pseudo'];
				$nom = null;
			}
			if($profil['place_rang'] == 0)
				$titre = rang($profil['place_rang'],$profil['sexe']);
			else
				$titre = rang($profil['place_rang'],$profil['sexe']).' de '.maison($profil['id_maison'], $profil['nom']);
				
			echo '<div id="profil_info">
				<h2>Personnage</h2>
				<hr>
				<table>
				
					<tr>
						<td><img width="100" src="'.$profil['avatar'].'" alt="avatar"></td>
						<td>';
							if($nom != null){
								echo'<span class="case_profil_info">'.$prenom.' '.$nom.'</span><br>';
							}
							else
								echo'<span class="case_profil_info">'.$prenom.'</span><br>';
							echo'<span class="case_profil_info">'.$titre.'</span><br>';
							if(trim($profil['description']) != '')
								echo'<span class="case_profil_info">Description : <br>'.$profil['description'].'</span><br>';
							echo'<a href=""><span class="case_profil_info">'.code($profil['signature']).'</span></a><br>	
						</td>
					</tr>
				</table>
			</div>';	
			
			echo '<div id="profil">
				<h2>Informations</h2>
				<hr>';
				echo'
				<a class="admin_lien" href=""><span class="case_profil">Envoyer un hibou</span></a>
				<hr>';
				if(trim($profil['signature']) != '')
					echo'<span class="case_profil">Signature : <br>'.$profil['signature'].'</span><hr>';
			echo'</div>';	
			echo '<div id="profil">
				<h2>Inventaire</h2>
				<hr>
			</div>';	
		}
		else{
			echo '<p class="erreur">Vous n\'êtes pas inscrit !</p>';
		}
	echo '</article>';

echo'</section>';

include("includes/bas.php");
?>
<script src="JS/profil.js"></script>