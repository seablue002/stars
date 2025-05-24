<?php
declare(strict_types=1);

namespace app\admin\business;

use app\common\model\mysql\PublicTempVar AS TplModel;
use think\Exception;
use app\admin\validate\TplVar AS TplValidate;

class TplVar
{
  private $tplModel = null;

  public function __construct()
  {
    $this->tplModel = new TplModel;
  }

  /**
   * 插入单条模板变量数据
   * @param $data 模板变量数据
   * @return int|string
   * @throws Exception
   */
  public function insertTpl($data)
  {
    if ($data['category_id']) {
      $category_id_len = count($data['category_id']);

      if ($category_id_len) {
        $data['category_id'] = $data['category_id'][$category_id_len - 1];
      }
    }

    $tpl_data = array_merge($data, [
      'create_time' => time()
    ]);

    validate(TplValidate::class)
      ->scene('add')
      ->check($tpl_data);

    return $this->tplModel->insertOneData($tpl_data);
  }

  public function getTplListWithPage($params)
  {
    $where = [];
    if ($params['var_key']) {
      $where[] = ['var_key', 'LIKE', "%{$params['var_key']}%"];
    }

    if ($params['var_name']) {
      $where[] = ['var_name', 'LIKE', "%{$params['var_name']}%"];
    }

    if (count($params['create_time']) > 0) {
      $where[] = ['create_time', 'BETWEEN', [strtotime($params['create_time'][0]), strtotime($params['create_time'][1])]];
    }

    if ($params['category_id']) {
      $where[] = ['category_id', '=', $params['category_id']];
    }
    $page_size = $params['page_size'];

    $list = $this->tplModel->getPageList($where, '*', $page_size);

    return $list;
  }

  public function updateTpl($data)
  {
    if ($data['category_id']) {
      $category_id_len = count($data['category_id']);

      if ($category_id_len) {
        $data['category_id'] = $data['category_id'][$category_id_len - 1];
      }
    }
    $data = array_merge($data, ['update_time' => time()]);

    validate(TplValidate::class)
      ->scene('edit')
      ->check($data);

    return $this->tplModel->update($data);
  }

  public function getTplDetail ($id) {
    validate(TplValidate::class)
      ->scene('detail')
      ->check(['id' => $id]);

    return $this
      ->tplModel
      ->where(['id' => $id])
      ->find()
      ->toArray();
  }

}
