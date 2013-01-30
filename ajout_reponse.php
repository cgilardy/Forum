<?php
$titre_page = 'Forum-RPG';
include("includes/haut.php");

echo '
<section>
	<article>';
		if(isset($_SESSION['session']) and $_SESSION['session'] == true){
			$requete = $bdd->prepare("SELECT * FROM forum_sujets FSU JOIN forum_souscategories FSO ON FSU.id_souscat = FSO.id_souscategorie JOIN forum_categories FC ON FSO.id_categorie = FC.id_categorie WHERE FSU.id_sujet = :id_sujet");
			$requete->execute(array('id_sujet'=>$_GET['id_sujet'])) or die(print_r($requete->errorInfo()));	
			$donnees = $requete->fetch();
			echo'<p class="ariane">> <a href="index.php">Forum</a> > '.$donnees['titre_cat'].' > <a href="voir_sujet.php?id_souscat='.$donnees['id_souscat'].'">'.$donnees['titre_souscat'].'</a> > <a href="voir_reponse.php?id_sujet='.$_GET['id_sujet'].'">'.$donnees['titre_sujet'].'</a> > Ajout d\'une réponse</p><br>';
			
			if(isset($_GET['ajout'])){
				$requete = $bdd->prepare("INSERT INTO forum_reponses (message_reponse,id_membre,id_sujet,date_creation_reponse,date_modification_reponse) VALUES(:mess, :log,:sujet,:crea,:modi)");
				$requete->execute(array('mess'=>$_POST['message'], 'log'=>$_SESSION['id'], 'sujet'=>$_GET['id_sujet'], 'crea'=>(time()+3600), 'modi'=>null)) or die(print_r($requete->errorInfo()));	
				header("Location: voir_reponse.php?reponseok=ok&id_sujet=".$_GET['id_sujet']);
			}
			
			echo '<h1 class="titre">'.$donnees['titre_sujet'].'</h1>'; // titre
			
			echo'
			<form id="ajout_reponse" method="post" action="ajout_reponse.php?id_sujet='.$_GET['id_sujet'].'&amp;ajout=ok" ">
				<fieldset>
				<legend>Ajouter une réponse</legend> <!-- Titre du fieldset --> 
					<table style="margin:auto;">
						<tr>
							<td><textarea id="message" name="message" cols="100" rows="15" ></textarea></td>
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
