<?php
// 栏目控制器
declare(strict_types=1);
namespace app\admin\controller;

use think\App;
use app\admin\business\Column AS ColumnBusiness;
use app\admin\business\ColumnExtendFields AS ColumnExtendFieldsBusiness;
use think\facade\Db;
use think\file\UploadedFile;
use think\facade\Filesystem;

class Column extends AdminBase
{
  private $columnBusiness = null;
  private $columnExtendFieldsBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->columnBusiness = new ColumnBusiness;
    $this->columnExtendFieldsBusiness = new ColumnExtendFieldsBusiness;
  }

  // 栏目列表
  public function list()
  {
    $column_list = $this->columnBusiness->getColumnNormalInfoList();
    
    return $this->responseMessage->success('栏目列表数据获取成功', $column_list);
  }

  // 栏目列表
  public function getListByRid()
  {
    $params = [
      'rid' => input('get.rid', '')
    ];

    $label_list = $this->columnBusiness->getListByRid($params);
    
    return $this->responseMessage->success('栏目列表数据获取成功', $label_list);
  }

  // 栏目列表，基本信息
  public function baseList()
  {
    $column_list = $this->columnBusiness->getColumnBaseInfoList();
    
    return $this->responseMessage->success('栏目基本信息列表数据获取成功', $column_list);
  }

  // 添加栏目
  public function add()
  {
    // 获取请求数据
    $column_data = [
      'name' => input('post.name'),
      'pid'  => explode(',', input('post.pid')),
      'is_last'  => input('post.is_last', 0),
      'is_show_in_nav'  => input('post.is_show_in_nav', 1),
      'parent_dir_path'  => input('post.parent_dir_path'),
      'column_dir_path'  => input('post.column_dir_path'),
      'model_tb_name'  => input('post.model_tb_name'),
      'list_temp_id'  => input('post.list_temp_id'),
      'detail_temp_id'  => input('post.detail_temp_id'),
      'seo_title'  => input('post.seo_title'),
      'seo_keywords'  => input('post.seo_keywords'),
      'seo_description'  => input('post.seo_description'),
      'sort' => input('post.sort', 0, 'intval')
    ];
    $extra_fields = json_decode(input('post.extra_fields'), true);

    try {
      Db::startTrans();
      if (isset($_FILES['cover']) && !empty($_FILES['cover'])) {
        $cover_file                 = new UploadedFile($_FILES['cover']['tmp_name'], $_FILES['cover']['name'], $_FILES['cover']['type'], $_FILES['cover']['error']);
        $url                       = Filesystem::disk('public')->putFile('column_cover', $cover_file);
        $column_data['cover_url'] = str_replace('\\','/', $url);;
      }
      $column_id = $this->columnBusiness->insertColumn($column_data);

      $extra_fields_params = ['column_id' => $column_id];
      $extra_fields_params  = array_merge($extra_fields, $extra_fields_params);
      $this->columnExtendFieldsBusiness->saveConfig($extra_fields_params);
      Db::commit();
    } catch (\Throwable $e) {
      Db::rollback();
      throw $e;
    }
    return $this->responseMessage->success('栏目添加成功');
  }

  // 栏目详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    
    $column_data = $this->columnBusiness->getColumnDetail($id);

    return $this->responseMessage->success('栏目详情数据获取成功', $column_data);
  }

  // 编辑栏目
  public function edit()
  {
    // 获取请求数据
    $column_data = [
      'id'   => input('post.id', 0, 'intval'),
      'name' => input('post.name'),
      'pid'  => explode(',', input('post.pid')),
      'is_last'  => input('post.is_last', 0),
      'is_show_in_nav'  => input('post.is_show_in_nav', 1),
      'parent_dir_path'  => input('post.parent_dir_path'),
      'column_dir_path'  => input('post.column_dir_path'),
      'model_tb_name'  => input('post.model_tb_name'),
      'list_temp_id'  => input('post.list_temp_id'),
      'detail_temp_id'  => input('post.detail_temp_id'),
      'seo_title'  => input('post.seo_title'),
      'seo_keywords'  => input('post.seo_keywords'),
      'seo_description'  => input('post.seo_description'),
      'sort' => input('post.sort', 0, 'intval')
    ];
    $extra_fields = json_decode(input('post.extra_fields'), true);

    try {
      Db::startTrans();
      if (isset($_FILES['cover'])) {
        $cover_file                 = new UploadedFile($_FILES['cover']['tmp_name'], $_FILES['cover']['name'], $_FILES['cover']['type'], $_FILES['cover']['error']);
        $url                       = Filesystem::disk('public')->putFile('column_cover', $cover_file);
        $column_data['cover_url'] = str_replace('\\','/', $url);
      }
      $this->columnBusiness->updateColumn($column_data);

      $extra_fields_params = ['column_id' => $column_data['id']];
      $extra_fields_params  = array_merge($extra_fields, $extra_fields_params);
      $this->columnExtendFieldsBusiness->saveConfig($extra_fields_params);
      Db::commit();
    } catch (\Throwable $e) {
      Db::rollback();
      throw $e;
    }
    return $this->responseMessage->success('栏目编辑成功');
  }

  // 删除栏目
  public function delete()
  {
    $data = [
      'id' => input('get.id', 0, 'intval')
    ];

    $this->columnBusiness->delete($data);
 
    return $this->responseMessage->success('删除成功');
  }

  public function deleteCover () {
    $column_id = input('get.column_id', 0);
    
    $this->columnBusiness->deleteCover($column_id);
    
    return $this->responseMessage->success('删除栏目封面图成功');
  }
}
