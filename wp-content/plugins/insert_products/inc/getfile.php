<?php 

/**
* 
*/
class GetPriceFileData
{

	function __construct(){
		require_once dirname(__FILE__) . '/PHPExel/Classes/PHPExcel.php';
	}
	
	static function read_price( $inputFileName ){
		

		$inputFileType = PHPExcel_IOFactory::identify( $inputFileName);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objPHPExcel = $objReader->load($inputFileName);

	    // Get first sheet(0)
	    $sheet = $objPHPExcel->getSheet(0);
	    
	    // // Collect data to array
	    $exl_data = $objPHPExcel->getActiveSheet()->toArray();

		return ( $exl_data );
	}
}
new GetPriceFileData();