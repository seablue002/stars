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
            <el-form-item label="label名称:" prop="name">
              <el-input
                v-model.trim="searchFormFilter.name"
                clearable
                placeholder="请输入label名称"
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

    <!-- S 栏目自定义字段添加、修改弹窗 -->
    <component
      :is="EditableColumnExtendFieldsDialogAsyncComp"
      ref="editableColumnExtendFieldsDialogAsyncCompRef"
      @add-success="handleColumnExtendFieldsAddSuccess"
      @edit-success="handleColumnExtendFieldsEditSuccess"
    ></component>
    <!-- E 栏目自定义字段添加、修改弹窗 -->
  </div>
</template>
<script>
import {
  defineComponent,
  ref,
  onMounted,
  computed,
  resolveComponent,
  h,
} from 'vue'
import useCommonStore from '@/store/modules/common'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import useListPage from '@/hooks/business/useListPage'
import useAsyncComponent from '@/hooks/useAsyncComponent'

export default defineComponent({
  name: 'ColumnExtraFieldsList',
  setup() {
    const { $api, $apiCode, $message } = useCurrentInstance()
    const commonStore = useCommonStore()

    const tableRef = ref(null)

    // 筛选条件
    const searchFormFilterRef = ref(null)
    const searchFormFilter = ref({
      column_id: null,
      name: '',
      create_time: [],
    })

    // 列表列配置
    const columns = [
      {
        label: '#',
        prop: '$index',
      },
      {
        label: 'label名称',
        prop: 'name',
      },
      {
        label: '字段名',
        prop: 'field',
      },
      {
        label: '元素类型',
        prop: 'props',
        width: 150,
        dataFormatConf: {
          withScopeRow: false,
          formatFunction: ({ type }) => {
            return type
          },
        },
      },
      {
        label: '默认值',
        prop: 'props',
        dataFormatConf: {
          withScopeRow: false,
          formatFunction: ({ value }) => {
            return value
          },
        },
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

      const {
        column_id: columnId,
        name,
        create_time: createTime,
      } = searchFormFilter.value

      const { currentPage: page, pageSize: size } = tableRef.value.ntTableRef
      const params = {
        column_id: columnId,
        name,
        create_time: createTime,
        page,
        size,
      }

      const apiRes = await $api.columnExtendFieldsConfig
        .columnExtendFieldsConfigListApi(params)
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
      handleShowEditableColumnExtendFieldsDialog({ mode: 'add' })
    }

    // 编辑
    const handleEdit = ({ id }) => {
      handleShowEditableColumnExtendFieldsDialog({ mode: 'edit', id })
    }

    // 详情
    const handleDetail = ({ id }) => {
      handleShowEditableColumnExtendFieldsDialog({ mode: 'detail', id })
    }

    commonStore.getDict(['SYS_YES_NO'])
    const yesOrNoStatus = computed(() => {
      return commonStore.dict.SYS_YES_NO
    })

    // 栏目添加、修改弹窗
    const editableColumnExtendFieldsDialogAsyncCompRef = ref(null)
    let editableColumnExtendFieldsDialogAsyncCompTrigger = null
    const { AsyncComponent: EditableColumnExtendFieldsDialogAsyncComp } =
      useAsyncComponent({
        component: () =>
          import(
            '@/components/business/NTEditableColumnExtendFieldsDialog/index.vue'
          ),
        wait: async () => {
          await new Promise((resolve) => {
            editableColumnExtendFieldsDialogAsyncCompTrigger = resolve
          })
        },
      })

    const handleShowEditableColumnExtendFieldsDialog = (data) => {
      const { mode } = data
      editableColumnExtendFieldsDialogAsyncCompTrigger()
      setTimeout(() => {
        if (mode === 'add') {
          editableColumnExtendFieldsDialogAsyncCompRef.value.open({
            mode,
          })
        } else if (['edit', 'detail'].includes(mode)) {
          const { id } = data
          editableColumnExtendFieldsDialogAsyncCompRef.value.open({
            id,
            mode,
          })
        }
      }, 50)
    }

    const handleColumnExtendFieldsAddSuccess = () => {
      handleSearch()
    }

    const handleColumnExtendFieldsEditSuccess = () => {
      handleSearch()
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
      yesOrNoStatus,
      editableColumnExtendFieldsDialogAsyncCompRef,
      EditableColumnExtendFieldsDialogAsyncComp,
      handleShowEditableColumnExtendFieldsDialog,
      handleColumnExtendFieldsAddSuccess,
      handleColumnExtendFieldsEditSuccess,
    }
  },
})
</script>
<style lang="scss" scoped></style>
