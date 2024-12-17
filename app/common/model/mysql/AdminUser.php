<?php
declare(strict_types=1);

namespace app\common\model\mysql;

use app\common\model\mysql\AuthRoleUser as AuthRoleUserModel;
use think\Session;

class AdminUser extends BaseModel
{
  /**
   * 根据用户名获取管理员信息
   * @param $username
   * @return array|bool|\think\db\concern\Model
   */
  public function getAdminUserByUsername($username) {
    if (empty($username)) {
      return false;
    }
    
    $where = [
      'username' => trim($username),
      'status' => 1
    ];

    $result = $this->where($where)->findOrEmpty();
    return $result;
  }
  
  /**
   * 根据主键更新信息
   * @param $id
   * @param $data
   * @return bool
   */
  public function updateById($id, $data) {
    $id = intval($id);
    if (empty($id) || empty($data) || !is_array($data)) {
      return false;
    }
    
    $where = [
      'id' => $id
    ];
    
    return $this->where($where)->save($data);
  }
  
  /**
   * 一对多关联 web_auth_role_user表
   * @return \think\model\relation\HasMany
   */
  public function role () {
    return $this->hasMany(AuthRoleUserModel::class, 'user_id');
  }
  
  
  public function getAdminUserList($where, $page_size)
  {
    $dataModel = $this
      ->alias('a')
      ->leftJoin('auth_role_user ru','a.id = ru.user_id')
      ->leftJoin('auth_role r','ru.role_id = r.id')
      ->group('a.id')
      ->field("a.*, ru.role_id, group_concat(r.title) roles")
      ->where($where)
      ->paginate($page_size);
    $total_page = $dataModel->total();
    return [
      'data' => $dataModel->toArray()['data'],
      'total' => $total_page
    ];
  }
}