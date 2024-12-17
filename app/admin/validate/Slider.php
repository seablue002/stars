<?php


namespace app\admin\validate;


use think\Validate;

class Slider extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'url' => 'require',
    'create_time' => 'require',
    'update_time' => 'require'
  ];

  protected $message = [
    'id' => '轮播图id必须',
    'id.number' => '轮播图id必须为数字',
    'url' => '轮播图url必须',
    'create_time' => '轮播图添加时间必须',
    'update_time' => '轮播图更新时间必须'
  ];

  protected $scene = [
    'add' => ['url', 'name', 'create_time'],
    'update' => ['id', 'name', 'update_time'],
    'detail' => ['id'],
    'delete' => ['id']
  ];
}