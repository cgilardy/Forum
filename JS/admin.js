$(function(){
	var taille = $("#admin").height();
	var article = $("article").height();
	var resultat = taille + article +10;
	$('article').css('height',resultat);
});