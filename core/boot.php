<?php

namespace app;

define('ROOT' , $root = dirname(__DIR__));
define('TMP'  , "{$root}/tmp");
define('WEB'  , "{$root}/web");
define('ASSET', "{$root}/web/asset");

require "{$root}/core/func.php";
require "{$root}/core/app/Loader.php";

Loader::includePath("{$root}/core", "{$root}/app", "{$root}/lib");
Loader::register();
Logger::register();
Exception::register();
//Error::registor();
//Database::register();

//ob_start();
