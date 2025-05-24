<?php
declare(strict_types=1);

namespace app\admin\business;

use app\common\model\mysql\Label AS LabelModel;
use app\common\model\mysql\News AS InfoModel;
use think\Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use app\common\exception\ApiException;
use app\common\lib\Utils;
use app\admin\validate\Label AS LabelValidate;

class Label
{
  private $labelModel = null;
  private $utils = null;

  public function __construct()
  {
    $this->labelModel = new LabelModel;
    $this->utils = new Utils;
  }

  /**
   * 插入单条标签数据
   * @param $data 标签数据
   * @return int|string
   * @throws Exception
   */
  public function insertLabel($data)
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

    $data['create_time'] = time();

    validate(LabelValidate::class)
      ->scene('add')
      ->check($data);

    $add_id = $this->labelModel->insertOneData($data);

    return $add_id;
  }

  /**
   * 获取标签基本信息(id, name, pid)数据列表
   * @return array
   * @throws DataNotFoundException
   * @throws DbException
   * @throws ModelNotFoundException
   */
  public function getLabelBaseInfoList()
  {
    $label_list = $this->getLabelInfoList(['id', 'name', 'pid']);
    $temp_label_list = [];
    $this->utils->getChildrenCategory($temp_label_list, $label_list, 0);
    return $temp_label_list;
  }

  public function getLabelNormalInfoList()
  {
    $label_list = $this->labelModel->getLabelNormalInfoList();
    $temp_label_list = [];
    $this->utils->getChildrenCategory($temp_label_list, $label_list, 0);

    return $temp_label_list;
  }

  public function getListByRid($params)
  {
    $where = [];
    if ($params['rid']) {
      $where[] = ['rid', '=', $params['rid']];
    }
    $label_list = $this->labelModel->where($where)->order('sort ASC')->select();

    return $label_list;
  }

  /**
   * 获取标签特定字段数据列表
   * @param $fields 需要获取的数据字段
   * @return array
   * @throws DataNotFoundException
   * @throws DbException
   * @throws ModelNotFoundException
   */
  public function getLabelInfoList($fields)
  {
    return $this->labelModel->getLabelInfoList($fields);
  }

  public function updateLabel($data)
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

    $data = array_merge($data, ['update_time' => time()]);

    validate(LabelValidate::class)
      ->scene('edit')
      ->check($data);

    $updated_row = $this->labelModel->update($data);

    return $updated_row;
  }

  public function getLabelDetail ($id) {
    validate(LabelValidate::class)
      ->scene('detail')
      ->check(['id' => $id]);

    return $this
      ->labelModel
      ->alias('c')
      ->where(['c.id' => $id])
      ->find()
      ->toArray();
  }

  public function deleteLabel($data)
  {
    validate(LabelValidate::class)
      ->scene('delete')
      ->check($data);

    // 查询是否已经和信息关联
    $isExistWhere = [
      ['', 'exp', \think\facade\Db::raw("FIND_IN_SET('{$data['id']}', label_ids)")]
    ];

    $existCount = app()->make(InfoModel::class)->where($isExistWhere)->count();
    
    if ($existCount > 0) {
      throw new ApiException('该标签已经和信息关联，无法删除');
    }

    $where = [
      'id' => $data['id']
    ];

    return $this->labelModel->where($where)->delete();
  }
}
