<?php

/* 权限规则控制器 */

namespace app\admin\business;

use app\admin\validate\AuthRule as AuthRuleValidate;
use app\common\model\mysql\AuthRule AS AuthRuleModel;
use think\Exception;
use think\exception\ValidateException;
use app\common\lib\Utils;

class AuthRule
{
  private $authRuleModel = null;
  private $utils = null;
  
  public function __construct()
  {
    $this->authRuleModel = new AuthRuleModel;
    $this->utils = new Utils;
  }
  
  public function getAuthRulePageList($params) {
    $whereAll = [];
    $where = [];
    $whereOr = [];
    if (!empty($params['name'])) {
      $where[] = ['a.title', 'LIKE', "%{$params['name']}%"];
      $whereOr[] = ['a.name', 'LIKE', "%{$params['name']}%"];
    }

    if (count($where) !== 0) {
      $whereAll = [$where, $whereOr];
    }

    return $this->authRuleModel->getAuthRulePageList($whereAll, $params['page_size']);
  }

  public function getAuthRuleList($field='*') {
    $auth_rule_list = $this->authRuleModel->getList($field);
    $temp_auth_rule_list = [];
    $this->utils->getChildrenRule($temp_auth_rule_list, $auth_rule_list, 0);

    return $temp_auth_rule_list;
  }
  
  public function insertAuthRule($data) {
    $time = time();
    $rule_data = array_merge($data, [
      'create_time' => $time,
      'update_time' => $time
    ]);
    try {
      validate(AuthRuleValidate::class)
        ->scene('add')
        ->check($rule_data);
    } catch (ValidateException $e) {
      throw new ValidateException($e->getMessage());
    }
    return $this->authRuleModel->insertOneData($rule_data);
  }
  
  public function editAuthRule($data) {
    $time = time();
    $rule_data = array_merge($data, [
      'update_time' => $time
    ]);
    try {
      validate(AuthRuleValidate::class)
        ->scene('edit')
        ->check($rule_data);
    } catch (ValidateException $e) {
      throw new ValidateException($e->getMessage());
    }
  
    $where = [
      'id' => $data['id']
    ];

    return $this->authRuleModel->updateOne($rule_data, $where);
  }
  
  /**
   * 获取一条权限规则记录
   * @param array $where
   * @param string $fields
   * @return array
   */
  public function getOneAuthRuleInfo($where=[], $fields='*') {
    return $this->authRuleModel->getOne($where, $fields)->toArray();
  }
  
}