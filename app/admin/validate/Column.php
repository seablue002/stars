<?php
declare(strict_types=1);

namespace app\admin\validate;


use think\Validate;

class Column extends Validate
{
  protected $rule = [
    'id' => 'require|number',
    'name' => 'require',
    'pid' => 'require|number',
    'rid' => 'require|number',
    'column_dir_path' => 'require',
    'model_tb_name' => 'require',
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
    'column_dir_path' => '本栏目目录必须',
    'model_tb_name' => '本栏目绑定的数据模型必须',
    'create_time' => '栏目添加时间必须',
    'update_time' => '栏目更新时间必须'
  ];

  protected $scene = [
    'add' => ['name', 'pid', 'rid', 'column_dir_path', 'model_tb_name', 'create_time'],
    'edit' => ['id', 'name', 'pid', 'rid', 'column_dir_path', 'model_tb_name', 'update_time'],
    'detail' => ['id'],
    'delete' => ['id'],
    'delete-cache' => ['id'],
  ];
}