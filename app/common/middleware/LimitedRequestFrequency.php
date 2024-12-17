<?php
declare (strict_types = 1);

namespace app\common\middleware;
use app\common\lib\ResponseMessage;
use think\facade\Cache;

/**
* 限制api请求频率
*/
class LimitedRequestFrequency
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        // 这个key记录该ip的访问次数
        $key = getClientRealIp();
        //限制时间60秒
        $limitTime = 60;
        //限制次数为30
        $limitCount = 200;
        $check = Cache::store('redis')->exists($key);
        if ($check) {
          Cache::store('redis')->incr($key);
          $count = Cache::store('redis')->get($key);
          if($count > $limitCount) {
            $responseMsg = new ResponseMessage;
            return $responseMsg->error('请求太频繁，请稍后再试！');
          }
        } else {
          Cache::store('redis')->incr($key);
          // 限制时间为60秒
          Cache::store('redis')->expire($key, $limitTime);
        }
        return $next($request);
    }
}
