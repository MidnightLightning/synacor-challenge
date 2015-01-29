<?php
$file = (isset($arvg[1]))? $argv[1] : 'challenge_unscrambled.bin';
$memory = file_get_contents($file);

$tmp = array();
for($i=0; $i<strlen($memory); $i+=2) {
	$b1 = sprintf("%02X", ord($memory[$i]));
	$b2 = sprintf("%02X", ord($memory[$i+1]));
	$tmp[] = hexdec($b2.$b1);
}
$memory = $tmp;

$line_length = 24;
for ($i=0; $i<count($memory); $i+=$line_length) {
	printf("\n0x%04x: ", $i);
	for ($j=0; $j<$line_length; $j++) {
		if ($i+$j > count($memory)) {
			echo "     ";
		} else {
			printf("%04x ", $memory[$i+$j]);
		}
	}
	echo "| ";
	for ($j=0; $j<$line_length; $j++) {
		if ($i+$j > count($memory)) {
			echo " ";
		} elseif ($memory[$i+$j] == 10) {
			echo mb_convert_encoding('&#x240D;', 'UTF-8', 'HTML-ENTITIES');
		} elseif ($memory[$i+$j] >= 32 && $memory[$i+$j] <= 126) {
			echo chr($memory[$i+$j]);
		} else {
			echo ".";
		}
	}
}
echo "\n";