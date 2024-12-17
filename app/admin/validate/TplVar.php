<?php

declare(strict_types=1);

namespace app\admin\validate;


use think\Validate;

class TplVar extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'var_key' => 'require',
    'var_name' => 'require',
    'var_value' => 'require',
    'create_time' => 'require',
    'update_time' => 'require'
  ];

  protected $message = [
    'id' => '信息id必须',
    'id.number' => '信息id必须为数字',
    'var_key' => '变量名必须',
    'var_name' => '变量标识必须',
    'var_value' => '变量值必须',
    'category_id' => '分类id必须',
    'category_id.number' => '分类id必须为数字',
    'create_time' => '信息添加时间必须',
    'update_time' => '信息更新时间必须'
  ];

  protected $scene = [
    'add' => ['var_key', 'var_name', 'var_value', 'create_time'],
    'edit' => ['id', 'var_key', 'var_name', 'var_value', 'update_time'],
    'detail' => ['id']
  ];
}