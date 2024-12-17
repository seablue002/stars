<?php


namespace app\home\middleware;
use app\common\lib\ResponseMessage;
use think\facade\Cache;

class Auth
{
  public function handle($request, \Closure $next)
  {
    $user_id = $request->payload->id;
    $user_info = Cache::store('redis')->get(config('jwt.redis_user_prefix') . $user_id);
    if (empty($user_info)) {
      $responseMsg = new ResponseMessage;
      return $responseMsg->error('请先登录', 'need-login');
    }
    return $next($request);
  }
}
