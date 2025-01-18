<template>
  <div class="add-role-form">
    <el-card shadow="never" class="box-card">
      <el-row>
        <el-col :span="10" :xs="24" :sm="12" :md="10">
          <el-form
            ref="formRef"
            :rules="formRules"
            :model="formMdl"
            v-loading="tbIsLoading"
            label-width="100px">
            <el-form-item prop="title" label="角色名">
              <el-input v-model="formMdl.title" :readonly="isReadOnly" clearable placeholder="请输入角色名"></el-input>
            </el-form-item>
            <el-form-item prop="status" label="角色状态">
              <el-switch v-model="formMdl.status" :disabled="isReadOnly" active-text="正常" inactive-text="停用" :active-value="1" :inactive-value="0"></el-switch>
            </el-form-item>
            <el-form-item prop="rule" label="权限赋予">
              <el-tree
                ref="ruleTreeRef"
                :data="ruleBaseInfoList"
                show-checkbox
                node-key="id">
              </el-tree>
            </el-form-item>
            <el-form-item class="form-footer-opt-btns" v-if="['add', 'edit'].includes(mode)">
              <el-button @click="resetForm">重置</el-button>
              <el-button type="primary" :loading="loading" @click="handleSubmit">{{ submitBtnTit }}</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, getCurrentInstance, onMounted, nextTick, computed, inject } from 'vue'
import addRoleRules from '@/validator/role'
import { HTTP_CONFIG } from '@/config/http'
import { isArray, getRuleTree } from '@/utils'
import {
  RoleProps,
  addRoleApi,
  RoleDetailProps,
  roleDetailApi,
  EditRoleProps,
  editRoleApi
} from '@/api/role'
import {
  ruleBaseInfoListApi
} from '@/api/rule'
export default defineComponent({
  props: {
    mode: {
      type: String,
      default: 'add'
    }
  },
  setup (props) {
    const { proxy } = (getCurrentInstance() as any)
    // 表单
    const formRef = ref()
    const ruleTreeRef = ref()
    const tbIsLoading = ref(false)
    const loading = ref(false)
    const submitBtnTit = computed(() => {
      return props.mode === 'edit' ? '更新' : '添加'
    })
    const isReadOnly = computed(() => {
      return ['detail'].includes(props.mode)
    })
    const formRules = (function () {
      let tempRule = null
      if (!isReadOnly.value) {
        tempRule = addRoleRules
      } else {
        tempRule = null
      }
      return tempRule
    }())
    const formMdl = ref<EditRoleProps>({
      id: '',
      title: '',
      status: 1,
      rule: []
    })

    const isNeedRefreshLstPage = inject('isNeedRefreshLstPage') as any

    const handleSubmit = async () => {
      getCheckedNodes()
      const validateStatus = await new Promise((resolve) => {
        formRef.value.validate((valid: any) => {
          if (valid) {
            resolve(true)
          } else {
            resolve(false)
            return false
          }
        })
      })

      if (!validateStatus || loading.value) {
        return false
      }

      const data: RoleProps = {
        title: formMdl.value.title,
        status: formMdl.value.status,
        rule: formMdl.value.rule
      }

      if (props.mode === 'add') {
        loading.value = true
        await addRole(data)
        loading.value = false
      } else if (props.mode === 'edit') {
        data.id = formMdl.value.id
        loading.value = true
        await editRole((data as EditRoleProps))
        loading.value = false
      }
    }

    // 添加
    const addRole = async (data: RoleProps) => {
      const { status, message } = await addRoleApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        resetForm()
        isNeedRefreshLstPage.value = true
        proxy.message.success({
          message,
          duration: 3000
        })
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 编辑
    const editRole = async (data: EditRoleProps) => {
      const { status, message } = await editRoleApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        isNeedRefreshLstPage.value = true
        proxy.message.success({
          message,
          duration: 3000
        })
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 详情
    const getDetail = async (id: any) => {
      const params: RoleDetailProps = {
        id
      }
      const { status, data, message } = await roleDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE && data) {
        const { role_info: { id, title, status }, roles_rule_list: ruleList } = data
        formMdl.value = {
          ...formMdl.value,
          id,
          title,
          status
        }

        const roleRuleIds = ruleList.map((rule: any) => {
          return rule.rule_id
        })

        ruleTreeRef.value.setCheckedKeys(roleRuleIds)

        const roleRuleInaccuracyCheckedIds = ruleTreeRef.value.getCheckedKeys()
        // 识别出tree组件不精准选中的rule权限规则ids
        const roleRuleErrorCheckedIds = roleRuleInaccuracyCheckedIds.filter((id: number) => !roleRuleIds.includes(id))

        // 取消不精准选中的rule权限规则选中状态
        isArray(roleRuleErrorCheckedIds) &&
        roleRuleErrorCheckedIds.forEach((id: number) => {
          ruleTreeRef.value.setChecked(id, false)
        })
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }
    onMounted(async () => {
      tbIsLoading.value = true
      await getRuleBaseInfoList()
      nextTick(async () => {
        if (formMdl.value.id && ['edit', 'detail'].includes(props.mode)) {
          await getDetail(formMdl.value.id)
        }
      })
      tbIsLoading.value = false
    })

    // 重置
    const resetForm = () => {
      formRef.value.resetFields()
      ruleTreeRef.value.setCheckedKeys([])
    }

    // 获取权限规则列表
    type RuleProps = { id: string; title: string; }
    const ruleBaseInfoList = ref<RuleProps[]>([])
    const getRuleBaseInfoList = async () => {
      const { status, data, message } = await ruleBaseInfoListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        getRuleTree(ruleBaseInfoList.value, data)
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 获取选中的权限规则id
    const getCheckedNodes = () => {
      // 获取选中的节点
      // 获取当前的选中的数据{对象}
      const checkedNodes = ruleTreeRef.value.getCheckedNodes(false, true)
      const checkedRuleIds = checkedNodes.map((item: any) => {
        return item.id
      })
      formMdl.value.rule = checkedRuleIds
    }
    return {
      formRules,
      formRef,
      ruleTreeRef,
      tbIsLoading,
      loading,
      submitBtnTit,
      isReadOnly,
      formMdl,
      handleSubmit,
      resetForm,
      getDetail,
      ruleBaseInfoList,
      getCheckedNodes
    }
  }
})
</script>
<style lang="scss" scoped>
.add-role-form {
  .el-tree {
    min-width: 350px;
    :deep(.el-tree__empty-text) {
      top: 0;
      left: 0;
      transform: none;
    }
  }
}
</style>
