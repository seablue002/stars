<?php
declare (strict_types=1);

namespace app\admin\middleware;

use Firebase\JWT\JWT;
use app\common\lib\ResponseMessage;

class JwtToken
{
  /**
   * 处理请求
   *
   * @param \think\Request $request
   * @param \Closure $next
   * @return Response
   */
  public function handle($request, \Closure $next)
  {
    // 前置中间件
    if (!checkJWTToken($request)) {
      $responseMsg = new ResponseMessage;
      return $responseMsg->error('请先登录', 'need-login');
    }
    return $next($request);
  }
}
