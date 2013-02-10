<?php
$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
		if(isset($_SESSION['session']) and $_SESSION['session'] == true){
			$requete = $bdd->prepare("SELECT * FROM forum_petitcat FP JOIN forum_souscategories FS ON FS.id_souscategorie = FP.id_souscat JOIN forum_categories FC ON FS.id_categorie = FC.id_categorie WHERE FP.id_petite = :id_souscat");
			$requete->execute(array('id_souscat'=>intval($_GET['id_petitcat']))) or die(print_r($requete->errorInfo()));	
			$donnees = $requete->fetch();
			echo'<p class="ariane">> <a href="index.php">Forum</a> > '.htmlspecialchars($donnees['titre_cat']).' > <a href="voir_sujet.php?id_souscat='.intval($donnees['id_souscat']).'">'.htmlspecialchars($donnees['titre_souscat']).'</a> > <a href="voir_petitsuj.php?id_petit='.intval($_GET['id_petitcat']).'">'.htmlspecialchars($donnees['titre_petite']).'</a> > Ajout d\'un sujet</p><br>';
			
			/***************** Requete *****************
			************* Ajout d'un sujet *************
			************* dans la souscat **************/
			
			if(isset($_GET['ajout'])){
				
				/***************** Requete *****************
				******* Selection de la derniere reponse ***
				**************** dans le sujet *************/
				$annonce = 0;
				if(isset($_POST['annonce'])){
					$annonce = 1;
				}
				$requete = $bdd->prepare("INSERT INTO forum_petitsujet (titre_petitsuj,sousTitre_petitsuj,id_petitcat,annonce_petitsuj,date_creation_petitsuj, date_modification_petitsuj,date_derniere_reponse_petitsuj,id_membre,message_petitsuj) VALUES(:titre,:soustitre,:id_souscat,:annonce,:date_c,:date_m,:date_d,:id_m,:message)");
				$requete->execute(array('titre'=>$_POST['titre'], 'soustitre'=>$_POST['soustitre'], 'id_souscat'=>intval($_GET['id_petitcat']),'annonce'=>$annonce, 'date_c'=>time()+3600, 'date_m'=>null,'date_d'=>time()+3600, 'id_m'=>$_SESSION['id'], 'message'=>$_POST['message'])) or die(print_r($requete->errorInfo()));	
				header("Location: voir_petitsuj.php?id_petit=".intval($_GET['id_petitcat']));
			}
			
			echo '<h1 class="titre">'.htmlspecialchars($donnees['titre_petite']).'</h1>'; // titre
			
			echo'
			<form id="ajout_sujet" method="post" action="ajout_petitsuj.php?id_petitcat='.intval($_GET['id_petitcat']).'&amp;ajout=ok" ">
				<fieldset>
				<legend>Ajouter un sujet</legend> <!-- Titre du fieldset --> 
					<table style="margin:auto;">
							<tr>
							<td>Titre<br>
							<input type="text" size="50" name="titre"></td>
						</tr>
						<tr>
							<td>Sous-titre<br>
							<input type="text" size="50" name="soustitre"></td>
						</tr>
						<tr>
							<td colspan="2">Votre message<br>';
							?>
								<input type="button" value="G"  onclick="insertTag('<gras>','</gras>','message');"/>
								<input type="button" value="I" onclick="insertTag('<italic>','</italic>','message');"/>
								<input type="button" value="Lien" onclick="insertTag('<Lien=&quot;votre lien&quot;>','</lien>','message');"/>
								<input type="button" value="Image" onclick="insertTag('<image=&quot;lien de l\'image&quot;/>','','message');"/>
								<input type="button" value="Citation" onclick="insertTag('<citation=&quot;auteur&quot;>','</citation>','message');"/>
								
								<select onchange="insertTag('<taille valeur=&quot;' + this.options[this.selectedIndex].value + '&quot;>', '</taille>', 'message');">
									<option value="none" class="selected" selected="selected">Taille</option>
									<option value="8px" >Très très petit</option>
									<option value="10px">Très petit</option>
									<option value="12px">Petit</option>
									<option value="18px">Gros</option>
									<option value="22px">Très gros</option>
									<option value="26px">Très très gros</option>
								</select>
								<select onchange="insertTag('<color=&quot;' + this.options[this.selectedIndex].value + '&quot;>', '</color>', 'message');">
									<option value="none" class="selected" selected="selected">Couleur</option>
									<option value="red" >Rouge</option>
									<option value="green">Vert</option>
									<option value="purple">Violet</option>
									<option value="blue">Bleu</option>
									<option value="yellow">Jaune</option>
								</select>
								
								<?php
							echo'<br>
							<textarea id="message" name="message" cols="100" rows="15" required></textarea></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="annonce" id="annonce" /> <label for="annonce">Annonce</label></td>
						</tr>
					</table>
				</fieldset>
					<center>
						<input name="Submit" type="submit" id="inscrire" value="Envoyer"/>					
						<input name="apercu" type="button" id="apercu" value="Apercu"/>					
					</center>
			</form>
			';
			echo '<div id="vue">
				
			</div>';
		}
		else
		{
			echo '<p class="erreur">Vous n\'�te pas inscrit !</p>';
		}
	echo '</article>';

echo'</section>';

include("includes/bas.php");
?>
<script src="JS/sujet.js"></script>
