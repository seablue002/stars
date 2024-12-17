<?php


namespace app\admin\validate;


use think\Validate;

class SystemArticle extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'title' => 'require',
    'content' => 'require',
    'create_time' => 'require',
    'update_time' => 'require'
  ];

  protected $message = [
    'id' => '文章id必须',
    'id.number' => '文章id必须为数字',
    'title' => '文章标题必须',
    'content' => '文章内容必须',
    'create_time' => '文章添加时间必须',
    'update_time' => '文章更新时间必须'
  ];

  protected $scene = [
    'update' => ['id', 'title', 'content', 'update_time'],
    'detail' => ['id']
  ];
}