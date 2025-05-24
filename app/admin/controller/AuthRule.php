<?php
namespace app\admin\controller;


use think\App;
use think\facade\Config;
use app\admin\business\AuthRule AS AuthRuleBusiness;

class AuthRule extends AdminBase
{
  private $authRuleBusiness = null;
  
  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->authRuleBusiness = new AuthRuleBusiness;
  }
  
  /**
   * 权限规则列表
   * @return string
   * @throws \Exception
   */
  public function list() {
    $params = [
      'name' => input('get.name', ''),
      'page_size' => input('get.size', Config::get('page.page_size'))
    ];
    
    $list = $this->authRuleBusiness->getAuthRulePageList($params);
    
    return $this->responseMessage->success('权限规则列表数据获取成功', $list);
  }

  /**
   * [baseList 权限规则基本信息列表]
   * @return [type] [description]
   */
  public function baseList() {
    $list = $this->authRuleBusiness->getAuthRuleList('id, title, pid');
    
    return $this->responseMessage->success('权限规则基本信息列表数据获取成功', $list);
  }
  
  public function add () {
    // 获取请求数据
    $name = input('post.name', '');
    $title = input('post.title', '');
    $pid = input('post.pid', '', 'intval');
    $menu_type = input('post.menu_type', '', 'intval');
    $rule_data = [
      'name' => $name,
      'title' => $title,
      'pid' => $pid,
      'menu' => $menu_type
    ];
    
    $this->authRuleBusiness->insertAuthRule($rule_data);
    
    return $this->responseMessage->success('权限规则添加成功');
  }

  public function detail() {
    $id = input('get.id', '', 'intval');
    if (empty($id)) {
      return $this->responseMessage->error('缺少必要参数');
    }

    $rule_data = [];
    $rule_data['rule_info'] = $this->authRuleBusiness->getOneAuthRuleInfo(['id' => $id]);
    $rule_data['id'] = $id;
    
    return $this->responseMessage->success('权限规则详情数据获取成功', $rule_data);
  }
  

  public function edit()
  {
    // 获取请求数据
    $id = input('post.id', '', 'intval');
    $name = input('post.name', '');
    $title = input('post.title', '');
    $pid = input('post.pid', '', 'intval');
    $menu_type = input('post.menu_type', '', 'intval');
    $rule_data = [
      'id' => $id,
      'name' => $name,
      'title' => $title,
      'pid' => $pid,
      'menu' => $menu_type
    ];

    $this->authRuleBusiness->editAuthRule($rule_data);
  
    return $this->responseMessage->success('权限规则编辑成功');
  }
  
}