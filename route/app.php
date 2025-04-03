<?php
use think\facade\Route;

// 捕获所有未匹配的路由，并将其重定向到404页面
Route::miss(function () {
  return response('页面不存在', 404);
});