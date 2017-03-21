# Site Tester
PHP CLI script to test bits of a website

## Features
Currently this is still in development, and only some CSS tests are included:

* Filesize  check (default limit set to 50 KiB or 50 * 1024 B)
* !important count
* @extend count
* @import count
* Styling by ID check
* Selector depth check (default depth limit is hard-coded to 3)
* Use of `background` shorthand property
* Use of the * key selector (default limit is hard-coded to 1)
* Chained class selector check
* Duplicate selectors check