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
										<strong class="breadscroller__item-active">Glagolitic Keyboard</strong>
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
					
	<textarea id="input" onKeyUp="gla()" rows=5></textarea>
	<br/>
	<button type="button" id="clipboard" onclick="copy()">Copy to Clipboard</button>

	<pre>	
   <span class="letter oksia"> ´ </span><span class="letter varia"> ` </span><span class="letter kamora"> ^ </span><span class="letter psili"> ʾ </span><span class="letter dasia"> ʿ </span><span class="letter ͛">  ͛ </span><span class="letter ҃">  ҃ </span>	<span class="letter bksp"> [bksp] </span>
   <span class="letter ⱉ"> ⱉ </span><span class="letter ⱕ"> ⱕ </span><span class="letter ⱗ"> ⱗ</span><span class="letter ⰷ"> ⰷ </span><span class="letter ⱚ"> ⱚ </span><span class="letter ⱒ"> ⱒ </span><span class="letter ⱝ"> ⱝ </span><span class="letter ⱜ"> ⱜ </span>
<span class="letter ⱍ"> ⱍ </span><span class="letter ⰲ"> ⰲ </span><span class="letter ⰵ"> ⰵ </span><span class="letter ⱔ"> ⱔ </span><span class="letter ⱃ"> ⱃ </span><span class="letter ⱅ"> ⱅ </span><span class="letter ⱏ"> ⱏ </span><span class="letter ⰻ"> ⰻ </span><span class="letter ⰺ"> ⰺ </span><span class="letter ⱛ"> ⱛ </span><span class="letter ⱐ"> ⱐ </span><span class="letter ⱆ"> ⱆ </span><span class="letter ⱁ"> ⱁ </span><span class="letter ⱘ"> ⱘ </span><span class="letter ⱂ"> ⱂ </span>
<span class="letter ⰰ"> ⰰ </span><span class="letter ⱑ"> ⱑ </span><span class="letter ⱄ"> ⱄ </span><span class="letter ⰴ"> ⰴ </span><span class="letter ⱇ"> ⱇ </span><span class="letter ⰳ"> ⰳ </span><span class="letter ⱈ"> ⱈ </span><span class="letter ⰹ"> ⰹ </span><span class="letter ⰽ"> ⰽ </span><span class="letter ⰾ"> ⰾ </span><span class="letter ⱓ"> ⱓ </span><span class="letter ⱎ"> ⱎ </span><span class="letter ⱋ"> ⱋ </span><span class="letter ⱙ"> ⱙ </span><span class="letter ⱖ"> ⱖ </span>
<span class="letter ⰸ"> ⰸ </span><span class="letter ⱌ"> ⱌ </span><span class="letter ⰼ"> ⰼ </span><span class="letter ⰶ"> ⰶ </span><span class="letter ⰱ"> ⰱ </span><span class="letter ⱀ"> ⱀ </span><span class="letter ⱞ"> ⱞ </span><span class="letter ⰿ"> ⰿ </span><span class="letter ·"> · </span>
	<span class="letter space">  [space]  </span>
	
	  [caps]  <input type='checkbox' onclick='caps=!caps'>
	
		 
	ASCII to Gla:
	' ► ⱐ		q ► ⱍ
	" ► ⱏ		s= ► ⱎ
	a= ► ⱑ		s== ► ⱋ
	a== ► ⱝ		z= ► ⰶ
	o= ► ⱘ		z== ► ⰷ
	e= ► ⱔ		g= ► ⰼ
	e== ► ⱗ	x ► ⱈ
	i ► ⰹ		x= ► ⱒ
	i= ► ⰺ		t= ► ⱚ
	j ► ⰻ		m ► ⱞ
	ju ► ⱓ		m= ► ⰿ
	ju= ► ⱙ	y ► ⱛ		
	ju== ► ⱖ	y= ► ⱜ
	w ► ⱉ		y== ► ⱕ
			~ ► titlo ҃
	
	</pre>
	
	<textarea id="free" rows=5></textarea>
	<br/>
	<button type="button" id="convertor" onclick="convert()">Copy to Glagolizator</button>
	


<script>

	var letters = document.getElementsByClassName("letter");
	var caps = false;
	
	var keys = function() {
		var keyId = this.classList.item(1);
		if (keyId == "space") {
			keyId = " ";
		} else if (keyId == "oksia") {
			keyId = "\u0301";
		} else if (keyId == "varia") {
			keyId = "\u0300";
		} else if (keyId == "kamora") {
			keyId = "\u0302";
		} else if (keyId == "psili") {
			keyId = "\u0486";
		} else if (keyId == "dasia") {
			keyId = "\u0485";
		};
		if (caps == true) {
			keyId = keyId.toUpperCase();
		};
		if (keyId == "bksp") {
			document.getElementById("input").value = document.getElementById("input").value.slice(0,-1);
		} else {
			document.getElementById("input").value = document.getElementById("input").value + keyId;
		};
	};

	var readKey = function() {
		var keyId = this.classList.item(1);
		for (var i=0; i < letters.length; i++) {
			if (letters[i].classList.item(1) == keyId) {
				letters[i].style.background = "#d8dee4";
			};
		};
	};
	
	var purgeKey = function() {
		var keyId = this.classList.item(1);
		for (var i=0; i < letters.length; i++) {
			if (letters[i].classList.item(1) == keyId) {
				letters[i].style.background = "white";
			};
		};
	};
	
	for (var i=0; i < letters.length; i++) {
		letters[i].addEventListener('click', keys, false);
		letters[i].addEventListener('mouseover', readKey, false);
		letters[i].addEventListener('mouseout', purgeKey, false);
	};

	var copy = function() {
		var text = document.getElementById("input");
		text.select();
		text.setSelectionRange(0, 99999);
		navigator.clipboard.writeText(text.value);
		
		document.getElementById("free").value = text.value;
		document.getElementById("input").value = "";
	};

	var convert = function() {
		var text = document.getElementById("free");
		var data = document.getElementById("input");
		
		document.getElementById("input").value = data.value + text.value;
		document.getElementById("free").value = "";
	};
	
	var gla = function() {
		
		var data = document.getElementById("input").value;
		
		data = data.replace(/ⱍ=/g, "ⰼ");
		data = data.replace(/ⰳ=/g, "ⰼ");
		data = data.replace(/ⰴ=/g, "ⰼ");
		data = data.replace(/č/g, "ⱍ");
		data = data.replace(/q/g, "ⱍ");
		data = data.replace(/ⱌ=/g, "ⱍ");
		data = data.replace(/ü/g, "ⱛ");
		data = data.replace(/ⱛ=/g, "ⱜ");
		data = data.replace(/w/g, "ⱉ");
		data = data.replace(/ź/g, "ⰷ");
		data = data.replace(/ⰶ=/g, "ⰷ");
		data = data.replace(/ju/g, "ⱓ");
		data = data.replace(/jo/g, "ⱙ");
		data = data.replace(/ⱓ=/g, "ⱙ");
		data = data.replace(/ⱙ=/g, "ⱖ");
		data = data.replace(/v/g, "ⰲ");
		data = data.replace(/e/g, "ⰵ");
		data = data.replace(/ⰵ=/g, "ⱔ");
		data = data.replace(/ę/g, "ⱔ");
		data = data.replace(/ⱔ=/g, "ⱗ");
		data = data.replace(/r/g, "ⱃ");
		data = data.replace(/št/g, "ⱋ");
		data = data.replace(/ś/g, "ⱋ");
		data = data.replace(/ⱎ=/g, "ⱋ");
		data = data.replace(/t/g, "ⱅ");
		data = data.replace(/ⱅ=/g, "ⱚ");
		data = data.replace(/y/g, "ⱛ");
		data = data.replace(/ⱜ=/g, "ⱕ");
		data = data.replace(/"/g, "ⱏ");
		data = data.replace(/'/g, "ⱐ");
		data = data.replace(/u/g, "ⱆ");
		data = data.replace(/ⱆ=/g, "ⱘ");
		data = data.replace(/j/g, "ⰹ");
		data = data.replace(/ï/g, "ⰺ");
		data = data.replace(/í/g, "ⰺ");
		data = data.replace(/ⰹ=/g, "ⰺ");
		data = data.replace(/ⰻ=/g, "ⰹ");
		data = data.replace(/i/g, "ⰻ");
		data = data.replace(/o/g, "ⱁ");
		data = data.replace(/ǫ/g, "ⱘ");
		data = data.replace(/ⱁ=/g, "ⱘ");
		data = data.replace(/p/g, "ⱂ");
		data = data.replace(/a/g, "ⰰ");
		data = data.replace(/ā/g, "ⱑ");
		data = data.replace(/ⰰ=/g, "ⱑ");
		data = data.replace(/ⱑ=/g, "ⱝ");
		data = data.replace(/ě/g, "ⱑ");
		data = data.replace(/s/g, "ⱄ");
		data = data.replace(/d/g, "ⰴ");
		data = data.replace(/f/g, "ⱇ");
		data = data.replace(/g/g, "ⰳ");
		data = data.replace(/h/g, "ⱈ");
		data = data.replace(/x/g, "ⱈ");
		data = data.replace(/ⱈ=/g, "ⱒ");
		data = data.replace(/k/g, "ⰽ");
		data = data.replace(/l/g, "ⰾ");
		data = data.replace(/š/g, "ⱎ");
		data = data.replace(/ⱄ=/g, "ⱎ");
		data = data.replace(/z/g, "ⰸ");
		data = data.replace(/ⱂ=/g, "ⱋ");
		data = data.replace(/c/g, "ⱌ");
		data = data.replace(/ž/g, "ⰶ");
		data = data.replace(/ⰸ=/g, "ⰶ");
		data = data.replace(/b/g, "ⰱ");
		data = data.replace(/n/g, "ⱀ");
		data = data.replace(/m/g, "ⱞ");
		data = data.replace(/ⱞ=/g, "ⰿ");
		
		// capitals
		data = data.replace(/Ⱍ=/g, "Ⰼ");
		data = data.replace(/Ⰳ=/g, "Ⰼ");
		data = data.replace(/Ⰴ=/g, "Ⰼ");
		data = data.replace(/Č/g, "Ⱍ");
		data = data.replace(/Q/g, "Ⱍ");
		data = data.replace(/Ⱌ=/g, "Ⱍ");
		data = data.replace(/Ü/g, "Ⱛ");
		data = data.replace(/Ⱛ=/g, "Ⱜ");
		data = data.replace(/W/g, "Ⱉ");
		data = data.replace(/Ź/g, "Ⰷ");
		data = data.replace(/Ⰶ=/g, "Ⰷ");
		data = data.replace(/Ju/g, "Ⱓ");
		data = data.replace(/Jo/g, "Ⱙ");
		data = data.replace(/ⰉⰖ/g, "Ⱓ");
		data = data.replace(/ⰉⰑ/g, "Ⱙ");
		data = data.replace(/Ⱓ=/g, "Ⱙ");
		data = data.replace(/Ⱙ=/g, "Ⱖ");
		data = data.replace(/V/g, "Ⰲ");
		data = data.replace(/E/g, "Ⰵ");
		data = data.replace(/Ⰵ=/g, "Ⱔ");
		data = data.replace(/Ę/g, "Ⱔ");
		data = data.replace(/Ⱔ=/g, "Ⱗ");
		data = data.replace(/R/g, "Ⱃ");
		data = data.replace(/Št/g, "Ⱋ");
		data = data.replace(/Ś/g, "Ⱋ");
		data = data.replace(/Ⱎ=/g, "Ⱋ");
		data = data.replace(/T/g, "Ⱅ");
		data = data.replace(/Ⱅ=/g, "Ⱚ");
		data = data.replace(/Y/g, "Ⱛ");
		data = data.replace(/Ⱜ=/g, "Ⱕ");
		data = data.replace(/ⱏ=/g, "Ⱏ");
		data = data.replace(/ⱐ=/g, "Ⱐ");
		data = data.replace(/U/g, "Ⱆ");
		data = data.replace(/Ⱆ=/g, "Ⱘ");
		data = data.replace(/J/g, "Ⰹ");
		data = data.replace(/Í/g, "Ⰺ");
		data = data.replace(/Ⰹ=/g, "Ⰺ");
		data = data.replace(/Ⰻ=/g, "Ⰹ");
		data = data.replace(/I/g, "Ⰻ");
		data = data.replace(/O/g, "Ⱁ");
		data = data.replace(/Ⱁ=/g, "Ⱘ");
		data = data.replace(/P/g, "Ⱂ");
		data = data.replace(/A/g, "Ⰰ");
		data = data.replace(/Ⰰ=/g, "Ⱑ");
		data = data.replace(/Ⱑ=/g, "Ⱝ");
		data = data.replace(/Ě/g, "Ⱑ");
		data = data.replace(/S/g, "Ⱄ");
		data = data.replace(/D/g, "Ⰴ");
		data = data.replace(/F/g, "Ⱇ");
		data = data.replace(/G/g, "Ⰳ");
		data = data.replace(/H/g, "Ⱈ");
		data = data.replace(/X/g, "Ⱈ");
		data = data.replace(/Ⱈ=/g, "Ⱒ");
		data = data.replace(/K/g, "Ⰽ");
		data = data.replace(/L/g, "Ⰾ");
		data = data.replace(/Š/g, "Ⱎ");
		data = data.replace(/Ⱄ=/g, "Ⱎ");
		data = data.replace(/Z/g, "Ⰸ");
		data = data.replace(/Ⱂ=/g, "Ⱋ");
		data = data.replace(/C/g, "Ⱌ");
		data = data.replace(/Ž/g, "Ⰶ");
		data = data.replace(/Ⰸ=/g, "Ⰶ");
		data = data.replace(/B/g, "Ⰱ");
		data = data.replace(/N/g, "Ⱀ");
		data = data.replace(/M/g, "Ⱞ");
		data = data.replace(/Ⱞ=/g, "Ⰿ");

		data = data.replace(/´/g, "̂");
		data = data.replace(/`/g, "̂");
		data = data.replace(/~/g, "҃");
		
		document.getElementById("input").value = data;
	
	};
	
	
</script>

						
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
					
					
				</div>
			</div>
		</footer>

	</body>
</html>