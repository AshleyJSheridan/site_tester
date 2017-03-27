# Site Tester
PHP CLI script to test bits of a website

## Features
Currently this is still in development, and some CSS & HTML tests are included:

### CSS Tests
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
* Count of colours used (default threshold set to 10)

### HTML Tests
* Doctype check
* Check for title, header, and nav tags
* Navigation check by tag or role
* Header hierarchy check
* Test number of includes CSS files against threshold (default limit is 2)
* Test number of includes JS files against threshold (default limit is 3)
