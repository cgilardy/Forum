$(function(){
	
	$('#vue').hide();
	$('#apercu').click(function(){
	var message = $('#ajout_reponse').find('textarea[id="message"]').val();
		if(message != ""){
			$('#vue').fadeIn();
			var message = $('#ajout_reponse').find('textarea[id="message"]').val();
			$('#vue').html(message);
		}
	});
	
	$('a.suppre').click(function(){
		if(confirm("Voulez vous supprimer ce message ?"))
			return true;
		else
			return false;
	});
	
	$('a.sup').click(function(){
		if(confirm("Voulez vous supprimer le sujet ?"))
			return true;
		else
			return false;
	});
});