Tests
=====

.htaccess exists in the root of tests so the scripts are not accessible from the browser; do not even install the tests in a public web folder.

first copy the default config to the root of test and then configure for your system:
>cp config/config.php/default config.php

find where php is installed:
>which php

run these tests using the command line:
>/usr/bin/php <php script>

