<?php

class Upravljac
{
	public $model;
	public $pogled;
	
	function __construct()
	{
		$this->model = new Model();
		$this->pogled = new Pogled();
	}
	
	function izbornik()
	{
		$dirs = scandir('.');
		foreach ( $dirs as $dir )
		{
			if ( is_dir($dir) && $dir != '.' && $dir != '..' && $dir != 'inc' && $dir != 'test' )
			{
				echo '<a href="'.$dir.'">';
				echo $dir . "</a><br />";				
			}
		}
	}
	
	function studenti_unos()
	{
		// provjera da li su poslani podaci, ako jesu proslijeđuju se modelu metodom putNoviStudent()
		if (isset($_POST['btnSubmit']))
			$this->model->putNoviStudent($_POST);
		
		// dohvaćanje mjesta i gradova iz baze preko modela i metode getPopisGradova()
		$mjesta = $this->model->getPopisGradova();
		foreach ($mjesta as $mjesto) 
			$mjestaFormated[$mjesto['pbr']] = $mjesto['nazMjesto'];
		
		// priprema podataka potrebnih za stvaranje forme za upis novog studenta
		$podacizaupis = array(	'Ime' 				=> '',
								'Prezime' 			=> '',
								'Datum rođenja' 	=> '__datum_r_',
								'Grad rođenja' 		=> $mjestaFormated,
								'JMBG' 				=> '',
								'Grad stanovanja' 	=> $mjestaFormated
								);
								
		//ispis forme korištenjem metode ispisiFormZaUpis() objekta pogled
		$this->pogled->ispisiFormZaUpis($podacizaupis);
	}
	
	function studenti_popis()
	{
		$studenti = $this->model->getPopisStudenata();
		$this->pogled->ispisiHTMLtablicu($studenti);
	}
	
	function ispiti_pregled($studentP)
	{
		$studenti = $this->model->getPopisStudenata();
		foreach ( $studenti as $student )
		{
			$podaci['student'][$student['mbr']] = $student['mbr'] . '-' . $student['Student'];
		}
		
		$this->pogled->ispisiFormZaUpis($podaci);
		
		if ( $studentP != '' )
		{
			$popisIspita = $this->model->getPopisIspita($studentP);
			$podaciStudenta = $this->model->getStudentByMbr($studentP);
			echo 'Student: '.$podaciStudenta[0]['imeStud'].' '.$podaciStudenta[0]['prezStud'].' ('.$studentP.')<br />';
			$this->pogled->ispisiHTMLtablicu($popisIspita);
		}
			
	}
	
	function ispit_unos ()
	{
		if ( isset($_POST['btnSubmit']) )
			$this->model->putNoviIspit();		
		
		$studenti 	= $this->model->getPopisStudenata();
		foreach ( $studenti as $key => $student )
			$studentiZaUpis[$student['mbr']] = $student['mbr'] . '-' . $student['Student'];
			
		$predmeti 	= $this->model->getPopisPredmeta();
		foreach ( $predmeti as $key => $predmet )
			$predmetiZaUpis[$predmet['sifPred']] = $predmet['nazPred'];
			
		$nastavnici = $this->model->getPopisNastavnika();
		foreach ( $nastavnici as $key => $nastavnik )
			$nastavniciZaUpis[$nastavnik['sifNastavnik']] = $nastavnik['prezNastavnik'].', '.$nastavnik['imeNastavnik'];
		
		// priprema podataka potrebnih za stvaranje forme za upis novog studenta
		$podacizaupis = array(	'Student' 		=> $studentiZaUpis,
								'Predmet' 		=> $predmetiZaUpis,
								'Nastavnik' 	=> $nastavniciZaUpis,
								'Datum' 		=> '__datum__',
								'Ocjena' 	=> array(1 => 'nedovoljan (1)',
													  2 => 'dovoljan (2)',
													  3 => 'dobar (3)',
													  4 => 'vrlo dobar (4)',
													  5 => 'odličan (5)')
								);
		
		
		$this->pogled->ispisiFormZaUpis($podacizaupis);
	}
	
}

?>