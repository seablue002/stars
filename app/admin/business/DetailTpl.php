<?php
declare(strict_types=1);

namespace app\admin\business;

use app\common\model\mysql\NewsTemp AS TplModel;
use think\Exception;
use think\exception\ValidateException;
use app\admin\validate\Tpl AS TplValidate;

class DetailTpl
{
  private $tplModel = null;

  public function __construct()
  {
    $this->tplModel = new TplModel;
  }

  /**
   * 插入单条详情模板数据
   * @param $data 详情模板数据
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

    try {
      validate(TplValidate::class)
        ->scene('add')
        ->check($tpl_data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    return $this->tplModel->insertOneData($tpl_data);
  }

  public function getTplListWithPage($params)
  {
    $where = [];
    if ($params['name']) {
      $where[] = ['name', 'LIKE', "%{$params['name']}%"];
    }
    if ($params['category_id']) {
      $where[] = ['category_id', '=', $params['category_id']];
    }
    $page_size = $params['page_size'];

    $list = $this->tplModel->getPageList($where, '*', $page_size);

    return $list;
  }

  public function getTplBaseInfoList()
  {
    return $this->tplModel->getList(['id', 'name']);
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
    try {
      validate(TplValidate::class)
        ->scene('edit')
        ->check($data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    return $this->tplModel->update($data);
  }

  public function getTplDetail ($id) {
    try {
      validate(TplValidate::class)
        ->scene('detail')
        ->check(['id' => $id]);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    return $this
      ->tplModel
      ->where(['id' => $id])
      ->find()
      ->toArray();
  }

}
