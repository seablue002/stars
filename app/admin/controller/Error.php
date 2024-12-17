<?php


namespace app\admin\controller;


use think\facade\View;

class Error
{
  public function error () {
    return View::fetch();
  }
}
