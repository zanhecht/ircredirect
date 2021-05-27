<?php
$redisClient = new Redis();
$redisClient -> connect('tools-redis',6379);
$num = $redisClient -> get('Bm0F0TKdeyhUE0TEO0karLN4q0G+ElXM_num');
if ($num < 99) { $num += 1; } else { $num = 0; }
$redisClient -> set('Bm0F0TKdeyhUE0TEO0karLN4q0G+ElXM_num', $num);
$redisClient -> close();

$ntoptext = "----<!--NOUN VALUES ONLY BELOW THIS LINE-->----\n";
$nbottext = "----<!--NOUN VALUES ONLY ABOVE THIS LINE-->----\n";

$atoptext = "----<!--ADJECTIVE VALUES ONLY BELOW THIS LINE-->----\n";
$abottext = "----<!--ADJECTIVE VALUES ONLY ABOVE THIS LINE-->----\n";

if (isset($_GET['baseurl'])) {
	$baseurl = urldecode($_GET['baseurl']);
} else {
	$baseurl = "https://en.wikipedia.org/w/index.php?title=Wikipedia:IRC_help_disclaimer/nicks&action=raw";
}

$nicks = file($baseurl);

$nountop = array_search($ntoptext,$nicks);
$nounbot = in_array($nbottext,$nicks) ? array_search($nbottext,$nicks) : array_search(rtrim($nbottext),$nicks);
$adjtop = array_search($atoptext,$nicks);
$adjbot = in_array($abottext,$nicks) ? array_search($abottext,$nicks) : array_search(rtrim($abottext),$nicks);

if ($nountop === FALSE or $nounbot === FALSE or $adjtop === FALSE or $adjbot === FALSE) {
	$nick = "WPHelp" . sprintf("%02d", $num);
} else {
	$nouns = array_slice($nicks,$nountop+1,$nounbot-$nountop-1);
	$adjs = array_pad(array_slice($nicks,$adjtop+1,$adjbot-$adjtop-1),20,"");
	$nick = rtrim($adjs[$num%20]) . rtrim($nouns[array_rand($nouns)]) . sprintf("%02d", $num);
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

if (isset($_GET['debug'])) {
	echo('Location: https://kiwiirc.com/client/' . urlencode($server) . '/' . urlencode($channel) . '?nick=' . urlencode($nick));
} elseif (isset($_GET['consent'])) {
	header('Location: https://kiwiirc.com/client/' . urlencode($server) . '/' . urlencode($channel) . '?nick=' . urlencode($nick));
} else {
	echo("<!DOCTYPE html>
		<html lang=\"en\">
			<head>
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
				<link rel=\"StyleSheet\" href=\"resources/mediawiki.ui.button.css\" type=\"text/css\" media=\"screen\" />
				<style type=\"text/css\">
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
				<h1 style=\"text-align: center;\">IRC Redirect</h1>
				<p style=\"text-align: center;\">Click on the button below to continue to <a href=https://kiwiirc.com/client/" . urlencode($server) . "/" . urlencode($channel) . "?nick=" . urlencode($nick) . ">https://kiwiirc.com/client/" . urlencode($server) . "/" . urlencode($channel) . "?nick=" . urlencode($nick) . "</a>.<br />
				This is a third-party site not run by the Wikimedia Foundation, and may have a different privacy policy.</p>
				<p style=\"text-align: center;\"><a href=\"https://kiwiirc.com/client/" . urlencode($server) . "/" . urlencode($channel) . "?nick=" . urlencode($nick) . "\">
					<span class=\"mw-ui-button mw-ui-progressive\">Continue to #" . $channel . " at kiwiirc</span>
				</a></p>
				<p style=\"text-align: center;\">&nbsp;</p>
				<p style=\"text-align: center; font-size: smaller;\">To avoid seeing this message in the future, add <code>&consent=yes</code> to the end of the URL. <a href=\"README.html\">View documentation</a>.</p>
			</body>
		</html>");
}

exit();

/*
		The MIT License (MIT)

		Copyright (c) 2017 Ahecht (https://en.wikipedia.org/wiki/User:Ahecht)

		Permission is hereby granted, free of charge, to any person
		obtaining a copy of this software and associated documentation
		files (the \"Software\"), to deal in the Software without
		restriction, including without limitation the rights to use,
		copy, modify, merge, publish, distribute, sublicense, and/or sell
		copies of the Software, and to permit persons to whom the
		Software is furnished to do so, subject to the following
		conditions:

		The above copyright notice and this permission notice shall be
		included in all copies or substantial portions of the Software.

		THE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND,
		EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
		OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
		NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
		HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
		WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
		FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
		OTHER DEALINGS IN THE SOFTWARE.
*/
