<?php
$redisClient = new Redis();
$redisClient -> connect('tools-redis',6379);
$num = $redisClient -> get('84Gbx4rM1qBT91Y4rbUMvuJZkJSaWETP_num');
if($num < 99) {	$num += 1; } else {	$num = 0; }
$redisClient -> set('84Gbx4rM1qBT91Y4rbUMvuJZkJSaWETP_num', $num);
$redisClient -> close();

$nicks = array("Alpaca", "Berry", "Canary", "Durian", "Emu", "Fig", "Grape", "Heron", "Ibex", "Jaguar", "Kiwi", "Lemur", "Melon", "Narwhal", "Ocelot", "Papaya", "Quetzal", "Rhino", "Stork", "Tiger", "Unicorn", "Vervet", "Walnut", "Xerus", "Yew", "Zebra");
$colors = array("Amber", "Brown", "Cyan", "Diamond", "Emerald", "Green", "Indigo", "Jade", "Khaki", "Lime", "Magenta", "Navy", "Orange", "Purple", "Red", "Silver", "Tan", "White", "Yellow", "Zircon");
$nick = $colors[$num%20] . $nicks[array_rand($nicks)] . sprintf("%02d", $num); 

$channel = $_GET['channel'];
if($channel === null) $channel = 'wikipedia-en-help';

$server = $_GET['server'];
if($server === null) $server = 'irc.freenode.net';

header('Location: https://kiwiirc.com/client/' . urlencode($server) . '/' . urlencode($channel) . '?nick=' . urlencode($nick));
//echo('Location: https://kiwiirc.com/client/' . urlencode($server) . '/' . urlencode($channel) . '?nick=' . urlencode($nick));
exit();
?>
