<?php

$file = 'challenge.bin';
$memory = file_get_contents($file);

$DEBUG_LEVEL = 2;


// Arrange program into addresses:
$tmp = array();
for($i=0; $i<strlen($memory); $i+=2) {
	$b1 = sprintf("%02X", ord($memory[$i]));
	$b2 = sprintf("%02X", ord($memory[$i+1]));
	$tmp[] = hexdec($b2.$b1);
}
$memory = $tmp;

/*
foreach($memory as $i => $int) {
	echo "$i:\t$int\t".chr($int)."\n";
}
exit; 
*/

// Execute program
$i = 0;
$registers = array(0,0,0,0,0,0,0,0);
$stack = array();
$input = "";
while ($i<count($memory)) {
	$command = $memory[$i++];
	//mdebug("COMMAND: $command");
	switch($command) {
		case 0:
			// Halt the program
			echo "Execution halted.\n";
			exit;
		case 1:
			// Set var
			$var = $memory[$i++] % 32768;
			$value = int_reg($memory[$i++]);
			mdebug("SET REG $var to $value");
			$registers[$var] = $value;
			break;
		case 2:
			// Push onto stack
			$value = int_reg($memory[$i++]);
			mdebug("PUSH $value");
			$stack[] = $value;
			break;
		case 3:
			// Pop off stack
			$reg = $memory[$i++] % 32768;
			$val = array_pop($stack);
			$registers[$reg] = $val;
			mdebug("POP $val into $reg");
			break;
		case 4:
			// Set if equal
			$reg = $memory[$i++] % 32768;
			$value = int_reg($memory[$i++]);
			$test = int_reg($memory[$i++]);
			mdebug("$value =? $test: REG $reg");
			$registers[$reg] = ($value == $test)? 1 : 0;
			break;
		case 5:
			// Set if greater-than
			$reg = $memory[$i++] % 32768;
			$value = int_reg($memory[$i++]);
			$test = int_reg($memory[$i++]);
			mdebug("$value >? $test: REG $reg");
			$registers[$reg] = ($value > $test)? 1 : 0;
			break;
		case 6:
			// Jump
			$index = int_reg($memory[$i++]);
			mdebug("JMP $index");
			$i = $index;
			break;
		case 7:
			// Jump if nonzero
			$var = int_reg($memory[$i++]);
			$index = int_reg($memory[$i++]);
			if ($var > 0) {
				mdebug("$var NONZERO; JMP $index");
				$i = $index;
			} else {
				mdebug("$var NOT NONZERO; NO JMP");
			}
			break;
		case 8:
			// Jump if false
			$var = int_reg($memory[$i++]);
			$index = int_reg($memory[$i++]);
			if ($var == 0) {
				mdebug("$var ZERO; JMP $index");
				$i = $index;
			} else {
				mdebug("$var NOT ZERO; NO JMP");
			}
			break;
		case 9:
			// Add
			$reg = $memory[$i++] % 32768;
			$a = int_reg($memory[$i++]);
			$b = int_reg($memory[$i++]);
			$registers[$reg] = ($a+$b) % 32768;
			mdebug("SET REG $reg = $a + $b = ".$registers[$reg]);
			break;
		case 10:
			// Multiply
			$reg = $memory[$i++] % 32768;
			$a = int_reg($memory[$i++]);
			$b = int_reg($memory[$i++]);
			$registers[$reg] = ($a*$b) % 32768;
			mdebug("SET REG $reg = $a * $b = ".$registers[$reg]);
			break;
		case 11:
			// Modulus
			$reg = $memory[$i++] % 32768;
			$a = int_reg($memory[$i++]);
			$b = int_reg($memory[$i++]);
			$registers[$reg] = ($a % $b) % 32768;
			mdebug("SET REG $reg = $a % $b = ".$registers[$reg]);
			break;
		case 12:
			// Bitwise AND
			$reg = $memory[$i++] % 32768;
			$a = int_reg($memory[$i++]);
			$b = int_reg($memory[$i++]);
			$registers[$reg] = $a & $b;
			mdebug("SET REG $reg = $a & $b = ".$registers[$reg]);
			break;
		case 13:
			// Bitwise OR
			$reg = $memory[$i++] % 32768;
			$a = int_reg($memory[$i++]);
			$b = int_reg($memory[$i++]);
			$registers[$reg] = $a | $b;
			mdebug("SET REG $reg = $a | $b = ".$registers[$reg]);
			break;
		case 14:
			// Bitwise NOT
			$reg = $memory[$i++] % 32768;
			$a = int_reg($memory[$i++]);
			$registers[$reg] = (~$a) & 32767;
			mdebug("SET REG $reg = ~$a = ".$registers[$reg]);
			break;
		case 15:
			// Read from Memory to Register
			mdebug("COMMAND: 15", 2);
			$to = $memory[$i++] % 32768;
			$from = int_reg($memory[$i++]);
			mdebug("PROG $from:{$memory[$from]} => REG $to", 2);
			$registers[$to] = $memory[$from];
			//pause();
			break;
		case 16:
			// Write from Register to Memory
			mdebug("COMMAND: 16", 2);
			$to = int_reg($memory[$i++]);
			$from = int_reg($memory[$i++]);
			
			mdebug("PROG SET MEM $to:{$memory[$to]} = $from");
			$memory[$to] = $from;
			break;
		case 17:
			// Call
			$jump = int_reg($memory[$i++]);
			$stack[] = $i;
			mdebug("PUSH $i, JMP $jump");
			$i = $jump;
			break;
		case 18:
			// Jump to stack value
			$jump = array_pop($stack);
			mdebug("JUMP TO $jump OFF STACK");
			$i = $jump;
			break;
		case 19:
			// Echo the next character
			$ord = int_reg($memory[$i++]);
			echo chr($ord);
			break;
		case 20:
			// Read character
			$reg = $memory[$i++] % 32768;
			
			if ($input == "") {
				// Fill input buffer
				$input = fgets(STDIN);
				mdebug("INPUT '$input' at $reg");
			}

			//echo "\nBuffer: '$input'\n";
			$registers[$reg] = ord($input[0]);
			$input = substr($input,1); // Trim first character
			break;
		case 21:
			// Noop
			break;
		default:
			// Unknown command; skip ahead
			echo "Unknown command '$command'\n";
			exit;
	}
}
echo "Pointer at $i...";

function mdebug($msg, $level=1) {
	global $DEBUG_LEVEL;
	if ($level > $DEBUG_LEVEL) echo "{".$msg."}";
	return true;
}
function int_reg($int) {
	global $registers;
	if ($int <= 32767) return $int;
	$reg = $int % 32768;
	mdebug("REG $reg = ".$registers[$reg]);
	return $registers[$reg];		
}
function pause() {
	$i = fgets(STDIN);
	return true;
}