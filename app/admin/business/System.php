<?php


namespace app\admin\business;

use app\admin\validate\SystemArticle as ArticleValidate;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\Config;

class System
{
  public function getArticleList ($params) {
    $where = '1 = 1';
    $pageSize = $params['page_size'];
    $offset = ($params['page'] - 1) * $pageSize;
    $limit = " LIMIT {$offset}, {$pageSize}";

    $article_list_sql = "SELECT * FROM web_system_article WHERE {$where} ORDER BY create_time DESC {$limit}";
    $article_list = Db::query($article_list_sql);

    $article_list_total_sql = "SELECT COUNT(id) total FROM web_system_article WHERE {$where}";
    $article_list_total = Db::query($article_list_total_sql);

    return [
      'data' => $article_list,
      'total' => $article_list_total[0]['total']
    ];
  }

  public function updateArticle ($params) {
    try {
      validate(ArticleValidate::class)
        ->scene('update')
        ->check($params);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    $success_update_len = Db::table('web_system_article')->update($params);
  }

  public function articleDetail ($params) {
    try {
      validate(ArticleValidate::class)
        ->scene('detail')
        ->check($params);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    return Db::table('web_system_article')->find($params);
  }

  public function getDicList ($dicTypeKeys) {
    $dicList = [];
    foreach ($dicTypeKeys as $dicTypeKey) {
      $temp = [];
      $sql = "SELECT
            d.dic_key code
            , d.dic_value value
            , d.dic_label label
            FROM web_dic d, web_dic_type dt
            WHERE dt.dic_type_key = '{$dicTypeKey}'
            AND dt.status = 1
            AND d.dic_type_id = dt.id
            AND d.status = 1
            ORDER BY d.sort ASC";
      $temp[$dicTypeKey] = Db::query($sql);

      $keys = array_map(function ($dic) {
        return $dic['code'];
      }, $temp[$dicTypeKey]);

      $values = array_values($temp[$dicTypeKey]);
      $dicList[$dicTypeKey] = array_combine($keys, $values);
    }


    return $dicList;
  }

  public function getRegionList ($pid) {
    $sql = "SELECT id, sname FROM web_region WHERE pid = {$pid}";
    $list = Db::query($sql);
    return $list;
  }

}