<?php

ini_set('display_errors', 'on');

require __DIR__ . '/vendor/autoload.php';

use App\App;

$conf = App::init();

define('BASE', $conf['web']);
define('ROOT', $conf['root']);
define('DSN', $conf['driver']);
define('USR', $conf['dbuser']);
define('PWD', $conf['dbpass']);

App::run();