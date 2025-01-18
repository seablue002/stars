<template>
  <div class="add-info-form">
    <el-card shadow="never" class="box-card">
      <el-row>
        <el-col :span="10" :xs="24" :sm="12" :md="10">
          <el-form
            ref="formRef"
            :rules="formRules"
            :model="formMdl"
            v-loading="tbIsLoading"
            label-width="100px">
            <el-tabs v-model.trim="activeTabName">
              <el-tab-pane label="基本信息" name="base">
                <el-form-item prop="title" label="标题">
                  <el-input v-model="formMdl.title" :readonly="isReadOnly" clearable placeholder="请输入标题"></el-input>
                </el-form-item>
                <el-form-item prop="sub_title" label="副标题">
                  <el-input v-model="formMdl.sub_title" :readonly="isReadOnly" clearable placeholder="请输入副标题"></el-input>
                </el-form-item>
                <el-form-item prop="column_id" label="所属栏目">
                  <el-cascader v-model="formMdl.column_id" :options="columnListTree" :props="{ checkStrictly: true, value: 'id' }" filterable clearable placeholder="请选择所属栏目" />
                </el-form-item>
                <el-form-item prop="title_url" label="标题url">
                  <el-input v-model="formMdl.title_url" :readonly="isReadOnly" clearable placeholder="请输入标题url"></el-input>
                </el-form-item>
                <el-form-item prop="cover" label="封面">
                  <el-upload
                    :on-change="handleChangeCover"
                    :before-remove="handleBeforeRemoveCover"
                    :auto-upload="false"
                    :file-list="fileList"
                    :show-file-list="false"
                    :limit="1">
                    <template #tip>
                      <Tips title="允许上传不超过500kb的jpg/png图片"></Tips>
                    </template>
                    <div
                      v-if="formMdl.cover"
                      class="upload-pic-view">
                      <el-image
                        style="width: 110px; height: 110px"
                        :src="imgUrl"
                        fit="contain"
                        @click="handleChooseFile">
                      </el-image>
                      <el-icon class="icon-close" @click.stop="handleBeforeRemoveCover"><Close /></el-icon>
                    </div>
                    <el-icon v-else class="icon-upload"><Plus /></el-icon>
                  </el-upload>
                </el-form-item>
                <el-form-item prop="content" label="内容" class="info-content">
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
                <el-form-item prop="publish_time" label="发布时间">
                  <el-date-picker
                    v-model="formMdl.publish_time"
                    type="datetime"
                    placeholder="请选择发布时间"
                    :shortcuts="shortcuts"
                    value-format="YYYY-MM-DD HH:mm:ss">
                  </el-date-picker>
                </el-form-item>
              </el-tab-pane>
              <el-tab-pane label="扩展信息" name="extend">
                <el-form-item prop="label" label="标签设置">
                  <el-tree
                    ref="labelTreeRef"
                    :data="labelBaseInfoList"
                    show-checkbox
                    node-key="id">
                  </el-tree>
                </el-form-item>
              </el-tab-pane>
            </el-tabs>
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
import _ from 'lodash'
import { getTree, formateTree, recursionMachine, getRuleTree as getLabelTree } from '@/utils'
import addInfoRules from '@/validator/info'
import { HTTP_CONFIG, API_HOST, API_VERSION } from '@/config/http'
import { TreeProps, TreeDataKeyMapProps, ROOT_NODE_PID } from '@/components/EditableTree.vue'
import type { UploadFile } from 'element-plus/es/components/upload/src/upload.d'

import {
  InfoProps,
  addInfoApi,
  InfoDetailProps,
  infoDetailApi,
  EditInfoProps,
  editInfoApi,
  DeleteCoverProps,
  deleteCoverApi
} from '@/api/info'
import {
  columnListApi
} from '@/api/column'
import {
  labelBaseInfoListApi
} from '@/api/label'

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
    // tab
    const activeTabName = ref('base')
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
    const picUploadUrl = `${API_HOST}${API_VERSION}upload-info-content-pic`
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
      formMdl.value.content = quillEditorRef.value.getHTML()
    }

    const shortcuts = [
      {
        text: '今天',
        value: new Date()
      },
      {
        text: '昨天',
        value: () => {
          const date = new Date()
          date.setTime(date.getTime() - 3600 * 1000 * 24)
          return date
        }
      },
      {
        text: '一周前',
        value: () => {
          const date = new Date()
          date.setTime(date.getTime() - 3600 * 1000 * 24 * 7)
          return date
        }
      }
    ]
    const formRules = (function () {
      let tempInfo = null
      if (!isReadOnly.value) {
        tempInfo = addInfoRules
      } else {
        tempInfo = null
      }
      return tempInfo
    }())
    const formMdl = ref<EditInfoProps>({
      id: '',
      column_id: '',
      title: '',
      sub_title: '',
      content: '',
      title_url: '',
      cover: '',
      label: [],
      publish_time: ''
    })

    const columnListTree = computed(() => {
      let tempColumnTree = _.cloneDeep(treeData.value)
      recursionMachine(tempColumnTree, (dataItem: any) => {
        if (dataItem.id === formMdl.value.id) {
          dataItem.disabled = true
        }
      })

      if (tempColumnTree.length) {
        tempColumnTree = tempColumnTree[0].children as any
      }

      return tempColumnTree
    })

    // 栏目数据列表
    const treeData = ref<TreeProps[]>([])
    const treeDataKeyMap: TreeDataKeyMapProps = { id: 'id', label: 'name', pid: 'pid', children: 'children' }
    const getColumnList = async () => {
      const { status, data, message } = await columnListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        treeData.value = []
        const treeArr: TreeProps[] = []
        getTree(treeArr, data)
        const allTreeNodeData = [{ id: 0, pid: ROOT_NODE_PID, name: '全部配置栏目', children: treeArr || [] }]
        formateTree((treeData.value as any), allTreeNodeData, (treeDataKeyMap as any))
      } else {
        proxy.message({
          showClose: true,
          message,
          type: 'error',
          duration: 3000
        })
      }
    }
    getColumnList()

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

      const data: InfoProps = {
        column_id: formMdl.value.column_id,
        title: formMdl.value.title,
        sub_title: formMdl.value.sub_title,
        content: formMdl.value.content,
        label: formMdl.value.label,
        title_url: formMdl.value.title_url,
        cover: formMdl.value.cover,
        publish_time: formMdl.value.publish_time
      }

      const formData = new FormData()
      for (const attr in data) {
        formData.append(attr, (data as any)[attr])
      }

      if (props.mode === 'add') {
        loading.value = true
        await addInfo(formData as any)
        loading.value = false
      } else if (props.mode === 'edit') {
        formData.append('id', formMdl.value.id)
        loading.value = true
        await editInfo((formData as any))
        loading.value = false
      }
    }

    // 添加
    const addInfo = async (data: InfoProps) => {
      const { status, message } = await addInfoApi(data)
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
    const editInfo = async (data: EditInfoProps) => {
      const { status, message } = await editInfoApi(data)
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
      const params: InfoDetailProps = {
        id
      }
      const { status, data, message } = await infoDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE && data) {
        let { label_ids: labelIds, cover_url: coverUrl, ...extra } = data
        formMdl.value = {
          ...formMdl.value,
          ...extra,
          cover: coverUrl
        }
        quillEditorRef.value.setHTML(data.content)

        labelIds = labelIds.map((label: any) => {
          return Number(label)
        })
        labelTreeRef.value.setCheckedKeys(labelIds)

        const labellInaccuracyCheckedIds = labelTreeRef.value.getCheckedKeys()
        // 识别出tree组件不精准选中的label标签ids
        const labellErrorCheckedIds = labellInaccuracyCheckedIds.filter((id: number) => !labelIds.includes(id))

        // 取消不精准选中的label标签选中状态
        _.isArray(labellErrorCheckedIds) &&
        labellErrorCheckedIds.forEach((id: number) => {
          labelTreeRef.value.setChecked(id, false)
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
      await getLabelBaseInfoList()
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
      fileList.value = []
      quillEditorRef.value.setText('')
    }

    const labelTreeRef = ref()
    // 获取标签列表
    type LabelProps = { id: string; title: string; }
    const labelBaseInfoList = ref<LabelProps[]>([])
    const getLabelBaseInfoList = async () => {
      const { status, data, message } = await labelBaseInfoListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        getLabelTree(labelBaseInfoList.value, data, 0, { id: 'id', label: 'name', pid: 'pid' })
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 获取选中的标签id
    const getCheckedNodes = () => {
      // 获取选中的节点
      // 获取当前的选中的数据{对象}
      const checkedNodes = labelTreeRef.value.getCheckedNodes(false, true)
      const checkedLabelIds = checkedNodes.map((item: any) => {
        return item.id
      })
      formMdl.value.label = checkedLabelIds
    }

    // 封面图片
    const fileList = ref<UploadFile[]>([])
    const imgUrl = computed(() => {
      const url = proxy.formatPicUrl(formMdl.value.cover)
      return url
    })
    const handleChooseFile = (e: Event) => {
      if (formMdl.value.cover) {
        proxy.message({
          type: 'warning',
          message: '目前只能上传一张图片, 如需上传可先删除',
          duration: 1000
        })
        e.stopPropagation()
      }
    }
    const handleChangeCover = (file: UploadFile) => {
      // 校验图片size大小
      const sizeValide = 1024 * 800
      // 校验类型
      const typeValide = ['image/jpeg', 'image/png']
      if ((file.size as any) > sizeValide) {
        proxy.message({
          message: '图片大小不能超过800KB',
          type: 'warning'
        })
        fileList.value = []
        return
      }
      if (!typeValide.includes((file.raw as any).type)) {
        proxy.message({
          message: '图片类型值允许 JPG、PNG',
          type: 'warning'
        })
        fileList.value = []
        return
      }
      formMdl.value.cover = (file.raw as any)
      fileList.value = [file]
    }
    const handleBeforeRemoveCover = () => {
      proxy.$confirm('确定移除图片吗', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(async () => {
        const isDeleted = await deleteCover()
        if (isDeleted) {
          formMdl.value.cover = ''
          fileList.value = []
        }
      }).catch(() => {
        return false
      })
      return false
    }

    const deleteCover = async () => {
      const params: DeleteCoverProps = {
        info_id: formMdl.value.id
      }
      const { status, message } = await deleteCoverApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        proxy.message.success({
          message,
          duration: 3000
        })
        return true
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
        return false
      }
    }

    return {
      token: computed(() => store.state.user.adminUserInfo.token),
      formRules,
      activeTabName,
      formRef,
      tbIsLoading,
      loading,
      submitBtnTit,
      isReadOnly,
      shortcuts,
      formMdl,
      columnListTree,
      handleSubmit,
      resetForm,
      getDetail,
      // 富文本编辑器配置
      quillEditorRef,
      editorOption: {
        size: 500, // 图片大小，单位为Kb
        accept: 'multipart/form-data,image/png,image/jpeg,image/jpg', // 可上传的图片格式
        placeholder: '此处填写内容',
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
      labelTreeRef,
      labelBaseInfoList,
      fileList,
      imgUrl,
      handleChooseFile,
      handleChangeCover,
      handleBeforeRemoveCover
    }
  }
})
</script>
<style lang="scss" scoped>
.add-info-form {
  :deep(.el-tabs) {
    .el-tabs__content {
      overflow: inherit;
    }
  }
  :deep(.el-upload ){
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    &:hover {
      border-color: #409EFF;
    }
    .icon-upload {
      font-size: 28px;
      color: #8c939d;
      width: 110px;
      height: 110px;
      line-height: 110px;
      text-align: center;
    }
  }
  .info-content {
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
}
</style>
