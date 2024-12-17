<?php


namespace app\home\controller;


use app\home\business\Common as CommonBusiness;
use app\common\business\SystemConfig as SystemConfigBusiness;

use app\BaseController;
use think\App;

class Common extends BaseController
{
  private $commonBusiness = null;
  private $systemConfigBusiness = null;
  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->commonBusiness = new CommonBusiness;
    $this->systemConfigBusiness = new SystemConfigBusiness;
  }

  public function getProvince () {
    try {
      $province_list = $this->commonBusiness->getProvince();
    } catch (\Exception $e) {
      return $this->responseMessage->error('获取省市数据失败');
    }
    return $this->responseMessage->success('获取省市数据成功', $province_list);
  }

  public function getCity () {
    $province_id = input('get.province_id', 0, 'intval');
    try {
      $province_list = $this->commonBusiness->getCity($province_id);
    } catch (\Exception $e) {
      return $this->responseMessage->error('获取城市数据失败');
    }
    return $this->responseMessage->success('获取城市数据成功', $province_list);
  }

  public function getArea () {
    $city_id = input('get.city_id', 0, 'intval');
    try {
      $area_list = $this->commonBusiness->getArea($city_id);
    } catch (\Exception $e) {
      return $this->responseMessage->error('获取区数据失败');
    }
    return $this->responseMessage->success('获取区数据成功', $area_list);
  }

  public function getDicList () {
    $dicTypeKeys = input('get.dic_type_keys', []);
    try {
      $dicList = $this->commonBusiness->getDicList($dicTypeKeys);
    } catch (\Exception $e) {
      return $this->responseMessage->error('字典列表数据获取失败');
    }
    return $this->responseMessage->success('字典列表数据获取成功', $dicList);
  }

  public function getSystemConfig () {
    // 允许前端访问的系统配置项列表
    $VALID_CONF_KEYS = ['order_pay_expire'];

    $conf_keys = input('get.config_keys', []);
    foreach ($conf_keys as $conf_key) {
      if (!in_array($conf_key, $VALID_CONF_KEYS)) {
        return $this->responseMessage->error('配置数据获取失败');
      }
    }

    try {
      $conf_list = $this->systemConfigBusiness->getConfigValue($conf_keys);
    } catch (\Exception $e) {
      return $this->responseMessage->error('配置数据获取失败');
    }
    return $this->responseMessage->success('配置数据获取成功', $conf_list);
  }

}