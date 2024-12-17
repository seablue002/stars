<?php
/* 管理员控制器 */

namespace app\admin\controller;


use think\App;
use think\facade\Config;
use think\facade\View;
use think\facade\Db;
use app\admin\business\AdminUser AS AdminUserBusiness;
use app\admin\business\AuthRole AS AuthRoleBusiness;
use app\admin\business\AuthRoleUser AS AuthRoleUserBusiness;

class AdminUser extends AdminBase
{
  private $adminBusiness = null;
  private $authRoleBusiness = null;
  private $authRoleUserBusiness = null;
  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->adminBusiness = new AdminUserBusiness;
    $this->authRoleBusiness = new AuthRoleBusiness;
    $this->authRoleUserBusiness = new AuthRoleUserBusiness;
  }
  
  /**
   * 管理员列表
   * @return string
   * @throws \Exception
   */
  public function list() {
    $params = [
      'name' => input('get.name', ''),
      'page_size' => input('get.size', Config::get('page.page_size'))
    ];
    try {
      $admin_list = $this->adminBusiness->getAdminUserList($params);
    } catch (\Exception $e) {
      return $this->responseMessage->error('后台用户列表数据获取失败');
    }
    return $this->responseMessage->success('后台用户列表数据获取成功', $admin_list);
  }

  /**
   * 添加管理员
   */
  public function add () {
    // 获取请求数据
    $username = input('post.username', '');
    $password = input('post.password', '');
    $role = input('post.role', [], 'intval');
    $status = input('post.status', '', 'intval');
    $admin_data = [
      'username' => $username,
      'password' => $password,
      'status' => $status
    ];

    try {
      Db::startTrans();
      $admin_id = $this->adminBusiness->insertAdmin($admin_data);
      
      $role_user_data = [
        'user_id' => $admin_id,
        'role_id' => $role
      ];

      $insert_success_len = $this->authRoleUserBusiness->insertRoleUser($role_user_data);
      if ($insert_success_len !== count($role)) {
        Db::rollback();
        throw new Exception('插入用户角色信息失败，管理员添加失败');
      }
      Db::commit();
    } catch (\Exception $e) {
      Db::rollback();
      return $this->responseMessage->error('管理员添加失败');
    }

    return $this->responseMessage->success('管理员用户添加成功');
  }

  public function detail() {
    $id = input('get.id', '', 'intval');
    if (empty($id)) {
      return $this->responseMessage->error('缺少必要参数');
    }

    try {
      $admin_user_data['admin_role'] = $this->adminBusiness->getAdminUserRole($id);
      $admin_user_data['admin_info'] = $this->adminBusiness->getOneAdminUserInfo(['id' => $id]);
      $admin_user_data['id'] = $id;
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('后台用户详情数据获取成功', $admin_user_data);
  }

  public function edit() {
    // 获取请求数据
    $id = input('post.id', '', 'intval');
    $username = input('post.username', '');
    $password = input('post.password', '');
    $role = input('post.role', '', 'intval');
    $status = input('post.status', '', 'intval');
    $admin_data = [
      'id' => $id,
      'username' => $username,
      'password' => $password,
      'status' => $status
    ];
    try {
      Db::startTrans();
      $update_success_len = $this->adminBusiness->editAdmin($admin_data);

      if ($update_success_len === 0) {
        return $this->responseMessage->error('管理员基本信息更新失败');
      }

      $role_user_data = [
        'user_id' => $id,
        'role_id' => $role
      ];

      $insert_success_len = $this->authRoleUserBusiness->editRoleUser($role_user_data);
      if ($insert_success_len !== count($role)) {
        Db::rollback();
        throw new Exception('插入用户角色信息失败，管理员添加失败');
      }
      Db::commit();
    } catch (\Exception $e) {
      Db::rollback();
      return $this->responseMessage->error($e->getMessage());
    }

    return $this->responseMessage->success('管理员用户编辑成功');
  }
}