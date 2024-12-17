<?php


namespace app\admin\business;


use app\admin\validate\Slider as SliderValidate;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Db;

class Slider
{
  public function getSliderList ($params) {
    $where = '';
    if ($params['name']) {
      $where .= " name LIKE '%{$params['name']}%' AND ";
    }
    $where .= '1 = 1';

    $pageSize = $params['page_size'];
    $offset = ($params['page'] - 1) * $pageSize;
    $limit = " LIMIT {$offset}, {$pageSize}";

    $slider_list_sql = "SELECT * FROM web_slider WHERE {$where} ORDER BY sort DESC {$limit}";

    $slider_list = Db::query($slider_list_sql);

    $slider_list_total_sql = "SELECT COUNT(id) total FROM web_slider WHERE {$where}";
    $slider_list_total = Db::query($slider_list_total_sql);

    return [
      'data' => $slider_list,
      'total' => $slider_list_total[0]['total']
    ];
  }

  public function insertSlider ($params) {
    $params = array_merge($params, [
      'create_time' => time()
    ]);
    try {
      validate(SliderValidate::class)
        ->scene('add')
        ->check($params);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    return Db::table('web_slider')->insert($params);
  }

  public function updateSlider ($params) {
    $params = array_merge($params, [
      'update_time' => time()
    ]);
    try {
      validate(SliderValidate::class)
        ->scene('update')
        ->check($params);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    return Db::table('web_slider')->update($params);
  }

  public function delete ($params) {
    try {
      validate(SliderValidate::class)
        ->scene('delete')
        ->check($params);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    $slider_path = Db::table('web_slider')->field('url')->find($params);

    $success_delete_len = Db::table('web_slider')->delete($params);
    if ($success_delete_len) {
      // 删除磁盘上的对应轮播图文件
      unlink(app()->getRootPath() . 'public/storage/' . $slider_path['url']);
    }
  }

  public function detail ($params) {
    try {
      validate(SliderValidate::class)
        ->scene('detail')
        ->check($params);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    return Db::table('web_slider')->field(['id', 'name', 'page_url', 'url AS slider', 'sort', 'status', 'remarks'])->find($params);
  }
}