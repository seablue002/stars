<template>
  <div class="system-config-category PAGE-MAIN-CONTENT">
    <FlexBlock
      :leftStyles="flexBlockLeftStyles"
      :centerStyles="flexBlockCenterStyles"
      :rightStyles="flexBlockRightStyles">
      <template #left>
        <SystemConfigCategoryEditableTree
          :mode="categoryEditableTreeMode"
          @add-category="categoryEditableTreeMode = 'add'"
          @edit-category="categoryEditableTreeMode = 'edit'"
          @select-row="handleSelectSystemCategoryRow">
        </SystemConfigCategoryEditableTree>
      </template>
      <template #right>
        <SystemConfigListTable
          title="配置列表"
          :tbHead="tbHead"
          :tbData="tbData"
          :tbIsLoading="tbIsLoading"
          @refresh="handleRefresh"
          @edit="handleEdit"
          @size-change="handlePageSizeChange"
          @current-change="handleCurrentPageChange">
          <template #head>
            <div class="table-search">
              <el-form
                ref="searchFormRef"
                :inline="true"
                :model="searchForm"
                label-width="100px"
              >
                <el-form-item label="配置名称" prop="label">
                  <el-input
                    v-model="searchForm.label"
                    clearable
                    placeholder="请输入配置名称"
                  ></el-input>
                </el-form-item>
                <el-form-item class="opts-btn">
                  <el-button
                    plain
                    @click.stop="handleResetSearchForm(searchFormRef)"
                  >
                    重置
                  </el-button>
                  <el-button
                    :loading="tbIsLoading"
                    type="primary"
                    icon="search"
                    @click.stop="handleSearch"
                  >
                    查询
                  </el-button>
                </el-form-item>
              </el-form>
            </div>
          </template>
          <template #opts>
            <el-button
              type="primary"
              icon="plus"
              @click.stop="handleOpenAddConfDialog"
            >
              添加配置
            </el-button>
          </template>
        </SystemConfigListTable>
      </template>
    </FlexBlock>

    <AddSystemConfigFormDialog
      ref="addConfFormDialogRef"
      :mode="addSystemConfigFormDialogMode"
      @add-success="getSystemConfigList"
      @edit-success="getSystemConfigList">
    </AddSystemConfigFormDialog>
  </div>
</template>
<script lang="ts">
import { defineComponent, reactive, ref, getCurrentInstance, nextTick } from 'vue'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import { TABLE_COLUMN_OPTS_TYPE_LIST } from '@/components/EditableTable.vue'
import { TreeProps } from '@/components/EditableTree.vue'
import FlexBlock from '@/components/FlexBlock.vue'
import SystemConfigCategoryEditableTree from '@/components/business/SystemConfigCategoryEditableTree.vue'
import SystemConfigListTable from '@/components/business/SystemConfigListTable.vue'
import AddSystemConfigFormDialog from '@/components/business/AddSystemConfigFormDialog.vue'
import type { ElForm } from 'element-plus'
import { HTTP_CONFIG } from '@/config/http'
import { DEFAULT_PAGE_SIZE } from '@/config/pagination'
import {
  SystemConfigListProps,
  systemConfigListApi
} from '@/api/systemConfig'
type FormInstance = InstanceType<typeof ElForm>
export default defineComponent({
  components: {
    FlexBlock,
    SystemConfigCategoryEditableTree,
    SystemConfigListTable,
    AddSystemConfigFormDialog
  },
  setup () {
    const { proxy } = (getCurrentInstance() as any)
    const { mainContentDomH } = useAutoMainContentHeight()

    const categoryEditableTreeMode = ref('add')

    const flexBlockLeftStyles = ref({})
    flexBlockLeftStyles.value = {
      height: mainContentDomH
    }
    const flexBlockCenterStyles = ref({})
    flexBlockCenterStyles.value = {
      'min-height': mainContentDomH
    }
    const flexBlockRightStyles = ref({})
    flexBlockRightStyles.value = {
      'min-height': mainContentDomH
    }

    // 搜索
    const searchFormRef = ref()
    const searchForm = reactive({
      label: '',
      cid: ''
    })
    const handleSearch = () => {
      tbData.pageNo = 1
      getSystemConfigList()
    }
    const handleResetSearchForm = (formEl: FormInstance) => {
      formEl.resetFields()
    }

    // 列表
    const tbIsLoading = ref(false)
    const tbData = reactive({
      data: [],
      total: 0,
      pageNo: 1,
      pageSize: DEFAULT_PAGE_SIZE
    })
    const tbHead: any[] = reactive([
      {
        label: '',
        prop: '$index',
        width: 50,
        overflowTips: false
      },
      {
        label: '配置名称',
        prop: 'label'
      },
      {
        label: '所属分类',
        prop: 'category_name'
      },
      {
        label: '字段名',
        prop: 'field'
      },
      {
        label: '表单元素类型',
        prop: 'type'
      },
      {
        label: '默认值',
        prop: 'value'
      },
      {
        label: '排序',
        prop: 'sort',
        sortable: true
      },
      {
        label: '创建时间',
        prop: 'create_time',
        sortable: true
      },
      {
        label: '操作',
        prop: 'TABLE_COLUMN_OPTS',
        tbColumnOptsTypeList: [
          TABLE_COLUMN_OPTS_TYPE_LIST.EDIT
        ],
        width: 150,
        minWidth: 150
      }
    ])
    const getSystemConfigList = async () => {
      const params: SystemConfigListProps = {
        label: searchForm.label,
        cid: searchForm.cid,
        page: tbData.pageNo,
        size: tbData.pageSize
      }
      tbIsLoading.value = true
      const { status, data, message } = await systemConfigListApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        tbData.data = data.data
        tbData.total = data.total
      } else {
        proxy.message({
          type: 'warning',
          message,
          duration: 3000
        })
      }
      tbIsLoading.value = false
    }
    getSystemConfigList()

    // 刷新
    const handleRefresh = () => {
      handleResetSearchForm(searchFormRef.value)
      handleSearch()
    }

    // 每页数据显示条数变化响应处理
    const handlePageSizeChange = async ({ size }: {size: number}) => {
      tbData.pageSize = size
      getSystemConfigList()
    }
    const handleCurrentPageChange = async ({ page }: {page: number}) => {
      tbData.pageNo = page
      getSystemConfigList()
    }

    // 编辑
    const handleEdit = ({ data: { id } }: any) => {
      addSystemConfigFormDialogMode.value = 'edit'
      addConfFormDialogRef.value.dialogFormVisible = true
      nextTick(() => {
        addConfFormDialogRef.value.addSystemConfigFormRef.formMdl.id = id
      })
    }

    // 选中配置分类分类
    const handleSelectSystemCategoryRow = (data: TreeProps) => {
      searchForm.cid = data.id
      getSystemConfigList()
    }

    // 添加系统配置dialog
    const addConfFormDialogRef = ref()
    const addSystemConfigFormDialogMode = ref('add')
    const handleOpenAddConfDialog = () => {
      addSystemConfigFormDialogMode.value = 'add'
      addConfFormDialogRef.value.dialogFormVisible = true
    }
    return {
      categoryEditableTreeMode,
      flexBlockLeftStyles,
      flexBlockCenterStyles,
      flexBlockRightStyles,
      searchFormRef,
      searchForm,
      handleSearch,
      handleResetSearchForm,
      tbIsLoading,
      tbData,
      tbHead,
      getSystemConfigList,
      handleRefresh,
      handlePageSizeChange,
      handleCurrentPageChange,
      handleEdit,
      handleSelectSystemCategoryRow,
      addConfFormDialogRef,
      addSystemConfigFormDialogMode,
      handleOpenAddConfDialog
    }
  }
})
</script>
<style lang="scss" scoped>
.table-search {
  padding: 10px 0 0;
  background: #fff;
  margin-bottom: 5px;
}
</style>
