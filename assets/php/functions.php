<?php

function GetHelKopfID($A){
	global $dbh ;
	$Query = "SELECT * FROM [bauteil].[hicad].[BAUTEILKOPF] WHERE HEL_SACHNUMMER = '" . $A . "'";
	$STH = tableQuery ( $dbh, $Query, true );
	if(isset($STH['HEL_KOPFID'])){
		return $STH['HEL_KOPFID'] ;
	}
	if(isset($STH[0])){
		if(isset($STH[0]['HEL_KOPFID'])){
			return $STH[0]['HEL_KOPFID'] ;
		}
	}
}
/**
 * Create the default HTML Head
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *
 */
function CreateHtmlHead() {
	include ('head.html.php');
}
/**
 * Create the default HTML Navigation bar (TOP)
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *
 */
function CreateHtmlNavbar() {
	include ('navbar.html.php');
}
/**
 * The Function Description 
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *
 * @param string $S 
 * 			String to start each line with
 * @param array $Headers
 * 			The array for the table Headers
 * @param array $Items
  * 		The array for the table rows
 * @param unknown $ID
 * 			The Table HTML ID Tag
 * @return string
 * 			A proper HTML table
 */
function CreateHtmlTableArray($S, $Headers, $Items, $ID) {
	// Make sure there are items
	if (count ( $Items ) == 0) {
		return '';
	}
	$Result = '';
	$Result .= $S . '<table id="' . $ID . '" class="table table-hover">' . EOL;
	$Result .= $S . '  <thead>' . EOL;
	$Result .= $S . '    <tr>' . EOL;
	// Create all Headers
	foreach ( $Headers as $K=>$V ) {
		// Check for 'Norm'
		if ($K != 'Norm') {
			$Result .= $S . "      <th>$K</th>" . EOL;
		} else {
			// Hide 'Norm'
			$Result .= $S . "      <th style=\"display:none\">$K</th>" . EOL;
		}
	}
	$Result .= $S . '    </tr>' . EOL;
	$Result .= $S . '  </thead>' . EOL;
	$Result .= $S . '  <tbody>' . EOL;
	// Generate all rows
	foreach ( $Items as $Row ) {
		$Result .= $S . '    <tr>' . EOL;
		if($Row['Prijs p/s']!==''){
			$Row['Prijs p/s'] = '€ ' . number_format ( $Row['Prijs p/s'], 2, ',', '.' );
			$Row['Prijs T'] = '€ ' . number_format ( $Row['Prijs T'], 2, ',', '.' );
		}
		foreach ( $Row as $TDK => $TD ) {
			// Check for 'Norm'
			if ($TDK == 'Norm') {
				// Hide with(out) class tag
				if ($TD != '') {
					$Result .= $S . "      <td style=\"display:none\" class=\"$TDK\">$TD</td>" . EOL;
				} else {
					$Result .= $S . "      <td style=\"display:none\" >$TD</td>" . EOL;
				}
			} else if ($TDK == 'ART nummer') {
				$Result .= $S . "      <td><a href=\"ART/?q=$TD\">$TD</a></td>" . EOL;
			} else if ($TDK == 'GRV nummer' && $TD != '') {
				$Result .= $S . "      <td><a href=\"GRV/?q=$TD\">$TD</a></td>" . EOL;
			} else {
				$Result .= $S . "      <td>$TD</td>" . EOL;
			}
		}
		$Result .= $S . '    </tr>' . EOL;
	}

	$Result .= $S . '  </tbody>' . EOL;
	$Result .= $S . '</table>' . EOL;
	// Return the Table
	return $Result;
}
/**
 * Debug Array in HTML
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *
 * @param array $Array 
 * 			The array to be secretly displayed
 */
function DebugArray($Name, $Array) {
	// Load global Debug settings
	global $Debug;
	// Debug $Structure
	if ($Debug === true) {
		echo "<!--" . EOL;
		echo "$Name - ";
		print_r ( $Array );
		echo "-->" . EOL;
	}
}
/**
 * Download table as CSV format
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *
 * @param array $Array 
 * 			The array to be converted to CSV format
 * @param string $Filename 
 * 			The filename which it should be named to
 */
function Download_CSV($Headers, $Array, $Filename) {
	// Clean output buffer
	ob_end_clean ();
	// open raw memory as file so no temp files needed
	$f = fopen ( 'php://memory', 'w' );
	
	foreach ( $Headers as $K=>$line ) {
		if($K == 'Norm'){
			unset($Headers[$K]);
		}
	}
	
	
	// Add File headers
	fputcsv ( $f, array_keys($Headers), ";" );
	// loop over the input array
	foreach ( $Array as $line ) {
		// forget "Norm"
		unset($line['Norm']);
		// generate csv lines from the inner arrays
		fputcsv ( $f, $line, ";" );
	}
	// "rewrind" the "file"
	fseek ( $f, 0 );
	// tell the browser it's going to be a csv file
	header ( 'Content-Type: application/csv' );
	// tell the browser we want to save it instead of displaying it
	header ( 'Content-Disposition: attachment; filename="' . $Filename . '.csv";' );
	// make php send the generated csv lines to the browser
	fpassthru ( $f );
	// Stop further execution of the script
	die ();
}
function Download_XLS($Headers, $Array, $Filename) {
	// Clean output buffer
	ob_end_clean ();
	$File = __DIR__ . '/plugins/PHPExcel.php';
	if(!file_exists( $File)){
		die("PHP Excel class could not be loaded");
	}
	require_once( $File );

	
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("HELiOS h4x0r");
	$objPHPExcel->getProperties()->setLastModifiedBy("HELiOS h4x0r");
	$objPHPExcel->getProperties()->setTitle($Filename);
	$objPHPExcel->getProperties()->setSubject("Artikel kaart");
	//$objPHPExcel->getProperties()->setDescription("Artikel kaart: $Filename");
	//$objPHPExcel->getProperties()->setKeywords("HiCAD HELiOS php");
	//$objPHPExcel->getProperties()->setCategory("HiCAD HELiOS");

	// Remove 'Norm'
	foreach($Headers as $K=>$V){
		if($V=='Norm'){
			unset($Headers[$K]);
		}
	}
	// In Pixels
	$ColomnWidth = array(
	);
	// Add headers 
	foreach($Headers as $K=>$V){
			$Pos = $V['XLS']['POS'] ;
			$Width = $V['XLS']['Width'] / 7 ;
			// Skip these
			if( $Pos == -1 ){
				continue;
			}
			// Value
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($Pos,1, $K);
			// Styling
			$objPHPExcel->setActiveSheetIndex(0)->getCellByColumnAndRow($Pos,1)->getStyle()->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0)->getColumnDimensionByColumn($Pos)->setWidth($Width);
	} 
	//echo EOL.EOL.EOL.EOL.EOL.EOL.EOL.EOL.EOL;
	//die('TEST');

	// Loop through each Line
	foreach( $Array as $K=>$V){
		$XlsLine = $K + 2 ;
		unset($V['Norm']);
		// Pass each Line's Value
		foreach(array_values($V) as $K2=>$V2){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($K2,$XlsLine, utf8_encode("$V2"));
		}

	}

	
	// Filter
	$objPHPExcel->getActiveSheet()->setAutoFilter('A1:'.$objPHPExcel->getActiveSheet()->getHighestDataColumn().'1');
	
	// resize Columns
	foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
		//$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
	} 
	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle($Filename);


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);


	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$Filename.'.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	die();

	
}
/**
 * Get an URL Parameter
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *
 * @param string $x 
 * 			the key to be found
 * @return string|boolean
 * 			The value of the Key or false when fail
 */
function GetGet($x) {
	// Check if the Key existst
	if (isset ( $_GET [$x] )) {
		// return the key
		return $_GET [$x];
	}
	// Return false, not fount
	return false;
}
/**
 * Get an Price of an GRV/ART article 
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *
 * @param string $Grv
 * 			The GRV article Code
 * @param string $Art
 * 			The ART article Code
 * @return float | string
 */
Function GetPrice($Grv,$Art) {
	// Check if Prices are not loaded
	if (! isset ( $GLOBALS ['WisPrices'] )) {
		// Prices file
		$File = $_SERVER ['DOCUMENT_ROOT'] . "\HELiOS\assets\data\prices.json";
		// Data
		$Data = '';
		// Check if File exists
		if (file_exists ( $File )) {
			// Content( JSON ) -> Array
			$Data = json_decode ( file_get_contents ( $File ), true );
		}
		// Store prices
		$GLOBALS ['WisPrices'] = $Data;
	}
	$P = $GLOBALS ['WisPrices'];
	// Return Prices if found
	return (isset ( $P [$Grv] ) ? $P [$Grv] : (isset ( $P [$Art] ) ? $P [$Art] : '') );
}





function HeliosCreateStructureList($HEL_KOPFID){
	
	// Load settings
	global $dbh;
	global $Debug;
	
	// Load Product Structure
	$Query = "SELECT * FROM [bauteil].[hicad].[PRODUKT] WHERE HEL_BTID = '" . $HEL_KOPFID . "'";
	$Structure = tableQuery ( $dbh, $Query );
	//print_r($Structure);

	
	$Table = array ();
	if (is_array ( $Structure )) {
		// loop through the Structure items and create the Table Array
		foreach ( $Structure as $K => $V ) {
			// Load the ART card - Main
			$Query = "SELECT * FROM [bauteil].[hicad].[BAUTEILKOPF] WHERE HEL_KOPFID = '" . $V ['HEL_SUBBTID'] . "'";
			$STH = tableQuery ( $dbh, $Query, true );

			echo EOL ;
			// Store Item Parameters
			$ItemNorm = $STH ['HEL_NORMID'];
			$ItemART = $STH ['HEL_SACHNUMMER'];
				
			// Create Link element
			$Link = '<a href="ART/?q=' . $STH ['HEL_SACHNUMMER'] . '">' . $STH ['HEL_SACHNUMMER'] . "</a>";
				
			// Load the ART card - Main
			$Query = "SELECT * FROM [bauteil].[hicad].[BAUTEILREVISION] WHERE HEL_REVKOPFID = '" . $V ['HEL_SUBBTID'] . "'";
			$STH = tableQuery ( $dbh, $Query, true );
			// Store Item Parameters
			$ItemGRV = $STH ['BEMERKUNG']; // ( $STH ['BEMERKUNG'] != '' ? $STH ['BEMERKUNG'] : '-' ) ;
			$ItemDesc = $STH ['BENENNUNG'];
			//print_r($V);
			$Table [] = array (
					'Aantal' => '',
					'Lengte' => ( $V['LAENGE'] != NULL ? $V['LAENGE'] : '' ),
					'ART nummer' => $ItemART,
					'GRV nummer' => $ItemGRV,
					'Omschrijving' => $ItemDesc,
					'Norm' => $ItemNorm,
					'Prijs p/s' => '',
					'Prijs T' => ''
			);
		}
		// Load Duplicate Norms
		global $DuplicteNorm;
		// What to 'Skip'
		$Skip = array ();
		// Count the Table Array
		foreach ( $Table as $K => $V ) {
			// 'ART' must be defined
			if (! isset ( $V ['ART nummer'] )) {
				continue;
			}
			// if the norm is found in the Setup
			if (in_array ( $V ['Norm'], $DuplicteNorm ) || $V ['Norm'] == '') {
				// Check if the current item is not 'skipped'
				if (! in_array ( $V ['ART nummer'], $Skip )) {
					// Add current item to the skip list
					$Skip [] = $V ['ART nummer'];
					// Start counting
					$Table [$K] ['Aantal'] = 0;
					// Reloop through all items
					foreach ( $Table as $K2 => $V2 ) {
						// Check iF 'ART' is a match
						if ($V ['ART nummer'] == $V2 ['ART nummer']) {
							// add 1 to the counter
							$Table [$K] ['Aantal'] ++;
						}
					}
				} else {
					// Should be skipped so also deleted
					unset ( $Table [$K] );
				}
			}
		}
	}
	
	// Sort the Table by 'GRV'
	usort ( $Table, "UsortGRV" );
	$Result = array ();
	
	// Find 'GRV' articles
	foreach ( $Table as $K => $V ) {
		if ($V ['GRV nummer'] != '') {
			$Result [] = $V;
			unset ( $Table [$K] );
		}
	}
	// Sort Table by 'ART'
	usort ( $Table, "UsortART" );
	// Move each Item
	foreach ( $Table as $K => $V ) {
		$Result [] = $V;
		unset ( $Table [$K] );
	}
	
	// Add prices
	foreach ( $Result as $K => $V ) {
		$PE = GetPrice ( $V ['GRV nummer'], $V ['ART nummer'] );
		if ($V ['Aantal'] != '' && $PE != '') {
			$Result [$K] ['Prijs p/s'] = $PE ;
			$Result [$K] ['Prijs T'] = $PE * $V ['Aantal'] ;
			//$Result [$K] ['PE'] = '€ ' . number_format ( $PE, 2, ',', '.' );
			//$Result [$K] ['PT'] = '€ ' . number_format ( $PE * $V ['Aantal'], 2, ',', '.' );
		}else if( $V ['Aantal'] == '' ){  
			$Result [$K] ['Lengte'] = '-' ;
		}
	}
	
	// return the table
	return $Result ;
	
}














/**
 * Usort() sorting function for ART 
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *
 * @param array $a 
 * 			Array A to be compared
 * @param array $b
 * 			Array B to be compared
 * @return integer 
 * 			Result of sorting
 */
function UsortART($a, $b) {
	return strcmp ( $a ["ART nummer"], $b ["ART nummer"] );
}
/**
 * Usort() sorting function for GRV
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *        
 * @param array $a
 *        	Array A to be compared
 * @param array $b
 *        	Array B to be compared
 * @return integer 
 * 			Result of sorting
 */
function UsortGRV($a, $b) {
	return strcmp ( $a ["GRV nummer"], $b ["GRV nummer"] );
}



function Module_ART($S) {
	// Create a Result variable
	$Result = '';
	// Load the Query
	$q = GetGet ( 'q' );
	// Check if the user submitted the form
	if ($q === false) {
		return '';
	}
	// Check if the user submitted an empty string
	if ($q === '') {
		return "    <pre>Voer een geldige code in</pre>";
	}
	// Load settings
	global $_ART;
	global $dbh;
	global $Debug;
	global $folderPDF;
	global $folderSZA;
	global $folderKRA;
	
	// Create the Query 'ART-000000000' replace $q at the end
	$_ART ['HEL_SACHNUMMER'] = substr ( $_ART ['HEL_SACHNUMMER'], 0, - strlen ( $q ) ) . $q;
	
	// Search: Article Card
	$Query = "SELECT * FROM [bauteil].[hicad].[BAUTEILKOPF] WHERE HEL_SACHNUMMER = '" . $_ART ['HEL_SACHNUMMER'] . "'";
	$STH = tableQuery ( $dbh, $Query, true );
	
	// Check if the Card is fount
	if ($STH == NULL) {
		return $S . "<pre>Artikel kaart niet gevonden: " . $_ART ['HEL_SACHNUMMER'] . "</pre>";
	}
	
	// Store Unique ID of the Aricle
	$_ART ['HEL_KOPFID'] = $STH ['HEL_KOPFID'];
	
	// Search Article Card Info
	$Query = "SELECT * FROM [bauteil].[hicad].[BAUTEILREVISION] WHERE HEL_REVKOPFID = '" . $_ART ['HEL_KOPFID'] . "'";
	$STH = tableQuery ( $dbh, $Query, true );
	
	// Check if the Card is fount
	if ($STH == NULL) {
		return $S . "<pre>Datbase Error: HEL_REVKOPFID not found(" . $_ART ['HEL_KOPFID'] . ")</pre>";
	}
	
	// Store Description and Grv Article number
	$_ART ['BENENNUNG'] = $STH ['BENENNUNG'];
	$_ART ['BEMERKUNG'] = $STH ['BEMERKUNG'];
	
	// Search: SZA & KRA File(s)
	$Query = "SELECT * FROM [bauteil].[hicad].[BAUTDOKUREVREV] WHERE HEL_QUELLID = '" . $_ART ['HEL_KOPFID'] . "'";
	$STH = tableQuery ( $dbh, $Query );
	
	// Check if there are any records found ( SZA / KRA )
	if (is_array ( $STH )) {
		// Loop throught the SZA & KRA records
		foreach ( $STH as $K => $V ) {
			// Lookup the SZA/KRA information card
			$Query = "SELECT * FROM [bauteil].[hicad].[DOKUMENTREVISION] WHERE HEL_REVKOPFID = '" . $V ['HEL_ZIELID'] . "'";
			$STH = tableQuery ( $dbh, $Query, true );
			
			// Check which Extension it is: SZA/KRA
			$ext = substr ( $STH ['HEL_DATEINAME'], - 3 );
			// Store data in main Article Card
			$_ART [$ext] = array (
					'HEL_REVKOPFID' => $STH ['HEL_REVKOPFID'],
					'file' => ($ext == 'SZA' ? $folderSZA : $folderKRA) . $STH ['HEL_DATEINAME'],
					'datec' => $STH ['HEL_GEAENDERT_DATUM'],
					'datem' => $STH ['HEL_FILE_GEAENDERT_DATUM'],
					'_Query' => $Query 
			);
		}
	}
	
	$_art = $_ART ['HEL_SACHNUMMER'];
	$_grv = $_ART ['BEMERKUNG'];
	$_des = $_ART ['BENENNUNG'];
	$_pdf = "$folderPDF$_art\\$_art.tek.pdf";
	// Create Head Info
	$Result .= $S . "<pre>";
	$Result .= "Hoofd onderdeel  : <a href=\"ART/?q=$_art\">$_art</a>" . EOL;
	$Result .= "artikel Grootvalk: <a href=\"GRV/?q=$_grv\">$_grv</a>" . EOL;
	$Result .= "Omschrijving     : $_des" . EOL;
	// / Not fully supported at this moment: No File Check
	$Result .= "PDF bestand      : $_pdf " . EOL;
	$Result .= "SZA bestand loc. : " . (isset ( $_ART ['SZA'] ) ? "<font id=\"SzaClipBoard\">".$_ART ['SZA'] ['file']."</font>" : '') . EOL;
	$Result .= "KRA bestand loc. : " . (isset ( $_ART ['KRA'] ) ? $_ART ['KRA'] ['file'] : '') . EOL;
	$Result .= "</pre>" . EOL;
	$Result .= $S . "<br>" . EOL;
	
	
	$HeliosStructure = HeliosCreateStructureList($_ART ['HEL_KOPFID']);
	
	global $_ART_Headers;
	if ((GetGet ( 'download' ) === 'true') == true) {
		
		if(GetGet ( 'format' ) == 'xls' ){
			Download_XLS ( $_ART_Headers, $HeliosStructure, $_ART ['HEL_SACHNUMMER'] );
		}else{
			Download_CSV ( $_ART_Headers, $HeliosStructure, $_ART ['HEL_SACHNUMMER'] );
		}
	}
	$Result .= CreateHtmlTableArray ( $S, $_ART_Headers, $HeliosStructure, 'dataTable' );
	
	return $Result;
}
function Module_ART_TREE($S) {
	// Create a Result variable
	$Result = '';
	// Load the Query
	$q = GetGet ( 'q' );
	// Check if the user submitted the form
	if ($q === false) {
		return '';
	}
	// Check if the user submitted an empty string
	if ($q === '') {
		return "    <pre>Voer een geldige code in</pre>";
	}
	// Load settings
	global $_ART;
	global $dbh;
	global $Debug;
	global $folderPDF;
	global $folderSZA;
	global $folderKRA;
	
	// Create the Query 'ART-000000000' replace $q at the end
	$_ART ['HEL_SACHNUMMER'] = substr ( $_ART ['HEL_SACHNUMMER'], 0, - strlen ( $q ) ) . $q;
	
	// Search: Article Card
	$Query = "SELECT * FROM [bauteil].[hicad].[BAUTEILKOPF] WHERE HEL_SACHNUMMER = '" . $_ART ['HEL_SACHNUMMER'] . "'";
	$STH = tableQuery ( $dbh, $Query, true );
	
	// Check if the Card is fount
	if ($STH == NULL) {
		return $S . "<pre>Artikel kaart niet gevonden: " . $_ART ['HEL_SACHNUMMER'] . "</pre>";
	}
	
	// Store Unique ID of the Aricle
	$_ART ['HEL_KOPFID'] = $STH ['HEL_KOPFID'];
	
	// Search Article Card Info
	$Query = "SELECT * FROM [bauteil].[hicad].[BAUTEILREVISION] WHERE HEL_REVKOPFID = '" . $_ART ['HEL_KOPFID'] . "'";
	$STH = tableQuery ( $dbh, $Query, true );
	
	// Check if the Card is fount
	if ($STH == NULL) {
		return $S . "<pre>Datbase Error: HEL_REVKOPFID not found(" . $_ART ['HEL_KOPFID'] . ")</pre>";
	}
	
	// Store Description and Grv Article number
	$_ART ['BENENNUNG'] = $STH ['BENENNUNG'];
	$_ART ['BEMERKUNG'] = $STH ['BEMERKUNG'];
	
	// Search: SZA & KRA File(s)
	$Query = "SELECT * FROM [bauteil].[hicad].[BAUTDOKUREVREV] WHERE HEL_QUELLID = '" . $_ART ['HEL_KOPFID'] . "'";
	$STH = tableQuery ( $dbh, $Query );
	
	// Check if there are any records found ( SZA / KRA )
	if (is_array ( $STH )) {
		// Loop throught the SZA & KRA records
		foreach ( $STH as $K => $V ) {
			// Lookup the SZA/KRA information card
			$Query = "SELECT * FROM [bauteil].[hicad].[DOKUMENTREVISION] WHERE HEL_REVKOPFID = '" . $V ['HEL_ZIELID'] . "'";
			$STH = tableQuery ( $dbh, $Query, true );
				
			// Check which Extension it is: SZA/KRA
			$ext = substr ( $STH ['HEL_DATEINAME'], - 3 );
			// Store data in main Article Card
			$_ART [$ext] = array (
					'HEL_REVKOPFID' => $STH ['HEL_REVKOPFID'],
					'file' => ($ext == 'SZA' ? $folderSZA : $folderKRA) . $STH ['HEL_DATEINAME'],
					'datec' => $STH ['HEL_GEAENDERT_DATUM'],
					'datem' => $STH ['HEL_FILE_GEAENDERT_DATUM'],
					'_Query' => $Query
			);
		}
	}
	
	$_art = $_ART ['HEL_SACHNUMMER'];
	$_grv = $_ART ['BEMERKUNG'];
	$_des = $_ART ['BENENNUNG'];
	$_pdf = "$folderPDF$_art\\$_art.tek.pdf";
	// Create Head Info
	$Result .= $S . "<pre>";
	$Result .= "Hoofd onderdeel  : <a href=\"ART/?q=$_art\">$_art</a>" . EOL;
	$Result .= "artikel Grootvalk: <a href=\"GRV/?q=$_grv\">$_grv</a>" . EOL;
	$Result .= "Omschrijving     : $_des" . EOL;
	// / Not fully supported at this moment: No File Check
	$Result .= "PDF bestand      : $_pdf " . EOL;
	$Result .= "SZA bestand loc. : " . (isset ( $_ART ['SZA'] ) ? $_ART ['SZA'] ['file'] : '') . EOL;
	$Result .= "KRA bestand loc. : " . (isset ( $_ART ['KRA'] ) ? $_ART ['KRA'] ['file'] : '') . EOL;
	$Result .= "</pre>" . EOL;
	$Result .= $S . "<br>" . EOL;
	
	
	$Structure = HeliosCreateStructureList($_ART ['HEL_KOPFID']);
	
	
	$Tree = '' ;
	$Tree .= $S . '<ul class="tree">' . EOL ;
	$Tree .= $S . "  <li ART=\"$_art\" GRV=\"$_grv\" DESC=\"$_des\" COUNT=\"\" PRICE=\"\"><a href=\"ART/?q=$_art\">$_art</a> $_des" . EOL ;
	$Tree .= $S . '    <ul>' . EOL ;
	$Tree .= Module_ART_TREE_Recursive($S."    ",$Structure) ;
	$Tree .= $S . '    </ul>' . EOL ;
	$Tree .= $S . '  </li>' . EOL ;
	$Tree .= $S . '</ul>' . EOL ;
	
	$Result .= $Tree ;
	return $Result ;

}

function Module_ART_TREE_Recursive($S,$Structure){
	$Result = '' ;
	
	foreach( $Structure as $K=>$V){
		
		$C = sprintf('%02d',( $V['Aantal'] == '' ? 1 : $V['Aantal'] )) . 'x' ;
		$CO = $V['Aantal'] ;
		$A = $V['ART nummer'] ;
		$G = $V['GRV nummer'] ;
		$D = $V['Omschrijving'] ;
		$P = GetPrice($A, $G);
		$Sub = HeliosCreateStructureList( GetHelKopfID($A) );
		
		$Result .= $S . "  <li ART=\"$A\" GRV=\"$G\" DESC=\"$D\" COUNT=\"$CO\" PRICE=\"$P\">$C <a href=\"ART/?q=$A\">$A</a> $D" ;
		if(is_array($Sub) && count($Sub)>0){
			$Result .= EOL ;
			$Result .= $S . "    <ul>" . EOL ;
			$Result .= Module_ART_TREE_Recursive($S . "    ",$Sub) ;
			$Result .= $S . "    </ul>" . EOL ;			
			$Result .= $S . "  </li>" . EOL ;
		}else{
			$Result .= "</li>" . EOL ;
		}
		
		
	}
	return $Result ;
}



function Module_GRV($S) {
	// Create a Result variable
	$Result = '';
	// Load the Query
	$q = GetGet ( 'q' );
	// Check if the user submitted the form
	if ($q === false) {
		return '';
	}
	// Check if the user submitted an empty string
	if ($q === '') {
		return $S . "<pre>Voer een geldige code in</pre>";
	}
	// Load settings
	global $dbh;
	global $_GRV;
	if (strlen ( $q ) !== 10) {
		return $S . "<pre>Voer 10 cijferige code in</pre>";
	}
	
	// Load the ART card - Main
	$Query = "SELECT * FROM [bauteil].[hicad].[BAUTEILREVISION] WHERE BEMERKUNG = '" . $q . "'";
	$STH = tableQuery ( $dbh, $Query, true );
	
	iF (! isset ( $STH ['HEL_REVKOPFID'] )) {
		if (isset ( $STH [0] ) && is_array ( $STH [0] )) {
			$Double = array ();
			foreach ( $STH as $K => $V ) {
				// Search: Article Card
				$Query = "SELECT * FROM [bauteil].[hicad].[BAUTEILKOPF] WHERE HEL_KOPFID = '" . $V ['HEL_REVKOPFID'] . "'";
				$STH = tableQuery ( $dbh, $Query, true );
				$Double [] = $STH ['HEL_SACHNUMMER'];
			}
			
			// print_r($STH);
			return $S . "<pre>'$q' is meerdere malen toegekend (" . implode ( ',', $Double ) . ")</pre>";
		} else {
			return $S . "<pre>GRV Artikel niet gevonden in HELiOS</pre>";
		}
	}
	
	// Search: Article Card
	$Query = "SELECT * FROM [bauteil].[hicad].[BAUTEILKOPF] WHERE HEL_KOPFID = '" . $STH ['HEL_REVKOPFID'] . "'";
	$STH = tableQuery ( $dbh, $Query, true );
	
	iF (! isset ( $STH ['HEL_SACHNUMMER'] )) {
		if (isset ( $STH [0] ) && is_array ( $STH [0] )) {
			return $S . "<pre>ART Artikel niet gevonden in HELiOS ???</pre>";
		} else {
			return $S . "<pre>ART Artikel niet gevonden in HELiOS</pre>";
		}
	}
	
	$_GET ['q'] = $STH ['HEL_SACHNUMMER'];
	
	echo Module_ART ( $S );
}
/**
 * Execute an Query at the database
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *        
 * @param resource $resource        	
 * @param string $Query        	
 * @param boolean $Single        	
 * @return boolean array
 */
function tableQuery($resource, $Query, $Single = false) {
	// Load the Debug setting
	global $Debug;
	if ($Debug === true) {
		echo "<!-- $Query -->" . EOL;
	}
	// Execute the query on the Database
	$Query = odbc_exec ( $resource, $Query );
	// Return false when query fails somehow
	if ($Query == false) {
		return false;
	}
	// Create the Results Variable
	$Result = array ();
	// Loop through all rows
	while ( $Row = odbc_fetch_array ( $Query ) ) {
		// Store each row
		$Result [] = $Row;
	}
	// Return Single array instead of MultiArray when 1 row is found
	if (! isset ( $Result [1] ) && $Single !== false) {
		$Result = $Result [0];
	}
	// Return the row(s)
	return $Result;
}
/**
 * Load all Tables of the Database
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *        
 * @param resource $resource        	
 * @return array | boolean
 */
function tablesList($resource) {
	// Create a Result variable
	$Result = array ();
	// Check if the correct Type has been provided
	if (gettype ( $resource ) !== 'resource') {
		// Return false
		return false;
	}
	// Loop through Resource rows
	while ( odbc_fetch_row ( $resource ) ) {
		// Check for a tale
		if (odbc_result ( $resource, "TABLE_TYPE" ) == "TABLE") {
			// Store the Table name
			$Result [] = odbc_result ( $resource, "TABLE_NAME" );
		}
	}
	// Return Results
	return $Result;
}
/**
 * Check if the user is logged in
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *        
 * @return boolean
 */
function UserLoggedIn() {
	// Check if the SESSION key is set
	if (! isset ( $_SESSION ['Login'] )) {
		// Not logged in
		return false;
	}
	// Return bool if the Key matches this value
	return ($_SESSION ['Login'] == '29142aa87babc73fea5109dc49fc52a954a82e2a7a9aa11cd08708329576a5ddbde89d7ba1e79e83ca7585844e594e9ee86c757751c935af37d315fe69c1bd6b');
}
/**
 * Log the user in
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *        
 * @param string $getUser        	
 * @param string $getPass        	
 * @return boolean|string
 */
function UserLogin($getUser, $getPass) {
	// Check if All Data is present
	if (! isset ( $_POST [$getUser] ) || ! isset ( $_POST [$getPass] )) {
		// You are not logged in
		return false;
	}
	// Store Username & Password
	$u = $_POST [$getUser];
	$p = hash ( 'sha512', $_POST [$getPass] );
	// Username is fixed to this
	if ($u !== 'grootvalk') {
		return 'Gebruikersnaam niet bekend';
	}
	// Password is fixed to this (5145pd)
	if ($p !== '29142aa87babc73fea5109dc49fc52a954a82e2a7a9aa11cd08708329576a5ddbde89d7ba1e79e83ca7585844e594e9ee86c757751c935af37d315fe69c1bd6b') {
		return 'Wachtwoord is onjuist';
	}
	// Store Credentials
	$_SESSION ['Login'] = $p;
	// You are now logged in
	return true;
}
/**
 * Log the current user out
 *
 * @version 1
 * @author Rick de Man <rick@rickdeman.nl>
 *        
 */
function UserLogout() {
	// Delete the key
	unset ( $_SESSION ['Login'] );
}
?>