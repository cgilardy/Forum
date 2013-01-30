<?php
if(isset($_GET['inscription']) && $_GET['inscription'] != 'no')
			echo '<p class="bon">Vous êtes bien inscrits !</p>';
		else if(isset($_GET['inscription']) && $_GET['inscription'] == 'no')
			echo '<p class="erreur">L\'inscription à échoué !</p>';
		else if(isset($_GET['pseudo']))
			echo '<p class="erreur">Votre pseudo est déjà utilisé.</p>';
		else if(isset($_GET['email']))
			echo '<p class="erreur">Votre email est déjà utilisé.</p>';
		if(isset($_GET['connexion']) and $_GET['connexion'] == 'no')
			echo '<p class="erreur">Vous avez dû vous tromper dans vos log.</p>';
		else if(isset($_GET['connexion']) and $_GET['connexion']= 'ok')
			echo '<p class="bon">Vous êtes bien connécté <a href="profil.php?id_pseudo='.$_SESSION['id'].'">'.$_SESSION['pseudo'].'</a></p>';
		if(isset($_GET['deconnexion']))
			echo '<p class="bon">Vous êtes bien déconnécté.</p>';
?>