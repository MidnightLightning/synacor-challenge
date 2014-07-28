<?php
$file = 'challenge_unscrambled.bin';
$memory = file_get_contents($file);

$tmp = array();
for($i=0; $i<strlen($memory); $i+=2) {
	$b1 = sprintf("%02X", ord($memory[$i]));
	$b2 = sprintf("%02X", ord($memory[$i+1]));
	$tmp[] = hexdec($b2.$b1);
}
$memory = $tmp;


$pointer = 0x2DC4;
$items = array();
$item_count = $memory[$pointer];
for ($i=1; $i<=$item_count; $i++) {
	$item = array();
	$item['id'] = $memory[$pointer+$i];
	$item['name'] = getVarStringAt($memory[$item['id']]);
	$item['desc'] = getVarStringAt($memory[$item['id']+1]);
	$item['room'] = $memory[$item['id']+2];
	print_r($item);
}

function getVarStringAt($pointer) {
	global $memory;
	$len = $memory[$pointer];
	$str = '';
	for ($i=1; $i<=$len; $i++) {
		$str .= chr($memory[$pointer+$i]);
	}
	return $str;
}

function dumpRaw($start, $end) {
	global $memory;
	$i = $start;
	$rowsize = 16;
	while ($i <= $end) {
		echo '0x'.dechex($i).': ';
		for($x=0; $x<$rowsize; $x++) {
			echo sprintf('%04X', $memory[$i+$x]).' ';
		}
		echo '|';
		for($x=0; $x<$rowsize; $x++) {
			$ord = $memory[$i+$x];
			if ($ord >= 32 && $ord <= 126) {
				echo chr($ord);
			} else {
				echo '.';
			}
		}
		echo "\n";
		$i += $rowsize;
	}
}
		