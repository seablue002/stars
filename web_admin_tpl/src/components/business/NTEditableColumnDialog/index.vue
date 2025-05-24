<template>
  <div class="nt-editable-column-dialog">
    <el-dialog
      v-model="dialogFormVisible"
      :title="hdTitle"
      width="1000px"
      :lock-scroll="true"
      draggable
      class="custom-dialog"
      @close="handleClose"
    >
      <el-form
        ref="addFormRef"
        v-loading="tbIsLoading"
        :validate-on-rule-change="false"
        :rules="formRules"
        :model="formMdl"
        label-width="130px"
      >
        <el-tabs v-model.trim="activeTabName" type="card">
          <el-tab-pane label="基础信息" name="base">
            <el-form-item prop="name" label="栏目名称">
              <el-input
                v-model.trim="formMdl.name"
                clearable
                placeholder="请输入栏目名称"
                class="w-4/5"
              ></el-input>
            </el-form-item>
            <el-form-item prop="pid" label="上级栏目">
              <el-cascader
                v-model="formMdl.pid"
                :options="columnListTree"
                :props="{ checkStrictly: true, value: 'id' }"
                filterable
                clearable
                placeholder="请选择上级栏目"
                class="w-4/5"
              />
            </el-form-item>
            <el-form-item prop="is_last" label="栏目类型">
              <el-radio-group v-model="formMdl.is_last">
                <el-radio :label="0">
                  栏目
                  <el-popover
                    placement="top-start"
                    width="auto"
                    trigger="hover"
                  >
                    <template #default>
                      <p class="ml-[12px]">栏目：有下级菜单</p>
                    </template>
                    <template #reference>
                      <i class="ri-question-line ml-[4px]"></i>
                    </template>
                  </el-popover>
                </el-radio>
                <el-radio :label="1">
                  终极栏目
                  <el-popover
                    placement="top-start"
                    width="auto"
                    trigger="hover"
                  >
                    <template #default>
                      <p class="ml-[12px]">终极栏目：有内容列表</p>
                    </template>
                    <template #reference>
                      <i class="ri-question-line ml-[4px]"></i>
                    </template>
                  </el-popover>
                </el-radio>
                <el-radio :label="2">
                  单页
                  <el-popover
                    placement="top-start"
                    width="auto"
                    trigger="hover"
                  >
                    <template #default>
                      <p class="ml-[12px]">单页：独立页面</p>
                    </template>
                    <template #reference>
                      <i class="ri-question-line ml-[4px]"></i>
                    </template>
                  </el-popover>
                </el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item prop="is_show_in_nav" label="是否显示到导航">
              <el-switch
                v-model="formMdl.is_show_in_nav"
                active-text="是"
                inactive-text="否"
                :active-value="1"
                :inactive-value="0"
              ></el-switch>
            </el-form-item>
            <el-form-item prop="cover" label="栏目封面">
              <el-upload
                :on-change="handleChangeCover"
                :auto-upload="false"
                :multiple="false"
                :file-list="fileList"
                list-type="picture-card"
                :limit="COVER_FILE_COUNT_LIMIT"
                class="w-4/5"
                :class="{
                  'el-upload--disabled':
                    fileList.length >= COVER_FILE_COUNT_LIMIT,
                }"
              >
                <template #tip>
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

                    <span class="icon-close" @click="handleBeforeRemoveCover">
                      <el-icon><Delete /></el-icon>
                    </span>
                  </div>
                </template>
              </el-upload>
            </el-form-item>
            <el-form-item prop="sort">
              <template #label>
                排序
                <el-popover placement="top-start" width="auto" trigger="hover">
                  <template #default>
                    <p class="ml-[12px]">数值越小，栏目（菜单）越靠前</p>
                  </template>
                  <template #reference>
                    <i class="ri-question-line ml-[4px]"></i>
                  </template>
                </el-popover>
              </template>
              <el-input
                v-model="formMdl.sort"
                clearable
                placeholder="请输入排序"
                class="w-4/5"
              ></el-input>
            </el-form-item>
          </el-tab-pane>
          <el-tab-pane label="数据与模板" name="tpl">
            <el-form-item prop="model_tb_name">
              <template #label>
                绑定数据模型
                <el-popover placement="top-start" width="auto" trigger="hover">
                  <template #default>
                    <p class="ml-[12px]">数据模型对应数据库表</p>
                  </template>
                  <template #reference>
                    <i class="ri-question-line ml-[4px]"></i>
                  </template>
                </el-popover>
              </template>
              <el-select
                v-model="formMdl.model_tb_name"
                filterable
                clearable
                placeholder="请选择绑定数据模型"
                class="w-4/5"
              >
                <el-option
                  v-if="formMdl.is_last === 2"
                  label="不绑定模型"
                  value=""
                >
                </el-option>
                <el-option label="新闻模型" value="news"> </el-option>
              </el-select>
            </el-form-item>
            <el-form-item prop="parent_dir_path" label="上级栏目目录">
              <el-input
                v-model.trim="formMdl.parent_dir_path"
                clearable
                placeholder="请输入上级栏目目录"
                class="w-4/5"
              >
              </el-input>
            </el-form-item>
            <el-form-item prop="column_dir_path" label="本栏目目录">
              <el-input
                v-model.trim="formMdl.column_dir_path"
                clearable
                placeholder="请输入本栏目目录"
                class="w-4/5"
              >
              </el-input>

              <el-alert type="info" class="w-4/5 mt-[8px]">
                <template #title>
                  <i class="ri-notification-2-line"></i>
                  上级栏目目录+本栏目目录，即栏目（菜单）访问路径
                </template>
              </el-alert>
            </el-form-item>
            <el-form-item
              v-if="formMdl.parent_dir_path || formMdl.column_dir_path"
              label="栏目URL访问地址"
            >
              <el-row class="w-4/5">
                <el-col :span="20">
                  <el-input
                    :value="
                      API_HOST +
                      formMdl.parent_dir_path +
                      formMdl.column_dir_path
                    "
                    readonly
                  >
                  </el-input>
                </el-col>
                <el-col :span="4">
                  <el-button
                    size="small"
                    class="ml-[8px]"
                    @click="
                      handleCopyText(
                        API_HOST +
                          formMdl.parent_dir_path +
                          formMdl.column_dir_path
                      )
                    "
                  >
                    点击复制
                  </el-button>
                </el-col>
              </el-row>
            </el-form-item>
            <el-form-item prop="list_temp_id">
              <template #label>
                页面模板
                <el-popover placement="top-start" width="auto" trigger="hover">
                  <template #default>
                    <p class="ml-[12px]">设置后栏目将优先使用此模板</p>
                    <p class="ml-[12px]">
                      未设置系统将自动查找模板（数据模型名_list.html）
                    </p>
                    <p class="ml-[12px]">栏目类型为单页时页面模板必填</p>
                  </template>
                  <template #reference>
                    <i class="ri-question-line ml-[4px]"></i>
                  </template>
                </el-popover>
              </template>

              <el-select
                v-model="formMdl.list_temp_id"
                filterable
                clearable
                placeholder="请选择页面模板"
                class="w-4/5"
              >
                <el-option label="请选择页面模板" :value="0"> </el-option>
                <el-option
                  v-for="tpl in listTplList"
                  :key="tpl.id"
                  :label="tpl.name"
                  :value="tpl.id"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item prop="detail_temp_id">
              <template #label>
                详情模板
                <el-popover placement="top-start" width="auto" trigger="hover">
                  <template #default>
                    <p class="ml-[12px]">设置后栏目将优先使用此模板</p>
                    <p class="ml-[12px]">
                      未设置系统将自动查找模板（数据模型名_detail.html）
                    </p>
                  </template>
                  <template #reference>
                    <i class="ri-question-line ml-[4px]"></i>
                  </template>
                </el-popover>
              </template>

              <el-select
                v-model="formMdl.detail_temp_id"
                filterable
                clearable
                placeholder="请选择详情模板"
                class="w-4/5"
              >
                <el-option label="请选择详情模板" :value="0"> </el-option>
                <el-option
                  v-for="tpl in detailTplList"
                  :key="tpl.id"
                  :label="tpl.name"
                  :value="tpl.id"
                >
                </el-option>
              </el-select>
            </el-form-item>
          </el-tab-pane>
          <el-tab-pane label="自定义字段" name="extra-field">
            <formCreate
              v-model:api="formCreateMdl"
              :rule="formCreateRules"
              :option="formCreateOptions"
            ></formCreate>
          </el-tab-pane>
          <el-tab-pane label="TDK(SEO)" name="seo">
            <el-form-item prop="seo_title" label="页面标题">
              <el-input
                v-model.trim="formMdl.seo_title"
                type="textarea"
                :rows="2"
                clearable
                placeholder="请输入页面标题title"
              ></el-input>
            </el-form-item>
            <el-form-item prop="seo_keywords" label="页面关键词">
              <el-input
                v-model.trim="formMdl.seo_keywords"
                type="textarea"
                :rows="2"
                clearable
                placeholder="请输入页面关键词keywords"
              ></el-input>
            </el-form-item>
            <el-form-item prop="seo_description" label="页面描述">
              <el-input
                v-model.trim="formMdl.seo_description"
                type="textarea"
                :rows="5"
                clearable
                placeholder="请输入页面描述description"
              ></el-input>
            </el-form-item>
          </el-tab-pane>
        </el-tabs>
        <el-form-item>
          <el-button @click="handleRest">重置</el-button>
          <el-button v-loading="loading" type="primary" @click="handleSubmit">{{
            submitBtnTit
          }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script>
import { defineComponent, ref, computed, nextTick, watch } from 'vue'
import { cloneDeep, debounce } from 'lodash-es'
import { useClipboard } from '@vueuse/core'
import { formateResourceUrl } from '@/utils/other/common'
import { API_HOST } from '@/settings/config/http'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import formCreate from '@form-create/element-ui'
import columnRules from './columnRules'

export default defineComponent({
  name: 'NTEditableColumnDialog',
  components: {
    formCreate: formCreate.$form(),
  },
  emits: ['add-success', 'edit-success', 'delete-success'],
  setup(props, { emit }) {
    const { $api, $apiCode, $message, $confirm, $tree } = useCurrentInstance()
    const { recursionMachine } = $tree

    const { copy, isSupported, copied } = useClipboard()

    // 自定义字段表单生成
    const formCreateMdl = ref({})
    const columnExtendFields = ref([])
    const formCreateOptions = {
      submitBtn: false,
      resetBtn: false,
    }
    const formCreateRules = computed(() => {
      return cloneDeep(columnExtendFields.value)
    })
    const columnExtendFieldsConfigAllList = async () => {
      const apiRes =
        await $api.columnExtendFieldsConfig.columnExtendFieldsConfigAllListApi()
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        columnExtendFields.value = data.map((item) => {
          item = item.props
          // 填充value
          item.value = formMdl.value[item.field]
          return item
        })
      } else {
        $message.error({
          message,
          duration: 3000,
        })
      }
    }

    // 添加、编辑栏目
    const columnTreeMode = ref('add')
    const dialogFormVisible = ref(false)
    const hdTitle = computed(() => {
      return columnTreeMode.value === 'edit' ? '编辑栏目' : '添加栏目'
    })

    // tab
    const activeTabName = ref('base')
    // 表单
    const tbIsLoading = ref(false)
    const loading = ref(false)
    const addFormRef = ref()
    const formMdl = ref({
      id: '',
      name: '',
      pid: [],
      is_last: 1,
      is_show_in_nav: '',
      parent_dir_path: '',
      column_dir_path: '',
      model_tb_name: '',
      detail_temp_id: '',
      list_temp_id: '',
      cover: '',
      seo_title: '',
      seo_keywords: '',
      seo_description: '',
      sort: '',
    })

    const treeData = ref([])
    const columnListTree = computed(() => {
      const tempColumnTree = cloneDeep(treeData.value)
      recursionMachine(tempColumnTree, (dataItem) => {
        if (dataItem.id === formMdl.value.id) {
          dataItem.disabled = true
        }
      })

      tempColumnTree[0].label = '顶级栏目'
      return tempColumnTree
    })
    const submitBtnTit = computed(() => {
      return columnTreeMode.value === 'edit' ? '提交' : '添加'
    })

    // 添加
    const addColumn = async (data) => {
      const apiRes = await $api.column.addColumnApi(data)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        resetForm()
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
    const editColumn = async (data) => {
      const apiRes = await $api.column.editColumnApi(data)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
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
    const columnDetail = async (id) => {
      const params = {
        id,
      }
      const apiRes = await $api.column.columnDetailApi(params)
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        const { cover_url: coverUrl, ...extra } = data
        formMdl.value = {
          ...extra,
          cover: coverUrl,
        }

        if (coverUrl) {
          const coverFullPath = formateResourceUrl(coverUrl)
          fileList.value = [
            {
              id: coverFullPath,
              name: coverFullPath,
              url: coverFullPath,
              isRemote: true,
            },
          ]
        }
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 列表模板基本信息列表
    const listTplList = ref([])
    const getListTplList = async () => {
      const apiRes = await $api.listTemplate.listTplBaseInfoListApi()
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        listTplList.value = data
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }
    getListTplList()

    // 详情模板基本信息列表
    const detailTplList = ref([])
    const getDetailTplList = async () => {
      const apiRes = await $api.detailTemplate.detailTplBaseInfoListApi()
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        detailTplList.value = data
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }
    getDetailTplList()

    // 打开添加、编辑栏目Dialog
    const open = async (data) => {
      const { columnPids, mode, columnTree } = data
      treeData.value = columnTree
      columnTreeMode.value = mode
      formMdl.value.pid = columnPids
      dialogFormVisible.value = true
      nextTick(async () => {
        if (mode === 'edit') {
          formMdl.value.id = columnPids[columnPids.length - 1]
          await columnDetail(formMdl.value.id)
        }
        columnExtendFieldsConfigAllList()
      })
    }

    // 关闭弹窗
    const close = () => {
      dialogFormVisible.value = false
    }

    // 关闭弹窗回调
    const handleClose = () => {
      resetForm()
      formMdl.value.id = ''
    }

    // 栏目图片
    const COVER_FILE_COUNT_LIMIT = 1
    const fileList = ref([])
    const handleChooseFile = (e) => {
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
      $confirm('确定移除图片吗', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
      })
        .then(async () => {
          if (!fileList.value[0].isRemote) {
            formMdl.value.cover = ''
            fileList.value = []
          } else {
            const isDeleted = await deleteColumnCover()
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

    const deleteColumnCover = async () => {
      const params = {
        column_id: formMdl.value.id,
      }
      const apiRes = await $api.column.deleteColumnCoverApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        $message.success({
          message,
          duration: 3000,
        })
        return true
      }
      $message.warning({
        message,
        duration: 3000,
      })
      return false
    }

    // 修改表单校验规则
    const formRules = computed(() => {
      if (!addFormRef.value) {
        return columnRules
      }
      let tempRules = columnRules

      // 单页时，可以不选择绑定数据模型
      if (formMdl.value.is_last === 2) {
        const { model_tb_name: mtn, ...extraRules } = tempRules
        tempRules = extraRules

        // 清除表单验证信息
        addFormRef.value.clearValidate('model_tb_name')
      }

      // 非单页时，可以不填写页面模板
      if (formMdl.value.is_last !== 2) {
        const { list_temp_id: ltp, ...extraRules } = tempRules
        tempRules = extraRules

        // 清除表单验证信息
        addFormRef.value.clearValidate('list_temp_id')
      }

      return tempRules
    })

    // 重置
    const handleRest = () => {
      resetForm()
    }

    // 单独清除已选择过的文件，防止下次重复上传
    const resetSelectedFiles = () => {
      formMdl.value.cover = ''
      fileList.value = []
    }
    const resetForm = async () => {
      columnExtendFields.value = columnExtendFields.value.map((item) => {
        item.value = ''
        return item
      })

      addFormRef.value.resetFields()

      resetSelectedFiles()
    }

    // 提交
    const handleSubmit = async () => {
      const validateStatus = await new Promise((resolve) => {
        addFormRef.value.validate(resolve)
      })

      const createFormValidateStatus = await new Promise((resolve) => {
        formCreateMdl.value.validate((valid) => {
          resolve(valid === true)
        })
      })

      if (!validateStatus || !createFormValidateStatus || loading.value) {
        return
      }

      const params = {
        name: formMdl.value.name,
        pid: formMdl.value.pid,
        is_last: formMdl.value.is_last,
        is_show_in_nav: formMdl.value.is_show_in_nav,
        parent_dir_path: formMdl.value.parent_dir_path,
        column_dir_path: formMdl.value.column_dir_path,
        model_tb_name: formMdl.value.model_tb_name,
        detail_temp_id: formMdl.value.detail_temp_id,
        list_temp_id: formMdl.value.list_temp_id,
        cover: formMdl.value.cover,
        seo_title: formMdl.value.seo_title,
        seo_keywords: formMdl.value.seo_keywords,
        seo_description: formMdl.value.seo_description,
        sort: formMdl.value.sort,
        extra_fields: JSON.stringify(formCreateMdl.value.formData()),
      }
      const formData = new FormData()
      Object.entries(params).forEach(([attr, value]) => {
        formData.append(attr, value)
      })

      if (columnTreeMode.value === 'add') {
        loading.value = true
        await addColumn(formData)
        emit('add-success')
        loading.value = false
      } else if (columnTreeMode.value === 'edit') {
        formData.append('id', formMdl.value.id)
        await editColumn(formData)
        emit('edit-success')
      }
    }

    // 复制栏目完整访问路径
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

    return {
      API_HOST,
      dialogFormVisible,
      hdTitle,
      formRules,
      tbIsLoading,
      activeTabName,
      loading,
      addFormRef,
      formMdl,
      columnListTree,
      submitBtnTit,
      handleRest,
      handleSubmit,
      listTplList,
      detailTplList,
      open,
      close,
      handleClose,
      COVER_FILE_COUNT_LIMIT,
      fileList,
      handleChooseFile,
      handleChangeCover,
      handleBeforeRemoveCover,
      formCreateMdl,
      columnExtendFields,
      formCreateOptions,
      formCreateRules,
      handleCopyText,
    }
  },
})
</script>
<style lang="scss" scoped>
:deep(.el-upload) {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  &:hover {
    border-color: #409eff;
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
</style>
