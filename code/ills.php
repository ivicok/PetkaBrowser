
<!DOCTYPE HTML>


<html>

<head>
	<meta charset="utf-8" />
</head>

<body>
	<?php
		
		$illustrations_url= "pps/ills.txt";

		echo "<table>";

		$handle_i = fopen($illustrations_url, "r");
		$file = file($illustrations_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
			$ill = fgetcsv($handle_i, 0, "\t");
			$spreadsheet_data[] = $ill;
			
			if ($ill[0] == "♣") {
				
				// Folio der Seite
				echo "<tr><td width=10%><a href=" . $ill[2] . " target='_blank'><img src=" . $ill[2] . " width=50></a>" . nl2br("\n");
				
				// Seite
				echo "</td><td width=5%> ♣ </td><td>page " . $ill[1] . nl2br("\n");
				
				// Kapitel mit Link
				$link = mb_substr($ill[3], 0, 3);
				echo $ill[3] . nl2br("\n") . "<a href=/chapter_view.php?chapter=" . $link . "&cyr=1 target='_blank'>chapter view</a>";
				
				// Beschreibung des Bildes
				echo "</td><td width=5%> ♣ </td><td>" . $ill[4] . "</td></tr>";
				
			}
			
		}
		fclose($handle_i);
		
		echo "</table>";

	?>
</body>
</html>