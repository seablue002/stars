<template>
  <div class="nt-editable-label-tree">
    <NTEditableTree
      ref="editableTreeRef"
      v-loading="treeIsLoading"
      :treeData="treeData"
      @add-category="handleOpenAddDialog"
      @edit-category="handleOpenEditDialog"
      @delete-category="handleDeleteLabel"
      @select-row="handleSelectRow"
    >
    </NTEditableTree>

    <!-- S 标签添加、修改弹窗 -->
    <component
      :is="EditableLabelDialogAsyncComp"
      ref="editableLabelDialogAsyncCompRef"
      @add-success="handleLabelAddSuccess"
      @edit-success="handleLabelEditSuccess"
    ></component>
    <!-- E 标签添加、修改弹窗 -->
  </div>
</template>
<script>
import { defineComponent, ref, onMounted } from 'vue'
import useCurrentInstance from '@/hooks/business/useCurrentInstance'
import { ROOT_NODE_PID } from '@/components/NTEditableTree/index.vue'
import useAsyncComponent from '@/hooks/useAsyncComponent'

export default defineComponent({
  name: 'NTEditableLabelTree',
  props: {
    availableIsLastVal: {
      type: Array,
      default: () => [0, 1, 2],
    },
  },
  emits: ['init-label-list', 'select-row', 'delete-success'],
  setup(props, { emit }) {
    const { $api, $apiCode, $message, $tree } = useCurrentInstance()
    const { getTree, formateTree } = $tree

    const editableTreeRef = ref()
    const treeIsLoading = ref(false)

    // 标签数据列表
    const treeData = ref([])
    const treeDataKeyMap = {
      id: 'id',
      label: 'name',
      pid: 'pid',
      children: 'children',
      pids: 'pids',
    }
    const getLabelList = async () => {
      treeIsLoading.value = true
      const apiRes = await $api.label.labelListApi()
      const { status, data, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
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
            name: '全部标签',
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
      initLabelList()
    })

    const initLabelList = async () => {
      const labelList = await getLabelList()

      emit('init-label-list', labelList)
    }

    // 删除
    const handleDeleteLabel = ({ data, selectedId }) => {
      deleteLabel(data.id, selectedId)
    }

    const deleteLabel = async (id, selectedId) => {
      const params = {
        id,
      }
      const apiRes = await $api.label.deleteLabelApi(params)
      const { status, message } = apiRes.data
      if (status === $apiCode.SUCCESS) {
        if (selectedId === id) {
          initLabelList()
        } else {
          getLabelList()
        }
        emit('delete-success', { isSelected: selectedId === id })
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

    const labelTreeMode = ref('add')
    const handleOpenAddDialog = ({ data }) => {
      labelTreeMode.value = 'add'
      let pids = data?.pids?.split(',')
      pids = pids.map((pid) => {
        return Number(pid)
      })
      pids.push(data.id)

      handleShowEditableLabelDialog(pids)
    }
    const handleOpenEditDialog = async ({ data }) => {
      labelTreeMode.value = 'edit'
      let pids = data?.pids?.split(',')
      pids = pids?.map((pid) => {
        return Number(pid)
      })
      pids.push(data.id)

      handleShowEditableLabelDialog(pids)
    }

    // 标签添加、修改弹窗
    const editableLabelDialogAsyncCompRef = ref(null)
    let editableLabelDialogAsyncCompTrigger = null
    const { AsyncComponent: EditableLabelDialogAsyncComp } = useAsyncComponent({
      component: () =>
        import('@/components/business/NTEditableLabelDialog/index.vue'),
      wait: async () => {
        await new Promise((resolve) => {
          editableLabelDialogAsyncCompTrigger = resolve
        })
      },
    })

    const handleShowEditableLabelDialog = (labelPids) => {
      editableLabelDialogAsyncCompTrigger()
      setTimeout(() => {
        editableLabelDialogAsyncCompRef.value.open({
          mode: labelTreeMode.value,
          labelPids,
          labelTree: treeData.value,
        })
      }, 16)
    }

    const handleLabelAddSuccess = () => {
      getLabelList()
    }

    const handleLabelEditSuccess = () => {
      getLabelList()
    }

    return {
      editableTreeRef,
      treeData,
      treeIsLoading,
      handleDeleteLabel,
      handleSelectRow,
      handleOpenAddDialog,
      handleOpenEditDialog,
      editableLabelDialogAsyncCompRef,
      EditableLabelDialogAsyncComp,
      handleLabelAddSuccess,
      handleLabelEditSuccess,
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
.nt-editable-label-tree {
  padding: 10px 15px;

  .line {
    text-align: center;
  }
}
</style>
