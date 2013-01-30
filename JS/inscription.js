
$(function(){
	pseudo = true;
	age = true;
	email = true;
	passe = true;
	confirme = true;
	
	$("#pseudo").keyup(function(){
		
		$(".erreur-pseudo").css('background','url(images/erreur.png) 2px no-repeat');
		if($("#pseudo").val() == ""){
			$("#pseudo").next(".erreur-pseudo").fadeIn().text("Veuillez entrer un pseudo !");
			pseudo = false;
		}
		else if($("#pseudo").val().length < 4){
			$("#pseudo").next(".erreur-pseudo").fadeIn().text("Veuillez entrer un pseudo supérieur à 4 caractères !");
			pseudo = false;
		}
		else{
			$(".erreur-pseudo").css('background','url(images/ok.png) 0px center no-repeat');
			$("#pseudo").next(".erreur-pseudo").fadeIn().text("Valide");
			pseudo = true;
		}
	});
	$("#age").keyup(function(){
			$(".erreur-age").css('background','url(images/erreur.png) 2px no-repeat');
		if($("#age").val() == ""){
			$("#age").next(".erreur-age").fadeIn().text("Veuillez entrer l'age de votre personnage");
			age = false;
		}
		else if ($("#age").val() < 11){
			$("#age").next(".erreur-age").fadeIn().text("L'âge doit être supérieur ou égal à 11.");
			age = false;
		}
		else{
			$(".erreur-age").css('background','url(images/ok.png) 0px center no-repeat');
			$("#age").next(".erreur-age").fadeIn().text("Valide");
			age = true;
		}
	});
	
	$("#email").keyup(function(){
	$(".erreur-email").css('background','url(images/erreur.png) 2px no-repeat');
		if($("#email").val() == ""){
			$("#email").next(".erreur-email").fadeIn().text("Vote adresse mail est obligatoire (elle ne sera pas publier sur votre profil sans votre accord).");
			email = false;
		}
		else{
			$(".erreur-email").css('background','url(images/ok.png) 0px center no-repeat');
			$("#email").next(".erreur-email").fadeIn().text("Valide");
			email = true;
		}
	});
	
	$("#passe").keyup(function(){
		$(".erreur-passe").css('background','url(images/erreur.png) 2px no-repeat');
		if($("#passe").val() == ""){
			$("#passe").next(".erreur-passe").fadeIn().text("Il vous faut un mot de passe !");
			passe = false;
		}
		else if($("#passe").val().length < 6){
			$("#passe").next(".erreur-passe").fadeIn().text("Votre mot de passe doit faire plus de 6 caractères.");
			passe = false;
		}
		else{
			$(".erreur-passe").css('background','url(images/ok.png) 0px center no-repeat');
			$("#passe").next(".erreur-passe").fadeIn().text("Valide");
			passe=true;
		}
	});
	
	$("#confirme").keyup(function(){
		$(".erreur-confirme").css('background','url(images/erreur.png) 2px no-repeat');
		if($("#confirme").val() != $("#passe").val()){
			$("#confirme").next(".erreur-confirme").fadeIn().text("La confirmation doit être identique au mot de passe.");
			confirme = false;
		}
		else{
			$(".erreur-confirme").css('background','url(images/ok.png) 0px center no-repeat');
			$("#confirme").next(".erreur-confirme").fadeIn().text("Valide");
			confirme = true;
		}
	});
	$("#inscrire").click(function(){
		valid = true;
		if(pseudo == false || age == false || email == false || passe == false || confirme == false){
			alert('Certain champ sont mal templie !');
			valid = false;
		}
		
		return valid;
	});
});