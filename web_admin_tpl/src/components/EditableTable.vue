<template>
  <div class="editable-table" @mouseleave="hideTbColumnSettingPopover">
    <el-table
      ref="elTableRef"
      :data="tbDataData.data"
      v-loading="loading"
      :size="tbSize"
      :height="styleConf.height"
      :max-height="styleConf.maxHeight"
      :header-cell-style="{ height: '50px', background: '#fafafa' }"
      :header-cell-class-name="cellClass"
      style="width: 100%"
      @selection-change="handleSelectionChange"
      @select-all="handleSelectionSelectAll"
      @select="handleSelectionSelect"
      @row-click="handleRowClick"
    >
      <template v-slot>
        <el-table-column
          v-if="hasExpand"
          type="expand">
          <template v-slot="scope">
            <slot name="expand" :data="scope.row"></slot>
          </template>
        </el-table-column>
        <el-table-column
          v-for="colAttrConf in tbHeadData"
          :key="colAttrConf.prop"
          :label="colAttrConf.label"
          :prop="colAttrConf.prop"
          :fixed="colAttrConf.fixed"
          :width="colAttrConf.width"
          :min-width="colAttrConf.minWidth"
          :show-overflow-tooltip="colAttrConf.overflowTips"
          :type="colAttrConf.prop === 'TABLE_COLUMN_CHECKBOX' ? 'selection' : ''"
          :selectable="(row: any) => !row.isDisableCheck"
          :sortable="colAttrConf.sortable"
        >
          <template #header>
            <el-popover
              v-if="colAttrConf.prop === '$index'"
              title="表格列设置"
              placement="bottom-start"
              :width="500"
              :offset="-10"
              trigger="manual"
              :visible="tbColumnSettingPopoverVisible"
              popper-class="tb-column-setting-popover"
              @click.stop>
              <template #reference>
                <a
                  href="javascript:void(0)"
                  @click="handleTriggleTbColumnSettingPopoverVisible"
                  style="display: flex;align-items: center;">
                  <el-tooltip content="表格列设置" placement="top" effect="light">
                    <el-icon :size="14" @click="$emit('setting-column')"><setting /></el-icon>
                  </el-tooltip>
                  &nbsp;
                  <span>{{colAttrConf.label}}</span>
                </a>
              </template>
              <el-card shadow="never" @mouseleave="hideTbColumnSettingPopover">
                <el-checkbox-group v-model="tbColumnSettingCheckList" @change="handleTbColumnSettingChange">
                  <el-checkbox
                    v-for="colAttrConf in tbHeadDataOriginCopy"
                    :key="colAttrConf.prop"
                    v-show="!DISABLE_SETTING_COLUMNS_PROP.includes(colAttrConf.prop)"
                    :label="colAttrConf"
                  >
                    {{colAttrConf.label}}
                  </el-checkbox>
                </el-checkbox-group>
              </el-card>
            </el-popover>
            <template v-else>
              <template v-if="colAttrConf.tbHdConf?.isShowHdTips">
                <span class="col-hd-tit">{{colAttrConf.label}}</span>
                <el-tooltip :content="colAttrConf.tbHdConf.tipsText" placement="top" effect="light">
                  <el-icon :size="16"><Warning /></el-icon>
                </el-tooltip>
              </template>
              <span v-else class="col-hd-tit">{{colAttrConf.label}}</span>
            </template>
          </template>
          <template v-slot="scope" v-if="colAttrConf.prop !== 'TABLE_COLUMN_CHECKBOX'">
            <template v-if="scope.column.property === '$index'">
              <span>{{ scope.$index + 1 }}</span>
            </template>

            <template v-else-if="scope.column.property === 'TABLE_COLUMN_OPTS'">
              <div class="tb-column-opts">
                <el-button
                  v-if="colAttrConf.tbColumnOptsTypeList.includes(TABLE_COLUMN_OPTS_TYPE_LIST.DETAIL) && !(colAttrConf.tbColumnOptsTypeMethodsList && isFunction(colAttrConf.tbColumnOptsTypeMethodsList[TABLE_COLUMN_OPTS_TYPE_LIST.DETAIL]) && colAttrConf.tbColumnOptsTypeMethodsList[TABLE_COLUMN_OPTS_TYPE_LIST.DETAIL](scope.row))"
                  type="primary"
                  text
                  size="small"
                  @click.stop="$emit('detail', { data: scope.row })"
                >
                  <el-icon><reading /></el-icon>&nbsp;详情
                </el-button>
                <el-button
                  v-if="colAttrConf.tbColumnOptsTypeList.includes(TABLE_COLUMN_OPTS_TYPE_LIST.EDIT) && !(colAttrConf.tbColumnOptsTypeMethodsList && isFunction(colAttrConf.tbColumnOptsTypeMethodsList[TABLE_COLUMN_OPTS_TYPE_LIST.EDIT]) && colAttrConf.tbColumnOptsTypeMethodsList[TABLE_COLUMN_OPTS_TYPE_LIST.EDIT](scope.row))"
                  type="primary"
                  text
                  size="small"
                  @click.stop="$emit('edit', { data: scope.row })"
                >
                  <el-icon><edit /></el-icon>&nbsp;编辑
                </el-button>
                <template v-if="colAttrConf.tbColumnOptsTypeList.includes(TABLE_COLUMN_OPTS_TYPE_LIST.DELETE)">
                  <el-button
                    type="primary"
                    text
                    size="small"
                    @click.stop="$emit('delete', { data: scope.row })"
                  >
                    <el-icon><delete /></el-icon>&nbsp;删除
                  </el-button>
                </template>

                <slot name="moreOpts" :row="scope.row"></slot>
              </div>
            </template>
            <template v-else>
              <template v-if="colAttrConf.formateMethodConf">
                <template v-if="colAttrConf?.formateMethodConf?.mode === 'needCurRowParams'">
                  <template v-if="colAttrConf?.formateMethodConf?.isShowAsHtml">
                    <div>
                      <component :is="colAttrConf.formateMethodConf.fn({ curRow: scope.row, curColumnVal: scope.row[scope.column.property]}, ...colAttrConf.formateMethodConf.params)"></component>
                    </div>
                  </template>
                  <template v-else>
                    {{ colAttrConf.formateMethodConf.fn({ curRow: scope.row, curColumnVal: scope.row[scope.column.property]}, ...colAttrConf.formateMethodConf.params) }}
                  </template>
                </template>
                <template v-else>
                  <template v-if="colAttrConf?.formateMethodConf?.isShowAsHtml">
                    <div>
                      <component :is="colAttrConf.formateMethodConf.fn(scope.row[scope.column.property], ...colAttrConf.formateMethodConf.params)"></component>
                    </div>
                  </template>
                  <template v-else>
                    {{ colAttrConf.formateMethodConf.fn(scope.row[scope.column.property], ...colAttrConf.formateMethodConf.params) }}
                  </template>
                </template>
              </template>
              <template v-else>
                {{ scope.row[scope.column.property] }}
              </template>
            </template>
          </template>
        </el-table-column>
      </template>
    </el-table>

    <el-pagination
      v-if="tbDataData.total > 0"
      background
      layout="total, sizes, prev, pager, next"
      :small="true"
      @size-change="handlePageSizeChange"
      @current-change="handleCurrentPageChange"
      :current-page="tbDataData.pageNo"
      :page-size="tbDataData.pageSize"
      :page-sizes="[DEFAULT_PAGE_SIZE, 20, 30, 40, 60]"
      :total="tbDataData.total"
      style="display: flex;justify-content: right;">
    </el-pagination>
  </div>
</template>

<script lang="ts">
import { defineComponent, nextTick, onMounted, ref } from 'vue'
import { DEFAULT_PAGE_SIZE } from '@/config/pagination'
import { isFunction, indexOfObjInObjArrByKey } from '@/utils'
// import Sortable from 'sortablejs'
import * as $_ from 'lodash'
export interface OPTS_EVENT_PROPS {
  data: any
}
export const TABLE_COLUMN_OPTS_TYPE_LIST = {
  DETAIL: Symbol('detail'),
  EDIT: Symbol('edit'),
  DELETE: Symbol('delete')
}

const DISABLE_SETTING_COLUMNS_PROP = ['$index', 'TABLE_COLUMN_OPTS']
export default defineComponent({
  props: {
    tbHead: {
      type: Array,
      default: () => {
        return []
      }
    },
    tbData: {
      type: Object,
      default: () => {
        return {
          data: [],
          total: 0,
          pageNo: 1,
          pageSize: DEFAULT_PAGE_SIZE
        }
      }
    },
    // 是否有表格嵌套
    hasExpand: {
      type: Boolean,
      default: false
    },
    tbSize: {
      type: String,
      default: 'default'
    },
    styleConf: {
      type: Object,
      default: () => {
        return {
          height: null,
          maxHeight: null
        }
      }
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  emits: [
    'detail',
    'edit',
    'delete',
    'size-change',
    'current-change',
    'check-box-group-change',
    'check-box-select-all',
    'check-box-select',
    'row-click',
    'setting-column'
  ],
  setup (props, { emit }) {
    const elTableRef = ref()
    const checkList = ref([])
    const tbHeadData = ref<any[]>(props.tbHead)
    const tbDataData = ref(props.tbData)
    const tbHeadDataOriginCopy = ref($_.clone(tbHeadData.value))
    const tbColumnSettingPopoverVisible = ref(false)
    const tbColumnSettingCheckList = ref(tbHeadDataOriginCopy.value.filter((colAttrConf: any) => {
      return !DISABLE_SETTING_COLUMNS_PROP.includes(colAttrConf.prop)
    }))
    // 每页数据显示条数变化响应处理
    const handlePageSizeChange = async (size: number) => {
      emit('size-change', { size })
    }
    const handleCurrentPageChange = async (page: number) => {
      emit('current-change', { page })
    }
    const cellClass = (row: any) => {
      const idx = indexOfObjInObjArrByKey({ prop: 'TABLE_COLUMN_CHECKBOX' }, (tbHeadData.value as any), 'prop')
      if (idx !== -1) {
        if ((tbHeadData.value as any)[idx].disabledCheckAll === true && row.columnIndex === 0) {
          return 'disabledCheckAll'
        }
      }
    }
    const handleSelectionChange = (list: any) => {
      checkList.value = list
      emit('check-box-group-change', checkList.value)
    }
    const handleSelectionSelectAll = () => {
      emit('check-box-select-all', checkList.value)
    }
    const handleSelectionSelect = (list: any, row: any) => {
      nextTick(() => {
        emit('check-box-select', checkList.value, row)
      })
    }
    const handleRowClick = (row: any) => {
      emit('row-click', row)
    }

    // 拖拽调整表格column顺序
    onMounted(() => {
      nextTick(() => {
        // setSort()
      })
    })

    // const setSort = () => {
    //   const el = [...elTableRef.value.$el.querySelectorAll('.editable-table .el-table__header-wrapper > table.el-table__header > thead > tr')].pop() as HTMLElement
    //   Sortable.create(el, {
    //     ghostClass: 'sortable-ghost', // Class name for the drop placeholder
    //     onEnd: (evt: any) => {
    //       // TODO 开启el-table expend后会自动生成一个切换下级的column造成tbHeadData.value数据操作走位！！！！！！
    //       if (typeof (evt.oldIndex) !== 'undefined' && typeof (evt.newIndex) !== 'undefined') {
    //         const targetTbHeadRow = $_.clone(tbHeadData.value[evt.oldIndex])
    //         tbHeadData.value.splice(evt.oldIndex, 1)
    //         tbHeadData.value.splice(evt.newIndex, 0, targetTbHeadRow)
    //         const tbHeadDataCopy = $_.clone(tbHeadData.value)
    //         tbHeadData.value = []
    //         nextTick(() => {
    //           tbHeadData.value = tbHeadDataCopy
    //           tbHeadDataOriginCopy.value = tbHeadDataCopy
    //         })
    //       }
    //     }
    //   })
    // }

    const hideTbColumnSettingPopover = (evt: any) => {
      if (!tbColumnSettingPopoverVisible.value) {
        return
      }
      if (evt.toElement.className.indexOf('el-popper__arrow') === -1) {
        tbColumnSettingPopoverVisible.value = false
      }
    }

    const handleTbColumnSettingChange = () => {
      tbHeadData.value = tbHeadDataOriginCopy.value.filter((colAttrConf: any) => {
        const idx = indexOfObjInObjArrByKey(colAttrConf, (tbColumnSettingCheckList.value as any), 'prop')
        return idx !== -1 || DISABLE_SETTING_COLUMNS_PROP.includes(colAttrConf.prop)
      })
    }

    const handleTriggleTbColumnSettingPopoverVisible = () => {
      tbColumnSettingPopoverVisible.value = !tbColumnSettingPopoverVisible.value
    }

    return {
      DISABLE_SETTING_COLUMNS_PROP,
      tbHeadData,
      tbDataData,
      tbHeadDataOriginCopy,
      tbColumnSettingPopoverVisible,
      tbColumnSettingCheckList,
      isFunction,
      elTableRef,
      checkList,
      TABLE_COLUMN_OPTS_TYPE_LIST,
      DEFAULT_PAGE_SIZE,
      handlePageSizeChange,
      handleCurrentPageChange,
      cellClass,
      handleSelectionChange,
      handleSelectionSelectAll,
      handleSelectionSelect,
      handleRowClick,
      hideTbColumnSettingPopover,
      handleTbColumnSettingChange,
      handleTriggleTbColumnSettingPopoverVisible
    }
  }
})
</script>
<style lang="scss" scoped>
@import '~@/assets/style/variable.scss';
.editable-table {
  width: 100%;
  max-width: 100%;
  :deep(th.el-table__cell) {
    // cursor: move;
    // &::before {
    //   position: absolute;
    //   top: 50%;
    //   right: 0;
    //   width: 1px;
    //   height: 1.6em;
    //   background-color: rgba(0,0,0,.06);
    //   transform: translateY(-50%);
    //   transition: background-color .3s;
    //   content: "";
    // }
    .cell {
      display: flex;
      align-items: center;
      color: rgba(0,0,0,.65);
      font-size: 14px;
      font-weight: 500;
      overflow: hidden;
      // .col-hd-tit {
      //   overflow: hidden;
      //   text-overflow: ellipsis;
      //   white-space: nowrap;
      // }
      .col-hd-tit {
        font-weight: 600;
      }
      .el-icon {
        margin-left: 4px;
      }
    }
  }
  :deep(td.el-table__cell) {
    .cell {
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      white-space: normal !important;
      word-wrap: break-word;
    }
  }
  .tb-column-opts {
    display: flex;
    align-items: center;
    :deep(.el-button + .el-button) {
      position: relative;
      margin-left: 0;
      &::before {
        position: absolute;
        left: 0;
        content: '';
        width: 1px;
        height: 10px;
        background: $lightGray;
      }
    }
  }
  :deep(.disabledCheckAll) {
    .el-checkbox__inner {
      display: none;
    }
  }
  :deep(.el-pagination) {
    margin: 10px 0;
  }
}
</style>
