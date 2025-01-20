
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
		
		// sucht nach Lemma in allen Korpora
		
		error_reporting(E_ERROR | E_CORE_ERROR | E_PARSE );
		ini_set('memory_limit', '300M');
		
		// Database-Generierung kann schon etwas dauern
		set_time_limit(6000);
		$start = microtime(true);
		
		if (isset($_GET["lemma"]) and $_GET["lemma"] !== "")
			$lemma = $_GET["lemma"];
		else
			$lemma = "Petka";
		
		echo "Results for the lemma <b>" . $lemma . "</b>: <span id='resulter'>...</span>" . nl2br("\n\n\n");
		
		$srcs_url["punco"] = "src.txt";
		$srcs_url["petka"] = "src_p.txt";
		$srcs_url["sva"] = "src_sva.txt";
		$srcs_url["lt"] = "src_lt.txt";
		
		$corpus_id["src.txt"] = "";
		$corpus_id["src_p.txt"] = "corpus=petka&";
		$corpus_id["src_sva.txt"] = "corpus=sva&";
		$corpus_id["src_lt.txt"] = "corpus=lt&";
		
		foreach ($srcs_url as $srcs) {
			$handle_a = fopen($srcs, "r");
			$file = file($srcs);
			$rows = count($file);
			for ($row = 0; $row < $rows; $row++) {
				$data = fgetcsv($handle_a, 0, "\t"); 
				if ($data[0] !== "id") {
					$sources[$srcs][$row][0] = $data[0];
					$sources[$srcs][$row][1] = $data[1];
					if (isset($data[4]))
						$sources[$srcs][$row][4] = $data[4];
					else
						$sources[$srcs][$row][4] = "PPS " . $data[0];
				}
			}
			fclose($handle_a);
		}

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
		}
		fclose($handle_b);

		$counter = 0;
		$results = 0;
		$result = null;
		$hit = FALSE;
		$current = "";
		$prev = "";
		
		foreach ($srcs_url as $srcs) {
		
			foreach ($sources[$srcs] as $source) {
					
				$handle_c = fopen($source[1], "r");
				$file = file($source[1]);
				$rows = count($file);
				for ($row = 0; $row < $rows; $row++) {
					$data = fgetcsv($handle_c, 0, "\t"); 
					if (isset($data[0]) and $data[0] !== "text" and isset($data[3]) and isset($data[6]) and isset($data[7])) {
						
						$current = $data[6];
						if ($current !== $prev)
							if ($hit == TRUE) {
								
								echo "<a target='_blank' href='syntax_browser.php?" . $corpus_id[$srcs] . "chapter=" . $source[0] . "&sent_id=" . $prev . "'>" . $source[4] . " - sentence ". $prev . "</a>" . nl2br("\n");
								
								echo $result[$source[1]][$prev] . nl2br("\n\n");
								$results += 1;
								$hit = FALSE;
							}
							else
								unset($result[$source[1]][$prev]);
						
						if (isset($result[$source[1]][$data[6]]) == FALSE)
							$result[$source[1]][$data[6]] = $data[2];
						else
							$result[$source[1]][$data[6]] = $result[$source[1]][$data[6]] . " " . $data[2];
						
						$counter += 1;
						
						// gesuchtes Lemma
						if ($data[3] == $lemma)
							$hit = TRUE;
						
						$prev = $data[6];
					
					}
				}
				
				if ($hit == TRUE) {
					
						echo "<a target='_blank' href='syntax_browser.php?" . $corpus_id[$srcs] . "chapter=" . $source[0] . "&sent_id=" . $prev . "'>" . $source[4] . " - sentence ". $prev . "</a>" . nl2br("\n");
						
						echo $result[$source[1]][$prev] . nl2br("\n\n");
						$results += 1;
						$hit = FALSE;
				}
			}
		}
		
		echo nl2br("\n") . "processed tokens: " . $counter . nl2br("\n\n");
		
		echo "<script type='text/javascript'>;
				var results = " .  $results . ";
				window.onload = function() {
					var resulter = document.getElementById('resulter');
					resulter.innerHTML = results;
				};
			</script>
				";

	?>


</body>
</html>