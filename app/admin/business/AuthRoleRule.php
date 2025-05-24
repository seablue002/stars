<?php
namespace app\admin\business;


use app\admin\validate\AuthRoleRule as AuthRoleRuleValidate;
use app\common\model\mysql\AuthRoleRule as AuthRoleRuleModel;
use think\exception\ValidateException;
use think\Exception;
use app\common\exception\ApiException;

class AuthRoleRule
{
  private $authRoleRuleModel = null;

  public function __construct()
  {
    $this->authRoleRuleModel = new AuthRoleRuleModel;
  }

  public function insertAuthRoleRule($role_id, $auth_rule_ids_data)
  {
    $data = [];
    foreach ($auth_rule_ids_data as $rule_id) {
      $temp = [
        'role_id' => $role_id,
        'rule_id' => $rule_id,
      ];

      $data[] = $temp;
      try {
        validate(AuthRoleRuleValidate::class)
          ->scene('add')
          ->check($temp);
      } catch (ValidateException $e) {
        throw new ApiException($e->getMessage());
        break;
      }
    }

    return $this->authRoleRuleModel->insertAllData($data);
  }

  /**
   * 删除角色的权限规则
   * @param $role_id 角色id
   * @param $rule_ids_data 权限规则id
   * @return bool
   * @throws \Exception
   */
  public function deleteAuthRoleRule($role_id, $rule_ids_data)
  {
    return $this
      ->authRoleRuleModel
      ->where([['role_id', '=', $role_id], ['rule_id', 'in', $rule_ids_data]])
      ->delete();
  }

  public function getRolesRule($role_id)
  {
    return $this->authRoleRuleModel->getRolesRule($role_id)->toArray();
  }

  /**
   * 查询当前角色已有的正常状态的权限规则
   * @param $role_id 角色id
   * @param string $fields 需要查询的字段
   * @return mixed
   */
  public function getNormalAuthRuleList($role_id, $fields = '*')
  {
    return $this->authRoleRuleModel->getNormalAuthRuleList($role_id, $fields);
  }

  public function editAuthRule($role_id, $rule_ids_data)
  {
    // 查询当前角色已有的正常状态的权限规则rule_id
    $normal_rule_ids = $this->getNormalAuthRuleList($role_id, 'rule_id');

    // 根据差集，获取需要做新增的权限规则ids需要做删除的权限规则ids
    $needAddRuleIds = array_diff($rule_ids_data, $normal_rule_ids);
    $needDelRuleIds = array_diff($normal_rule_ids, $rule_ids_data);

    // steps1、如果rule新增数组内元素个数不为0,做新增
    $needAddRuleIdsLLen = count($needAddRuleIds);
    if ($needAddRuleIdsLLen > 0) {
      $insert_success_len = $this->insertAuthRoleRule($role_id, $needAddRuleIds);
      if ($insert_success_len !== $needAddRuleIdsLLen) {
        throw new ApiException('修改角色拥有权限时，插入角色权限规则信息失败');
      }
    }

    // steps2、如果rule删除数组内元素个数不为0,做新增
    $needDelRuleIdsLLen = count($needDelRuleIds);
    if ($needDelRuleIdsLLen > 0) {
      $del_success_len = $this->deleteAuthRoleRule($role_id, $needDelRuleIds);
      if ($del_success_len !== $needDelRuleIdsLLen) {
        throw new ApiException('修改角色拥有权限时，删除角色权限规则信息失败'.$del_success_len.'---'.json_encode($needDelRuleIds));
      }
    }
  }


}