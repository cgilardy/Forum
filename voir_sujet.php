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
		echo'<p class="ariane">> <a href="index.php">Forum</a> > '.htmlspecialchars($donnees['titre_cat']).'</p><br>';
		echo '<h1>'.$donnees['titre_souscat'].'<br>'.$donnees['sousTitre_souscat'].'</h1>';
		
		
		/************* Requete ************
		******** Suppression du sujets*****
		***********************************/
		
		if(isset($_GET['suprSujet'])){
			supSujet(intval($_GET['suprSujet']));
			echo '<p class="bon">Le sujet a bien été supprimé !</p>';
		}
		if(isset($_SESSION['session']))
			echo'<p><a href="ajout_sujet.php?id_souscat='.$_GET['id_souscat'].'">Nouveau sujet</a></p>';
		echo'
			<table id="index">
				<thead> 
				   <tr>
					   <th colspan="2">Titre</th>
					   <th class="index_sujet">Sujets</th>
					   <th class="index_reponse">Réponses</th>
					   <th class="index_dernierMessage">Dernier message</th>
				   </tr>
			   </thead> 
			   <tfoot>
				   <tr>
					   <th colspan="2">Titre</th>
					   <th>Sujets</th>
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
				
				/**************** Requete *****************
				************ Affichage des lieux ! ********
				********* que si on est pas déjà dedans ***/
				
				$lie = $bdd->prepare("SELECT * FROM forum_petitcat WHERE id_souscat=:id");
				$lie->execute(array('id'=>$_GET['id_souscat']));
				
				while($lieux = $lie->fetch()){
				
				/*************** Requete **************
				************ Dernier message **********
				********** Par sous-catégories ********/
				
				$dernier = $bdd->prepare("SELECT * FROM forum_petitsujet F JOIN forum_petitcat FP ON F.id_petitcat = FP.id_petite JOIN membres FM ON FM.id_membre = F.id_membre WHERE FP.id_petite = :id ORDER BY date_creation_petitsuj DESC LIMIT 1");
				$dernier->execute(array('id'=>intval($lieux['id_petite']))) or die(print_r($dernier->errorInfo()));
				$nbMessage = $dernier->rowCount();
				$dernierMessage = $dernier->fetch();
				$timestamp = $dernierMessage['date_creation_petitsuj'];
				
				if($nbMessage > 0)
					$date = 'Dernier message par '.htmlspecialchars($dernierMessage['pseudo']).'<br>'.temps($timestamp);
				else
					$date = "Aucun message";
					
				/*************** Requete **************
				*********** Compte des sujets *********
				********** Par sous-catégories ********/
				
				$sujet_sous = $bdd->prepare("SELECT FS.id_petite FROM forum_petitsujet F JOIN forum_petitcat FS ON FS.id_petite = F.id_petitcat WHERE FS.id_petite=:cat");
				$sujet_sous->execute(array('cat'=>intval($lieux['id_petite']))) or die(print_r($sujet_sous->errorInfo()));
				$nbSujet_sous = $sujet_sous->rowCount();
				
				/*************** Requete **************
				*********** Compte des sujets *********
				********** Par sous-catégories ********/
				
				$reponse_sous = $bdd->prepare("SELECT * FROM forum_petitrep FPR JOIN forum_petitsujet FSU ON FSU.id_petitsuj = FPR.id_petitsuj JOIN forum_petitcat FSO ON FSO.id_petite = FSU.id_petitcat WHERE FSO.id_petite=:cat");
				$reponse_sous->execute(array('cat'=>intval($lieux['id_petite']))) or die(print_r($reponse_sous->errorInfo()));	
				$nbReponse = $reponse_sous->rowCount();
				
				/**************** Affichage ***************
				************ Affichage des lieux ! ********
				********* que si on est pas déjà dedans ***/
				
				echo'<tr>
						<td colspan="2" class="titre_sujet"><a class="index_titre" href="voir_petitsuj.php?id_petit='.$lieux['id_petite'].'" title="Entrer dans '.$lieux['titre_petite'].'">'.htmlspecialchars($lieux['titre_petite']).'</a><br><span class="index_sousTitre">'.htmlspecialchars($lieux['sousTitre_petite']).'</span></td>
						<td class="text-center">'.$nbSujet_sous.'</td>
						<td class="text-center">'.$nbReponse.'</td>
						<td class="index_message">'.$date.'</td>
					</tr>';
				
				}
				//séparation des petites categories
				echo '<tr style="height: 20px;"><td style="background-color: black;" colspan="5"></td></tr>';
				/********** Requete ****************
				**** Affichage des annonces ! ******
				************************************/
				$ann = $bdd->prepare("SELECT * FROM forum_sujets WHERE id_souscat=:id and annonce_sujet =:annonce ORDER BY date_derniere_reponse");
				$ann->execute(array('id'=>$_GET['id_souscat'], 'annonce'=>1)) or die(print_r($ann->errorInfo()));	
				$nbAnnonce = $ann->rowCount();
				
				while($annonce = $ann->fetch()){
				
					/************* Requete ****************
					************ Message lus **************
					***************************************/
					$lus = "Non lus";
					if(isset($_SESSION['session'])){	
						$message = $bdd->prepare("SELECT * FROM lus_sujets WHERE id_membre=:id_m and id_sujet=:id_sujet");
						$message->execute(array('id_m'=>$_SESSION['id'], 'id_sujet'=>$annonce['id_sujet'])) or die(print_r($message->errorInfo()));	
						$messageLus = $message->rowCount();
						
						if($messageLus > 0)
							$lus = "Lus";
						else
							$lus = "Non lus";
					}
				
					/************* Requete ****************
					**** Nombre de reponse par sujet ******
					************* annonce *************/
					
					$requete = $bdd->prepare("SELECT * FROM forum_reponses WHERE id_sujet=:id");
					$requete->execute(array('id'=>$annonce['id_sujet']));
					$nbReponse = $requete->rowCount();
					$requete->closeCursor();
					
					/************* Requete ****************
					********** Dernier message ************
					************** annonce *****************/
					
					$requete = $bdd->prepare("SELECT * FROM forum_reponses FR JOIN membres M ON M.id_membre = FR.id_membre WHERE id_sujet=:id ORDER BY date_creation_reponse DESC LIMIT 1");
					$requete->execute(array('id'=>$annonce['id_sujet']));
					$nbMessage = $requete->rowCount();
					$dernierMessage = $requete->fetch();
					$requete->closeCursor();
					$timestamp = $dernierMessage['date_creation_reponse'];
					if($nbMessage > 0)
						$date = 'Dernier message par '.htmlspecialchars($dernierMessage['pseudo']).'<br>'.temps($timestamp);
					else
						$date = "Aucun message";
					
					/********** Affichage **************
					****** Affichage des sujets ! ******
					************* annonce **************/
					
					echo'<tr>
						<td class="lus_sujet">'.$lus.'</td>
						<td style="border-right:none;" class="titre_sujet"><a class="index_titre" href="voir_reponse.php?id_sujet='.$annonce['id_sujet'].'" title="Voir ce sujet">'.htmlspecialchars($annonce['titre_sujet']).'</a><br>'.htmlspecialchars($annonce['sousTitre_sujet']).'</td>
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
				
				$requete1 = $bdd->prepare("SELECT * FROM forum_sujets WHERE id_souscat=:id and annonce_sujet = :annonce ORDER BY date_derniere_reponse DESC LIMIT ".$premiereEntree.", ".$messageParPage);
				$requete1->execute(array('id'=>$_GET['id_souscat'],'annonce'=>0 )) or die(print_r($requete->errorInfo()));	
				
				while($sujet = $requete1->fetch()){
				
					/************* Requete ****************
					************ Message lus **************
					***************************************/
					$lus = "Non lus";
					if(isset($_SESSION['session'])){	
						$message = $bdd->prepare("SELECT * FROM lus_sujets WHERE id_membre=:id_m and id_sujet=:id_sujet");
						$message->execute(array('id_m'=>$_SESSION['id'], 'id_sujet'=>$sujet['id_sujet'])) or die(print_r($message->errorInfo()));	
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
						$date = 'Dernier message par '.htmlspecialchars($dernierMessage['pseudo']).'<br>'.temps($timestamp);
					else
						$date = "Aucun message";
					
					/********** Affichage **************
					**** Affichage des sujets ! ********
					************************************/
					
					echo'<tr>
						<td class="lus_sujet">'.$lus.'</td>
						<td style="border-right: none;" class="titre_sujet"><a class="index_titre" href="voir_reponse.php?id_sujet='.$sujet['id_sujet'].'" title="Voir ce sujet">'.htmlspecialchars($sujet['titre_sujet']).'</a><br>'.htmlspecialchars($sujet['sousTitre_sujet']).'</td>
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
