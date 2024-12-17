<?php
declare(strict_types=1);

namespace app\admin\validate;


use think\Validate;

class AdminUser extends Validate
{
  protected $rule = [
    'username' => 'require',
    'password' => 'require',
    'status' => 'require|number',
    'captcha' => 'require',
  ];
  
  protected $message = [
    'username' => '用户名必须',
    'password' => '密码必须',
    'status' => '账号状态必须',
    'status.number' => '账号状态必须为数字',
    'captcha' => '验证码必须',
  ];
  
  protected $scene = [
    'login' => ['username', 'password', 'captcha'],
    'add' => ['username', 'password', 'role_id', 'status'],
    'edit' => ['username', 'role_id', 'status']
  ];
}