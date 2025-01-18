<?php

use think\facade\Route;

// 免jwt校验的路由组
Route::group(function() {
  // 页面显示入口路由
  Route::get(':path', 'home/Index/index');
});
// 需要进行jwt校验的路由组
Route::group(function() {
  // 系统相关
  // 获取字典列表
  Route::get('dic-list', 'api/common/getDicList');

});
