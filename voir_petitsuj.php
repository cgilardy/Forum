<?php
$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
		$requete = $bdd->prepare("SELECT * FROM forum_petitcat FS JOIN forum_souscategories FC ON FS.id_souscat= FC.id_souscategorie JOIN forum_categories F ON F.id_categorie = FC.id_categorie WHERE FS.id_petite = :cat");
		$requete->execute(array('cat'=>$_GET['id_petit'])) or die ("erreur");
		$donnees = $requete->fetch();
		$requete->closeCursor();
		echo'<p class="ariane">> <a href="index.php">Forum</a> > '.htmlspecialchars($donnees['titre_cat']).' > <a href="voir_sujet.php?id_souscat='.$donnees['id_souscat'].'">'.htmlspecialchars($donnees['titre_souscat']).'</a> > '.htmlspecialchars($donnees['titre_petite']).'</p><br>';
		echo '<h1>'.$donnees['titre_petite'].'<br>'.$donnees['sousTitre_petite'].'</h1>';
		
		
		/************* Requete ************
		******** Suppression du sujets*****
		***********************************/
		
		if(isset($_GET['suprSujet'])){
			supPetitSujet(intval($_GET['suprSujet']));
			echo '<p class="bon">Le sujet a bien été supprimé !</p>';
		}
		if(isset($_SESSION['session']))
			echo'<p><a href="ajout_petitsuj.php?id_petitcat='.$_GET['id_petit'].'">Nouveau sujet</a></p>';
			
		echo'
			<table id="index">
				<thead> 
				   <tr>
					   <th colspan="3">Titre</th>
					   <th class="index_reponse">Réponses</th>
					   <th class="index_dernierMessage">Dernier message</th>
				   </tr>
			   </thead> 
			   <tfoot>
				   <tr>
					   <th colspan="3">Titre</th>
					   <th>Réponses</th>
					   <th class="index_dernierMessage">Dernier message</th>
				   </tr>
			   </tfoot>
				<tbody>';
				
				/********* Algorithme **************
				********** Pagination ! ************
				************************************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_petitsujet WHERE id_petitcat=:cat");
				$requete->execute(array('cat'=>$donnees['id_petite'])) or die(print_r($requete->errorInfo()));	
				$nbSujet = $requete->rowCount();
				$requete->closeCursor();
				$messageParPage = 20;
				$nombreDePage = ceil($nbSujet/$messageParPage);
				
				if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
				{
					 $pageActuelle=intval($_GET['page']);
					 
					 if($pageActuelle>$nombreDePage) // Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $nombreDePages...
					 {
						  $pageActuelle=$nombreDePage;
					 }
				}
				else // Sinon
				{
					 $pageActuelle=1; // La page actuelle est la n°1    
				}

				$premiereEntree=($pageActuelle-1)*$messageParPage; // On calcul la première entrée à lire
				
				/********** Requete ****************
				**** Affichage des annonces ! ******
				************************************/
				
				$ann = $bdd->prepare("SELECT * FROM forum_petitsujet WHERE id_petitcat=:id and annonce_petitsuj =:annonce ORDER BY date_derniere_reponse_petitsuj");
				$ann->execute(array('id'=>$donnees['id_petite'], 'annonce'=>1)) or die(print_r($ann->errorInfo()));	
				$nbAnnonce = $ann->rowCount();
				
				while($annonce = $ann->fetch()){
				
					/************* Requete ****************
					************ Message lus **************
					***************************************/
					$lus = "Non lus";
					if(isset($_SESSION['session'])){	
						$message = $bdd->prepare("SELECT * FROM lus_petitsuj WHERE id_membre=:id_m and id_petitsuj=:id_sujet");
						$message->execute(array('id_m'=>$_SESSION['id'], 'id_sujet'=>$annonce['id_petitsuj'])) or die(print_r($message->errorInfo()));	
						$messageLus = $message->rowCount();
						
						if($messageLus > 0)
							$lus = "Lus";
						else
							$lus = "Non lus";
					}
				
					/************* Requete ****************
					**** Nombre de reponse par sujet ******
					************* annonce *************/
					
					$requete = $bdd->prepare("SELECT * FROM forum_petitrep WHERE id_petitsuj=:id");
					$requete->execute(array('id'=>$annonce['id_petitsuj']));
					$nbReponse = $requete->rowCount();
					$requete->closeCursor();
					
					/************* Requete ****************
					********** Dernier message ************
					************** annonce *****************/
					
					$requete = $bdd->prepare("SELECT * FROM forum_petitrep FR JOIN membres M ON M.id_membre = FR.id_membre WHERE id_petitsuj=:id ORDER BY date_creation_reponse_petitsuj DESC LIMIT 1");
					$requete->execute(array('id'=>$annonce['id_petitsuj']));
					$nbMessage = $requete->rowCount();
					$dernierMessage = $requete->fetch();
					$requete->closeCursor();
					$timestamp = $dernierMessage['date_creation_petitrep'];
					if($nbMessage > 0)
						$date = 'Dernier message par '.htmlspecialchars($dernierMessage['pseudo']).'<br>'.temps($timestamp);
					else
						$date = "Aucun message";
					
					/********** Affichage **************
					****** Affichage des sujets ! ******
					************* annonce **************/
					
					echo'<tr>
						<td class="lus_sujet">'.$lus.'</td>
						<td style="border-right:none;" class="titre_sujet"><a class="index_titre" href="voir_reponse.php?id_sujet='.$annonce['id_petitsuj'].'" title="Voir ce sujet">'.htmlspecialchars($annonce['titre_petitsuj']).'</a><br>'.htmlspecialchars($annonce['sousTitre_petitsuj']).'</td>
						<td style="border:none;"></td>
						<td class="text-center">'.$nbReponse.'</td>
						<td class="index_message">'.$date.'</td>
					</tr>';
				}
				$ann->closeCursor();
				
				/********** Requete **************
				**** Affichage des sujets ! ******
				**********************************/
				
				if($nbAnnonce != 0)
					echo '<tr style="height:20px;"><td colspan="5"></td></tr>';
				
				$requete1 = $bdd->prepare("SELECT * FROM forum_petitsujet WHERE id_petitcat=:id and annonce_petitsuj = :annonce ORDER BY date_derniere_reponse_petitsuj DESC LIMIT ".$premiereEntree.", ".$messageParPage);
				$requete1->execute(array('id'=>$_GET['id_petit'],'annonce'=>0 )) or die(print_r($requete->errorInfo()));	
				
				while($sujet = $requete1->fetch()){
				
					/************* Requete ****************
					************ Message lus **************
					***************************************/
					$lus = "Non lus";
					if(isset($_SESSION['session'])){	
						$message = $bdd->prepare("SELECT * FROM lus_petitsuj WHERE id_membre=:id_m and id_petitsuj=:id_sujet");
						$message->execute(array('id_m'=>$_SESSION['id'], 'id_sujet'=>$sujet['id_petitsuj'])) or die(print_r($message->errorInfo()));	
						$messageLus = $message->rowCount();
						
						if($messageLus > 0)
							$lus = "Lus";
						else
							$lus = "Non lus";
					}
				
					/************* Requete ****************
					**** Nombre de reponse par sujet ******
					***************************************/
					
					$requete = $bdd->prepare("SELECT * FROM forum_petitrep WHERE id_petitsuj=:id");
					$requete->execute(array('id'=>$sujet['id_petitsuj']));
					$nbReponse = $requete->rowCount();
					$requete->closeCursor();
					
					/************* Requete ****************
					********** Dernier message ************
					***************************************/
					
					$requete = $bdd->prepare("SELECT * FROM forum_petitrep FR JOIN membres M ON M.id_membre = FR.id_membre WHERE id_petitsuj=:id ORDER BY date_creation_petitrep DESC LIMIT 1");
					$requete->execute(array('id'=>$sujet['id_petitsuj']));
					$nbMessage = $requete->rowCount();
					$dernierMessage = $requete->fetch();
					$requete->closeCursor();
					$timestamp = $dernierMessage['date_creation_petitrep'];
					if($nbMessage > 0)
						$date = 'Dernier message par '.htmlspecialchars($dernierMessage['pseudo']).'<br>'.temps($timestamp);
					else
						$date = "Aucun message";
					
					/********** Affichage **************
					**** Affichage des sujets ! ********
					************************************/
					
					echo'<tr>
						<td class="lus_sujet">'.$lus.'</td>
						<td style="border-right: none;" class="titre_sujet"><a class="index_titre" href="voir_petitrep.php?id_petitsuj='.$sujet['id_petitsuj'].'" title="Voir ce sujet">'.htmlspecialchars($sujet['titre_petitsuj']).'</a><br>'.htmlspecialchars($sujet['sousTitre_petitsuj']).'</td>
						<td style="border:none;"></td>
						<td class="text-center">'.$nbReponse.'</td>
						<td class="index_message">'.$date.'</td>
					</tr>';
				}
				
				if($nbSujet != 0){
					echo'
					<tr>
						<td colspan="5">';
						for($i=1; $i<=$nombreDePage; $i++) //On fait notre boucle
						{
							 //On va faire notre condition
							 if($i==$pageActuelle) //Si il s'agit de la page actuelle...
							 {
								 echo '<div class="pagination">'.$i.'</div> '; 
							 }	
							 else //Sinon...
							 {
								  echo '<div class="pagination"><a href="voir_petitsuj.php?id_petit='.$donnees['id_petite'].'&amp;page='.$i.'">'.$i.'</a></div>';
							 }
						}
						echo'</td>
					</tr>';
				}
				echo'</tbody>
			</table>
		';
		
	echo '</article>';

echo'</section>';

include("includes/bas.php");
?>
<script src="JS/sujet.js"></script>
