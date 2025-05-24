<?php
/* 权限角色控制器 */

namespace app\admin\business;

use app\admin\validate\AuthRole as AuthRoleValidate;
use app\common\model\mysql\AuthRole AS AuthRoleModel;

class AuthRole
{
  private $authRoleModel = null;
  
  public function __construct()
  {
    $this->authRoleModel = new AuthRoleModel;
  }
  
  /**
   * 获取管理员权限角色列表
   */
  public function getAuthRoleList() {
    return $this->authRoleModel->getList();
  }
  
  /**
   * 获取管理员权限角色分页列表
   */
  public function getAuthRolePageList($params) {
    $where = [];
    if (!empty($params['name'])) {
      $where[] = ['title', 'LIKE', "%{$params['name']}%"];
    }
    return $this->authRoleModel->getPageList($where, '*',$params['page_size']);
  }
  
  /**
   * 获取一条权限角色记录
   * @param array $where
   * @param string $fields
   * @return array
   */
  public function getOneAuthRuleInfo($where=[], $fields='*') {
    return $this->authRoleModel->getOne($where, $fields)->toArray();
  }
  
  public function insertAuthRole($data) {
    $time = time();
    $role_data = array_merge($data, [
      'create_time' => $time,
      'update_time' => $time
    ]);
    
    validate(AuthRoleValidate::class)
        ->scene('add')
        ->check($role_data);
    
    return $this->authRoleModel->insertOneData($role_data);
  }
  
  public function editAuthRole($data) {
    $time = time();
    $role_data = array_merge($data, [
      'update_time' => $time
    ]);
    
    validate(AuthRoleValidate::class)
        ->scene('edit')
        ->check($role_data);

    $where = [
      'id' => $data['id']
    ];
    
    return $this->authRoleModel->updateOne($role_data, $where);
  }
  
  /**
   * 获取一条权限角色记录
   * @param array $where
   * @param string $fields
   * @return array
   */
  public function getOneAuthRoleInfo($where=[], $fields='*') {
    return $this->authRoleModel->getOne($where, $fields)->toArray();
  }
  
}