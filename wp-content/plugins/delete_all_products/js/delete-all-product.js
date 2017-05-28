/**
 * Delete all products: Plugin's JS
 */

(function ($) {
    $(function () {

        $(document).submit('.form-delete_all_products', function (event) {
            event.preventDefault();

            console.log('ADMIN AJAX');

            var data = {
            	action: 'call_delete_products',
            };

            $.ajax({
            	url     :   ajaxurl,
                method  :   'post',
                data    :   data,
                success :   function (responce) {
                    console.log(responce);
                    $('.show_results').text( responce );
				}
            });
        })
    });
})(jQuery);