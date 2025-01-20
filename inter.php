
<!DOCTYPE HTML>

<html>

<head>
	<meta charset="utf-8" />

<script type="text/javascript">

	var tokens = document.getElementsByClassName("token");

	var changeFont = function(fontName) {
		var rest = document.getElementsByClassName('ext');
		for (var i = 0; i < tokens.length; i++) {
			tokens[i].style.cssText = fontName;
		};
		for (var i = 0; i < rest.length; i++) {
			rest[i].style.cssText = 'font-family: Arial; font-size: 12px;';
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
	
	window.onload = function(){
		for (var i=0; i < tokens.length; i++) {
			tokens[i].addEventListener('mouseover', readStid, false);
			tokens[i].addEventListener('mouseout', purgeStid, false);
		};
	};
	
</script>
	
</head>

<style>
	td {
		vertical-align: top;
		padding: 5px;
	}	
	.verse	{
		color: red;
		font-weight: bold;
	}
</style>

<body>	
	<?php
		
		// generiert einen Psalm mit paralleller Stelle aus anderer Webseite
		
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

		$corpus_id = "corpus=lt&";
		$chapter_url = "editions/pss.txt";
		$chapter_id = "pss";

		$psalm = null;
		$masor = null;
		$verse = 1;
		$vmod = 1;
		$target = null;
		$ref = null;
		$prev = null;
		$next = null;
		
		if (isset($_GET["verse"]) == TRUE) {
			$target = strtok($_GET["verse"], "-");
			$verse = substr(strrchr($_GET["verse"], "-"), 1);
			
			$prev = intval($target) - 1;
			$next = intval($target) + 1;
		}
		
		if (isset($_GET["ref"]) == TRUE)
			$ref = $_GET["ref"];
		
		// macht sich eine Liste der Lemmas mit Übersetzungen
		$lemmas_url = "lemmas.txt";
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
		
		echo "<b>Psalm " . $target . "</b>" . nl2br("\n");
		
		// Navigation zu den anderen Psalmen
		if ($prev !== null and $prev > 0)
			echo "<a href=\"inter_view.php?ref=" . $ref . "&cyr=" . $script . "&verse=" . $prev . "-1\">previous</a>" . nl2br("\n");
		if ($next !== null and $next < 152)
			echo "<a href=\"inter_view.php?ref=" . $ref . "&cyr=" . $script . "&verse=" . $next . "-1\">next</a>";
		echo nl2br("\n\n");
		
		// Psalter View
		echo "<a href=\"psalter_view.php?single=true&cyr=" . $script . "&verse=" . $target . "-" . $verse . "\">psalter view</a>" . nl2br("\n\n");
		
		echo "<table><tr><td width=50%>";
		
		// Schrift-Steuerung
		if ($script == 1) {
			echo "<a href=\"inter_view.php?ref=" . $ref . "&cyr=3&verse=" . $target . "-" . $verse . "\">Cyrillic</a>" . " " . "<a href=\"inter_view.php?ref=" . $ref . "&cyr=0&verse=" . $target . "-" . $verse . "\">Latin</a>" . " " . "<a href=\"inter_view.php?ref=" . $ref . "&cyr=2&verse=" . $target . "-" . $verse . "\">diplomatic</a>";
		} elseif ($script == 0) {
			echo "<a href=\"inter_view.php?ref=" . $ref . "&cyr=1&verse=" . $target . "-" . $verse . "\">Glagolitic</a>" . " " . "<a href=\"inter_view.php?ref=" . $ref . "&cyr=3&verse=" . $target . "-" . $verse . "\">Cyrillic</a>" . " " . "<a href=\"inter_view.php?ref=" . $ref . "&cyr=2&verse=" . $target . "-" . $verse . "\">diplomatic</a>";
		} elseif ($script == 2) {
			echo "<a href=\"inter_view.php?ref=" . $ref . "&cyr=1&verse=" . $target . "-" . $verse . "\">Glagolitic</a>" . " " . "<a href=\"inter_view.php?ref=" . $ref . "&cyr=3&verse=" . $target . "-" . $verse . "\">Cyrillic</a>" . " " . "<a href=\"inter_view.php?ref=" . $ref . "&cyr=0&verse=" . $target . "-" . $verse . "\">Latin</a>";
		} elseif ($script == 26) {
			echo "<a href=\"inter_view.php?ref=" . $ref . "&cyr=1&verse=" . $target . "-" . $verse . "\">Glagolitic</a>" . " " . "<a href=\"inter_view.php?ref=" . $ref . "&cyr=0&verse=" . $target . "-" . $verse . "\">Latin</a>" . " " . "<a href=\"inter_view.php?ref=" . $ref . "&cyr=2&verse=" . $target . "-" . $verse . "\">diplomatic</a>";
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
		
		if (($handle_chapter = fopen($chapter_url, "r")) !== FALSE) {
			while (($data = fgetcsv($handle_chapter, 0, "\t")) !== FALSE) {
										
				// wir brauchen keine Headers, Marginalia und leere Zeilen
				if ($data[9] !== "UD_type" and $data[9] !== "mrg" and empty($data[0]) == FALSE) {
					
					if (empty($data[22]) == FALSE) {
						
						// wenn der Psalm schon generiert ist, ein IFrame mit paralleler Stelle wird gemacht
						if ($data[22] == (intval($target) + 1)) {
							echo "</td><td>";
							
							// Version-Auswahl
							echo "<a href=\"inter_view.php?ref=bren&cyr=" . $script . "&verse=" . $target . "-" . $verse . "\">Brenton</a>" . " " .
							"<a href=\"inter_view.php?ref=elis&cyr=" . $script . "&verse=" . $target . "-" . $verse . "\">Elisabethan</a>" . " " .
							"<a href=\"inter_view.php?ref=hebr&cyr=" . $script . "&verse=" . $target . "-" . $verse . "\">Hebrew</a>" . " " . 
							"<a href=\"inter_view.php?ref=kral&cyr=" . $script . "&verse=" . $target . "-" . $verse . "\">Kralice</a>" . " " .
							"<a href=\"inter_view.php?ref=luth&cyr=" . $script . "&verse=" . $target . "-" . $verse . "\">Luther</a>" . " " .
							"<a href=\"inter_view.php?ref=niv&cyr=" . $script . "&verse=" . $target . "-" . $verse . "\">NIV</a>" . " " .
							"<a href=\"inter_view.php?ref=lxx&cyr=" . $script . "&verse=" . $target . "-" . $verse . "\">LXX</a>" . " " .
							"<a href=\"inter_view.php?ref=vulg&cyr=" . $script . "&verse=" . $target . "-" . $verse . "\">Vulgata</a>" . " " .
							nl2br("\n");
			
							if ($ref !== null) {
								
								// die LXX Nummerierung wird in wenigeren Quellen benutzt
								
								$link = "";
								
								if ($ref == "bren")
									$link = "https://biblehub.com/sep/psalms/" . $masor . ".htm#leftbox";
								else if ($ref == "elis")
									$link = "https://azbyka.ru/bogosluzhenie/cu/psalms/#ps" . $target;
								else if ($ref == "hebr")
									$link = "https://biblehub.com/text/psalms/" . $masor . "-" . $vmod . ".htm#leftbox";
								else if ($ref == "kral") 
									$link = "https://cs.wikisource.org/wiki/Bible_kralick%C3%A1_(1918)/%C5%BDalmy#" . $masor . ":" . $vmod;
									// alt.: $link = "https://cb.cz/budejovice/oldweb/cz/bk/Z" . $masor . ".htm";
							//	else if ($ref == "klem")
							//		$link = "https://diabible.ujc.cas.cz/bible/zaltklem/ps/" . $target . "/" . $verse;
								else if ($ref == "luth")
									$link = "https://www.bibel-online.net/buch/luther_1545_letzte_hand/psalm/" . $masor . "#open";
								else if ($ref == "lxx")
									$link = "https://biblebento.com/index.html?lxx1&230." . $target . "." . $vmod;
								else if ($ref == "niv")
									$link = "https://biblehub.com/niv/psalms/" . $masor . "-" . $vmod . ".htm#l1";
								else if ($ref == "vulg")
									$link = "https://la.wikisource.org/wiki/Vulgata_Clementina/Liber_Psalmorum#Caput_" . $target;
								
								echo "<iframe width=600px height=600px src='" . $link . "'></iframe>" . nl2br("\n\n") . "<a target=_blank href='" . $link . "' >source</a>";
							}
							
							echo "</td>";
						}
				
						$psalm = $data[22];
					
						// Behandlung der Diskrepanzen in Nummerierung
						$masor = $data[20];
						if (str_contains($masor, "-") == TRUE)
							$masor = strtok($masor, "-");
						
						if ($psalm == "9" and intval($verse) > 21) {
							$masor = "10";
							$vmod = intval($verse) - 21;
						}
						else if ($psalm == "113" and intval($verse) > 8) {
							$masor = "115";
							$vmod = intval($verse) - 8;
						}
						else if ($psalm == "147")
							$vmod = intval($verse) + 11;
						else
							$vmod = $verse;
						
					}
					
					if ($psalm == $target) {
												
						if (empty($data[24]) == FALSE)
							echo "<br/><span class='verse'>" . $psalm . ":" . $data[24] . "</span> ";
						else if (isset($data[22]) == TRUE and $data[24] == 0)
							echo "<br/><span class='verse'>" . $psalm . "</span> ";
						
						// der Text selbst
						// Token-ID generiert nach Satz und Position
						echo "<span class='token " . $data[6] . "' >";
							
						// boldface, wenn es Übersetzung gibt
						if (empty($data[13]) == FALSE or empty($data[16]) == FALSE)
							 echo "<b>" . $data[$script] . "</b> ";
						else
							echo $data[$script] . " ";
					
						// Inhalt des Pop-up's (beim Mouse-over)
						// Lemma
						echo "<span class='ext'> lemma: <i>" . $data[3] . " </i>";
						
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

				}
			}
		}
		fclose($handle_chapter);
		
		echo "</tr></table>";
		
	?>		

</body>

</html>