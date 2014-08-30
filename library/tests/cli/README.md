Tests
=====

.htaccess exists in the root of tests so the scripts are not accessible from the browser; do not even install the tests in a public web folder.

first copy the sample config and then configure for your system:
>cd config
>cp config.php.sample config.php
>nano config.php

find where php is installed:
>which php

start by running the info.php script to see how php is configured:
>/usr/bin/php info.php

run tests using the command line:
>/usr/bin/php <php script>

