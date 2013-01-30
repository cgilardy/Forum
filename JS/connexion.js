$(function(){
$("article").css('opacity','1.0');
	$("#connexion_haut").click(function(){
		$("article").css('opacity','0.5');
		$("#box").fadeIn();
		$("#haut_connexion").fadeIn();
		return false;
	});

	$(".quit").click(function(){
		$("article").css('opacity','1.0');
		$("#haut_connexion").fadeOut();
		$("#box").fadeOut();
	
	});
});