<template>
  <div class="column-editable-tree">
    <EditableTree
      ref="editableTreeRef"
      v-loading="treeIsLoading"
      :treeData="treeData"
      :styleConf="{ width: '500px' }"
      :mode="mode"
      @add-category="handleOpenAddDialog"
      @edit-category="handleOpenEditDialog"
      @delete-category="handleDeleteColumn"
      @select-row="handleSelectRow">
    </EditableTree>
    <el-dialog
      v-if="dialogFormVisible"
      v-model="dialogFormVisible"
      :title="hdTitle"
      width="800px"
      :lock-scroll="true"
      @close="handleClose">
      <el-form
        ref="addFormRef"
        :rules="formRules"
        :model="formMdl"
        v-loading="tbIsLoading"
        label-width="120px">
        <el-tabs v-model.trim="activeTabName">
          <el-tab-pane label="基本信息" name="base">
            <el-form-item prop="name" label="栏目名称">
              <el-input v-model.trim="formMdl.name" clearable placeholder="请输入栏目名称"></el-input>
            </el-form-item>
            <el-form-item prop="pid" label="上级栏目">
              <el-cascader v-model="formMdl.pid" :options="columnListTree" :props="{ checkStrictly: true, value: 'id' }" filterable clearable placeholder="请选择上级栏目" />
            </el-form-item>
            <el-form-item prop="is_last" label="是否终级栏目">
              <!-- <el-switch v-model="formMdl.is_last" active-text="是" inactive-text="否" :active-value="1" :inactive-value="0"></el-switch> -->
              <el-radio-group v-model="formMdl.is_last">
                <el-radio :label="0">栏目</el-radio>
                <el-radio :label="1">终极栏目</el-radio>
                <el-radio :label="2">单页</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item prop="is_show_in_nav" label="是否显示到导航">
              <el-switch v-model="formMdl.is_show_in_nav" active-text="是" inactive-text="否" :active-value="1" :inactive-value="0"></el-switch>
            </el-form-item>
            <el-form-item prop="" label="栏目存放文件夹">
              <el-col :span="11">
                <el-input v-model.trim="formMdl.parent_dir_path" clearable placeholder="请输入上层栏目目录"></el-input>
              </el-col>
              <el-col class="line" :span="1">/</el-col>
              <el-col :span="11">
                <el-input v-model.trim="formMdl.column_dir_path" clearable placeholder="请输入本栏目目录"></el-input>
              </el-col>
            </el-form-item>
            <el-form-item prop="model_tb_name" label="绑定的数据模型">
              <el-select v-model="formMdl.model_tb_name" filterable clearable placeholder="请选择绑定的数据模型">
                <el-option
                  label="新闻模型"
                  value="news"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item prop="cover" label="栏目封面">
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
            <el-form-item prop="keywords" label="关键字keywords">
              <el-input v-model.trim="formMdl.keywords" clearable placeholder="请输入页面关键字keywords"></el-input>
            </el-form-item>
            <el-form-item prop="description" label="描述description">
              <el-input type="textarea" v-model.trim="formMdl.description" clearable placeholder="请输入页面描述description"></el-input>
            </el-form-item>
            <el-form-item prop="sort" label="排序" class="small-input">
              <el-input v-model="formMdl.sort" clearable placeholder="请输入排序"></el-input>
            </el-form-item>
          </el-tab-pane>
          <el-tab-pane label="模板设置" name="tpl">
            <el-form-item prop="list_temp_id" label="所属列表模板">
              <el-select v-model="formMdl.list_temp_id" filterable clearable placeholder="请选择所属列表模板">
                <el-option
                  label="请选择列表模板"
                  :value="0"
                >
                </el-option>
                <el-option
                  v-for="tpl in listTplList"
                  :key="tpl.id"
                  :label="tpl.name"
                  :value="tpl.id"
                >
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item prop="detail_temp_id" label="所属内容模板">
              <el-select v-model="formMdl.detail_temp_id" filterable clearable placeholder="请选择所属内容模板">
                <el-option
                  label="请选择详情模板"
                  :value="0"
                >
                </el-option>
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
          <el-tab-pane label="自定义字段设置" name="extra-field">
            <formCreate v-model:api="formCreateMdl" :rule="formCreateRules" :option="formCreateOptions"></formCreate>
          </el-tab-pane>
        </el-tabs>
        <el-form-item>
          <el-button @click="handleRest">重置</el-button>
          <el-button type="primary" v-loading="loading" @click="handleSubmit">{{submitBtnTit}}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref, getCurrentInstance, computed } from 'vue'
import { getTree, formateTree, recursionMachine } from '@/utils'
import { HTTP_CONFIG } from '@/config/http'
import _ from 'lodash'
import type { UploadFile } from 'element-plus/es/components/upload/src/upload.d'
import EditableTree, { TreeProps, TreeDataKeyMapProps, ROOT_NODE_PID } from '@/components/EditableTree.vue'
import columnRules from '@/validator/column'
import formCreate from '@form-create/element-ui'
import {
  columnListApi,
  ColumnProps,
  addColumnApi,
  ColumnDetailProps,
  columnDetailApi,
  EditColumnProps,
  editColumnApi,
  DeleteColumnProps,
  deleteColumnApi,
  DeleteColumnCoverProps,
  deleteColumnCoverApi
} from '@/api/column'
import {
  columnExtendFieldsConfigAllListApi
} from '@/api/columnExtendFieldsConfig'
import { listTplBaseInfoListApi } from '@/api/listTpl'
import { detailTplBaseInfoListApi } from '@/api/detailTpl'
export default defineComponent({
  components: {
    EditableTree,
    formCreate: formCreate.$form()
  },
  emits: [
    'edit-category',
    'add-category',
    'select-row'
  ],
  props: {
    mode: {
      type: String,
      default: 'add'
    },
    availableIsLastVal: {
      type: Array,
      default: () => [0, 1, 2]
    }
  },
  setup (props, { emit }) {
    const { proxy } = (getCurrentInstance() as any)
    const editableTreeRef = ref()
    const treeIsLoading = ref(false)

    // 栏目数据列表
    const treeData = ref<TreeProps[]>([])
    const treeDataKeyMap: TreeDataKeyMapProps = { id: 'id', label: 'name', pid: 'pid', children: 'children', pids: 'pids' }
    const getColumnList = async () => {
      treeIsLoading.value = true
      let { status, data, message } = await columnListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        data = data.filter((column: any) => {
          return props.availableIsLastVal.includes(column.is_last)
        })
        treeData.value = []
        const treeArr: TreeProps[] = []
        getTree(treeArr, data)
        const allTreeNodeData = [{ id: 0, pid: ROOT_NODE_PID, name: '全部栏目', children: treeArr || [], pids: '' }]

        formateTree((treeData.value as any), allTreeNodeData, (treeDataKeyMap as any))
      } else {
        proxy.message({
          showClose: true,
          message,
          type: 'error',
          duration: 3000
        })
      }
      treeIsLoading.value = false
    }
    getColumnList()

    // 自定义字段表单生成
    const formCreateMdl = ref({})
    const systemConfList = ref<TreeProps[]>([])
    const formCreateOptions = {
      submitBtn: false,
      resetBtn: false
    }
    const formCreateRules = computed(() => {
      return _.cloneDeep(systemConfList.value)
    })
    const columnExtendFieldsConfigAllList = async () => {
      const { status, data, message } = await columnExtendFieldsConfigAllListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        systemConfList.value = data.map((item: any) => {
          /* eslint-disable */
          item.options = item.options ? eval('(' + item.options + ')') : []
          item.props = item.props ? eval('(' + item.props + ')') : {}
          item.validate = item.validate ? eval('(' + item.validate + ')') : []

          // 填充value
          item.value = formMdl.value[item.field]
          return item
        })
      } else {
        proxy.message.error({
          message,
          duration: 3000
        })
      }
    }

    // 添加、编辑栏目
    const dialogFormVisible = ref(false)
    const hdTitle = computed(() => {
      return props.mode === 'edit' ? '编辑栏目' : '添加栏目'
    })
    // tab
    const activeTabName = ref('base')
    // 表单
    const tbIsLoading = ref(false)
    const loading = ref(false)
    const addFormRef = ref()
    const formMdl = ref<any>({
      id: '',
      name: '',
      pid: [],
      is_last: '',
      is_show_in_nav: '',
      parent_dir_path: '',
      column_dir_path: '',
      model_tb_name: '',
      detail_temp_id: '',
      list_temp_id: '',
      cover: '',
      keywords: '',
      description: '',
      sort: ''
    })
    const columnListTree = computed(() => {
      const tempColumnTree = _.cloneDeep(treeData.value)
      recursionMachine(tempColumnTree, (dataItem: any) => {
        if (dataItem.id === formMdl.value.id) {
          dataItem.disabled = true
        }
      })
      tempColumnTree[0].label = '顶级栏目'
      return tempColumnTree
    })
    const submitBtnTit = computed(() => {
      return props.mode === 'edit' ? '更新' : '添加'
    })

    const handleOpenAddDialog = ({ data }: {data: TreeProps}) => {
      emit('add-category')
      let pids = data?.pids?.split(',')
      pids = pids?.map((pid) => {
        return Number(pid)
      }) as any
      pids && pids.push(data.id)
      formMdl.value.pid = pids
      dialogFormVisible.value = true

      columnExtendFieldsConfigAllList()
    }
    const handleOpenEditDialog = async ({ data }: {data: TreeProps}) => {
      emit('edit-category')
      let pids = data?.pids?.split(',')
      pids = pids?.map((pid) => {
        return Number(pid)
      }) as any
      pids && pids.push(data.id)
      formMdl.value.pid = pids
      dialogFormVisible.value = true
      // 获取详情
      await columnDetail(data.id)

      columnExtendFieldsConfigAllList()
    }

    // 重置
    const handleRest = () => {
      resetForm()
    }
    const resetForm = async () => {
      systemConfList.value = systemConfList.value.map((item: any) => {
        item.value = ''
        return item
      });
      
      addFormRef.value.resetFields()
      formMdl.value.pid = ''
      formMdl.value.parent_dir_path = ''
      formMdl.value.column_dir_path = ''
      fileList.value = []
    }

    // 提交
    const handleSubmit = async () => {
      const validateStatus = await new Promise((resolve) => {
        addFormRef.value.validate((valid: any) => {
          if (valid) {
            resolve(true)
          } else {
            resolve(false)
            return false
          }
        })
      })

      const createFormValidateStatus = await new Promise((resolve) => {
        (formCreateMdl.value as any).validate((valid: any) => {
          if (valid === true) {
            resolve(true)
          } else {
            resolve(false)
            return false
          }
        })
      })

      if (!validateStatus || !createFormValidateStatus || loading.value) {
        return false
      }

      const params: {[key: string]: any} = {
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
        keywords: formMdl.value.keywords,
        description: formMdl.value.description,
        sort: formMdl.value.sort,
        extra_fields: JSON.stringify((formCreateMdl.value as any).formData())
      }
      const formData = new FormData()
      for (const attr in params) {
        formData.append(attr, params[attr])
      }
      if (props.mode === 'add') {
        loading.value = true
        await addColumn((formData as any))
        getColumnList()
        loading.value = false
      } else if (props.mode === 'edit') {
        formData.append('id', formMdl.value.id)
        await editColumn((formData as any))
        getColumnList()
      }
    }

    // 添加
    const addColumn = async (data: ColumnProps) => {
      const { status, message } = await addColumnApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        resetForm()
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
    const editColumn = async (data: EditColumnProps) => {
      const { status, message } = await editColumnApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
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
    const columnDetail = async (id: string) => {
      const params: ColumnDetailProps = {
        id
      }
      const { status, data, message } = await columnDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        formMdl.value = {
          ...data,
          id: data.id,
          name: data.name,
          pid: data.pid,
          is_last: data.is_last,
          is_show_in_nav: data.is_show_in_nav,
          parent_dir_path: data.parent_dir_path,
          column_dir_path: data.column_dir_path,
          model_tb_name: data.model_tb_name,
          detail_temp_id: data.detail_temp_id,
          list_temp_id: data.list_temp_id,
          cover: data.cover_url,
          keywords: data.keywords,
          description: data.description,
          sort: data.sort
        }
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 删除
    const handleDeleteColumn = ({ data }: {data: TreeProps}) => {
      deleteColumn(data.id)
    }

    // 选择
    const handleSelectRow = (data: TreeProps) => {
      emit('select-row', data)
    }

    const deleteColumn = async (id: string) => {
      const params: DeleteColumnProps = {
        id
      }
      const { status, message } = await deleteColumnApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        getColumnList()
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }

    // 关闭Dialog
    const handleClose = () => {
      resetForm()
      formMdl.value.id = ''
    }

    // 栏目图片
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
        const isDeleted = await deleteColumnCover()
        if (isDeleted) {
          formMdl.value.cover = ''
          fileList.value = []
        }
      }).catch(() => {
        return false
      })
      return false
    }

    const deleteColumnCover = async () => {
      const params: DeleteColumnCoverProps = {
        column_id: formMdl.value.id
      }
      const { status, message } = await deleteColumnCoverApi(params)
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

    interface TplListProps {
      id: string;
      name: string;
    }

    // 列表模板基本信息列表
    const listTplList = ref<TplListProps[]>([])
    const getListTplList = async () => {
      const { status, data, message } = await listTplBaseInfoListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        listTplList.value = data
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }
    getListTplList()

    // 详情模板基本信息列表
    const detailTplList = ref<TplListProps[]>([])
    const getDetailTplList = async () => {
      const { status, data, message } = await detailTplBaseInfoListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        detailTplList.value = data
      } else {
        proxy.message.warning({
          message,
          duration: 3000
        })
      }
    }
    getDetailTplList()

    return {
      editableTreeRef,
      treeData,
      treeIsLoading,
      dialogFormVisible,
      hdTitle,
      formRules: columnRules,
      tbIsLoading,
      activeTabName,
      loading,
      addFormRef,
      formMdl,
      columnListTree,
      submitBtnTit,
      handleOpenAddDialog,
      handleOpenEditDialog,
      handleRest,
      handleSubmit,
      handleDeleteColumn,
      handleSelectRow,
      handleClose,
      fileList,
      imgUrl,
      handleChooseFile,
      handleChangeCover,
      handleBeforeRemoveCover,
      listTplList,
      detailTplList,
      formCreateMdl,
      systemConfList,
      formCreateOptions,
      formCreateRules
    }
  }
})
</script>
<style lang="scss" scoped>
.column-editable-tree {
  padding: 10px 15px;

  .line {
    text-align: center;
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
}
</style>
