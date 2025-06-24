<template>
  <div class="nt-editable-column-tree">
    <NTEditableTree
      ref="editableTreeRef"
      v-loading="treeIsLoading"
      :treeData="treeData"
      @add-category="handleOpenAddDialog"
      @edit-category="handleOpenEditDialog"
      @delete-category="handleDeleteColumn"
      @select-row="handleSelectRow"
    >
      <template #otherOperate="{ data }">
        <el-icon
          v-if="data.pid !== ROOT_NODE_PID"
          title="清除缓存"
          class="tree-node__icon"
          @click.stop="handleDeleteColumnCache(data)"
        >
          <Brush />
        </el-icon>
      </template>
    </NTEditableTree>

    <!-- S 栏目添加、修改弹窗 -->
    <component
      :is="EditableColumnDialogAsyncComp"
      ref="editableColumnDialogAsyncCompRef"
      @add-success="handleColumnAddSuccess"
      @edit-success="handleColumnEditSuccess"
    ></component>
    <!-- E 栏目添加、修改弹窗 -->
  </div>
</template>
<script>
import { defineComponent, ref, onMounted } from 'vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import { ROOT_NODE_PID } from '@/components/NTEditableTree/index.vue'
import useAsyncComponent from '@/hooks/useAsyncComponent'

export default defineComponent({
  name: 'NTEditableColumnTree',
  props: {
    availableIsLastVal: {
      type: Array,
      default: () => [0, 1, 2],
    },
  },
  emits: ['init-column-list', 'select-row', 'delete-success'],
  setup(props, { emit }) {
    const { $api, $apiCode, $message, $tree, $confirm } = useCurrentInstance()
    const { getTree, formateTree } = $tree

    const editableTreeRef = ref()
    const treeIsLoading = ref(false)

    // 栏目数据列表
    const treeData = ref([])
    const treeDataKeyMap = {
      id: 'id',
      label: 'name',
      pid: 'pid',
      children: 'children',
      pids: 'pids',
    }
    const getColumnList = async () => {
      treeIsLoading.value = true
      const apiRes = await $api.column.columnListApi()
      const { status, data: originData, message } = apiRes.data
      let data = []
      if (status === $apiCode.SUCCESS) {
        data = originData.filter((column) => {
          return props.availableIsLastVal.includes(column.is_last)
        })
        treeData.value = []
        const treeArr = []
        getTree(treeArr, data, {
          id: 'id',
          pid: 'pid',
          name: 'name',
        })
        const allTreeNodeData = [
          {
            id: 0,
            pid: ROOT_NODE_PID,
            name: '全部栏目',
            children: treeArr || [],
            pids: '',
          },
        ]

        formateTree(treeData.value, allTreeNodeData, treeDataKeyMap)
      } else {
        $message({
          showClose: true,
          message,
          type: 'error',
          duration: 3000,
        })
      }
      treeIsLoading.value = false
      return data
    }

    onMounted(() => {
      initColumnList()
    })

    const initColumnList = async () => {
      const columnList = await getColumnList()

      emit('init-column-list', columnList)
    }

    // 删除
    const handleDeleteColumn = ({ data, selectedId }) => {
      deleteColumn(data.id, selectedId)
    }

    const deleteColumn = async (id, selectedId) => {
      const params = {
        id,
      }
      const apiRes = await $api.column.deleteColumnApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        if (selectedId === id) {
          initColumnList()
        } else {
          getColumnList()
        }
        emit('delete-success', { isSelected: selectedId === id })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 清除栏目缓存
    const handleDeleteColumnCache = async (data) => {
      $confirm('确定要清除当前栏目及其子栏目下所有的信息缓存吗？', '提示', {
        type: 'warning',
      })
        .then(() => {
          deleteColumnCache(data.id)
        })
        .catch(() => {})
    }

    const deleteColumnCache = async (id) => {
      const params = {
        id,
      }
      const apiRes = await $api.column.deleteColumnCacheApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        $message.success({
          message: '清除栏目缓存成功',
          duration: 3000,
        })
      } else {
        $message.warning({
          message,
          duration: 3000,
        })
      }
    }

    // 选择
    const handleSelectRow = (data) => {
      emit('select-row', data)
    }

    const columnTreeMode = ref('add')
    const handleOpenAddDialog = ({ data }) => {
      columnTreeMode.value = 'add'
      let pids = data?.pids?.split(',')
      pids = pids.map((pid) => {
        return Number(pid)
      })
      pids.push(data.id)

      handleShowEditableColumnDialog(pids)
    }
    const handleOpenEditDialog = async ({ data }) => {
      columnTreeMode.value = 'edit'
      let pids = data?.pids?.split(',')
      pids = pids?.map((pid) => {
        return Number(pid)
      })
      pids.push(data.id)

      handleShowEditableColumnDialog(pids)
    }

    // 栏目添加、修改弹窗
    const editableColumnDialogAsyncCompRef = ref(null)
    let editableColumnDialogAsyncCompTrigger = null
    const { AsyncComponent: EditableColumnDialogAsyncComp } = useAsyncComponent(
      {
        component: () =>
          import('@/components/business/NTEditableColumnDialog/index.vue'),
        wait: async () => {
          await new Promise((resolve) => {
            editableColumnDialogAsyncCompTrigger = resolve
          })
        },
      }
    )

    const handleShowEditableColumnDialog = (columnPids) => {
      editableColumnDialogAsyncCompTrigger()
      setTimeout(() => {
        editableColumnDialogAsyncCompRef.value.open({
          mode: columnTreeMode.value,
          columnPids,
          columnTree: treeData.value,
        })
      }, 16)
    }

    const handleColumnAddSuccess = () => {
      getColumnList()
    }

    const handleColumnEditSuccess = () => {
      getColumnList()
    }

    return {
      ROOT_NODE_PID,
      editableTreeRef,
      treeData,
      treeIsLoading,
      handleDeleteColumn,
      handleDeleteColumnCache,
      handleSelectRow,
      handleOpenAddDialog,
      handleOpenEditDialog,
      editableColumnDialogAsyncCompRef,
      EditableColumnDialogAsyncComp,
      handleColumnAddSuccess,
      handleColumnEditSuccess,
    }
  },
})
</script>
<style lang="scss" scoped>
:deep(.el-form-item) {
  &.is-error {
    .el-col-11:first-child {
      .el-input__wrapper {
        box-shadow: 0 0 0 1px
          var(--el-input-border-color, var(--el-border-color)) inset;
      }
    }
  }
}
.nt-editable-column-tree {
  padding: 10px 15px;

  .line {
    text-align: center;
  }
}
</style>
