<?php
$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
	
		/********** Lus/non-lus **************
		******** Affichage du sujet ! ********
		**************************************/
		if(isset($_SESSION['session'])){
			$requete = $bdd->prepare("INSERT INTO lus_sujets (id_membre, id_sujet) VALUES(:id,:sujet)");
			$requete->execute(array('id'=>$_SESSION['id'], 'sujet'=>$_GET['id_sujet']));
			$requete->closeCursor();
		}
		
		/************** Reponse ******************
		************ fils d'ariane ***************
		******************************************/
		
		$requete = $bdd->prepare("SELECT * FROM forum_sujets FSU JOIN forum_souscategories FSO ON FSU.id_souscat = FSO.id_souscategorie WHERE id_sujet =:cat");
		$requete->execute(array('cat'=>$_GET['id_sujet']));
		$donnees = $requete->fetch();
		
		echo'<p class="ariane">> <a href="index.php">Forum</a> > <a href="voir_sujet.php?id_souscat='.$donnees['id_souscat'].'">'.$donnees['titre_souscat'].'</a> > '.$donnees['titre_sujet'].'</p><br>';
		
		/*********** Reponse *****************
		************ Envoyer *****************
		**************************************/
		
		if(isset($_GET['reponseok']) and $_GET['reponseok'] == 'ok'){
			echo '<p class="bon">Votre réponse à bien été posté !</p>';
		}
		
		/*********** Requete *******************
		************ supprimer *****************
		****************************************/
		
		if(isset($_GET['sup'])){
			supReponse($_GET['sup']);
		}
		
		/*********** Reponse *****************
		************ En-tete ! ***************
		**************************************/
		
		echo '<h1 class="titre_reponse">'.$donnees['titre_sujet'].'<br>'.$donnees['sousTitre_sujet'].'</h1>'; // titre
		
		echo '<p><a href="ajout_reponse.php?id_sujet='.$_GET['id_sujet'].'">Répondre</a></p>';
		echo'
		
			<table id="index">
				<thead> 
				   <tr>
					   <th class="auteur_sujet">Auteur</th>
					   <th>Message</th>
				   </tr>
			   </thead> 
			   <tfoot>
				   <tr>
					   <th>Auteur</th>
					   <th>Message</th>
				   </tr>
			   </tfoot>
				<tbody>';
				
				/********** Requete **************
				**** Affichage du sujet ! ********
				**********************************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_sujets F JOIN membres M ON M.id_membre = F.id_membre JOIN maisons MA ON MA.id_maison = M.id_maison WHERE id_sujet=:id");
				$requete->execute(array('id'=>$_GET['id_sujet']));
				$sujet = $requete->fetch();
				$requete->closeCursor();
				echo'<tr>
						<td class="lus_sujet">'.rang($sujet['rang']).'<br>'.$sujet['pseudo'].'<br><img width="135" src="'.$sujet['avatar'].'" alt="hey"><br>'.maison($sujet['id_maison'],$sujet['nom']).'</td>
						<td class="message_sujet"><div style="color: gray">'.date('j/m/Y à G:i:s',$sujet['date_creation_sujet']);
						
						if(isset($_SESSION['session']) and (($_SESSION['id'] == $sujet['id_membre']) OR ($_SESSION['rang'] == 1) OR ($_SESSION['rang'] == 2))){
							echo'<img style="float:right" src="images/editer.gif">';
						}
						if(isset($_SESSION['session']) and $_SESSION['rang'] == 1){
							echo'<img style="float:right" src="images/erreur.png">';
						}
						echo'</div>'.$sujet['message_sujet'].'</td>
					</tr>';
					
				/********* Algorithme **************
				********** Pagination ! ************
				************************************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_reponses WHERE id_sujet = :id");
				$requete->execute(array('id'=>$_GET['id_sujet'])) or die(print_r($requete->errorInfo()));	
				$nbReponse = $requete->rowCount();
				$requete->closeCursor();
				
				$messageParPage = 19;
				$nombreDePage = ceil($nbReponse/$messageParPage);
				
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

				/*********** Requete ***************
				**** Affichage des reponses ! ******
				************************************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_reponses F JOIN membres M ON M.id_membre = F.id_membre JOIN maisons MA ON MA.id_maison = M.id_maison WHERE id_sujet = :id ORDER BY date_creation_reponse LIMIT ".$premiereEntree.", ".$messageParPage);
				
				$requete->execute(array('id'=>$_GET['id_sujet']));
				
				/********** Affichage **************
				**** Affichage des reponses ! ******
				************************************/
				
				while($reponse = $requete->fetch()){
					
					echo'<tr>
						<td class="lus_sujet">'.rang($reponse['rang']).'<br>'.$reponse['pseudo'].'<br><img width="135" src="'.$reponse['avatar'].'" alt=":p">'.maison($reponse['id_maison'], $reponse['nom']).'</td>
						<td class="message_sujet"><div style="color: gray">'.date('j/m/Y à G:i:s',$reponse['date_creation_reponse']);
						
						if(isset($_SESSION['session']) and (($_SESSION['id'] == $reponse['id_membre']) OR ($_SESSION['rang'] == 1) OR ($_SESSION['rang'] == 2))){
							echo'<img style="float:right" src="images/editer.gif">';
						}
						if(isset($_SESSION['session']) and $_SESSION['rang'] == 1){
							echo'<a class="suppre" href="voir_reponse.php?sup='.$reponse['id_reponse'].'&amp;id_sujet='.$_GET['id_sujet'].'"><img style="float:right" src="images/erreur.png"></a>';
						}
						echo'</div>'.$reponse['message_reponse'].'</td>
					</tr>';
				}
				if($nbReponse != 0){
					echo'
					<tr>
						<td colspan="2">';
						
						for($i=1; $i<=$nombreDePage; $i++) //On fait notre boucle
						{
							 //On va faire notre condition
							 if($i==$pageActuelle) //Si il s'agit de la page actuelle...
							 {
								 echo '<div class="pagination">'.$i.'</div> '; 
							 }	
							 else //Sinon...
							 {
								  echo '<div class="pagination"><a href="voir_reponse.php?id_sujet='.$_GET['id_sujet'].'&amp;page='.$i.'">'.$i.'</a></div>';
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
<script src="JS/reponse.js"></script>
