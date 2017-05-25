<?php
require ('../inc/cModel.php');
require ('../inc/cPogled.php');
require ('../inc/cUpravljac.php');

$upravljac = new Upravljac();

if ( isset($_POST['btnSubmit']) )
	$upravljac->ispiti_pregled($_POST['student']);
else
	$upravljac->ispiti_pregled('');

?>