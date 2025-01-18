<template>
  <div class="add-admin-user-form">
    <el-card shadow="never" class="box-card">
      <el-row>
        <el-col :span="10" :xs="24" :sm="12" :md="10">
          <el-form
            ref="formRef"
            :rules="formRules"
            :model="formMdl"
            v-loading="tbIsLoading"
            label-width="100px">
            <el-form-item prop="username" label="管理员账号">
              <el-input v-model="formMdl.username" :readonly="isReadOnly" clearable placeholder="请输入管理员账号"></el-input>
            </el-form-item>
            <el-form-item prop="password" label="登录密码">
              <el-input v-model="formMdl.password" clearable placeholder="请输入登录密码"></el-input>
            </el-form-item>
            <el-form-item prop="role" label="角色">
              <el-checkbox-group v-model="formMdl.role">
                <el-checkbox
                  v-for="role in roleList"
                  :key="role.id"
                  :label="role.id">
                  {{ role.title }}
                </el-checkbox>
              </el-checkbox-group>
            </el-form-item>
            <el-form-item prop="status" label="账号状态">
              <el-switch v-model="formMdl.status" :disabled="isReadOnly" active-text="正常" inactive-text="停用" :active-value="Number(accountStatus.SYS_YES?.value)" :inactive-value="Number(accountStatus.SYS_NO?.value)"></el-switch>
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
import { addAdminUserRules } from '@/validator/adminUser'
import { HTTP_CONFIG } from '@/config/http'
import md5 from 'js-md5'
import useDicData from '@/hooks/useDicData'
import {
  AdminUserProps,
  addAdminUserApi,
  AdminUserDetailProps,
  adminUserDetailApi,
  EditAdminUserProps,
  editAdminUserApi
} from '@/api/adminUser'
import { roleBaseInfoListApi } from '@/api/role'
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
        if (props.mode !== 'add') {
          // eslint-disable-next-line
          const { password, ...extraRules } = addAdminUserRules
          tempRule = extraRules
        } else {
          tempRule = addAdminUserRules
        }
      } else {
        tempRule = null
      }
      return tempRule
    }())
    const formMdl = ref<EditAdminUserProps>({
      id: '',
      username: '',
      password: '',
      status: 1,
      role: []
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

      const data: AdminUserProps = {
        username: formMdl.value.username,
        password: md5(formMdl.value.password || ''),
        status: formMdl.value.status,
        role: formMdl.value.role
      }

      if (props.mode === 'add') {
        loading.value = true
        await addAdminUser(data)
        loading.value = false
      } else if (props.mode === 'edit') {
        data.id = formMdl.value.id
        loading.value = true
        await editAdminUser((data as EditAdminUserProps))
        loading.value = false
      }
    }

    // 添加
    const addAdminUser = async (data: AdminUserProps) => {
      const { status, message } = await addAdminUserApi(data)
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
    const editAdminUser = async (data: EditAdminUserProps) => {
      const { status, message } = await editAdminUserApi(data)
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
      const params: AdminUserDetailProps = {
        id
      }
      tbIsLoading.value = true
      const { status, data, message } = await adminUserDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE && data.admin_info) {
        const { id, username, status } = data.admin_info
        const { admin_role: role } = data
        formMdl.value = {
          id,
          username,
          status,
          role
        }
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
      tbIsLoading.value = false
    }
    onMounted(() => {
      nextTick(() => {
        if (formMdl.value.id && ['edit', 'detail'].includes(props.mode)) {
          getDetail(formMdl.value.id)
        }
      })
    })

    // 重置
    const resetForm = () => {
      formRef.value.resetFields()
    }

    // 获取角色列表
    type RoleProps = { id: string; title: string; }
    const roleList = ref<RoleProps[]>([])
    const getRoleBaseInfoList = async () => {
      const { status, data, message } = await roleBaseInfoListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        roleList.value = data
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }
    onMounted(() => {
      getRoleBaseInfoList()
    })

    // 是否状态字典数据
    const accountStatus = ref<{[key: string]: any}>({})
    onMounted(async () => {
      formMdl.value.status = 1
      const { SYS_YES_NO: sysYesNo } = await useDicData(['SYS_YES_NO'])
      accountStatus.value = sysYesNo
    })

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
      roleList,
      accountStatus
    }
  }
})
</script>
<style lang="scss" scoped>
</style>
