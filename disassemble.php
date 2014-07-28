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

$i = 0;
while ($i < count($memory)) {
	$address = $i; // Initial address location
	$cmd = $memory[$i++];
	//echo "(RAW $cmd) ";
	switch ($cmd) {
		case 0:
			echo 'HALT ';
			break;
		case 1:
			echo valueFormat($memory[$i++]).' = '.valueFormat($memory[$i++]);
			break;
		case 2:
			echo 'PUSH '.valueFormat($memory[$i++]);
			break;
		case 3:
			echo 'POP '.valueFormat($memory[$i++]);
			break;
		case 4: // EQ
			echo 'EQ (SET '.valueFormat($memory[$i++]).' = ('.valueFormat($memory[$i++]).' == '.valueFormat($memory[$i++]).')? 1 : 0)';
			break;
		case 5: // GT
			echo 'GT (SET '.valueFormat($memory[$i++]).' = ('.valueFormat($memory[$i++]).' > '.valueFormat($memory[$i++]).')? 1 : 0)';
			break;
		case 6:
			echo 'JMP '.valueFormat($memory[$i++]);
			break;
		case 7: // JT
			echo 'JT (IF '.valueFormat($memory[$i++]).' != 0, JMP '.valueFormat($memory[$i++]).')';
			break;
		case 8: // JF
			echo 'JF (IF '.valueFormat($memory[$i++]).' == 0, JMP '.valueFormat($memory[$i++]).')';
			break;
		case 9: // ADD
			echo valueFormat($memory[$i++]).' = '.valueFormat($memory[$i++]).' + '.valueFormat($memory[$i++]). ' (% 32768)';
			break;
		case 10: // MULT
			echo valueFormat($memory[$i++]).' = '.valueFormat($memory[$i++]).' * '.valueFormat($memory[$i++]). ' (% 32768)';
			break;
		case 11: // MOD
			echo valueFormat($memory[$i++]).' = '.valueFormat($memory[$i++]).' % '.valueFormat($memory[$i++]);
			break;
		case 12: // AND
			echo valueFormat($memory[$i++]).' = '.valueFormat($memory[$i++], 'b').' & '.valueFormat($memory[$i++], 'b');
			break;
		case 13: // OR
			echo valueFormat($memory[$i++]).' = '.valueFormat($memory[$i++], 'b').' ^ '.valueFormat($memory[$i++], 'b');
			break;
		case 14: // NOT
			echo valueFormat($memory[$i++]).' = ~'.valueFormat($memory[$i++], 'b');
			break;
		case 15: // RMEM
			echo valueFormat($memory[$i++]).' = ['.valueFormat($memory[$i++]).']';
			break;
		case 16: // WMEM
			echo '['.valueFormat($memory[$i++]).'] = '.valueFormat($memory[$i++]);
			break;
		case 17:
			echo 'CALL '.valueFormat($memory[$i++]);
			break;
		case 18:
			echo 'RET ';
			break;
		case 19:
			echo "OUT ";
			$ord = $memory[$i++];
			if ($ord >= 32 && $ord <= 126) {
				$chr = true;
				echo "'".chr($ord);
			} else {
				$chr = false;
				echo valueFormat($ord);
			}
			while ($memory[$i] == 19) {
				// Next command is an OUT too; absorb it into this one
				$i++; // Skip the OUT command
				$ord = $memory[$i++];
				if ($ord >= 32 && $ord <= 126) {
					if (!$chr) echo ", '";
					$chr = true;
					echo chr($ord);
				} else {
					if ($chr) echo "'";
					$chr = false;
					echo valueFormat($ord);
				}
			}
			if ($chr) echo "'";
			break;
		case 20:
			echo 'IN '.valueFormat($memory[$i++]);
			break;
		case 21:
			echo 'NOOP ';
			break;
		default:
			$bin = sprintf('%016b', $cmd);
			
			echo sprintf('0x%04x', $cmd).' ('.$cmd.', '.implode(' ', str_split($bin,4)).')';
	}
	echo "\t; ".sprintf('0x%06x', $address)."\n";
}

function valueFormat($value, $base = 'h') {
	if ($value <= 32767) {
		if ($base == 'h') {
			return sprintf('0x%04x', $value);
		} elseif ($base == 'b') {
			$bin = sprintf('%016b', $value);
			return implode(' ', str_split($bin, 4));
		}
	} elseif ($value <= 32775) {
		return 'R'.($value-32768);
	}
	return false;
}
	
/*
$command_ids = array_keys($commands);
foreach($memory as $a => $data) {
	if (in_array($data, $command_ids)) {
		$final = $commands[$data];
	} elseif ($data >= 32 && $data <= 126) {
		$final = "'".chr($data)."'";
	} elseif ($data >= 32768) {
		$final = "R".($data-32768);
	} else {
		$final = sprintf("0x%04x", $data);
	}
	echo sprintf("0x%06x", $a).":\t".$final."\n";
}
*/