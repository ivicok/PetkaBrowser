<html>

	<meta charset="utf-8"/>
	<meta name="language" content="en" />
	
	<title>Digital Editions - LMU München</title>
	
	<link rel="preload" href="https://cms-cdn.lmu.de/assets/fonts/roboto-v18-latin-300.woff2" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="https://cms-cdn.lmu.de/assets/fonts/roboto-v18-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="https://cms-cdn.lmu.de/assets/fonts/roboto-v18-latin-italic.woff2" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="https://cms-cdn.lmu.de/assets/fonts/roboto-v18-latin-700.woff2" as="font" type="font/woff2" crossorigin>
	<link rel="stylesheet" href="https://cms-cdn.lmu.de/assets/css/app.bundle.css"/>
	<link rel="shortcut icon" href="https://cms-cdn.lmu.de/assets/img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="https://cms-cdn.lmu.de/assets/img/favicon.ico" type="image/x-icon">	
	
	<link rel="stylesheet" href="content.css"/>
	
	<style>
		td {
			vertical-align: top;
			padding: 5px;
		}
	</style>
	
	<script src="https://cms-cdn.lmu.de/assets/scripts/jquery-3.5.1.min.js"></script>
	<script src="https://cms-cdn.lmu.de/assets/vuejs/v3_3_4_vue_min.js"></script>
	<script src="https://cms-cdn.lmu.de/assets/vuejs/v4_0_2_vuex.min.js"></script>
	<script src="https://cms-cdn.lmu.de/assets/vuejs/v4_2_6_es6-promise_auto.min.js"></script>
	<script src="https://cms-cdn.lmu.de/assets/vuejs/v0_18_0_axios.min.js"></script>
	
	<script>
		  var _paq = window._paq = window._paq || [];
		  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
		  _paq.push(['trackPageView']);
		  _paq.push(['enableLinkTracking']);
		  (function() {
		    var u="https://web-analytics.lmu.de/";
		    _paq.push(["disableCookies"]);
		    _paq.push(['setTrackerUrl', u+'matomo.php']);
		    _paq.push(['setSiteId', '98']);
		    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
		    g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
		  })();
	</script>

	<body>
	
		<header id="header" class="c-header" data-js-module="header" data-js-item="header">
			<div class="grid__container">
				<div class="grid__row">
					<div class="header__wrapper">
						<a class="header__logo-wrapper" href="https://www.lmu.de/de/"><img class="header__logo" src="https://cms-cdn.lmu.de/assets/img/Logo_LMU.svg" alt="Logo Ludwig Maximilians Universität München"/></a>
					</div>
				</div>
			</div>
		</header>
		
		<main id="r-main" data-js-item="main">
			<nav class="c-breadscroller" data-js-module="scroll-indicator" data-js-options='{&quot;scrollToEnd&quot;:true}'>
				<div class="grid__container" data-nosnippet="true">
					<div class="grid__row">
						<div class="breadscroller__content" data-js-item="scroll-indicator" aria-labelledby="breadscroller__headline-1">
							<div class="breadscroller__list-wrapper" data-js-item="scroll-wrapper">
								<ul class="breadscroller__list" data-js-item="scroll-content">
									<li class="breadscroller__list-item">
										<a href="https://www.slavistik.uni-muenchen.de/index.html" title="Startseite" class="breadscroller__item-link">Home</a>
									</li>
									<li class="breadscroller__list-item">
										<a href="https://www.punco.slavistik.lmu.de/index.html" title="Index" class="breadscroller__item-link">Digital Editions</a>
									</li>
									<li class="breadscroller__list-item">
										<strong class="breadscroller__item-active">Lemma Dictionary</strong>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</nav>
		</main>
	
		<div class="c-error-page--404" data-css="c-error-page">
			<div class="grid__container">
				<div class="grid__row" >
					<div class="content">
		<?php
		
		error_reporting(E_ERROR | E_PARSE );
		set_time_limit(6000);
		$start = microtime(true);
		
		if ($_GET["cyr"] == 1) {
			$script = 1;
		} else {
			$script = 0;
		}
		
		$chapter_id = $_GET["chapter"];
		

		// Liste der Kapitel mit Links
		if ($_GET["corpus"] == "petka") {
			$src_url="src_p.txt";
			$title = 17;		// Spalte mit Titel oder Beschreibung des Kapitels
			$corpus_id = "corpus=petka&";
			$index = "petka.html";
		} elseif ($_GET["corpus"] == "lt") {
			$src_url="src_lt.txt";
			$title = 17;		// Spalte mit Titel oder Beschreibung des Kapitels
			$corpus_id = "corpus=lt&";
			$index = "editions.html";
		} elseif ($_GET["corpus"] == "sva") {
			$src_url="src_sva.txt";
			$title = 15;
			$corpus_id = "corpus=sva&";
			$index = "editions.html";
		} else {
			$src_url="src.txt";
			$title = 15;
			$index = "index.html";
		}

		// wählt das Kapitel nach Input von der Index-Seite
		if ($spreadsheet_url == "") {
			$handle_a = fopen($src_url, "r");
			$file = file($src_url);
			$rows = count($file);
			for ($row = 0; $row <= $rows; $row++) {
				$dataa = fgetcsv($handle_a, 0, "\t");
				$spreadsheet_data[] = $dataa;
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

		// macht sich eine Liste der Lemmas mit Übersetzungen
		$lemmas_url= "lemmas.txt";
		$handle_b = fopen($lemmas_url, "r");
		$file = file($lemmas_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
				$lemma_data = fgetcsv($handle_b, 0, "\t");
				$spreadsheet_data[] = $lemma_data;
				$lemmas[$row][0] = $lemma_data[0];	// Latein-Lemma
				$lemmas[$row][1] = $lemma_data[1];	// Kirillische Lemma
				$lemmas[$row][2] = $lemma_data[2];	// die Übersetzung
				$lemmas[$row][3] = $lemma_data[3];	// Wortkategorie
				$lemmas[$row][4] = $lemma_data[4];	// Semantik
				$lemmas[$row][5] = $lemma_data[5];	// morphologische Klasse
				$lemmas[$row][6] = $lemma_data[10];	// Suffixe
				$lemmas[$row][7] = $lemma_data[14];	// Präfixe
				$lemmas[$row][8] = $lemma_data[6];	// Notes
				
				$lemmas[$row]["SJS"] = $lemma_data[11];		// SJS
				$lemmas[$row]["SNSP"] = $lemma_data[12];	// SNSP
				$lemmas[$row]["Mikl"] = $lemma_data[13];	// Miklosich
				$lemmas[$row]["LOVe"] = $lemma_data[15];	// LOVe
			}
		fclose($handle_b);

		$total = $rows - 1;
		print "total number of lemmas: " . $total . nl2br("\n\n");

		function azBukuj($a, $b) {
			return strcmp(mb_strtolower($a[1]), mb_strtolower($b[1]));
		}
		
		usort($lemmas, "azBukuj");
		print "<table>";
		
		for ($i = 3; $i <= $rows; $i++) {
			
			if (empty($lemmas[$i][0]) == false && $lemmas[$i][0] !== "lemma") {
				print "<tr><td><b>" . $lemmas[$i][1] . "</b></td><td>" . $lemmas[$i][0] . "</td><td>" .  $lemmas[$i][2] . "</td><td>" . $lemmas[$i][3];
				
				if (empty($lemmas[$i][4]) == false)
					print " ● " . $lemmas[$i][4];
				
				if (empty($lemmas[$i][5]) == false)
					print " ● " . $lemmas[$i][5];
				
				if (empty($lemmas[$i][6]) == false)
					print " ● " . $lemmas[$i][6];
				if (empty($lemmas[$i][7]) == false)
					print " ● " . $lemmas[$i][7];
				
				print "</td><td>";
				
				if (empty($lemmas[$i][8]) == false)
					print $lemmas[$i][8] . nl2br("\n");
				
				print "<a target=_blank href=search_lemma.php?lemma=" . $lemmas[$i][0] . ">search</a>";
				
				if ($lemmas[$i]["SJS"] !== '')
					print " <a target=_blank href='http://gorazd.org/gulliver/?envLang=en&recordId=" . $lemmas[$i]["SJS"] . "'>SJS</a> ";
				if ($lemmas[$i]["SNSP"] !== '')
					print " <a target=_blank href='http://gorazd.org/gulliver/?envLang=en&recordId=" . $lemmas[$i]["SNSP"] . "'>SNSP</a> ";
				if ($lemmas[$i]["Mikl"] !== '')
					print " <a target=_blank href='http://monumentaserbica.branatomic.com/mikl2/main.php?id=" . $lemmas[$i]["Mikl"] . "'>Miklosich</a> ";
				if ($lemmas[$i]["LOVe"] !== '')
					print " <a target=_blank href='love.php?root=" . $lemmas[$i]["LOVe"] . "'>LOVe</a> ";
				
				print "</td></tr>";
			}
			
		}		
		
		print "</table>";
		
		echo nl2br("\n\n\n");
		
		?>
	</div>
				</div>
			</div>
		</div>


		<footer id="footer" class="c-footer" data-js-item="footer">
			<div class="grid__container">
				<div class="grid__row">
					<div class="footer__wrapper">
						<div class="footer__content-wrapper">
							<nav class="footer__site-navigation">
								<h6 class="footer__site-navigation-headline">Weiterführende Links</h6>
								<ul class="footer__site-navigation-list">
									<li class="footer__site-navigation-list-item">
										<a href="https://www.lmu.de/de/footer/datenschutz/" class="footer__site-navigation-list-link"  >Privacy policy</a>
									</li>
									<li class="footer__site-navigation-list-item">
										<a href="https://www.lmu.de/de/footer/impressum/" class="footer__site-navigation-list-link"  >Imprint</a>
									</li>
									<li class="footer__site-navigation-list-item">
										<a href="https://www.lmu.de/de/footer/barrierefreiheit/" class="footer__site-navigation-list-link"  >Accessibility</a>
									</li>
									<li class="footer__site-navigation-list-item">
										<a href="https://www.lmu.de/de/die-lmu/struktur/zentrale-universitaetsverwaltung/kommunikation-und-presse/press-room/" class="footer__site-navigation-list-link"  >Press Room</a>
									</li>
									<li class="footer__site-navigation-list-item">
										<a href="https://www.lmu.de/de/die-lmu/die-lmu-auf-einen-blick/lmu-shop/" class="footer__site-navigation-list-link"  >LMU Shop</a>
									</li>
									<li class="footer__site-navigation-list-item is-copyright">
										<span class="footer__site-navigation-list-copy">LMU München © 2024</span>
									</li>
								</ul>
							</nav>
						</div>
					</div>
					
					<?php
						echo "<br />PHP " . phpversion() . " using " . memory_get_usage() . " B  | load duration: " . round(microtime(true) - $start, 2) . " s ";
					?>
					
				</div>
			</div>
		</footer>

	</body>
</html>