Logs
====

Web app for viewing logs in configured directories using the format in the associated library.

The web app itself outputs logs in the same format, and those logs can be viewed using this same app, but you will cause a race condition that affects log viewing.

.htaccess in the app root removes access to directory listing for folders without an index.php file.
.htaccess exists in the log and config directories to deny access to those files from the browser.

first copy the default config to the root of test and then configure for your system:
>cp config/config.php/default config.php

