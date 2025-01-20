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
										<strong class="breadscroller__item-active">Search Engine</strong>
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
							include('search2.php');
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