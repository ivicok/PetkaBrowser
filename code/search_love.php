
<!DOCTYPE HTML>


<html>

<head>
	<meta charset="utf-8" />

	<style>
	  td {
		vertical-align: top;
	  }
	</style>

</head>

<body>
	<?php
		
		// sucht nach Lemma in einer Quelle nach einkodierten Kriterien
		
		error_reporting(E_ERROR | E_CORE_ERROR | E_PARSE );
		ini_set('memory_limit', '300M');
		
		// Database-Generierung kann schon etwas dauern
		set_time_limit(6000);
		$start = microtime(true);
		
		if (isset($_GET["lemma"]) and $_GET["lemma"] !== "")
			$lemma = $_GET["lemma"];
		else
			$lemma = "Petka";
		
		echo "Results for the given criteria: <span id='resulter'>...</span>" . nl2br("\n\n\n");
		
		$source = "editions/pss.txt";

		// macht sich eine Liste der Lemmas
		$lemmas_url= "lemmas.txt";
		$handle_b = fopen($lemmas_url, "r");
		$file = file($lemmas_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
			$lemma_data = fgetcsv($handle_b, 0, "\t");
			$spreadsheet_data[] = $lemma_data;
			if ($lemma_data[0] !== "" and $lemma_data[0] !== "lemma")
				$lemmas[$row][0] = $lemma_data[0];
				$lemmas[$row]["root"] = $lemma_data[15] ?? "";
		}
		fclose($handle_b);

		$counter = 0;
		$results = [];
		$used = FALSE;
				
					
		$handle_c = fopen($source, "r");
		$file = file($source);
		$rows = count($file);
		for ($row = 0; $row < $rows; $row++) {
			$data = fgetcsv($handle_c, 0, "\t"); 
			if (isset($data[0]) and $data[0] !== "text" and isset($data[3]) and isset($data[6]) and isset($data[7])) {

				$counter += 1;
				
				// hier definiert man die Kriterien
				// Datenstruktur:
				//	0	1	2	3	4	5	6	7	8	9	10
				//	txt	cyr	dpl	lem	PoS	ext	sid	Uid	Ucy	UD	ext
				
				// alle Verben ohne Eintrag im LOVe
				if (str_contains($data[4], "V") and isset($data[3])) {
					
					$used = FALSE;
					
					foreach ($lemmas as $lemma) {
						
						if ($data[3] == $lemma[0])
							if ($lemma["root"] == "") {
								
								foreach ($results as $result) 
									if ($result == $data[3])
										$used = TRUE;
								
								if ($used == FALSE) {
									echo "<b>" . $data[3] . "</b>" . nl2br("\n");
									array_push($results, $data[3]);
								}		
							//	else
							//		echo $data[3] . nl2br("\n");
							}
					}
				}
			}
		}
		
		echo nl2br("\n") . "processed tokens: " . $counter . nl2br("\n\n");
		
		echo "<script type='text/javascript'>;
				var results = " .  count($results) . ";
				window.onload = function() {
					var resulter = document.getElementById('resulter');
					resulter.innerHTML = results;
				};
			</script>
				";

	?>


</body>
</html>