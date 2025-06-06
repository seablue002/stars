<?php

/* 权限管理角色控制器 */

namespace app\admin\controller;
use think\App;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Config;
use think\facade\Db;
use app\admin\business\AuthRole AS AuthRoleBusiness;
use app\admin\business\AuthRoleRule AS AuthRoleRuleBusiness;
use app\common\exception\ApiException;

class AuthRole extends AdminBase
{
  private $authRoleBusiness = null;
  private $authRoleRuleBusiness = null;
  
  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->authRoleBusiness = new AuthRoleBusiness;
    $this->authRoleRuleBusiness = new AuthRoleRuleBusiness;
  }
  
  /**
   * 权限角色列表
   * @return string
   * @throws \Exception
   */
  public function list() {
    $params = [
      'name' => input('get.name', ''),
      'page_size' => input('get.size', Config::get('page.page_size'))
    ];

    $role_list = $this->authRoleBusiness->getAuthRolePageList($params);
  
    return $this->responseMessage->success('权限角色基本信息列表数据获取成功', $role_list);
  }

  // 权限角色列表，基本信息
  public function baseList()
  {
    $role_list = $this->authRoleBusiness->getAuthRoleList();

    return $this->responseMessage->success('权限角色基本信息列表数据获取成功', $role_list);
  }
  
  public function add() {
    // 获取请求数据
    $title = input('post.title', '');
    $status = input('post.status', 0, 'intval');
    $rule = input('post.rule', 0, 'intval');
    $role_data = [
      'title' => $title,
      'status' => $status
    ];
    
    try {
      Db::startTrans();
      // 1、入库角色信息
      $role_id = $this->authRoleBusiness->insertAuthRole($role_data);
      
      // 2、入库角色权限
      $insert_success_len = $this->authRoleRuleBusiness->insertAuthRoleRule($role_id, $rule);
      if ($insert_success_len !== count($rule)) {
        throw new ApiException('插入角色权限规则信息失败，角色添加失败');
      }
      Db::commit();
    } catch (\Throwable $e) {
      Db::rollback();
      throw $e;
    }
    
    return $this->responseMessage->success('角色添加成功');
  }

  public function detail() {
    $id = input('get.id', '', 'intval');
    if (empty($id)) {
      return $this->responseMessage->error('缺少必要参数');
    }

    $role_data = [];
    $role_data['role_info'] = $this->authRoleBusiness->getOneAuthRoleInfo(['id' => $id]);
    $role_data['roles_rule_list'] = $this->authRoleRuleBusiness->getRolesRule($id);
    $role_data['id'] = $id;

    return $this->responseMessage->success('权限角色详情数据获取成功', $role_data);
  }
  
  public function edit() {
    // 获取请求数据
    $id = input('post.id', '', 'intval');
    $title = input('post.title', '');
    $status = input('post.status', 0, 'intval');
    $rule_ids = input('post.rule', 0, 'intval');
    $role_data = [
      'id' => $id,
      'title' => $title,
      'status' => $status
    ];
  
    // 编辑角色基本信息
    $this->authRoleBusiness->editAuthRole($role_data);

    // 编辑角色拥有的权限信息
    $this->authRoleRuleBusiness->editAuthRule($id, $rule_ids);


    return $this->responseMessage->success('权限角色编辑成功');
  }
  
}