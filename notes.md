
* Magic key of main data area: `0x7f7f`
* Magic key of other program strings: `0x60`
* To start the program without doing any of the pre-run checks, jump cursor to `0xa08` directly (`JMP` command at `0x442` is what calls that main subroutine after pre-run checks normally).
* To teleport to a specific room: set `0xa06` to point to the room's data structure and jump cursor to `0xae1` to show it.
* MAX_INT = `0x7fff`

# Subroutines

* `0x70c`: Pass the data at R0 through the function at address R1.
    * `0x752`: Simple output function; echo character stored in R0.
    * `0x755`: Decrypting output function; echo the exclusive-or of R0 (data) and R2 (magic key). (uses `0x901` to do the XORing)
	* `0x79f`: String search; if the current character (R0) matches the search target (R2), set R2 to that offset, and jump to the end of the loop (set R1 to MAX_INT)
* `0x748`: Shortcut to call subroutine `0x70c` with output function `0x752` (simple output).
* `0x815`: Unscramble memory chunk. Set all memory addresses after `0x12a5` to the exclusive-or of themselves and `0x7f7f`.
* `0x885`: Take a binary number, and turn it into ASCII output
* `0x901`: Set R0 to the exclusive-or of R0 and R1.
* `0xb25`: Show current room. Calls `0x748` to echo room title and description, `0x11d9` to show things, `0x885` to show exits
* `0x11d9`: Count things of note in the current room (stores result in `R0`).
    * `0x11f0`: output function (for use with `0x70c`) to count items in the current room (stores result in `R2`).
* `0x11d0`: Echo the string at R0, with a "- " before, and a "\n" after.
* `0x120e`: Show the current room's items.
    * `0x121f`: output function (for use with `0x70c`) to show the items in room `R2`.

* `0x83b`: Get user input, until a carriage return is detected
* `0x77d`: Search string at R0 for character R1. R0 is set to location of first occurrence or MAX_INT
    * `0x761`: String search function. Search the string at R0 for R2. Sets R0 to the found location or MAX_INT if not found

# Memory Buffers

* `0xa06`: Location of current room data (starts at `0x91b`)
* `0x91b`: Starting room information ("Foothills")
    * byte 0: room title address location
    * byte 1: room description address location
    * byte 2: room exit information
        * byte 0: count of exits
        * byte 1: length of exit 1
        * byte 2-n: exit 1 name
* `0x2C7D`: Keyboard buffer for user input, length `0x20`        
* `0x2C9F`: "The self-test completion code is: tkIVVI\n\n"
* `0x2DC4`: dictionary of items?
   * byte 0: address of name string
   * byte 1: 
   * byte 3: room address
* `0x2E09`: "Things of interest here:"