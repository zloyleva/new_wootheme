<?php

class productParser{

	static public $price_keys = [];
	static public $categories_keys = [];
	
	static function parce_data($data_rows){

		$offset_first_col = 1; //Offset for price file(First column). Slice first colunm
		$flag_record = '';
		$result = array();
		
		$price_keys = array();
		$categories = [];

		foreach ($data_rows as $i => $row) {
			$product = array();
			$temp = [];
			$filled_cell = 0;
			for($j = $offset_first_col; $j < count($row); $j++){
				//Find title by price
				if ($row[$j] == 'Номенклатура/ Характеристика номенклатуры'){
					$flag_record = true;
					// HERE >> set array with product key
					productParser::set_price_keys($row, $offset_first_col);
					continue 2;
				}
				if($row[$j]){$filled_cell++;}	// Counter for SET cell
				$temp[] =  $row[$j];			// Collect result
			}

			// // If $filled_cell 
			if($flag_record == true){

				if(1 == $filled_cell){
					$get_category_data = productParser::get_space_data($temp);
					$categories[$get_category_data['position']] = $get_category_data['value'];
					$categories = array_slice($categories, 0, $get_category_data['position']+1);

					$result['product_insert'][] = array( 'lenght'=>$get_category_data['position']+1 ,'categories_temp' => $categories);
				}
				if ($filled_cell >= 4) {

					$product_name = $temp[productParser::$price_keys['name']-1];
					$product_name = productParser::get_space_data([$product_name]);// Clear product name from white space
					
					$product['name'] = $product_name['value'];
					$product['stock'] = $temp[productParser::$price_keys['stock']-1];
					$product['sku'] = $temp[productParser::$price_keys['sku']-1];
					$product['content'] = ( isset( productParser::$price_keys['content'] ) )?($temp[productParser::$price_keys['content']-1]):''; // If empty - need get from name
					$product['short_desc'] = ( isset( productParser::$price_keys['short_desc'] ) )?$temp[productParser::$price_keys['short_desc']-1]:'';
					
					$product['price'] = $temp[productParser::$price_keys['price']-1];

					// Slice category by product position
					$categories_temp = array_slice($categories, 0, $product_name['position']);

					// Slice first item for next categories [Наша продукция, ] // Todo add here except
					if($categories_temp[0] == 'Наша продукция' || $categories_temp[0] == 'Продукция других производителей'){
						array_shift($categories_temp);
					}

					$product['categories'] = $categories_temp;
					$result['products'][] = $product;


				}

				// $result['product_insert'][] = array( 'name' => $product['name'] ,'categories_temp' => $categories_temp);
			}
		}

		$result['keys'] = productParser::$price_keys;
		return ($result);
	}

	/**
	* Set product KEYs
	*/
	static function set_price_keys($args, $offset_first_col){

		for ($i=$offset_first_col; $i<count($args) ; $i++) { 
			/**
			*    *'name', >>'short_desc', ?'content', *'sku', *'price', *'stock'
			'Номенклатура/ Характеристика номенклатуры'	'Остаток'	'Номенклатура.Артикул' 	'Номенклатура.Дополнительное описание номенклатуры'	'1. Розница'
			*/
			switch ($args[$i]) {
				case 'Номенклатура/ Характеристика номенклатуры':
					productParser::$price_keys['name'] = $i;
					break;
				case 'Остаток':
					productParser::$price_keys['stock'] = $i;
					break;
				case 'Номенклатура.Артикул ':
					productParser::$price_keys['sku'] = $i;
					break;
				case 'Номенклатура.Полное наименование':
					productParser::$price_keys['content'] = $i;
					break;
				case 'Номенклатура.Дополнительное описание номенклатуры':
					productParser::$price_keys['short_desc'] = $i;
					break;
				case ('1. Розница' || '3. Опт безналичные' || '4. VIP' || '8. опт'):
					productParser::$price_keys['price'] = $i;
					break;
			}
		}
	}

	static function get_space_data($args){
		$result = [];
		foreach ($args as $key => $value) {
			if($value){
				preg_match('/(\s*|\d\S+\s)(\d\S+\s)*(\W.+)/', $value, $matches);
				$result = array( 
					'position' => (strlen($matches[1]))/4, 
					'value' => $matches[3] 
				);
			}
		}
		return $result;
	}

}