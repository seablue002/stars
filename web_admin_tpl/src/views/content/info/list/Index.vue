<template>
  <div class="system-config-category PAGE-MAIN-CONTENT">
    <FlexBlock
      :leftStyles="flexBlockLeftStyles"
      :centerStyles="flexBlockCenterStyles"
      :rightStyles="flexBlockRightStyles">
      <template #left>
        <ColumnEditableTree
          :mode="columnEditableTreeMode"
          :availableIsLastVal="[0, 1]"
          @add-category="columnEditableTreeMode = 'add'"
          @edit-category="columnEditableTreeMode = 'edit'"
          @select-row="handleSelectInfoRow">
        </ColumnEditableTree>
      </template>
      <template #right>
        <InfoListTable
          title="信息列表"
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
                <el-form-item label="标题" prop="label">
                  <el-input
                    v-model="searchForm.title"
                    clearable
                    placeholder="请输入标题"
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
              @click.stop="$router.push({ path: '/content/info/add' })"
            >
              添加信息
            </el-button>
          </template>
        </InfoListTable>
      </template>
    </FlexBlock>

  </div>
</template>
<script lang="ts">
import { defineComponent, reactive, ref, getCurrentInstance } from 'vue'
import { useRouter } from 'vue-router'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import { TABLE_COLUMN_OPTS_TYPE_LIST } from '@/components/EditableTable.vue'
import { TreeProps } from '@/components/EditableTree.vue'
import FlexBlock from '@/components/FlexBlock.vue'
import ColumnEditableTree from '@/components/business/ColumnEditableTree.vue'
import InfoListTable from '@/components/business/InfoListTable.vue'
import type { ElForm } from 'element-plus'
import { HTTP_CONFIG } from '@/config/http'
import { DEFAULT_PAGE_SIZE } from '@/config/pagination'
import {
  InfoListProps,
  infoListApi
} from '@/api/info'
type FormInstance = InstanceType<typeof ElForm>
export default defineComponent({
  components: {
    FlexBlock,
    ColumnEditableTree,
    InfoListTable
  },
  setup () {
    const { proxy } = (getCurrentInstance() as any)
    const { mainContentDomH } = useAutoMainContentHeight()

    const router = useRouter()

    const columnEditableTreeMode = ref('add')

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
      title: '',
      column_id: ''
    })
    const handleSearch = () => {
      tbData.pageNo = 1
      getInfoList()
    }
    const handleResetSearchForm = (formEl: FormInstance) => {
      formEl.resetFields()
    }

    const dateFormatMethodConf = {
      fn: (params: any, ...extra: any) => {
        return proxy.formateDateStr(params * 1000, ...extra)
      },
      params: ['YYYY-MM-DD HH:mm:ss']
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
        label: '标题',
        prop: 'title'
      },
      {
        label: '副标题',
        prop: 'sub_title'
      },
      {
        label: '发布时间',
        prop: 'publish_time',
        formateMethodConf: dateFormatMethodConf,
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
    const getInfoList = async () => {
      const params: InfoListProps = {
        title: searchForm.title,
        column_id: searchForm.column_id,
        page: tbData.pageNo,
        size: tbData.pageSize
      }
      tbIsLoading.value = true
      const { status, data, message } = await infoListApi(params)
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
    getInfoList()

    // 刷新
    const handleRefresh = () => {
      handleResetSearchForm(searchFormRef.value)
      handleSearch()
    }

    // 每页数据显示条数变化响应处理
    const handlePageSizeChange = async ({ size }: {size: number}) => {
      tbData.pageSize = size
      getInfoList()
    }
    const handleCurrentPageChange = async ({ page }: {page: number}) => {
      tbData.pageNo = page
      getInfoList()
    }

    // 编辑
    const handleEdit = ({ data: { id } }: any) => {
      router.push({ path: '/content/info/edit', query: { id } })
    }

    // 选中信息栏目
    const handleSelectInfoRow = (data: TreeProps) => {
      searchForm.column_id = data.id
      getInfoList()
    }

    return {
      columnEditableTreeMode,
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
      getInfoList,
      handleRefresh,
      handlePageSizeChange,
      handleCurrentPageChange,
      handleEdit,
      handleSelectInfoRow
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
