<?php
use Firebase\JWT\JWT;
use app\common\lib\ResponseMessage;
use think\facade\Cache;
// 应用公共文件

// 生成JWT token
function getJWTToken($value)
{
  $time    = time();
  $payload = [
    'iat'  => $time,
    'nbf'  => $time,
    // 有效期
    'exp'  => $time + config('jwt.redis_admin_user_exp'),
    'data' => [
      'id'       => $value['id']
    ]
  ];
  $key     = config('jwt.secret');
  $alg     = config('jwt.alg');
  $token   = JWT::encode($payload, $key, $alg);
  return $token;
}

// 验证JWT token
function checkJWTToken($request)
{
  $alg = [
    'typ' => 'JWT',
    'alg' => config('jwt.alg')
  ];
  $jwt = $request->header('Authorization');
  
  $responseMsg = new ResponseMessage;
  if (empty($jwt)) {
    $responseMsg->error('[400] 缺少token');
  }
  $key = config('jwt.secret');
  try {
    $payload = JWT::decode($jwt, $key, $alg);
    $request->payload = $payload->data;
  } catch (\Throwable $e) {
    return false;
  }
  return true;
}
