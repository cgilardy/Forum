<?php
$titre_page = 'Inscription';
include("includes/haut.php");

echo '
<section>
	<article>';
		echo'<p class="ariane">> <a href="index.php">Forum</a> > Accueil</p><br>';
		echo '<h1>Inscription</h1>';
		if(!isset($_SESSION['session']))
		{
		?>
			<form id="inscriptionForm" method="post" action="traitement_inscription.php" enctype="multipart/form-data">
<fieldset>
<legend>Personnage</legend> <!-- Titre du fieldset --> 
	<table>
		<tr>
			<td>Pseudo : </td>
			<td><input id="pseudo" name="pseudo" type="text" size="30" min="4" autofocus required placeholder="Nom et prénom ou pseudo" /><span class="erreur-pseudo"></span></td>
		</tr>
		<tr>
			<td>Age :</td>
			<td><input id="age" name="age" type="number" required> <span class="erreur-age"></span></td>
		</tr>
		<tr>
			<td>Sexe : </td>
			<td><input name="sexe" type="radio" value="Homme" required>Homme<br>
				<input name="sexe" type="radio" value="Femme" required>Femme</td>
		</tr>
		<tr>
			<td>Avatar : </td>
			<td><input name="photo" type="file" required/></td>
		</tr>
		<tr>
			<td>Description : </td>
			<td><textarea id="description" name="description" cols="50" rows="5" placeholder="Une description de votre personnage, qui pourra être modifier plus tard dans vos options. (pas obligatoire)"></textarea></td>
		</tr>
	</table>
</fieldset>
<fieldset>
<legend>Personnel</legend> <!-- Titre du fieldset --> 
	<table>
		<tr>
			<td>E-mail :</td>
			<td><input name="email" id="email" type="email" size="30"><span class="erreur-email"></span></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input id="passe" name="passe" type="password" size="20" min="6" required><span class="erreur-passe"></span></td>
		</tr>
		<tr>
			<td>Confirmation</td>
			<td><input name="confirme" id="confirme" type="password" size="20" min="6" required> <span class="erreur-confirme"></span></td>
		</tr>
	</table>
</fieldset>
	<center>
		<input name="Submit" type="submit" id="inscrire" value="S'inscrire"/>
	</center>
</form>
<?php	
}
else
	echo'<p class="erreur">Vous êtes déjà inscrit !</p>';
echo '</article>';

echo'</section>';

include("includes/bas.php");
?>
<script src="JS/inscription.js"></script>