jQuery(document).on('change', '.produkt input', function(){
	calc_rest( jQuery(this).parents('tr.produkt') );
});

jQuery(document).ready(function() {
	jQuery('.produkt').each(function(){
		calc_rest( jQuery(this) );
	})
});

function calc_rest(produkt) {
	behov = parseInt( produkt.find('td.behov').html() );
	
	if(produkt.attr('id') == '24' ) {
		behov = behov + parseInt( jQuery('#ekstradiplom_lokal').html() );
	}
	if(produkt.attr('id') == '25' ) {
		behov = behov + parseInt( jQuery('#ekstradiplom_fylke').html() );
	}
	
	opplag = parseInt ( produkt.find('td.opplag input').val() );
	rest = opplag - behov;
	if( rest >= 0) {
		if( rest > 300 )
			produkt.removeClass('error').addClass('warning');
		else
			produkt.removeClass('error').removeClass('warning');
	} else {
		produkt.addClass('error');
	}
	produkt.find('td.rest').html( rest );
}