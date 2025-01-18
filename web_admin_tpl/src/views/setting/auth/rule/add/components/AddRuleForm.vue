<template>
  <div class="add-rule-form">
    <el-card shadow="never" class="box-card">
      <el-row>
        <el-col :span="10" :xs="24" :sm="12" :md="10">
          <el-form
            ref="formRef"
            :rules="formRules"
            :model="formMdl"
            v-loading="tbIsLoading"
            label-width="100px">
            <el-form-item prop="title" label="规则名">
              <el-input v-model="formMdl.title" :readonly="isReadOnly" clearable placeholder="请输入规则名"></el-input>
            </el-form-item>
            <el-form-item prop="name" label="规则PATH">
              <el-input v-model="formMdl.name" :readonly="isReadOnly" clearable placeholder="请输入规则PATH"></el-input>
            </el-form-item>
            <el-form-item prop="pid" label="父级规则">
              <el-cascader v-model="formMdl.pid" :options="supRuleList" :props="{ checkStrictly: true, value: 'id' }" filterable clearable placeholder="请选择商品分类" />
            </el-form-item>
            <el-form-item prop="menu_type" label="规则类型">
              <el-select v-model="formMdl.menu_type" filterable clearable placeholder="请选择规则类型">
                <el-option
                  v-for="item in ruleTypeMaps"
                  :key="item.type"
                  :label="item.title"
                  :value="item.type">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item prop="status" label="规则状态">
              <el-switch v-model="formMdl.status" :disabled="isReadOnly" active-text="正常" inactive-text="停用" :active-value="1" :inactive-value="0"></el-switch>
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
import addRuleRules from '@/validator/rule'
import { HTTP_CONFIG } from '@/config/http'
import { getRuleTree, isArray } from '@/utils'
import { ROOT_NODE_PID } from '@/components/EditableTree.vue'
import {
  RuleProps,
  addRuleApi,
  RuleDetailProps,
  ruleDetailApi,
  EditRuleProps,
  editRuleApi,
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
        tempRule = addRuleRules
      } else {
        tempRule = null
      }
      return tempRule
    }())
    const formMdl = ref<EditRuleProps>({
      id: '',
      title: '',
      name: '',
      pid: '',
      menu_type: 1,
      status: 1
    })

    const isNeedRefreshLstPage = inject('isNeedRefreshLstPage') as any

    const handleSubmit = async () => {
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

      const data: RuleProps = {
        title: formMdl.value.title,
        name: formMdl.value.name,
        pid: isArray(formMdl.value.pid) ? formMdl.value.pid[formMdl.value.pid.length - 1] : formMdl.value.pid,
        menu_type: formMdl.value.menu_type,
        status: formMdl.value.status
      }

      if (props.mode === 'add') {
        loading.value = true
        await addRule(data)
        loading.value = false
      } else if (props.mode === 'edit') {
        data.id = formMdl.value.id
        loading.value = true
        await editRule((data as EditRuleProps))
        loading.value = false
      }
    }

    // 添加
    const addRule = async (data: RuleProps) => {
      const { status, message } = await addRuleApi(data)
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
    const editRule = async (data: EditRuleProps) => {
      const { status, message } = await editRuleApi(data)
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
      const params: RuleDetailProps = {
        id
      }
      const { status, data, message } = await ruleDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE && data) {
        const { rule_info: { id, title, name, pid, menu, status } } = data
        formMdl.value = {
          id,
          title,
          name,
          pid,
          menu_type: menu,
          status
        }
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
    }

    // 获取权限规则列表（父级）
    type SupRuleProps = { id: string; title: string; }
    const supRuleList = ref<SupRuleProps[]>([])
    const getRuleBaseInfoList = async () => {
      const { status, data, message } = await ruleBaseInfoListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        supRuleList.value = []
        const allTreeNodeData = [{ id: 0, pid: ROOT_NODE_PID, title: '顶级分类' }, ...data]
        getRuleTree(supRuleList.value, allTreeNodeData, ROOT_NODE_PID)
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }
    return {
      formRules,
      formRef,
      tbIsLoading,
      loading,
      submitBtnTit,
      isReadOnly,
      formMdl,
      handleSubmit,
      resetForm,
      getDetail,
      supRuleList,
      ruleTypeMaps: [
        { type: 0, title: '系统' },
        { type: 1, title: '导航菜单' },
        { type: 2, title: '功能按钮' }
      ]
    }
  }
})
</script>
<style lang="scss" scoped>
</style>
