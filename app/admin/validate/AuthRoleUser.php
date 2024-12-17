<?php


namespace app\admin\validate;


use think\Validate;

class AuthRoleUser extends Validate
{
  protected $rule = [
    'user_id' => 'require|number',
    'role_id' => 'require|number'
  ];
  
  protected $message = [
    'user_id' => '用户id必须',
    'user_id.number' => '用户id必须为数字',
    'role_id' => '角色id必须',
    'role_id.number' => '角色id必须为数字'
  ];
  
  protected $scene = [
    'add' => ['user_id', 'role_id']
  ];
}