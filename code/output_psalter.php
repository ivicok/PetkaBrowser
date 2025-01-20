<!DOCTYPE HTML>

<html>

<head>
	<meta charset="utf-8" />
</head>

<style>
	.header	{
		color: red;
		font-weight: bold;
	}	
	.verse	{
		color: red;
		font-weight: bold;
	}
</style>

<body>
	<?php

		error_reporting(E_ERROR | E_CORE_ERROR | E_PARSE );
	//	ini_set('display_errors', 1); error_reporting(~0);
		set_time_limit(6000);
		$start = microtime(true);
			
		// Parameter für die Schrift-Variante
		if (isset($_GET["cyr"]) == TRUE) {
			if ($_GET["cyr"] == 1)
				$script = 1;
			elseif ($_GET["cyr"] == 2)
				$script = 2;
			elseif ($_GET["cyr"] == 3 or $_GET["cyr"] == 26)
				$script = 26;
			elseif ($_GET["cyr"] == 0)
				$script = 0;
			}
		else
			$script = 0;
		
		// Parameter für die Satz-Suche
		if (isset($_GET["sent_id"]) == TRUE)
			$sent_id = $_GET["sent_id"];
		else
			$sent_id = 0;

		// für die Psalm-Suche
		if (isset($_GET["verse"]) == TRUE) {
			$verse = $_GET["verse"];
			$verse = str_replace("-", ":", $verse);
			$target = $verse;
		}
		else
			$verse = 0;
		
		if (isset($_GET["single"]) == TRUE) {
			$single = $_GET["single"];
			$target = "-" . strtok($verse, ":") . "-";
		}
		else
			$single = FALSE;
		
		$lemmas_url = "lemmas.txt";
		$src_url="src.txt";
		$title = 17;	
		$spreadsheet_url = "editions/pss.txt";
		$corpus_id = "corpus=lt&";
		$chapter_id = "pss";

		// macht sich eine Liste der Lemmas mit Übersetzungen
		$handle_b = fopen($lemmas_url, "r");
		$file = file($lemmas_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
					$lemma_data = fgetcsv($handle_b, 0, "\t");
					if (empty($lemma_data[0]) == FALSE) {
						$lemmas[$row][0] = $lemma_data[0];	// Lemma selbst
						$lemmas[$row][1] = $lemma_data[2];	// die Übersetzung
					//	echo $lemmas[$row][0] . " " . $lemmas[$row][1] . nl2br("\n");
					}
			}
		fclose($handle_b);

		// Liste der PoS-Tags
		$msd_url= "msd.txt";
		$handle_c = fopen($msd_url, "r");
		$file = file($msd_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
				$msd_data = fgetcsv($handle_c, 0, "\t");
				if (empty($msd_data[0]) == FALSE) {
					$msds[$row][0] = $msd_data[0];	// PoS Tag selbst
					$msds[$row][1] = $msd_data[2];	// die Leipzig-Notation
				}
			}
		fclose($handle_c);

		// wichtige Daten werden nochmals hier für JavaScript gesammelt
		echo "<span id='data' class='" . $chapter_id . " " . $script . " " . $corpus_id . "' />";

		if(!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";

		$line = 1;
		$psalm = null;
		$prev = null;
		$next = null;
		$echo = FALSE;
		
		if (isset($_GET["verse"]) == TRUE) {
			$prev = intval(strtok($verse, "-")) - 1;
			$next = intval(strtok($verse, "-")) + 1;
		}
		

		if (($handle = fopen($spreadsheet_url, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {
				
				if ($data[0] !== "text" and empty($data[0]) == FALSE) {
					
					// eol 0: markiert Anfang des Dokuments
					if ($data[14] == "0") {
												
						// Titel/Metadata des Kapitels
						echo "<p style='width:75%;'>" . $data[$title] . "</p>" . nl2br("\n");
												
						if (isset($_GET["verse"]) == TRUE)
							echo "<b>Psalm " . intval(strtok($verse, "-")) . "</b>" . nl2br("\n");
					
						// Navigation zu den anderen Psalmen
						if ($prev !== null and $prev > 0)
							echo "<a href='psalter_view.php?single=true&cyr=" . $script . "&verse=" . $prev . "-1'>previous</a>" . " ";
						if ($next !== null and $next < 152)
							echo "<a href='psalter_view.php?single=true&cyr=" . $script . "&verse=" . $next . "-1'>next</a>";
						
						echo nl2br("\n\n");
						
						// Plain View und Export
						echo "<a href=\"plain_view.php?" . $corpus_id . "chapter=" . $chapter_id . "&cyr=" . $script . "\" target=\"_blank\">plain view</a> 
							<a href=" . $spreadsheet_url . " download>source</a> 
							<a target='_blank' href='conllu2.php?" . $corpus_id . "chapter=" . $chapter_id . "&cyr=" . $script  . "'>.conllu</a> 
							<a href='inter_view.php?ref=bren&cyr=" . $script . "&verse=" . str_replace(":", "-", $verse) . "'>interlinear</a>" . nl2br("\n\n");
						
						// Schrift-Steuerung
						if ($script == 1) {
							echo "<a href=\"psalter_view.php?single=true&cyr=3&verse=" . str_replace(":", "-", $verse) . "\">Cyrillic</a>" . " " . "<a href=\"psalter_view.php?single=true&cyr=0&verse=" . str_replace(":", "-", $verse) . "\">Latin</a>" . " " . "<a href=\"psalter_view.php?single=true&cyr=2&verse=" . str_replace(":", "-", $verse) . "\">diplomatic</a>";
						} elseif ($script == 0) {
							echo "<a href=\"psalter_view.php?single=true&cyr=1&verse=" . str_replace(":", "-", $verse) . "\">Glagolitic</a>" . " " . "<a href=\"psalter_view.php?single=true&cyr=3&verse=" . str_replace(":", "-", $verse) . "\">Cyrillic</a>" . " " . "<a href=\"psalter_view.php?single=true&cyr=2&verse=" . str_replace(":", "-", $verse) . "\">diplomatic</a>";
						} elseif ($script == 2) {
							echo "<a href=\"psalter_view.php?single=true&cyr=1&verse=" . str_replace(":", "-", $verse) . "\">Glagolitic</a>" . " " . "<a href=\"psalter_view.php?single=true&cyr=3&verse=" . str_replace(":", "-", $verse) . "\">Cyrillic</a>" . " " . "<a href=\"psalter_view.php?single=true&cyr=0&verse=" . str_replace(":", "-", $verse) . "\">Latin</a>";
						} elseif ($script == 26) {
							echo "<a href=\"psalter_view.php?single=true&cyr=1&verse=" . str_replace(":", "-", $verse) . "\">Glagolitic</a>" . " " . "<a href=\"psalter_view.php?single=true&cyr=0&verse=" . str_replace(":", "-", $verse) . "\">Latin</a>" . " " . "<a href=\"psalter_view.php?single=true&cyr=2&verse=" . str_replace(":", "-", $verse) . "\">diplomatic</a>";
						}
						echo nl2br("\n");
						
						// Font-Steuerung
						echo "<select id='font_selector' onChange='changeFont(this.options[this.selectedIndex].value);'>
								<option value='font-family: Arial; font-size: 1em;'>Arial (default)</option>
								<option value='font-family: Calibri;'>Calibri</option>
								<option value='font-family: Consolas, monospace, serif; font-size: 0.9em;'>Consolas (monospace)</option>
								<option value='font-family: BukyVedeWeb; font-size: 1.2em;'>BukyVede</option>
								<option value='font-family: KlimentStdWeb; font-size: 1.2em;'>Kliment</option>
								<option value='font-family: MenaionWeb; font-size: 1.2em;'>Menaion</option>
								<option value='font-family: MonomakhWeb; font-size: 1.2em;'>Monomakh</option>
								<option value='font-family: \"Times New Roman\";'>Times New Roman</option>
								<option value='font-family: Verdana;'>Verdana</option>
							</select>" . nl2br("\n\n");
							
						// Switch für Sentence-Browser - vereinfacht die Anwendung durch Touchscreen
						echo "Browser Lock <label class='switch'>
								<input type='checkbox' id='toggle' onclick='locked=!locked'>
								<div class='slider'></div>
								</label>" . nl2br("\n");
						
						echo nl2br("\n\n");
						echo "<table>";
					}
					
					// wenn nur ein Psalm geschrieben werden soll
					if (preg_match("/{$target}/i", $data[21]))
						$echo = TRUE;
					else if (empty($data[21]) == FALSE and preg_match("/{$target}/i", $data[21]) == FALSE)
						$echo = FALSE;
					
					// Psalm-Nummer
					if (empty($data[22]) == FALSE)
						$psalm = $data[22];
						
					if ($single == FALSE or $echo == TRUE) {
					
						// das Bild
						// orientiert sich nach System-Seitennummer ("page", L-Spalte)
						// "mrg" bezeichnet Marginalia - die sollen nicht die Struktur ändern
						if (empty($data[11]) == FALSE and $data[9] !== "mrg") {
							echo "<tr><td width=40%><img alt=facsimile src=" . $data[12] . "></td><td width=5%></td><td>";
							
							// System-Seitennummer
							echo "<table><tr>page " . $data[11] . " </tr><tr><td height=10 colspan=1 /></tr>";
							echo "<tr><td width=20>";
							
							// Seiten-Header
							// der Index soll die ausgewählte Schriftvariante wählen
							// für Header - 18: Latin/diplomatic, 19: Glagolitic/Cyrillic
							// für Pagination - 20: Latin/diplomatic, 21: Glagolitic/Cyrillic
							if ($script == 0 or $script == 2) {
								if (empty($data[18]) == FALSE or empty($data[20]) == FALSE)
									echo "</td><td><span class='header'>" . $data[18] . "</span></td></tr><tr><td>";
							} elseif ($script == 1 or $script == 26) {
								if (empty($data[19]) == FALSE or empty($data[21]) == FALSE) 
									echo "</td><td><span class='header'>" . $data[19] . "</span></td></tr><tr><td>";
							}	
							
							// die erste Liniennummer
							echo $line . ": </td><td> ";
						}
						
						// der Text
						if (empty($data[0]) == FALSE and $data[9] !== "mrg") {
							
							// Psalm-Nummer wird schon oben geholt
						//	if (empty($data[22]) == FALSE)
						//		$psalm = $data[22];
							
							// Vers-Nummer
							if (empty($data[24]) == FALSE) {
								
								$nr = $psalm . ":" . $data[24];
								
								echo "<span class='verse' ";
								if ($nr == $verse and $sent_id == 0)
									echo " id='scroll' ";
								
								echo ">" . $nr . " </span>";
							}
							else if ($data[24] == 0)
								echo "<span class='verse'>" . $psalm . " </span>";
							
							// der Text selbst
							// Token-ID generiert nach Satz und Position
							echo "<span class='token " . $data[6] . "'";
							
							// Satz-Orientierung
							// hat Vorrang vor dem Vers
							if ($sent_id !== 0 and $data[6] == $sent_id and $data[7] == "1")
								echo " id='scroll' ";
							echo ">";
							
							// boldface, wenn es Übersetzung gibt
							if (empty($data[13]) == FALSE or empty($data[16]) == FALSE)
								 echo "<b>" . $data[$script] . "</b> ";
							else
								echo $data[$script] . " ";
							
							
							// Inhalt des Pop-up's (beim Mouse-over)
							// Lemma
							echo "<span class=ext> lemma: <i>" . $data[3] . " </i>";
							
							// Übersetzung der Lemma
							foreach($lemmas as $lemma)
								if ($data[3] == $lemma[0]) echo "'" . $lemma[1] . "'";
							echo "<br />";
							
							// PoS-Tag
							$pos = $data[4];
							foreach ($msds as $msd)
								if ($pos == $msd[0])
									$pos = $msd[1];
							echo " form: <font style='font-variant: small-caps'>" . $pos . "</font>";
							if (empty($data[5]) == FALSE) {
								$pos = $data[5];
								foreach ($msds as $msd)
									if ($pos == $msd[0])
										$pos = $msd[1];
								echo "<br /> alt.analysis: <font style='font-variant: small-caps'>" . $pos . "</font>";
							}
							unset($pos);
							echo "<br /><br />";
							
							// Übersetzung
							if (empty($data[13]) == FALSE)
								echo " translation: " . $data[13] . "<br /><br />";
							
							// Notes
							if (empty($data[16]) == FALSE)
								echo $data[16] . "<br /><br />";

							echo "</span></span>";
							
						}
						
						// eol 1: Ende der Linie und die neue Liniennummer					
						if ($data[14] == 1) {
							$line++;
							echo "</td></tr>";
							echo "<tr><td>". $line . ": </td><td>";
							
						// eol 2: Ende der Seite
						} elseif ($data[14] == 2) {
							echo "</td></tr></table>";	// Text-Block
							echo nl2br("\n\n");
							echo "</td></tr><tr>";		// Reihe mit dem Bild
							$line = 1;
						}
						
					}
				}
			}
			fclose($handle);
			echo "</table></table>";
		}
				
	?>

<script>

	var tokens = document.getElementsByClassName("token");
	var data = document.getElementById("data");
	var corpus_id = data.classList.item(2);
	var locked = false;

	window.onload = function(){
		
		var scroll = document.getElementById("scroll");
		if (scroll) {
			scroll.scrollIntoView();
			window.scrollBy(0, -72)
		}
	};
	
	var browseSyntax = function() {
		var chapter_id = data.classList.item(0);
		var script =  data.classList.item(1);
		var sent_id =  this.classList.item(1);
		if (!locked) {
			if (corpus_id == null) {
				window.open("syntax_browser.php?chapter=" + chapter_id + "&cyr=" + script + "&sent_id=" + sent_id, "_blank");
			} else {
				window.open("syntax_browser.php?" + corpus_id + "chapter=" + chapter_id + "&cyr=" + script + "&sent_id=" + sent_id, "_blank");
			};
		};
	};

	var readStid = function() {
		var stid = this.classList.item(1);
	//	console.log(stid);
		for (var i=0; i < tokens.length; i++) {
			if (tokens[i].classList.item(1) == stid) {
				tokens[i].style.background = "#d8dee4";
			};
		};
	};
	
	var purgeStid = function() {
		var stid = this.classList.item(1);
		for (var i=0; i < tokens.length; i++) {
			if (tokens[i].classList.item(1) == stid) {
				tokens[i].style.background = "white";
			};
		};
	};

	for (var i=0; i < tokens.length; i++) {
		tokens[i].addEventListener('click', browseSyntax, false);
		tokens[i].addEventListener('mouseover', readStid, false);
		tokens[i].addEventListener('mouseout', purgeStid, false);
	};

	var changeFont = function(fontName) {
		var headers = document.getElementsByClassName('header');
		var rest = document.getElementsByClassName('ext');
		for (var i = 0; i < tokens.length; i++) {
			tokens[i].style.cssText = fontName;
		};
		for (var i = 0; i < headers.length; i++) {
			headers[i].style.cssText = fontName;
		};
		for (var i = 0; i < rest.length; i++) {
			rest[i].style.cssText = 'font-family: Arial; font-size: 12px;';
		};
	};
	
</script>

</body>
</html>