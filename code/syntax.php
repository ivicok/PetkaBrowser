
<!DOCTYPE HTML>

<html>

<head>
	<meta charset="utf-8" />

	<!-- brat/UD 1/3 -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script type="text/javascript" src="https://universaldependencies.org/lib/ext/head.load.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/anchor-js/3.2.2/anchor.min.js"></script>
	<script>document.addEventListener("DOMContentLoaded", function(event) {anchors.add();});</script>
    <script type="text/javascript" src="https://universaldependencies.org/lib/ext/head.load.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/anchor-js/3.2.2/anchor.min.js"></script>
	<link rel='stylesheet' type='text/css' href='https://universaldependencies.org/css/style-vis.css' />

</head>

<body>	

	<!-- brat/UD 2/3 -->
	<script type="text/javascript">
		console.time('loading libraries');
		var root = 'https://universaldependencies.org/'; // filled in by jekyll
		head.js(
			root + 'lib/ext/jquery.min.js',
			root + 'lib/ext/jquery.svg.min.js',
			root + 'lib/ext/jquery.svgdom.min.js',
			root + 'lib/ext/jquery.timeago.js',
			root + 'lib/ext/jquery-ui.min.js',
			root + 'lib/ext/waypoints.min.js',
			root + 'lib/ext/jquery.address.min.js'
		);
	</script>

	<?php
		
		error_reporting(E_ERROR | E_CORE_ERROR | E_PARSE );
		// ini_set('display_errors', 1); error_reporting(~0);
		set_time_limit(6000);
		$start = microtime(true);
		
		// Syntax-Analyse
		// übernimt "cyr" und "chapter" von dem output.php
		// neues Parameter "sent_id" steuert den Satz-Auswahl
		
		if ($_GET["cyr"] == 1) {
			$script_str = "cyr";
			$script = 1;
		} elseif ($_GET["cyr"] == 2) {
			$script_str = "dipl";
			$script = 2;
		} elseif ($_GET["cyr"] == 3 or $_GET["cyr"] == 26) {
			$script_str = "cyr2";
			$script = 26;
		} else {
			$script_str = "lat";
			$script = 0;
		}
		
		$chapter_id = $_GET["chapter"];
		$trans = "";
		$element = 0;
		$ud_ready = TRUE;
		$is_more = FALSE;
		$brat = 0;
		$psalm = 0;
		$verse = 0;
		$links_done = FALSE;
		$lemmas_url = "lemmas.txt";
		$spreadsheet_url = "";
		
		if (isset($_GET["sent_id"]) == FALSE)
			$sent_id = "1";
		else
			$sent_id = $_GET["sent_id"];

		// Liste der Kapitel mit Links
		if ($_GET["corpus"] == "petka") {
			$src_url="src_p.txt";
			$title = 15;
			$corpus_id = "corpus=petka&";
		} elseif ($_GET["corpus"] == "sva") {
			$src_url="src_sva.txt";
			$title = 15;
			$corpus_id = "corpus=sva&";
		} elseif ($_GET["corpus"] == "lt") {
			$src_url="src_lt.txt";
			$title = 15;
			$corpus_id = "corpus=lt&";
		} else {
			$src_url="src.txt";
			$title = 15;
			$corpus_id = "corpus=punco&";
		}
		
		// wählt das Kapitel nach Input von der Index-Seite
		$handle_a = fopen($src_url, "r");			
		$file = file($src_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
			$dataa = fgetcsv($handle_a, 0, "\t");
			if (empty($dataa[0]) == FALSE)
				if ($dataa[0] == $chapter_id) {
					$spreadsheet_url = $dataa[1];
					$prev = $dataa[2];
					$next = $dataa[3];
				}
		}
		fclose($handle_a);
		
	
		// Petka rettet uns, falls etwas schief geht
		if ($spreadsheet_url == "") { 
			echo "error loading source file" . nl2br("\n\n");
			$spreadsheet_url="pps/020.txt";
		}

		// macht sich eine Liste der Lemmas mit Übersetzungen
		$handle_b = fopen($lemmas_url, "r");
		$file = file($lemmas_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
				$lemma_data = fgetcsv($handle_b, 0, "\t");
				if (empty($lemma_data[0]) == FALSE) {
					$lemmas[$row][0] = $lemma_data[0];	// Lemma selbst
					$lemmas[$row][1] = $lemma_data[2];	// die Übersetzung
					$lemmas[$row][2] = $lemma_data[11];	// SJS id
					$lemmas[$row][3] = $lemma_data[12];	// SNSP id
					$lemmas[$row][4] = $lemma_data[13];	// Miklosich 1865 id
					$lemmas[$row][5] = $lemma_data[5];	// Inflektionsklasse
			//		if (empty($lemma_data[9]) == FALSE)
			//			$lemmas[$row][5] = $lemmas[$row][5] . ", " . $lemma_data[9];	// Aspekt wird in Annotation gezeigt
					$lemmas[$row][6] = $lemma_data[6];	// Notes
					$lemmas[$row][7] = $lemma_data[14];	// Präfixe
					$lemmas[$row][8] = $lemma_data[10];	// Suffixe
					$lemmas[$row][9] = $lemma_data[3];	// Wortkategorie
			//	echo $lemmas[$row][0] . " " . $lemmas[$row][1] . nl2br("\n");
					$lemmas[$row]["LOVe"] = $lemma_data[15];	// LOVe id
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

		// wichtige Daten werden hier für JavaScript gesammelt
		// Schrift muss als String angegeben werden, so dass es nicht mit dem $sent_id interferiert
		echo "<span id='data' class='" . $chapter_id . " " . $script_str . " " . $sent_id . " " . $corpus_id . "' />";

		// Daten für die Kontext-Analyse
		$readings = [
			"1" => "actual-processual",
			"2" => "conative",
			"3" => "inactual-continuous",
			"4" => "durative",
			"5" => "general-factual",
			"6" => "iterative",
			"7" => "habitual",
			"8" => "potential",
			"9" => "permanent-atemporal",
			"1e" => "future-potential",
			"4e" => "chained event",
			"5e" => "isolated event",
			"6e" => "iterative-summary",
			"7e" => "exemplifying",
			"8e" => "present-potential",
			"b" => "benefactive (benevolent optative)",
			"c" => "conditional",
			"d" => "deontic",
			"f" => "destined future",
			"h" => "hypothetic",
			"i" => "imperative",
			"o" => "optative (neutral)",
			"p" => "prayer (optative towards God)",
			"q" => "interrogative",
			"t" => "telic",
			"u" => "curse (malevolent optative)",
			"v" => "volitive future",
			"x" => "experiential perfect",
			"y" => "universal perfect",
			"z" => "resultative perfect",
		];


		if(!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";

		if (($handle = fopen($spreadsheet_url, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {

				if (empty($data[0]) == FALSE) {
					
					// für Psalmen
					if (empty($data[22]) == FALSE)
						$psalm = $data[22];
					if (empty($data[24]) == FALSE or $data[24] == 0)
						$verse = $data[24];
					
					// Titel des Kapitels, Navigation und Satz-Nummer am Anfang
					if ($data[14] == "0") {
						echo $data[$title];
						echo nl2br("\n\n");
					}
					
					// Link zum Chapter View
					if ($data[6] == $sent_id and $links_done == FALSE) {
						if ($chapter_id == "pss")
							echo "<a target='_blank' href='psalter_view.php?cyr=" . $script . "&single=true&verse=" . $psalm . "-" . $verse . "&sent_id=" . $sent_id . "'>chapter view</a>";
						else
							echo "<a target='_blank' href='chapter_view.php?" . $corpus_id . "chapter=" . $chapter_id . "&cyr=" . $script . "&sent_id=" . $sent_id . "'>chapter view</a>";
						echo nl2br("\n\n");
						
						echo "sentence " . $sent_id;
						if ($chapter_id == "pss")
							echo nl2br("\n") . "Psalm " . $psalm . ":" . $verse;
						echo nl2br("\n\n");
						$links_done = TRUE;
					}

					// nur der geclickte Satz kommt vor
					if ($data[6] == $sent_id) {
						
						// Übersetzung
						if (empty($data[13]) == FALSE) { 
							if ($data[7] == "1") {
								$trans = $data[13];
							} elseif ($element > 1) {
								echo nl2br("\n") . $trans . nl2br("\n\n");
								$trans = $data[13];
							}
						}
						
						$token[$element]["id"] = $data[7];
						$token[$element]["text"] = $data[$script];
						$token[$element]["dipl"] = $data[2];
						$token[$element]["ud_ncy"] = $data[8];
						$token[$element]["ud_type"] = $data[9];
						$token[$element]["notes"] = $data[16];
						$token[$element]["read"] = $data[27];
						
						if ($data[9] == "")
							$ud_ready = FALSE;
						
						if ($data[10] !== "")
							$token[$element]["ud_type"] = $data[9] . ":" . $data[10];
						
						// der Text: mit Daten für den JS Diagramm vorbereitet
						echo "<span class='token " . $data[7] . " " . $data[8] . " " . $data[9];	// UD_id, UD_ncy, UD_type
						if ($data[10] !== "") echo ":" . $data[10];									// UD_ext
						echo " " . $data[4]; 														// PoS Tag
						if ($data[5] !== "") echo "/" . $data[5];									// alt.PoS
						echo " " .  $data[$script] . "'><b>" . $data[$script] . " </b>";		// Token selbst
						
						foreach($lemmas as $lemma)
							if ($data[3] == $lemma[0]) {
								$token[$element]["lemma"] = $lemma[0];																
								$token[$element]["cyr"] = $lemma[0];
								$token[$element]["trans"] = $lemma[1];
								$token[$element]["SJS"] = $lemma[2];
								$token[$element]["SNSP"] = $lemma[3];
								$token[$element]["Mikl"] = $lemma[4];
								$token[$element]["LOVe"] = $lemma["LOVe"];
								$token[$element]["class"] = $lemma[5];
								$token[$element]["notes_2"] = $lemma[6];
								$token[$element]["prefix"] = $lemma[7];
								$token[$element]["suffix"] = $lemma[8];
								$token[$element]["cat"] = $lemma[9];
								
							}
							
						$token[$element]["tag"] = $data[4];
						$token[$element]["msd"] = $data[4];
						foreach ($msds as $msd)
							if ($token[$element]["tag"] == $msd[0])
								$token[$element]["msd"] = $msd[1];
							
						if (empty($data[5]) == FALSE) {
							$token[$element]["alt_tag"] = $data[5];
							$token[$element]["alt_msd"] = $data[5];
							foreach ($msds as $msd)
								if ($token[$element]["alt_tag"] == $msd[0])
									$token[$element]["alt_msd"] = $msd[1];
						}
						
						if (empty($data[28]) == FALSE)
							$token[$element]["stem"] = $data[28];
						
					//	unset($pos);		
						echo "<span class=ext>  lemma: <i>" . $data[3] . " </i> '" . $token[$element]["trans"] . "'<br />";
						echo " form: <font style='font-variant: small-caps'>" . $token[$element]["msd"] . "</font>";
						if (empty($data[5]) == FALSE)
							echo "<br /> alt.analysis: <font style='font-variant: small-caps'>" . $token[$element]["alt_msd"] . "</font>";
						
						if (empty($data[16]) == FALSE)
							echo "<br /><br />". $data[16];
						
						echo "<br /><br /></span></span>";
						
						$element++;
						if ($element > 1) $is_more = TRUE;
						
						// für den brat/UD Visual
						if ($ud_ready == TRUE) {
							$brat_token[$element] = $data[2];
							$brat_id[$element] = $data[7] + "1";
							$brat_ncy[$element] = $data[8] + "1";
							if ($data[10] !== "")
								$brat_type[$element] = $data[9] . ":" . $data[10];
							else
								$brat_type[$element] = $data[9];
						}
						
					}
				}
			}
			fclose($handle);

			echo nl2br("\n") . $trans . nl2br("\n\n");
			
			echo "total elements: " . $element;
		}


		echo nl2br("\n\n\n");

		if ($ud_ready == TRUE) {
			// vertikale...
			echo "tree view (<a target=_blank href='svg.php?" . $corpus_id . "chapter=" . $chapter_id . "&cyr=" . $script . "&sent_id=" . $sent_id . "'>.svg</a>)" . nl2br("\n");
			// ...und lineare Darstellung der Dependenzen
			echo "linear view (<a href=https://universaldependencies.org/visualization.html>Embedded brat</a>):" . nl2br("\n");
		} else
			echo "syntax annotation not available" . nl2br("\n");
		
		// .conllu-Export
		echo "<a target='_blank' href='conllu2.php?" . $corpus_id . "chapter=" . $chapter_id . "&cyr=" . $script . "&sent_id=" . $sent_id . "'>view .conllu</a>" . nl2br("\n");
		
		if ($ud_ready == TRUE) {
			echo nl2br("\n") . " <div style='https://universaldependencies.org/css/jquery-ui-redmond.css' class='example'><div style='border: 1px solid #7fa2ff'><pre><code class='language-sdparse'>ROOT ";
			for ($i = 1; $i <= $element; $i++)
				echo $brat_token[$i] . " ";
			echo nl2br("\n");
			for ($i = 1; $i <= $element; $i++) {
				echo $brat_type[$i] . "(" . $brat_token[$i] . "-" . $brat_id[$i] . ", ";
				if ($brat_ncy[$i] == 1)
					echo "ROOT)";
				else {
					for ($ii = 1; $ii <= $element; $ii++) {
						if ($brat_ncy[$i] == $brat_id[$ii]) echo $brat_token[$ii] . "-" . $brat_id[$ii] . ")";
					}
				}
				if ($i == $element)
					echo "</code></pre></div></div>";
				else
					echo nl2br("\n");
			}
		}
		
		echo nl2br("\n\n");
		
		// einzelne Tokens
		foreach ($token as $tok) {

			echo "<span id='" . $tok["id"] . "'><b>" . $tok["text"] . "</b>" . nl2br("\n") . "<i>" . $tok["dipl"] . "</i>";
			
			if (isset($tok["lemma"])) {
				echo nl2br("\n") . "lemma: <i>" . $tok["lemma"] . "</i> '" . $tok["trans"] . "' ";
		
				if ($tok["SJS"] !== '')
					echo "<a target=_blank href='http://gorazd.org/gulliver/?envLang=en&recordId=" . $tok["SJS"] . "'>SJS</a> ";
				if ($tok["SNSP"] !== '')
					echo "<a target=_blank href='http://gorazd.org/gulliver/?envLang=en&recordId=" . $tok["SNSP"] . "'>SNSP</a> ";
				if ($tok["Mikl"] !== '')
					echo "<a target=_blank href='http://monumentaserbica.branatomic.com/mikl2/main.php?id=" . $tok["Mikl"] . "'>Miklosich</a> ";
				if ($tok["LOVe"] !== '')
					print " <a target=_blank href='love.php?root=" . $tok["LOVe"] . "'>LOVe</a> ";
				if ($tok["lemma"] !== '')
					echo "<a target=_blank href='search_lemma.php?lemma=" . $tok["lemma"] . "'>search</a> ";
				if ($tok["notes_2"] !== '')
					echo nl2br("\n") . "<font size=1>" . $tok["notes_2"] . "</font>";
			
				if ($tok["class"] !== '')
					echo nl2br("\n") . "inflection: <font style='font-variant: small-caps'>" . $tok["class"] . "</font>";
				
				if ($tok["prefix"] !== "")
					echo nl2br("\n") . "prefixes: " . $tok["prefix"];
				if ($tok["suffix"] !== "")
					echo nl2br("\n") . "suffixes: " . $tok["suffix"];
				
			}

			echo nl2br("\n") . "tag: ";
			echo "<font style='font-variant: small-caps'>". $tok["tag"] . "</font>";
			if (isset($tok["alt_tag"]))
				echo " or <font style='font-variant: small-caps'>" . $tok["alt_tag"] . "</font>";
			echo nl2br("\n") . "form: ";
			echo "<font style='font-variant: small-caps'>". $tok["msd"] . "</font>";
			if (isset($tok["alt_tag"]))
				echo " or <font style='font-variant: small-caps'>" . $tok["alt_msd"] . "</font>";			
			
			echo nl2br("\n") . "element " . $tok["id"];
			echo nl2br("\n") . "dependency: <font style='font-variant: small-caps'>" . $tok["ud_type"] . "</font>→" . $tok["ud_ncy"];
			
			if (isset($tok["read"]) and $tok["read"] !== "") {
				echo nl2br("\n") . "readings: ";
				$read = explode('-', $tok["read"]);
				for ($i = 0; $i < sizeof($read); $i++) {
					if ($i > 0)
						echo ", ";
					echo $readings[$read[$i]];
				}
			}
			
			if ($tok["notes"] !== '')
				echo nl2br("\n") . "<font size=1>" . $tok["notes"] . "</font>";
			
			echo "</span>" . nl2br("\n\n");
		}

	?>
		

<script>

	var tokens = document.getElementsByClassName("token");
	var data = document.getElementById("data");
	
	var chapter_id = data.classList.item(0);
	var script = data.classList.item(1);
	var sent_id = data.classList.item(2);
	var corpus_id = data.classList.item(3);
	
	if (script == "cyr") {
		var script =  "1";
	} else if (script == "dipl") {
		var script =  "2";
	} else if (script == "cyr2") {
		var script =  "3";
	} else {
		var script =  "0";
	};

	var browseToken = function() {
		if (sent_id == null) {
			sent_id = "1";
		};
		var tok_id =  this.classList.item(1);
		console.log(tok_id);
		document.getElementById(tok_id).scrollIntoView();
		window.scrollBy(0, -72)

	};
	
	var readStid = function() {
		var ud_id = this.classList.item(1);
	//	var ud_ncy = this.classList.item(2);
		for (var i=0; i < tokens.length; i++) {
			// Highlight für das Token selbst
			if (tokens[i].classList.item(1) == ud_id) {
				tokens[i].style.background = "#d8dee4";
			};
			// Highlight für seine Dependenten
			if (tokens[i].classList.item(2) == ud_id) {
				tokens[i].style.background = "#ebedf0";
			};
		};
	};
	
	var purgeStid = function() {
		var ud_id = this.classList.item(1);
	//	var ud_ncy = this.classList.item(2);
		for (var i=0; i < tokens.length; i++) {
			if (tokens[i].classList.item(1) == ud_id) {
				tokens[i].style.background = "white";
			};
			if (tokens[i].classList.item(2) == ud_id) {
				tokens[i].style.background = "white";
			};
		};
	};

	for (var i=0; i < tokens.length; i++) {
		tokens[i].addEventListener('click', browseToken, false);
		tokens[i].addEventListener('mouseover', readStid, false);
		tokens[i].addEventListener('mouseout', purgeStid, false);
	};
	
</script>

<!-- brat/UD 3/3 -->
<script type="text/javascript">

    var root = 'https://universaldependencies.org/';
    head.js(
        root + 'lib/brat/configuration.js',
        root + 'lib/brat/util.js',
        root + 'lib/brat/annotation_log.js',
        root + 'lib/ext/webfont.js',
        root + 'lib/brat/dispatcher.js',
        root + 'lib/brat/url_monitor.js',
        root + 'lib/brat/visualizer.js',
        root + 'lib/local/config.js',
        root + 'lib/local/collections.js',
        root + 'lib/annodoc/annodoc.js',
    );

    var webFontURLs = [
        root + 'static/fonts/PT_Sans-Caption-Web-Regular.ttf',
        root + 'static/fonts/Liberation_Sans-Regular.ttf'
    ];

    var setupTimeago = function() {
        jQuery("time.timeago").timeago();
    };

    head.ready(function() {
        setupTimeago();
        Collections.listing['_current'] = '';
		Annodoc.activate(Config.bratCollData, Collections.listing);
    });
	
</script>


</body>
</html>