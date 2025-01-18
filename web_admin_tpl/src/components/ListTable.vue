<template>
  <div class="list-table">
    <slot name="head"></slot>
    <el-card shadow="never" class="box-card">
      <template #header>
        <div class="card-header">
          <span>{{title}}</span>
          <div class="card-header__toolbar">
            <el-space :size="12">
              <slot name="opts"></slot>
              <el-space class="more-opts" :size="12">
                <el-tooltip content="密度" placement="top" effect="light">
                  <el-dropdown trigger="click" @command="handleSetTbSize">
                    <span class="el-dropdown-link">
                      <el-button link>
                        <el-icon :size="14">
                          <svg viewBox="64 64 896 896" focusable="false" data-icon="column-height" width="1em" height="1em" fill="currentColor" aria-hidden="true"><path d="M840 836H184c-4.4 0-8 3.6-8 8v60c0 4.4 3.6 8 8 8h656c4.4 0 8-3.6 8-8v-60c0-4.4-3.6-8-8-8zm0-724H184c-4.4 0-8 3.6-8 8v60c0 4.4 3.6 8 8 8h656c4.4 0 8-3.6 8-8v-60c0-4.4-3.6-8-8-8zM610.8 378c6 0 9.4-7 5.7-11.7L515.7 238.7a7.14 7.14 0 00-11.3 0L403.6 366.3a7.23 7.23 0 005.7 11.7H476v268h-62.8c-6 0-9.4 7-5.7 11.7l100.8 127.5c2.9 3.7 8.5 3.7 11.3 0l100.8-127.5c3.7-4.7.4-11.7-5.7-11.7H548V378h62.8z"></path></svg>
                        </el-icon>
                      </el-button>
                    </span>
                    <template #dropdown>
                      <el-dropdown-menu>
                        <el-dropdown-item command="default" :class="{'on': tbSize === 'default'}">默认</el-dropdown-item>
                        <el-dropdown-item command="large" :class="{'on': tbSize === 'large'}">宽松</el-dropdown-item>
                        <el-dropdown-item command="small" :class="{'on': tbSize === 'small'}">紧凑</el-dropdown-item>
                      </el-dropdown-menu>
                    </template>
                  </el-dropdown>
                </el-tooltip>
                <el-tooltip content="刷新" placement="top" effect="light">
                  <el-button icon="refreshRight" link @click="$emit('refresh')"></el-button>
                </el-tooltip>
              </el-space>
            </el-space>
          </div>
        </div>
      </template>
      <EditableTable
        ref="editableTable"
        :tbData="tbData"
        :tbHead="tbHead"
        :hasExpand="hasExpand"
        :tbSize="tbSize"
        :loading="tbIsLoading"
        @edit="handleEdit"
        @detail="handleDetail"
        @delete="handleDelete"
        @size-change="handlePageSizeChange"
        @current-change="handleCurrentPageChange"
        @check-box-select="handleSelectionSelect"
        @check-box-group-change="handleCheckBoxGroupChange">
        <template #expand="slotScope">
          <slot name="expand" :data="slotScope.data"></slot>
        </template>
        <template #moreOpts="slotScope">
          <slot name="moreOpts" :data="slotScope.row"></slot>
        </template>
      </EditableTable>
    </el-card>
  </div>
</template>
<script lang="ts">
import { defineComponent, ref } from 'vue'
import EditableTable from '@/components/EditableTable.vue'
import { DEFAULT_PAGE_SIZE } from '@/config/pagination'
export default defineComponent({
  components: {
    EditableTable
  },
  emits: [
    'refresh',
    'size-change',
    'current-change',
    'check-box-select',
    'check-box-group-change',
    'edit',
    'detail',
    'delete'
  ],
  props: {
    title: {
      type: String,
      default: '列表'
    },
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
    tbIsLoading: {
      type: Boolean,
      default: false
    }
  },
  setup (props, { emit }) {
    const editableTable = ref()
    // 表格密度调整
    const tbSize = ref('large')
    const handleSetTbSize = (size: string) => {
      tbSize.value = size
    }
    // 每页数据显示条数变化响应处理
    const handlePageSizeChange = async ({ size }: {size: number}) => {
      emit('size-change', { size })
    }
    const handleCurrentPageChange = async ({ page }: {page: number}) => {
      emit('current-change', { page })
    }
    const handleCheckBoxGroupChange = (list: any) => {
      emit('check-box-group-change', list)
    }
    const handleSelectionSelect = (list: any, row: any) => {
      emit('check-box-select', list, row)
    }

    // 编辑
    const handleEdit = (data: any) => {
      emit('edit', data)
    }

    // 详情
    const handleDetail = (data: any) => {
      emit('detail', data)
    }

    // 删除
    const handleDelete = (data: any) => {
      emit('delete', data)
    }
    return {
      editableTable,
      tbSize,
      handleSetTbSize,
      handlePageSizeChange,
      handleCurrentPageChange,
      handleSelectionSelect,
      handleCheckBoxGroupChange,
      handleEdit,
      handleDetail,
      handleDelete
    }
  }
})
</script>
<style lang="scss" scoped>
.el-dropdown-menu {
  :deep(.el-dropdown-menu__item.on) {
    background-color: var(--el-dropdown-menuItem-hover-fill);
    color: var(--el-dropdown-menuItem-hover-color);
  }
}
</style>
