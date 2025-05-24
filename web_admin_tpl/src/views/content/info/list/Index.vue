<template>
  <div class="page-container page-container--bg PAGE-MAIN-CONTENT">
    <NTFoldingBox
      :leftStyles="foldingBoxLeftStyles"
      :rightStyles="foldingBoxRightStyles"
      >>
      <template #left>
        <NTEditableColumnTree
          ref="editableColumnTreeRef"
          :availableIsLastVal="[0, 1]"
          @init-column-list="handleInitColumnList"
          @select-row="handleSelectInfoRow"
        >
        </NTEditableColumnTree>
      </template>

      <template #right>
        <el-card shadow="never" class="border-0">
          <!-- S 搜索表单 -->
          <NTSearchFormFilter
            ref="searchFormFilterRef"
            :model="searchFormFilter"
            :inline="true"
            label-width="80"
            label-position="left"
            :btnsCol="{ lg: 12 }"
            :loading="loadding"
            :isShowFoldUnfoldBtn="false"
            :searchHandle="handleSearch"
            :resetHandle="handleReset"
          >
            <template #default>
              <NTSearchFormFilterItem>
                <el-form-item label="标题:" prop="title">
                  <el-input
                    v-model.trim="searchFormFilter.title"
                    clearable
                    placeholder="请输入标题"
                    @clear="handleSearch"
                  />
                </el-form-item>
              </NTSearchFormFilterItem>

              <NTSearchFormFilterItem :xl="10">
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
              <el-button
                type="primary"
                text
                size="small"
                @click="handleDetail(row)"
                >详情
              </el-button>
              <el-button
                type="primary"
                text
                size="small"
                @click="handleEdit(row)"
                >编辑
              </el-button>
              <el-button
                type="danger"
                text
                size="small"
                @click="handleDelete(row)"
              >
                删除
              </el-button>
            </template>
          </NTCustomTable>
          <!-- E 表格列表 -->
        </el-card>
      </template>
    </NTFoldingBox>
  </div>
</template>
<script>
import { defineComponent, ref, onMounted, resolveComponent, h } from 'vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import useListPage from '@/hooks/business/useListPage'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import useCachedPageJudgmentRefresh from '@/hooks/useCachedPageJudgmentRefresh'
import { API_HOST } from '@/settings/config/http'

export default defineComponent({
  name: 'InfoList',
  setup() {
    const { mainContentDomH } = useAutoMainContentHeight()
    const { $api, $apiCode, $message, $confirm, $date, router } =
      useCurrentInstance()
    const { formatDate } = $date
    const { executeRefreshJudgment } = useCachedPageJudgmentRefresh()

    const foldingBoxLeftStyles = ref({})
    foldingBoxLeftStyles.value = {
      height: mainContentDomH,
    }

    const foldingBoxRightStyles = ref({})
    foldingBoxRightStyles.value = {
      'min-height': mainContentDomH,
    }

    const tableRef = ref(null)

    // 筛选条件
    const searchFormFilterRef = ref(null)
    const searchFormFilter = ref({
      column_id: null,
      title: '',
      create_time: [],
    })

    // 列表列配置
    const columns = [
      {
        label: '#',
        prop: '$index',
        width: 80,
      },
      {
        label: '标题',
        prop: 'title',
      },
      {
        label: '副标题',
        prop: 'sub_title',
      },
      {
        label: '封面',
        prop: 'cover_url',
        dataFormatConf: {
          renderType: 'html',
          withScopeRow: true,
          formatFunction: ({ value }) => {
            if (value === '') {
              const component = {
                setup() {
                  return () => {
                    return h('span', null, '无')
                  }
                },
              }

              return component
            }

            const cover = `${API_HOST}storage/${value}`
            const component = {
              setup() {
                const ElImage = resolveComponent('ElImage')
                return () => {
                  return h(ElImage, {
                    src: cover,
                    fit: 'cover',
                    'preview-src-list': [cover],
                    'preview-teleported': true,
                    'hide-on-click-modal': true,
                    class: 'cover',
                  })
                }
              },
            }

            return component
          },
        },
        minWidth: 60,
      },
      {
        label: '发布时间',
        prop: 'publish_time',
        dataFormatConf: {
          formatFunction: (value) => {
            return formatDate(value * 1000)
          },
        },
      },
      {
        label: '创建时间',
        prop: 'create_time',
      },
      {
        label: '操作',
        prop: 'TABLE_COLUMN_OPTS',
        fixed: 'right',
        width: 200,
        overflowTooltip: false,
      },
    ]

    // 获取数据列表
    const getDataList = async () => {
      loadding.value = true

      const {
        column_id: columnId,
        title,
        create_time: createTime,
      } = searchFormFilter.value

      const { currentPage: page, pageSize: size } = tableRef.value.ntTableRef
      const params = {
        column_id: columnId,
        title,
        create_time: createTime,
        page,
        size,
      }

      const apiRes = await $api.info.infoListApi(params).catch((error) => {
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

    const handleAdd = () => {
      router.push({ path: '/content/info/add' })
    }

    // 编辑
    const handleEdit = ({ id }) => {
      router.push({ path: '/content/info/edit', query: { id } })
    }

    // 详情
    const handleDetail = ({ id }) => {
      router.push({ path: '/content/info/detail', query: { id } })
    }

    // 删除
    const handleDelete = (row) => {
      $confirm('确定删除信息吗？', '提示', {
        type: 'warning',
      })
        .then(async () => {
          await deleteInfo(row)
        })
        .catch(() => {})
    }

    const deleteInfo = async ({ id }) => {
      const data = {
        id,
      }
      const apiRes = await $api.info.deleteInfoApi(data)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        getDataList()
        $message.success({
          message,
          duration: 3000,
        })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 选中信息栏目
    const editableColumnTreeRef = ref()

    const handleInitColumnList = (list) => {
      const columnList = list.filter((column) => {
        return column.is_last === 1
      })

      if (columnList.length) {
        editableColumnTreeRef.value.editableTreeRef.elTreeRef.setCurrentKey(
          columnList[0].id
        )
        handleSelectInfoRow(columnList[0])
      }
    }
    const handleSelectInfoRow = (data) => {
      searchFormFilter.value.column_id = data.id
      if (data.id) {
        handleReset()
      }
    }

    return {
      foldingBoxLeftStyles,
      foldingBoxRightStyles,
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
      handleDelete,
      editableColumnTreeRef,
      handleInitColumnList,
      handleSelectInfoRow,
    }
  },
})
</script>
<style lang="scss" scoped>
:deep(.cover) {
  width: 65px;
}
</style>
