<?php
/**
 * Created by PhpStorm.
 * User: fengzixi
 * Date: 2018/1/21
 * Time: 下午12:06
 */

namespace App;

class BootStarp
{
    public static function start()
    {
        self::setHeader();
        self::initDb();
    }

    public static function setHeader()
    {
        if(!isset($_SERVER['HTTP_REFERER']))
        {
            return;
        }
        $referer = $_SERVER['HTTP_REFERER'];
        $res = explode('.org', $referer);
        if (empty($res)) {
            return;
        }
        $domain = $res[0] . ".org";
        if (strpos($domain, 'homingleopards.org')) {
            header("Access-Control-Allow-Origin:{$domain}");
        }
    }

    /**
     * init the db conn;
     */
    protected static function initDb()
    {
        \Flight::register(
            "Db",
            "Medoo\Medoo",
            array(
                ['database_type' => 'mysql',
                'database_name' => getConfig("db.dbname"),
                'server' => getConfig("db.host"),
                'username' => getConfig("db.user"),
                'password' => getConfig("db.pass")]
            ),
            function() {

            }
        );

        \Flight::register(
            "Capsule",
            "\Illuminate\Database\Capsule\Manager",
            array(),
            function($capsule) {
                $capsule->addConnection(
                    [

                        'driver' => 'mysql',

                        'host' => \Yaconf::get("maomeng.db.host"),

                        'database' => \Yaconf::get("maomeng.db.dbname"),

                        'username' => \Yaconf::get("maomeng.db.user"),

                        'password' => \Yaconf::get("maomeng.db.pass"),

                        'charset' => 'utf8mb4',

                        'collation' => 'utf8mb4_general_ci',

                        'prefix' => '',

                    ]
                );

                $capsule->bootEloquent();
            }
        );

    }
}