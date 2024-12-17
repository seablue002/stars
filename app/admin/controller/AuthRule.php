<?php


namespace app\admin\controller;


use think\App;
use think\exception\ValidateException;
use think\facade\Config;
use think\facade\View;
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
    try {
      $auth_rule_list = $this->authRuleBusiness->getAuthRulePageList($params);
    } catch (\Exception $e) {
      return $this->responseMessage->error('权限规则列表数据获取失败');
    }
    return $this->responseMessage->success('权限规则列表数据获取成功', $auth_rule_list);
  }

  /**
   * [baseList 权限规则基本信息列表]
   * @return [type] [description]
   */
  public function baseList() {
    try {
      $auth_rule_base_list = $this->authRuleBusiness->getAuthRuleList('id, title, pid');
    } catch (\Exception $e) {
      return $this->responseMessage->error('权限规则基本信息列表数据获取失败');
    }
    return $this->responseMessage->success('权限规则基本信息列表数据获取成功', $auth_rule_base_list);
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
    
    try {
      $rule_id = $this->authRuleBusiness->insertAuthRule($rule_data);
    } catch (ValidateException $e) {
      return $this->responseMessage->error($e->getMessage());
    } catch (\Exception $e) {
      return $this->responseMessage->error('系统内部错误');
    }
    
    return $this->responseMessage->success('权限规则添加成功');
  }

  public function detail() {
    $id = input('get.id', '', 'intval');
    if (empty($id)) {
      return $this->responseMessage->error('缺少必要参数');
    }

    try {
      $rule_data = [];
      $rule_data['rule_info'] = $this->authRuleBusiness->getOneAuthRuleInfo(['id' => $id]);
      $rule_data['id'] = $id;
    } catch (\Exception $e) {
      return $this->responseMessage->error('权限规则详情数据获取失败');
    }
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

    try {
      $rule_id = $this->authRuleBusiness->editAuthRule($rule_data);
    } catch (ValidateException $e) {
      return $this->responseMessage->error($e->getMessage());
    } catch (\Exception $e) {
      return $this->responseMessage->error('系统内部错误');
    }
  
    return $this->responseMessage->success('权限规则编辑成功');
  }
  
}