<?php
use Firebase\JWT\JWT;
use app\common\lib\ResponseMessage;
use app\common\model\mysql\ColumnExtendFields as ColumnExtendFieldsModel;
use app\home\business\Column as ColumnBusiness;

// 生成JWT token
function getJWTToken($value)
{
  $time    = time();
  $payload = [
    'iat'  => $time,
    'nbf'  => $time,
    // 有效期
    'exp'  => $time + config('jwt.redis_user_exp'),
    'data' => [
      'id'       => $value['id'],
      'openid' => $value['openid']
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
  } catch (\Exception $e) {
    return false;
  }
  return true;
}

// 获取栏目扩展字段
function getColumnExtendFields ($where = [], $fields = '*') {
  $mdl = new ColumnExtendFieldsModel;
  return $mdl->getOne($where, $fields)->toArray();
}

/**
 * @param $manual_url 手动输入的url
 * @param $column 栏目
 * @param string $mode 提取url的模式 detail内容页，list列表页
 */
function getUrl ($manual_url, $column, $id = '', $mode = 'detail') {
  $url = '';
  if ($manual_url) {
    $url = $manual_url;
  } else {
    if ($mode === 'detail') {
      $url = $column['parent_dir_path'] . $column['column_dir_path'] . '/' . $id . '.html';
    } else {
      if ($column['column_dir_path'] !== 'index') {
        $url = $column['parent_dir_path'] . $column['column_dir_path'];
      }
    }
  }
  return request()->domain() .'/' . $url;
}

/**
 * 获取当前页面的面包屑导航
 * @param $column 当前栏目
 * @param string $base_file 入口文件名
 * @param string $current_class_name 当前菜单选中的当前样式类名
 * @return string 面包屑导航信息html字符串
 */
function breadcrumb ($column, $base_file = '/', $current_class_name = 'active') {
  $column_business = new ColumnBusiness;
  $column_list = $column_business->getColumnNormalInfoList();

  $column_list = getTopParent($column_list, $column['id']);

  $html_str = '<a href="' . $base_file . '">首页</a> &gt; ';

  foreach ($column_list as $item) {
    $extra_fields = getColumnExtendFields(['column_id' => $item['id']], ['column_manual_url']);
    $url = getUrl($extra_fields['column_manual_url'], $item,null, null);
    $href = 'href="' . $url . '"';
    $is_current = $item['id'] === $column['id'];
    if (!$is_current) {
      $html_str .= '<a ' . $href . '>' . $item['name'] . '</a> &gt; ';
    } else {
      $html_str .= '<a class="' . ($is_current ? $current_class_name : "") . '"' . '>' . $item['name'] . '</a>';
    }
  }

  return $html_str;
}