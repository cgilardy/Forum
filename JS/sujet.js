$(function(){
$('#vue').hide();
	$('#apercu').click(function(){
	var message = $('#ajout_sujet').find('textarea[id="message"]').val();
		if(message != "" && message != " "){
			$('#vue').fadeIn();
			var message = $('#ajout_sujet').find('textarea[id="message"]').val();
			$('#vue').html(message);
		}
	});
$('.lus_sujet').css('border-right','none');
$('.titre_sujet').css('border-left','none');
});