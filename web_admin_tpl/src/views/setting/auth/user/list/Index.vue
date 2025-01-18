<template>
  <div class="list PAGE-MAIN-CONTENT">
    <ListTable
      title="管理员列表"
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
            <el-form-item label="管理员名称" prop="name">
              <el-input
                v-model="searchForm.name"
                clearable
                placeholder="请输入管理员名称"
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
          @click.stop="$router.push({ path: '/setting/auth/user/add' })"
        >
          添加管理员
        </el-button>
      </template>
    </ListTable>
  </div>
</template>
<script lang="ts">
import { defineComponent, reactive, ref, getCurrentInstance, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import type { ElForm } from 'element-plus'
import ListTable from '@/components/ListTable.vue'
import { HTTP_CONFIG } from '@/config/http'
import { formateDateStr } from '@/utils'
import { TABLE_COLUMN_OPTS_TYPE_LIST } from '@/components/EditableTable.vue'
import { DEFAULT_PAGE_SIZE } from '@/config/pagination'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import useFixKeepAliveListRefresh from '@/hooks/useFixKeepAliveListRefresh'
import useDicData from '@/hooks/useDicData'
import {
  AdminUserListProps,
  adminUserListApi
} from '@/api/adminUser'
type FormInstance = InstanceType<typeof ElForm>
export default defineComponent({
  components: {
    ListTable
  },
  setup () {
    useAutoMainContentHeight()
    const { proxy } = (getCurrentInstance() as any)
    const router = useRouter()
    // 搜索
    const searchFormRef = ref()
    const searchForm = reactive({
      name: ''
    })
    const handleSearch = () => {
      tbData.pageNo = 1
      getAdminUserList()
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
    const dateFormatMethodConf = {
      fn: (params: any, ...extra: any) => {
        return formateDateStr(params * 1000, ...extra)
      },
      params: ['YYYY-MM-DD HH:mm:ss']
    }
    const tbHead: any[] = reactive([
      {
        label: '',
        prop: '$index',
        width: 50,
        overflowTips: false
      },
      {
        label: '管理员名称',
        prop: 'username'
      },
      {
        label: '角色',
        prop: 'roles'
      },
      {
        label: '账号状态',
        prop: 'status',
        formateMethodConf: {
          isShowAsHtml: true,
          fn: (params: any) => {
            const StatusComponent = {
              template: `<el-switch :model-value="${params}" disabled active-text="正常" inactive-text="停用" :active-value="${Number(yesOrNoStatus.value.SYS_YES?.value)}" :inactive-value="${Number(yesOrNoStatus.value.SYS_NO?.value)}"></el-switch>`
            }
            return StatusComponent
          },
          params: []
        },
        width: 150
      },
      {
        label: '最近登录时间',
        prop: 'last_login_time',
        formateMethodConf: dateFormatMethodConf
      },
      {
        label: '最近登录IP',
        prop: 'last_login_ip'
      },
      {
        label: '创建时间',
        prop: 'create_time'
      },
      {
        label: '创建人',
        prop: 'oparate_user'
      },
      {
        label: '操作',
        prop: 'TABLE_COLUMN_OPTS',
        tbColumnOptsTypeList: [
          TABLE_COLUMN_OPTS_TYPE_LIST.EDIT
        ],
        width: 200,
        minWidth: 200
      }
    ])
    const getAdminUserList = async () => {
      const params: AdminUserListProps = {
        name: searchForm.name,
        page: tbData.pageNo,
        size: tbData.pageSize
      }
      tbIsLoading.value = true
      const { status, data, message } = await adminUserListApi(params)
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
    getAdminUserList()

    // 刷新
    const handleRefresh = () => {
      handleResetSearchForm(searchFormRef.value)
      handleSearch()
    }

    // 每页数据显示条数变化响应处理
    const handlePageSizeChange = async ({ size }: {size: number}) => {
      tbData.pageSize = size
      getAdminUserList()
    }
    const handleCurrentPageChange = async ({ page }: {page: number}) => {
      tbData.pageNo = page
      getAdminUserList()
    }

    // 编辑
    const handleEdit = ({ data: { id } }: any) => {
      router.push({ path: '/setting/auth/user/edit', query: { id } })
    }
    useFixKeepAliveListRefresh.executeRefreshJudgment(getAdminUserList)

    // 是否状态字典数据
    const yesOrNoStatus = ref<{[key: string]: any}>({})
    onMounted(async () => {
      const { SYS_YES_NO: sysYesNo } = await useDicData(['SYS_YES_NO'])
      yesOrNoStatus.value = sysYesNo
    })
    return {
      searchFormRef,
      searchForm,
      handleSearch,
      handleResetSearchForm,
      tbIsLoading,
      tbData,
      tbHead,
      handleRefresh,
      handlePageSizeChange,
      handleCurrentPageChange,
      handleEdit,
      yesOrNoStatus
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
