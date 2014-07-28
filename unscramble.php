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

// Unscramble main data area
for ($i=0x12A5; $i<count($memory); $i++) {
	$memory[$i] = $memory[$i] ^ 0x7F7F;
}
$memory[0x387] = 0x15; // Don't call unscrambling function
$memory[0x388] = 0x15;

// Unscramble pre-run check reward string
$i = 0x2C9F;
for ($x=0; $x<0x2A; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0x435] = 0x752; // Update output to standard print

// "Things of interest here:"
$i = 0x2E0A;
for($x=0; $x<0x1A; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xB85] = 0x752; // Update output to standard print

// "What do you do?"
$i = 0x2de1;
for($x=0; $x<0x11; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xa42] = 0x752; // Update output to standard print


// "You see no such item"
$i = 0x2df2;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xb16] = 0x752; // Update output to standard print

// "I don't understand; try 'help' for instructions"
$i = 0x2e24;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xc3e] = 0x752; // Update output to standard print

// Help text:
/*

look
  You may merely 'look' to examine the room, or you may 'look <subject>' (such as 'look chair') to examine something specific.
go
  You may 'go <exit>' to travel in that direction (such as 'go west'), or you may merely '<exit>' (such as 'west').
inv
  To see the contents of your inventory, merely 'inv'.
take
  You may 'take <item>' (such as 'take large rock').
drop
  To drop something in your inventory, you may 'drop <item>'.
use
  You may activate or otherwise apply an item with 'use <item>'.

*/
$i = 0x2e56;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xc5f] = 0x752; // Update output to standard print

// "Your inventory:"
$i = 0x304f;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xc7e] = 0x752; // Update output to standard print

// "Taken"
$i = 0x3060;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xcbf] = 0x752; // Update output to standard print

// "You see no such item here"
$i = 0x3068;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xcd9] = 0x752; // Update output to standard print

// "Dropped"
$i = 0x3084;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xd11] = 0x752; // Update output to standard print

// "You can't find that in your pack"
$i = 0x308e;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xd2b] = 0x752; // Update output to standard print

// "You can't find that in your pack"
$i = 0x30b1;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xd69] = 0x752; // Update output to standard print

// "Nothing interesting seems to happen"
$i = 0x30d4;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xd83] = 0x752; // Update output to standard print

// "You have been eaten by a grue"
$i = 0x30fa;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xdcd] = 0x752; // Update output to standard print

// "Scrawled on the wall of one of the passageways, you see:"
$i = 0x3128;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xe46] = 0x752; // Update output to standard print

/*
// password?
$i = 0x311a;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x40;
}
$memory[0xe54] = 0x752; // Update output to standard print
*/

// "You take note of this and keep walking."
$i = 0x3167;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xe60] = 0x752; // Update output to standard print

// "That door is locked."
$i = 0x3193;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xe8f] = 0x752; // Update output to standard print

// "You find yourself writing "
$i = 0x31a9;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xeb4] = 0x752; // Update output to standard print

// " on the tablet. Perhaps its some kind of code?"
$i = 0x31c5;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xefd] = 0x752; // Update output to standard print


// "You fill your lantern with oil.  It seems to cheer up!"
$i = 0x31f9;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xf39] = 0x752; // Update output to standard print

// "You'll have to find something to put the oil into first"
$i = 0x3232;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xf53] = 0x752; // Update output to standard print


// "You light your lantern."
$i = 0x326d;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xf7e] = 0x752; // Update output to standard print

// "You douse your lantern."
$i = 0x3287;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0xfac] = 0x752; // Update output to standard print

// "You place the "
$i = 0x32a1;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0x1005] = 0x752; // Update output to standard print

// " into the leftmost open slot"
$i = 0x32b0;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0x102e] = 0x752; // Update output to standard print

// "As you place the last coin, they are all released onto the floor."
$i = 0x32cf;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0x10db] = 0x752; // Update output to standard print

// "As you place the last coin, you hear a click from the north door."
$i = 0x3312;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0x10f5] = 0x752; // Update output to standard print

// "You activate the teleporter!  After a swirl of colors, you find yourself at Synacor headquarters.  Here, you find one last signpost.  It reads"
$i = 0x3355;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0x117a] = 0x752; // Update output to standard print

// "Congratulations; you have reached the end of the challenge!"
$i = 0x33e8;
$len = $memory[$i];
for($x=1; $x<=$len; $x++) {
	$memory[$i+$x] = $memory[$i+$x] ^ 0x60;
}
$memory[0x11c2] = 0x752; // Update output to standard print


$out = '';
foreach($memory as $i=>$v) {
	$out .= pack('v', $v);
}
echo $out;