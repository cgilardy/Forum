$(function(){
	
	$('.supprimer_souscat').click(function(){
		if(confirm("Etes vous sur de vouloir supprimer cette sous-catégorie !"))
			return true;
		else
			return false;
	});
	
	$('.supprimer_cat').click(function(){
		if(confirm("Etes vous sur de vouloir supprimer cette catégorie !"))
			return true;
		else
			return false;
	});
});