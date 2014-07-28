<?php
// Coin solver
$coins = array(2,3,5,7,9);

$coin_names = array(
	2 => 'red',
	3 => 'corroded',
	5 => 'shiny',
	7 => 'concave',
	9 => 'blue',
);

while (true) {
	shuffle($coins);
	$current = array();
	foreach ($coins as $c) {
		$current[] = $coin_names[$c];
	}
	print_r($current);
	$rs = $coins[0] + $coins[1] * pow($coins[2], 2) + pow($coins[3], 3) - $coins[4];
	echo "Result: $rs\n\n";
	if ($rs == 399) exit;
}