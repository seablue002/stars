<?php
declare(strict_types=1);

namespace app\admin\business;

use app\common\model\mysql\Column AS ColumnModel;
use think\Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use app\common\exception\ApiException;
use app\common\lib\Utils;
use app\admin\validate\Column AS ColumnValidate;
use app\admin\business\Info AS InfoBusiness;
use think\facade\Db;
use app\admin\business\ColumnExtendFields AS ColumnExtendFieldsBusiness;

class Column
{
  private $columnModel = null;
  private $utils = null;

  public function __construct()
  {
    $this->columnModel = new ColumnModel;
    $this->utils = new Utils;
  }

  /**
   * 插入单条栏目数据
   * @param $data 栏目数据
   * @return int|string
   * @throws Exception
   */
  public function insertColumn($data)
  {
    if (is_array($data['pid'])) {
      $pid_len = count($data['pid']);
      if ($pid_len > 1) {
        $data['rid'] = intval($data['pid'][1]);
      } else if ($pid_len === 1) {
        $data['rid'] = intval($data['pid'][0]);
      }
      $data['pid'] = intval($data['pid'][$pid_len - 1]);
    }

    if ($data['parent_dir_path'] && !preg_match('/\/$/', $data['parent_dir_path'])) {
      $data['parent_dir_path'] .= '/';
    }

    $data['create_time'] = time();
    
    validate(ColumnValidate::class)
        ->scene('add')
        ->check($data);

    $add_id = $this->columnModel->insertOneData($data);

    if ($add_id) {
      // 如果添加成功，判断是否存在当前栏目名的目录
      $dir_name = 'tpl_catche/home/' . $data['parent_dir_path'] . $data['column_dir_path'];
      if (!file_exists($dir_name)) {
        mkdir($dir_name, 0777, true);
      }
    }

    return $add_id;
  }

  /**
   * 获取栏目基本信息(id, name, pid)数据列表
   * @return array
   * @throws DataNotFoundException
   * @throws DbException
   * @throws ModelNotFoundException
   */
  public function getColumnBaseInfoList()
  {
    $column_list = $this->getColumnInfoList(['id', 'name', 'pid']);
    $temp_column_list = [];
    $this->utils->getChildrenCategory($temp_column_list, $column_list, 0);
    return $temp_column_list;
  }

  public function getColumnNormalInfoList()
  {
    $column_list = $this->columnModel->getColumnNormalInfoList();
    $temp_column_list = [];
    $this->utils->getChildrenCategory($temp_column_list, $column_list, 0);

    return $temp_column_list;
  }

  public function getListByRid($params)
  {
    $where = [];
    if ($params['rid']) {
      $where[] = ['rid', '=', $params['rid']];
    }
    $column_list = $this->columnModel->where($where)->order('sort ASC')->select();

    return $column_list;
  }

  /**
   * 获取栏目特定字段数据列表
   * @param $fields 需要获取的数据字段
   * @return array
   * @throws DataNotFoundException
   * @throws DbException
   * @throws ModelNotFoundException
   */
  public function getColumnInfoList($fields)
  {
    return $this->columnModel->getColumnInfoList($fields);
  }

  public function updateColumn($data)
  {
    if (is_array($data['pid'])) {
      $pid_len = count($data['pid']);
      if ($pid_len > 1) {
        $data['rid'] = intval($data['pid'][1]);
      } else if ($pid_len === 1) {
        $data['rid'] = intval($data['pid'][0]);
      }
      $data['pid'] = intval($data['pid'][$pid_len - 1]);
    }

    if ($data['parent_dir_path'] && !preg_match('/\/$/', $data['parent_dir_path'])) {
      $data['parent_dir_path'] .= '/';
    }

    $data = array_merge($data, ['update_time' => time()]);
    
    validate(ColumnValidate::class)
        ->scene('edit')
        ->check($data);

    $updated_row = $this->columnModel->update($data);
    if ($updated_row) {
      // 如果更新成功，判断是否存在当前栏目名的目录
      $dir_name = 'tpl_catche/home/' . $data['parent_dir_path'] . $data['column_dir_path'];
      if (!file_exists($dir_name)) {
        mkdir($dir_name, 0777, true);
      }
    }
    return $updated_row;
  }

  public function getColumnDetail ($id) {
    validate(ColumnValidate::class)
        ->scene('detail')
        ->check(['id' => $id]);
    
    return $this
      ->columnModel
      ->alias('c')
      ->leftJoin('column_extend_fields cef', 'c.id = cef.column_id')
      ->where(['c.id' => $id])
      ->find()
      ->toArray();
  }

  public function delete($data)
  {
    validate(ColumnValidate::class)
      ->scene('delete')
      ->check($data);

    $columnId = $data['id'];
    // 查询下面是否有子栏目，有则不可以删除
    $subColumn = $this
    ->columnModel->getFields(['pid' => $columnId], 'id');

    if (!empty($subColumn)) {
      throw new ApiException('栏目下有子栏目，不可以直接删除');
    }

    // 查询栏目下是否有内容，有则不可以删除
    $info = app()->make(InfoBusiness::class)->infoModel->getFields(['column_id' => $columnId], 'id');

    if (!empty($info)) {
      throw new ApiException('栏目下有内容，不可以直接删除');
    }

    $column = $this->columnModel->getOne(['id' => $columnId], 'parent_dir_path, column_dir_path');

    try {
      Db::startTrans();
      $this->columnModel->where([
        'id' => $columnId
      ])->delete();

      app()->make(ColumnExtendFieldsBusiness::class)->columnExtendFieldsModel->where([
        'column_id' => $columnId
      ])->delete();

      // 删除栏目对应缓存目录
      $dirName = 'tpl_catche/home/' . $column['parent_dir_path'] . $column['column_dir_path'];
      if (file_exists($dirName)) {
        rmdir($dirName);
      }

      Db::commit();
    } catch (\Throwable $e) {
      Db::rollback();
      throw $e;
    }
  }

  public function deleteCover ($column_id) {
    $column = $this->columnModel->where(['id' => $column_id])->field('cover_url')->find();
    if (!empty($column)) {
      $updated_row = $this->columnModel->where(['id' => $column_id])->update(['cover_url' => '']);
      if ($updated_row === 1) {
        // 删除磁盘上的图片资源
        $cover_path = root_path() . 'public\\storage\\' . $column['cover_url'];
        unlink($cover_path);
      }
    }
  }
}
