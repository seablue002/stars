<?php
declare (strict_types = 1);

namespace app;

use think\Service;
use app\common\lib\ResponseMessage;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public $bind = [
      'response' => ResponseMessage::class,
    ];
    public function register()
    {
        // 服务注册
    }

    public function boot()
    {
        // 服务启动
    }
}
