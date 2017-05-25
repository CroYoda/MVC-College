<?php
class Pogled
{
	function ispisiHTMLtablicu($podaci)
	{
		if ( isset($podaci[0]) )
		{
			echo "<table border=1>";
			echo "<tr>";
			foreach ( $podaci[0] as $naslov => $vrijednost)
			{
				echo "<th>".$naslov."</th>";
			}
			echo "</tr>";
			foreach ( $podaci as $redak )
			{
				echo "<tr>";
				echo "<td>".implode("</td><td>", $redak)."</td>";
				echo "</tr>";
			}
			echo "</table>";			
		}
	}
	
	function ispisiFormZaUpis($podaci)
	{
		echo '<form method="POST" action="">' . "<br />";
		echo '<table border=1>';
		foreach ($podaci as $nazivP => $tipP)
		{
			echo '<tr><td>'.$nazivP.":</td>";
			switch (true)
			{
				
				// SELECT
				case (is_array($tipP)):
					echo '<td><select name="'.$nazivP.'">';
					foreach ($tipP as $key => $value)
					{
						echo '<option value="'.$key.'">'.$value.'</option>';
					}
					echo '</select>' . "</td></tr>";
				break;
				
				// SELECT datuma
				case ($tipP == '__datum_r_'):
				case ($tipP == '__datum__'):
					echo '<td>';
					echo '<select name="'.$nazivP.'_d">';
					for ($i=1; $i<=31; $i++)
					{
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
					echo '</select>';
					echo '<select name="'.$nazivP.'_m">';
					for ($i=1; $i<=12; $i++)
					{
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
					echo '</select>';
					echo '<select name="'.$nazivP.'_g">';
					for ($i=date('Y')-90; $i<=date('Y')-0; $i++)
					{
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
					echo '</select>';
					echo "</td></tr>";
				break;
				
				// INPUT type = text
				default:
					echo '<td><input type="text" name="'.$nazivP.'" value="'.$tipP.'" />'
							. "</td></tr>";
				
			}
		}
		echo  '<tr><td colspan="2"><input type="submit" name="btnSubmit" value="Upis" /></td></tr>';
		echo '</table></form>';
	}
} 