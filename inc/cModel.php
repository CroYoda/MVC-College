<?php

class Model
{
	protected $baza;
	
	function __construct()
	{
		$this->baza = new mysqli('localhost', 'root', '', 'fakultet');
		$this->baza->set_charset('utf8');
	}
	
	function getPopisStudenata()
	{
		$query = "
			SELECT s.mbrStud AS mbr,
				   CONCAT(s.prezStud, ', ', s.imeStud) AS Student,
				   DATE_FORMAT(s.datRodStud, '%d.%m.%Y.') AS 'Datum rođenja',
				   s.jmbgStud AS JMBG,
				   m1.nazMjesto AS 'Grad rođenja',
				   m2.nazMjesto AS 'Grad stanovanja'
			FROM stud s, mjesto m1, mjesto m2
			WHERE s.pbrRod = m1.pbr AND s.pbrStan = m2.pbr
			ORDER BY s.prezStud, s.imeStud
		";
		$result = $this->baza->query($query);
		
		return $result->fetch_all(MYSQLI_ASSOC);
	}
	
	function getStudentByMbr ($mbrStudent)
	{
		$query = "SELECT * FROM stud WHERE mbrStud =".$mbrStudent;
		$result = $this->baza->query($query);
		
		return $result->fetch_all(MYSQLI_ASSOC);
	}
	
	function getPopisGradova()
	{
		$query = "SELECT * FROM mjesto ORDER BY nazMjesto";
		$result = $this->baza->query($query);
		
		return $result->fetch_all(MYSQLI_ASSOC);	
	}
	
	function getPopisPredmeta()
	{
		$query = "SELECT * FROM pred ORDER BY nazPred";
		$result = $this->baza->query($query);
		
		return $result->fetch_all(MYSQLI_ASSOC);
	}
	
	function getPopisNastavnika()
	{
		$query = "SELECT * FROM nastavnik ORDER BY prezNastavnik, imeNastavnik";
		$result = $this->baza->query($query);
		
		return $result->fetch_all(MYSQLI_ASSOC);
	}
	
	function putNoviStudent($podaci)
	{
		$query = "SELECT MAX(mbrStud) FROM stud";
		$result = $this->baza->query($query);
		$mbr = $result->fetch_row();
		$mbr = $mbr[0]+1;
		
		$datRod = $podaci['Datum_rođenja_g']
					."-".$podaci['Datum_rođenja_m']
					."-".$podaci['Datum_rođenja_d']
					." 00:00:00";
		
		$query = "
			INSERT INTO stud 
				(mbrStud, 
				 imeStud, 
				 prezStud, 
				 pbrRod, 
				 pbrStan, 
				 datRodStud, 
				 jmbgStud)
			 VALUES
				('".$mbr."', 
				 '".$podaci['Ime']."', 
				 '".$podaci['Prezime']."', 
				 '".$podaci['Grad_rođenja']."', 
				 '".$podaci['Grad_stanovanja']."',
				 '".$datRod."',
				 '".$podaci['JMBG']."'
				 )
		";
		
		$this->baza->query($query);		
	}
	
	function getPopisIspita($mbrStudenta)
	{
		$query = "
				SELECT  p.nazPred AS Predmet, 
						CONCAT(n.prezNastavnik, ', ', n.imeNastavnik) AS Nastavnik,
						DATE_FORMAT(i.datIspit, '%d.%m.%Y.') AS Datum,
						i.ocjena AS Ocjena
				FROM ispit i, nastavnik n, pred p
				WHERE i.mbrStud = ".$mbrStudenta." AND
				(i.sifPred = p.sifPred AND i.sifNastavnik = n.sifNastavnik)
		";
		$result = $this->baza->query($query);
		$ispiti = $result->fetch_all(MYSQLI_ASSOC);
		
		foreach ( $ispiti as $key => $ispit )
		{
			switch ($ispit['Ocjena'])
			{
				case 1:
					$ispiti[$key]['Ocjena'] = 'nedovoljan (1)';
				break;
				case 2:
					$ispiti[$key]['Ocjena'] = 'dovoljan (2)';
				break;
				case 3:
					$ispiti[$key]['Ocjena'] = 'dobar (3)';
				break;
				case 4:
					$ispiti[$key]['Ocjena'] = 'vrlo dobar (4)';
				break;
				case 5:
					$ispiti[$key]['Ocjena'] = 'odličan (5)';
				break;
			}
		}
		
		return $ispiti;
	}
	
	function putNoviIspit()
	{
		
		$datum = $_POST['Datum_g'].'-'.sprintf('%02d', $_POST['Datum_m']).'-'.sprintf('%02d', $_POST['Datum_d']);
		$datum .= ' 00:00:00';
		
		$query = "INSERT INTO ispit (mbrStud, sifPred, sifNastavnik, datIspit, ocjena)
					VALUES (".$_POST['Student'].",
							".$_POST['Predmet'].",
							".$_POST['Nastavnik'].",
							'".$datum."',
							".$_POST['Ocjena'].")";
							
		$this->baza->query($query);	
	}
}

?>