Logs
====

Web app for viewing logs in configured directories using the format in the associated library.

The web app itself outputs logs in the same format, and those logs can be viewed using this same app, but you will cause a race condition that affects log viewing.

.htaccess in the app root removes access to directory listing for folders without an index.php file.
.htaccess exists in the log and config directories to deny access to those files from the browser.

first copy the sample config and then configure for your system:
>cd config
>cp config.php.sample config.php
>nano config.php

next create the log files in the configured log directory (this is necessary when the web server user does not have file create permissions):
>touch error.log
>touch debug.log

error messages will be appended to /var/log/php.log until the app's log files are created and given 666 permissions (so www-data user can write).

