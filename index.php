<?php
$nicks = array("Alpaca", "Berry", "Canary", "Durian", "Emu", "Fig", "Grape", "Heron", "Ibex", "Jaguar", "Kiwi", "Lemur", "Melon", "Narwhal", "Ocelot", "Papaya", "Quetzal", "Rhino", "Stork", "Tiger", "Unicorn", "Vervet", "Walnut", "Xerinae", "Yew", "Zebra");
$colors = array("Brown", "Cyan", "Green", "Magenta", "Orange", "Purple", "Red", "White", "Yellow");
$nick = $colors[array_rand($colors)] . $nicks[array_rand($nicks)] . mt_rand(0,9) . mt_rand(0,9); 

$channel = $_GET['channel'];
if($channel === null) $channel = 'wikipedia-en-help';

$server = $_GET['server'];
if($server === null) $server = 'irc.freenode.net';

header('Location: https://kiwiirc.com/client/' . urlencode($server) . '/' . urlencode($channel) . '?nick=' . urlencode($nick));
exit();
?>
