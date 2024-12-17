<?php


namespace app\admin\validate;


use think\Validate;

class AuthRoleRule extends Validate
{
  protected $rule = [
    'role_id' => 'require|number',
    'rule_id' => 'require|number'
  ];
  
  protected $message = [
    'role_id' => '角色id必须',
    'role_id.number' => '角色id必须为数字',
    'rule_id' => '规则id必须',
    'rule_id.number' => '规则id必须为数字'
  ];
  
  protected $scene = [
    'add' => ['role_id', 'rule_id']
  ];
  
}