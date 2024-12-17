<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use think\facade\View;
use think\exception\ValidateException;
use app\admin\validate\AdminUser AS AdminUserValidate;
use app\admin\business\AdminUser AS AdminUserBusiness;
use think\Request;

class Login extends AdminBase
{
  public function loginIn() {
    $username = input('post.username', '', 'trim');
    $password = input('post.password', '', 'trim');
    $captcha = input('post.captcha', '', 'trim');
    $captcha_key = input('post.captcha_key', '', 'trim');

    $requestData = [
      'username' => $username,
      'password' => $password,
      'captcha' => $captcha,
      'captcha_key' => $captcha_key,
    ];

    try {
      $adminUserBusiness = new AdminUserBusiness;
      $adminUser = $adminUserBusiness->login($requestData);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('登录成功', $adminUser);
  }

  public function loginOut (Request $request) {
    $adminUserId = $request->payload->id;
    try {
      $adminUserBusiness = new AdminUserBusiness;
      $adminUserBusiness->out($adminUserId);
    } catch (\Exception $e) {
      return $this->responseMessage->error('退出登录失败');
    }

    return $this->responseMessage->success('退出登录成功');
  }

}
