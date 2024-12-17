<?php

namespace app\admin\middleware;
use app\common\lib\ResponseMessage;
use think\facade\Cache;
use think\Auth AS AuthHandle;

class Auth
{
  public function handle($request, \Closure $next)
  {
    // 前置中间件
    $admin_user_id = $request->payload->id;
    $admin_user_info = Cache::store('redis')->get(config('jwt.redis_admin_user_prefix') . $admin_user_id);
    if (empty($admin_user_info)) {
      $responseMsg = new ResponseMessage;
      return $responseMsg->error('请先登录', 'need-login');
    }

    // 权限检查
    $rule  = "{$request->controller()}/{$request->action()}";
    
    $check = AuthHandle::check($rule, $admin_user_id);
    if (false === $check) {
      $responseMsg = new ResponseMessage;
      return $responseMsg->error('暂无操作权限');
    }

    return $next($request);
  }
}
