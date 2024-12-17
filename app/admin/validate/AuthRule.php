<?php


namespace app\admin\validate;


use think\Validate;

class AuthRule extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'name' => 'require',
    'title' => 'require',
    'pid' => 'require|number',
    'menu' => 'require|number',
  ];
  
  protected $message = [
    'id' => 'id必须',
    'id.number' => 'id必须为数字',
    'name' => '规则必须',
    'title' => '规则名称必须',
    'pid' => '父级规则必须',
    'pid.number' => '父级规则必须为数字',
    'menu' => '菜单类型必须',
    'menu.number' => '菜单类型必须为数字',
  ];
  protected $scene = [
    'add' => ['name', 'title', 'pid', 'menu'],
    'edit' => ['id', 'name', 'title', 'pid', 'menu']
  ];
}