<?php


namespace app\home\business;

use think\facade\Db;

class Slider
{
  public function list () {
    return Db::table('web_slider')->order(['create_time' => 'DESC'])->select();
  }
}