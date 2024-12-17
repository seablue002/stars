<?php


namespace app\common\model\mysql;


class AuthRoleRule extends BaseModel
{
  public function getRolesRule ($role_id) {
    return $this
      ->where(['role_id' => $role_id])
      ->select();
  }

  /**
   * 查询当前角色已有的权限规则
   * @param $role_id 角色id
   * @param string $fields 需要查询的字段
   * @return mixed
   */
  public function getNormalAuthRuleList($role_id, $fields = '*')
  {
    return $this
      ->where(['role_id' => $role_id])
      ->column($fields);
  }

}