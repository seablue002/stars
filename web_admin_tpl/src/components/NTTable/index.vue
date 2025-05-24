<!--
 * 表格组件二次封装
-->
<template>
  <div class="nt-table" :class="{ 'nt-table--pagination': pagination.isShow }">
    <el-table
      ref="elTableRef"
      v-bind="$attrs"
      :header-cell-style="{ height: '50px', background: '#fafafa' }"
    >
      <!-- S 表格展开行插槽 -->
      <el-table-column v-if="expand" type="expand">
        <template #default="{ $row }">
          <slot name="expand" :row="$row"></slot>
        </template>
      </el-table-column>
      <!-- E 表格展开行插槽 -->

      <!-- S 需要调用el-table-column组件时 -->
      <slot></slot>
      <!-- S 需要调用el-table-column组件时 -->

      <el-table-column
        v-for="column in columns"
        :key="column.prop"
        v-bind="{ ...tableColumnOptions }"
        :type="column.prop === 'TABLE_COLUMN_CHECKBOX' ? 'selection' : ''"
        :prop="column.prop"
        :fixed="column.fixed"
        :width="column.width"
        :min-width="column.minWidth"
        :sortable="column.sortable"
        :show-overflow-tooltip="column.overflowTooltip"
      >
        <!-- S 表头单元格 -->
        <template #header>
          <template v-if="column?.tableHeaderConfig?.isShowTips">
            <span>{{ column.label }}</span>
            <el-tooltip
              :content="column.tableHeaderConfig.tipsText"
              placement="top"
              effect="light"
            >
              <el-icon :size="16"><Warning /></el-icon>
            </el-tooltip>
          </template>
          <span v-else>{{ column.label }}</span>
        </template>
        <!-- E 表头单元格 -->

        <!-- S 表body单元格 -->
        <template
          v-if="column.prop !== 'TABLE_COLUMN_CHECKBOX'"
          #default="{ $index, row: $row }"
        >
          <!-- S 类型1、展示索引数据 -->
          <template v-if="column.prop === '$index'">
            <span>{{ $index + 1 }}</span>
          </template>
          <!-- E 类型1、展示索引 -->

          <!-- S 类型2、操作栏按钮展示 -->
          <template v-else-if="column.prop === 'TABLE_COLUMN_OPTS'">
            <div class="operate-btns">
              <el-button
                v-if="
                  column.operateList &&
                  isObject(column.operateList[TABLE_COLUMN_OPERATE.DETAIL])
                "
                type="primary"
                text
                size="small"
                @click.stop="handleClickDetail(column, $row)"
              >
                {{
                  column.operateList[TABLE_COLUMN_OPERATE.DETAIL].btnText
                    ? column.operateList[TABLE_COLUMN_OPERATE.DETAIL].btnText
                    : '详情'
                }}
              </el-button>
              <el-button
                v-if="
                  column.operateList &&
                  isObject(column.operateList[TABLE_COLUMN_OPERATE.EDIT])
                "
                type="primary"
                text
                size="small"
                @click.stop="handleClickEdit(column, $row)"
              >
                {{
                  column.operateList[TABLE_COLUMN_OPERATE.EDIT].btnText
                    ? column.operateList[TABLE_COLUMN_OPERATE.EDIT].btnText
                    : '编辑'
                }}
              </el-button>

              <el-button
                v-if="
                  column.operateList &&
                  isObject(column.operateList[TABLE_COLUMN_OPERATE.DELETE])
                "
                type="primary"
                text
                size="small"
                @click.stop="handleClickDelete(column, $row)"
              >
                {{
                  column.operateList[TABLE_COLUMN_OPERATE.DELETE].btnText
                    ? column.operateList[TABLE_COLUMN_OPERATE.DELETE].btnText
                    : '删除'
                }}
              </el-button>

              <slot name="otherOperate" :row="$row" :index="$index"></slot>
            </div>
          </template>
          <!-- E 类型3、操作栏按钮展示 -->

          <!-- S 类型3、展示数据 -->
          <template v-else>
            <template v-if="column.dataFormatConf">
              <template v-if="column?.dataFormatConf?.withScopeRow === true">
                <template v-if="column?.dataFormatConf?.renderType === 'html'">
                  <component
                    :is="
                      column.dataFormatConf.formatFunction(
                        {
                          row: $row,
                          value: $row[column.prop],
                        },
                        ...(column.dataFormatConf.params
                          ? column.dataFormatConf.params
                          : [])
                      )
                    "
                  >
                  </component>
                </template>
                <template v-else>
                  {{
                    column.dataFormatConf.formatFunction(
                      {
                        row: $row,
                        value: $row[column.prop],
                      },
                      ...(column.dataFormatConf.params
                        ? column.dataFormatConf.params
                        : [])
                    )
                  }}
                </template>
              </template>
              <template v-else>
                <template v-if="column?.dataFormatConf?.renderType === 'html'">
                  <component
                    :is="
                      column.dataFormatConf.formatFunction(
                        $row[column.prop],
                        ...(column.dataFormatConf.params
                          ? column.dataFormatConf.params
                          : [])
                      )
                    "
                  >
                  </component>
                </template>
                <template v-else>
                  {{
                    column.dataFormatConf.formatFunction(
                      $row[column.prop],
                      ...(column.dataFormatConf.params
                        ? column.dataFormatConf.params
                        : [])
                    )
                  }}
                </template>
              </template>
            </template>
            <template v-else>
              {{ $row[column.prop] }}
            </template>
          </template>
          <!-- E 类型3、展示数据 -->
        </template>
        <!-- E 表body单元格 -->
      </el-table-column>

      <template #empty>
        <slot name="empty"></slot>
      </template>
    </el-table>

    <el-pagination
      v-if="pagination.isShow !== false"
      v-model:current-page="currentPage"
      v-model:page-size="pageSize"
      :page-sizes="PAGE_SIZES"
      :small="pagination.small"
      :background="true"
      :layout="
        pagination.layout
          ? pagination.layout
          : 'total, sizes, prev, pager, next, jumper'
      "
      :total="pagination.total || 0"
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
    />
  </div>
</template>
<script>
import { ref, getCurrentInstance } from 'vue'
import { PAGE_SIZES } from '@/settings/config/app'

export const TABLE_COLUMN_OPERATE = {
  DETAIL: Symbol('detail'),
  EDIT: Symbol('edit'),
  DELETE: Symbol('delete'),
}
export default {
  name: 'NTTable',
  props: {
    // 表格列table-column属性
    tableColumnOptions: {
      type: Object,
      default: () => ({}),
    },
    // 是否有表格展开行
    expand: {
      type: Boolean,
      default: false,
    },
    // 表格columns集合，用于指定如何生成表格栏table-column
    columns: {
      type: Array,
      default: () => [],
    },
    pagination: {
      type: Object,
      default: () => ({
        isShow: true,
        total: 0,
      }),
    },
  },
  emits: ['page-change', 'size-change', 'detail', 'edit', 'delete'],
  setup(props, { emit }) {
    const { proxy } = getCurrentInstance()
    const {
      $is: { isFunction, isObject },
    } = proxy

    const elTableRef = ref(null)

    // 分页相关操作
    // 当前页码
    const currentPage = ref(1)
    // 页码变化
    const handleCurrentChange = (page) => {
      currentPage.value = page
      emit('page-change', page)
    }

    // 每页条数
    const pageSize = ref(PAGE_SIZES[0])
    // 每页条数选项变化
    const handleSizeChange = (size) => {
      pageSize.value = size
      currentPage.value = 1
      emit('size-change', size)
    }

    // 详情按钮点击
    const handleClickDetail = (column, $row) => {
      if (isFunction(column.operateList[TABLE_COLUMN_OPERATE.DETAIL].action)) {
        column.operateList[TABLE_COLUMN_OPERATE.DETAIL].action($row)
      } else {
        emit('detail', { data: $row })
      }
    }

    // 编辑按钮点击
    const handleClickEdit = (column, $row) => {
      if (isFunction(column.operateList[TABLE_COLUMN_OPERATE.EDIT].action)) {
        column.operateList[TABLE_COLUMN_OPERATE.EDIT].action($row)
      } else {
        emit('edit', { data: $row })
      }
    }

    // 删除按钮点击
    const handleClickDelete = (column, $row) => {
      if (isFunction(column.operateList[TABLE_COLUMN_OPERATE.DELETE].action)) {
        column.operateList[TABLE_COLUMN_OPERATE.DELETE].action($row)
      } else {
        emit('delete', { data: $row })
      }
    }

    return {
      isFunction,
      isObject,
      PAGE_SIZES,
      elTableRef,
      currentPage,
      handleCurrentChange,
      pageSize,
      handleSizeChange,
      TABLE_COLUMN_OPERATE,
      handleClickDetail,
      handleClickEdit,
      handleClickDelete,
    }
  },
}
</script>
<style lang="scss" scoped>
.nt-table {
  @apply px-[0] py-[16px];
  background: #fff;

  &.nt-table--pagination {
    @apply mb-[16px];
  }

  .operate-btns {
    :deep(.el-button) {
      padding: 5px;
    }
  }

  .el-pagination {
    @apply ml-[16px] mt-[12px];
  }
}
</style>
