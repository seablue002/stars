<?php
declare(strict_types=1);

namespace app\admin\validate;


use think\Validate;

class columnExtendFieldsConfig extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'field' => 'require',
    'name' => 'require',
    'props' => 'require',
    'create_time' => 'require',
    'update_time' => 'require'
  ];

  protected $message = [
    'id' => '配置id必须',
    'id.number' => '配置id必须为数字',
    'field' => '配置字段名称必须',
    'name' => '配置名称必须',
    'props' => '配置元素属性必须',
    'create_time' => '配置添加时间必须',
    'update_time' => '配置更新时间必须'
  ];

  protected $scene = [
    'add' => ['field', 'name', 'type', 'create_time'],
    'edit' => ['id', 'field', 'name', 'type', 'update_time'],
    'detail' => ['id']
  ];
}
