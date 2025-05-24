<?php
// 标签控制器
declare(strict_types=1);
namespace app\admin\controller;

use think\App;
use app\admin\business\Label AS LabelBusiness;

class Label extends AdminBase
{
  private $labelBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->labelBusiness = new LabelBusiness;
  }

  // 标签列表
  public function list()
  {
    $list = $this->labelBusiness->getLabelNormalInfoList();
    return $this->responseMessage->success('标签列表数据获取成功', $list);
  }

  // 标签列表
  public function getListByRid()
  {
    $params = [
      'rid' => input('get.rid', '')
    ];

    $label_list = $this->labelBusiness->getListByRid($params);
    return $this->responseMessage->success('标签列表数据获取成功', $label_list);
  }

  // 标签列表，基本信息
  public function baseList()
  {
    $label_list = $this->labelBusiness->getLabelBaseInfoList();
    
    return $this->responseMessage->success('标签基本信息列表数据获取成功', $label_list);
  }

  // 添加标签
  public function add()
  {
    // 获取请求数据
    $label_data = [
      'name' => input('post.name'),
      'pid'  => explode(',', input('post.pid')),
      'sort' => input('post.sort', 0, 'intval')
    ];

    $this->labelBusiness->insertLabel($label_data);
    
    return $this->responseMessage->success('标签添加成功');
  }

  // 标签详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    $label_data = $this->labelBusiness->getLabelDetail($id);
    

    return $this->responseMessage->success('标签详情数据获取成功', $label_data);
  }

  // 编辑标签
  public function edit()
  {
    // 获取请求数据
    $label_data = [
      'id'   => input('post.id', 0, 'intval'),
      'name' => input('post.name'),
      'pid'  => explode(',', input('post.pid')),
      'sort' => input('post.sort', 0, 'intval')
    ];

    $this->labelBusiness->updateLabel($label_data);
    
    return $this->responseMessage->success('标签编辑成功');
  }

  // 标签删除
  public function delete()
  {
    $id = input('post.id', 0, 'intval');

    $this->labelBusiness->deleteLabel(['id' => $id]);
    

    return $this->responseMessage->success('标签删除成功');
  }
}
