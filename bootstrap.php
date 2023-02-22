<?php

use Dotenv\Dotenv;

require_once(__DIR__ . '/vendor/autoload.php');

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('ROOT', $_ENV['ROOT']);

//only one time; register all services
use App\Database\Connection;
use App\Database\DB;
use App\Container;
use App\Registry;
use App\Session;
use App\Request;

Container::bind('config', require 'config.php');
Registry::bind('config', Container::get('config'));

Container::bind('Database', new DB(Connection::make(Registry::get('config')['db'])));
Registry::bind('Database', Container::get('Database'));

Container::bind('Request', new Request());
Registry::bind('Request', Container::get('Request'));

Container::bind('Session', new Session());
Registry::bind('Session', Container::get('Session'));

Container::bind('Session', new Session());
Registry::bind('Session', Container::get('Session'));

//Container::bind('Session', Registry::get('Session')->set("csrf-token", md5(uniqid(mt_rand(), true))));
