<?php
$redisClient = new Redis();
$redisClient -> connect('tools-redis',6379);
$num = $redisClient -> get('Bm0F0TKdeyhUE0TEO0karLN4q0G+ElXM:num');
if ($num < 99) {
	$num += 1;
	if (($num == 69) or ($num == 88)) {
		$num += 1;
	}
} else {
	$num = 0;
}
$redisClient -> set('Bm0F0TKdeyhUE0TEO0karLN4q0G+ElXM:num', $num);
$redisClient -> close();

if (isset($_GET['baseurl'])) {
	$baseurl = urldecode($_GET['baseurl']);
} else {
	$baseurl = 'https://en.wikipedia.org/w/index.php?title=Wikipedia:IRC_help_disclaimer/nicks.json&action=raw';
}

$jsonFile = file_get_contents($baseurl);

if ($jsonFile) {
	$nicks = json_decode($jsonFile, TRUE);
}

if ($jsonFile === FALSE or $nicks === FALSE or (is_array($nicks) and (!array_key_exists('nouns', $nicks) or !array_key_exists('adjectives', $nicks)))) {
	$nick = 'WPHelp' . sprintf('%02d', $num);
} else {
	$nick = $nicks['adjectives'][$num%20] . $nicks['nouns'][array_rand($nicks['nouns'])] . sprintf('%02d', $num);
}

if (isset($_GET['channel'])) {
	$channel = $_GET['channel'];
} else {
	$channel = 'wikipedia-en-help';
}

if (isset($_GET['server'])) {
	$server = $_GET['server'];
} else {
	$server = 'irc.freenode.net';
}

$targetURL = 'https://kiwiirc.com/nextclient/' . urlencode($server) . '/' . urlencode($channel) . '?nick=' . urlencode($nick);

if (isset($_GET['debug'])) {
	echo("Location: $targetURL<hr>");
	if ($jsonFile === FALSE) {
		echo('$jsonFile === FALSE<hr>');
	} else {
		echo("\$jsonFile = $jsonFile<hr>");
	}
	if ($nicks === FALSE) {
		echo('$nicks === FALSE<hr>');
	} elseif (is_array($nicks)) {
		echo('is_array($nicks) === TRUE, array_key_exists(\'nouns\', $nicks) = ' . (array_key_exists('nouns', $nicks) ? 'TRUE' : 'FALSE') . ', array_key_exists(\'adjectives\', $nicks) = ' . (array_key_exists('adjectives', $nicks)  ? 'TRUE' : 'FALSE') . '<hr>'); 
	} else {
		echo("is_array(\$nicks) === FALSE, \$nicks = $nicks<hr>");
	}
} elseif (isset($_GET['consent'])) {
	header("Location: $targetURL");
} else {
	echo("<!DOCTYPE html>
		<html lang='en'>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<link rel='StyleSheet' href='resources/mediawiki.ui.button.css' type='text/css' media='screen' />
				<style type='text/css'>
					<!--
					body {font-size:90%; font-family: sans-serif;}
					h1 {margin:.8em 0 .2em 0;padding:0;}
					p {margin:.4em 0 .8em 0;padding:0;}
					code {font-family: monospace !important;}
					a {text-decoration: none; color: #0645ad;}
					a:hover, a:focus {text-decoration: underline;}
					a:visited {color:#0b0080}
					a:active {color:#faa700}
					-->
				</style>
				<title>IRC Redirect</title>
			</head>
			<body>
				<h1 style='text-align: center;'>IRC Redirect</h1>
				<p style='text-align: center;'>Click on the button below to continue to <a href=$targetURL>$targetURL</a>.<br />
				This is a third-party site not run by the Wikimedia Foundation, and may have a different privacy policy.</p>
				<p style='text-align: center;'><a href='$targetURL'>
					<span class='mw-ui-button mw-ui-progressive'>Continue to #$channel at kiwiirc</span>
				</a></p>
				<p style='text-align: center;'>&nbsp;</p>
				<p style='text-align: center; font-size: smaller;'>To avoid seeing this message in the future, add <code>&consent=yes</code> to the end of the URL. <a href='README.html'>View documentation</a>.</p>
			</body>
		</html>");
}

exit();

/*
		The MIT License (MIT)

		Copyright (c) 2017–2019 Ahecht (https://en.wikipedia.org/wiki/User:Ahecht)

		Permission is hereby granted, free of charge, to any person
		obtaining a copy of this software and associated documentation
		files (the 'Software'), to deal in the Software without
		restriction, including without limitation the rights to use,
		copy, modify, merge, publish, distribute, sublicense, and/or sell
		copies of the Software, and to permit persons to whom the
		Software is furnished to do so, subject to the following
		conditions:

		The above copyright notice and this permission notice shall be
		included in all copies or substantial portions of the Software.

		THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND,
		EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
		OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
		NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
		HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
		WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
		FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
		OTHER DEALINGS IN THE SOFTWARE.
*/
