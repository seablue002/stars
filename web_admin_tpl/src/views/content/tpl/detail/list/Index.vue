<template>
  <div class="list PAGE-MAIN-CONTENT">
    <ListTable
      title="管理详情模板"
      :tbHead="tbHead"
      :tbData="tbData"
      :tbIsLoading="tbIsLoading"
      @refresh="handleRefresh"
      @edit="handleEdit"
      @detail="handleDetail"
      @delete="handleDelete"
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
            <el-form-item label="模板名称" prop="name">
              <el-input
                v-model="searchForm.name"
                clearable
                placeholder="请输入详情模板名称"
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
          @click.stop="$router.push({ path: '/tpl/detail/add' })"
        >
          添加详情模板
        </el-button>
      </template>
    </ListTable>
  </div>
</template>
<script lang="ts">
import { defineComponent, reactive, ref, getCurrentInstance } from 'vue'
import { useRouter } from 'vue-router'
import type { ElForm } from 'element-plus'
import ListTable from '@/components/ListTable.vue'
import { HTTP_CONFIG } from '@/config/http'
import { TABLE_COLUMN_OPTS_TYPE_LIST } from '@/components/EditableTable.vue'
import { DEFAULT_PAGE_SIZE } from '@/config/pagination'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import useFixKeepAliveListRefresh from '@/hooks/useFixKeepAliveListRefresh'
import {
  TplListProps,
  tplListApi,
  DeleteTplProps,
  deleteTplApi
} from '@/api/detailTpl'
type FormInstance = InstanceType<typeof ElForm>
export default defineComponent({
  name: 'DetailTplList',
  components: {
    ListTable
  },
  setup () {
    useAutoMainContentHeight()

    const router = useRouter()
    const { proxy } = (getCurrentInstance() as any)
    // 搜索
    const searchFormRef = ref()
    const searchForm = reactive({
      name: ''
    })
    const handleSearch = () => {
      tbData.pageNo = 1
      getDetailTplList()
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
        label: '详情模板名称',
        prop: 'name'
      },
      {
        label: '添加时间',
        prop: 'create_time'
      },
      {
        label: '更新时间',
        prop: 'update_time'
      },
      {
        label: '操作',
        prop: 'TABLE_COLUMN_OPTS',
        tbColumnOptsTypeList: [
          TABLE_COLUMN_OPTS_TYPE_LIST.DETAIL,
          TABLE_COLUMN_OPTS_TYPE_LIST.EDIT,
          TABLE_COLUMN_OPTS_TYPE_LIST.DELETE
        ],
        width: 220,
        minWidth: 220
      }
    ])
    const getDetailTplList = async () => {
      const params: TplListProps = {
        name: searchForm.name,
        page: tbData.pageNo,
        size: tbData.pageSize
      }
      tbIsLoading.value = true
      const { status, data, message } = await tplListApi(params)
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
    getDetailTplList()

    // 刷新
    const handleRefresh = () => {
      handleResetSearchForm(searchFormRef.value)
      handleSearch()
    }

    // 编辑
    const handleEdit = ({ data: { id } }: any) => {
      router.push({ path: '/tpl/detail/edit', query: { id } })
    }

    // 详情
    const handleDetail = ({ data: { id } }: any) => {
      router.push({ path: '/tpl/detail/detail', query: { id } })
    }

    // 删除
    const handleDelete = async ({ data: { id } }: any) => {
      const cb = await proxy.$msgbox({
        title: '提示',
        message: '刪除操作将不可逆转，确定删除?',
        confirmButtonText: '立即删除',
        cancelButtonText: '取消',
        showCancelButton: true,
        type: 'warning'
      }).then(() => {
        return Promise.resolve(true)
      }).catch(() => {
        return Promise.resolve(false)
      })
      if (!cb) {
        return
      }
      delDetailTpl(id)
    }
    const delDetailTpl = async (id: number) => {
      const params: DeleteTplProps = {
        id
      }
      tbIsLoading.value = true
      const { status, message } = await deleteTplApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        proxy.message({
          type: 'success',
          message,
          duration: 3000
        })
        getDetailTplList()
      } else {
        proxy.message({
          type: 'warning',
          message,
          duration: 3000
        })
      }
      tbIsLoading.value = false
    }

    // 每页数据显示条数变化响应处理
    const handlePageSizeChange = async ({ size }: {size: number}) => {
      tbData.pageSize = size
      getDetailTplList()
    }
    const handleCurrentPageChange = async ({ page }: {page: number}) => {
      tbData.pageNo = page
      getDetailTplList()
    }

    useFixKeepAliveListRefresh.executeRefreshJudgment(getDetailTplList)

    return {
      searchFormRef,
      searchForm,
      handleSearch,
      handleResetSearchForm,
      tbIsLoading,
      tbData,
      tbHead,
      handleRefresh,
      handleEdit,
      handleDetail,
      handleDelete,
      handlePageSizeChange,
      handleCurrentPageChange
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
