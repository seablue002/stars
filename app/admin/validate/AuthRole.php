<?php


namespace app\admin\validate;


use think\Validate;

class AuthRole extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'title' => 'require',
    'status' => 'require|number'
  ];
  
  protected $message = [
    'id' => 'id必须为数字',
    'id.number' => 'id必须',
    'title' => '角色名称必须',
    'status' => '角色状态必须',
    'status.number' => '角色状态必须为数字',
  ];
  protected $scene = [
    'add' => ['title', 'status'],
    'edit' => ['id', 'title', 'status']
  ];
}