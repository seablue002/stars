<?php
// 应用公共文件
use Endroid\QrCode\QrCode;
/**
 * 获取指定日期段内每一天的日期
 * @param  Date  $start_date 开始日期
 * @param  Date  $end_date   结束日期
 * @return Array
 */
function getDateFromRange($start_date, $end_date) {
  $stime_stamp = strtotime($start_date);
  $etime_stamp = strtotime($end_date);
  
  // 计算日期段内有多少天
  $days = ($etime_stamp - $stime_stamp) / 86400 + 1;
  
  // 保存每天日期
  $date = [];
  
  for($i = 0; $i < $days; $i++) {
    $date[] = date('Y-m-d', $stime_stamp + (86400 * $i));
  }
  return $date;
}

function getMonthFromRange($start_date, $end_date) {
  $month_arr = [];
  $start_date_first_day = date('Y-m-01', strtotime("$start_date"));
  $month_arr[] = $start_date_first_day;
  $temp = strtotime("$start_date_first_day +1 month");
  $temp2 = strtotime(date('Y-m-01', strtotime("$end_date")));
  while($temp <= $temp2){
    $month_arr[] = date('Y-m-01', $temp);
    $temp = strtotime(" +1 month", $temp);
  }
  
  return $month_arr;
}

// 获取客户端真实ip地址
function getClientRealIp () {
  static $realip;
  if (isset($_SERVER)) {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
      $realip = $_SERVER['HTTP_CLIENT_IP'];
    }else{
      $realip=$_SERVER['REMOTE_ADDR'];
    }
  } else {
    if (getenv('HTTP_X_FORWARDED_FOR')) {
      $realip = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('HTTP_CLIENT_IP')) {
      $realip = getenv('HTTP_CLIENT_IP');
    } else {
      $realip = getenv('REMOTE_ADDR');
    }
  }
  return $realip;
}

/**
 * 将树结构扁平化数组还原为树结构
 * @param $result_arr
 * @param $origin_data
 * @param int $pid
 * @param int $level
 * @param array $key_maps 主键必须放第一位
 */
function getTree (&$result_arr, $origin_data, $pid = 0, $level = 0, $key_maps = ['id' => 'id', 'name' => 'name', 'pid' => 'pid']) {
  if (is_array($origin_data)) {
    $level++;
    for ($i = 0; $i < count($origin_data); $i++) {
      if ($origin_data[$i]['pid'] === $pid) {
        $map_keys = array_keys($key_maps);
        $map_vals = array_values($key_maps);
        $temp = [];
        for ($j = 0; $j < count($map_keys); $j++) {
          $temp[$map_keys[$j]] = $origin_data[$i][$map_vals[$j]];
        }
        $temp['level'] = $level;
        $temp['is_show_children'] = true;
        $temp['children'] = [];
        $result_arr[] = $temp;
        getTree($result_arr[count($result_arr) - 1]['children'], $origin_data, $temp[$map_keys[0]], $level, $key_maps);
      }
    }
  }
  return $level;
}

function getTopParent ($cate, $id) {
  $arr = [];
  foreach ($cate as $v) {
    if ($v['id'] === $id) {
      $arr[] = $v;
      $arr = array_merge(getTopParent($cate, $v['pid']), $arr);
    }
  }
  return $arr;
}

/**
 * 递归器
 * 可以实现对数结构的递归调用执行
 * @param $origin_data
 * @param $cb 递归时需要执行的业务逻辑回调函数
 */
function recursionMachine (&$origin_data, $cb = '') {
  if (is_array($origin_data)) {
    for ($i = 0; $i < count($origin_data); $i++) {
      if (isFunction($cb)) {
        $cb($origin_data[$i], $origin_data);
      }
      if (isset($origin_data[$i]['children']) && count($origin_data[$i]['children'])) {
        recursionMachine($origin_data[$i]['children'], $cb);
      }
    }
  }
}

/**
 * 递归器, 区别层级执行回调
 * 可以实现对数结构的递归调用执行
 * @param $origin_data
 * @param $cb 递归时需要执行的业务逻辑回调函数
 */
function recursionMachineWithLevelDiffCallback (&$origin_data, &$parent_data = null, $index = 0, $cb = []) {
  if (is_array($origin_data)) {
    for ($i = 0; $i < count($origin_data); $i++) {
      $data = $origin_data[$i];
      $data_level = $data['level'];

      if (isFunction($cb[$data_level])) {
        $cb[$data_level]($data, $parent_data, $index);
      }
      $index++;
      if (isset($data['children']) && count($data['children'])) {
        $index = 0;
        recursionMachineWithLevelDiffCallback($data['children'], $data, $index, $cb);
      }

    }
  }
}

function isFunction ($f) {
  return (is_string($f) && function_exists($f)) || (is_object($f) && ($f instanceof Closure));
}

function renderQrCode ($value, $dir_name, $file_name) {
  $qr_code = new QrCode();
  $qr_code->setText($value);
  $file_path = '/storage/info_content/qrcode/'. $dir_name;

  $file_full_path = $file_path . '/'. $file_name;
  if (!is_dir(app()->getRootPath() . 'public'. $file_path)) {
    mkdir(app()->getRootPath() . 'public'. $file_path, '0777', true);
  }
  $qr_code->writeFile(app()->getRootPath() . 'public'. $file_full_path);
  return request()->domain() . $file_full_path;
}
