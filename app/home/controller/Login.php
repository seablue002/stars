<?php


namespace app\home\controller;

use think\App;
use app\home\business\User AS UserBusiness;
use think\Request;

class Login extends IndexBase
{
  private $userBusiness = null;
  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->userBusiness = new UserBusiness;
  }

  public function loginIn()
  {
    $user       = [
      'phone' => input('post.phone', ''),
      'password'  => input('post.pwd', '')
    ];

    try {
      $user_data = $this->userBusiness->login($user);
    } catch (\Exception $e) {
      return $this->responseMessage->error('登录失败');
    }

    return $this->responseMessage->success('登录成功', $user_data);
  }

  public function loginOut (Request $request) {
    $user_id = $request->payload->id;
    try {
      $this->userBusiness->out($user_id);
    } catch (\Exception $e) {
      return $this->responseMessage->error('退出登录失败');
    }

    return $this->responseMessage->success('退出登录成功');
  }
}
