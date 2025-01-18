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
            <el-form-item prop="content" label="页面模板内容" class="tpl-content">
              <QuillEditor ref="quillEditorRef" :content="formMdl.content" @update:content="handleContentChange" :options="editorOption" theme="snow" class="quill-editor"/>
              <!-- S 富文本编辑器图片添加辅助 -->
              <el-upload
                style="display: none"
                multiple
                :headers="{ Authorization: token }"
                :action="picUploadUrl"
                :file-list="editorUploadFileList"
                :on-success="handleSuccessEditorUpload"
                list-type="picture">
                <el-button id="editorPicUploadBtn" size="small" type="primary">选择简介中的图片</el-button>
              </el-upload>
              <!-- E 富文本编辑器图片添加辅助 -->
            </el-form-item>
            <el-form-item prop="item_var" label="列表内容模板（list.item）" class="tpl-item-var">
              <QuillEditor ref="quillEditorListVarRef" :content="formMdl.item_var" @update:content="handleListVarChange" :options="editorOption" theme="snow" class="quill-editor"/>
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
import addTplRules from '@/validator/tpl'
import { HTTP_CONFIG, API_HOST, API_VERSION } from '@/config/http'
import {
  TplProps,
  addTplApi,
  TplDetailProps,
  tplDetailApi,
  EditTplProps,
  editTplApi
} from '@/api/listTpl'

import Quill from 'quill'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import ImageResize from 'quill-image-resize-module'
Quill.register('modules/imageResize', ImageResize)

// 工具栏配置
const toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],
  ['blockquote', 'code-block'],
  [{ header: 1 }, { header: 2 }],
  [{ list: 'ordered' }, { list: 'bullet' }],
  [{ script: 'sub' }, { script: 'super' }],
  [{ indent: '-1' }, { indent: '+1' }],
  [{ direction: 'rtl' }],
  [{ size: ['small', false, 'large', 'huge'] }],
  [{ header: [1, 2, 3, 4, 5, 6, false] }],
  [{ color: [] }, { background: [] }],
  [{ font: [] }],
  [{ align: [] }],
  ['image'],
  ['link'],
  ['clean']
]
export default defineComponent({
  components: {
    QuillEditor
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

    const quillEditorRef = ref()
    const quillEditorListVarRef = ref()
    const picUploadUrl = `${API_HOST}${API_VERSION}upload-tpl-content-pic`
    const editorUploadFileList = ref([])
    const handleSuccessEditorUpload = (response: any) => {
      // 获取富文本组件实例
      const quill = quillEditorRef.value.getQuill()
      const { status, data } = response
      // result为图片上传后在服务器中所处地址
      if (status === HTTP_CONFIG.API_SUCCESS_CODE && data) {
        // 获取光标所在位置
        const length = quill.getSelection().index
        // 插入图片，url为服务器返回的图片链接地址
        quill.insertEmbed(length, 'image', data)
        // 调整光标到最后
        quill.setSelection(length + 1)
      } else {
        proxy.message({
          type: 'error',
          message: '图片插入失败',
          duration: 1500
        })
      }
    }
    const handleContentChange = () => {
      formMdl.value.content = quillEditorRef.value.getText()
    }

    const handleListVarChange = () => {
      formMdl.value.item_var = quillEditorListVarRef.value.getText()
    }

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
      content: '',
      item_var: ''
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
        name: formMdl.value.name,
        content: formMdl.value.content,
        item_var: formMdl.value.item_var
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
        quillEditorRef.value.setText(data.content)
        quillEditorListVarRef.value.setText(data.item_var)
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
      quillEditorRef.value.setText('')
      quillEditorListVarRef.value.setText('')
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
      getDetail,
      // 富文本编辑器配置
      quillEditorRef,
      quillEditorListVarRef,
      editorOption: {
        size: 500, // 图片大小，单位为Kb
        accept: 'multipart/form-data,image/png,image/jpeg,image/jpg', // 可上传的图片格式
        placeholder: '此处填写模板内容',
        modules: {
          toolbar: {
            container: toolbarOptions, // 工具栏
            handlers: {
              image: function (value: any) {
                if (value) {
                  // 调用elementUI图片上传
                  (document.querySelector('#editorPicUploadBtn') as HTMLElement).click()
                } else {
                  const quill = quillEditorRef.value.getQuill()
                  quill.format('image', false)
                }
              }
            }
          },
          imageResize: {
            displayStyles: {
              backgroundColor: 'black',
              border: 'none',
              color: 'white'
            },
            modules: ['Resize', 'DisplaySize', 'Toolbar']
          }
        }
      },
      picUploadUrl,
      editorUploadFileList,
      handleSuccessEditorUpload,
      handleContentChange,
      handleListVarChange
    }
  }
})
</script>
<style lang="scss" scoped>
.add-tpl-form {
  .tpl-content, .tpl-item-var {
    :deep(.el-form-item__content) {
      display: block;
      .ql-toolbar, .quill-editor {
        width: 1000px;
      }
      .quill-editor {
        height: 400px;
        min-height: 400px;
      }
    }
  }
  .tpl-item-var {
    :deep(.el-form-item__content) {
      .quill-editor {
        height: 200px;
        min-height: 200px;
      }
    }
  }
}
</style>
