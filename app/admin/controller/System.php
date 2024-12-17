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