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
    'detail' => ['id'],
    'delete' => ['id'],
    'delete-cover' => ['id', 'column_id'],
  ];

  public function __construct()
  {
    parent::__construct();
    $validateName = (new \ReflectionClass($this))->getShortName();
    if (in_array($validateName, ['Info'])) {
      // 追加校验规则
      $this->rule['resource_type'] = 'require';
      $this->rule['resource_url'] = 'require';

      $this->message['resource_type'] = '资源类型必须';
      $this->message['resource_url'] = '资源url必须';

      $this->scene['delete-resource'] = ['id', 'column_id', 'resource_type', 'resource_url'];
    }
  }
}