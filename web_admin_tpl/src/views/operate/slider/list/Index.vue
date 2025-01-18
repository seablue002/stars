<template>
  <div class="list PAGE-MAIN-CONTENT">
    <ListTable
      title="轮播图列表"
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
            <el-form-item label="轮播图名称" prop="name">
              <el-input
                v-model="searchForm.name"
                clearable
                placeholder="请输入轮播图名称"
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
          @click.stop="$router.push({ path: '/operate/slider/add' })"
        >
          添加轮播图
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
import { HTTP_CONFIG, RESOURCE_PATH } from '@/config/http'
import { formateDateStr } from '@/utils'
import { TABLE_COLUMN_OPTS_TYPE_LIST } from '@/components/EditableTable.vue'
import { DEFAULT_PAGE_SIZE } from '@/config/pagination'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import useFixKeepAliveListRefresh from '@/hooks/useFixKeepAliveListRefresh'
import useDicData from '@/hooks/useDicData'
import {
  SliderListProps,
  sliderListApi,
  DelSliderProps,
  delSliderApi
} from '@/api/slider'
type FormInstance = InstanceType<typeof ElForm>
export default defineComponent({
  name: 'SliderList',
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
      getSliderList()
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
      // {
      //   label: '选择框',
      //   prop: 'TABLE_COLUMN_CHECKBOX'
      // },
      {
        label: '',
        prop: '$index',
        width: 50,
        overflowTips: false
      },
      {
        label: '图片',
        formateMethodConf: {
          mode: 'needCurRowParams',
          isShowAsHtml: true,
          fn: ({ curRow }: any) => {
            const ThumbComponent = {
              template: `<el-image fit="cover" :style="{ width: '45px', height: '45px' }" src="${RESOURCE_PATH}${curRow.url}"></el-image>`
            }
            return curRow.url ? ThumbComponent : null
          },
          params: []
        },
        width: 80
      },
      {
        label: '轮播图名称',
        prop: 'name'
      },
      {
        label: '跳转路径',
        prop: 'page_url'
      },
      {
        label: '排序',
        prop: 'sort',
        width: 100
      },
      {
        label: '状态',
        prop: 'status',
        formateMethodConf: {
          isShowAsHtml: true,
          fn: (params: any) => {
            const StatusComponent = {
              template: `<el-switch :model-value="${params}" disabled active-text="上架" inactive-text="下架" :active-value="${Number(yesOrNoStatus.value.SYS_YES?.value)}" :inactive-value="${Number(yesOrNoStatus.value.SYS_NO?.value)}"></el-switch>`
            }
            return StatusComponent
          },
          params: []
        },
        width: 150,
        minWidth: 150
      },
      {
        label: '备注',
        prop: 'remarks'
      },
      {
        label: '添加时间',
        prop: 'create_time',
        formateMethodConf: dateFormatMethodConf
      },
      {
        label: '更新时间',
        prop: 'update_time',
        formateMethodConf: dateFormatMethodConf
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
    const getSliderList = async () => {
      const params: SliderListProps = {
        name: searchForm.name,
        page: tbData.pageNo,
        size: tbData.pageSize
      }
      tbIsLoading.value = true
      const { status, data, message } = await sliderListApi(params)
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
    getSliderList()

    // 刷新
    const handleRefresh = () => {
      handleResetSearchForm(searchFormRef.value)
      handleSearch()
    }

    // 编辑
    const handleEdit = ({ data: { id } }: any) => {
      router.push({ path: '/operate/slider/edit', query: { id } })
    }

    // 详情
    const handleDetail = ({ data: { id } }: any) => {
      router.push({ path: '/operate/slider/detail', query: { id } })
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
      delSlider(id)
    }
    const delSlider = async (id: number) => {
      const params: DelSliderProps = {
        id
      }
      tbIsLoading.value = true
      const { status, message } = await delSliderApi(params)
      if (status === HTTP_CONFIG.API_SUCCESS_CODE) {
        proxy.message({
          type: 'success',
          message,
          duration: 3000
        })
        getSliderList()
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
      getSliderList()
    }
    const handleCurrentPageChange = async ({ page }: {page: number}) => {
      tbData.pageNo = page
      getSliderList()
    }

    useFixKeepAliveListRefresh.executeRefreshJudgment(getSliderList)

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
      handleEdit,
      handleDetail,
      handleDelete,
      handlePageSizeChange,
      handleCurrentPageChange,
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
