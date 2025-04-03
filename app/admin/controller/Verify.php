<?php
declare(strict_types=1);

namespace app\admin\controller;
use think\captcha\facade\Captcha;
use think\facade\Cache;

class Verify extends AdminBase
{
  public function index () {
    $captcha = Captcha::create();

    $token = uniqid('catcha_', true);
    // 保存5分钟后过期
    Cache::store('redis')->set($token, $captcha['code'], 300);

    $captchaReturn = [
      'base64' => $captcha['img'],
      'key' => $token
    ];
    return $this->responseMessage->success('获取验证码成功', $captchaReturn);
  }
}
