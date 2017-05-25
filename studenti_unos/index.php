<?php
require ('../inc/cModel.php');
require ('../inc/cPogled.php');
require ('../inc/cUpravljac.php');

$upravljac = new Upravljac();
$upravljac->studenti_unos();

?>