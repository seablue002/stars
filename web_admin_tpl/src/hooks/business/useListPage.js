import { ref } from 'vue'
import { PAGE_SIZES } from '@/settings/config/app'

const useListPage = (params) => {
  const {
    searchFormFilterRef,
    tableRef,
    getDataList,
    pagination: paginationCustom,
  } = params

  const list = ref([])
  // 列表数据总条数
  const total = ref(0)

  // 分页参数
  const pagination = ref({
    // 当前页码
    currentPage: 1,
    // 每页条数
    pageSize: PAGE_SIZES[0],
    // 是否显示分页
    isShow: true,
    // 总条数
    total: 0,
    ...paginationCustom,
  })

  // loadding加载状态
  const loadding = ref(false)

  // 页码变化
  const handleCurrentChange = () => {
    getDataList()
  }

  // 每页条数选项变化
  const handleSizeChange = () => {
    getDataList()
  }

  // 搜索
  const handleSearch = () => {
    tableRef.value.currentPage = 1
    getDataList()
  }

  // 重置
  const handleReset = () => {
    // 重置表单搜索条件
    tableRef.value.currentPage = 1
    if (searchFormFilterRef.value) {
      searchFormFilterRef.value.$refs.ntSearchFormFilterForm.resetFields()
    }

    handleSearch()
  }

  return {
    list,
    total,
    pagination,
    loadding,
    handleCurrentChange,
    handleSizeChange,
    handleSearch,
    handleReset,
  }
}

export default useListPage
