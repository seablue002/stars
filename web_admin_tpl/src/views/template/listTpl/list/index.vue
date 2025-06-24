<template>
  <div class="page-container page-container--bg PAGE-MAIN-CONTENT">
    <el-card shadow="never" class="border-0">
      <!-- S 搜索表单 -->
      <NTSearchFormFilter
        ref="searchFormFilterRef"
        :model="searchFormFilter"
        :inline="true"
        label-width="80"
        label-position="left"
        :loading="loadding"
        :isShowFoldUnfoldBtn="false"
        :searchHandle="handleSearch"
        :resetHandle="handleReset"
      >
        <template #default>
          <NTSearchFormFilterItem>
            <el-form-item label="模板名称:" prop="name">
              <el-input
                v-model.trim="searchFormFilter.name"
                clearable
                placeholder="请输入模板名称"
                @clear="handleSearch"
              />
            </el-form-item>
          </NTSearchFormFilterItem>

          <NTSearchFormFilterItem>
            <el-form-item label="创建时间" prop="create_time">
              <el-date-picker
                v-model="searchFormFilter.create_time"
                type="datetimerange"
                align="right"
                unlink-panels
                value-format="YYYY-MM-DD HH:mm:ss"
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="结束日期"
                clearable
                @change="handleSearch"
                @clear="handleSearch"
              >
              </el-date-picker>
            </el-form-item>
          </NTSearchFormFilterItem>
        </template>
      </NTSearchFormFilter>
      <!-- E 搜索表单 -->

      <el-row>
        <el-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
          <el-button type="primary" @click="handleAdd">
            <i class="ri-add-line"></i>
            添加
          </el-button>
        </el-col>
      </el-row>

      <!-- S 表格列表 -->
      <NTCustomTable
        ref="tableRef"
        v-loading="loadding"
        :data="dataList"
        :columns="columns"
        stripe
        :show-overflow-tooltip="true"
        :pagination="pagination"
        @page-change="handleCurrentChange"
        @size-change="handleSizeChange"
      >
        <template #otherOperate="{ row }">
          <el-button type="primary" text size="small" @click="handleDetail(row)"
            >详情
          </el-button>
          <el-button type="primary" text size="small" @click="handleEdit(row)"
            >编辑
          </el-button>
        </template>
      </NTCustomTable>
      <!-- E 表格列表 -->
    </el-card>
  </div>
</template>
<script>
import { defineComponent, ref, onMounted } from 'vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import useListPage from '@/hooks/business/useListPage'
import useCachedPageJudgmentRefresh from '@/hooks/useCachedPageJudgmentRefresh'

export default defineComponent({
  name: 'ListTemplateList',
  setup() {
    const { $api, $apiCode, $message, router } = useCurrentInstance()
    const { executeRefreshJudgment } = useCachedPageJudgmentRefresh()

    const tableRef = ref(null)

    // 筛选条件
    const searchFormFilterRef = ref(null)
    const searchFormFilter = ref({
      name: '',
      create_time: [],
    })

    // 列表列配置
    const columns = [
      {
        label: '#',
        prop: '$index',
        width: 150,
      },
      {
        label: '列表模板名称',
        prop: 'name',
      },
      {
        label: '创建时间',
        prop: 'create_time',
        sortable: true,
      },
      {
        label: '更新时间',
        prop: 'update_time',
      },
      {
        label: '操作',
        prop: 'TABLE_COLUMN_OPTS',
        fixed: 'right',
        overflowTooltip: false,
      },
    ]

    // 获取数据列表
    const getDataList = async () => {
      loadding.value = true

      const { name, create_time: createTime } = searchFormFilter.value

      const { currentPage: page, pageSize: size } = tableRef.value.ntTableRef
      const params = {
        name,
        create_time: createTime,
        page,
        size,
      }

      const apiRes = await $api.listTemplate
        .tplListApi(params)
        .catch((error) => {
          $message.error({
            message: error,
            duration: 3000,
          })
          setTimeout(() => {
            // 解决loadding闪烁
            loadding.value = false
          }, 150)
        })

      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS && data) {
        const { data: list, total } = data
        dataList.value = list
        pagination.value.total = total
      } else {
        $message.error({
          message,
          duration: 3000,
        })
      }
      setTimeout(() => {
        // 解决loadding闪烁
        loadding.value = false
      }, 150)
    }

    executeRefreshJudgment(getDataList)

    const {
      pagination,
      list: dataList,
      loadding,
      handleCurrentChange,
      handleSizeChange,
      handleSearch,
      handleReset,
    } = useListPage({
      searchFormFilterRef,
      tableRef,
      getDataList,
    })

    onMounted(() => {
      handleSearch()
    })

    // 添加
    const handleAdd = () => {
      router.push({ path: '/template/listTpl/add' })
    }

    // 编辑
    const handleEdit = ({ id }) => {
      router.push({ path: '/template/listTpl/edit', query: { id } })
    }

    // 详情
    const handleDetail = ({ id }) => {
      router.push({ path: '/template/listTpl/detail', query: { id } })
    }

    return {
      tableRef,
      searchFormFilterRef,
      searchFormFilter,
      loadding,
      columns,
      pagination,
      dataList,
      handleCurrentChange,
      handleSizeChange,
      handleSearch,
      handleReset,
      handleAdd,
      handleEdit,
      handleDetail,
    }
  },
})
</script>
<style lang="scss" scoped></style>
