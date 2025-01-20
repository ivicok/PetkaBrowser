
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

	<title>Pop Punčov Sbornik - tag analyzer</title>

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

    </div>
	
    <div class="error-content" id="output">
		<?php
		error_reporting(E_ERROR | E_PARSE );
		
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
			$src_url="https://docs.google.com/spreadsheets/d/e/2PACX-1vQdePlLp_u9-fWpOYiqDuoc6X6AXmzHxOSeshfOEI6TIA5MnW22Qm5lkw97BDYHNg/pub?gid=1957405706&single=true&output=tsv";
			$title = 17;		// Spalte mit Titel oder Beschreibung des Kapitels
			$corpus_id = "corpus=petka&";
			$index = "editions.html";
		} elseif ($_GET["corpus"] == "sva") {
			$src_url="https://docs.google.com/spreadsheets/d/e/2PACX-1vTvPkkwPpR-kGpq7e95-9QD3DmuMgBAeINynmocIODrDf2xn8fB3a6J_LS-dVvH1A/pub?gid=1957405706&single=true&output=tsv";
			$title = 17;		// Spalte mit Titel oder Beschreibung des Kapitels
			$corpus_id = "corpus=sva&";
			$index = "editions.html";
		} elseif ($_GET["corpus"] == "lt") {
			$src_url="src_lt.txt";
			$title = 17;		// Spalte mit Titel oder Beschreibung des Kapitels
			$corpus_id = "corpus=lt&";
			$index = "editions.html";
		} else {
			$src_url="https://docs.google.com/spreadsheets/d/e/2PACX-1vRiySna4E-2HS3g_V5j0xRTNYUmAGMJmbDuwTi2TTY01lhCa-q8Sm8u9-Nk__Hffw/pub?gid=1957405706&single=true&output=tsv";
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
		if ($spreadsheet_url == "") $spreadsheet_url="https://docs.google.com/spreadsheets/d/e/2PACX-1vQ5Qg7emZWKxzH3RQd-9dFOQ3gxMsNpO3vdvXZS7FGKHPmLTkvzMfUljLmptNBhUA/pub?gid=1707005014&single=true&output=tsv";

		// Liste der PoS-Tags
		$msd_url= "msd.txt";
		
		// "https://docs.google.com/spreadsheets/d/e/2PACX-1vR0V3BzD1besJfEJt92QhVpUGWldl_zaw_U0NLBgHIW04hPxji1P4IEzxAEEraIIw/pub?gid=1429904811&single=true&output=tsv";
		$handle_c = fopen($msd_url, "r");
		$file = file($msd_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
				$msd_data = fgetcsv($handle_c, 0, "\t");
				$msds[$row][0] = $msd_data[0];	// PoS Tag selbst
				$msds[$row][1] = $msd_data[2];	// die Leipzig-Notation
			}
		fclose($handle_c);


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
						$known = false;
						foreach($msds as $msd)
							if ($data[4] == $msd[0])
								$known = true;
						if ($known == false)
							echo $data[4] . nl2br("\n");
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