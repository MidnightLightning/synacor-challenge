<?php
$file = 'challenge.bin';
$memory = file_get_contents($file);

$tmp = array();
for($i=0; $i<strlen($memory); $i+=2) {
	$b1 = sprintf("%02X", ord($memory[$i]));
	$b2 = sprintf("%02X", ord($memory[$i+1]));
	$tmp[] = hexdec($b2.$b1);
}
$memory = $tmp;

echo "Address Hex (Dec): Value Hex (Dec, Bin, CMD)\n";
$cmds = array('HALT', 'SET', 'PUSH', 'POP', 'EQ', 'GT', 'JMP', 'JT', 'JF', 'ADD', 'MULT', 'MOD', 'AND', 'OR', 'NOT', 'RMEM', 'WMEM', 'CALL', 'RET', 'OUT', 'IN', 'NOOP');
foreach($memory as $i => $value) {
	echo sprintf('0x%06x', $i).' ('.sprintf('%05d', $i).'): ';
	$bin = sprintf('%016b', $value);
	$cmd = 'DATA';
	if ($value < count($cmds)) {
		$cmd = $cmds[$value];
	} elseif ($value >= 32 && $value <= 126) {
		$cmd = "'".chr($value)."'";
	} elseif ($value > 32767) {
		$cmd = 'R'.($value-32768);
	}
	
	echo sprintf('0x%04x', $value).' ('.sprintf('%05d', $value).', '.implode(' ', str_split($bin,4)).", {$cmd})\n";
}
