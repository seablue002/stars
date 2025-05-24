<?php


namespace app\admin\business;
use app\admin\validate\AuthRoleUser as AuthRoleUserValidate;
use app\common\model\mysql\AuthRoleUser AS AuthRoleUserModel;
use app\common\exception\ApiException;
use think\exception\ValidateException;

class AuthRoleUser
{
  private $authRoleUserModel = null;
  
  public function __construct()
  {
    $this->authRoleUserModel = new AuthRoleUserModel;
  }
  
  public function insertRoleUser($data) {
    $role_data = [];
    $time = time();
    foreach ($data['role_id'] as $k => $v) {
      $temp = [
        'user_id' => $data['user_id'],
        'role_id' => $v
      ];

      $temp = array_merge($temp, [
        'create_time' => $time
      ]);
  
      $role_data[] = $temp;

      try {
        validate(AuthRoleUserValidate::class)
          ->scene('add')
          ->check($temp);
      } catch (ValidateException $e) {
        throw new ApiException($e->getMessage());
        break;
      }
    }
    return $this->authRoleUserModel->insertAllData($role_data);
  }
  
  public function editRoleUser($data) {
    $role_data = [];
    $time = time();

    foreach ($data['role_id'] as $k => $v) {
      $temp = [
        'user_id' => $data['user_id'],
        'role_id' => $v
      ];
      
      $temp = array_merge($temp, [
        'create_time' => $time
      ]);
      
      $role_data[] = $temp;
      
      try {
        validate(AuthRoleUserValidate::class)
          ->scene('add')
          ->check($temp);
      } catch (ValidateException $e) {
        throw new ApiException($e->getMessage());
        break;
      }
    }
    $where = [
      'user_id' => $data['user_id']
    ];
    $this->authRoleUserModel->where($where)->delete();
    return $this->authRoleUserModel->insertAllData($role_data);
  }
}