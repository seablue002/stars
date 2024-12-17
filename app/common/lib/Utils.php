<?php


namespace app\common\lib;


class Utils
{
  public function getChildrenCategory (&$data, $origin_data, $pid, $level = 0) {
    foreach ($origin_data as $cate) {
      if ($cate['pid'] === $pid) {
        $cate['level'] = $level;
        $data[] = $cate;
        $this->getChildrenCategory($data, $origin_data, $cate['id'], $level+1);
      }
    }
  }

  public function getChildrenCategoryTree (&$data, $origin_data, $pid, $level = 0) {
    foreach ($origin_data as $cate) {
      if ($cate['pid'] === $pid) {
        $cate['level'] = $level;
        $cate['children'] = [];
        $cate['children_html_str'] = [];
        $this->getChildrenCategory($cate['children'], $origin_data, $cate['id'], $level+1);
        $data[] = $cate;
      }
    }
  }

  public function getChildrenRule (&$data, $origin_data, $pid, $level = 0) {
    foreach ($origin_data as $rule) {
      if ($rule['pid'] === $pid) {
        $rule['level'] = $level;
        $data[] = $rule;
        $this->getChildrenRule($data, $origin_data, $rule['id'], $level+1);
      }
    }
  }

  public function getAncestors (&$data, $list, $pid, $level = 0) {
    foreach ($list as $item) {
      if ($item['id'] === $pid) {
        $item['level'] = $level;
        $data[] = $item;
        $this->getAncestors($data, $list, $item['pid'], $level+1);
      }
    }
  }

  public function indexOfObjInObjArrByMultipleKey ($obj, $objArr, $keys = ['id']) {
    $index = -1;
    for ($i = 0; $i < count($objArr); $i++) {
      $count = 0;
      forEach ($keys as $j) {
        if ($obj[$j] === $objArr[$i][$j]) {
          $count++;
        }
      }
      if ($count === count($keys)) {
        $index = $i;
        break;
      }
    }
    return $index;
  }
  
  // curl 封装
  public static function curl ($url) {
    $info = curl_init();
    curl_setopt($info, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($info, CURLOPT_HEADER, 0);
    curl_setopt($info, CURLOPT_NOBODY, 0);
    curl_setopt($info, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($info, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($info, CURLOPT_URL, $url);
    $output = curl_exec($info);
    curl_close($info);
    return $output;
  }

}
