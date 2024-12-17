<?php

declare(strict_types=1);

namespace app\admin\validate;


use think\Validate;

class Tpl extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'name' => 'require',
    'content' => 'require',
    'create_time' => 'require',
    'update_time' => 'require'
  ];

  protected $message = [
    'id' => '信息id必须',
    'id.number' => '信息id必须为数字',
    'name' => '名称必须',
    'content' => '内容必须',
    'category_id' => '分类id必须',
    'category_id.number' => '分类id必须为数字',
    'create_time' => '信息添加时间必须',
    'update_time' => '信息更新时间必须'
  ];

  protected $scene = [
    'add' => ['name', 'category_id', 'create_time'],
    'edit' => ['id', 'name', 'category_id', 'update_time'],
    'detail' => ['id']
  ];
}