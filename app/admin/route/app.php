<?php

use think\facade\Route;

// 免jwt校验的路由组
Route::group(function() {
  // 获取图片验证码
  Route::get('verify-img', 'admin/verify/index');

  // 普通登录(账号密码方式登录)
  Route::post('login-in', 'admin/login/loginIn');

  // 获取系统配置项数据
  Route::get('system-config', 'admin/systemConfig/getSystemConfig');

});

// 需要进行jwt
Route::group(function() {
  // 退出登录
  Route::post('login-out', 'admin/login/loginOut');

  // 信息列表
  Route::get('info-list', 'admin/info/list');
  // 添加信息
  Route::post('add-info', 'admin/info/add');
  // 信息详情
  Route::get('info-detail', 'admin/info/detail');
  // 编辑信息
  Route::post('edit-info', 'admin/info/edit');
  // 删除信息
  Route::post('delete-info', 'admin/info/delete');
  // 根据pid获取信息列表
  Route::get('info-list-by-pid', 'admin/info/getListByPid');
  // 信息详情内容图片上传
  Route::post('upload-info-content-pic', 'admin/info/infoContentPicUpload');
  // 删除cover封面
  Route::get('delete-cover', 'admin/info/deleteCover');
  // 删除资源（栏目数据模型为图片、下载时的图片、文件等资源删除）
  Route::post('delete-resource', 'admin/info/deleteResource');

  // 标签列表
  Route::get('label-list', 'admin/label/list');
  // 标签列表（基本信息）
  Route::get('label-base-info-list', 'admin/label/baseList');
  // 添加标签
  Route::post('add-label', 'admin/label/add');
  // 标签详情
  Route::get('label-detail', 'admin/label/detail');
  // 编辑标签
  Route::post('edit-label', 'admin/label/edit');
  // 删除标签
  Route::post('delete-label', 'admin/label/delete');

  // 栏目列表
  Route::get('column-list', 'admin/column/list');
  // 栏目列表（基本信息）
//  Route::get('column-base-info-list', 'admin/column/baseList');
  // 添加栏目
  Route::post('add-column', 'admin/column/add');
  // 栏目详情
  Route::get('column-detail', 'admin/column/detail');
  // 编辑栏目
  Route::post('edit-column', 'admin/column/edit');
  // 删除栏目
  Route::get('delete-column', 'admin/column/delete');
  // 删除栏目cover封面
  Route::get('delete-column-cover', 'admin/column/deleteCover');

  // 栏目自定义字段配置列表（有分页）
  Route::get('column-extend-fields-config-list', 'admin/columnExtendFieldsConfig/list');
  // 栏目自定义字段配置列表（无分页）
  Route::get('column-extend-fields-config-all-list', 'admin/columnExtendFieldsConfig/allList');
  // 添加栏目自定义字段配置
  Route::post('add-column-extend-fields-config', 'admin/columnExtendFieldsConfig/add');
  // 栏目自定义字段配置详情
  Route::get('column-extend-fields-config-detail', 'admin/columnExtendFieldsConfig/detail');
  // 编辑栏目自定义字段配置
  Route::post('edit-column-extend-fields-config', 'admin/columnExtendFieldsConfig/edit');
  // 保存配置设置
  Route::post('setting-save-column-extend-fields-config', 'admin/columnExtendFieldsConfig/settingSave');

  // 列表模板列表
  Route::get('list-tpl-list', 'admin/listTpl/list');
  // 列表模板列表（基本信息）
  Route::get('list-tpl-base-info-list', 'admin/listTpl/baseList');
  // 添加列表模板
  Route::post('add-list-tpl', 'admin/listTpl/add');
  // 列表模板详情
  Route::get('list-tpl-detail', 'admin/listTpl/detail');
  // 编辑列表模板
  Route::post('edit-list-tpl', 'admin/listTpl/edit');
  // 删除列表模板
  Route::get('delete-list-tpl', 'admin/listTpl/delete');
  // 根据pid获取列表模板列表
  Route::get('list-tpl-list-by-cid', 'admin/listTpl/getListByCid');

  // 模板内容图片上传
  Route::post('upload-tpl-content-pic', 'admin/listTpl/tplContentPicUpload');

  // 详情模板列表
  Route::get('detail-tpl-list', 'admin/detailTpl/list');
  // 详情模板列表（基本信息）
  Route::get('detail-tpl-base-info-list', 'admin/detailTpl/baseList');
  // 添加详情模板
  Route::post('add-detail-tpl', 'admin/detailTpl/add');
  // 详情模板详情
  Route::get('detail-tpl-detail', 'admin/detailTpl/detail');
  // 编辑详情模板
  Route::post('edit-detail-tpl', 'admin/detailTpl/edit');
  // 删除详情模板
  Route::get('delete-detail-tpl', 'admin/detailTpl/delete');
  // 根据pid获取详情模板列表
  Route::get('detail-tpl-list-by-cid', 'admin/detailTpl/getListByCid');

  // 模板变量列表
  Route::get('tpl-var-list', 'admin/tplVar/list');
  // 添加模板变量
    Route::post('add-tpl-var', 'admin/tplVar/add');
  // 模板变量详情
    Route::get('tpl-var-detail', 'admin/tplVar/detail');
  // 编辑模板变量
    Route::post('edit-tpl-var', 'admin/tplVar/edit');
  // 删除模板变量
    Route::get('delete-tpl-var', 'admin/tplVar/delete');

  // 后台用户列表
  Route::get('admin-user-list', 'admin/adminUser/list');
  // 添加后台用户
  Route::post('add-admin-user', 'admin/adminUser/add');
  // 后台用户详情
  Route::get('admin-user-detail', 'admin/adminUser/detail');
  // 编辑后台用户
  Route::post('edit-admin-user', 'admin/adminUser/edit');

  // 权限角色基本信息列表
  Route::get('auth-role-base-info-list', 'admin/authRole/baseList');
  // 权限角色列表
  Route::get('auth-role-list', 'admin/authRole/list');
  // 添加权限角色
  Route::post('add-auth-role', 'admin/authRole/add');
  // 权限角色详情
  Route::get('auth-role-detail', 'admin/authRole/detail');
  // 编辑权限角色
  Route::post('edit-auth-role', 'admin/authRole/edit');

  // 权限规则列表
  Route::get('auth-rule-list', 'admin/authRule/list');
  // 权限规则基本信息列表
  Route::get('auth-rule-base-info-list', 'admin/authRule/baseList');
  // 权限规则详情
  Route::get('auth-rule-detail', 'admin/authRule/detail');
  // 权限规则添加
  Route::post('add-auth-rule', 'admin/authRule/add');
  // 权限规则编辑
  Route::post('edit-auth-rule', 'admin/authRule/edit');

  // 系统配置分类列表
  Route::get('system-config-category-list', 'admin/systemConfigCategory/list');
  // 系统配置分类列表（基本信息）
//  Route::get('system-config-category-base-info-list', 'admin/systemConfigCategory/baseList');
  // 添加系统配置分类
  Route::post('add-system-config-category', 'admin/systemConfigCategory/add');
  // 系统配置分类详情
  Route::get('system-config-category-detail', 'admin/systemConfigCategory/detail');
  // 编辑系统配置分类
  Route::post('edit-system-config-category', 'admin/systemConfigCategory/edit');
  // 删除系统配置分类
 Route::get('delete-system-config-category', 'admin/systemConfigCategory/delete');
  // 根据rid获取系统配置分类列表（主要用于生成动态配置表单tab菜单）
  Route::get('system-config-category-list-by-rid', 'admin/systemConfigCategory/getListByRid');

  // 系统配置列表
  Route::get('system-config-list', 'admin/systemConfig/list');
  // 添加系统配置
  Route::post('add-system-config', 'admin/systemConfig/add');
  // 系统配置详情
  Route::get('system-config-detail', 'admin/systemConfig/detail');
  // 编辑系统配置
  Route::post('edit-system-config', 'admin/systemConfig/edit');
  // 删除系统配置
  Route::get('delete-system-config', 'admin/systemConfig/delete');
  // 根据cid获取系统配置列表
  Route::get('system-config-list-by-cid', 'admin/systemConfig/getListByCid');
  // 保存配置设置
  Route::post('setting-save-system-config', 'admin/systemConfig/settingSave');

  // 获取系统信息
  Route::get('system-info', 'admin/system/info');


  // 轮播列表
  Route::get('slider-list', 'admin/Slider/list');
  // 添加轮播
  Route::post('add-slider', 'admin/Slider/add');
  // 轮播详情
  Route::get('slider-detail', 'admin/Slider/detail');
  // 编辑轮播
  Route::post('edit-slider', 'admin/Slider/edit');
  // 删除轮播
  Route::get('delete-slider', 'admin/Slider/delete');


  // 省市区
  Route::get('region-list','admin/system/getRegionList');

  // 系统相关
  // 获取字典列表
  Route::get('dic-list', 'admin/system/getDicList');

})->middleware(['jwt']);