
(function($){
	$(function(){

		$(document).submit('.form-add_products', function(event){
			event.preventDefault();
			var fileData = $('.get_price_file').prop('files')[0];
			var form = new FormData();
			if(!fileData){
				$('.show_results').text('Error. You need to select any price file!');

			}else{
				console.log(fileData.name);
				form.append('file', fileData);
				form.append('action', 'call_upload_price');
				form.append('temp', 'some text');
				$.ajax({
					url: ajaxurl,
					dataType: 'json',
					contentType: false,
                	processData: false,
                	data: form,                         
                	type: 'post',
                	success:function(response){
                		console.log(response);
                		$('.show_results').html('<p>The price <span class="file_name">'+ response.file_name +'</span> was successfuliiy downloaded</p>')
                		$('.get_price_file').prop('disabled', true);
                		$("input[value='Upload price']").prop('disabled', true).removeClass('button-primary').addClass('button-default');
                		$('.show_results').append('<p><input type="button" class="button button-primary insert_products" value="Parsed price file"></p>')
                	}
				});
			}
		});

		$(document).on('click', 'input.insert_products', function(event){
			event.preventDefault();
			var data = {
				action: 'call_read_price_file',
				file: $('span.file_name').text(),
				temp: 'temp text'
			}
			console.log('read file ' + data.file);
			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: data,
				success: function(response){

					// console.log(response);
					
					$("input[value='Parsed price file']").prop('disabled', true).removeClass('button-primary').addClass('button-default');
					$('.show_results').append('<div class="spiner"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></div>').append('<div class="product-count"></div>');
					console.log('go to insertProductsWoo');
					var finish = insertProductsWoo(response);
					console.log(finish);
				},
				error: function(error){
					console.log(error.responseText);
				}
			});
		});


		var getProductsArray; // Prepare global variable for product's array

		function insertProductsWoo(response){
			getProductsArray = response.products;
					
			// insert_chanck(getProductsArray[0], getProductsArray.length, 0);
			var x = insert_chanck(0);
			console.log(x);
			return 'finished all function';
		}

		function insert_chanck(i){

			console.log(getProductsArray[i]);

			// Send to insert HERE
			data = {
				action: 'call_insert_products',
				product: getProductsArray[i],
				step: i
			}
			$.ajax({
				url: ajaxurl,
				type: 'post',
				dataType: 'json',
				data: data,
				success: function(response){

					console.log(response);

					// Next products number
					step = i+1;

					// Check if its last product in list
					if(step < getProductsArray.length){

						$('.product-count').text(step+1); // Display counter

						// Call function to insert products
						insert_chanck(step);
					}else{
						console.log('Here we insert the Last product!!!');
						$('.spiner').text('We insert the Last product!!!')
					}
					// That's all - was last producst insert
					return;
				},
				error: function(error){
					console.log(error.responseText);
				}
			});

			return 'last element inserted';
		}

	});
})(jQuery)