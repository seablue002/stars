<?php
declare(strict_types=1);

namespace app\admin\controller;
use edward\captcha\facade\CaptchaApi;

class Verify extends AdminBase
{
  public function index () {
    return $this->responseMessage->success('获取验证码成功', CaptchaApi::create());
  }
}
