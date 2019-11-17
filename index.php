<?php

use FinanceService\components\Router;
// FRONT CONTROLLER

ini_set('display_errors', 'off');
ini_set('display_errors',0);
ini_set("session.use_trans_sid", true);
error_reporting(E_ALL);
session_start();

define('ROOT', dirname(__FILE__));
require_once (ROOT."/vendor/autoload.php");
$router = new Router();
$router->run();