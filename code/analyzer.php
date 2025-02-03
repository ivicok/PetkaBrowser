
<!DOCTYPE HTML>


<html>

<head>
	<meta charset="utf-8" />

	<title>Pop Punčov Sbornik - lemma analyzer</title>

</head>
<body>
	<?php
		
		// gibt die nicht lemmatisierten Tokens mit dem Satz-ID
		
		
		error_reporting(E_ERROR | E_CORE_ERROR | E_PARSE );
		
		$script = 0;
		if (isset($_GET["cyr"]))
			if ($_GET["cyr"] == 1)
				$script = 1;
			elseif ($_GET["cyr"] == 2) 
				$script = 2;
		
		$chapter_id = "020";
		if (isset($_GET["chapter"]))
			$chapter_id = $_GET["chapter"];
		
		// Liste der Kapitel mit Links
		$src_url="src.txt";
		
		if (isset($_GET["corpus"]))
			if ($_GET["corpus"] == "petka")
				$src_url="src_p.txt";
			elseif ($_GET["corpus"] == "sva")
				$src_url="src_sva.txt";
			elseif ($_GET["corpus"] == "lt")
				$src_url="src_lt.txt";

		// Petka rettet uns, falls etwas schief geht
		$spreadsheet_url="https://www.punco.slavistik.lmu.de/pps/020.txt";
		
		// wählt das Kapitel nach Input von der Index-Seite
		$handle_a = fopen($src_url, "r");
		$file = file($src_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
			$dataa = fgetcsv($handle_a, 0, "\t");
			if ($dataa[0] == $chapter_id) {
				$spreadsheet_url = $dataa[1];
				$prev = $dataa[2];
				$next = $dataa[3];
				if ($dataa[5] !== "") $var = $dataa[5];		// Sprachvarietät
			}
		}
		fclose($handle_a);

		// macht sich eine Liste der Lemmas mit Übersetzungen
		$lemmas_url = "lemmas.txt";
		$handle_b = fopen($lemmas_url, "r");
		$file = file($lemmas_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
				$lemma_data = fgetcsv($handle_b, 0, "\t");
				$lemmas[$row][0] = $lemma_data[0];	// Lemma selbst
				$lemmas[$row][1] = $lemma_data[2];	// die Übersetzung
			//	echo $lemmas[$row][0] . " " . $lemmas[$row][1] . nl2br("\n");
			}
		fclose($handle_b);


		if(!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";

		$unset = TRUE;
		$counter = 0;
		
		echo "chapter <b>" . $chapter_id . "</b> in the subcorpus " . $src_url . nl2br("\n");
		echo "processed tokens: <span id='resulter'>...</span>" . nl2br("\n\n");
		
		echo "<table><td>token</td><td>lemma</td><td>Sentence</td></tr>";

		if (($handle = fopen($spreadsheet_url, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {

				// Datenstruktur:
				//	0	1	2	3	4	5	6	7	8	9	10
				//	txt	cyr	dpl	lem	PoS	ext	sid	Uid	Ucy	UD	ext

				if (isset($data[0]) and $data[0] !== "text" and isset($data[3]) and isset($data[6]) and isset($data[7])) {
					
					$counter += 1;
					$unset = TRUE;
					
					foreach($lemmas as $lemma)
						if ($data[3] == $lemma[0]) {
							$lem = $lemma[1];
							$unset = FALSE;
						}
					
					if ($unset == TRUE) {
						echo "<tr><td>" . $data[0] . "</td><td><b>" . $data[3] . "</b></td><td>" . $data[6] . "</td></tr>";
						}
				//	else
				//		echo "<tr><td>" . $data[0] . "</td><td>" . $data[3] . "</td>><td>" . "'" . $lem . "'</td><td/></tr>";

				}
				
			}
			fclose($handle);
			echo "</table>";
		}
		else
			die("Problem reading tsv");
		
		echo "<script type='text/javascript'>;
				var results = " .  $counter . ";
				window.onload = function() {
					var resulter = document.getElementById('resulter');
					resulter.innerHTML = results;
				};
			</script>
				";
		
	?>



</body>
</html>