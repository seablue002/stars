<?php
declare(strict_types=1);

namespace app\admin\validate;


use think\Validate;

class SystemConfig extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'cid' => 'require|number',
    'field' => 'require',
    'name' => 'require',
    'create_time' => 'require',
    'update_time' => 'require'
  ];

  protected $message = [
    'id' => '系统配置id必须',
    'id.number' => '系统配置id必须为数字',
    'cid' => '系统配置分类id必须',
    'cid.number' => '系统配置分类id必须为数字',
    'field' => '系统配置字段名称必须',
    'name' => '系统配置名称必须',
    'create_time' => '系统配置添加时间必须',
    'update_time' => '系统配置更新时间必须'
  ];

  protected $scene = [
    'add' => ['cid', 'field', 'name', 'create_time'],
    'edit' => ['id', 'cid', 'field', 'name', 'update_time'],
    'detail' => ['id'],
    'delete' => ['id']
  ];
}
