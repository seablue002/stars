<?php
declare(strict_types=1);

namespace app\admin\validate;


use think\Validate;

class Info extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'title' => 'require',
    'content' => 'require',
    'column_id' => 'require|number',
    'create_time' => 'require',
    'update_time' => 'require'
  ];

  protected $message = [
    'id' => '信息id必须',
    'id.number' => '信息id必须为数字',
    'title' => '标题必须',
    'content' => '内容必须',
    'column_id' => '栏目id必须',
    'column_id.number' => '栏目id必须为数字',
    'create_time' => '信息添加时间必须',
    'update_time' => '信息更新时间必须'
  ];

  protected $scene = [
    'add' => ['title', 'column_id', 'create_time'],
    'edit' => ['id', 'title', 'column_id', 'update_time'],
    'detail' => ['id']
  ];
}