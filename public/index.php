<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;
// 暂时支持options请求，解决前台请求跨域问题，tp6自带跨域中间件的bug，后续需要升级tp6版本！！！！！！！！！！！
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
  header("Access-Control-Allow-Origin:*");
  header("Access-Control-Allow-Headers: Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With");
  header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
  exit;
}
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
