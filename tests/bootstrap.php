<?php

require dirname(__DIR__) . '/autoload.php';

// recover initial database
copy(dirname(__DIR__) . '/var/db/todo_test.dist.sqlite3', dirname(__DIR__) . '/var/db/todo_test.sqlite3');
