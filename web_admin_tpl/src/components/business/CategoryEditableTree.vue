<template>
  <div class="category-editable-tree">
    <EditableTree
      ref="editableTreeRef"
      v-loading="treeIsLoading"
      :title="title"
      :treeData="treeData"
      :styleConf="{ width: '500px' }"
      :mode="mode"
      @add-category="handleOpenAddDialog"
      @edit-category="handleOpenEditDialog"
      @delete-category="handleDeleteCategory">
    </EditableTree>
    <el-dialog
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
        label-width="100px">
        <el-form-item prop="categoryName" label="分类名称">
          <el-input v-model.trim="formMdl.categoryName" clearable placeholder="请输入分类名称"></el-input>
        </el-form-item>
        <el-form-item prop="parentCategory" label="上级分类">
          <el-cascader v-model="formMdl.parentCategory" :options="categoryListTree" :props="{ checkStrictly: true, value: 'id' }" filterable clearable placeholder="请选择上级分类" />
        </el-form-item>
        <el-form-item prop="categoryLogo" label="分类logo">
          <el-upload
            :on-change="handleChangeLogo"
            :before-remove="handleBeforeRemoveLogo"
            :auto-upload="false"
            :file-list="fileList"
            :show-file-list="false"
            :limit="1">
            <template #tip>
              <Tips title="允许上传不超过500kb的jpg/png图片"></Tips>
            </template>
            <div
              v-if="formMdl.categoryLogo"
              class="upload-pic-view">
              <el-image
                style="width: 110px; height: 110px"
                :src="imgUrl"
                fit="contain"
                @click="handleChooseFile">
              </el-image>
              <el-icon class="icon-close" @click.stop="handleBeforeRemoveLogo"><Close /></el-icon>
            </div>
            <el-icon v-else class="icon-upload"><Plus /></el-icon>
          </el-upload>
        </el-form-item>
        <el-form-item prop="sort" label="排序" class="small-input">
          <el-input v-model="formMdl.sort" clearable placeholder="请输入排序"></el-input>
        </el-form-item>
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
import { getTree, formateTree, recursionMachine, isArray } from '@/utils'
import { HTTP_CONFIG, RESOURCE_PATH } from '@/config/http'
import _ from 'lodash'
import type { UploadFile } from 'element-plus/es/components/upload/src/upload.d'
import EditableTree, { TreeProps, TreeDataKeyMapProps, ROOT_NODE_PID } from '@/components/EditableTree.vue'
import categoryRules from '@/validator/category'
import {
  categoryListApi,
  CategoryProps,
  addCategoryApi,
  CategoryDetailProps,
  categoryDetailApi,
  EditCategoryProps,
  editCategoryApi,
  DeleteCategoryProps,
  DeleteCategoryApi
} from '@/api/category'
export default defineComponent({
  components: {
    EditableTree
  },
  emits: ['edit-category', 'add-category'],
  props: {
    title: {
      type: String,
      default: '分类列表'
    },
    mode: {
      type: String,
      default: 'add'
    }
  },
  setup (props, { emit }) {
    const { proxy } = (getCurrentInstance() as any)
    const editableTreeRef = ref()
    const treeIsLoading = ref(false)

    // 分类数据列表
    const treeData = ref<TreeProps[]>([])
    const treeDataKeyMap: TreeDataKeyMapProps = { id: 'id', label: 'name', pid: 'pid', children: 'children' }
    const getCategoryList = async () => {
      treeIsLoading.value = true
      const { status, data, message } = await categoryListApi()
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        treeData.value = []
        const treeArr: TreeProps[] = []
        getTree(treeArr, data)
        const allTreeNodeData = [{ id: 0, pid: ROOT_NODE_PID, name: '全部分类', children: treeArr || [] }]
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
    getCategoryList()

    // 添加、编辑分类
    const dialogFormVisible = ref(false)
    const hdTitle = computed(() => {
      return props.mode === 'edit' ? '编辑分类' : '添加分类'
    })
    // 表单
    const tbIsLoading = ref(false)
    const loading = ref(false)
    const addFormRef = ref()
    const formMdl = ref({
      id: '',
      categoryName: '',
      parentCategory: '',
      categoryLogo: '',
      sort: ''
    })
    const categoryListTree = computed(() => {
      const tempCategoryTree = _.cloneDeep(treeData.value)
      recursionMachine(tempCategoryTree, (dataItem: any) => {
        if (dataItem.id === formMdl.value.id) {
          dataItem.disabled = true
        }
      })
      tempCategoryTree[0].label = '顶级分类'
      return tempCategoryTree
    })
    const submitBtnTit = computed(() => {
      return props.mode === 'edit' ? '更新' : '添加'
    })

    const handleOpenAddDialog = ({ data }: {data: TreeProps}) => {
      emit('add-category')
      formMdl.value.parentCategory = data.id
      dialogFormVisible.value = true
    }
    const handleOpenEditDialog = ({ data }: {data: TreeProps}) => {
      emit('edit-category')
      formMdl.value.parentCategory = data.id
      dialogFormVisible.value = true
      // 获取详情
      categoryDetail(data.id)
    }

    // 分类图片
    const fileList = ref<UploadFile[]>([])
    const imgUrl = computed(() => {
      const url = proxy.formatPicUrl(formMdl.value.categoryLogo)
      return url
    })
    const handleChooseFile = (e: Event) => {
      if (formMdl.value.categoryLogo) {
        proxy.message({
          type: 'warning',
          message: '目前只能上传一张图片, 如需上传可先删除',
          duration: 1000
        })
        e.stopPropagation()
      }
    }
    const handleChangeLogo = (file: UploadFile) => {
      // 校验图片size大小
      const sizeValide = 1024 * 200
      // 校验类型
      const typeValide = ['image/jpeg', 'image/png']
      if ((file.size as any) > sizeValide) {
        proxy.message({
          message: '图片大小不能超过200KB',
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
      formMdl.value.categoryLogo = (file.raw as any)
      fileList.value = [file]
    }
    const handleBeforeRemoveLogo = () => {
      proxy.$confirm('确定移除图片吗', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(async () => {
        formMdl.value.categoryLogo = ''
        fileList.value = []
      }).catch(() => {
        return false
      })
      return false
    }

    // 重置
    const handleRest = () => {
      resetForm()
    }
    const resetForm = () => {
      addFormRef.value.resetFields()
      formMdl.value.parentCategory = ''
      formMdl.value.categoryLogo = ''
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

      if (!validateStatus || loading.value) {
        return false
      }
      const params: {[key: string]: any} = {
        name: formMdl.value.categoryName,
        pid: isArray(formMdl.value.parentCategory) ? formMdl.value.parentCategory[formMdl.value.parentCategory.length - 1] : formMdl.value.parentCategory,
        category_logo: formMdl.value.categoryLogo,
        sort: formMdl.value.sort
      }
      const formData = new FormData()
      for (const attr in params) {
        formData.append(attr, params[attr])
      }
      if (props.mode === 'add') {
        loading.value = true
        await addCategory((formData as any))
        getCategoryList()
        loading.value = false
      } else if (props.mode === 'edit') {
        loading.value = true
        formData.append('id', formMdl.value.id)
        await editCategory((formData as any))
        loading.value = false
      }
    }

    // 添加
    const addCategory = async (data: CategoryProps) => {
      const { status, message } = await addCategoryApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        resetForm()
        proxy.message.success({
          message: '添加成功',
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
    const editCategory = async (data: EditCategoryProps) => {
      const { status, message } = await editCategoryApi(data)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        proxy.message.success({
          message: '编辑成功',
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
    const categoryDetail = async (id: string) => {
      const params: CategoryDetailProps = {
        id
      }
      const { status, data, message } = await categoryDetailApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        formMdl.value = {
          id: data.id,
          categoryName: data.name,
          parentCategory: data.pid,
          categoryLogo: data.logo_url,
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
    const handleDeleteCategory = ({ data }: {data: TreeProps}) => {
      deleteCategory(data.id)
    }

    const deleteCategory = async (id: string) => {
      const params: DeleteCategoryProps = {
        id
      }
      const { status, message } = await DeleteCategoryApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        getCategoryList()
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

    return {
      editableTreeRef,
      treeData,
      treeIsLoading,
      dialogFormVisible,
      hdTitle,
      formRules: categoryRules,
      tbIsLoading,
      loading,
      addFormRef,
      formMdl,
      categoryListTree,
      submitBtnTit,
      handleOpenAddDialog,
      handleOpenEditDialog,
      fileList,
      imgUrl,
      handleChooseFile,
      handleChangeLogo,
      handleBeforeRemoveLogo,
      handleRest,
      handleSubmit,
      handleDeleteCategory,
      handleClose
    }
  }
})
</script>
<style lang="scss" scoped>
.category-editable-tree {
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
