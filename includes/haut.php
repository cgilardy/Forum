<?php
include("config.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">	
	<link href="CSS/index.css" rel="stylesheet" type="text/css">
	<link href="CSS/inscription.css" rel="stylesheet" type="text/css">
	<link href="CSS/connexion.css" rel="stylesheet" type="text/css">
	<link href="CSS/profil.css" rel="stylesheet" type="text/css">
	<link href="CSS/liste_membres.css" rel="stylesheet" type="text/css">
	<link href="CSS/bbcode.css" rel="stylesheet" type="text/css">
	<link href="CSS/gestion_categorie.css" rel="stylesheet" type="text/css">
	<link href="CSS/admin.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/base/jquery-ui.css">
	<script src="/chemin/vers/jquery.js" type="text/javascript"></script>  
	<script src="/chemin/vers/jquery.ui.draggable.js" type="text/javascript"></script>  
  
	<script src="/chemin/vers/jquery.alerts.js" type="text/javascript"></script>  
	<link href="/chemin/vers/jquery.alerts.css" rel="stylesheet" type="text/css" media="screen">  
	<link rel="icon" type="image/png" href="images/favicon.ico" />
	<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" /><![endif]-->
	<title><?php echo $titre_page; ?></title>
	<!-- script qui sert pour le bbcode -->
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>
	<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
    <script src="/resources/demos/external/jquery.bgiframe-2.1.2.js"></script>
	<script src="JS/bbcode.js"></script>
</head>

<body>
	<div id="box">
		<div id="haut_connexion">
			<h2>Connexion <a class="quit" href="#"><img src="images/erreur.png" alt="fermer" title="Fermer"></a></h2>
			<form method="post" action="traitement_connexion.php">
			<table>
				<tr>
					<td>Login :</td>
					<td><input type="text" name="pseudo" required></td>
				</tr>
				<tr>
					<td>Password :</td>
					<td><input type="password" name="passe" required></td>
				</tr>
			</table>
			<input type="submit" value="Connexion">
			</form>
		</div>
	</div>
	<div id="page">
	
	<header>	
		
	</header>
		<nav>
			<ul>
				<li><a href="index.php">Acceuil</a></li>
				<?php
					if(isset($_SESSION['session']) and $_SESSION['session'] == true){
						echo '<li><a href="profil.php?id_pseudo='.$_SESSION['id'].'">Profil</a></li>';
						echo'<li><a href="deconnexion.php">Deconnexion</a></li>';
					}
					else{
					echo'
						<li><a id="connexion_haut" href="">Connexion</a></li>
						<li><a href="inscription.php">Inscription</a></li>';
					}
				
				?>
			</ul>
		</nav>
<script src="JS/connexion.js"></script>