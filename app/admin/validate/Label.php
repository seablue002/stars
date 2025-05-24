<?php
declare(strict_types=1);

namespace app\admin\validate;


use think\Validate;

class Label extends Validate
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
    'id' => '栏目id必须',
    'id.number' => '栏目id必须为数字',
    'name' => '栏目名称必须',
    'pid' => '栏目上级id必须',
    'pid.number' => '栏目上级id必须为数字',
    'rid' => '根栏目id必须',
    'rid.number' => '根栏目id必须为数字',
    'create_time' => '栏目添加时间必须',
    'update_time' => '栏目更新时间必须'
  ];

  protected $scene = [
    'add' => ['name', 'pid', 'rid', 'create_time'],
    'edit' => ['id', 'name', 'pid', 'rid', 'update_time'],
    'detail' => ['id'],
    'delete' => ['id']
  ];
}