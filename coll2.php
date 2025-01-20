
<html>

<head>
	<meta charset="utf-8" />
</head>

<body>
	<?php
		
		error_reporting(E_ERROR | E_CORE_ERROR | E_PARSE );
		set_time_limit(6000);
		$start = microtime(true);
		
		if ($_GET["cyr"] == 1) {
			$script = 1;
		} elseif ($_GET["cyr"] == 2) {
			$script = 2;
		} else {
			$script = 0;
		}
		
		$corpus_id = "corpus=dict&";
		$eols = 0;
		
		if ($_GET["chapter"] == null) {
			$chapter_id = "ptk";
		} else 
			$chapter_id = $_GET["chapter"];
		
		// Petka bekommt eine besondere Behandlung
		if ($_GET["corpus"] == "petka") {

			$corpus_id = "corpus=petka&";
			
			// Vuković 1536, Tixon.d. und Berl.d. als Default
			$src_url = array("vuk", "tix", "ber");
			
			if ($_GET["src0"] !== null) $src_url[0] = $_GET["src0"];
			if ($_GET["src1"] !== null) $src_url[1] = $_GET["src1"];
			if ($_GET["src2"] !== null) $src_url[2] = $_GET["src2"];
			
			$srcs_url="src_p.txt";
			$handle_a = fopen($srcs_url, "r");
			$file = file($srcs_url);
			$rows = count($file);
			for ($row = 0; $row <= $rows; $row++) {
				$dataa = fgetcsv($handle_a, 0, "\t");
				for ($i = 0; $i <= 2; $i++)
					if ($dataa[0] == $src_url[$i]) $src_url[$i] = $dataa[1];
			}
			fclose($handle_a);
		
		// Alexander auch
		} elseif ($_GET["corpus"] == "sva") {

			$corpus_id = "corpus=sva&";
			
			// Veles, Sofia und Adžar als Default
			$src_url = array("667", "116", "326");
			
			if ($_GET["src0"] !== null) $src_url[0] = $_GET["src0"];
			if ($_GET["src1"] !== null) $src_url[1] = $_GET["src1"];
			if ($_GET["src2"] !== null) $src_url[2] = $_GET["src2"];
			
			$srcs_url="src_sva.txt";
			$handle_a = fopen($srcs_url, "r");
			$file = file($srcs_url);
			$rows = count($file);
			for ($row = 0; $row <= $rows; $row++) {
				$dataa = fgetcsv($handle_a, 0, "\t");
				for ($i = 0; $i <= 2; $i++)
					if ($dataa[0] == $src_url[$i]) $src_url[$i] = $dataa[1];
			}
			fclose($handle_a);
			
		} else {
		
			$srcs_url="src_coll.txt";
			
			
			if ($spreadsheet_url == "") {
				$handle_a = fopen($srcs_url, "r");
				$file = file($srcs_url);
				$rows = count($file);
				for ($row = 0; $row <= $rows; $row++) {
					$dataa = fgetcsv($handle_a, 0, "\t");
					if ($dataa[0] == $chapter_id) {

						$src_corpus[0] = $dataa[2];
						$src_chapter[0] = $dataa[3];
						$src_url[0] = $dataa[4];
				
						$src_corpus[1] = $dataa[5];
						$src_chapter[1] = $dataa[6];
						$src_url[1] = $dataa[7];
						
						// jetzt ist Limit der Quellen hardcoded auf 3
						if ($dataa[1] == 3) {
							$src_corpus[2] = $dataa[8];
							$src_chapter[2] = $dataa[9];
							$src_url[2] = $dataa[10];
						}
						
						$title = $dataa[11];
						
						// für vor-alignierten parallelen Texten
						if ($dataa[12] == 1)
							$eols = 1;
						
					}
				}
				fclose($handle_a);
			}
		}

		// wichtige Daten werden nochmals hier für JavaScript gesammelt
		echo "<span id='data' class='" . $chapter_id . " " . $script . " " . $corpus_id . " ' />";

		// für Kollationen aus vorbestimmter Liste
		// wieder hardcoded auf max. 3
		if ($_GET["corpus"] == null) {
			echo "<span id='corpora' class='" . $src_corpus[0] . " " . $src_corpus[1] . " " . $src_corpus[2] . " ' />";
			echo "<span id='sources' class='" . $src_chapter[0] . " " .  $src_chapter[1] . " " .  $src_chapter[2] . " ' />";
		}

		if(!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";

		$row = 0;
		$line = 1;


		// macht sich eine Liste der Lemmas mit Übersetzungen
		$lemmas_url= "lemmas.txt";
		$handle_b = fopen($lemmas_url, "r");
		$file = file($lemmas_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
				$lemma_data = fgetcsv($handle_b, 0, "\t");
				$lemmas[$row][0] = $lemma_data[0];	// Lemma selbst
				$lemmas[$row][1] = $lemma_data[2];	// die Übersetzung
			}
		fclose($handle_b);
		
		// Liste der PoS-Tags
		$msd_url= "msd.txt";
		$handle_c = fopen($msd_url, "r");
		$file = file($msd_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
				$msd_data = fgetcsv($handle_c, 0, "\t");
				$msds[$row][0] = $msd_data[0];	// PoS Tag selbst
				$msds[$row][1] = $msd_data[2];	// die Leipzig-Notation
			}
		fclose($handle_c);

		// erstellt ein Array mit allen Daten
		$srcs = count($src_url);
		$chunk = 0;
		$chunks = 0;
		for ($src = 0; $src < $srcs; $src++) {
			$handle_src = fopen($src_url[$src], "r");
			$file = file($src_url[$src]);
			$rows = count($file);
			for ($row = 0; $row <= $rows; $row++) {
				$data = fgetcsv($handle_src, 0, "\t");
				
				// ignoriert Header
				if ($data[0] !== "text") {
					
					if ($data[14] == "0")
						$src_desc[$src] = $data[15];
					
					// Chunks sind Gruppen von Tokens, relevant für die Ausbau des Textes als Ganzen
					// z.B. Verse, Episoden...
					if ($data[22] !== "") {
						if ($chunk !== $data[22]) {
							$chunk = $data[22];
							
							// optionaler Titel für die Chunks wird von der ersten Quelle genommen
							if ($src == 0) {
								$desc[$chunk] = $data[23];
								$chunks++;
							}
						}
					}
					
					// ignoriert leere Zeilen (Satzgrenzen)
					if ($data[0] !== "") {
						
						$tok_id = $data[6] . "_" . $data[7];
						
						$token[$src][$chunk][$tok_id]["text"] = $data[$script];
						$token[$src][$chunk][$tok_id]["lemma"] = $data[3];
						$token[$src][$chunk][$tok_id]["PoS"] = $data[4];
						$token[$src][$chunk][$tok_id]["PoS_ext"] = $data[5];

						$token[$src][$chunk][$tok_id]["sent"] = $data[6];
						$token[$src][$chunk][$tok_id]["UD_ncy"] = $data[8];
						$token[$src][$chunk][$tok_id]["UD_type"] = $data[9];
						$token[$src][$chunk][$tok_id]["UD_ext"] = $data[10];
						
						if ($data[13] !== "") $token[$src][$chunk][$tok_id]["trans"] = $data[13];
						if ($data[16] !== "") $token[$src][$chunk][$tok_id]["notes"] = $data[16];
						if ($data[24] !== "") $token[$src][$chunk][$tok_id]["pic"] = $data[24];
						
						// end-of-line Markierung, falls gewünscht
						if ($eols == 1 and $data[14] !== "") $token[$src][$chunk][$tok_id]["eol"] = $data[14];
						
						// Bilder werden nur vom ersten Source gelesen
						if ($src == 0 and $data[12] !== "") $token[$src][$chunk][$tok_id]["img"] = $data[12];
						
						$token[$src][$chunk][$tok_id]["src"] = $src;
					}
				}
			}
			
			fclose($handle_src);
		}

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
				</label>" . nl2br("\n\n");

		// Verzeichnis der Chunks
		echo "<a href='#' id='coll' onclick='showConts()'>table of contents</a><p id='conts' style='display:none'> " . nl2br("\n");
		for ($i = 1; $i < $chunks; $i++)
			if ($desc[$i] !== null) echo "<a href='#chunk" . $i . "'>" . $desc[$i] . "</a>" . nl2br("\n");
		echo "</p>" . nl2br("\n\n") . "<table>";						

		// Headers und Links zu den einzelnen Kapitel
		if ($src_desc[0] !== "") {
			echo "<tr><td /><td />";
			for ($i = 0; $i < $srcs; $i++) {
				$src_id[$i] = substr($src_desc[$i], 0, 3);
		//		$src_desc[$i] = substr($src_desc[$i], 4);
				if ($_GET["corpus"] == null) {
					echo "<td>" . $src_desc[$i];
					echo nl2br("\n") . "<a href='chapter_view.php?" . $src_corpus[$i] . "chapter=" . $src_chapter[$i] . "&cyr=" . $script . "' target='_blank'>chapter view</a>";
				} else {
					echo "<td width=25%>" . $src_desc[$i];
					echo nl2br("\n") . "<a href='chapter_view.php?" . $corpus_id . "chapter=" . $src_id[$i] . "&cyr=" . $script . "' target='_blank'>chapter view</a>";
				}
				echo "</td><td />";
			}
			echo "</tr>";
		}


		for ($i = 1; $i < $chunks; $i++) {
			
			// wenn es sie gibt, die Chunks bekommen einen Titel
			if ($desc[$i] !== null)
				echo "<tr><td /><td /><td>" . nl2br("\n") . "<h2 id='chunk" . $i . "'>" . $desc[$i] . "</h2>" . nl2br("\n") . "</td></tr>";
			else
				echo "<tr><td /><td /><td>" . nl2br("\n") . "</td></tr>";
			echo "<tr><td width=10%>";
			
			// sucht, ob es einen Bild gibt
			foreach($token[0][$i] as $tok)
				if ($tok["img"] !== null) echo "<a href=" . $tok["img"] . " target='_blank'><img src=" . $tok["img"] . " width=50></a>" . nl2br("\n");
			echo "</td>";
			
			// dann generiert den Text in separaten Spalten
			for ($ii = 0; $ii <= $srcs; $ii++) {
				echo "<td width=5%><td ";
				if ($srcs == 2)
					echo  "width=40%";
				echo ">";
				
				foreach($token[$ii][$i] as $tok) {
					
					if ($tok["text"] !== "(n/a)") {
						
						// der Text selbst
						if ($_GET["corpus"] !== null)
							echo "<span class='token " . $tok["sent"] . " " . $src_id[$ii] . "'>";
						else
							echo "<span class='token " . $tok["sent"] . " src" . $tok["src"] . "'>";
						
						// markiert Tokens mit mehr Daten
						if ((empty($tok["trans"]) == FALSE) or (empty($tok["notes"]) == FALSE))
							echo "<b>" . $tok["text"] . " </b>";
						else
							echo $tok["text"] . " ";
						
						// Inhalt des Pop-up's (beim Mouse-over)
						// Lemma
						echo "<span class=ext> lemma: <i>" . $tok["lemma"] . " </i>";
						
						// Übersetzung der Lemma
						foreach($lemmas as $lemma) {
							if ($tok["lemma"] == $lemma[0]) echo "'" . $lemma[1] . "'";
						}
						echo "<br />";
						
						// PoS-Tag
						$pos = $tok["PoS"];
						foreach ($msds as $msd)
							if ($pos == $msd[0])
								$pos = $msd[1];
						echo " form: <font style='font-variant: small-caps'>" . $pos . "</font>";
						if (empty($tok["PoS_ext"]) == FALSE) {
							$pos = $tok["PoS_ext"];
							foreach ($msds as $msd)
								if ($pos == $msd[0])
									$pos = $msd[1];
							echo "<br /> alt.analysis: <font style='font-variant: small-caps'>" . $pos . "</font>";
						}
						
						// Übersetzung
						if (empty($tok["trans"]) == FALSE) {
							echo nl2br("\n");
							echo " translation: " . $tok["trans"];
						}	

						// Bild-Beschreibung
						if (empty($tok["pic"]) == FALSE) {
							echo nl2br("\n");
							echo " picture: " . $tok["pic"];
						}
						
						// Notes
						if (empty($tok["notes"]) == FALSE) {
							echo nl2br("\n");
							echo $tok["notes"];
						}				
						
						echo nl2br("\n");						
						echo "</span></span>";
						
						// neue Linie, falls markiert
						if ($eols == 1) {
							if (empty($tok["eol"]) == FALSE)
								echo nl2br("<span> \n</span>");														
						}
					}	
				}
				echo "</td>";
			}
			
			echo "</tr>";
		}
		echo "</table>";

	?>

<script>

	var tokens = document.getElementsByClassName("token");
	var data = document.getElementById("data");
	var corpus_id = data.classList.item(2);
	var locked = false;
	
	var browseSyntax = function() {
		
		var chapter_id = data.classList.item(0);
		var script =  data.classList.item(1);
		var sent_id =  this.classList.item(1);
		var src_id =  this.classList.item(2);
		
		// Syntax Browser muss wieder mit Petka und Alexander anders umgehen		
		if (!locked) {
			if (corpus_id == "corpus=petka&" || corpus_id == "corpus=sva&" || corpus_id == "corpus=rs&") {
				
				window.open("syntax_browser.php?" + corpus_id + "chapter=" + src_id + "&cyr=" + script + "&sent_id=" + sent_id, "_blank");
				
			} else {
				
				var corps = document.getElementById("corpora");
				var srcs = document.getElementById("sources");
				src_id = src_id.split("src").pop();
				
				if (corps.classList.item(1) == null) {
					var corps_id = corps.classList.item(0)
				} else {
					var corps_id = corps.classList.item(src_id)
				};
				
				window.open("syntax_browser.php?" + corps_id + "chapter=" + srcs.classList.item(src_id) + "&cyr=" + script + "&sent_id=" + sent_id, "_blank");
				
			};
		};
		
	};

	var readStid = function() {
		var stid = this.classList.item(1);
		var src_id =  this.classList.item(2);
	//	console.log(stid);
		for (var i=0; i < tokens.length; i++) {
			if (tokens[i].classList.item(2) == src_id) {
				if (tokens[i].classList.item(1) == stid) {
					tokens[i].style.background = "#d8dee4";
				};
			};
		};
	};
	
	var purgeStid = function() {
		var stid = this.classList.item(1);
		var src_id =  this.classList.item(2);
		for (var i=0; i < tokens.length; i++) {
			if (tokens[i].classList.item(2) == src_id) {
				if (tokens[i].classList.item(1) == stid) {
					tokens[i].style.background = "white";
				};
			};
		};
	};

	for (var i=0; i < tokens.length; i++) {
		tokens[i].addEventListener('click', browseSyntax, false);
		tokens[i].addEventListener('mouseover', readStid, false);
		tokens[i].addEventListener('mouseout', purgeStid, false);
	};

	var showConts = function(){
		document.getElementById('conts').style.display = 'block';
		document.getElementById('coll').style.display = 'none';
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