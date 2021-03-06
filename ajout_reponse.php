<?php
$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
		if(isset($_SESSION['session']) and $_SESSION['session'] == true){
			$requete = $bdd->prepare("SELECT * FROM forum_sujets FSU JOIN forum_souscategories FSO ON FSU.id_souscat = FSO.id_souscategorie JOIN forum_categories FC ON FSO.id_categorie = FC.id_categorie WHERE FSU.id_sujet = :id_sujet");
			$requete->execute(array('id_sujet'=>intval($_GET['id_sujet']))) or die(print_r($requete->errorInfo()));	
			$donnees = $requete->fetch();
			echo'<p class="ariane">> <a href="index.php">Forum</a> > '.htmlspecialchars($donnees['titre_cat']).' > <a href="voir_sujet.php?id_souscat='.intval($donnees['id_souscat']).'">'.htmlspecialchars($donnees['titre_souscat']).'</a> > <a href="voir_reponse.php?id_sujet='.intval($_GET['id_sujet']).'">'.htmlspecialchars($donnees['titre_sujet']).'</a> > Ajout d\'une réponse</p><br>';
			
			/***************** Requete *****************
			************* Ajout d'une reponse **********
			**************** dans le sujet *************/
			
			if(isset($_GET['ajout'])){
			
				/***************** Requete *****************
				******* Selection de la derniere reponse ***
				**************** dans le sujet *************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_reponses WHERE id_sujet=:id ORDER BY date_creation_reponse DESC LIMIT 1");
				$requete->execute(array('id'=>intval($_GET['id_sujet'])));
				$donnees = $requete->fetch();
				$requete->closeCursor();
				$passe = false; //teste si le membre qui poste est le même qui vient de poster
				if($_SESSION['id'] == $donnees['id_membre']){
					$resultat = time()-$donnees['date_creation_reponse']; // regarde depuis combien de temps le membre a posté
				}
				else{
					$passe = true;
				}
				//si sa fais plus q'un jour que le membre n'a pas poster il peut poster a sa suite
				if(($resultat >= 86400) OR $passe){
					//mise a jour des sujets lut !
					$majlut = $bdd->prepare("DELETE FROM lus_sujets WHERE id_membre != :id and id_sujet=:id_sujet");
					$majlut->execute(array('id'=>$_SESSION['id'], 'id_sujet'=>intval($_GET['id_sujet'])));
					$majlut->closeCursor();
					//mise a jour des sujets
					$maj = $bdd->prepare("UPDATE forum_sujets SET date_derniere_reponse=:date WHERE id_sujet=:id");
					$maj->execute(array('date'=>time()+3600, 'id'=>$_GET['id_sujet']));
					$maj->closeCursor();
					//insertion de la reponse
					$requete = $bdd->prepare("INSERT INTO forum_reponses (message_reponse,id_membre,id_sujet,date_creation_reponse,date_modification_reponse) VALUES(:mess, :log,:sujet,:crea,:modi)");
					$requete->execute(array('mess'=>$_POST['message'], 'log'=>$_SESSION['id'], 'sujet'=>$_GET['id_sujet'], 'crea'=>(time()+3600), 'modi'=>null)) or die(print_r($requete->errorInfo()));	
					header("Location: voir_reponse.php?reponseok=ok&id_sujet=".intval($_GET['id_sujet']));
				}
				else
					header("Location: voir_reponse.php?reponse=no&id_sujet=".intval($_GET['id_sujet']));
			}
			
			echo '<h1 class="titre">'.htmlspecialchars($donnees['titre_sujet']).'</h1>'; // titre
			
			echo'
			<form id="ajout_reponse" method="post" action="ajout_reponse.php?id_sujet='.intval($_GET['id_sujet']).'&amp;ajout=ok" ">
				<fieldset>
				<legend>Ajouter une réponse</legend> <!-- Titre du fieldset --> 
					<table style="margin:auto;">
						<tr>
							<td>';
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
								<br>
								<?php
								echo'
							<textarea id="message" name="message" cols="100" rows="15" required></textarea></td>
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
			echo '<p class="erreur">Vous n\'ête pas inscrit !</p>';
		}
	echo '</article>';

echo'</section>';

include("includes/bas.php");
?>
<script src="JS/reponse.js"></script>
