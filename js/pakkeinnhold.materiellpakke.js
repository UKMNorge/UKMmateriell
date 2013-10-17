jQuery(document).on('change', '.produkt input', function(){
	calc_rest( jQuery(this).parents('tr.produkt') );
});

jQuery(document).ready(function() {
	jQuery('.produkt').each(function(){
		calc_rest( jQuery(this) );
	})
});

function calc_rest(produkt) {
	console.log(produkt);
	behov = parseInt( produkt.find('td.behov').html() );
	opplag = parseInt ( jQuery(this).val() );
	rest = opplag - behov;
/*
	if( rest >= 0)
	  produkt.removeClass('error');
	else
	  produkt.addClass('error');
*/

	produkt.find('td.rest').html( rest );
}