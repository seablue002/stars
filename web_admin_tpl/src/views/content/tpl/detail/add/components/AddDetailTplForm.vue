<template>
  <div class="add-tpl-form">
    <el-card shadow="never" class="box-card">
      <el-row>
        <el-col :span="10" :xs="24" :sm="12" :md="10">
          <el-form
            ref="formRef"
            :rules="formRules"
            :model="formMdl"
            v-loading="tbIsLoading"
            label-width="120px">
            <el-form-item prop="name" label="模板名称">
              <el-input v-model="formMdl.name" :readonly="isReadOnly" clearable placeholder="请输入模板名称"></el-input>
            </el-form-item>
            <el-form-item prop="content" label="模板内容" class="tpl-content">
              <VAceEditor
                :value="formMdl.content"
                @init="vaceEditorInit"
                lang="html"
                theme="chrome">
              </VAceEditor>
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
import { defineComponent, ref, getCurrentInstance, onMounted, nextTick, computed, inject, shallowRef } from 'vue'
import { useStore } from 'vuex'
import addTplRules from '@/validator/tpl'
import { HTTP_CONFIG } from '@/config/http'
import {
  TplProps,
  addTplApi,
  TplDetailProps,
  tplDetailApi,
  EditTplProps,
  editTplApi
} from '@/api/detailTpl'

import { VAceEditor } from 'vue3-ace-editor'
import 'ace-builds'

import 'ace-builds/src-noconflict/mode-html'
import 'ace-builds/src-noconflict/theme-chrome'

import beautify from 'js-beautify'

export default defineComponent({
  components: {
    VAceEditor
  },
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
        tempTpl = addTplRules
      } else {
        tempTpl = null
      }
      return tempTpl
    }())
    const formMdl = ref<EditTplProps>({
      id: '',
      category_id: '',
      name: '',
      content: ''
    })

    const vaceEditorRef = shallowRef()
    const vaceEditorInit = (instance: any) => {
      vaceEditorRef.value = instance

      instance.getSession().on('change', () => {
        formMdl.value.content = instance.getValue()
      })
    }

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
        name: formMdl.value.name,
        content: vaceEditorRef.value.getValue()
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
        const { content, ...extra } = data
        formMdl.value = {
          ...extra,
          content: beautify.html(content, {
            indent_size: 2,
            indent_char: ' ',
            max_preserve_newline: 0,
            preserve_newlines: false,
            keep_array_indentation: false,
            break_chained_methods: false,
            indent_scripts: 'normal',
            brace_style: 'collapse-preserve-inline',
            space_before_conditional: true
          })
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
      vaceEditorInit,
      handleSubmit,
      resetForm,
      getDetail
    }
  }
})
</script>
<style lang="scss" scoped>
.add-tpl-form {
  .tpl-content {
    :deep(.el-form-item__content) {
      display: block;

      .ace_editor {
        width: 1000px;
        height: 550px;
        min-height: 550px;
        border: 1px solid rgb(204, 204, 204);
        .ace_gutter,
        .ace_scrollbar {
          z-index: 1;
        }
      }
    }
  }
}
</style>
