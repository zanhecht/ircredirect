<?php
$redisKey = @file_get_contents('../redis.key') ?: 'Bm0F0TKdeyhUE0TEO0karLN4q0G+ElXM:num';
$redisClient = new Redis();
$redisClient -> connect('tools-redis',6379);
$num = $redisClient -> get($redisKey);
if ($num < 99) {
	$num += 1;
	if (($num == 69) or ($num == 88)) {
		$num += 1;
	}
} else {
	$num = 0;
}
$redisClient -> set($redisKey, $num);
$redisClient -> close();

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
$project = isset($_GET['project']) ? $_GET['project'] : 'wikipedia';

if (isset($_GET['baseurl'])) {
	$baseurl = urldecode($_GET['baseurl']);
} else {
	$title = isset($_GET['title']) ? $_GET['title'] : 'Wikipedia:IRC_help_disclaimer/nicks.json';
	$baseurl = "https://$lang.$project.org/w/index.php?title=$title&action=raw";
}

if (preg_match("/^https:\/\/[^\/]*\.?(mediawiki|toolforge|wik(i(books|data|[mp]edia|news|quote|source|versity|voyage)|tionary)).org\//i", $baseurl)) {
	$jsonFile = file_get_contents($baseurl);
} else {
	$jsonFile = FALSE;
}

if ($jsonFile) {
	$nicks = json_decode($jsonFile, TRUE);
}

if ($jsonFile === FALSE or $nicks === FALSE or is_array($nicks) === FALSE or (is_array($nicks) and (!array_key_exists('nouns', $nicks) or !array_key_exists('adjectives', $nicks)))) {
	$nick = 'WPHelp' . sprintf('%02d', $num);
} else {
	$nick = $nicks['adjectives'][$num%20] . $nicks['nouns'][array_rand($nicks['nouns'])] . sprintf('%02d', $num);
}

if (isset($_GET['channel'])) {
	$channel = $_GET['channel'];
} elseif (isset($_GET['lang']) or isset($_GET['project'])) {
	$channel = "$project-$lang";
} else {
	$channel = $project;
}

if (isset($_GET['server'])) {
	$server = $_GET['server'];
} else {
	$server = 'irc.libera.chat';
}

$targetURL = 'https://kiwiirc.com/nextclient/' . urlencode($server) . '/' . urlencode($channel) . '?nick=' . urlencode($nick);

if (isset($_GET['debug'])) {
	echo('Location: '.htmlspecialchars($targetURL).'<hr>');
	echo('Server: '.htmlspecialchars($server).'<hr>');
	echo('Channel: '.htmlspecialchars($channel).'<hr>');
	echo('BaseUrl: '.htmlspecialchars($baseurl).'<hr>');
	if ($jsonFile === FALSE) {
		echo('$jsonFile === FALSE<hr>');
	} else {
		echo('$jsonFile = '.htmlspecialchars($jsonFile).'<hr>');
	}
	if ($nicks === FALSE) {
		echo('$nicks === FALSE<hr>');
	} elseif (is_array($nicks)) {
		echo('is_array($nicks) === TRUE, array_key_exists(\'nouns\', $nicks) = ' . (array_key_exists('nouns', $nicks) ? 'TRUE' : 'FALSE') . ', array_key_exists(\'adjectives\', $nicks) = ' . (array_key_exists('adjectives', $nicks)  ? 'TRUE' : 'FALSE') . '<hr>'); 
	} else {
		echo('is_array($nicks) === FALSE, $nicks = '.htmlspecialchars($nicks).'<hr>');
	}
} elseif (isset($_GET['consent'])) {
	header("Location: $targetURL");
} else {
	$chanhtml = htmlspecialchars("#$channel on $server");
	echo("<!DOCTYPE html>
		<html lang='en'>
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
				<link rel='StyleSheet' href='resources/mediawiki.ui.button.css' type='text/css' media='screen' />
				<style type='text/css'>
					<!--
					body {font-family: sans-serif; background-color: #ffffff; color: #202122; padding: 1em; font-size: 0.875em; line-height: 1.6;}
					h1, h2, h3, h4, h5, h6 {color: #000; margin: 0;}
					h1, h2 {margin-bottom: 0.25em; padding: 0; font-family: 'Linux Libertine','Georgia','Times',serif; line-height: 1.3; border-bottom: 1px solid #a2a9b1;}
					h1 {font-size: calc(1.8em/0.875); font-weight: normal;}
					p {margin:.4em 0 .8em 0;padding:0;}
					code {border-radius: 2px; padding: 1px 4px;}
					pre, code {background-color: #f8f9fa; color: #000; border: 1px solid #eaecf0;}
					pre, code, tt, kbd, samp {font-family: monospace,monospace;}
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
					<span class='mw-ui-button mw-ui-progressive'>Continue to $chanhtml at kiwiirc</span>
				</a></p>
				<p style='text-align: center;'>&nbsp;</p>
				<p style='text-align: center; font-size: smaller;'>To avoid seeing this message in the future, add <code>&consent=yes</code> to the end of the URL. <a href='README.html'>View documentation</a>.</p>
			</body>
		</html>");
}

exit();

/*
		The MIT License (MIT)

		Copyright (c) 2017–2021 Ahecht (https://en.wikipedia.org/wiki/User:Ahecht)

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
