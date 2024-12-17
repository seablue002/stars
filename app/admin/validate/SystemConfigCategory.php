<?php
declare(strict_types=1);

namespace app\admin\validate;


use think\Validate;

class SystemConfigCategory extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'name' => 'require',
    'pid' => 'require|number',
    'rid' => 'require|number',
    'create_time' => 'require',
    'update_time' => 'require'
  ];

  protected $message = [
    'id' => '系统配置分类id必须',
    'id.number' => '系统配置分类id必须为数字',
    'name' => '系统配置分类名称必须',
    'pid' => '系统配置分类上级id必须',
    'pid.number' => '系统配置分类上级id必须为数字',
    'rid' => '系统配置根分类id必须',
    'rid.number' => '系统配置根分类id必须为数字',
    'create_time' => '系统配置分类添加时间必须',
    'update_time' => '系统配置分类更新时间必须'
  ];

  protected $scene = [
    'add' => ['name', 'pid', 'rid', 'create_time'],
    'edit' => ['id', 'name', 'pid', 'update_time'],
    'detail' => ['id']
  ];
}
