<?php
chdir(dirname(__DIR__));
passthru('rm -rf var/tmp/*');
passthru('chmod 775 var/tmp');
passthru('chmod 775 var/log');

copy(dirname(__DIR__) . '/.env.dist', dirname(__DIR__) . '/.env');

chdir(dirname(__DIR__) . '/var/db');
passthru('sqlite3 todo.sqlite3 --init todo.sql');
