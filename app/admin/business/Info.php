<?php
declare(strict_types=1);

namespace app\admin\business;

use app\common\model\mysql\News AS InfoModel;
use app\admin\business\Column as ColumnBusiness;
use think\Exception;
use think\exception\ValidateException;
use app\common\lib\Utils;
use app\admin\validate\Info AS InfoValidate;

class Info
{
  public $infoModel = null;
  private $utils = null;
  private $columnBusiness = null;

  public function __construct()
  {
    $this->infoModel = new InfoModel;
    $this->utils = new Utils;
    $this->columnBusiness = new ColumnBusiness;
  }

  public function getListByCid($params)
  {
    $where = [];
    if ($params['id']) {
      $where[] = ['id', '=', $params['id']];
    }
    $info_list = $this->infoModel->where($where)->order('sort ASC')->select();

    return $info_list;
  }

  /**
   * 插入单条信息数据
   * @param $data 信息数据
   * @return int|string
   * @throws Exception
   */
  public function insertInfo($data)
  {
    if ($data['column_id']) {
      $column_id_len = count($data['column_id']);

      if ($column_id_len) {
        $data['column_id'] = $data['column_id'][$column_id_len - 1];
      }
    }

    $column_data = array_merge($data, [
      'create_time' => time()
    ]);

    try {
      validate(InfoValidate::class)
        ->scene('add')
        ->check($column_data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    return $this->infoModel->insertOneData($column_data);
  }

  public function getInfoListWithPage($params)
  {
    $where = [];
    if ($params['title']) {
      $where[] = ['title', 'LIKE', "%{$params['title']}%"];
    }
    if ($params['column_id']) {
      $where[] = ['column_id', '=', $params['column_id']];
    }
    $page_size = $params['page_size'];

    if (!$params['column_id']) {
      return [
        'data' => [],
        'total' => 0
      ];
    }

    $nt_cms_model = $this->getColumnModelInfo($params['column_id']);

    $list = $nt_cms_model['model']->getPageList($where, '*', $page_size);

    return $list;
  }

  // 获取栏目、数据表对应模型
  protected function getColumnModelInfo ($column_id) {
    $column = $this->columnBusiness->getColumnDetail($column_id);

    if (empty($column)) {
      throw new Exception('栏目不存在');
    }

    // 从栏目数据中整理出对应的数据表模型
    $model_tb_name_words = explode('_', $column['model_tb_name']);
    $model_tb_name_words = array_map(function ($name_words) {
      return ucfirst($name_words);
    }, $model_tb_name_words);
    $model_tb_name = implode('', $model_tb_name_words);

    if (!$model_tb_name) {
      throw new Exception('数据模型不存在');
    }

    // 组装拼接详情模板对应的数据表模型路径
    $model_tpl_str = '\app\common\model\mysql\\' . $model_tb_name;

    // 实例化数据表模型
    $model_tpl = new $model_tpl_str();


    return [
      'column' => $column,
      'model_tb_name' => $model_tb_name,
      'model' => $model_tpl
    ];
  }

  public function updateInfo($data)
  {
    if ($data['column_id']) {
      $column_id_len = count($data['column_id']);

      if ($column_id_len) {
        $data['column_id'] = $data['column_id'][$column_id_len - 1];
      }
    }
    $data = array_merge($data, ['update_time' => time()]);
    try {
      validate(InfoValidate::class)
        ->scene('edit')
        ->check($data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    return $this->infoModel->update($data);
  }

  public function getInfoDetail ($id) {
    try {
      validate(InfoValidate::class)
        ->scene('detail')
        ->check(['id' => $id]);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    $detail = $this
      ->infoModel
      ->where(['id' => $id])
      ->field("*, DATE_FORMAT(FROM_UNIXTIME(publish_time),'%Y-%m-%d %H:%i:%s') as publish_time")
      ->find()
      ->toArray();
    $detail['label_ids'] = explode(',', $detail['label_ids']);
    return $detail;
  }

  public function deleteCover ($info_id) {
    $info = $this->infoModel->where(['id' => $info_id])->field('cover_url')->find();
    if (!empty($info)) {
      $updated_row = $this->infoModel->where(['id' => $info_id])->update(['cover_url' => '']);
      if ($updated_row === 1) {
        // 删除磁盘上的图片资源
        $cover_path = root_path() . 'public\\storage\\' . $info['cover_url'];
        unlink($cover_path);
      }
    }
  }

  public function deleteResource($params) {
    try {
      validate(InfoValidate::class)
        ->scene('delete-resource')
        ->check(['id'=> $params['info_id'], 'column_id'=> $params['column_id'], 'resource_type' => $params['resource_type'], 'resource_url' => $params['resource_url']]);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    $field = '';
    switch ($params['resource_type']) {
      case 'pic':
        $field = 'images';
        break;
    }

    $info = $this
      ->infoModel
      ->where([['id', '=', $params['info_id']]])
      ->field($field)
      ->find()
      ->toArray();

    if (!empty($info)) {
      // 删除对应资源url
      $resourceList = json_decode($info[$field], true);

      $resourceList = array_filter($resourceList, function ($resource) use ($params) {
        return $resource['url'] !== $params['resource_url'];
      });

      $resourceList = array_values($resourceList);
      $resourceList = json_encode($resourceList);

      $res = $this->infoModel->updateOne([$field => $resourceList], [['id', '=', $params['info_id']]]);
      // $res = $service->editFields([['id', '=', $params['info_id']]], [$field => $resourceList]);
      if ($res) {
        // 删除磁盘上的对应资源
        $resourcePath = root_path() . 'public/storage/' . $params['resource_url'];
        unlink($resourcePath);
      }
    }
  }

}
