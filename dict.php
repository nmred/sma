<?php
require_once "vendor/autoload.php";

// {{{ fanyi
use PHPHtmlParser\Dom;

function fanyi($word) {
	$dom = new Dom;
	$content = file_get_contents('http://dict.cn/' . $word);
	$content = str_replace('<!--', '', $content);
	$content = str_replace('-->', '', $content);
	$dom->load($content);
	$dom->setOptions([
			'strict' => true, // Set a global option to enable strict html parsing.
			'whitespaceTextNode' => false,
			'cleanupInput' => true,
	]);

	$phoneticbdos = $dom->find('.phonetic span bdo');
	$bdos = array();
	foreach ($phoneticbdos as $bdo) {
		$bdos[] = $bdo->innerHtml;
	}

	$sounds = array();
	$phoneticsounds = $dom->find('.phonetic span i');
	foreach ($phoneticsounds as $sound) {
		$sounds[] = $sound->getAttribute('naudio');
	}

	$basics = array();
	$dictbasics = $dom->find('.basic ul');
	foreach ($dictbasics as $basic) {
		$basic = trim(strip_tags(str_replace('search_in_n', '', $basic->innerHtml)));
		if ($basic) {
			$basics[] = $basic;		
		}
	}

	$sents = array();
	$sent = $dom->find('.sent ol li');
	foreach($sent as $s) {
		$s = trim(strip_tags($s->innerHtml, '<em><br>'));
		if ($s) {
			$sents[] = $s;		
		}
	}

	return array(
		'bdos' => $bdos,
		'basics' => $basics,
		'sents'  => $sents,
	);
}

// }}}


foreach (glob("*.md") as $file) {
	$contents = file($file);
	$words = array();
	$fanyiStart = false;
	foreach ($contents as $key => $line) {
		if (trim($line) == '<!-- FANYI SATRT -->') {
			$fanyiStart = true;	
		}
		if ($fanyiStart) {
			unset($contents[$key]);
			continue;	
		}
		if (preg_match_all('/@@([a-zA-Z-]*)@@/', $line, $out)) {
			if(isset($out[1])) {
				foreach($out[1] as $val) {
					$words[] = $val;	
				}
			}
		}
		if (preg_match_all('/<f>([a-zA-Z-]*)<\/f>/', $line, $out)) {
			if(isset($out[1])) {
				foreach($out[1] as $val) {
					$words[] = $val;	
				}
			}
		}
		$line = preg_replace('/@@([a-zA-Z-]*)@@/i', '<f>$1</f>', $line);
		$line = str_replace('>', '', $line);
		$line = str_replace('<f', '<f>', $line);
		$line = str_replace('</f', '</f>', $line);
		$contents[$key] = $line;
	}
	$result = array();
	foreach ($words as $word) {
		$result[$word] = fanyi(trim($word));
	}

	$result = json_encode($result);
	$html = printFanyi($result);
	$contents[] = PHP_EOL . $html;
	if (!empty($words)) {
		file_put_contents($file, implode('', $contents));
	}
}

function printFanyi($result) {
	$html = <<<EOF
<!-- FANYI SATRT -->
<link rel="stylesheet" type="text/css" href="tooltip.css">
<style>
f {
	background: yellow;
}
</style>
<script type="text/javascript" src="jquery-1.11.2.js"></script>
<script type="text/javascript" src="tooltip.js"></script>
<script>
$(document).ready(function() {
});
var fanyiData = $result;
</script>
<!-- FANYI END --->
EOF;
	return $html;
}
