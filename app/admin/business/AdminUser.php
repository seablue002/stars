<?php
declare(strict_types=1);

namespace app\admin\business;

use app\admin\validate\AdminUser as AdminUserValidate;
use app\common\model\mysql\AdminUser AS AdminUserModel;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Cache;

class AdminUser
{
  private $adminUserModel = null;

  public function __construct()
  {
    $this->adminUserModel = new AdminUserModel;
  }
  /**
   * 管理员用户登录
   * @param $data 登录的表单数据
   * @throws Exception
   */
  public function login($data)
  {

    $captcha_key = $data['captcha_key'];
    $code = Cache::store('redis')->get($captcha_key);
    if (!$code || strtolower($code) !== strtolower($data['captcha'])) {
      throw new Exception('验证码错误');
    }
    Cache::store('redis')->delete($captcha_key);

    try {
      validate(AdminUserValidate::class)
        ->scene('login')
        ->check($data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    $adminUser    = $this->adminUserModel->getAdminUserByUsername($data['username'])->toArray();
    if (empty($adminUser)) {
      throw new Exception('登录失败');
    }

    if ($adminUser['password'] !== $data['password']) {
      throw new Exception('密码错误');
    }

    // 更新登录信息到数据库表
    $updateData = [
      'last_login_time' => time(),
      'last_login_ip'   => request()->ip()
    ];
    $res        = $this->adminUserModel->updateById($adminUser['id'], $updateData);
    if (empty($res)) {
      throw new Exception('登录失败');
    }

    unset($adminUser['password']);
    // 登录成功生成token
    $token = getJWTToken($adminUser);
    try {
      Cache::store('redis')->set(config('jwt.redis_admin_user_prefix') . $adminUser['id'], $adminUser, config('jwt.redis_admin_user_exp'));
    } catch (Exception $e) {
      throw new Exception('内部错误，登录失败');
    }

    return [
      'token' => $token,
      'admin_info' => [
        'username' => $adminUser['username']
      ]
    ];
  }

  /**
   * [out 退出登录]
   * @param  [type] $user_id [用户id]
   * @return [type]          [description]
   */
  public function out ($user_id) {
    Cache::store('redis')->delete(config('jwt.redis_admin_user_prefix') . $user_id);
  }

  /**
   * 获取管理员列表
   * @param array $where
   * @param string $fields
   * @return array
   */
  public function getAdminUserList($params) {
    $where = [];
    if (!empty($params['name'])) {
      $where[] = ['a.username', 'LIKE', "%{$params['name']}%"];
    }
    return $this->adminUserModel->getAdminUserList($where, $params['page_size']);
  }

  /**
   * 获取一条管理员记录
   * @param array $where
   * @param string $fields
   * @return array
   */
  public function getOneAdminUserInfo($where=[], $fields='*') {
    return $this->adminUserModel->getOne($where, $fields)->toArray();
  }

  public function getAdminUserRole($user_id) {
    return $this->adminUserModel->role()->where(['user_id'=> $user_id])->column('role_id');
  }

  /**
   * 添加管理员
   * @param $data
   * @return int|string
   * @throws Exception
   */
  public function insertAdmin($data) {
    $time = time();
    $admin_data = array_merge($data, [
      'create_time' => $time,
      'update_time' => $time
    ]);
    try {
      validate(AdminUserValidate::class)
        ->scene('add')
        ->check($admin_data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    return $this->adminUserModel->insertOneData($admin_data);
  }

  /**
   * 编辑管理员
   * @param $data
   * @return int|string
   * @throws Exception
   */
  public function editAdmin($data) {
    if (empty($data['password'])) {
      unset($data['password']);
    }
    $time = time();
    $admin_data = array_merge($data, [
      'update_time' => $time
    ]);
    try {
      validate(AdminUserValidate::class)
        ->scene('edit')
        ->check($admin_data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    $where = [
      'id' => $data['id']
    ];
    return $this->adminUserModel->updateOne($admin_data, $where);
  }
}