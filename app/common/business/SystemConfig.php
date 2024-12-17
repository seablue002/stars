<?php
declare(strict_types=1);

namespace app\common\business;

use app\common\model\mysql\SystemConfig AS SystemConfigModel;
use think\facade\Db;

class SystemConfig
{
  private $systemConfigModel = null;

  public function __construct()
  {
    $this->systemConfigModel = new SystemConfigModel;
  }

  public function getConfigValue ($configKeys) {
    $configList = [];
    foreach ($configKeys as $configKey) {
      $sql = "SELECT
            field
            , value
            FROM web_system_config
            WHERE field = '{$configKey}'
            LIMIT 1";
      $configList[$configKey] = Db::query($sql)[0];
    }
    return $configList;
  }
}
