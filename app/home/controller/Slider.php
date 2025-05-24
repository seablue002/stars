<?php
namespace app\home\controller;
use app\home\business\Slider as SliderBusiness;
use think\App;

class Slider extends IndexBase
{
  private $sliderBusiness = null;
  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->sliderBusiness = new SliderBusiness;
  }

  public function list () {
    $list = $this->sliderBusiness->list();

    return $this->responseMessage->success('获取轮播图列表数据成功', $list);
  }
}