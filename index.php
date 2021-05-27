<?php
$redisClient = new Redis();
$redisClient -> connect('tools-redis',6379);
$num = $redisClient -> get('Bm0F0TKdeyhUE0TEO0karLN4q0G+ElXM_num');
if($num < 99) {	$num += 1; } else {	$num = 0; }
$redisClient -> set('Bm0F0TKdeyhUE0TEO0karLN4q0G+ElXM_num', $num);
$redisClient -> close();

$ntoptext = "----<!--NOUN VALUES ONLY BELOW THIS LINE-->----\n";
$nbottext = "----<!--NOUN VALUES ONLY ABOVE THIS LINE-->----\n";

$atoptext = "----<!--ADJECTIVE VALUES ONLY BELOW THIS LINE-->----\n";
$abottext = "----<!--ADJECTIVE VALUES ONLY ABOVE THIS LINE-->----\n";

$baseurl = $_GET['baseurl'];
$baseurl = ($baseurl === null) ? $baseurl = "https://en.wikipedia.org/w/index.php?title=Wikipedia:IRC_help_disclaimer/nicks&action=raw" : urldecode($baseurl);

$nicks = file($baseurl);

$nountop = array_search($ntoptext,$nicks);
$nounbot = in_array($nbottext,$nicks) ? array_search($nbottext,$nicks) : array_search(rtrim($nbottext),$nicks);
$adjtop = array_search($atoptext,$nicks);
$adjbot = in_array($abottext,$nicks) ? array_search($abottext,$nicks) : array_search(rtrim($abottext),$nicks);

if($nountop === FALSE or $nounbot === FALSE or $adjtop === FALSE or $adjbot === FALSE) {
    $nouns = ["Help"];
    $adjs = array_fill(0,20,"WP");
} else {
    $nouns = array_slice($nicks,$nountop+1,$nounbot-$nountop-1);
    $adjs = array_pad(array_slice($nicks,$adjtop+1,$adjbot-$adjtop-1),20,"");
}

$nick = rtrim($adjs[$num%20]) . rtrim($nouns[array_rand($nouns)]) . sprintf("%02d", $num); 

$channel = $_GET['channel'];
if($channel === null) $channel = 'wikipedia-en-help';

$server = $_GET['server'];
if($server === null) $server = 'irc.freenode.net';

header('Location: https://kiwiirc.com/client/' . urlencode($server) . '/' . urlencode($channel) . '?nick=' . urlencode($nick));
//echo('Location: https://kiwiirc.com/client/' . urlencode($server) . '/' . urlencode($channel) . '?nick=' . urlencode($nick));
exit();

/*
        Copyright (c) 2017 Ahecht (https://en.wikipedia.org/wiki/User:Ahecht)

        The MIT License

        Copyright (c) 2011 Wyss Institute at Harvard University
        
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
        
        http://www.opensource.org/licenses/mit-license.php
*/
