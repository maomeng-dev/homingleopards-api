<?php
/**
 * Created by PhpStorm.
 * User: fengzixi
 * Date: 2018/1/21
 * Time: 上午10:56
 */

namespace App\Route;

use App\Api\Front\Download;

/**
 * Router of Api.
 * Class Api
 * @package App\Route
 */

final class Api
{
    public static function router()
    {
        \Flight::route('/front/download/@act', function ($act) {
            $controller = new Download();
            if ($act == 'list') {
                $controller->showlist();
            }
        });

        \Flight::route('/api/backend/@con/@act', function ($con, $act) {
            $className = "\App\Api\Backend\Controller\\" . ucfirst($con);
            if(!class_exists($className))
            {
                exit('入口' . $con . "不存在");
            }
            $controller = new $className;
            if(!method_exists($controller, $act))
            {
                exit('方法' . $act . "不存在");
            }
            $controller->$act();
        });
    }
}