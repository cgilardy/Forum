<?php
$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
		$requete = $bdd->prepare("SELECT * FROM forum_souscategories FS JOIN forum_categories FC ON FS.id_categorie = FC.id_categorie WHERE FS.id_souscategorie = :cat");
		$requete->execute(array('cat'=>$_GET['id_souscat'])) or die ("erreur");
		$donnees = $requete->fetch();
		$requete->closeCursor();
		echo'<p class="ariane">> <a href="index.php">Forum</a> > '.$donnees['titre_cat'].'</p><br>';
		echo '<h1>'.$donnees['titre_souscat'].'</h1>'; // titre
		
		echo'
			<table id="index">
				<thead> 
				   <tr>
					   <th colspan="2">Sujets</th>
					   <th class="index_reponse">Réponses</th>
					   <th class="index_dernierMessage">Dernier message</th>
				   </tr>
			   </thead> 
			   <tfoot>
				   <tr>
					   <th colspan="2">Sujets</th>
					   <th>Réponses</th>
					   <th class="index_dernierMessage">Dernier message</th>
				   </tr>
			   </tfoot>
				<tbody>';
				
				/********* Algorithme **************
				********** Pagination ! ************
				************************************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_sujets WHERE id_souscat=:cat");
				$requete->execute(array('cat'=>$_GET['id_souscat'])) or die(print_r($requete->errorInfo()));	
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
				
				/********** Requete **************
				**** Affichage des sujets ! ******
				**********************************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_sujets WHERE id_souscat=:id ORDER BY date_creation_sujet DESC LIMIT ".$premiereEntree.", ".$messageParPage);
				$requete->execute(array('id'=>$_GET['id_souscat'])) or die(print_r($requete->errorInfo()));	
				
				while($sujet = $requete->fetch()){
				
					/************* Requete ****************
					************ Message lus **************
					***************************************/
					$lus = "Non lus";
					if(isset($_SESSION['session'])){	
						$message = $bdd->prepare("SELECT * FROM lus_sujets WHERE id_membre=:id_m and id_sujet=:id_sujet");
						$message->execute(array('id_m'=>$_SESSION['id'], 'id_sujet'=>$sujet['id_sujet'])) or die(print_r($requete->errorInfo()));	
						$messageLus = $message->rowCount();
						
						if($messageLus > 0)
							$lus = "Lus";
						else
							$lus = "Non lus";
					}
				
					/************* Requete ****************
					**** Nombre de reponse par sujet ******
					***************************************/
					
					$requete = $bdd->prepare("SELECT * FROM forum_reponses WHERE id_sujet=:id");
					$requete->execute(array('id'=>$sujet['id_sujet']));
					$nbReponse = $requete->rowCount();
					$requete->closeCursor();
					
					/************* Requete ****************
					********** Dernier message ************
					***************************************/
					
					$requete = $bdd->prepare("SELECT * FROM forum_reponses FR JOIN membres M ON M.id_membre = FR.id_membre WHERE id_sujet=:id ORDER BY date_creation_reponse DESC LIMIT 1");
					$requete->execute(array('id'=>$sujet['id_sujet']));
					$nbMessage = $requete->rowCount();
					$dernierMessage = $requete->fetch();
					$requete->closeCursor();
					$timestamp = $dernierMessage['date_creation_reponse'];
					if($nbMessage > 0)
						$date = 'Dernier message par '.$dernierMessage['pseudo'].'<br>le '.date('j/m/Y à G:i:s',$timestamp);
					else
						$date = "Aucun message";
					
					/********** Affichage **************
					**** Affichage des sujets ! ********
					************************************/
					
					echo'<tr>
						<td class="lus_sujet">'.$lus.'</td>
						<td class="titre_sujet"><a class="index_titre" href="voir_reponse.php?id_sujet='.$sujet['id_sujet'].'" title="Voir ce sujet">'.$sujet['titre_sujet'].'</a><br>'.$sujet['sousTitre_sujet'].'</td>
						<td class="text-center">'.$nbReponse.'</td>
						<td class="index_message">'.$date.'</td>
					</tr>';
				}
				
				if($nbSujet != 0){
					echo'
					<tr>
						<td colspan="4">';
						for($i=1; $i<=$nombreDePage; $i++) //On fait notre boucle
						{
							 //On va faire notre condition
							 if($i==$pageActuelle) //Si il s'agit de la page actuelle...
							 {
								 echo '<div class="pagination">'.$i.'</div> '; 
							 }	
							 else //Sinon...
							 {
								  echo '<div class="pagination"><a href="voir_sujet.php?id_souscat='.$_GET['id_souscat'].'&amp;page='.$i.'">'.$i.'</a></div>';
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
