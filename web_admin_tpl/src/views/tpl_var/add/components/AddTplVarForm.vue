<template>
  <div class="add-tpl-var-form">
    <el-card shadow="never" class="box-card">
      <el-row>
        <el-col :span="10" :xs="24" :sm="12" :md="10">
          <el-form
            ref="formRef"
            :rules="formRules"
            :model="formMdl"
            v-loading="tbIsLoading"
            label-width="120px">
            <el-form-item prop="var_key" label="变量名">
              <span></span>
              <el-input v-model="formMdl.var_key" :readonly="isReadOnly" clearable placeholder="请输入变量名">
                <template #prepend>[!--temp_var.</template>
                <template #append>--!]</template>
              </el-input>
              <span></span>
            </el-form-item>
            <el-form-item prop="var_name" label="变量标识">
              <el-input v-model="formMdl.var_name" :readonly="isReadOnly" clearable placeholder="请输入变量标识"></el-input>
            </el-form-item>
            <el-form-item prop="var_value" label="模板变量内容" class="tpl-var_value">
              <el-input v-model="formMdl.var_value" type="textarea" :rows="10"></el-input>
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
import { useStore } from 'vuex'
import addTplVarRules from '@/validator/tplVar'
import { HTTP_CONFIG } from '@/config/http'
import {
  TplProps,
  addTplApi,
  TplDetailProps,
  tplDetailApi,
  EditTplProps,
  editTplApi
} from '@/api/tplVar'

export default defineComponent({
  props: {
    mode: {
      type: String,
      default: 'add'
    }
  },
  setup (props) {
    const { proxy } = (getCurrentInstance() as any)
    const store = useStore()
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
      let tempTpl = null
      if (!isReadOnly.value) {
        tempTpl = addTplVarRules
      } else {
        tempTpl = null
      }
      return tempTpl
    }())
    const formMdl = ref<EditTplProps>({
      id: '',
      category_id: '',
      var_key: '',
      var_name: '',
      var_value: ''
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

      const data: TplProps = {
        category_id: formMdl.value.category_id,
        var_key: formMdl.value.var_key,
        var_name: formMdl.value.var_name,
        var_value: formMdl.value.var_value
      }

      if (props.mode === 'add') {
        loading.value = true
        await addTpl(data)
        loading.value = false
      } else if (props.mode === 'edit') {
        data.id = formMdl.value.id
        loading.value = true
        await editTpl((data as EditTplProps))
        loading.value = false
      }
    }

    // 添加
    const addTpl = async (data: TplProps) => {
      const { status, message } = await addTplApi(data)
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
    const editTpl = async (data: EditTplProps) => {
      const { status, message } = await editTplApi(data)
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
      const params: TplDetailProps = {
        id
      }
      const { status, data, message } = await tplDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE && data) {
        formMdl.value = data
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }
    onMounted(async () => {
      tbIsLoading.value = true
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

    return {
      token: computed(() => store.state.user.adminUserInfo.token),
      formRules,
      formRef,
      tbIsLoading,
      loading,
      submitBtnTit,
      isReadOnly,
      formMdl,
      handleSubmit,
      resetForm,
      getDetail
    }
  }
})
</script>
<style lang="scss" scoped>
.add-tpl-var-form {
  .tpl-var_value {
    .el-textarea {
      .el-textarea__inner {
        width: 1000px;
      }
    }
  }
}
</style>
