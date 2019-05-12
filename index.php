<?php
require 'flight/Flight.php';
require __DIR__ . '/vendor/autoload.php';

define("ROOT_PATH", __DIR__);
define("VIEW_PATH", __DIR__ . "/layout/view");

define("DEV", strpos($_SERVER['SERVER_NAME'], 'dev.') !== false);

Flight::set('flight.views.path', VIEW_PATH);

require __DIR__ . "/public/function.php";

Flight::route(
    '/',
    function() {
        echo "                               +---------------------------------------------------+
                               |                                                   |
                               |                                                   |
                               |                                                   |
                               |                                                   |
                    homing     |                                                   |
              +--------------->+                     BeiJing                       |
              |                |                                                   |
              |                |                                                   |
              |                |                                                   |
              |                |                                  CFCA  2017-2019  |
              |                |                                                   |
              |                +---------------------------------------------------+
              |
              |
              |
              |
              |
              |
              |
              |
              |
+-------------+----------------+
|                              |
|                              |
|                              |
|           Leopards           |
|                              |
|                              |
|                              |
+------------------------------+
";
    }
);

Flight::before(
    'start',
    function(&$params, &$output) {
        App\Route\Api::router();
        App\BootStarp::start();
    }
);

Flight::start();

?>
