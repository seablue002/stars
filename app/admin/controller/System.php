<?php
namespace app\admin\controller;
use app\admin\business\System AS SystemBusiness;
use think\App;

class System extends AdminBase
{
  private $systemBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->systemBusiness = new SystemBusiness;
  }

  public function getDicList () {
    $dicTypeKeys = input('get.dic_type_keys', []);
    
    $dicList = $this->systemBusiness->getDicList($dicTypeKeys);
    
    return $this->responseMessage->success('字典列表数据获取成功', $dicList);
  }

  public function info () {
    $list = [
      '操作系统'=>PHP_OS,
      '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
      'PHP运行方式'=>php_sapi_name(),
      'PHP版本'=>PHP_VERSION,
      '数据库版本' => \think\facade\Db::query("select version() as ver")[0]['ver'],
      'ThinkPHP版本'=>\think\facade\App::version(),
      '上传附件限制'=>ini_get('upload_max_filesize'),
      '服务器时间'=>date("Y年n月j日 H:i:s"),
      '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
      '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
      '获取服务器语言'=>$_SERVER['HTTP_ACCEPT_LANGUAGE'],
      'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
    ];

    return $this->responseMessage->success('系统信息获取成功', $list);
  }

  public function getRegionList () {
    $pid = input('get.pid', 0);
    
    $regionList = $this->systemBusiness->getRegionList($pid);

    return $this->responseMessage->success('区域列表数据获取成功', $regionList);
  }
}