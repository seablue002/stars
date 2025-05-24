<template>
  <div class="base-form">
    <el-form
      ref="formRef"
      :rules="formRules"
      :model="formMdl"
      label-width="100px"
    >
      <el-form-item prop="title" label="标题">
        <el-input
          v-model="formMdl.title"
          :readonly="isDetailMode"
          clearable
          placeholder="请输入标题"
          class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
        ></el-input>
      </el-form-item>
      <el-form-item prop="sub_title" label="副标题">
        <template #label>
          副标题
          <el-popover placement="top-start" width="auto" trigger="hover">
            <template #default>
              <p class="ml-[12px]">可用于展示简介、描述信息</p>
            </template>
            <template #reference>
              <i class="ri-question-line ml-[4px]"></i>
            </template>
          </el-popover>
        </template>
        <el-input
          v-model="formMdl.sub_title"
          :readonly="isDetailMode"
          clearable
          placeholder="请输入副标题"
          class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
        ></el-input>
      </el-form-item>
      <el-form-item prop="column_id" label="所属栏目">
        <el-cascader
          v-model="formMdl.column_id"
          :disabled="isDetailMode"
          :options="columnListTree"
          :props="{ checkStrictly: true, value: 'id' }"
          filterable
          clearable
          placeholder="请选择所属栏目"
          class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
        />
      </el-form-item>
      <el-form-item prop="title_url">
        <template #label>
          自定义url
          <el-popover placement="top-start" width="auto" trigger="hover">
            <template #default>
              <p class="ml-[12px]">可用于自定义信息跳转url地址</p>
            </template>
            <template #reference>
              <i class="ri-question-line ml-[4px]"></i>
            </template>
          </el-popover>
        </template>
        <el-input
          v-model="formMdl.title_url"
          :readonly="isDetailMode"
          clearable
          placeholder="请输入自定义url"
          class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
        ></el-input>
      </el-form-item>
      <el-form-item prop="cover" label="封面">
        <el-upload
          :on-change="handleChangeCover"
          :auto-upload="false"
          :multiple="false"
          :file-list="fileList"
          list-type="picture-card"
          :limit="COVER_FILE_COUNT_LIMIT"
          class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
          :class="{
            'el-upload--disabled':
              isDetailMode || fileList.length >= COVER_FILE_COUNT_LIMIT,
          }"
        >
          <template v-if="!isDetailMode" #tip>
            <el-alert type="info" class="mt-[8px]">
              <template #title>
                <i class="ri-notification-2-line"></i>
                允许上传不超过800KB的jpg、png格式图片
              </template>
            </el-alert>
          </template>

          <template #trigger>
            <div
              class="w-full h-full flex justify-center items-center"
              @click="handleChooseFile"
            >
              <el-icon class="icon-upload">
                <Plus />
              </el-icon>
            </div>
          </template>

          <template #file="{ file }">
            <div class="upload-pic-view">
              <el-image
                :src="file.url"
                :hide-on-click-modal="true"
                :preview-src-list="[file.url]"
                :initial-index="[file.url].indexOf(file.url)"
                fit="cover"
              />

              <span
                v-if="!isDetailMode"
                class="icon-close"
                @click="handleBeforeRemoveCover"
              >
                <el-icon><Delete /></el-icon>
              </span>
            </div>
          </template>
        </el-upload>
      </el-form-item>
      <el-form-item prop="content" label="内容" class="info-content">
        <div
          id="editor—wrapper"
          class="sm:w-1/1 md:w-1/1 lg:w-8/9 xl:w-8/9 2xl:w-2/3"
        >
          <Toolbar id="toolbar-container" :editor="editorRef" mode="default" />

          <Editor
            id="editor-container"
            v-model="formMdl.content"
            :defaultConfig="editorConfig"
            mode="default"
            @onCreated="handleCreated"
          />
        </div>
        <!-- S 富文本编辑器图片添加辅助 -->
        <el-upload
          style="display: none"
          multiple
          :file-list="editorUploadFileList"
          :http-request="customUpload"
          list-type="picture"
        >
          <el-button id="editorPicUploadBtn" size="small" type="primary"
            >选择简介中的图片</el-button
          >
        </el-upload>
        <!-- E 富文本编辑器图片添加辅助 -->
      </el-form-item>
      <el-form-item class="form-item-images" prop="images">
        <template #label>
          图片集
          <el-popover placement="top-start" width="auto" trigger="hover">
            <template #default>
              <p class="ml-[12px]">可用于需要展示图片场景</p>
            </template>
            <template #reference>
              <i class="ri-question-line ml-[4px]"></i>
            </template>
          </el-popover>
        </template>
        <el-upload
          :on-change="handleChangePicFile"
          :auto-upload="false"
          :multiple="true"
          :file-list="picFileList"
          list-type="picture-card"
          :limit="PIC_FILE_COUNT_LIMIT"
          :disabled="isDetailMode"
          class="sm:w-1/1 md:w-1/1 lg:w-2/3 xl:w-3/5 2xl:w-2/5"
          :class="{
            'el-upload--disabled':
              isDetailMode || picFileList.length >= PIC_FILE_COUNT_LIMIT,
          }"
        >
          <template v-if="!isDetailMode" #tip>
            <el-alert type="info" class="mt-[8px]">
              <template #title>
                <i class="ri-notification-2-line"></i>
                允许上传单张不超过2M的jpg、png格式图片
              </template>
            </el-alert>
          </template>

          <template #trigger>
            <div
              class="w-full h-full flex justify-center items-center"
              @click="handleChoosePicFile"
            >
              <el-icon class="icon-upload">
                <Plus />
              </el-icon>
            </div>
          </template>

          <template #file="{ file }">
            <div class="upload-pic-view">
              <el-image
                :src="file.url"
                :hide-on-click-modal="true"
                :preview-src-list="previewSrcList"
                :initial-index="previewSrcList.indexOf(file.url)"
                fit="cover"
                :lazy="true"
              />
              <span
                v-if="!isDetailMode"
                class="icon-close"
                @click="handleBeforeRemovePicFile(file)"
              >
                <el-icon><Delete /></el-icon>
              </span>
            </div>
          </template>
        </el-upload>
      </el-form-item>
      <el-form-item prop="publish_time" label="发布时间">
        <el-date-picker
          v-model="formMdl.publish_time"
          type="datetime"
          placeholder="请选择发布时间"
          :shortcuts="shortcuts"
          :readonly="isDetailMode"
          value-format="YYYY-MM-DD HH:mm:ss"
        >
        </el-date-picker>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
import {
  ref,
  computed,
  onMounted,
  onBeforeUnmount,
  shallowRef,
  nextTick,
} from 'vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import useAddPage from '@/hooks/business/useAddPage'
import { ROOT_NODE_PID } from '@/components/NTEditableTree/index.vue'
import { cloneDeep, isArray, last } from 'lodash-es'
import { unformatResourceUrl } from '@/utils/other/common'
import { API_VERSION } from '@/settings/config/http'
import { http } from '@/utils/http'

// 富文本编辑器
import '@wangeditor-next/editor/dist/css/style.css'
import { Editor, Toolbar } from '@wangeditor-next/editor-for-vue'
import rules from './baseFormRules'

export default {
  components: {
    Editor,
    Toolbar,
  },
  setup(props) {
    const { isDetailMode } = useAddPage(props)
    const { $api, $apiCode, $message, $tree, $confirm } = useCurrentInstance()
    const { getTree, formateTree, recursionMachine } = $tree

    // 表单
    const formRef = ref(null)
    const formRules = (() => {
      if (!isDetailMode.value) {
        return rules
      }
      return null
    })()

    const initFormMdl = {
      id: '',
      column_id: '',
      title: '',
      sub_title: '',
      content: '',
      title_url: '',
      cover: '',
      images: [],
      label: [],
      publish_time: '',
    }
    const formMdl = ref({ ...cloneDeep(initFormMdl) })

    // 栏目数据列表
    const treeData = ref([])
    const treeDataKeyMap = {
      id: 'id',
      label: 'name',
      pid: 'pid',
      children: 'children',
    }
    const getColumnList = async () => {
      const apiRes = await $api.column.columnListApi()
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        treeData.value = []
        const treeArr = []
        getTree(
          treeArr,
          data,
          {
            id: 'id',
            pid: 'pid',
            name: 'name',
            component: 'component',
            level: 'level',
          },
          []
        )
        const allTreeNodeData = [
          {
            id: 0,
            pid: ROOT_NODE_PID,
            name: '全部配置栏目',
            children: treeArr || [],
          },
        ]
        formateTree(treeData.value, allTreeNodeData, treeDataKeyMap)
      } else {
        $message({
          showClose: true,
          message,
          type: 'error',
          duration: 3000,
        })
      }
    }
    getColumnList()

    const columnId = computed(() => {
      return isArray(formMdl.value.column_id)
        ? last(formMdl.value.column_id)
        : formMdl.value.column_id
    })

    const columnListTree = computed(() => {
      let tempColumnTree = cloneDeep(treeData.value)
      recursionMachine(tempColumnTree, (dataItem) => {
        if (dataItem.id === formMdl.value.id) {
          dataItem.disabled = true
        }
      })

      if (tempColumnTree.length) {
        tempColumnTree = tempColumnTree[0].children
      }

      return tempColumnTree
    })

    // 封面图片
    const COVER_FILE_COUNT_LIMIT = 1
    const fileList = ref([])
    const handleChooseFile = (e) => {
      if (isDetailMode.value) {
        return
      }
      if (fileList.value.length >= COVER_FILE_COUNT_LIMIT) {
        $message({
          type: 'warning',
          message: `目前只能上传${COVER_FILE_COUNT_LIMIT}张图片, 如需上传可先删除`,
          duration: 1000,
        })
        e.stopPropagation()
      }
    }
    const handleChangeCover = (file) => {
      // 校验图片size大小
      const sizeValide = 1024 * 800
      // 校验类型
      const typeValide = ['image/jpeg', 'image/png']
      if (file.size > sizeValide) {
        $message({
          message: '图片大小不能超过800KB',
          type: 'warning',
        })
        fileList.value = []
        return
      }
      if (!typeValide.includes(file.raw.type)) {
        $message({
          message: '图片类型值允许 JPG、PNG',
          type: 'warning',
        })
        fileList.value = []
        return
      }
      formMdl.value.cover = file.raw
      fileList.value = [file]
    }
    const handleBeforeRemoveCover = () => {
      $confirm('确定删除图片吗', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      })
        .then(async () => {
          if (!fileList.value[0].isRemote) {
            formMdl.value.cover = ''
            fileList.value = []
          } else {
            const isDeleted = await deleteCover()
            if (isDeleted) {
              formMdl.value.cover = ''
              fileList.value = []
            }
          }
        })
        .catch(() => {
          return false
        })
      return false
    }

    const deleteCover = async () => {
      const params = {
        info_id: formMdl.value.id,
      }
      const apiRes = await $api.info.deleteCoverApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        $message.success({
          message,
          duration: 3000,
        })
        return true
      }
      $message.error({
        message,
        duration: 3000,
      })
      return false
    }

    // 富文本编辑器
    const editorRef = shallowRef()

    const colors = [
      'rgb(0, 0, 0)',
      'rgb(38, 38, 38)',
      'rgb(89, 89, 89)',
      'rgb(140, 140, 140)',
      'rgb(191, 191, 191)',
      'rgb(217, 217, 217)',
      'rgb(233, 233, 233)',
      'rgb(245, 245, 245)',
      'rgb(250, 250, 250)',
      'rgb(255, 255, 255)',
      'rgb(225, 60, 57)',
      'rgb(231, 95, 51)',
      'rgb(235, 144, 58)',
      'rgb(245, 219, 77)',
      'rgb(114, 192, 64)',
      'rgb(89, 191, 192)',
      'rgb(66, 144, 247)',
      'rgb(54, 88, 226)',
      'rgb(106, 57, 201)',
      'rgb(216, 68, 147)',
      'rgb(251, 233, 230)',
      'rgb(252, 237, 225)',
      'rgb(252, 239, 212)',
      'rgb(252, 251, 207)',
      'rgb(231, 246, 213)',
      'rgb(218, 244, 240)',
      'rgb(217, 237, 250)',
      'rgb(224, 232, 250)',
      'rgb(237, 225, 248)',
      'rgb(246, 226, 234)',
      'rgb(255, 163, 158)',
      'rgb(255, 187, 150)',
      'rgb(255, 213, 145)',
      'rgb(255, 251, 143)',
      'rgb(183, 235, 143)',
      'rgb(135, 232, 222)',
      'rgb(145, 213, 255)',
      'rgb(173, 198, 255)',
      'rgb(211, 173, 247)',
      'rgb(255, 173, 210)',
      'rgb(255, 77, 79)',
      'rgb(255, 122, 69)',
      'rgb(255, 169, 64)',
      'rgb(255, 236, 61)',
      'rgb(115, 209, 61)',
      'rgb(54, 207, 201)',
      'rgb(64, 169, 255)',
      'rgb(89, 126, 247)',
      'rgb(146, 84, 222)',
      'rgb(247, 89, 171)',
      'rgb(207, 19, 34)',
      'rgb(212, 56, 13)',
      'rgb(212, 107, 8)',
      'rgb(212, 177, 6)',
      'rgb(56, 158, 13)',
      'rgb(8, 151, 156)',
      'rgb(9, 109, 217)',
      'rgb(29, 57, 196)',
      'rgb(83, 29, 171)',
      'rgb(196, 29, 127)',
      'rgb(130, 0, 20)',
      'rgb(135, 20, 0)',
      'rgb(135, 56, 0)',
      'rgb(97, 71, 0)',
      'rgb(19, 82, 0)',
      'rgb(0, 71, 79)',
      'rgb(0, 58, 140)',
      'rgb(6, 17, 120)',
      'rgb(34, 7, 94)',
      'rgb(120, 6, 80)',
      '#f2f5ff',
      '#0054ac',
      '#ffb300',
    ]
    const editorConfig = {
      placeholder: '请输入内容...',
      MENU_CONF: {
        bgColor: {
          colors,
        },
        // 图片上传配置
        uploadImage: {
          // 自定义上传
          async customBrowseAndUpload() {
            // 调用elementUI图片上传
            document.querySelector('#editorPicUploadBtn').click()
          },
        },
      },
    }

    // el-upload自定义富文本图片上传功能
    const picUploadUrl = `${API_VERSION}admin/upload-info-content-pic`
    const editorUploadFileList = ref([])
    const handleSuccessEditorUpload = (response) => {
      const { status, data, message } = response.data
      // result为图片上传后在服务器中所处地址
      if (status === $apiCode.SUCCESS && data) {
        // 新建一个 image node
        editorRef.value.insertNode({
          type: 'image',
          src: data,
          href: data,
          alt: '',
          style: {},
          children: [{ text: '' }],
        })
      } else {
        $message({
          type: 'error',
          message,
          duration: 1500,
        })
      }
    }
    const customUpload = (options) => {
      const formData = new FormData()
      formData.append('column_id', columnId.value)
      formData.append('file', options.file)

      http
        .post(picUploadUrl, formData)
        .then((apiRes) => {
          const { status, message } = apiRes.data
          if (status === $apiCode.SUCCESS) {
            handleSuccessEditorUpload(apiRes)
          } else {
            $message({
              type: 'error',
              message,
              duration: 1500,
            })
          }
        })
        .catch((error) => {
          console.error('Error during upload:', error)
        })
    }
    // 组件销毁时，也及时销毁编辑器
    onBeforeUnmount(() => {
      const editor = editorRef.value
      if (editor == null) return
      editor.destroy()
    })

    const handleCreated = (editor) => {
      // 记录 editor 实例
      editorRef.value = editor
    }

    onMounted(() => {
      if (isDetailMode.value) {
        nextTick(() => {
          editorRef.value.disable()
        })
      }
    })

    // 图片集
    const PIC_FILE_COUNT_LIMIT = 15
    const picFileList = ref([])
    const previewSrcList = computed(() => {
      return picFileList.value.map((file) => {
        return file.url
      })
    })
    const handleChoosePicFile = (e) => {
      if (isDetailMode.value) {
        return
      }

      if (picFileList.value.length >= PIC_FILE_COUNT_LIMIT) {
        $message({
          type: 'warning',
          message: `目前只能上传${PIC_FILE_COUNT_LIMIT}张图片, 如需上传可先删除`,
          duration: 1000,
        })
        e.stopPropagation()
      }
    }
    const handleChangePicFile = (file) => {
      // 校验图片size大小
      const sizeValide = 1024 * 1024 * 2
      // 校验类型
      const typeValide = ['image/jpeg', 'image/png']
      if (file.size > sizeValide) {
        $message({
          message: '单张图片大小不能超过2M',
          type: 'warning',
        })
        picFileList.value.splice(-1, 1)
        return
      }
      if (!typeValide.includes(file.raw.type)) {
        $message({
          message: '图片类型值允许 jpg、png',
          type: 'warning',
        })
        picFileList.value.splice(-1, 1)
        return
      }
      formMdl.value.images.push(file.raw)
      picFileList.value.push(file)
    }
    const handleBeforeRemovePicFile = (uploadFile) => {
      $confirm('确定删除图片吗', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      })
        .then(async () => {
          if (!uploadFile.isRemote) {
            removePicFile(uploadFile.url)
          } else {
            const url = unformatResourceUrl(uploadFile.url)
            const isDeleted = await deleteResource(url)
            if (isDeleted) {
              removePicFile(uploadFile.url)
            }
          }
        })
        .catch(() => {
          return false
        })
      return false
    }

    const removePicFile = (url) => {
      formMdl.value.images = formMdl.value.images.filter((item) => {
        return item.url !== url
      })
      picFileList.value = picFileList.value.filter((item) => {
        return item.url !== url
      })
    }

    const deleteResource = async (url = '', type = 'pic') => {
      const params = {
        info_id: formMdl.value.id,
        column_id: formMdl.value.column_id,
        resource_type: type,
        resource_url: url,
      }
      const apiRes = await $api.info.deleteResourceApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        $message.success({
          message,
          duration: 3000,
        })
        return true
      }
      $message.error({
        message,
        duration: 3000,
      })
      return false
    }

    const shortcuts = [
      {
        text: '今天',
        value: new Date(),
      },
      {
        text: '昨天',
        value: () => {
          const date = new Date()
          date.setTime(date.getTime() - 3600 * 1000 * 24)
          return date
        },
      },
      {
        text: '一周前',
        value: () => {
          const date = new Date()
          date.setTime(date.getTime() - 3600 * 1000 * 24 * 7)
          return date
        },
      },
    ]

    // 单独清除已选择过的文件，防止下次重复上传
    const resetSelectedFiles = () => {
      formMdl.value.cover = ''
      fileList.value = []

      // 清除上传时候选择的图片，防止下次重复传递
      formMdl.value.images = []
      picFileList.value = []
    }

    const handleReset = () => {
      formRef.value.resetFields()
      resetSelectedFiles()
    }

    return {
      formRef,
      formMdl,
      formRules,
      isDetailMode,
      columnId,
      columnListTree,
      COVER_FILE_COUNT_LIMIT,
      fileList,
      handleChooseFile,
      handleChangeCover,
      handleBeforeRemoveCover,
      // 富文本编辑器配置
      picUploadUrl,
      editorUploadFileList,
      handleSuccessEditorUpload,
      customUpload,
      editorRef,
      editorConfig,
      handleCreated,
      PIC_FILE_COUNT_LIMIT,
      picFileList,
      previewSrcList,
      handleChoosePicFile,
      handleChangePicFile,
      handleBeforeRemovePicFile,
      shortcuts,
      resetSelectedFiles,
      handleReset,
    }
  },
}
</script>
<style lang="scss" scoped>
.info-content {
  :deep(.el-form-item__content) {
    display: block;

    #editor—wrapper {
      // width: 1000px;
      border: 1px solid #ccc;
      z-index: 1000;

      &.w-e-full-screen-container {
        position: fixed !important;
      }

      #toolbar-container {
        border-bottom: 1px solid #ccc;
      }

      #editor-container {
        height: 650px !important;
        overflow-y: hidden;
      }
    }
  }
}
</style>
