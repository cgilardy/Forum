<?php
$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
		echo'<p class="ariane">> <a href="index.php">Forum</a> > Accueil</p><br>';
		echo '<h1 class="titre">Forum RPG</h1>'; // titre
		
		include("includes/erreur.php");
			
		echo'
			<table id="index">
				<thead> 
				   <tr>
					   <th colspan="2">Catégorie</th>
					   <th class="index_sujet">Sujets</th>
					   <th class="index_reponse">Réponses</th>
					   <th class="index_dernierMessage">Dernier message</th>
				   </tr>
			   </thead> 
			   <tfoot>
				   <tr>
					   <th colspan="2">Catégorie</th>
					   <th>Sujets</th>
					   <th>Réponses</th>
					   <th class="index_dernierMessage">Dernier message</th>
				   </tr>
			   </tfoot>
				<tbody>';
				
				/********** Requete **************
				**** Affichage des catégories ! **
				**********************************/
				
				$categories = $bdd->query("SELECT * FROM forum_categories");
				while($categoriesOk = $categories->fetch()){
					
					/*********************************
					******** Compte des sujets *******
					********** Par catégories ********/
					
					$sujet = $bdd->prepare("SELECT FC.id_categorie FROM forum_sujets FSU LEFT JOIN forum_souscategories FSO ON FSO.id_souscategorie = FSU.id_souscat JOIN forum_categories FC ON FC.id_categorie = FSO.id_categorie WHERE FC.id_categorie=:cat");
					$sujet->execute(array('cat'=>$categoriesOk['id_categorie'])) or die(print_r($sujet->errorInfo()));
					$nbSujet = $sujet->rowCount();					
					
					/************ Requete ************
					******** Compte des réponses *****
					********** Par catégories ********/
					
					$reponse = $bdd->prepare("SELECT * FROM forum_reponses FR LEFT JOIN forum_sujets FSU ON FSU.id_sujet = FR.id_sujet LEFT JOIN forum_souscategories FSO ON FSO.id_souscategorie = FSU.id_souscat JOIN forum_categories FC ON FC.id_categorie = FSO.id_categorie WHERE FC.id_categorie=:cat");
					$reponse->execute(array('cat'=>$categoriesOk['id_categorie'])) or die(print_r($reponse->errorInfo()));	
					$nbReponse = $reponse->rowCount();
					
					/********** Affichage **************
					**** Affichage des catégories ! **
					**********************************/
					
					echo'<tr>
						<td colspan ="2">'.$categoriesOk['titre_cat'].'</td>
						<td class="text-center">'.$nbSujet.'</td>
						<td class="text-center">'.$nbReponse.'</td>
						<td style="border:none;"></td>
					</tr>';
					
					/************** Requete *******************
					**** Affichage des sous-catégories ! ******
					*******************************************/
					
					$souscat = $bdd->prepare("SELECT * FROM forum_souscategories WHERE id_categorie = :cat ORDER BY place");
					$souscat->execute(array('cat'=>$categoriesOk['id_categorie']));
					
						while($souscatsOk = $souscat->fetch()){
						
							/*************** Requete **************
							************ Dernier message **********
							********** Par sous-catégories ********/
							
							$dernier = $bdd->prepare("SELECT * FROM forum_sujets FSU JOIN forum_souscategories FSO ON FSO.id_souscategorie = FSU.id_souscat JOIN membres FM ON FM.id_membre = FSU.id_membre WHERE FSO.id_souscategorie = :id ORDER BY date_creation_sujet DESC LIMIT 1");
							$dernier->execute(array('id'=>$souscatsOk['id_souscategorie'])) or die(print_r($dernier->errorInfo()));
							$nbMessage = $dernier->rowCount();
							$dernierMessage = $dernier->fetch();
							$timestamp = $dernierMessage['date_creation_sujet'];
							
							if($nbMessage > 0)
								$date = 'Dernier message par '.$dernierMessage['pseudo'].'<br>le '.date('j/m/Y à G:i:s',$timestamp);
							else
								$date = "Aucun message";
								
							/*************** Requete **************
							*********** Compte des sujets *********
							********** Par sous-catégories ********/
							
							$sujet_sous = $bdd->prepare("SELECT FSO.id_souscategorie FROM forum_sujets FSU JOIN forum_souscategories FSO ON FSO.id_souscategorie = FSU.id_souscat WHERE FSO.id_souscategorie=:cat");
							$sujet_sous->execute(array('cat'=>$souscatsOk['id_souscategorie'])) or die(print_r($sujet_sous->errorInfo()));
							$nbSujet_sous = $sujet_sous->rowCount();
							
							/*************** Requete **************
							*********** Compte des sujets *********
							********** Par sous-catégories ********/
							
							$reponse_sous = $bdd->prepare("SELECT * FROM forum_reponses FR LEFT JOIN forum_sujets FSU ON FSU.id_sujet = FR.id_sujet LEFT JOIN forum_souscategories FSO ON FSO.id_souscategorie = FSU.id_souscat WHERE FSO.id_souscategorie=:cat");
							$reponse_sous->execute(array('cat'=>$souscatsOk['id_souscategorie'])) or die(print_r($reponse_sous->errorInfo()));	
							$nbReponse = $reponse_sous->rowCount();

							/************** Affichage *****************
							**** Affichage des sous-catégories ! ******
							*******************************************/
								
							echo'<tr>
								<td colspan ="2"><a class="index_titre" href="voir_sujet.php?id_souscat='.$souscatsOk['id_souscategorie'].'">'.$souscatsOk['titre_souscat'].'</a><br><span class="index_sousTitre">'.$souscatsOk['sousTitre_souscat'].'</span></td>
								<td class="text-center">'.$nbSujet_sous.'</td>
								<td class="text-center">'.$nbReponse.'</td>
								<td class="index_message">'.$date.'</td>
							</tr>';
						}
					}
					
					//fermeture de la base de donnée pour chaque requete !
					$categories->closeCursor();
					$sujet->closeCursor();
					$reponse->closeCursor();
					
				echo'</tbody>
			</table>
		';
		if(isset($_SESSION['rang']) and $_SESSION['rang'] == 1)
			echo'<p class="text_center"><a href="administration.php">Administration</a></p>';
	echo '</article>';

echo'</section>';

include("includes/bas.php");
?>