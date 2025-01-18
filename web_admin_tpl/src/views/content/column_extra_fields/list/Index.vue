<template>
  <div class="column-extend-fields-config PAGE-MAIN-CONTENT">
    <ColumnExtendFieldsConfigListTable
      title="自定义字段列表"
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
            <el-form-item label="label名称" prop="label">
              <el-input
                v-model="searchForm.label"
                clearable
                placeholder="请输入字段label名称"
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
          添加字段
        </el-button>
      </template>
    </ColumnExtendFieldsConfigListTable>

    <AddColumnExtendFieldsConfigFormDialog
      ref="addConfFormDialogRef"
      :mode="addColumnExtendFieldsConfigFormDialogMode"
      @add-success="getColumnExtendFieldsConfigList"
      @edit-success="getColumnExtendFieldsConfigList">
    </AddColumnExtendFieldsConfigFormDialog>
  </div>
</template>
<script lang="ts">
import { defineComponent, reactive, ref, getCurrentInstance, nextTick } from 'vue'
import { TABLE_COLUMN_OPTS_TYPE_LIST } from '@/components/EditableTable.vue'
import ColumnExtendFieldsConfigListTable from '@/components/business/ColumnExtendFieldsConfigListTable.vue'
import AddColumnExtendFieldsConfigFormDialog from '@/components/business/AddColumnExtendFieldsConfigFormDialog.vue'
import type { ElForm } from 'element-plus'
import { HTTP_CONFIG } from '@/config/http'
import { DEFAULT_PAGE_SIZE } from '@/config/pagination'
import {
  ColumnExtendFieldsConfigListProps,
  columnExtendFieldsConfigListApi
} from '@/api/columnExtendFieldsConfig'
type FormInstance = InstanceType<typeof ElForm>
export default defineComponent({
  components: {
    ColumnExtendFieldsConfigListTable,
    AddColumnExtendFieldsConfigFormDialog
  },
  setup () {
    const { proxy } = (getCurrentInstance() as any)

    // 搜索
    const searchFormRef = ref()
    const searchForm = reactive({
      label: ''
    })
    const handleSearch = () => {
      tbData.pageNo = 1
      getColumnExtendFieldsConfigList()
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
        label: '字段label名称',
        prop: 'label'
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
    const getColumnExtendFieldsConfigList = async () => {
      const params: ColumnExtendFieldsConfigListProps = {
        label: searchForm.label,
        page: tbData.pageNo,
        size: tbData.pageSize
      }
      tbIsLoading.value = true
      const { status, data, message } = await columnExtendFieldsConfigListApi(params)
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
    getColumnExtendFieldsConfigList()

    // 刷新
    const handleRefresh = () => {
      handleResetSearchForm(searchFormRef.value)
      handleSearch()
    }

    // 每页数据显示条数变化响应处理
    const handlePageSizeChange = async ({ size }: {size: number}) => {
      tbData.pageSize = size
      getColumnExtendFieldsConfigList()
    }
    const handleCurrentPageChange = async ({ page }: {page: number}) => {
      tbData.pageNo = page
      getColumnExtendFieldsConfigList()
    }

    // 编辑
    const handleEdit = ({ data: { id } }: any) => {
      addColumnExtendFieldsConfigFormDialogMode.value = 'edit'
      addConfFormDialogRef.value.dialogFormVisible = true
      nextTick(() => {
        addConfFormDialogRef.value.addColumnExtendFieldsConfigFormRef.formMdl.id = id
      })
    }

    // 添加系统字段dialog
    const addConfFormDialogRef = ref()
    const addColumnExtendFieldsConfigFormDialogMode = ref('add')
    const handleOpenAddConfDialog = () => {
      addColumnExtendFieldsConfigFormDialogMode.value = 'add'
      addConfFormDialogRef.value.dialogFormVisible = true
    }
    return {
      searchFormRef,
      searchForm,
      handleSearch,
      handleResetSearchForm,
      tbIsLoading,
      tbData,
      tbHead,
      getColumnExtendFieldsConfigList,
      handleRefresh,
      handlePageSizeChange,
      handleCurrentPageChange,
      handleEdit,
      addConfFormDialogRef,
      addColumnExtendFieldsConfigFormDialogMode,
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
