<template>
  <el-dialog
    v-model="dialogFormVisible"
    :title="title"
    :lock-scroll="lockScroll"
    @close="$emit('close')"
  >
    <editable-tree
      ref="categoryTree"
      :treeData="treeData"
      :defaultTreeProps="defaultTreeProps"
      @label-name-change="handleLabelNameChange"
      @label-name-add="handleLabelNameAdd"
      @delete-category="handleDeleteCategory"
      @select-row="handleSelectCategory"
    >
    </editable-tree>
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取消</el-button>
        <el-button type="primary" :loading="submitLoading" @click.stop="handleSubmit">选中</el-button>
      </span>
    </template>
  </el-dialog>
</template>

<script lang="ts">
import { defineComponent, PropType, ref } from 'vue'
import EditableTree from './EditableTree.vue'
import { TreeProps } from '@/components/EditableTree.vue'

export default defineComponent({
  components: {
    EditableTree
  },
  emits: [
    'close',
    'label-name-change',
    'label-name-add',
    'delete-category',
    'select-row',
    'submit'
  ],
  props: {
    lockScroll: {
      type: Boolean,
      dafault: true
    },
    styleConf: {
      type: Object,
      default: () => {
        return {
          width: '600px'
        }
      }
    },
    title: {
      type: String,
      default: ''
    },
    defaultTreeProps: {
      type: Object,
      default: () => {
        return {}
      }
    },
    treeData: {
      type: Array as PropType<TreeProps[]>,
      default: () => {
        return []
      }
    }
  },
  setup (props, { emit }) {
    const categoryTree = ref()
    const dialogFormVisible = ref(false)
    const submitLoading = ref(false)

    const handleLabelNameChange = async (evtParams: { node: Node, data: TreeProps }) => {
      emit('label-name-change', evtParams)
    }

    const handleLabelNameAdd = async (evtParams: { label: string, pid: string }) => {
      emit('label-name-add', evtParams)
    }

    const handleDeleteCategory = async (evtParams: { data: TreeProps }) => {
      emit('delete-category', evtParams)
    }

    const curRowData = ref<TreeProps>()
    const handleSelectCategory = async (evtParams: TreeProps) => {
      curRowData.value = evtParams
      emit('select-row', evtParams)
    }

    const handleSubmit = () => {
      emit('submit', curRowData)
    }
 
    return {
      categoryTree,
      dialogFormVisible,
      submitLoading,
      handleLabelNameChange,
      handleLabelNameAdd,
      handleDeleteCategory,
      handleSelectCategory,
      handleSubmit
    }
  }
})
</script>
<style lang="scss" scoped>
</style>