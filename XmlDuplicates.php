<?php

	/* Loading Files */
	require_once ('assets/php/load.php');
	
	if($Member==true){
		if( isset( $_POST['Action'] ) && isset( $_POST['Norm'] ) ){
			if( $_POST['Action'] != '' && $_POST['Norm'] != ''  ) {

				$A = $_POST['Action'] ;
				$N = $_POST['Norm'] ;
				
				if($A == 'ADD' ){
					if(! in_array( $N, $DuplicteNorm ) ){
						$DuplicteNorm[] = $N ;
					}				
				}
				if($A == 'DEL' ){
					if( in_array( $N, $DuplicteNorm ) ){
						foreach( $DuplicteNorm as $K=>$V){
							if($N==$V){
								unset($DuplicteNorm[$K]);
							}
						}
					}				
				}
				
				asort($DuplicteNorm);
				
				$xml = new SimpleXMLElement('<root/>');
				foreach( $DuplicteNorm as $V ){
					$xml->addChild('norm', $V);
				}
				
				$dom = new DOMDocument('1.0');
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;
				$dom->loadXML($xml->asXML());
				file_put_contents($DuplicteNormFile,$dom->saveXML() );
				echo 'OK' ;
				
				
			}
		}
	}
	

?>