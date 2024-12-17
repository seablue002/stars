<?php
// +----------------------------------------------------------------------
// | jwt认证设置
// +----------------------------------------------------------------------

declare(strict_types=1);

// 保存jwt验证与redis存储的用户信息有效期 30天
$expire = 86400 * 30;
return [
  'alg'                => 'HS256',
  'secret'             => 'sl777',
  'exp'                => $expire,
  // 用户jwt相关配置
  'redis_user_exp'    => $expire,
  'redis_user_prefix' => 'user-',
  // 管理员jwt相关配置
  'redis_admin_user_exp'    => $expire,
  'redis_admin_user_prefix' => 'admin-user-',
];
