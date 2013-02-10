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
			/*TEST a faire ici !!*/
			$requete = $bdd->prepare("INSERT INTO lus_petitsuj (id_membre, id_petitsuj) VALUES(:id,:sujet)");
			$requete->execute(array('id'=>$_SESSION['id'], 'sujet'=>intval($_GET['id_petitsuj'])));
			$requete->closeCursor();
		}
		
		/************** Reponse ******************
		************ fils d'ariane ***************
		******************************************/
		
		$req = $bdd->prepare("SELECT * FROM forum_petitsujet FPS JOIN forum_petitcat FPC ON FPS.id_petitcat = FPC.id_petite JOIN forum_souscategories FS ON FS.id_souscategorie = FPC.id_souscat JOIN forum_categories FCE ON FCE.id_categorie = FS.id_categorie WHERE FPS.id_petitsuj =:cat");
		$req->execute(array('cat'=>$_GET['id_petitsuj'])) or die(print_r($requete->errorInfo()));	
		$ariane = $req->fetch();
		$req->closeCursor();
		
		echo'<p class="ariane">> <a href="index.php">Forum</a> > '.htmlspecialchars($ariane['titre_cat']).' > <a href="voir_sujet.php?id_souscat='.intval($ariane['id_souscat']).'">'.htmlspecialchars($ariane['titre_souscat']).'</a> > <a href="voir_petitsuj.php?id_petit='.$ariane['id_petite'].'">'.htmlspecialchars($ariane['titre_petite']).'</a>  > '.htmlspecialchars($ariane['titre_petitsuj']).'</p><br>';
		
		/*********** Reponse *****************
		************ Envoyer *****************
		**************************************/
		
		if(isset($_GET['reponseok']) and ($_GET['reponseok'] == 'ok')){
			echo '<p class="bon">Votre r√©ponse √† bien √©t√© post√© !</p>';
		}
		
		if (isset($_GET['reponse']) and empty($_GET['reponseok'])){
			echo '<p class="erreur">Vous avez post√© un message juste avant, √©dit√© plutot votre ancien message ou attendez 24h !</p>';
		}
		
		/**************** Requete **************
		************ supprimer reponse *********
		****************************************/
		
		if(isset($_GET['sup'])){
			supPetitReponse(intval($_GET['sup']));
		}
		
		/*********** Reponse *****************
		************ En-tete ! ***************
		**************************************/
		
		echo '<h1 class="titre_reponse">'.htmlspecialchars($ariane['titre_petitsuj']).'<br>'.htmlspecialchars($ariane['sousTitre_petitsuj']).'</h1>'; // titre
		if(isset($_SESSION['session'])){
			echo '<p><a href="ajout_petitrep.php?id_petitsuj='.intval($_GET['id_petitsuj']).'">R√©pondre</a> | <a href="ajout_petitsuj.php?id_petitcat='.$ariane['id_petite'].'">Nouveau sujet</a></p>';
		}
		
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
				
				$requete = $bdd->prepare("SELECT * FROM forum_petitsujet F JOIN membres M ON M.id_membre = F.id_membre JOIN maisons MA ON MA.id_maison = M.id_maison JOIN rang R ON r.id_rang = M.id_rang WHERE id_petitsuj=:id");
				$requete->execute(array('id'=>intval($_GET['id_petitsuj']))) or die(print_r($requete->errorInfo()));	
				$sujet = $requete->fetch();
				$requete->closeCursor();
				echo'<tr>
						<td class="lus_sujet">'.rang($sujet['place_rang'], $sujet['sexe']).'<br>'.htmlspecialchars($sujet['pseudo']).'<br><img width="135" src="'.$sujet['avatar'].'" alt="hey"><br>'.maison($sujet['id_maison'],$sujet['nom']).'</td>
						<td class="message_sujet"><div style="color: gray">'.temps($sujet['date_creation_petitsuj']);
						
						if(isset($_SESSION['session']) and (($_SESSION['id'] == $sujet['id_membre']) OR ($_SESSION['rang'] <= 2))){
							echo'<img style="float:right" src="images/editer.gif">';
						}
						if(isset($_SESSION['session']) and $_SESSION['rang'] <= 1){
							echo'<a class="sup" href="voir_petitsuj.php?id_petit='.$ariane['id_petite'].'&amp;suprSujet='.intval($_GET['id_petitsuj']).'"><img style="float:right" src="images/erreur.png"></a>';
						}
						echo'</div>'.nl2br(code($sujet['message_petitsuj'])).'</td>
					</tr>';
					
				/********* Algorithme **************
				********** Pagination ! ************
				************************************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_petitrep WHERE id_petitsuj = :id");
				$requete->execute(array('id'=>intval($_GET['id_petitsuj']))) or die(print_r($requete->errorInfo()));	
				$nbReponse = $requete->rowCount();
				$requete->closeCursor();
				
				$messageParPage = 19;
				$nombreDePage = ceil($nbReponse/$messageParPage);
				
				if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
				{
					 $pageActuelle=intval($_GET['page']);
					 
					 if($pageActuelle>$nombreDePage) // Si la valeur de $pageActuelle (le numÈro de la page) est plus grande que $nombreDePages...
					 {
						  $pageActuelle=$nombreDePage;
					 }
				}
				else // Sinon
				{
					 $pageActuelle=1; // La page actuelle est la n∞1    
				}

				$premiereEntree=($pageActuelle-1)*$messageParPage; // On calcul la premiËre entrÈe ‡ lire

				/*********** Requete ***************
				**** Affichage des reponses ! ******
				************************************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_petitrep F JOIN membres M ON M.id_membre = F.id_membre JOIN maisons MA ON MA.id_maison = M.id_maison JOIN rang R ON R.id_rang = M.id_rang WHERE id_petitsuj = :id ORDER BY date_creation_petitrep LIMIT ".$premiereEntree.", ".$messageParPage);
				
				$requete->execute(array('id'=>intval($_GET['id_petitsuj'])));
				
				/********** Affichage **************
				**** Affichage des reponses ! ******
				************************************/
				
				while($reponse = $requete->fetch()){
					
					echo'<tr>
						<td class="lus_sujet">'.rang($reponse['place_rang'],$reponse['sexe']).'<br>'.htmlspecialchars($reponse['pseudo']).'<br><img width="135" src="'.$reponse['avatar'].'" alt=":p">'.maison($reponse['id_maison'], $reponse['nom']).'</td>
						<td class="message_sujet"><div style="color: gray">'.temps($reponse['date_creation_petitrep']);
						
						if(isset($_SESSION['session']) and (($_SESSION['id'] == $reponse['id_membre']) OR ($_SESSION['rang'] <= 2))){
							echo'<img style="float:right" src="images/editer.gif">';
						}
						if(isset($_SESSION['session']) and $_SESSION['rang'] <= 1){
							echo'<a class="suppre" href="voir_petitrep.php?sup='.$reponse['id_petitrep'].'&amp;id_petitsuj='.$_GET['id_petitsuj'].'"><img style="float:right" src="images/erreur.png"></a>';
						}
						echo'</div>'.nl2br(code($reponse['message_petitrep'])).'</td>
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
								  echo '<div class="pagination"><a href="voir_petitrep.php?id_petitsuj='.$_GET['id_petitsuj'].'&amp;page='.$i.'">'.$i.'</a></div>';
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
