
<!DOCTYPE HTML>


<html>

<head>
	<meta charset="utf-8" />

	<style>
	  td {
		vertical-align: top;
	  }
	</style>

</head>

<body>
	<?php
		
		error_reporting(E_ERROR | E_CORE_ERROR | E_PARSE );
		ini_set('memory_limit', '300M');
		
		// Database-Generierung kann schon etwas dauern
		set_time_limit(6000);
		$start = microtime(true);
		
		// Kyrillisch macht die Suche langsamer
	//	if ($_GET["script"] == 1)
	//		$script = 1;
	//	else
	//		$script = 0;
		
		// Liste der Korpora
		if ($_GET["corpus"] == "petka") {
			$srcs_url="src_p.txt";
			$corpus_id = "corpus=petka&";
			$corpus_name = "Life of St. Petka";
			$ext_data = "petka_data.json";
		} elseif ($_GET["corpus"] == "sva") {
			$srcs_url="src_sva.txt";
			$corpus_id = "corpus=sva&";
			$corpus_name = "Tale of Alexander the Elder";
			$ext_data = "sva_data.json";
		} elseif ($_GET["corpus"] == "lt") {
			$srcs_url="src_lt.txt";
			$corpus_id = "corpus=lt&";
			$corpus_name = "Excerpts";
			$ext_data = "lt_data.json";
		} else {
			$srcs_url="src.txt";
			$corpus_name = "Pop Punčov Sbornik";
			$ext_data = "data_1.json";
			$ext_data_2 = "data_2.json";
		}
		
		$handle_a = fopen($srcs_url, "r");
		$file = file($srcs_url);
		$rows = count($file);
		for ($row = 0; $row < $rows; $row++) {
			$data = fgetcsv($handle_a, 0, "\t"); 
			if ($data[0] !== "id")
				$src_url[$row] = $data[1];
		}
		fclose($handle_a);

		// macht sich eine Liste der Lemmas
		$lemmas_url= "lemmas.txt";
		$handle_b = fopen($lemmas_url, "r");
		$file = file($lemmas_url);
		$rows = count($file);
		for ($row = 0; $row <= $rows; $row++) {
			$lemma_data = fgetcsv($handle_b, 0, "\t");
			$spreadsheet_data[] = $lemma_data;
			if ($lemma_data[0] !== "" and $lemma_data[0] !== "lemma") {
				$lemmas[$row][0] = $lemma_data[0];	// Latein-Lemma
		//		$lemmas[$row][1] = $lemma_data[1];	// Kirillische Lemma
		//		$lemmas[$row][2] = $lemma_data[2];	// die Übersetzung
		//		$lemmas[$row][3] = $lemma_data[3];	// Wortkategorie
		//		$lemmas[$row][4] = $lemma_data[4];	// Semantik
		//		$lemmas[$row][5] = $lemma_data[5];	// morphologische Klasse
		//		$lemmas[$row][6] = $lemma_data[10];	// Suffixe
		//		$lemmas[$row][7] = $lemma_data[6];	// Notes
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
				$msds[$row]["tag"] = $msd_data[0];	// PoS Tag selbst
				$msds[$row]["desc"] = $msd_data[2];	// die Leipzig-Notation
			}
		fclose($handle_c);

		$counter = 0;
	
		$decoded = json_decode(file_get_contents($ext_data), true);
		
		if ($ext_data_2)
			$decoded = array_merge($decoded, json_decode(file_get_contents($ext_data_2), true));	

		// holt die Quellen-Namen
		$srcs = count($src_url);
		for ($src = 0; $src <= $srcs; $src++)
			foreach ($decoded[$src] as $tok)
				if ($tok["src_desc"] !== null) 
					$src_desc[$src] = $tok["src_desc"];
				
		// rechnet die Anzahl der Tokens im externen File
		foreach ($decoded as $a)
			foreach ($a as $b)
				$counter = $counter + 1;
		
		// Links zu anderen Subkorpora
	//	echo "<a href=https://www.punco.slavistik.lmu.de/search_engine.php>Pop Punčov Sbornik</a><br/>
	//		<a href=https://www.punco.slavistik.lmu.de/search_engine.php?corpus=petka>Life of St. Petka</a><br/>
	//		<a href=https://www.punco.slavistik.lmu.de/search_engine.php?corpus=sva>Tale of Alexander the Elder</a><br/>
	//		<a href=https://www.punco.slavistik.lmu.de/search_engine.php?corpus=lt>Digital Editions</a>"
	//		. nl2br("\n\n");
		
		// Name des Korpus
		echo "corpus: <span id='c_name'>loading...</span>" . nl2br("\n");
		
		// letzte Änderung
		echo "version date: " . date("d F Y H:i:s", filemtime($ext_data)) . nl2br("\n");
		
		// Anzahl der Tokens
		echo "tokens: " . $counter . nl2br("\n\n");

		// Suche nach einem Token
		echo "<input type='text' id='tokens' /><button onclick='tokenSearch()'> Search Token</button><span class='token'> ■<span class='ext'>● search for a string, included in a token (single word)<br/>● articles and negation particles are handled as separate tokens<br/>● uses diplomatized Latin script<br/>● special (non-ASCII) characters beneath the text-field<br/>● no accents and punctuation<br/></span></span><br/>";
		
		// nicht-ASCII Zeichen für leichtere Suche
		echo "<pre><span class='letter č'>č </span><span class='letter š'>š </span><span class='letter ž'>ž </span><span class='letter ě'>ě </span><span class='letter ę'>ę </span><span class='letter ǫ'>ǫ </span><span class='letter ъ'>ъ </span><span class='letter ь'>ь </span><span class='letter ѳ'>ѳ </span><span class='letter ѯ'>ѯ </span><span class='letter ѱ'>ѱ </span><span class='letter ѵ'>ѵ </span><span class='letter џ'>џ </span></pre><br/><br/>";

		// Suche nach Lemma mit einer Auswahl-Liste
		echo "<input type='search' ";
		if (empty($_GET["lemma"]) == false)
			echo "value='" . ($_GET["lemma"]) . "' ";
		echo "list='lemmas' id='lemma' /><button onclick='lemmaSearch()'> Search Lemma</button><span class='token'> ■<span class='ext'>● search for a matching lemma (dictionary form)<br/>● chosen from a pre-defined dictionary<br/>● suggestions given while writing<br/></span></span><br/><br/>";
		echo "<datalist id='lemmas'>";
		foreach ($lemmas as $lemma)
			echo "<option value='" . $lemma[0] . "'>";
		echo "</datalist><br/><br/>";
		
		echo "<div id='advanced' style='display: none'>";
		
		// Suche nach PoS-Tag - auch mit einer Auswahl- und einer Kategorie-Liste
		echo "<input type='search' list='msds' id='msd' /><button onclick='tagSearch()'> Search Tag</button><span class='token'> ■<span class='ext'>● search for a morphological tag<br/>● based on the MultextEast system<br/>● suggestions given while writing<br/>● drop-down menu for writing word categories<br/>● use the character '?' as wildcard for any sign (e.g. V?ip3se)<br/></span></span><br/>
			<select id='pos' onchange='writeTag()'><option disabled selected value>select word category</option>
				<option value='N'>noun</option>
				<option value='A'>adjective</option>
				<option value='M'>numeral</option>
				<option value='V'>verb</option><option value='R'>adverb</option>
				<option value='S'>preposition</option><option value='C'>conjunction</option>
				<option value='Q'>particle</option>
				<option value='I'>interjection</option>
				<option value='X'>punctuation</option></select><br/><a target=_blank href=https://www.punco.uzh.ch/ref>Reference Grammar</a><br/>";
		echo "<datalist id='msds'>";
		foreach ($msds as $msd)
			echo "<option value=" . $msd["tag"] . ">";
		echo "</datalist><br/>";
		
		// Suche nach syntaktischen Dependenzen
		echo "<select id='ud'><option selected value>select main dependency</option>
						<option value='root'>root - sentence root</option>
						<option value='nsubj'>nsubj - subject</option>
						<option value='obj'>obj - direct object</option>
						<option value='obl'>obl - oblique argument</option>
						<option value='acl'>acl - adnominal clause root</option>
						<option value='advcl'>advcl - adverbial clause root</option>
						<option value='aux'>aux - auxiliary</option>
						<option value='cop'>cop - copula</option>
						<option value='advmod'>advmod - adverbial modifier</option>
						<option value='amod'>amod - adjectival modifier</option>
						<option value='nmod'>nmod - nominal modifier</option>
						<option value='nummod'>nummod - numeric modifier</option>
						<option value='det'>det - determiner</option>
						<option value='mark'>mark - subordinated clause marker</option>
						<option value='case'>case - analytic case marker</option>
						<option value='cc'>cc - coordinating conjunction</option>
						<option value='appos'>appos - appositional modifier</option>
						<option value='conj'>conj - conjunct</option>
						<option value='discourse'>discourse - no lexical meaning</option>
						<option value='expl'>expl - expletive</option>
						<option value='fixed'>fixed - part of a fixed expression</option>
						<option value='vocative'>vocative</option>
						<option value='orphan'>orphaned element</option>
						<option value='punct'>non-verbal element</option></select>
					<button onclick='udSearch()'> Search Dependency</button>
					<span class='token'> ■<span class='ext'>● search for a syntactic tag<br/>● denotes dependency of a token towards the head of phrase or sentence root<br/>● selected from a drop-down menu<br/></span></span><br/>
					<select id='ud_ext'><option selected value=''>no extension</option>
						<option value='nsubj'>nsubj - subject</option>
						<option value='obj'>obj - direct object</option>
						<option value='obl'>obl - oblique argument</option>
						<option value='advmod'>advmod - adverbial modifier</option>
						<option value='amod'>amod - adjectival modifier</option>
						<option value='nmod'>nmod - nominal modifier</option>
						<option value='nummod'>nummod - numeric modifier</option>
						<option value='det'>det - determiner</option>
						<option value='mark'>mark - subordinated clause marker</option>
						<option value='abl'>abl - ablative</option>
						<option value='iobj'>iobj - indirect object</option>
						<option value='lat'>lat - lative</option>
						<option value='loc'>loc - locative</option>
						<option value='poss'>poss - possessor</option>
						<option value='pred'>pred - nominal predicate</option>
						<option value='ext'>ext - extended demonstrative</option>
						<option value='p_adj'>p_adj - post-adjectival article</option>
						<option value='p_nom'>p_nom - post-nominal article</option>
						<option value='con'>con - conditional</option>
						<option value='fut'>fut - future tense</option>
						<option value='inf'>inf - analytic infinitive</option>
						<option value='opt'>opt - optative</option>
						<option value='pass'>pass - passive voice</option>
						<option value='prf'>prf - perfect construction</option>
						<option value='pprf'>pprf - plusquamperfect</option></select><br/>
					<a target=_blank href=https://universaldependencies.org/u/dep/index.html>Universal Dependencies</a><br/><br/>";
		
		// Suche nach Head der Dependenzen
		echo "<select id='head_ud'><option selected value>select parent dependency</option>
						<option value='root'>root - sentence root</option>
						<option value='nsubj'>nsubj - subject</option>
						<option value='obj'>obj - direct object</option>
						<option value='obl'>obl - oblique argument</option>
						<option value='acl'>acl - adnominal clause root</option>
						<option value='advcl'>advcl - adverbial clause root</option>
						<option value='aux'>aux - auxiliary</option>
						<option value='cop'>cop - copula</option>
						<option value='advmod'>advmod - adverbial modifier</option>
						<option value='amod'>amod - adjectival modifier</option>
						<option value='nmod'>nmod - nominal modifier</option>
						<option value='nummod'>nummod - numeric modifier</option>
						<option value='det'>det - determiner</option>
						<option value='mark'>mark - subordinated clause marker</option>
						<option value='case'>case - analytic case marker</option>
						<option value='cc'>cc - coordinating conjunction</option>
						<option value='appos'>appos - appositional modifier</option>
						<option value='conj'>conj - conjunct</option>
						<option value='expl'>expl - expletive</option>
						<option value='fixed'>fixed - part of a fixed expression</option>
						<option value='vocative'>vocative</option></select>
					<span class='token'> ■<span class='ext'>● specify the head of dependency of the token<br/>● for example an auxiliary (main: aux) dependent on adnominal clause roots (parent: acl)<br/>● used with Advanced Search<br/>● selected from a drop-down menu<br/></span></span><br/>
					<select id='head_ext'><option selected value=''>no extension</option>
						<option value='nsubj'>nsubj - subject</option>
						<option value='obj'>obj - direct object</option>
						<option value='obl'>obl - oblique argument</option>
						<option value='advmod'>advmod - adverbial modifier</option>
						<option value='amod'>amod - adjectival modifier</option>
						<option value='nmod'>nmod - nominal modifier</option>
						<option value='nummod'>nummod - numeric modifier</option>
						<option value='det'>det - determiner</option>
						<option value='mark'>mark - subordinated clause marker</option>
						<option value='abl'>abl - ablative</option>
						<option value='iobj'>iobj - indirect object</option>
						<option value='lat'>lat - lative</option>
						<option value='loc'>loc - locative</option>
						<option value='poss'>poss - possessor</option>
						<option value='pred'>pred - nominal predicate</option>
						<option value='ext'>ext - extended demonstrative</option>
						<option value='p_adj'>p_adj - post-adjectival article</option>
						<option value='p_nom'>p_nom - post-nominal article</option>
						<option value='con'>con - conditional</option>
						<option value='fut'>fut - future tense marker</option>
						<option value='inf'>inf - analytic infinitive</option>
						<option value='opt'>opt - optative</option>
						<option value='pass'>pass - passive voice</option>
						<option value='prf'>prf - perfect construction</option>
						<option value='pprf'>pprf - plusquamperfect</option></select><br/><br/>";
		
		// Suche nach allen Kriterien
		echo "<button onclick='advSearch()'> Advanced Search</button><span class='token'> ■<span class='ext'>● search with all the specified criteria above<br/></span></span<br/></div>";
		
		echo "<button id='adv_options' onclick='advOptions()'> Advanced Options</button><br/>";

		// Korpus-Daten werden auf JS übergeben
		echo "<script type='text/javascript'>;
			var tokenData = null;
			var tokenData2 = null;
			var tokenDataSrc = './" . $ext_data . "';
			var tokenDataSrc2 = './" . $ext_data_2 . "';
			var srcData = JSON.parse('" . json_encode($src_desc) . "');
			var msdData = JSON.parse('" . json_encode($msds) . "');
			var corpusId = '" . $corpus_id . "';
			var corpusName = '" . $corpus_name . "';
			var letters = document.getElementsByClassName('letter');
			var expanded = false;
			</script>" . nl2br("\n\n");
		
		// Ergebnisse
		echo "<div id='results'></div>";

		echo nl2br("\n\n\n");

	?>

<script>
	
	window.onload = function() {
		
		for (var i = 0; i < letters.length; i++) {
			letters[i].addEventListener('click', keys, false);
			letters[i].addEventListener('mouseover', readKey, false);
			letters[i].addEventListener('mouseout', purgeKey, false);
		};
		
		getData(tokenDataSrc, false);
		
		if (tokenDataSrc2 !== './')
			getData(tokenDataSrc2, true);

	};
	
	function getData(src, a) {
		
		// neither fetch nor this seems to work on InfinityFree server
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.responseType = 'json';
		xmlhttp.open("GET", src, true);
		
		xmlhttp.onload = function() {
			if (this.readyState == 4 && this.status == 200 && a == false)
				tokenData = xmlhttp.response;
			else if (this.readyState == 4 && this.status == 200 && a == true)
				tokenData2 = xmlhttp.response;
			console.log(src, " loaded");
			
			var c_name = document.getElementById("c_name");
			c_name.innerHTML = corpusName;
			
		};

		xmlhttp.send(null);
		
		if (document.getElementById("lemma").value)
			lemmaSearch();
		
	};

	function expandData() {
		
		if (tokenData2 == null)
			return;
		
		var dataArray = [];
		for (var x in tokenData)
			dataArray.push(tokenData[x]);
		
		for (var x in tokenData2)
			dataArray.push(tokenData2[x]);
		
		tokenData = toObject(dataArray);
		
		expanded = true;
	};
	
	function toObject(array) {
		var rv = {};
		for (var i = 0; i < array.length; ++i) {
			rv[i] = array[i];
			if (array[i] !== undefined) rv[i] = array[i];	
		}
		return rv;
	};
	
	function tokenSearch() {
			
		if (tokenData2 !== null && expanded == false)
			 expandData();
		
		var query = document.getElementById("tokens").value.toLowerCase();
		var results = document.getElementById("results");
		var count = 0;
		
		if (query == "")
			return;
		
		results.innerHTML = '';
		
		for (var i in tokenData)
			for (var ii in tokenData[i]) {
				
				if (tokenData[i][ii].dipl == null)
					tokenData[i][ii].dipl = "_";
				
				if (srcData[i] == null)
					srcData[i] = tokenData[i][ii].src;
				
			//	results.innerHTML = results.innerHTML + tokenData[i][ii].dipl + " ";
			
				if (tokenData[i][ii].dipl.toLowerCase().includes(query)) {
						
					if (count < 1000) {
						
						results.innerHTML = results.innerHTML + "<a target=_blank href='syntax_browser.php?" + corpusId + "&chapter=" + tokenData[i][ii].src + "&cyr=2&sent_id=" + tokenData[i][ii].sent + "'>" + srcData[i] + " - sentence " + tokenData[i][ii].sent + "</a><br/>";
						
						for (var iii in tokenData[i])
							if (tokenData[i][ii].sent == tokenData[i][iii].sent)
								results.innerHTML = results.innerHTML + tokenData[i][iii].dipl + " ";
						
						results.innerHTML = results.innerHTML + "<br/><br/>";
					
					} else if (count == 1000)
						results.innerHTML = "limit exceeded - showing first 1000 hits<br/><br/>" + results.innerHTML;
					
					count = count + 1;
					
				};
			
			};

		results.innerHTML = "total hits: " + count + "<br/><br/>" + results.innerHTML;
				
	};
	
	function keys() {

		var keyId = this.classList.item(1);
		document.getElementById("tokens").value = document.getElementById("tokens").value + keyId;

	};

	function readKey() {
		
		var keyId = this.classList.item(1);
		
		for (var i = 0; i < letters.length; i++)
			if (letters[i].classList.item(1) == keyId)
				letters[i].style.background = "#d8dee4";
				
	};
	
	function purgeKey() {
		
		var keyId = this.classList.item(1);
		
		for (var i = 0; i < letters.length; i++)
			if (letters[i].classList.item(1) == keyId)
				letters[i].style.background = "white";
			
	};
	
	function lemmaSearch() {
		
		if (tokenData2 !== null && expanded == false)
			 expandData();
		 
		var query = document.getElementById("lemma").value;
		var results = document.getElementById("results");
		var count = 0;
		
		if (query == "")
			return;
		
		results.innerHTML = '';
		
		for (var i in tokenData)
			for (var ii in tokenData[i]) {
				
				if (tokenData[i][ii].lemma == null)
					tokenData[i][ii].lemma = "_";
				
				if (srcData[i] == null)
					srcData[i] = tokenData[i][ii].src;
			
				if (tokenData[i][ii].lemma == query || 
					query == null) {
					
					if (count < 1000) {
						
						results.innerHTML = results.innerHTML + "<a target=_blank href='syntax_browser.php?" + corpusId + "&chapter=" + tokenData[i][ii].src + "&cyr=2&sent_id=" + tokenData[i][ii].sent + "'>" + srcData[i] + " - sentence " + tokenData[i][ii].sent + "</a><br/>";
						
						for (var iii in tokenData[i])
							if (tokenData[i][ii].sent == tokenData[i][iii].sent)
								results.innerHTML = results.innerHTML + tokenData[i][iii].dipl + " ";
						
						results.innerHTML = results.innerHTML + "<br/><br/>";
					
					} else if (count == 1000)
						results.innerHTML = "limit exceeded - showing first 1000 hits<br/><br/>" + results.innerHTML;
					
					count = count + 1;
					
				};
			};

		results.innerHTML = "total hits: " + count + "<br/><br/>" + results.innerHTML;
		
	};
	
	function advOptions() {
	
		var div = document.getElementById("advanced");
		var button = document.getElementById("adv_options");
		
		div.style.display = "inline";
		button.style.display = "none";
	
	};
	
	function tagSearch() {
			
		if (tokenData2 !== null && expanded == false)
			 expandData();
		 
		var query = document.getElementById("msd").value;
		var results = document.getElementById("results");
		var count = 0;
		
		if (query == "")
			return;
		
		results.innerHTML = '';
		
		for (var i in tokenData)
			for (var ii in tokenData[i]) {
				
				if (tokenData[i][ii].PoS == null)
					tokenData[i][ii].PoS = "_";
				
				if (tokenData[i][ii].PoS_ext == null)
					tokenData[i][ii].PoS_ext = "_";
				
				if (srcData[i] == null)
					srcData[i] = tokenData[i][ii].src;
			
				if (tokenData[i][ii].PoS.match(wildcard(query)) || 
					tokenData[i][ii].PoS_ext.match(wildcard(query))) {
					
					if (count < 1000) {
						
						results.innerHTML = results.innerHTML + "<a target=_blank href='syntax_browser.php?" + corpusId + "&chapter=" + tokenData[i][ii].src + "&cyr=2&sent_id=" + tokenData[i][ii].sent + "'>" + srcData[i] + " - sentence " + tokenData[i][ii].sent + "</a><br/>";
						
						for (var iii in tokenData[i])
							if (tokenData[i][ii].sent == tokenData[i][iii].sent)
								results.innerHTML = results.innerHTML + tokenData[i][iii].dipl + " ";
						
						results.innerHTML = results.innerHTML + "<br/><br/>";
						
					} else if (count == 1000)
						results.innerHTML = "limit exceeded - showing first 1000 hits<br/><br/>" + results.innerHTML;
					
					count = count + 1;
					
				};
			};

		results.innerHTML = "total hits: " + count + "<br/><br/>" + results.innerHTML;
		
	};
	
	function writeTag() {
		
		var tag = document.getElementById("pos").value;
		document.getElementById("msd").value = tag;
			
	};
	
	// thx to https://gist.github.com/donmccurdy/6d073ce2c6f3951312dfa45da14a420f
	function wildcard(s) {
		return new RegExp('^' + s.split(/\?+/).map(escape).join('.') + '.*');
	} 
	
	function escape(s) {
		return s.replace(/[|\\{}()[\]^$+*?.]/g, '\\$&');
	}
	
	function udSearch() {
			 
		if (tokenData2 !== null && expanded == false)
			 expandData();
		 
		var queryBase = document.getElementById("ud").value;
		var queryExt = document.getElementById("ud_ext").value;
		var results = document.getElementById("results");
		var count = 0;
		
		if (queryBase == "")
			return;
		
		results.innerHTML = '';
		
		for (var i in tokenData)
			for (var ii in tokenData[i]) {
				
				if (tokenData[i][ii].UD_type == null)
					tokenData[i][ii].UD_type = "_";
				
				if (tokenData[i][ii].UD_ext == null)
					tokenData[i][ii].UD_ext = "_";
				
				if (srcData[i] == null)
					srcData[i] = tokenData[i][ii].src;
				
				if (tokenData[i][ii].UD_type == queryBase && 
					tokenData[i][ii].UD_ext.includes(queryExt)) {
					
					if (count < 1000) {					
					
						results.innerHTML = results.innerHTML + "<a target=_blank href='syntax_browser.php?" + corpusId + "&chapter=" + tokenData[i][ii].src + "&cyr=2&sent_id=" + tokenData[i][ii].sent + "'>" + srcData[i] + " - sentence " + tokenData[i][ii].sent + "</a><br/>";
						
						for (var iii in tokenData[i])
							if (tokenData[i][ii].sent == tokenData[i][iii].sent)
								results.innerHTML = results.innerHTML + tokenData[i][iii].dipl + " ";
						
						results.innerHTML = results.innerHTML + "<br/><br/>";
					
					} else if (count == 1000)
						results.innerHTML = "limit exceeded - showing first 1000 hits<br/><br/>" + results.innerHTML;
					
					count = count + 1;
					
				};
			};

		results.innerHTML = "total hits: " + count + "<br/><br/>" + results.innerHTML;
		
	};
	
	function advSearch() {
			 
		if (tokenData2 !== null && expanded == false)
			 expandData();
		 
		var queryText = document.getElementById("tokens").value.toLowerCase();
		var queryLemma = document.getElementById("lemma").value;
		var queryPoS = document.getElementById("msd").value;
		var queryUDBase = document.getElementById("ud").value;
		var queryUDExt = document.getElementById("ud_ext").value;
		var queryHeadBase = document.getElementById("head_ud").value;
		var queryHeadExt = document.getElementById("head_ext").value;
		
		var results = document.getElementById("results");
		var count = 0;
		
		if (queryText == "" && queryLemma == "" && queryPoS == "")
			return;
		
		results.innerHTML = '';
		
		for (var i in tokenData)
			for (var ii in tokenData[i]) {

				if (tokenData[i][ii].dipl == null)
					tokenData[i][ii].dipl = "_";
				
				if (tokenData[i][ii].lemma == null)
					tokenData[i][ii].lemma = "_";
				
				if (tokenData[i][ii].PoS == null)
					tokenData[i][ii].PoS = "_";
				
				if (tokenData[i][ii].PoS_ext == null)
					tokenData[i][ii].PoS_ext = "_";
				
				if (tokenData[i][ii].UD_type == null)
					tokenData[i][ii].UD_type = "_";
				
				if (tokenData[i][ii].UD_ext == null)
					tokenData[i][ii].UD_ext = "_";
				
				if (tokenData[i][ii].head_type == null)
					tokenData[i][ii].head_type = "_";
				
				if (tokenData[i][ii].head_ext == null)
					tokenData[i][ii].head_ext = "_";
				
				if (srcData[i] == null)
					srcData[i] = tokenData[i][ii].src;
			
				if (tokenData[i][ii].dipl.toLowerCase().includes(queryText) &&
					(tokenData[i][ii].lemma == queryLemma || 
						queryLemma == "") &&
					(tokenData[i][ii].PoS.match(wildcard(queryPoS)) || 
						tokenData[i][ii].PoS_ext.match(wildcard(queryPoS))) &&
					((tokenData[i][ii].UD_type == queryUDBase && 
						tokenData[i][ii].UD_ext.includes(queryUDExt)) || 
						queryUDBase == "") &&
					((tokenData[i][ii].head_type == queryHeadBase && 
						tokenData[i][ii].head_ext.includes(queryHeadExt)) || 
						queryHeadBase == "")) {
					
					if (count < 1000) {
						
					results.innerHTML = results.innerHTML + "<a target=_blank href='syntax_browser.php?" + corpusId + "&chapter=" + tokenData[i][ii].src + "&cyr=2&sent_id=" + tokenData[i][ii].sent + "'>" + srcData[i] + " - sentence " + tokenData[i][ii].sent + "</a><br/>";
						
						for (var iii in tokenData[i])
							if (tokenData[i][ii].sent == tokenData[i][iii].sent)
								results.innerHTML = results.innerHTML + tokenData[i][iii].dipl + " ";
						
						results.innerHTML = results.innerHTML + "<br/><br/>";
					
					} else if (count == 1000)
						results.innerHTML = "limit exceeded - showing first 1000 hits<br/><br/>" + results.innerHTML;
					
					count = count + 1;
					
				};
			};

		results.innerHTML = "total hits: " + count + "<br/><br/>" + results.innerHTML;
		
	};
	
</script>

</body>
</html>