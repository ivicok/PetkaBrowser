
<!DOCTYPE HTML>

<html>

<head>
	<meta charset="utf-8" />
</head>

<body>	
	<?php
		
		// liest den ganzen Zeug und generiert einen einfachen Text nach Sätze und Kapitel
		
		error_reporting(E_ERROR | E_CORE_ERROR | E_PARSE );
	//	ini_set('display_errors', 1); error_reporting(~0);
		set_time_limit(6000);
		$start = microtime(true);

		// Parameter für die Schrift-Variante		
		if ($_GET["cyr"] == 1)
			$script = 1;
		elseif ($_GET["cyr"] == 2)
			$script = 2;
		elseif ($_GET["cyr"] == 3 or $_GET["cyr"] == 26)
			$script = 26;
		else
			$script = 0;
		
		// Liste der Kapitel mit Links
		if ($_GET["corpus"] == "petka") {
			$src_url="src_p.txt";
			$title = 17;
			$corpus_id = "corpus=petka&";
		} elseif ($_GET["corpus"] == "sva") {
			$src_url="src_sva.txt";
			$title = 17;
			$corpus_id = "corpus=sva&";
		} elseif ($_GET["corpus"] == "lt") {
			$src_url="src_lt.txt";
			$title = 17;		// Spalte mit Titel oder Beschreibung des Kapitels
			$corpus_id = "corpus=lt&";
		} else {
			$src_url="src.txt";
			$title = 17;
		}

		if (empty($_GET["chapter"]) == true)
			$chapter_id = "full";
		else
			$chapter_id = $_GET["chapter"];

		// macht eine Liste der Kapitel
		$ch_index = 0;
		if (($handle_src = fopen($src_url, "r")) !== FALSE) {			
			while (($data_src = fgetcsv($handle_src, 0, "\t")) !== FALSE) {
				if (empty($data_src[0]) == FALSE) {
					$ch_list[$ch_index] = $data_src[1];
					$ch_index++;
					if ($chapter_id !== "full") {
						if ($data_src[0] == $chapter_id) $chapter_url = $data_src[1];
					}
					
				//	echo $data_src[1] . "<br/>";
				}
			}
			fclose($handle_src);
		}

		// .tsv-, .conllu- & .tei-Export
		echo "view <a target='_blank' href='conllu2.php?" . $corpus_id . "chapter=" . $chapter_id . "&cyr=" . $script  . "'>.conllu</a>". nl2br("\n");

		// öffnet Kapitel aus der Liste
		for ($i = 0; $i <= $ch_index; $i++) {
			
			if ($chapter_id !== "full") {
				$i = $ch_index;
			} else 
				$chapter_url = $ch_list[$i];
			
		//	echo "open: " . $chapter_url . "<br/>";
			
			if ($chapter_url !== "url" && empty($chapter_url) == FALSE) {
				if (($handle_chapter = fopen($chapter_url, "r")) !== FALSE) {
					while (($data = fgetcsv($handle_chapter, 0, "\t")) !== FALSE) {
						// am Anfang des Textes, die Bezeichnung des Kapitels wird als Key für die erste Ebene des Arrays genommen
						if ($data[14] == "0") {
							$const[$i]["chunk"] = $data[15];
							echo "<br/><br/>" . $const[$i]["chunk"] . "<br/>";
							echo "<a href=" . $chapter_url . " download>source</a><br/>";
						}
						
						// wir brauchen keine Headers, Marginalia und leere Zeilen
						if ($data[9] !== "UD_type" and $data[9] !== "mrg" and empty($data[0]) == FALSE) {
						
							// jeder Satz in einem Kapitel bekommt eigene Nummer
							if ($data[7] == "1") {
								$sent_id = $data[6];
								echo "<br/>" . $sent_id . ": ";
							}
							
							// der Text selbst
							echo $data[$script] . " ";
						
						}
					}
				}
				fclose($handle_chapter);
			}
		}
		
	?>		


</body>
</html>