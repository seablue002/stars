<?php


namespace app\admin\controller;
use app\admin\business\System AS SystemBusiness;
use think\App;
use think\facade\Config;
use think\facade\View;

class System extends AdminBase
{
  private $systemBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->systemBusiness = new SystemBusiness;
  }

  public function articleList () {
    $params = [
      'page' => input('get.page', 1),
      'page_size' => input('get.page_size', Config::get('page.page_size'))
    ];
    $article_list = $this->systemBusiness->getArticleList($params);
    $template_data = $article_list;
    View::assign($template_data);
    return View::fetch();
  }

  public function articleEdit() {
    if (!request()->isAjax()) {
      $params = [
        'id' => input('get.id', 0, 'intval')
      ];
      $article_data = $this->systemBusiness->articleDetail($params);

      $template_data = [
        'article_detail' => $article_data,
      ];
      View::assign($template_data);
      return View::fetch();
    } else {
      // 获取请求数据
      $params = [
        'id' => input('post.id', 0, 'intval'),
        'title' => input('post.title'),
        'content' => input('post.content'),
        'update_time' => time()
      ];

      try {
        $this->systemBusiness->updateArticle($params);
      } catch (\Exception $e) {
        return $this->responseMessage->error('文章编辑失败');
      }

      return $this->responseMessage->success('文章编辑成功');
    }
  }

  public function getDicList () {
    $dicTypeKeys = input('get.dic_type_keys', []);
    try {
      $dicList = $this->systemBusiness->getDicList($dicTypeKeys);
    } catch (\Exception $e) {
      return $this->responseMessage->error('字典列表数据获取失败');
    }
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
    try {
      $regionList = $this->systemBusiness->getRegionList($pid);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('区域列表数据获取成功', $regionList);
  }
}