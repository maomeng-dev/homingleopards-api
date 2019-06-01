<?php
/**
 * Created by PhpStorm.
 * User: fengzixi
 * Date: 2018/1/21
 * Time: 下午12:06
 */

namespace App;

use Medoo\Medoo;

class BootStarp
{
    public static function start()
    {
        self::initDb();
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
                'database_name' => \Yaconf::get("maomeng.db.dbname"),
                'server' => \Yaconf::get("maomeng.db.host"),
                'username' => \Yaconf::get("maomeng.db.user"),
                'password' => \Yaconf::get("maomeng.db.pass")]
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