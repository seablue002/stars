<template>
  <div class="system-config-list">
    <ListTable
      :title="title"
      :tbHead="tbHead"
      :tbData="tbData"
      :tbIsLoading="tbIsLoading"
      @refresh="$emit('refresh')"
      @edit="handleEdit"
      @detail="handleDetail"
      @delete="handleDelete"
      @size-change="handlePageSizeChange"
      @current-change="handleCurrentPageChange">
      <template #head>
        <slot name="head"></slot>
      </template>
      <template #opts>
        <slot name="opts"></slot>
      </template>
      <template #expand="slotScope">
        <slot name="expand" :data="slotScope.data"></slot>
      </template>
      <template #moreOpts="slotScope">
        <slot name="moreOpts" :data="slotScope.data"></slot>
      </template>
    </ListTable>
  </div>
</template>
<script lang="ts">
import { defineComponent } from 'vue'
import { DEFAULT_PAGE_SIZE } from '@/config/pagination'
import ListTable from '../ListTable.vue'
export default defineComponent({
  components: {
    ListTable
  },
  emits: [
    'refresh',
    'size-change',
    'current-change',
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
    tbIsLoading: {
      type: Boolean,
      default: false
    }
  },
  setup (props, { emit }) {
    const handlePageSizeChange = async ({ size }: {size: number}) => {
      emit('size-change', { size })
    }
    const handleCurrentPageChange = async ({ page }: {page: number}) => {
      emit('current-change', { page })
    }
    const handleEdit = (data: any) => {
      emit('edit', data)
    }
    const handleDetail = (data: any) => {
      emit('detail', data)
    }
    // 删除
    const handleDelete = (data: any) => {
      emit('delete', data)
    }
    return {
      handlePageSizeChange,
      handleCurrentPageChange,
      handleEdit,
      handleDetail,
      handleDelete
    }
  }
})
</script>
<style lang="scss" scoped>
.system-config-list {
  :deep(.list-table) {
    .el-card {
      border-bottom: 0;
      border-left: 0;
      border-right: 0;
      .el-card__body {
        .el-table__inner-wrapper {
          &::before {
            height: 0;
          }
        }
      }
    }
  }
}
</style>
