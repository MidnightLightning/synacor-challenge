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


$pointer = 0x91b;
$rooms = array();
getRooms($pointer);
print_r($rooms);

function getRooms($pointer) {
	global $rooms;
	$room = getRoomAt($pointer);
	if (!isset($rooms[$room['id']])) {
		$rooms[$room['id']] = $room;
		foreach($room['exits'] as $e) {
			getRooms($e['dest']);
		}
	}
}

function getRoomAt($pointer) {
	global $memory;
	$room = array();
	$room['id'] = $pointer;
	$room['name'] = getVarStringAt($memory[$pointer]);
	$room['description'] = getVarStringAt($memory[$pointer+1]);
	$exitArray = $memory[$pointer+2];
	$exitNum = $memory[$exitArray];
	$room['exits'] = array();
	for($i=1; $i<=$exitNum; $i++) {
		$exitId = $memory[$exitArray+$i];
		$exitName = getVarStringAt($exitId);
		$room['exits'][] = array('id' => $exitId, 'name' => $exitName);
	}
	
	$exitDests = $memory[$pointer+3];
	for ($i=1; $i<=$exitNum; $i++) {
		$room['exits'][$i-1]['dest'] = $memory[$exitDests+$i];
	}
	
	return $room;
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
		