<?php

$titre_page = 'Structure';
include("includes/haut.php");

echo '
<section>
	<article>';
		
		if(isset($_SESSION['rang']) and $_SESSION['rang'] <= 1){
			echo'<p class="ariane">> <a href="index.php">Forum</a> > <a href="administration.php">Administration</a> > Structure du forum</p><br>';
			echo '<h1>Structure du forum</h1>';
			echo'<a class="ajouter" href="structure.php?ajoutCategorie=ok">Ajouter une catégorie</a> | <a href="structure.php?ajoutSousCat=ok">Ajouter une sous-catégorie</a><br><br>';
			/*********************************
			***** Monter une sous-catégorie **
			**********************************/
			if(isset($_GET['monter'])){
			
				/************ Requete *****************
				***** Selection de la sous-categorie **
				****** et de sa categorie associée ****/
				
				$requete = $bdd->prepare("SELECT * FROM forum_souscategories FS JOIN forum_categories FC ON FS.id_categorie = FC.id_categorie WHERE FS.id_souscategorie = :souscat ");
				$requete->execute(array('souscat'=>intval($_GET['monter'])));
				$donnees = $requete->fetch();
				$requete->closeCursor();
				
				/************ Requete *****************
				***** Selection de la plus petite *****
				************* categorie ***************/
				
				$minCat = $bdd->query("SELECT MIN(id_categorie) as nb FROM forum_categories");
				$min = $minCat->fetch();
				
				//Si la sous categorie est dans la premiere categorie et qu'elle est à la place 1, on ne la bouge pas
				if(($donnees['place'] == 1) and ($donnees['id_categorie'] == $min['nb'])){
					echo '<p class="erreur">Vous ne pouvez pas monter la sous-catégorie <em>'.htmlspecialchars($donnees['titre_souscat']).'</em> !</p>';
				}
				// si sa place est différente de 1 c'est que l'on peut la bouger
				else if(($donnees['place'] != 1)){
				
					// numero de la place actuelle
					$place = $donnees['place'];
					//nombre de sous-categorie portant la place actuelle moins 1
					$nbSousCat = 0;
					
					//tant qu'il n'y à pas de sous-categorie avec une place au dessus on continue à chercher !
					while($nbSousCat == 0){
						$place--;
						/*********************** Requete ***************************
						************* Séléction de la sous-catégorie ***************
						****** positionné juste avant celle qu'on veut modifier ****/
						
						$requete1 = $bdd->prepare("SELECT * FROM forum_souscategories WHERE id_categorie = :id and place=:place");
						$requete1->execute(array('id'=>$donnees['id_categorie'], 'place'=>$place)) or die ("erreur");
						$nbSousCat = $requete1->rowCount();
					}
					//dès qu'on a trouvé le numero de la sous-categorie on fetch()
					$reponse = $requete1->fetch();
					//on close
					$requete1->closeCursor();
					//on stock les places dans des variables, c'est plus maniable
					$derniere = $donnees['place'];
					$premiere = $reponse['place'];
					
					/***************************** Requete ********************************
					************* On inverse les places des sous-categories ***************
					******************* en mettant à jour la table ************************/
					
					$req = $bdd->prepare("UPDATE forum_souscategories SET place= :place WHERE id_souscategorie=:id");
					$req->execute(array('place'=>$premiere, 'id'=>$donnees['id_souscategorie']));
					
					$req2 = $bdd->prepare("UPDATE forum_souscategories SET place = :place WHERE id_souscategorie = :id");
					$req2->execute(array('place'=>$derniere, 'id'=>$reponse['id_souscategorie']));
					
					//on affiche le bon déroulement de l'operation !
					echo '<p class="bon">La sous-catégorie <em>'.htmlspecialchars($donnees['titre_souscat']).'</em> est bien monté !</p>';
				}
				//changement de categorie !
				else if(($donnees['place'] == 1) and ($donnees['id_categorie'] != $min['nb'])){
					//numero de la nouvelle categorie
					$numCategorie = 0;
					
					//numero de l'actuelle categorie
					$idCat = $donnees['id_categorie'];
					$idCatConst = $donnees['id_categorie'];
					/**************** Requete ***************
					***** trouver la categorie précédente ***
					*****************************************/
					
					while($numCategorie == 0){
						$idCat--;
						$requete = $bdd->prepare("SELECT * FROM forum_categories WHERE id_categorie =:id");
						$requete->execute(array('id'=>$idCat));
						$numCategorie = $requete->rowCount();
					}
					
					//on fetch après la boucle (toujours)
					$donnees2 = $requete->fetch();
					
					//on close après le fetch quand celui-ci n'est pas dans une boucle
					$requete->closeCursor();
					
					//on mets la categorie trouver dans une variable !
					$id_cat = $donnees2['id_categorie'];
					
					/************************** Requete **************************
					***** On selectionne la plus grande place dans la nouvelle ***
					****** categorie et on lui ajoute 1 pour l'attribué à la *****
					*********************** nouvelle sous cat ********************
					**************************************************************/
					
					$reponse = $bdd->prepare("SELECT MAX(place) as numPlace FROM forum_souscategories WHERE id_categorie = :id");
					$reponse->execute(array('id'=>$id_cat));
					$donnees1 = $reponse->fetch();
					$place = $donnees1['numPlace'] +1 ;
					
					//On mets à jour la sous-categories
					
					$requete = $bdd->prepare("UPDATE forum_souscategories SET id_categorie = :id, place = :place WHERE id_souscategorie = :souscat");
					$requete->execute(array('id'=>$id_cat, 'place'=>$place, 'souscat'=>intval($_GET['monter']))) or die ("erreur");
					
					/************************** Requete **************************
					***** On selectionne la plus grande place dans l'ancienne ****
					****** categorie et on retire 1 a toute les sous-categories **
					**************************************************************/
					
					$reponse = $bdd->prepare("SELECT MAX(place) as numPlace FROM forum_souscategories WHERE id_categorie = :id");
					$reponse->execute(array('id'=>intval($idCatConst))) or die ("erreur1");
					$donnees1 = $reponse->fetch();
					$place1 = $donnees1['numPlace'];
					
					/*on mets à jour toute les autres sous-categories*/
					
					//on selectionne les sous-categories de l'acienne categorie
					$r = $bdd->prepare("SELECT * FROM forum_souscategories WHERE id_categorie = :id");
					$r->execute(array('id'=>intval($idCatConst)));
					//on les comptes
					$nbSousCat = $r->rowCount();
					//si il y en a dedans on modifie leur place qui n'est surement plus la bonne
					if($nbSousCat != 0){
						$r2 = $bdd->prepare("SELECT * FROM forum_souscategories WHERE id_categorie = :id");
						$r2->execute(array('id'=>$idCatConst));
						$placeNew = $place1;
						while($reponse1 = $r2->fetch()){
							$placeNew--;
							$req = $bdd->prepare("UPDATE forum_souscategories SET place=:place WHERE id_souscategorie = :id");
							$req->execute(array('place'=>$placeNew, 'id'=>$reponse1['id_souscategorie']));
						}
					}
					
					//on informe que tout c'est bien passé !
					echo '<p class="bon">Le changement de catégorie c\'est bien déroulé !</p>';
				}
			
			}
			
			/************************************
			***** Descendre une sous-categorie **
			*************************************/
			
			if(isset($_GET['descendre'])){
				/************ Requete *****************
				***** Selection de la sous-categorie **
				****** et de sa categorie associée ****/
				
				$requete = $bdd->prepare("SELECT * FROM forum_souscategories FS JOIN forum_categories FC ON FS.id_categorie = FC.id_categorie WHERE FS.id_souscategorie = :souscat ");
				$requete->execute(array('souscat'=>intval($_GET['descendre'])));
				$donnees = $requete->fetch();
				$requete->closeCursor();
				
				//place maximal de la sous-categorie
				$placeMax = $bdd->prepare("SELECT MAX(place) as placeMax FROM forum_souscategories WHERE id_categorie = :id");
				$placeMax->execute(array('id'=>$donnees['id_categorie'])) or die ("erreur");
				$max = $placeMax->fetch();
				$placeMax->closeCursor();
				
				//categorie MAX
				$catMax = $bdd->query("SELECT MAX(id_categorie) as catMax FROM forum_categories");				
				$maxCat = $catMax->fetch();
				
				//Si la sous categorie est dans la derniere categorie et qu'elle est à la derniere place, on ne la bouge pas
				if(($donnees['place'] == $max['placeMax']) and ($donnees['id_categorie'] == $maxCat['catMax'])){
					echo '<p class="erreur">Vous ne pouvez pas descendre cette categorie !</p>';
				}
				// si sa place est différente de 1 c'est que l'on peut la bouger
				else if(($donnees['place'] != $max['placeMax'])){
				
					// numero de la place actuelle
					$place = $donnees['place'];
					//nombre de sous-categorie portant la place actuelle plus 1
					$nbSousCat = 0;
					
					//tant qu'il n'y à pas de sous-categorie avec une place en dessous on continue à chercher !
					while($nbSousCat == 0)
					{
						$place++;
						/*********************** Requete ***************************
						************* Selection de la sous-categorie ***************
						****** positionné juste apres celle que l'on veut modifier */
						
						$requete1 = $bdd->prepare("SELECT * FROM forum_souscategories WHERE id_categorie = :id and place=:place");
						$requete1->execute(array('id'=>$donnees['id_categorie'], 'place'=>$place)) or die ("erreur");
						$nbSousCat = $requete1->rowCount();
					}
					//dès qu'on a trouver le numero de la souscategorie on fetch()
					$reponse = $requete1->fetch();
					//on close
					$requete1->closeCursor();
					//on stock les places dans des variable, c'est plus maniable
					$derniere = $donnees['place']; //actuel
					$premiere = $reponse['place']; //celle trouvé
					
					/***************************** Requete ********************************
					************* On inverse les places des sous categories ***************
					******************* En mettant à jour la table ************************/
					
					$req = $bdd->prepare("UPDATE forum_souscategories SET place= :place WHERE id_souscategorie=:id");
					$req->execute(array('place'=>$premiere, 'id'=>$donnees['id_souscategorie']));
					
					$req2 = $bdd->prepare("UPDATE forum_souscategories SET place = :place WHERE id_souscategorie = :id");
					$req2->execute(array('place'=>$derniere, 'id'=>$reponse['id_souscategorie']));
					
					//on affiche le bon déroulement de l'operation !
					echo '<p class="bon">La sous-categorie <em>'.$donnees['titre_souscat'].'</em> a bien changé de place !</p>';
				}
				//changement de categorie !
				else if(($donnees['place'] == $max['placeMax']) and ($donnees['id_categorie'] != $maxCat['catMax'])){
					//numero de la nouvelle categorie
					$numCategorie = 0;
					
					//numero de l'actuelle categorie
					$idCat = $donnees['id_categorie'];
					
					/**************** Requete ***************
					***** trouver la categorie suivante *****
					*****************************************/
					
					while($numCategorie == 0){
						$idCat++;
						$requete = $bdd->prepare("SELECT * FROM forum_categories WHERE id_categorie =:id");
						$requete->execute(array('id'=>$idCat));
						$numCategorie = $requete->rowCount();
					}
					
					//on fetch après la boucle (toujours)
					$donnees1 = $requete->fetch();
					
					//on close après le fetch quand celui-ci n'estt pas dans une boucle
					$requete->closeCursor();
					
					//on mets la categorie trouver dans une variable !
					$id_cat = $donnees1['id_categorie'];
					
					/************************** Requete **************************
					***** On selectionne la plus petite place dans la nouvelle ***
					****** categorie et on ajoute 1 au autre sous-categorie ******
					**************************************************************/
					
					$reponse = $bdd->prepare("SELECT MIN(place) as numPlace FROM forum_souscategories WHERE id_categorie = :id");
					$reponse->execute(array('id'=>$id_cat));
					$donnees1 = $reponse->fetch();
					$place = $donnees1['numPlace'];
					
					//on mets à jour toute les autres sous-categorie en premier
					$r = $bdd->prepare("SELECT * FROM forum_souscategories WHERE id_categorie = :id");
					$r->execute(array('id'=>$id_cat));
					$nbSousCat = $r->rowCount();
					if($nbSousCat != 0){
						$r2 = $bdd->prepare("SELECT * FROM forum_souscategories WHERE id_categorie = :id");
						$r2->execute(array('id'=>$id_cat));
						$placeNew = $place;
						while($reponse1 = $r2->fetch()){
							$placeNew++;
							$req = $bdd->prepare("UPDATE forum_souscategories SET place=:place WHERE id_souscategorie = :id");
							$req->execute(array('place'=>$placeNew, 'id'=>$reponse1['id_souscategorie']));
						}
						
						
					}
					else{$place = 1;}
					
					//On mets à jour la sous-categories
					
					$requete = $bdd->prepare("UPDATE forum_souscategories SET id_categorie = :id, place = :place WHERE id_souscategorie = :souscat");
					$requete->execute(array('id'=>$id_cat, 'place'=>$place, 'souscat'=>intval($_GET['descendre']))) or die ("erreur");
					//on informe l'admin du bon déroulement !
					echo '<p class="bon">Le changement de catégorie c\'est bien déroulé !</p>';
				}
			}
			
			/************ Requete ************
			***** supprimer une catégories ***
			**********************************/
			if(isset($_GET['supprimerCat'])){
			
				//suppression des réponses !
				$req = $bdd->prepare("DELETE FROM forum_reponses WHERE id_sujet IN (SELECT id_sujet FROM forum_sujets FSU JOIN forum_souscategories FSO ON FSU.id_souscat = FSO.id_souscategorie WHERE FSO.id_categorie = :cat )");
				$req->execute(array('cat'=>intval($_GET['supprimerCat']))) or die(print_r($req->errorInfo()));
				$req->closeCursor();
				//suppression des sujets
				$req = $bdd->prepare("DELETE FROM forum_sujets WHERE id_souscat IN (SELECT id_souscategorie FROM forum_souscategories WHERE id_categorie = :cat )");
				$req->execute(array('cat'=>intval($_GET['supprimerCat']))) or die(print_r($req->errorInfo()));
				$req->closeCursor();
				//suppression des sous-categories
				$requete = $bdd->prepare("DELETE FROM forum_souscategories WHERE id_categorie =:cat");
				$requete->execute(array('cat'=>intval($_GET['supprimerCat']))) or die(print_r($requete->errorInfo()));
				$requete->closeCursor();
				//suppression de la categorie
				$requete = $bdd->prepare("DELETE FROM forum_categories WHERE id_categorie = :cat");
				$requete->execute(array('cat'=>intval($_GET['supprimerCat']))) or die(print_r($requete->errorInfo()));
				$requete->closeCursor();
				echo '<p class="bon">La catégorie a bien été supprimé ainsi que ses sous-catégories, ses sujets et ses réponse !</p>';
			}
			
			/************ Requete ************
			***** Modifier une catégories ****
			**********************************/
			
			if(isset($_GET['modifierCatOk'])){
				$requete = $bdd->prepare("UPDATE forum_categories SET titre_cat = :titre WHERE id_categorie = :id_cat");
				$requete->execute(array('titre'=>$_POST['titre'], 'id_cat'=>intval($_GET['modifierCatOk'])))or die(print_r($requete->errorInfo()));
				$requete->closeCursor();
				echo '<p class="bon">La catégorie a bien été modifié !</p>';
			}
			/************ Requete ************
			***** Ajouter une catégories *****
			**********************************/
		
			if(isset($_GET['ajoutCatOk'])){
				$requete = $bdd->prepare("INSERT INTO forum_categories (titre_cat) VALUES(:titre)");
				$requete->execute(array('titre'=>$_POST['titre']));
				$requete->closeCursor();
				echo'<p class="bon">La catégorie <em>'.htmlspecialchars($_POST['titre']).' a été bien ajouté !</em></p>';
			}
			
			/************ Requete ****************
			***** Modifier une sous-catégorie ****
			**************************************/
			if(isset($_GET['modifierSousCatOk'])){
				$requete = $bdd->prepare("UPDATE forum_souscategories SET titre_souscat =:titre, sousTitre_souscat=:soustitre WHERE id_souscategorie =:id");
				$requete->execute(array('titre'=>$_POST['titre'], 'soustitre'=>$_POST['soustitre'], 'id'=>intval($_GET['modifierSousCatOk'])));
				echo'<p class="bon">La sous-catégorie <em>'.htmlspecialchars($_POST['titre']).' a bien été modifié !</em></p>';
			}
			
			/************ Requete ****************
			***** Ajouter une sous-catégorie ****
			**************************************/
			if(isset($_GET['ajoutSousCatOk'])){
				$requete = $bdd->prepare("SELECT MAX(place) as nb FROM forum_souscategories WHERE id_categorie=:cat");
				$requete->execute(array('cat'=>$_POST['categorie'])) or die(print_r($requete->errorInfo()));
				$donnees = $requete->fetch();
				$requete->closeCursor();
				
				$place = $donnees['nb']+1;
				
				$requete = $bdd->prepare("INSERT INTO forum_souscategories (titre_souscat,id_categorie,sousTitre_souscat,place,id_in) VALUES(:titre,:cat,:soustitre,:place,:id_in)");
				$requete->execute(array('titre'=>$_POST['titre'], 'cat'=>$_POST['categorie'], 'soustitre'=>$_POST['soustitre'], 'place'=>$place,'id_in'=>-1)) or die(print_r($requete->errorInfo()));
				echo'<p class="bon">La sous-catégorie <em>'.htmlspecialchars($_POST['titre']).' a bien été ajouté !</em></p>';
			}
			
			/*************** Formulaire ******************
			***** Modifier/Ajouter une sous-catégorie ****
			**********************************************/
			if(isset($_GET['modifierSousCat']) OR isset($_GET['ajoutSousCat'])){
				$titre =null;
				$sousTitre = null;
				$type="Ajout";
				$action="?ajoutSousCatOk=ok";
				$texte = "Ajouter";
				if(isset($_GET['modifierSousCat'])){
					$requete = $bdd->prepare("SELECT * FROM forum_souscategories WHERE id_souscategorie=:souscat");
					$requete->execute(array('souscat'=>intval($_GET['modifierSousCat'])));
					$donnees = $requete->fetch();
					$titre = htmlspecialchars($donnees['titre_souscat']);
					$sousTitre = htmlspecialchars($donnees['sousTitre_souscat']);
					$type= "Modification";
					$action = "?modifierSousCatOk=".intval($_GET['modifierSousCat']);
					$texte = "Modifier";
				}
			
				echo '
					<form id="sousCategorie" method="post" action="structure.php'.$action.'">
						<fieldset>
							<legend>'.$type.'</legend> 
							<table>
								<tr>
									<td>Titre : </td>
									<td><input type="text" name="titre" value="'.$titre.'" required></td>
								</tr>
								<tr>
									<td>Sous-titre : </td>
									<td><input type="text" name="soustitre" size="40" value="'.$sousTitre.'" required></td>
								</tr>
								<tr>
								';
									if(isset($_GET['ajoutSousCat'])){
										echo'<td>Catégorie : </td>
										<td>
										   <select name="categorie" id="categorie" required>';
										   
										   $requete = $bdd->query("SELECT * FROM forum_categories");
										   while($donnees = $requete->fetch()){
											   echo'<option value="'.$donnees['id_categorie'].'">'.htmlspecialchars($donnees['titre_cat']).'</option>';
											}
										   echo'</select></td>';
									}
								echo'
								</tr>
							</table>';
							echo'<input type="submit" value="'.$texte.'">
						</fieldset>
					</form>';
			}
			
			/************ Formulaire *********
			***** Modifier une catégories ****
			**********************************/
			if(isset($_GET['modifierCat'])){
			
				$titre = null; //déclaration de la variable qui contiendra le titre
				
				/************ Requete ************
				***** Modifier une catégories ****
				**********************************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_categories WHERE id_categorie=:cat");
				$requete->execute(array('cat'=>intval($_GET['modifierCat'])));
				$reponse = $requete->fetch();
				$requete->closeCursor();
				$titre = htmlspecialchars($reponse['titre_cat']); // affectation du titre
				
				echo '
					<form id="modifCategorie" method="post" action="structure.php?modifierCatOk='.intval($_GET['modifierCat']).'">
						<fieldset>
							<legend>Modification</legend> 
							
							<label>Titre : </lable><input type="text" name="titre" value="'.$titre.'"><br>
							<input type="submit" value="Modifier">
						</fieldset>
					</form>';
			}
			
			/*********** Formulaire **********
			***** Ajouter une catégories *****
			**********************************/
			if(isset($_GET['ajoutCategorie'])){
				
					echo'<form id="ajoutCategorie" method="post" action="structure.php?ajoutCatOk=ok">
						<fieldset>
							<legend>Ajout</legend> 
							
							<label>Titre : </lable><input type="text" name="titre"><br>
							<input type="submit" value="Ajouter">
						</fieldset>
					</form>
				
			';
			}
			
			/************** Requete **************
			***** Supprimer une sous-catégorie ***
			**************************************/
			
			if(isset($_GET['supprimerSousCat'])){
				/********************* Requete *********************
				***** Selectionner la sous-catégorie a supprimer ***
				****************************************************/	
				
				$requete = $bdd->prepare("SELECT * FROM forum_souscategories WHERE id_souscategorie=:id");
				$requete->execute(array('id'=>intval($_GET['supprimerSousCat'])));
				$donnees = $requete->fetch();
				$requete->closeCursor();
				
				/********************* Requete *********************
				***** Selectionner le nombre de place maximum ******
				****************************************************/
				
				$requete = $bdd->prepare("SELECT MAX(place) as nbPlace FROM forum_souscategories WHERE id_categorie=:id");
				$requete->execute(array('id'=>$donnees['id_categorie']))or die(print_r($requete->errorInfo()));	
				$Place = $requete->fetch();
				$requete->closeCursor();
				
				//place actuel
				$place = $donnees['place'];
				//place max
				$placeMax = $Place['nbPlace'];
				//place actuel
				$i = $donnees['place'];
				while($i < $placeMax ){
					$i++;
					$modif = $bdd->prepare("UPDATE forum_souscategories SET place=:place WHERE id_categorie=:id and place=:i");
					$modif->execute(array('place'=>$place, 'id'=>$donnees['id_categorie'], 'i'=>$i)) or die(print_r($modif->errorInfo()));	
					$place++;
					
				}
				
				//suppression des réponses !
				$req = $bdd->prepare("DELETE FROM forum_reponses WHERE id_sujet IN (SELECT id_sujet FROM forum_sujets WHERE id_souscat = :cat )");
				$req->execute(array('cat'=>intval($_GET['supprimerSousCat']))) or die(print_r($req->errorInfo()));
				$req->closeCursor();
				//suppression des sujets
				$req = $bdd->prepare("DELETE FROM forum_sujets WHERE id_souscat = :cat");
				$req->execute(array('cat'=>intval($_GET['supprimerSousCat']))) or die(print_r($req->errorInfo()));
				$req->closeCursor();
				
				/************** Requete ****************
				***** supprimer la sous-catégorie ******
				****************************************/
				
				$requete = $bdd->prepare("DELETE FROM forum_souscategories WHERE id_souscategorie = :id");
				$requete->execute(array('id'=>intval($_GET['supprimerSousCat']))) or die(print_r($requete->errorInfo()));	
				$requete->closeCursor();
			}
		
			/************ Requete ************
			***** Affichage des catégories ***
			**********************************/
			$requete = $bdd->query("SELECT * FROM forum_categories");
			
			echo'
				<table class="structure">
					<tr>
						<th class="id_liste_cat">N°</th>
						<th>Titre</th>
						<th>Place</th>
						<th class="action_liste_cat">Action</th>
					<tr>';
					
					/************ Affichage **********
					***** Affichage des catégories ***
					**********************************/
					
			while($categories = $requete->fetch()){
				echo'<tr>
					<td class="center_structure">'.$categories['id_categorie'].'</td>
					<td class="titre_cat">'.htmlspecialchars($categories['titre_cat']).'</td>
					<td></td>
					<td class="center_structure"><a class="modifier_cat" href="structure.php?modifierCat='.$categories['id_categorie'].'">Modifier</a> | <a class="supprimer_cat" href="structure.php?supprimerCat='.$categories['id_categorie'].'">Supprimer</a></td>
				</tr>';

				/******************* Requete *********************
				***** Compte les sous catégories par catégorie ***
				**************************************************/
						
				$reponse = $bdd->prepare("SELECT * FROM forum_souscategories WHERE id_categorie = :cat ORDER BY place");
				$reponse->execute(array('cat'=>$categories['id_categorie'])) or die ("erreur");
				
				while($sousCategories = $reponse->fetch()){		
						echo'<tr>
							<td class="center_structure">'.$sousCategories['id_souscategorie'].'</td>
							<td class="titre_souscat">'.htmlspecialchars($sousCategories['titre_souscat']).'<br>'.htmlspecialchars($sousCategories['sousTitre_souscat']).'</td>
							<td class="center_structure"><a href="structure.php?monter='.$sousCategories['id_souscategorie'].'"><img src="images/fleche_haut.png" alt="" title="monter la sous-categorie"></a><br> <a href="structure.php?descendre='.$sousCategories['id_souscategorie'].'"><img src="images/fleche_bas.png" alt="" title="descendre la sous-categorie"></a></td>
							<td class="center_structure"><a class="modifier_souscat" href="structure.php?modifierSousCat='.$sousCategories['id_souscategorie'].'">Modifier</a> | <a class="supprimer_souscat" href="structure.php?supprimerSousCat='.$sousCategories['id_souscategorie'].'">Supprimer</a></td>
						</tr>';
				}
			}
			echo'</table>';		
		}
	echo '</article>';

echo'</section>';

include("includes/bas.php");
?>

<script src="JS/structure.js"></script>