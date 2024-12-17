<?php


namespace app\home\validate;


use think\Validate;

class Column extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'create_time' => 'require'
  ];

  protected $message = [
    'id' => 'id必须',
    'id.number' => 'id必须为数字',
    'create_time' => '更新时间必须'
  ];

  protected $scene = [
    'add' => ['create_time'],
    'update' => ['update_time'],
    'detail' => ['id'],
    'delete' => ['id']
  ];
}