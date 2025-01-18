<template>
  <el-dialog
    v-model="dialogFormVisible"
    :title="title"
    width="1000px"
    :lock-scroll="lockScroll"
    @close="$emit('close')"
  >
    <slot></slot>
    <editable-table
      ref="editableTableRef"
      :loading="loading"
      :tbHead="tbHead"
      :tbData="tbData"
      :styleConf="styleConf"
      @page-change="handleCurrentPageChange"
      @page-size-change="handlePageSizeChange"
      @row-click="handleSelectRow"
    >
    </editable-table>
  </el-dialog>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import { DEFAULT_PAGE_SIZE } from '@/config/pagination'
import EditableTable from './EditableTable.vue'

export default defineComponent({
  components: {
    EditableTable
  },
  emits: [
    'close',
    'page-change',
    'page-size-change',
    'select-row'
  ],
  props: {
    lockScroll: {
      type: Boolean,
      dafault: true
    },
    title: {
      type: String,
      default: ''
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
  setup (props, { emit }) {
    const editableTableRef = ref()
    const dialogFormVisible = ref(false)

    const handleCurrentPageChange = (evtParams: any) => {
      emit('page-change', evtParams)
    }

    const handlePageSizeChange = (evtParams: any) => {
      emit('page-size-change', evtParams)
    }

    const handleSelectRow = (row: any) => {
      emit('select-row', row)
    }
 
    return {
      editableTableRef,
      dialogFormVisible,
      handleCurrentPageChange,
      handlePageSizeChange,
      handleSelectRow
    }
  }
})
</script>
<style lang="scss" scoped>
:deep(.el-dialog) {
  height: auto;
  padding-bottom: 30px;
}
</style>