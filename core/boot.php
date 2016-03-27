<?php

namespace app;

define('ROOT'  , $root = dirname(__DIR__));
define('CORE'  , "{$root}/core");
define('APP'   , "{$root}/app");
define('LIB'   , "{$root}/lib");
define('TMP'   , "{$root}/tmp");
define('WEB'   , "{$root}/web");
define('ASSET' , "{$root}/web/asset");
define('VENDOR', "{$root}/vendor");

require CORE . '/func.php';
require CORE . '/app/Loader.php';

Loader::includePath(CORE, APP, LIB);
Loader::register();
Logger::register();
Exception::register();
//Error::registor();
//Database::register();

//ob_start();
