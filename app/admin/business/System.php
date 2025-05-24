<?php


namespace app\admin\business;

use think\facade\Db;

class System
{
  public function getDicList ($dicTypeKeys) {
    $dicList = [];
    foreach ($dicTypeKeys as $dicTypeKey) {
      $temp = [];
      $sql = "SELECT
            d.dic_key code
            , d.dic_value value
            , d.dic_label label
            FROM web_dic d, web_dic_type dt
            WHERE dt.dic_type_key = '{$dicTypeKey}'
            AND dt.status = 1
            AND d.dic_type_id = dt.id
            AND d.status = 1
            ORDER BY d.sort ASC";
      $temp[$dicTypeKey] = Db::query($sql);

      $keys = array_map(function ($dic) {
        return $dic['code'];
      }, $temp[$dicTypeKey]);

      $values = array_values($temp[$dicTypeKey]);
      $dicList[$dicTypeKey] = array_combine($keys, $values);
    }


    return $dicList;
  }

  public function getRegionList ($pid) {
    $sql = "SELECT id, sname FROM web_region WHERE pid = {$pid}";
    $list = Db::query($sql);
    return $list;
  }

}