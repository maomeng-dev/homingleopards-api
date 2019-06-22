<?php
require 'flight/Flight.php';
require __DIR__ . '/vendor/autoload.php';

define("ROOT_PATH", __DIR__);
define("VIEW_PATH", __DIR__ . "/layout/view");

define("DEV", strpos($_SERVER['SERVER_NAME'], 'dev.') !== false);

define("INC_FILE", DEV ? "maomeng_dev" : "maomeng");

Flight::set('flight.views.path', VIEW_PATH);

ini_set('session.use_cookies', 1);
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', 'homingleopards.org');

require __DIR__ . "/public/function.php";

Flight::route(
    '/',
    function() {
        echo "<pre>";
        echo "                               +---------------------------------------------------+\r\n
                               |                                                   |\r\n
                               |                                                   |\r\n
                    homing     |                                                   |\r\n
              +--------------->+                     BeiJing                       |\r\n
              |                |                                                   |\r\n
              |                |                                                   |\r\n
              |                |                                  CFCA  2017-2019  |\r\n
              |                +---------------------------------------------------+\r\n
              |\r\n
              |\r\n
              |\r\n
              |\r\n
              |\r\n
+-------------+----------------+\r\n
|                              |\r\n
|                              |\r\n
|           Leopards           |\r\n
|                              |\r\n
|                              |\r\n
+------------------------------+\r\n
";
        echo "</pre>";
    }
);

Flight::route(
    '/test/list/',
    function() {
        $app = getWechatApp();
        $material = $app->material;

        $lists = $material->list('news', 0, 10);
        var_dump($lists);

    }
);

Flight::route(
    '/test/db/',
    function() {
        $db = Flight::Db();
        $result = $db->select('user_list', '*', []);
        var_dump($result);
    }
);

Flight::route(
    '/test/testuser/',
    function() {
        $user = new \App\Model\User();
        $user->add([
            'user_name' => 'leofm365',
            'user_pass' => md5("abcdef"),
            'nickname' => '风子夕',
            'cre_time' => date("Y-m-d H:i:s"),
            'last_login_time' => date("Y-m-d H:i:s"),
            'is_super_user' => 1,
            'last_login_ip' => '127.0.0.1',
            'create_by' => 'admin',
        ]);
    }
);

Flight::route(
    '/test/user/login/',
    function() {

        \App\Api\Backend\BootStrap::init();
        $user = new \App\Model\User();
        var_dump($user->login('leofm365', 'abcdef'));
    }
);

Flight::route(
    '/test/user/current/',
    function() {
        \App\Api\Backend\BootStrap::init();
        $user = new \App\Model\User();
        var_dump($user->currentUser());
    }
);

Flight::route(
    '/test/cookie',
    function() {
        setcookie('h_token', '1', time() + 86400, '/', 'cf.homingleopards.org');
        echo 'ok';

    }
);

Flight::route(
    '/test/content',
    function() {
        $id = Flight::request()->query->id;

        $app = getWechatApp();
        $material = $app->material;
        $content = $material->get($id);
        var_dump($content);

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
