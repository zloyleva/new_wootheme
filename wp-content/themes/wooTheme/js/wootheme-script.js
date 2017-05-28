(function($){
	$(function(){

		// ajax_add_to_cart
		// single_add_to_cart_button

		$(document).on('click', '.ajax_add_to_cart', function(e){
			e.preventDefault();
			$('.fa-shopping-cart').css('color', 'red');
		});

	});
})(jQuery)