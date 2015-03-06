<?php

define("EOL", "\r\n");

/* MS SQL Setup */
$server = "WWSV05\SQL_HELIOS";
$user = "hicad";
$password = "hicad";
$database = "bauteil";

$Debug = false;

/* Hicad Folder */
$folderSZA = '\\\\wwsvfs1\\data\\Grootvalk bv\\Hicad\\_Drawings\\';
$folderKRA = '\\\\wwsvfs1\\data\\Grootvalk bv\\Hicad\\_Parts_Referenced\\';
$folderPDF = '\\\\wwsvfs1\\data\\Grootvalk bv\\Hicad\\_PDF\\';

/* Article Card */
$_ART = array (
	'HEL_KOPFID' => '',
	'HEL_SACHNUMMER' => 'ART-000000000',
	'BEMERKUNG' => NULL,
	'BENENNUNG' => NULL
);
$_ART_Headers = array(
	'Aantal' => array(
		'XLS' => array(
			'POS' => 0,
			'Width'=> 74
		)
	),
	'Lengte' => array(
		'XLS' => array(
			'POS' => 1,
			'Width'=> 74
		)
	),
	'ART nummer' => array(
		'XLS' => array(
			'POS' => 2,
			'Width'=> 110
		)
	),
	'GRV nummer' => array(
		'XLS' => array(
			'POS' => 3,
			'Width'=> 110
		)
	),
	'Omschrijving' => array(
		'XLS' => array(
			'POS' => 4,
			'Width'=> 500
		)
	),
	'Norm' => array(
		'XLS' => array(
			'POS' => -1,
			'Width'=> 0
		)
	),
	'Prijs p/s' => array(
		'XLS' => array(
			'POS' => 5,
			'Width'=> 80
		)
	),
	'Prijs T' => array(
		'XLS' => array(
			'POS' => 6,
			'Width'=> 80
		)
	),
);
/* Article Card */
$_GRV = array (
	'HEL_KOPFID' => '',
	'HEL_SACHNUMMER' => '',
	'BEMERKUNG' => '0000000000',
	'BENENNUNG' => NULL
);

$noCountRegEx = array ();
$noCountRegEx [] = '/;704[0-9]{7};/i'; // 704 xxx xxxx - Staf Staal
$noCountRegEx [] = '/;720[0-9]{7};/i'; // 702 xxx xxxx - Buis RVS
$noCountRegEx [] = '/;6150380050;/i'; //  702 xxx xxxx - Buis RVS
$noCountRegEx [] = '/;6150500062;/i'; //  carboflex/grecato 50x62
$noCountRegEx [] = '/;6150600010;/i'; //  Heduflex/10 073x6,5 i=60mm Buigradius 300mm


/* Create Connection */
$dbh = odbc_connect ( "Driver={SQL Server Native Client 10.0};Server=$server;Database=$database;", $user, $password );


$DuplicteNorm = array();
$DuplicteNormFile = $_SERVER ['DOCUMENT_ROOT'] . "\HELiOS\assets\data\DuplicteNorm.xml";
if (file_exists ( $DuplicteNormFile )) {
	$Content = file_get_contents ( $DuplicteNormFile ) ;
	$xml = simplexml_load_string($Content); 
	$result = $xml->xpath('norm');
	while(list( , $node) = each($result)) {
		$DuplicteNorm[] = "$node" ;
	}
}

?>