
<!DOCTYPE HTML>


<html class="mod mod-layout">

<style>
  .token {
   position: relative;
   display: inline-block;
   white-space: pre-wrap;
   white-space: -moz-pre-wrap;
   word-wrap: break-word;
  }
  .token .ext {
   visibility: hidden;
   width: 200px;
   background-color: #555;
   color: #fff;
   text-align: left;
   border-radius: 2px;
   padding: 10px 10;
   position: absolute;
   z-index: 1;
   bottom: 125%;
   left: 50%;
   opacity: 0;
  }
  .token .ext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: 0px;
    border-width: 0px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
  }
  .token:hover .ext {
    visibility: visible;
	font-weight: normal;
    opacity: 1;
  } 
</style>

<head>
	<meta charset="utf-8" />
	<meta name="description" content="" />
	
	<meta name="viewport" content="user-scalable=1.0,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="format-detection" content="telephone=no" />

	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta name="ctxPath" content="${contextPath}" />

    <link rel="stylesheet" type="text/css" media="screen" href="app2.css?v=3" />

    <link rel="shortcut icon" type="image/ico" href="favicon.ico" />

	<title>Pop Punčov Sbornik - lemma analyzer</title>

</head>
<body>
<div class="l-body">
	
	<div class="mod mod-error">
    <div class="error-header-wrapper">
        <h1 class="mod mod-logo">
			<a href="http://www.uzh.ch" id="home" class="svg">
				<object data="uzh_logo_d_pos.svg" height="80" type="image/svg+xml" width="232.128">
					<img alt="Universität Zürich" height="80" src="uzh_logo_d_pos_web_main.jpg" />
				</object>
				<span>Universität Zürich</span>
			</a>
        </h1>

        <div class="mod mod-navigation skin-navigation-breadcrumb">
			<?php
			
			if ($_GET["corpus"] == "petka") {
				$doc = "<i>Life of St.Petka of Tarnovo</i>";
			} elseif ($_GET["corpus"] == "sva") {
				$doc = "<i>Tale of Alexander the Elder</i>";
			} else {
				$doc = "Pop Punčov Sbornik";
			}
		
            echo "<nav class='l1' role='navigation'>
                <ul id='firstlevel'>
                    <li>" . $doc . "</li>
                </ul>
            </nav>";
			
			?>
        </div>
    </div>
	
    <div class="error-content" id="output">
		<?php
		
		if ($_GET["cyr"] == 1) {
			$script = 1;
		} elseif ($_GET["cyr"] == 2) {
			$script = 2;
		} else {
			$script = 0;
		}
		
		$chapter_id = $_GET["chapter"];
		

		// Liste der Kapitel mit Links
		if ($_GET["corpus"] == "petka") {
			$src_url="src_p.txt";
			$title = 17;
			$corpus_id = "corpus=petka&";
			$index = "editions.html";
		} elseif ($_GET["corpus"] == "sva") {
			$src_url="src_sva.txt";
			$title = 17;
			$corpus_id = "corpus=sva&";
			$index = "editions.html";
		} elseif ($_GET["corpus"] == "lt") {
			$src_url="src_lt.txt";
			$title = 17;
			$corpus_id = "corpus=lt&";
			$index = "editions.html";
		} else {
			$src_url="src.txt";
			$title = 17;
			$index = "index.html";
		}

		// wählt das Kapitel nach Input von der Index-Seite
		if ($spreadsheet_url == "") {
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
		}
						
		// Petka rettet uns, falls etwas schief geht
		if ($spreadsheet_url == "") $spreadsheet_url="http://punco.uzh.ch/txt/020.tsv";

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


		// wichtige Daten werden nochmals hier für JavaScript gesammelt
	//	echo "<span id='data' class='" . $chapter_id . " " . $script . " " . $corpus_id . " " . $var . "' />";


		if(!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";

		$row = 0;
		$noted = true;
		
		echo "<table>";

		if (($handle = fopen($spreadsheet_url, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {
				$row++;
				
				// 2 Rows wegen dem Header ignorieren
				if ($row > 2) {
					
					// eol 0: markiert Anfang des Dokuments
					if ($data[14] == "0") {
						
						// Titel des Kapitels am Anfang
						echo "<p style='width:50%;'>" . $data[$title] . "</p>" . nl2br("\n");
						
						// Link zum Index
						echo "<a href=" . $index . ">index</a>" . nl2br("\n");
						echo nl2br("\n");

					}

					if (empty($data[0]) == FALSE and $data[9] !== "mrg") {
						
						echo "<tr><td>" . $data[3] . "</td><td/><td>";
						foreach($lemmas as $lemma)
							if ($data[3] == $lemma[0]) echo "'" . $lemma[1] . "'";
						echo "</td></tr>";

					}
				}
			}
			fclose($handle);
			echo "</table>";
		}
		else
			die("Problem reading tsv");
		
		?>
    </div>
</div>

<div class="l-footer" id="footer"><div class="mod mod-footer">
	<h2>Fusszeile</h2>

	<p class="copyright language-switcher">
		<a href="/cmsssl/en.html" class="first show-mobile">English</a>		
	</p>

	<p class="copyright">
		<a class="first show-mobile" href="/cmsssl/de.html">Home</a>
		<a class="show-mobile" href="/cmsssl/de/contact.html">Kontakt</a>
		<a class="show-mobile" href="/cmsssl/de/sitemap.html">Sitemap</a>

		<span class="first">&copy;&nbsp;University of Zürich</span>
		<span></span>                                  
                <a href="https://www.uzh.ch/de/privacy">Data Protection Statement</a>
		<span> <b>Website under construction</b> </span>

	</p>
</div>
</div>


</body>
</html>