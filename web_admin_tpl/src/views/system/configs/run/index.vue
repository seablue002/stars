<template>
  <div class="page-container page-container--bg PAGE-MAIN-CONTENT">
    <NTFoldingBox
      :leftStyles="foldingBoxLeftStyles"
      :rightStyles="foldingBoxRightStyles"
      >>
      <template #left>
        <NTEditableSystemConfigCategoryTree
          ref="editableCategoryTreeRef"
          :availableIsLastVal="[0, 1]"
          @init-category-list="handleInitCategoryList"
          @select-row="handleSelectInfoRow"
        >
        </NTEditableSystemConfigCategoryTree>
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
            :loading="loadding"
            :isShowFoldUnfoldBtn="false"
            :searchHandle="handleSearch"
            :resetHandle="handleReset"
          >
            <template #default>
              <NTSearchFormFilterItem>
                <el-form-item label="配置名称:" prop="title">
                  <el-input
                    v-model.trim="searchFormFilter.title"
                    clearable
                    placeholder="请输入配置名称"
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

          <!-- S 配置添加、修改弹窗 -->
          <component
            :is="ConfigsRunDialogAsyncComp"
            ref="configsRunDialogAsyncCompRef"
            @add-success="handleSystemConfigAddSuccess"
            @edit-success="handleSystemConfigEditSuccess"
          ></component>
          <!-- E 配置添加、修改弹窗 -->
        </el-card>
      </template>
    </NTFoldingBox>
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  onMounted,
  resolveComponent,
  h,
  computed,
} from 'vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import useListPage from '@/hooks/business/useListPage'
import useAutoMainContentHeight from '@/hooks/useAutoMainContentHeight'
import useCachedPageJudgmentRefresh from '@/hooks/useCachedPageJudgmentRefresh'
import useAsyncComponent from '@/hooks/useAsyncComponent'
import useCommonStore from '@/store/modules/common'

export default defineComponent({
  name: 'InfoList',
  setup() {
    const { mainContentDomH } = useAutoMainContentHeight()
    const { $api, $apiCode, $message, $confirm } = useCurrentInstance()
    const commonStore = useCommonStore()

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
      cid: null,
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
        label: '配置名称',
        prop: 'name',
      },
      {
        label: '所属分类',
        prop: 'category_name',
      },
      {
        label: '字段名称',
        prop: 'field',
      },
      {
        label: '默认值',
        prop: 'default_value',
      },
      {
        label: '状态',
        prop: 'status',
        dataFormatConf: {
          renderType: 'html',
          withScopeRow: true,
          formatFunction: ({ value }) => {
            const component = {
              setup() {
                const ElSwitch = resolveComponent('ElSwitch')
                return () => {
                  return h(ElSwitch, {
                    'model-value': value,
                    'active-text': '正常',
                    'inactive-text': '停用',
                    'active-value': Number(yesOrNoStatus.value?.SYS_YES?.value),
                    'inactive-value': Number(
                      yesOrNoStatus.value?.SYS_NO?.value
                    ),
                  })
                }
              },
            }

            return component
          },
        },
      },
      {
        label: '排序',
        prop: 'sort',
        sortable: true,
      },
      {
        label: '创建时间',
        prop: 'create_time',
        sortable: true,
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

      const { cid, title, create_time: createTime } = searchFormFilter.value

      const { currentPage: page, pageSize: size } = tableRef.value.ntTableRef
      const params = {
        cid,
        title,
        create_time: createTime,
        page,
        size,
      }

      const apiRes = await $api.systemConfig
        .systemConfigListApi(params)
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

    const configsRunDialogAsyncCompRef = ref(null)
    let configsRunDialogAsyncCompTrigger = null
    const { AsyncComponent: ConfigsRunDialogAsyncComp } = useAsyncComponent({
      component: () =>
        import('@/components/business/NTEditableSystemConfigDialog/index.vue'),
      wait: async () => {
        await new Promise((resolve) => {
          configsRunDialogAsyncCompTrigger = resolve
        })
      },
    })

    const handleAdd = () => {
      handleShowConfigsRunDialog({ mode: 'add' })
    }

    // 编辑
    const handleEdit = ({ id }) => {
      handleShowConfigsRunDialog({ mode: 'edit', id })
    }

    // 详情
    const handleDetail = ({ id }) => {
      handleShowConfigsRunDialog({ mode: 'detail', id })
    }

    const handleShowConfigsRunDialog = (data) => {
      const { mode } = data
      configsRunDialogAsyncCompTrigger()
      setTimeout(() => {
        if (mode === 'add') {
          configsRunDialogAsyncCompRef.value.open({
            mode,
          })
        } else if (['edit', 'detail'].includes(mode)) {
          const { id } = data
          configsRunDialogAsyncCompRef.value.open({
            id,
            mode,
          })
        }
      }, 50)
    }

    const handleSystemConfigAddSuccess = () => {
      getDataList()
    }

    const handleSystemConfigEditSuccess = () => {
      getDataList()
    }

    // 删除
    const handleDelete = (row) => {
      const { name } = row
      $confirm(`确定删除配置${name}吗？`, '提示', {
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
      const apiRes = await $api.systemConfig.deleteSystemConfigApi(data)
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

    // 选中分类
    const editableCategoryTreeRef = ref()

    const handleInitCategoryList = (list) => {
      if (list.length) {
        editableCategoryTreeRef.value.editableTreeRef.elTreeRef.setCurrentKey(
          list[0].id
        )
        handleSelectInfoRow(list[0])
      }
    }
    const handleSelectInfoRow = (data) => {
      searchFormFilter.value.cid = data.id
      if (data.id) {
        handleReset()
      }
    }

    commonStore.getDict(['SYS_YES_NO'])
    const yesOrNoStatus = computed(() => {
      return commonStore.dict.SYS_YES_NO
    })

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
      configsRunDialogAsyncCompRef,
      ConfigsRunDialogAsyncComp,
      handleDelete,
      handleSystemConfigAddSuccess,
      handleSystemConfigEditSuccess,
      editableCategoryTreeRef,
      handleInitCategoryList,
      handleSelectInfoRow,
      yesOrNoStatus,
    }
  },
})
</script>
<style lang="scss" scoped>
:deep(.cover) {
  width: 65px;
}
</style>
