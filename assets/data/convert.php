<?php

	$CsvFile = 'Prices.csv' ;


	if(file_exists($CsvFile)){
		$JSON = array();
		if (($handle = fopen($CsvFile, "r")) !== FALSE) {
			while (($Data = fgetcsv($handle, 0, ";")) !== FALSE) {
				$JSON[$Data[0]] = floatval(str_replace(',','.',$Data[1]));
			}
			fclose($handle);
		}
		$JSON = json_encode($JSON);
		file_put_contents('prices.json',$JSON);
		echo 'CONVERTED!' ;
	
	}

?>