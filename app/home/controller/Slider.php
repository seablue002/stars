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
    try {
      $slider_list = $this->sliderBusiness->list();
    } catch (\Exception $e) {
      return $this->responseMessage->error('获取轮播图列表数据失败');
    }
    return $this->responseMessage->success('获取轮播图列表数据成功', $slider_list);
  }
}