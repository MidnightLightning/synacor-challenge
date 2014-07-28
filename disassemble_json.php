<?php
$file = (isset($argv[1]))? $argv[1] : 'challenge.bin';
$memory = file_get_contents($file);

$tmp = array();
for($i=0; $i<strlen($memory); $i+=2) {
	$b1 = sprintf("%02X", ord($memory[$i]));
	$b2 = sprintf("%02X", ord($memory[$i+1]));
	$tmp[] = hexdec($b2.$b1);
}
$memory = $tmp;

echo "var address = [\n\t";
for ($i=0; $i<count($memory); $i++) {
	printf('% 5d', $memory[$i]);
	if ((($i+1) % 10) == 0) {
		echo ",\t// {$i}\n\t";
	} elseif ($i+1 != count($memory)) {
		echo ", ";
	}
}
echo "\n];\n";
