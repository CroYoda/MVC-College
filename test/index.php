<?php

require ('../inc/cPogled.php');
require ('../inc/cModel.php');

$model = new Model();
$pogled = new Pogled();

if (isset($_POST['btnSubmit']))
	$model->putNoviStudent($_POST);
//var_dump($_POST);

//$studenti = $model->getPopisStudenata();
//$pogled->ispisiHTMLtablicu($studenti);

$mjesta = $model->getPopisGradova();
foreach ($mjesta as $mjesto) 
{
	$mjestaFormated[$mjesto['pbr']] = $mjesto['nazMjesto'];
}

//var_dump($mjestaFormated);

// PRIMJER POLJA ZA METODU ISPIS FORMA ZA UPISIVANJE U BAZU
$podacizaupis = array(	'Ime' 				=> '',
						'Prezime' 			=> 'fdghdf',
						'Datum rođenja' 	=> '__datum_r_',
						'Grad rođenja' 		=> $mjestaFormated,
						'JMBG' 				=> '',
						'Grad stanovanja' 	=> $mjestaFormated
						);

$pogled->ispisiFormZaUpis($podacizaupis);


exit(); 
// PRIMJER POLJA ZA METODU ISPIS HTML TABLICE
$drugi = array(	0 => array(	'mbr' 				=> 1120, 
				'Student' 			=> 'Ivić, Ivo', 
				'Datum rođenja' 	=> '13.10.1983.',
				'JMBG' 				=> '12345645',
				'Grad rođenja' 		=> 'Split',
				'Grad stanovanja' 	=> 'Zadar'),

		1 => array(	'mbr' 				=> 1120, 
				'Student' 			=> 'Ivić, Ivo', 
				'Datum rođenja' 	=> '13.10.1983.',
				'JMBG' 				=> '12345645',
				'Grad rođenja' 		=> 'Split',
				'Grad stanovanja' 	=> 'Zadar')
		);

$pogled->ispisiHTMLtablicu($drugi);

?>