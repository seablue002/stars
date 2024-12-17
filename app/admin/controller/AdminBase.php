<?php


namespace app\admin\controller;


use app\BaseController;
use think\Exception;
use think\exception\HttpException;
use think\exception\HttpResponseException;

class AdminBase extends BaseController
{
  public $adminUser = null;
  public function initialize()
  {
    parent::initialize();
  }
  
  public function redirect(...$args) {
    throw new HttpResponseException(redirect(...$args));
  }
}