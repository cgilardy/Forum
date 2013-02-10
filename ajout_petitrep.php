<?php
$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
		if(isset($_SESSION['session']) and $_SESSION['session'] == true){
			$requete = $bdd->prepare("SELECT * FROM forum_petitsujet FPS JOIN forum_petitcat FPC ON FPC.id_petite = FPS.id_petitcat JOIN forum_souscategories FSC ON FSC.id_souscategorie = FPC.id_souscat JOIN forum_categories FC ON FC.id_categorie = FSC.id_categorie WHERE FPS.id_petitsuj = :id_sujet");
			$requete->execute(array('id_sujet'=>intval($_GET['id_petitsuj']))) or die(print_r($requete->errorInfo()));	
			$donnees = $requete->fetch();
			echo'<p class="ariane">> <a href="index.php">Forum</a> > '.htmlspecialchars($donnees['titre_cat']).' > <a href="voir_sujet.php?id_souscat='.intval($donnees['id_souscat']).'">'.htmlspecialchars($donnees['titre_souscat']).'</a> > <a href="voir_petitrep.php?id_petitsuj='.intval($_GET['id_petitsuj']).'">'.htmlspecialchars($donnees['titre_petitsuj']).'</a> > Ajout d\'une réponse</p><br>';
			
			/***************** Requete *****************
			************* Ajout d'une reponse **********
			**************** dans le sujet *************/
			
			if(isset($_GET['ajout'])){
			
				/***************** Requete *****************
				******* Selection de la derniere reponse ***
				**************** dans le sujet *************/
				
				$requete = $bdd->prepare("SELECT * FROM forum_petitrep WHERE id_petitsuj=:id ORDER BY date_creation_petitrep DESC LIMIT 1");
				$requete->execute(array('id'=>intval($_GET['id_petitsuj'])));
				$donnees = $requete->fetch();
				$requete->closeCursor();
				$passe = false; //teste si le membre qui poste est le m�me qui vient de poster
				if($_SESSION['id'] == $donnees['id_membre']){
					$resultat = time()-$donnees['date_creation_petitrep']; // regarde depuis combien de temps le membre a post�
				}
				else{
					$passe = true;
				}
				//si sa fais plus q'un jour que le membre n'a pas poster il peut poster a sa suite
				if(($resultat >= 86400) OR $passe){
					//mise a jour des sujets lut !
					$majlut = $bdd->prepare("DELETE FROM lus_petitsuj WHERE id_membre != :id and id_petitsuj=:id_sujet");
					$majlut->execute(array('id'=>$_SESSION['id'], 'id_sujet'=>intval($_GET['id_petitsuj'])));
					$majlut->closeCursor();
					//mise a jour des sujets
					$maj = $bdd->prepare("UPDATE forum_petitsujet SET date_derniere_reponse_petitsuj=:date WHERE id_petitsuj=:id");
					$maj->execute(array('date'=>time()+3600, 'id'=>$_GET['id_petitsuj']));
					$maj->closeCursor();
					//insertion de la reponse
					$requete = $bdd->prepare("INSERT INTO forum_petitrep (message_petitrep,id_membre,id_petitsuj,date_creation_petitrep,date_modification_petitrep) VALUES(:mess, :log,:sujet,:crea,:modi)");
					$requete->execute(array('mess'=>$_POST['message'], 'log'=>$_SESSION['id'], 'sujet'=>$_GET['id_petitsuj'], 'crea'=>(time()+3600), 'modi'=>null)) or die(print_r($requete->errorInfo()));	
					header("Location: voir_petitrep.php?reponseok=ok&id_petitsuj=".intval($_GET['id_petitsuj']));
				}
				else
					header("Location: voir_petitrep.php?reponse=no&id_petitsuj=".intval($_GET['id_petitsuj']));
			}
			
			echo '<h1 class="titre">'.htmlspecialchars($donnees['titre_petitsuj']).'</h1>'; // titre
			
			echo'
			<form id="ajout_reponse" method="post" action="ajout_petitrep.php?id_petitsuj='.intval($_GET['id_petitsuj']).'&amp;ajout=ok" ">
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
