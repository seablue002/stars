<?php


namespace app\home\business;

use think\facade\Db;
use think\Exception;
use app\common\model\mysql\Goods AS GoodsModel;

class Common
{
  private $goodsModel = null;
  public function __construct()
  {
    $this->goodsModel = new GoodsModel;
  }
  public function getProvince () {
    $sql = "SELECT * FROM web_region WHERE pid = 100000";
    return Db::query($sql);
  }

  public function getCity ($province_id) {
    if (empty($province_id)) {
      throw new Exception('缺少省份id');
    }
    $sql = "SELECT * FROM web_region WHERE pid = {$province_id}";
    return Db::query($sql);
  }

  public function getArea ($city_id) {
    if (empty($city_id)) {
      throw new Exception('缺少城市id');
    }
    $sql = "SELECT * FROM web_region WHERE pid = {$city_id}";
    return Db::query($sql);
  }

  public function getDicList ($dicTypeKeys)
  {
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

}