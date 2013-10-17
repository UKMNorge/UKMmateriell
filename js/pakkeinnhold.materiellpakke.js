jQuery(document).on('change', '.produkt input', function(){
	calc_rest( jQuery(this).parents('tr.produkt') );
});

function calc_rest(produkt) {
	behov = parseInt( produkt.find('td.behov').html() );
	opplag = parseInt ( jQuery(this).val() );
	rest = opplag - behov;
	if( rest >= 0)
	  produkt.removeClass('error');
	else
	  produkt.addClass('error');
	produkt.find('td.rest').html( rest );
}