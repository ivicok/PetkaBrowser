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
										<a href="https://www.punco.slavistik.lmu.de/index.html#pss" title="Index" class="breadscroller__item-link">Psalterium Sinaiticum</a>
									</li>
									<li class="breadscroller__list-item">
										<strong class="breadscroller__item-active">LOVe</strong>
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
		
		if (isset($_GET["root"]))
			$root = $_GET["root"];
		else
			$root = null;
		
		$total = 0;

		// macht sich eine Liste der Lemmas mit Übersetzungen
		$lemmas_url= "love.txt";
		$handle_b = fopen($lemmas_url, "r");
		$file = file($lemmas_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
				$lemma_data = fgetcsv($handle_b, 0, "\t");
				$spreadsheet_data[] = $lemma_data;
				if ($lemma_data[0] != "") {
					$lemmas[$row][0] = $lemma_data[0];		// Leskien-Klasse
					$lemmas[$row][1] = $lemma_data[1];		// aor/prs-Stämme
					$lemmas[$row][2] = $lemma_data[2];		// Wurzel
					$lemmas[$row][3] = $lemma_data[3];		// Infinitiv
					$lemmas[$row][4] = $lemma_data[4];		// 3sg.prs
					$lemmas[$row][5] = $lemma_data[5];		// 2/3sg.aor
					$lemmas[$row][6] = $lemma_data[6];		// 2/3sg.imp
					$lemmas[$row][7] = $lemma_data[7];		// SJS-Id
					$lemmas[$row][8] = $lemma_data[8];		// Aspekt nach SJS/SNSP
					$lemmas[$row][9] = $lemma_data[9];		// Präfixe nach Miklosich
					$lemmas[$row][10] = $lemma_data[10];	// Aspekt der präfig. Formen
					$lemmas[$row][11] = $lemma_data[11];	// impliziert Bewegung?
					$lemmas[$row][12] = $lemma_data[12];	// Notes
					$lemmas[$row][13] = $lemma_data[13];	// Transitivität
					$lemmas[$row][14] = $lemma_data[14];	// Form des Objekts
					$total = $total + 1;
				}					
			}
		fclose($handle_b);
		
		print "<b>Lexicon of Old Church Slavonic Verbs</b>" . nl2br("\n\n");
		
		if ($root == null) {
			print "The database provides a list of verbal stems based on the <i>Psalterium Sinaiticum</i> and the <i>Lexikon der Indogermanischen Verben</i> (LIV), supplementing the lemma dictionary with information specific for OCS morphology, like aorist and present stems, as well as the aspect (according to SJS). Clicking on a root lists the cognates and derivates of the given verb, including a list of prefixes used with the root (according to Miklosich) and other data." . nl2br("\n\n") . "<a href=love.php?root=classes>stem classes</a> <a href='love.txt'>source</a>" . nl2br("\n\n");
		}
		
		if ($root == null) {
			print "total number of lemmas: " . $total . nl2br("\n\n\n");
			
			function azBukuj($a, $b) {
				return strcmp(mb_strtolower($a[3]), mb_strtolower($b[3]));		// 2 für Wurzel, 3 für Lemmas
			}
			
			usort($lemmas, "azBukuj");
			print "<table>";
			
			for ($i = 0; $i <= $rows; $i++) {
				
				if (empty($lemmas[$i][0]) == false && $lemmas[$i][0] !== "lemma") {
					print "<tr><td><b>" . $lemmas[$i][3] . "</b></td><td><a href='love.php?root=". $lemmas[$i][2] . "'>" . $lemmas[$i][2] . "</td><td>" .  $lemmas[$i][1] . "</td><td>" .  $lemmas[$i][8];
					
					if (empty($lemmas[$i][7]) == false)
						print "</td><td> <a target=_blank href='http://gorazd.org/gulliver/?envLang=en&recordId=" . $lemmas[$i][7] . "'>SJS</a> ";
					print "</td></tr>";
				}
				
			}		
			
			print "</table>";			
		}
		else if ($root == "classes") {
			
			print "This overview shows lemmas in the database classified according to the variation between aorist and present stems. See the <a href=ref-pss.html#verbs>reference grammar</a> for more details. The order reflects that of Leskienʹs classification (I: e-presents, II: ne-, III: je-, IV: i-, V: athematic)." . nl2br("\n\n") . "<a href=love.php>full lexicon</a>" . nl2br("\n");
			
			$selection = null;
			$classes = [];
			
			for ($i = 0; $i <= $rows; $i++) {
				
				if ($classes == [] and $lemmas[$i][1] != "")
					array_push($classes, $lemmas[$i][1]);
				else {
					$used = false;
					foreach ($classes as $c)
						if ($lemmas[$i][1] == $c)
							$used = true;
						
					if ($used == false and $lemmas[$i][1] != "")
						array_push($classes, $lemmas[$i][1]);
					else
						$used = false;
				}
			
			}
			
			foreach ($classes as $c) {
				print "<br/><br/><b>" . $c . "</b> : ";
				$p = false;
				for ($ii = 0; $ii <= $rows; $ii++) 
					if ($lemmas[$ii][1] == $c) {
						if ($p == true)
							print ", ";
						print "<i>" . $lemmas[$ii][3] . "</i> (<a href='love.php?root=". $lemmas[$ii][2] . "'>" . $lemmas[$ii][2] . "</a>)";
						$p = true;
					}
			}
			
		}
		else {
			
			if ($root == "es-" or $root == "bes-")
				$root = "by-";
			
			print "Verbs based on root *<i>" . $root . "</i>" . nl2br("\n\n");			
			
			foreach ($lemmas as $i) {
				
				if ($root == "by-" and ($i[2] == "es-" or $i[2] == "bes-"))
					$i[2] = "by-";
				
				if ($i[2] == $root) {
					print "<b>" . $i[3] . "</b>" . nl2br("\n");
					print "3sg.prs <i>" . $i[4] . "</i> 2/3sg.aor <i>" . $i[5] . "</i>" . nl2br("\n");
					print "Leskien class " . $i[0] . nl2br("\n");
					print $i[1] . " stems" . nl2br("\n");
					print $i[8] . " aspect" . nl2br("\n");
					
					if (empty($i[13]) == false)
						print $i[13] . nl2br("\n");
					if (empty($i[14]) == false)
						print "arguments: " . $i[14] . nl2br("\n");
					if (empty($i[11]) == false and $i[11] == "yes")
						print "motion verb" . nl2br("\n");
					if (empty($i[9]) == false)
						print "prefix variation: <i>" . $i[9] . "</i> (" . $i[10] . ")" . nl2br("\n");
					if (empty($i[12]) == false)
						print "<p style='font-size:80%'>" . $i[12] . "</p>";
					if (empty($i[7]) == false)
						print "<a target=_blank href='http://gorazd.org/gulliver/?envLang=en&recordId=" . $i[7] . "'>SJS</a> " . nl2br("\n");
					
					print nl2br("\n");
				}
			}
			
			print nl2br("\n") . "Examples in the corpus: ";
			
			$handle_c = fopen("lemmas.txt", "r");
			$file = file("lemmas.txt");
			$rows = count($file);
			for ($row = 0; $row < $rows; $row++) {
				$data = fgetcsv($handle_c, 0, "\t");
				if (isset($data[15]) and $data[15] == $root)
					print "<a target=_blank href=search_lemma.php?lemma=" . $data[0] . ">" . $data[0] . "</a> ";
			}	
			
			print nl2br("\n\n") . "<a href=love.php>full lexicon</a>";
		}
		
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