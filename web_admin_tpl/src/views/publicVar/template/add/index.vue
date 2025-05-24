<template>
  <div class="page-container page-container--bg">
    <el-card v-loading="loadding" shadow="never" class="border-0">
      <el-form
        ref="formRef"
        :rules="formRules"
        :model="formMdl"
        label-width="100px"
      >
        <el-form-item prop="var_key" label="变量名称">
          <!-- <el-input
            v-model="formMdl.var_key"
            :readonly="isDetailMode"
            clearable
            placeholder="请输入变量名称"
            class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
          ></el-input> -->
          <el-input
            v-model="formMdl.var_key"
            :readonly="isDetailMode"
            clearable
            placeholder="请输入变量名称"
            class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
          >
            <template #prepend>[!--temp_var.</template>
            <template #append>--!]</template>
          </el-input>
        </el-form-item>
        <el-form-item v-if="formMdl.var_key" label="变量调用语法">
          <el-row class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5">
            <el-col :span="20">
              <el-input :value="`[!--temp_var.${formMdl.var_key}--!]`" readonly>
              </el-input>
            </el-col>
            <el-col :span="4">
              <el-button
                size="small"
                class="ml-[8px]"
                @click="handleCopyText(`[!--temp_var.${formMdl.var_key}--!]`)"
              >
                点击复制
              </el-button>
            </el-col>
          </el-row>
        </el-form-item>
        <el-form-item prop="var_name" label="变量标识">
          <el-input
            v-model="formMdl.var_name"
            :readonly="isDetailMode"
            clearable
            placeholder="请输入变量标识"
            class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
          ></el-input>
        </el-form-item>
        <el-form-item prop="var_value">
          <template #label>
            变量内容
            <el-popover placement="top-start" width="auto" trigger="hover">
              <template #default>
                <p class="ml-[12px]">模板变量对应的html代码</p>
              </template>
              <template #reference>
                <i class="ri-question-line ml-[4px]"></i>
              </template>
            </el-popover>
          </template>

          <VAceEditor
            :value="formMdl.var_value"
            lang="html"
            theme="chrome"
            class="sm:w-1/1 md:w-1/1 lg:w-8/9 xl:w-8/9 2xl:w-2/3"
            @init="vaceContentEditorInit"
          >
          </VAceEditor>
        </el-form-item>

        <el-form-item v-if="isCreateMode || isEditMode">
          <el-button @click="handleReset">重置</el-button>
          <el-button type="primary" @click="handleSubmit">提交</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  shallowRef,
  onMounted,
  onActivated,
  watch,
} from 'vue'
import { cloneDeep, debounce } from 'lodash-es'
import { useClipboard } from '@vueuse/core'
import useAddPage from '@/hooks/business/useAddPage'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import useCachedPageJudgmentRefresh from '@/hooks/useCachedPageJudgmentRefresh'

import { VAceEditor } from 'vue3-ace-editor'
import 'ace-builds'

import 'ace-builds/src-noconflict/mode-html'
import 'ace-builds/src-noconflict/theme-chrome'

import beautify from 'js-beautify'

import rules from './rules'

export default defineComponent({
  components: {
    VAceEditor,
  },
  setup(props) {
    const { $api, $apiCode, $message, route } = useCurrentInstance()
    const { isCreateMode, isEditMode, isDetailMode } = useAddPage(props)
    const { isNeedRefreshPage, pageRefreshRecode } =
      useCachedPageJudgmentRefresh()
    pageRefreshRecode()

    const { copy, isSupported, copied } = useClipboard()

    // 表单
    const loadding = ref(false)
    const formRef = ref(null)
    const formRules = (() => {
      if (!isDetailMode.value) {
        return rules
      }
      return null
    })()

    const initFormMdl = {
      id: '',
      category_id: '',
      var_key: '',
      var_name: '',
      var_value: '',
    }
    const formMdl = ref({ ...cloneDeep(initFormMdl) })

    const tplId = route.query.id

    onMounted(() => {
      if (tplId && (isEditMode.value || isDetailMode.value)) {
        formMdl.value.id = tplId
      }
    })
    onActivated(() => {
      if (tplId && (isEditMode.value || isDetailMode.value)) {
        formMdl.value.id = tplId
      }
    })

    const vaceContentEditorRef = shallowRef()
    const vaceContentEditorInit = (instance) => {
      vaceContentEditorRef.value = instance
      instance.getSession().on('change', () => {
        formMdl.value.var_value = instance.getValue()
      })
    }

    const handleSubmit = async () => {
      const validate = await new Promise((resolve) => {
        formRef.value.validate(resolve)
      })

      if (!validate) {
        return
      }

      const data = {
        category_id: formMdl.value.category_id,
        var_key: formMdl.value.var_key,
        var_name: formMdl.value.var_name,
        var_value: vaceContentEditorRef.value.getValue(),
      }

      const formData = new FormData()
      // 数据组装
      Object.keys(data).forEach((attr) => {
        formData.append(attr, data[attr])
      })

      if (isCreateMode.value) {
        loadding.value = true
        await addTpl(formData)
        loadding.value = false
      } else if (isEditMode.value) {
        formData.append('id', formMdl.value.id)
        loadding.value = true
        await editTpl(formData)
        loadding.value = false
      }
    }

    const handleReset = () => {
      formRef.value.resetFields()
    }

    // 复制变量调用语法
    const handleCopyText = debounce((text) => {
      if (!isSupported.value) {
        $message({
          message: '当前浏览器不支持此复制功能',
          type: 'warnning',
        })
        return
      }
      copy(text)
    }, 50)

    watch(
      () => copied.value,
      (newVal) => {
        if (newVal) {
          $message({
            message: '复制成功',
            type: 'success',
          })
        }
      }
    )

    // 添加
    const addTpl = async (params) => {
      const apiRes = await $api.teplateVar.addTplVarApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        handleReset()
        isNeedRefreshPage.value = true
        $message.success({
          message,
          duration: 3000,
        })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 编辑
    const editTpl = async (params) => {
      const apiRes = await $api.teplateVar.editTplVarApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        isNeedRefreshPage.value = true

        $message.success({
          message,
          duration: 3000,
        })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 详情
    const getDetail = async (id) => {
      const params = {
        id,
      }
      const apiRes = await $api.teplateVar.tplVarDetailApi(params)
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        const { var_value: varValue, ...extra } = data
        formMdl.value = {
          ...extra,
          var_value: beautify.html(varValue, {
            indent_size: 2,
            indent_char: ' ',
            max_preserve_newline: 0,
            preserve_newlines: false,
            keep_array_indentation: false,
            break_chained_methods: false,
            indent_scripts: 'normal',
            brace_style: 'collapse',
            space_before_conditional: true,
          }),
        }
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    onMounted(async () => {
      if (formMdl.value.id && (isEditMode.value || isDetailMode.value)) {
        loadding.value = true
        await getDetail(formMdl.value.id)
        loadding.value = false
      }
    })

    return {
      isDetailMode,
      isCreateMode,
      isEditMode,
      loadding,
      formRef,
      formRules,
      formMdl,
      vaceContentEditorInit,
      handleSubmit,
      handleReset,
      handleCopyText,
    }
  },
})
</script>
<style lang="scss" scoped>
:deep(.el-form-item__content) {
  display: block;
  .ace_editor {
    height: 700px;
    min-height: 700px;
    border: 1px solid rgb(204, 204, 204);

    .ace_gutter,
    .ace_scrollbar {
      z-index: 1;
    }
  }
}
</style>
